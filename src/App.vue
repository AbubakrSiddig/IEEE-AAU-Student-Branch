<template>
  <div :dir="dir" class="min-h-screen flex flex-col bg-slate-50 dark:bg-slate-900 text-slate-800 dark:text-slate-100 transition-colors duration-200">
    <!-- Public Navbar (shown on public routes) -->
    <Navbar 
      v-if="!isAdminRoute" 
      :t="t" 
      :lang="lang" 
      @toggle-lang="toggleLang" 
      @open-search="searchModalOpen = true"
      @notify="handleNotification"
    />

    <!-- Main View Component -->
    <main class="flex-1">
      <router-view 
        :t="t" 
        :lang="lang" 
        @toggle-lang="toggleLang"
        @notify="handleNotification" 
      />
    </main>

    <!-- Public Footer (shown on public routes) -->
    <Footer 
      v-if="!isAdminRoute" 
      :t="t" 
      :lang="lang" 
    />

    <!-- Global Toast Component -->
    <Toast ref="toastRef" />

    <!-- Global Search Modal -->
    <div 
      v-if="searchModalOpen" 
      class="fixed inset-0 z-50 flex items-start justify-center pt-24 p-4 bg-slate-900/60 backdrop-blur-sm"
      @click.self="searchModalOpen = false"
    >
      <div class="bg-white dark:bg-slate-800 rounded-3xl max-w-xl w-full p-6 shadow-2xl space-y-4 border border-slate-100 dark:border-slate-700">
        <div class="flex items-center gap-3 border-b dark:border-slate-700 pb-3">
          <Search class="w-5 h-5 text-slate-400" />
          <input 
            v-model="globalSearchQuery" 
            type="text" 
            :placeholder="t('searchPlaceholder')" 
            class="flex-1 text-base bg-transparent focus:outline-none dark:text-white"
            autofocus
            @keyup.enter="performSearch"
          />
          <button @click="searchModalOpen = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
            <X class="w-5 h-5" />
          </button>
        </div>

        <div class="max-h-72 overflow-y-auto space-y-2 text-xs">
          <div 
            v-for="res in searchResults" 
            :key="res.title" 
            @click="goToResult(res)" 
            class="p-3 hover:bg-slate-50 dark:hover:bg-slate-700/50 rounded-xl cursor-pointer border border-transparent hover:border-slate-100 dark:hover:border-slate-700 transition-all flex items-center justify-between"
          >
            <div>
              <span class="text-[10px] font-bold uppercase text-blue-700 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/40 px-2 py-0.5 rounded mr-2">{{ res.type }}</span>
              <span class="font-bold text-slate-800 dark:text-slate-200">{{ res.title }}</span>
            </div>
            <ArrowRight class="w-3.5 h-3.5 text-slate-400" />
          </div>

          <div v-if="globalSearchQuery && searchResults.length === 0" class="text-center py-6 text-slate-400">
            {{ lang === 'ar' ? 'لم يتم العثور على أي نتائج' : 'No results found' }}
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { Search, X, ArrowRight } from 'lucide-vue-next';
import Navbar from './components/Navbar.vue';
import Footer from './components/Footer.vue';
import Toast from './components/Toast.vue';
import { translations } from './i18n/translations';
import { storageService } from './services/storage';
import { themeService } from './services/theme';

const route = useRoute();
const router = useRouter();

const lang = ref(localStorage.getItem('ieee_aau_lang') || 'ar');
const dir = computed(() => (lang.value === 'ar' ? 'rtl' : 'ltr'));

const isAdminRoute = computed(() => route.path.startsWith('/admin'));

const toastRef = ref(null);
const searchModalOpen = ref(false);
const globalSearchQuery = ref('');

// Initialize theme and storage seeds
onMounted(() => {
  storageService.init();
  themeService.applyTheme();
  document.documentElement.setAttribute('dir', dir.value);
  document.documentElement.setAttribute('lang', lang.value);
});

watch(lang, (newLang) => {
  localStorage.setItem('ieee_aau_lang', newLang);
  document.documentElement.setAttribute('dir', dir.value);
  document.documentElement.setAttribute('lang', newLang);
});

function toggleLang() {
  lang.value = lang.value === 'en' ? 'ar' : 'en';
}

function t(key) {
  return translations[lang.value]?.[key] || translations['en']?.[key] || key;
}

function handleNotification(message, type = 'success') {
  if (toastRef.value) {
    toastRef.value.showToast(message, type);
  }
}

// Global Search
const searchResults = computed(() => {
  if (!globalSearchQuery.value.trim()) return [];
  const q = globalSearchQuery.value.toLowerCase();
  const results = [];

  storageService.getNews().forEach(n => {
    if (n.title.toLowerCase().includes(q) || n.snippet.toLowerCase().includes(q)) {
      results.push({ type: 'News', title: n.title, path: '/news' });
    }
  });

  storageService.getArticles().forEach(a => {
    if (a.title.toLowerCase().includes(q) || a.snippet.toLowerCase().includes(q)) {
      results.push({ type: 'Article', title: a.title, path: '/articles' });
    }
  });

  storageService.getEvents().forEach(e => {
    if (e.title.toLowerCase().includes(q) || (e.description && e.description.toLowerCase().includes(q))) {
      results.push({ type: 'Event', title: e.title, path: '/events' });
    }
  });

  return results;
});

function goToResult(res) {
  searchModalOpen.value = false;
  router.push(res.path);
}
</script>
