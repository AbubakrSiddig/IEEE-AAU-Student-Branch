<template>
  <div class="py-12 bg-slate-50 dark:bg-slate-900 min-h-screen transition-colors duration-200">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
      <!-- Header -->
      <div class="border-b border-slate-200 dark:border-slate-800 pb-6">
        <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white">{{ t('articles') }}</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
          {{ lang === 'ar' ? 'مقالات وأبحاث تقنية وكتابات طلابية متخصصة ومقالات التوجيه المهني' : 'Technical insights, career guidance, and engineering articles.' }}
        </p>

        <!-- Search input -->
        <div class="relative mt-4">
          <input 
            v-model="searchQuery" 
            type="text" 
            :placeholder="t('searchPlaceholder')" 
            class="w-full pl-10 pr-4 py-3 text-sm bg-white dark:bg-slate-800 text-slate-900 dark:text-white border border-slate-200 dark:border-slate-700 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent shadow-xs transition-colors"
          />
          <Search class="w-4 h-4 text-slate-400 absolute left-3 top-3.5" />
        </div>
      </div>

      <!-- Categories Filter (All, Technology, Career, IEEE, Student Life) -->
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

      <!-- Articles Grid/List -->
      <div class="space-y-4">
        <div 
          v-for="article in filteredArticles" 
          :key="article.id"
          @click="selectedArticle = article"
          class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-200 dark:border-slate-700 shadow-xs hover:shadow-md dark:hover:border-slate-600 transition-all duration-200 cursor-pointer flex flex-col sm:flex-row gap-5 items-start"
        >
          <img 
            :src="article.image" 
            :alt="article.title"
            class="w-full sm:w-48 h-36 object-cover rounded-xl flex-shrink-0 bg-slate-100 dark:bg-slate-700"
          />
          <div class="flex-1 space-y-2">
            <div class="flex items-center gap-3">
              <span class="text-xs font-bold text-blue-900 dark:text-blue-300 bg-blue-50 dark:bg-blue-900/40 px-2.5 py-0.5 rounded-md">
                {{ article.category }}
              </span>
              <span class="text-xs text-slate-400 flex items-center gap-1">
                <Calendar class="w-3.5 h-3.5" />
                {{ article.date || 'Recently' }}
              </span>
            </div>
            <h2 class="text-lg font-bold text-slate-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
              {{ article.title }}
            </h2>
            <p class="text-xs sm:text-sm text-slate-600 dark:text-slate-300 leading-relaxed line-clamp-2">
              {{ article.snippet }}
            </p>
            <div class="pt-2 flex items-center justify-between text-xs">
              <span class="text-slate-500 dark:text-slate-400 font-medium flex items-center gap-1">
                <User class="w-3.5 h-3.5" /> {{ article.author }}
              </span>
              <span class="text-blue-900 dark:text-blue-400 font-semibold flex items-center gap-1">
                {{ lang === 'ar' ? 'اقرأ المقال' : 'Read Article' }} &rarr;
              </span>
            </div>
          </div>
        </div>

        <div v-if="filteredArticles.length === 0" class="text-center py-16 bg-white dark:bg-slate-800 rounded-2xl border border-dashed border-slate-300 dark:border-slate-700">
          <p class="text-slate-400 dark:text-slate-500 text-sm">
            {{ lang === 'ar' ? 'لا توجد مقالات مطابقة لبحثك' : 'No articles match your query.' }}
          </p>
        </div>
      </div>
    </div>

    <!-- Article Reader Modal -->
    <div v-if="selectedArticle" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
      <div class="bg-white dark:bg-slate-800 rounded-3xl max-w-2xl w-full max-h-[90vh] overflow-y-auto p-6 sm:p-8 space-y-5 shadow-2xl border border-slate-200 dark:border-slate-700">
        <div class="flex items-center justify-between">
          <span class="text-xs font-bold text-blue-900 dark:text-blue-300 bg-blue-50 dark:bg-blue-900/40 px-3 py-1 rounded-md">
            {{ selectedArticle.category }}
          </span>
          <button @click="selectedArticle = null" class="p-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 rounded-lg">
            <X class="w-5 h-5" />
          </button>
        </div>
        <img :src="selectedArticle.image" :alt="selectedArticle.title" class="w-full h-64 object-cover rounded-2xl bg-slate-100 dark:bg-slate-700" />
        <div class="text-xs text-slate-400 flex items-center gap-2">
          <Calendar class="w-4 h-4" />
          <span>{{ selectedArticle.date || 'Recently' }}</span>
          <span>•</span>
          <User class="w-3.5 h-3.5 ml-1" />
          <span>{{ selectedArticle.author }}</span>
        </div>
        <h2 class="text-2xl font-black text-slate-900 dark:text-white">{{ selectedArticle.title }}</h2>
        <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed whitespace-pre-line">{{ selectedArticle.content || selectedArticle.snippet }}</p>
        <div class="pt-4 border-t dark:border-slate-700 flex justify-end">
          <button 
            @click="selectedArticle = null" 
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
import { ref, computed, onMounted } from 'vue';
import { Calendar, User, Search, X } from 'lucide-vue-next';

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
const selectedArticle = ref(null);
const articlesList = ref([]);

const categories = [
  { id: 'all', key: 'filterAll' },
  { id: 'technology', key: 'filterTech' },
  { id: 'career', key: 'filterCareer' },
  { id: 'ieee', key: 'filterIEEE' },
  { id: 'student life', key: 'filterStudentLife' },
];

async function loadArticles() {
  try {
    const res = await fetch('./api.php?action=articles');
    const data = await res.json();
    if (res.ok && data.status === 'success' && data.data && data.data.length > 0) {
      articlesList.value = data.data;
      return;
    }
  } catch (e) {}

  // Fallback defaults
  articlesList.value = [
    {
      id: 1,
      title: 'The Future of AI in Education',
      category: 'Technology',
      date: 'April 2025',
      snippet: 'How artificial intelligence is transforming adaptive learning and digital curriculum design.',
      content: 'Artificial intelligence is fundamentally reshaping how engineering concepts are taught, personalizing educational materials and simulating complex laboratory environments virtually.',
      image: 'https://images.unsplash.com/photo-1620712943543-bcc4688e7485?auto=format&fit=crop&w=600&q=80',
      author: 'Sara Ali'
    },
    {
      id: 2,
      title: 'Getting Started with Embedded Systems',
      category: 'Technology',
      date: 'March 2025',
      snippet: 'A practical guide for engineering students stepping into microcontrollers, ARM, and real-time OS.',
      content: 'Navigating the hardware-software boundary requires understanding register-level programming, interrupt service routines, and communication buses like SPI, I2C, and UART.',
      image: 'https://images.unsplash.com/photo-1518770660439-4636190af475?auto=format&fit=crop&w=600&q=80',
      author: 'Ahmed Mohamed'
    },
    {
      id: 3,
      title: 'Why IEEE Membership Matters',
      category: 'IEEE',
      date: 'February 2025',
      snippet: 'Unlocking access to IEEE Xplore digital library, global grant competitions, and international conferences.',
      content: 'Beyond technical documentation, IEEE membership grants you an international professional identity, student paper contest opportunities, and mentorship from IEEE Senior Members worldwide.',
      image: 'https://images.unsplash.com/photo-1521737604893-d14cc237f11d?auto=format&fit=crop&w=600&q=80',
      author: 'Branch Chair'
    }
  ];
}

const filteredArticles = computed(() => {
  return articlesList.value.filter(item => {
    const matchesCategory = selectedCategory.value === 'all' || 
      item.category.toLowerCase() === selectedCategory.value.toLowerCase();
    const query = searchQuery.value.toLowerCase();
    const matchesSearch = item.title.toLowerCase().includes(query) || 
      item.snippet.toLowerCase().includes(query);
    return matchesCategory && matchesSearch;
  });
});

onMounted(() => {
  loadArticles();
});
</script>
