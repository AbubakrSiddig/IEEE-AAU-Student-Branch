<template>
  <div class="py-12 bg-slate-50 dark:bg-slate-900 min-h-screen transition-colors duration-200">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
      <!-- Header -->
      <div class="border-b border-slate-200 dark:border-slate-800 pb-6">
        <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white">{{ t('magazineTitle') }}</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
          {{ lang === 'ar' ? 'المجلة الدورية الرسمية لفرع IEEE بجامعة الزعيم الأزهري - أبحاث وابتكارات طلابية' : 'The official periodic publication of IEEE AAU Student Branch.' }}
        </p>
      </div>

      <!-- Latest Issue Feature (Screen 5) -->
      <div v-if="latestIssue" class="bg-white dark:bg-slate-800 rounded-3xl p-8 sm:p-12 border border-slate-200/90 dark:border-slate-700 shadow-sm flex flex-col md:flex-row items-center gap-10 transition-colors">
        <div class="w-56 sm:w-64 h-80 rounded-2xl shadow-2xl relative overflow-hidden flex-shrink-0 group bg-slate-900 border border-slate-700">
          <img 
            :src="latestIssue.cover" 
            :alt="latestIssue.title"
            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
          />
          <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent flex flex-col justify-end p-4 text-white">
            <span class="text-xs font-bold text-blue-400">IEEE AAU MAGAZINE</span>
            <span class="text-sm font-semibold">{{ latestIssue.volume }}</span>
          </div>
        </div>

        <div class="flex-1 space-y-4">
          <div class="inline-block px-3 py-1 bg-blue-50 dark:bg-blue-900/40 text-blue-900 dark:text-blue-300 text-xs font-bold rounded-lg uppercase tracking-wider">
            {{ t('latestIssue') }}
          </div>
          <h2 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white leading-tight">
            {{ latestIssue.title }}
          </h2>
          <div class="text-xs text-slate-500 dark:text-slate-400 font-medium">
            {{ latestIssue.volume }} • {{ latestIssue.published_date || latestIssue.publishedDate }}
          </div>
          <p class="text-slate-600 dark:text-slate-300 text-sm leading-relaxed">
            {{ latestIssue.description }}
          </p>
          <div class="pt-4 flex flex-wrap gap-4">
            <a 
              :href="latestIssue.file_url || latestIssue.fileUrl || '#'"
              target="_blank"
              class="px-6 py-3 bg-blue-900 dark:bg-blue-600 hover:bg-blue-800 text-white font-semibold rounded-xl text-sm shadow-md hover:shadow-lg transition-all flex items-center gap-2"
            >
              <Download class="w-4 h-4" />
              <span>{{ t('downloadPDF') }}</span>
            </a>
            <button 
              @click="previewIssue(latestIssue)"
              class="px-6 py-3 border border-slate-300 dark:border-slate-600 hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 font-semibold rounded-xl text-sm transition-colors"
            >
              {{ lang === 'ar' ? 'معاينة الغلاف' : 'Quick Preview' }}
            </button>
          </div>
        </div>
      </div>

      <!-- Past Issues Section (Screen 5) -->
      <div class="space-y-6">
        <div class="flex items-center justify-between">
          <h2 class="text-2xl font-bold text-slate-900 dark:text-white">
            {{ t('pastIssues') }}
          </h2>
          <button 
            @click="showAllPast = !showAllPast"
            class="text-xs font-bold text-blue-900 dark:text-blue-400 hover:underline"
          >
            {{ showAllPast ? (lang === 'ar' ? 'عرض أقل' : 'View Less') : (lang === 'ar' ? 'عرض الكل' : 'View All') }}
          </button>
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-6">
          <div 
            v-for="issue in displayedPastIssues" 
            :key="issue.id"
            class="bg-white dark:bg-slate-800 rounded-2xl p-4 border border-slate-200 dark:border-slate-700 shadow-xs hover:shadow-md dark:hover:border-slate-600 transition-all group flex flex-col justify-between"
          >
            <div class="h-52 rounded-xl overflow-hidden mb-3 relative bg-slate-900 cursor-pointer" @click="previewIssue(issue)">
              <img 
                :src="issue.cover" 
                :alt="issue.volume"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
              />
              <div class="absolute inset-0 bg-black/30 group-hover:bg-black/10 transition-colors"></div>
              <div class="absolute bottom-2 left-2 right-2 text-center text-white text-xs font-bold bg-black/60 backdrop-blur-sm py-1 rounded">
                {{ issue.volume }}
              </div>
            </div>
            <div class="text-center space-y-2">
              <div class="text-xs text-slate-500 dark:text-slate-400 font-semibold">{{ issue.year }}</div>
              <h4 class="text-xs font-bold text-slate-900 dark:text-white truncate" :title="issue.title">{{ issue.title }}</h4>
              <a 
                :href="issue.file_url || issue.fileUrl || '#'"
                target="_blank"
                class="w-full py-1.5 px-3 text-xs font-semibold text-blue-900 dark:text-blue-300 hover:bg-blue-50 dark:hover:bg-blue-900/30 border border-blue-200 dark:border-slate-600 rounded-lg transition-colors flex items-center justify-center gap-1.5"
              >
                <Download class="w-3.5 h-3.5" />
                <span>{{ t('downloadPDF') }}</span>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Preview Modal -->
    <div v-if="previewingIssue" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/70 backdrop-blur-sm">
      <div class="bg-white dark:bg-slate-800 rounded-3xl max-w-lg w-full p-6 text-center space-y-4 shadow-2xl border border-slate-200 dark:border-slate-700">
        <div class="flex items-center justify-between pb-2 border-b dark:border-slate-700">
          <h3 class="font-bold text-slate-900 dark:text-white text-sm">{{ previewingIssue.title }}</h3>
          <button @click="previewingIssue = null" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
            <X class="w-5 h-5" />
          </button>
        </div>
        <img :src="previewingIssue.cover" :alt="previewingIssue.title" class="max-h-96 mx-auto rounded-xl object-contain shadow-lg" />
        <p class="text-xs text-slate-500 dark:text-slate-400">{{ previewingIssue.volume }} • {{ previewingIssue.published_date || previewingIssue.publishedDate }}</p>
        <div class="pt-2 flex justify-center gap-3">
          <a 
            :href="previewingIssue.file_url || previewingIssue.fileUrl || '#'"
            target="_blank"
            class="px-5 py-2 bg-blue-900 dark:bg-blue-600 text-white rounded-xl text-xs font-bold flex items-center gap-1.5"
          >
            <Download class="w-3.5 h-3.5" />
            <span>{{ t('downloadPDF') }}</span>
          </a>
          <button @click="previewingIssue = null" class="px-5 py-2 border dark:border-slate-600 rounded-xl text-xs font-bold text-slate-600 dark:text-slate-300">
            {{ lang === 'ar' ? 'إغلاق' : 'Close' }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { Download, X } from 'lucide-vue-next';

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

const showAllPast = ref(false);
const previewingIssue = ref(null);
const allIssues = ref([]);

const defaultIssues = [
  {
    id: 1,
    volume: 'Vol. 12 | Issue 3 | 2025',
    title: 'Engineering a Smarter Tomorrow',
    published_date: 'April 2025',
    year: '2025',
    cover: 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?auto=format&fit=crop&w=600&q=80',
    description: 'Cutting-edge breakthroughs in renewable grid integration, quantum computing, and student innovations from Alzaeim Alazhari University.',
    file_url: '#'
  },
  {
    id: 2,
    volume: 'Vol. 12 Issue 1',
    title: 'The AI Renaissance',
    published_date: 'January 2025',
    year: '2025',
    cover: 'https://images.unsplash.com/photo-1634017839464-5c339ebe3cb4?auto=format&fit=crop&w=600&q=80',
    description: 'Generative computing in academia and research.',
    file_url: '#'
  },
  {
    id: 3,
    volume: 'Vol. 11 Issue 4',
    title: 'Smart Grids & Africa',
    published_date: 'November 2024',
    year: '2024',
    cover: 'https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?auto=format&fit=crop&w=600&q=80',
    description: 'Sustainable energy transitions.',
    file_url: '#'
  },
  {
    id: 4,
    volume: 'Vol. 11 Issue 3',
    title: 'Robotics in Surgery',
    published_date: 'August 2024',
    year: '2024',
    cover: 'https://images.unsplash.com/photo-1550751827-4bd374c3f58b?auto=format&fit=crop&w=600&q=80',
    description: 'Biomedical engineering milestones.',
    file_url: '#'
  },
  {
    id: 5,
    volume: 'Vol. 11 Issue 2',
    title: '5G Infrastructure',
    published_date: 'May 2024',
    year: '2024',
    cover: 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?auto=format&fit=crop&w=600&q=80',
    description: 'Telecommunications in developing nations.',
    file_url: '#'
  }
];

async function loadMagazines() {
  try {
    const res = await fetch('./api.php?action=magazines');
    const data = await res.json();
    if (res.ok && data.status === 'success' && data.data && data.data.length > 0) {
      allIssues.value = data.data;
      return;
    }
  } catch (e) {}

  allIssues.value = defaultIssues;
}

const latestIssue = computed(() => {
  return allIssues.value[0] || null;
});

const displayedPastIssues = computed(() => {
  const past = allIssues.value.slice(1);
  return showAllPast.value ? past : past.slice(0, 4);
});

function previewIssue(issue) {
  previewingIssue.value = issue;
}

onMounted(() => {
  loadMagazines();
});
</script>
