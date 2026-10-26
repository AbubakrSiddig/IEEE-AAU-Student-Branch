<template>
  <div class="py-12 bg-slate-50 dark:bg-slate-900 min-h-screen transition-colors duration-200">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">
      
      <!-- Top Animated Slideshow / Hero Carousel (Requested by User) -->
      <div class="relative overflow-hidden rounded-3xl shadow-xl bg-slate-950 aspect-[16/9] sm:aspect-[21/9] border border-slate-800 group">
        <!-- Slides -->
        <div 
          v-for="(slide, index) in eventSlides" 
          :key="index"
          class="absolute inset-0 transition-opacity duration-700 ease-in-out"
          :class="currentSlide === index ? 'opacity-100 z-10' : 'opacity-0 z-0 pointer-events-none'"
        >
          <img 
            :src="slide.image" 
            :alt="slide.title" 
            class="w-full h-full object-cover object-center transform scale-100 group-hover:scale-105 transition-transform duration-700"
          />
          <!-- Gradient Overlay -->
          <div class="absolute inset-0 bg-gradient-to-t from-slate-950/95 via-slate-950/40 to-transparent flex flex-col justify-end p-6 sm:p-10 text-white space-y-2">
            <div class="flex items-center gap-2">
              <span class="px-2.5 py-1 rounded-md text-[10px] font-black uppercase tracking-wider bg-blue-600 text-white shadow-xs">
                {{ slide.badge }}
              </span>
              <span class="text-xs text-slate-300 font-medium flex items-center gap-1">
                <Calendar class="w-3.5 h-3.5" />
                {{ slide.date }}
              </span>
            </div>
            <h2 class="text-xl sm:text-3xl font-black text-white leading-tight drop-shadow-md">
              {{ slide.title }}
            </h2>
            <p class="text-xs sm:text-sm text-slate-200 max-w-2xl line-clamp-2 leading-relaxed">
              {{ slide.caption }}
            </p>
          </div>
        </div>

        <!-- Carousel Navigation Controls -->
        <button 
          @click="prevSlide" 
          aria-label="Previous Slide"
          class="absolute left-3 top-1/2 -translate-y-1/2 z-20 w-10 h-10 rounded-full bg-black/40 hover:bg-black/70 text-white backdrop-blur-sm flex items-center justify-center transition-all opacity-70 hover:opacity-100"
        >
          <ChevronLeft class="w-5 h-5" />
        </button>
        <button 
          @click="nextSlide" 
          aria-label="Next Slide"
          class="absolute right-3 top-1/2 -translate-y-1/2 z-20 w-10 h-10 rounded-full bg-black/40 hover:bg-black/70 text-white backdrop-blur-sm flex items-center justify-center transition-all opacity-70 hover:opacity-100"
        >
          <ChevronRight class="w-5 h-5" />
        </button>

        <!-- Carousel Dots Indicator -->
        <div class="absolute bottom-3 right-6 z-20 flex items-center gap-1.5">
          <button 
            v-for="(_, idx) in eventSlides" 
            :key="idx"
            @click="currentSlide = idx"
            class="h-2 rounded-full transition-all duration-300"
            :class="currentSlide === idx ? 'w-6 bg-blue-500' : 'w-2 bg-white/40 hover:bg-white/70'"
          ></button>
        </div>
      </div>

      <!-- Header & Search -->
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-slate-200 dark:border-slate-800 pb-6">
        <div>
          <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white">{{ t('news') }}</h1>
          <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
            {{ lang === 'ar' ? 'تابع أحدث البيانات والأنشطة وتغطيات الفعاليات لفرع IEEE AAU' : 'Stay up to date with branch announcements, workshops, and event recaps.' }}
          </p>
        </div>

        <!-- Search Bar -->
        <div class="relative w-full sm:w-72">
          <input 
            v-model="searchQuery" 
            type="text" 
            :placeholder="t('searchPlaceholder')" 
            class="w-full pl-10 pr-4 py-2.5 text-sm bg-white dark:bg-slate-800 text-slate-900 dark:text-white border border-slate-200 dark:border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent shadow-xs transition-colors"
          />
          <Search class="w-4 h-4 text-slate-400 absolute left-3 top-3.5" />
        </div>
      </div>

      <!-- Filter Pills (All, Announcements, Events, Achievements) -->
      <div class="flex flex-wrap items-center gap-2">
        <button 
          v-for="cat in categories" 
          :key="cat.id"
          @click="selectedCategory = cat.id"
          class="px-4 py-2 rounded-xl text-xs sm:text-sm font-semibold transition-all duration-150"
          :class="selectedCategory === cat.id 
            ? 'bg-blue-900 dark:bg-blue-600 text-white shadow-sm' 
            : 'bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700'"
        >
          {{ t(cat.key) }}
        </button>
      </div>

      <!-- News Items List -->
      <div class="space-y-4">
        <div 
          v-for="item in filteredNews" 
          :key="item.id"
          @click="selectedNews = item"
          class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-200 dark:border-slate-700 shadow-xs hover:shadow-md dark:hover:border-slate-600 transition-all duration-200 cursor-pointer flex flex-col sm:flex-row gap-5 items-start"
        >
          <img 
            :src="item.image" 
            :alt="item.title"
            class="w-full sm:w-48 h-36 object-cover rounded-xl flex-shrink-0 bg-slate-100 dark:bg-slate-700"
          />
          <div class="flex-1 space-y-2">
            <div class="flex items-center gap-3">
              <span class="text-xs font-bold text-blue-900 dark:text-blue-300 bg-blue-50 dark:bg-blue-900/40 px-2.5 py-0.5 rounded-md">
                {{ item.category }}
              </span>
              <span class="text-xs text-slate-400 flex items-center gap-1">
                <Calendar class="w-3.5 h-3.5" />
                {{ item.date || item.published_at || 'Recently' }}
              </span>
            </div>
            <h2 class="text-lg font-bold text-slate-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
              {{ item.title }}
            </h2>
            <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed line-clamp-2">
              {{ item.snippet }}
            </p>
            <div class="pt-2 flex items-center justify-between text-xs">
              <span class="text-slate-400 font-medium">{{ item.author }}</span>
              <span class="text-blue-900 dark:text-blue-400 font-semibold flex items-center gap-1">
                {{ lang === 'ar' ? 'التفاصيل الكاملة' : 'Read Full Coverage' }} &rarr;
              </span>
            </div>
          </div>
        </div>

        <div v-if="filteredNews.length === 0" class="text-center py-16 bg-white dark:bg-slate-800 rounded-2xl border border-dashed border-slate-300 dark:border-slate-700">
          <p class="text-slate-400 dark:text-slate-500 text-sm">
            {{ lang === 'ar' ? 'لا توجد نتائج مطابقة لبحثك' : 'No news found matching your criteria.' }}
          </p>
        </div>
      </div>
    </div>

    <!-- News Details Modal -->
    <div v-if="selectedNews" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
      <div class="bg-white dark:bg-slate-800 rounded-3xl max-w-2xl w-full max-h-[90vh] overflow-y-auto p-6 sm:p-8 space-y-5 shadow-2xl border border-slate-200 dark:border-slate-700">
        <div class="flex items-center justify-between">
          <span class="text-xs font-bold text-blue-900 dark:text-blue-300 bg-blue-50 dark:bg-blue-900/40 px-3 py-1 rounded-md">
            {{ selectedNews.category }}
          </span>
          <button @click="selectedNews = null" class="p-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 rounded-lg">
            <X class="w-5 h-5" />
          </button>
        </div>
        <img :src="selectedNews.image" :alt="selectedNews.title" class="w-full h-64 object-cover rounded-2xl bg-slate-100 dark:bg-slate-700" />
        <div class="text-xs text-slate-400 flex items-center gap-2">
          <Calendar class="w-4 h-4" />
          <span>{{ selectedNews.date || selectedNews.published_at || 'Recently' }}</span>
          <span>•</span>
          <span>By {{ selectedNews.author }}</span>
        </div>
        <h2 class="text-2xl font-black text-slate-900 dark:text-white">{{ selectedNews.title }}</h2>
        <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed whitespace-pre-line">{{ selectedNews.content || selectedNews.snippet }}</p>
        <div class="pt-4 border-t dark:border-slate-700 flex justify-end">
          <button 
            @click="selectedNews = null" 
            class="px-5 py-2.5 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 rounded-xl text-xs font-bold hover:bg-slate-200 dark:hover:bg-slate-600 transition-colors"
          >
            {{ lang === 'ar' ? 'إغلاق' : 'Close' }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { Calendar, Search, X, ChevronLeft, ChevronRight } from 'lucide-vue-next';

defineProps({
  t: {
    type: Function,
    required: true
  },
  lang: {
    type: String,
    required: true
  }
});

const searchQuery = ref('');
const selectedCategory = ref('all');
const selectedNews = ref(null);
const newsList = ref([]);

// Slideshow state
const currentSlide = ref(0);
let slideTimer = null;

const eventSlides = [
  {
    image: 'https://images.unsplash.com/photo-1531482615713-2afd69097998?auto=format&fit=crop&w=1200&q=80',
    title: 'ورشة إنترنت الأشياء والأنظمة المدمجة (IoT Workshop)',
    caption: 'تطبيق عملي وتدريب مكثف على معالجات ARM وأجهزة الاستشعار بحضور أكثر من 70 طالباً.',
    badge: 'فعالية حديثة',
    date: 'March 2025'
  },
  {
    image: 'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?auto=format&fit=crop&w=1200&q=80',
    title: 'مؤتمر الذكاء الاصطناعي وتطبيقاته الهندسية',
    caption: 'جلسات حوارية وكلمات رئيسية مع نخبة من الخبراء والمهندسين في مجالات تعلّم الآلة والروبوتات.',
    badge: 'تغطية خاصة',
    date: 'February 2025'
  },
  {
    image: 'https://images.unsplash.com/photo-1511578314322-379afb476865?auto=format&fit=crop&w=1200&q=80',
    title: 'تمثيل الفرع في ملتقى الفروع الطلابية IEEE Region 8',
    caption: 'مشاركة مميزة لوفد الفرع وتبادل للخبرات مع قادة فروع الجامعات الدولية.',
    badge: 'إنجاز إقليمي',
    date: 'January 2025'
  },
  {
    image: 'https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&w=1200&q=80',
    title: 'معسكر تدريب المتطوعين الجدد وفرق العمل',
    caption: 'بناء المهارات القيادية وإدارة المشاريع الطلابية استعداداً للعام الأكاديمي الجديد.',
    badge: 'نشاط طلابي',
    date: 'November 2024'
  }
];

function nextSlide() {
  currentSlide.value = (currentSlide.value + 1) % eventSlides.length;
}

function prevSlide() {
  currentSlide.value = (currentSlide.value - 1 + eventSlides.length) % eventSlides.length;
}

const categories = [
  { id: 'all', key: 'filterAll' },
  { id: 'announcements', key: 'filterAnnouncements' },
  { id: 'events', key: 'filterEvents' },
  { id: 'achievements', key: 'filterAchievements' },
];

async function loadNews() {
  try {
    const res = await fetch('./api.php?action=news');
    const data = await res.json();
    if (res.ok && data.status === 'success' && data.data && data.data.length > 0) {
      newsList.value = data.data;
      return;
    }
  } catch (e) {}

  // Fallback defaults
  newsList.value = [
    {
      id: 1,
      title: 'IEEE AAU Organizes a Technical Workshop',
      category: 'Events',
      date: 'April 2025',
      snippet: 'A hands-on workshop on Embedded Systems and IoT architectures for engineering undergraduates.',
      content: 'A comprehensive 3-day practical workshop that empowered students to design embedded systems and connected sensor nodes with live testing.',
      image: 'https://images.unsplash.com/photo-1531482615713-2afd69097998?auto=format&fit=crop&w=800&q=80',
      author: 'Media Team'
    },
    {
      id: 2,
      title: 'New IEEE Magazine Issue Released',
      category: 'Announcements',
      date: 'March 2025',
      snippet: 'Explore the latest issue of IEEE AAU Magazine featuring student innovations, AI ethics, and interviews.',
      content: 'The editorial board has unveiled Volume 12 Issue 3 showcasing breakthroughs in sustainable grids, machine intelligence, and student hardware prototypes.',
      image: 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?auto=format&fit=crop&w=800&q=80',
      author: 'Editorial Committee'
    },
    {
      id: 3,
      title: 'Upcoming Event: AI & Future Technologies',
      category: 'Events',
      date: 'March 2025',
      snippet: 'Register now for our flagship symposium featuring guest keynote speakers from regional tech firms.',
      content: 'Join world-class engineering experts to discuss generative AI, ethical computing guidelines, and future tech career pathways.',
      image: 'https://images.unsplash.com/photo-1517245386807-bb43f82c33c4?auto=format&fit=crop&w=800&q=80',
      author: 'Events Team'
    },
    {
      id: 4,
      title: 'IEEE AAU Participates in IEEE Region 8 Event',
      category: 'Achievements',
      date: 'February 2025',
      snippet: 'Our branch represented Alzaeim Alazhari University at the annual cross-sectional congress.',
      content: 'Our student leaders engaged with fellow branches across Region 8, establishing cross-chapter research initiatives and collaborative projects.',
      image: 'https://images.unsplash.com/photo-1511578314322-379afb476865?auto=format&fit=crop&w=800&q=80',
      author: 'Branch Chair'
    }
  ];
}

const filteredNews = computed(() => {
  return newsList.value.filter(item => {
    const matchesCategory = selectedCategory.value === 'all' || 
      item.category.toLowerCase() === selectedCategory.value.toLowerCase();
    const query = searchQuery.value.toLowerCase();
    const matchesSearch = item.title.toLowerCase().includes(query) || 
      item.snippet.toLowerCase().includes(query);
    return matchesCategory && matchesSearch;
  });
});

onMounted(() => {
  loadNews();
  slideTimer = setInterval(nextSlide, 5000);
});

onUnmounted(() => {
  if (slideTimer) clearInterval(slideTimer);
});
</script>
