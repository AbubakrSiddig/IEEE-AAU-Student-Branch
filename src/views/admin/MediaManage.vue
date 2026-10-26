<template>
  <div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div>
        <h1 class="text-2xl font-black text-slate-900 dark:text-white">
          {{ lang === 'ar' ? 'إدارة الإعلام والمحتوى الرقمي' : 'Media & Content Management' }}
        </h1>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
          {{ lang === 'ar' ? 'نشر وإدارة الأخبار وتغطيات الفعاليات، رفع الصور، ونشر المقالات التقنية للفرع' : 'Manage news releases, event photo coverages, and published student technical articles.' }}
        </p>
      </div>

      <div class="flex items-center gap-2">
        <button 
          v-if="canEdit"
          @click="openAddModal"
          class="px-4 py-2.5 bg-blue-900 hover:bg-blue-800 text-white text-xs font-bold rounded-xl shadow-xs transition-all flex items-center gap-2"
        >
          <Plus class="w-4 h-4" />
          <span>{{ activeTab === 'news' ? (lang === 'ar' ? 'نشر خبر / فعالية' : 'Publish News') : (lang === 'ar' ? 'نشر مقال جديد' : 'Publish Article') }}</span>
        </button>
      </div>
    </div>

    <!-- Active Management Tabs (News vs Articles) -->
    <div class="flex items-center gap-2 border-b border-slate-200 dark:border-slate-700 pb-2">
      <button 
        @click="activeTab = 'news'"
        class="px-5 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center gap-2"
        :class="activeTab === 'news' 
          ? 'bg-blue-900 dark:bg-blue-600 text-white shadow-xs' 
          : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700'"
      >
        <Newspaper class="w-4 h-4" />
        <span>{{ lang === 'ar' ? 'الأخبار وتغطيات الفعاليات' : 'News & Event Coverages' }}</span>
        <span class="px-1.5 py-0.5 rounded-full text-[10px] bg-white/20 font-mono">{{ newsItems.length }}</span>
      </button>

      <button 
        @click="activeTab = 'articles'"
        class="px-5 py-2.5 rounded-xl text-xs font-bold transition-all flex items-center gap-2"
        :class="activeTab === 'articles' 
          ? 'bg-blue-900 dark:bg-blue-600 text-white shadow-xs' 
          : 'bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700'"
      >
        <FileText class="w-4 h-4" />
        <span>{{ lang === 'ar' ? 'المقالات التقنية' : 'Technical Articles' }}</span>
        <span class="px-1.5 py-0.5 rounded-full text-[10px] bg-white/20 font-mono">{{ articlesItems.length }}</span>
      </button>
    </div>

    <!-- Filter & Search Bar -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 border border-slate-200/80 dark:border-slate-700 shadow-xs flex flex-wrap items-center justify-between gap-3">
      <div class="flex items-center gap-2">
        <select 
          v-model="categoryFilter" 
          class="px-3 py-2 bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-xs font-semibold dark:text-white"
        >
          <option value="All">All Categories (كل التصنيفات)</option>
          <template v-if="activeTab === 'news'">
            <option value="Events">Events (الفعاليات)</option>
            <option value="Announcements">Announcements (الإعلانات)</option>
            <option value="Achievements">Achievements (الإنجازات)</option>
            <option value="Workshops">Workshops (الورش التدريبية)</option>
          </template>
          <template v-else>
            <option value="Technology">Technology (تكنولوجيا)</option>
            <option value="Career">Career (التوجيه المهني)</option>
            <option value="IEEE">IEEE (أنشطة المنظمة)</option>
            <option value="Student Life">Student Life (الحياة الطلابية)</option>
          </template>
        </select>
      </div>

      <div class="relative w-full sm:w-64">
        <input 
          v-model="searchQuery" 
          type="text" 
          :placeholder="lang === 'ar' ? 'بحث في المحتوى...' : 'Search content...'"
          class="w-full pl-9 pr-3 py-2 bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-xs dark:text-white"
        />
        <Search class="w-3.5 h-3.5 text-slate-400 absolute left-3 top-2.5" />
      </div>
    </div>

    <!-- NEWS TABLE -->
    <div v-if="activeTab === 'news'" class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/80 dark:border-slate-700 shadow-xs overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-left text-xs text-slate-600 dark:text-slate-300">
          <thead class="bg-slate-50 dark:bg-slate-700/50 text-slate-400 font-semibold uppercase tracking-wider border-b border-slate-100 dark:border-slate-700">
            <tr>
              <th class="py-3.5 px-6">Image</th>
              <th class="py-3.5 px-6">News Title &amp; Snippet</th>
              <th class="py-3.5 px-6">Category</th>
              <th class="py-3.5 px-6">Author / Date</th>
              <th class="py-3.5 px-6">Status</th>
              <th class="py-3.5 px-6 text-right">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100 dark:divide-slate-700 font-medium">
            <tr 
              v-for="item in filteredNews" 
              :key="item.id"
              class="hover:bg-slate-50 dark:hover:bg-slate-700/40 transition-colors"
            >
              <td class="py-3.5 px-6">
                <img 
                  :src="item.image" 
                  :alt="item.title" 
                  class="w-16 h-12 object-cover rounded-lg shadow-xs bg-slate-100 dark:bg-slate-700 border dark:border-slate-600"
                />
              </td>
              <td class="py-3.5 px-6">
                <div class="font-bold text-slate-900 dark:text-white text-sm">{{ item.title }}</div>
                <div class="text-[11px] text-slate-400 max-w-sm truncate mt-0.5">{{ item.snippet }}</div>
              </td>
              <td class="py-3.5 px-6 whitespace-nowrap">
                <span class="px-2.5 py-1 rounded-md text-[11px] font-bold bg-blue-50 dark:bg-blue-900/40 text-blue-900 dark:text-blue-300">
                  {{ item.category }}
                </span>
              </td>
              <td class="py-3.5 px-6 whitespace-nowrap">
                <div class="font-semibold text-slate-800 dark:text-slate-200">{{ item.author }}</div>
                <div class="text-[10px] text-slate-400">{{ item.published_at || 'Recently' }}</div>
              </td>
              <td class="py-3.5 px-6 whitespace-nowrap">
                <span 
                  class="px-2 py-0.5 rounded text-[10px] font-bold uppercase border"
                  :class="item.status === 'published' ? 'bg-emerald-50 text-emerald-800 border-emerald-200 dark:bg-emerald-950/40 dark:text-emerald-300' : 'bg-amber-50 text-amber-800 border-amber-200'"
                >
                  {{ item.status }}
                </span>
              </td>
              <td class="py-3.5 px-6 text-right whitespace-nowrap space-x-1">
                <button 
                  v-if="canEdit"
                  @click="openEditNews(item)"
                  class="p-1.5 text-blue-900 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-slate-700 rounded-lg"
                  title="Edit News"
                >
                  <Edit3 class="w-4 h-4" />
                </button>
                <button 
                  v-if="canEdit"
                  @click="deleteNews(item)"
                  class="p-1.5 text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-900/20 rounded-lg"
                  title="Delete News"
                >
                  <Trash2 class="w-4 h-4" />
                </button>
              </td>
            </tr>
            <tr v-if="filteredNews.length === 0">
              <td colspan="6" class="py-8 text-center text-slate-400">
                {{ lang === 'ar' ? 'لا توجد أخبار مطابقة' : 'No news found.' }}
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- ARTICLES TABLE -->
    <div v-else class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/80 dark:border-slate-700 shadow-xs overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-left text-xs text-slate-600 dark:text-slate-300">
          <thead class="bg-slate-50 dark:bg-slate-700/50 text-slate-400 font-semibold uppercase tracking-wider border-b border-slate-100 dark:border-slate-700">
            <tr>
              <th class="py-3.5 px-6">Image</th>
              <th class="py-3.5 px-6">Article Title &amp; Snippet</th>
              <th class="py-3.5 px-6">Category</th>
              <th class="py-3.5 px-6">Author</th>
              <th class="py-3.5 px-6">Status</th>
              <th class="py-3.5 px-6 text-right">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100 dark:divide-slate-700 font-medium">
            <tr 
              v-for="item in filteredArticles" 
              :key="item.id"
              class="hover:bg-slate-50 dark:hover:bg-slate-700/40 transition-colors"
            >
              <td class="py-3.5 px-6">
                <img 
                  :src="item.image" 
                  :alt="item.title" 
                  class="w-16 h-12 object-cover rounded-lg shadow-xs bg-slate-100 dark:bg-slate-700 border dark:border-slate-600"
                />
              </td>
              <td class="py-3.5 px-6">
                <div class="font-bold text-slate-900 dark:text-white text-sm">{{ item.title }}</div>
                <div class="text-[11px] text-slate-400 max-w-sm truncate mt-0.5">{{ item.snippet }}</div>
              </td>
              <td class="py-3.5 px-6 whitespace-nowrap">
                <span class="px-2.5 py-1 rounded-md text-[11px] font-bold bg-indigo-50 dark:bg-indigo-900/40 text-indigo-900 dark:text-indigo-300">
                  {{ item.category }}
                </span>
              </td>
              <td class="py-3.5 px-6 whitespace-nowrap font-semibold text-slate-800 dark:text-slate-200">
                {{ item.author }}
              </td>
              <td class="py-3.5 px-6 whitespace-nowrap">
                <span 
                  class="px-2 py-0.5 rounded text-[10px] font-bold uppercase border"
                  :class="item.status === 'published' ? 'bg-emerald-50 text-emerald-800 border-emerald-200 dark:bg-emerald-950/40 dark:text-emerald-300' : 'bg-amber-50 text-amber-800 border-amber-200'"
                >
                  {{ item.status }}
                </span>
              </td>
              <td class="py-3.5 px-6 text-right whitespace-nowrap space-x-1">
                <button 
                  v-if="canEdit"
                  @click="openEditArticle(item)"
                  class="p-1.5 text-blue-900 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-slate-700 rounded-lg"
                  title="Edit Article"
                >
                  <Edit3 class="w-4 h-4" />
                </button>
                <button 
                  v-if="canEdit"
                  @click="deleteArticle(item)"
                  class="p-1.5 text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-900/20 rounded-lg"
                  title="Delete Article"
                >
                  <Trash2 class="w-4 h-4" />
                </button>
              </td>
            </tr>
            <tr v-if="filteredArticles.length === 0">
              <td colspan="6" class="py-8 text-center text-slate-400">
                {{ lang === 'ar' ? 'لا توجد مقالات مطابقة' : 'No articles found.' }}
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Add / Edit Modal -->
    <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
      <div class="bg-white dark:bg-slate-800 rounded-3xl max-w-xl w-full p-6 sm:p-8 space-y-5 shadow-2xl border border-slate-100 dark:border-slate-700 max-h-[92vh] overflow-y-auto">
        <div class="flex items-center justify-between border-b dark:border-slate-700 pb-3">
          <div>
            <h3 class="text-lg font-black text-slate-900 dark:text-white">
              {{ isEditing 
                ? (activeTab === 'news' ? (lang === 'ar' ? 'تعديل الخبر' : 'Edit News') : (lang === 'ar' ? 'تعديل المقال' : 'Edit Article')) 
                : (activeTab === 'news' ? (lang === 'ar' ? 'نشر خبر جديد' : 'New News Release') : (lang === 'ar' ? 'نشر مقال جديد' : 'New Technical Article')) 
              }}
            </h3>
            <p class="text-xs text-slate-400 mt-0.5">
              {{ lang === 'ar' ? 'أدخل البيانات وارفع الصورة التوضيحية' : 'Fill details, upload coverage photo or graphic' }}
            </p>
          </div>
          <button @click="showModal = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
            <X class="w-5 h-5" />
          </button>
        </div>

        <form @submit.prevent="saveContent" class="space-y-4 text-xs">
          <!-- Title -->
          <div>
            <label class="block font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">
              {{ lang === 'ar' ? 'العنوان الرئيسي' : 'Title' }} *
            </label>
            <input 
              v-model="form.title" 
              required 
              type="text" 
              placeholder="Title here..." 
              class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-600"
            />
          </div>

          <!-- Category and Author -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
              <label class="block font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">
                {{ lang === 'ar' ? 'التصنيف' : 'Category' }} *
              </label>
              <select 
                v-model="form.category" 
                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 dark:text-white"
              >
                <template v-if="activeTab === 'news'">
                  <option value="Events">Events (فعاليات)</option>
                  <option value="Announcements">Announcements (إعلانات)</option>
                  <option value="Achievements">Achievements (إنجازات)</option>
                  <option value="Workshops">Workshops (ورش تدريبية)</option>
                </template>
                <template v-else>
                  <option value="Technology">Technology (تقنية)</option>
                  <option value="Career">Career (توجيه مهني)</option>
                  <option value="IEEE">IEEE (أنشطة المنظمة)</option>
                  <option value="Student Life">Student Life (حياة جامعية)</option>
                </template>
              </select>
            </div>
            <div>
              <label class="block font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">
                {{ lang === 'ar' ? 'الكاتب / المصدر' : 'Author / Source' }} *
              </label>
              <input 
                v-model="form.author" 
                required 
                type="text" 
                placeholder="Media Team / Author Name" 
                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-600"
              />
            </div>
          </div>

          <!-- Snippet -->
          <div>
            <label class="block font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">
              {{ lang === 'ar' ? 'موجز قصير (Snippet)' : 'Brief Snippet' }} *
            </label>
            <textarea 
              v-model="form.snippet" 
              required 
              rows="2" 
              placeholder="Short teaser shown in cards and listings..."
              class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-600"
            ></textarea>
          </div>

          <!-- Full Content -->
          <div>
            <label class="block font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">
              {{ lang === 'ar' ? 'المحتوى الكامل والتفاصيل' : 'Full Content & Details' }}
            </label>
            <textarea 
              v-model="form.content" 
              rows="4" 
              placeholder="Full article content or complete event coverage..."
              class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-600"
            ></textarea>
          </div>

          <!-- Direct Image Upload (No URL required) -->
          <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 space-y-3">
            <div class="flex items-center justify-between">
              <label class="block font-bold text-slate-700 dark:text-slate-300 uppercase text-xs flex items-center gap-2">
                <Camera class="w-4 h-4 text-blue-900 dark:text-blue-400" />
                <span>{{ lang === 'ar' ? 'صورة الخبر أو المقال' : 'Cover / Event Photo' }} *</span>
              </label>
              <span v-if="form.image" class="text-[10px] text-emerald-600 dark:text-emerald-400 font-semibold flex items-center gap-1">
                <Check class="w-3 h-3" /> {{ lang === 'ar' ? 'تم اختيار الصورة' : 'Image ready' }}
              </span>
            </div>

            <!-- If Image selected/uploaded -->
            <div v-if="form.image" class="flex flex-col sm:flex-row items-center gap-4 p-3 bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-600">
              <img :src="form.image" alt="Preview" class="w-28 h-20 object-cover rounded-lg shadow-xs border border-slate-200 dark:border-slate-700 shrink-0" />
              <div class="flex-1 space-y-2 text-center sm:text-left w-full">
                <div class="text-[11px] font-mono text-slate-400 truncate max-w-xs">{{ form.image }}</div>
                <div class="flex items-center gap-2 justify-center sm:justify-start">
                  <label class="px-3 py-1.5 bg-blue-900 text-white hover:bg-blue-800 rounded-lg text-xs font-bold cursor-pointer transition-colors inline-flex items-center gap-1.5 shadow-xs">
                    <Upload class="w-3.5 h-3.5" />
                    <span>{{ uploadingImage ? (lang === 'ar' ? 'جاري الرفع...' : 'Uploading...') : (lang === 'ar' ? 'تغيير الصورة من الجهاز' : 'Change Photo') }}</span>
                    <input type="file" accept="image/*" class="hidden" @change="uploadImage" />
                  </label>
                  <button 
                    type="button" 
                    @click="form.image = ''" 
                    class="px-3 py-1.5 text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/30 rounded-lg text-xs font-semibold border border-rose-200 dark:border-rose-900"
                  >
                    {{ lang === 'ar' ? 'حذف' : 'Remove' }}
                  </button>
                </div>
              </div>
            </div>

            <!-- If NO image selected yet: Clickable Dropzone -->
            <div v-else>
              <label class="border-2 border-dashed border-slate-300 dark:border-slate-600 hover:border-blue-500 dark:hover:border-blue-400 rounded-2xl p-6 flex flex-col items-center justify-center gap-2 cursor-pointer bg-white dark:bg-slate-800 transition-all group">
                <div class="w-12 h-12 rounded-full bg-blue-50 dark:bg-blue-950/60 text-blue-900 dark:text-blue-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                  <Upload class="w-6 h-6" />
                </div>
                <div class="text-center space-y-1">
                  <p class="text-xs font-bold text-slate-800 dark:text-slate-200">
                    {{ uploadingImage ? (lang === 'ar' ? 'جاري رفع وضغط الصورة...' : 'Uploading image...') : (lang === 'ar' ? 'انقر لاختيار صورة من جهازك' : 'Click to select photo from device') }}
                  </p>
                  <p class="text-[10px] text-slate-400">
                    {{ lang === 'ar' ? 'الملفات المدعومة: PNG, JPG, WebP, GIF حتى 64 ميجابايت' : 'Supported: PNG, JPG, WebP, GIF up to 64MB' }}
                  </p>
                </div>
                <input type="file" accept="image/*" class="hidden" @change="uploadImage" />
              </label>
            </div>
          </div>

          <!-- Status -->
          <div>
            <label class="block font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">
              {{ lang === 'ar' ? 'حالة النشر' : 'Publication Status' }}
            </label>
            <select 
              v-model="form.status" 
              class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 dark:text-white"
            >
              <option value="published">Published (منشور)</option>
              <option value="draft">Draft (مسودة غير منشورة)</option>
            </select>
          </div>

          <!-- Actions -->
          <div class="flex items-center justify-end gap-2 pt-3 border-t dark:border-slate-700">
            <button 
              type="button" 
              @click="showModal = false" 
              class="px-4 py-2 border dark:border-slate-600 rounded-xl text-slate-600 dark:text-slate-300 font-semibold"
            >
              {{ lang === 'ar' ? 'إلغاء' : 'Cancel' }}
            </button>
            <button 
              type="submit" 
              :disabled="saving"
              class="px-5 py-2 bg-blue-900 hover:bg-blue-800 text-white font-bold rounded-xl shadow-xs transition-all flex items-center gap-1.5 disabled:opacity-50"
            >
              <Check class="w-4 h-4" />
              <span>{{ saving ? (lang === 'ar' ? 'جاري الحفظ...' : 'Saving...') : (lang === 'ar' ? 'حفظ ونشر' : 'Save & Publish') }}</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { Newspaper, FileText, Plus, Search, Edit3, Trash2, Upload, Check, X, Camera, Loader2 } from 'lucide-vue-next';
import { authService } from '../../services/auth';

const props = defineProps({
  t: {
    type: Function,
    required: true
  },
  lang: {
    type: String,
    required: true
  }
});

const emit = defineEmits(['notify']);

const activeTab = ref('news');
const newsItems = ref([]);
const articlesItems = ref([]);
const categoryFilter = ref('All');
const searchQuery = ref('');

const showModal = ref(false);
const isEditing = ref(false);
const saving = ref(false);
const uploadingImage = ref(false);

const canEdit = computed(() => authService.can('news', 'edit') || authService.can('articles', 'edit'));

const form = ref({
  id: null,
  title: '',
  category: 'Events',
  author: 'Media Team',
  snippet: '',
  content: '',
  image: '',
  status: 'published'
});

async function loadData() {
  try {
    const resN = await fetch('./api.php?action=news');
    const dataN = await resN.json();
    if (resN.ok && dataN.status === 'success') {
      newsItems.value = dataN.data;
    }

    const resA = await fetch('./api.php?action=articles');
    const dataA = await resA.json();
    if (resA.ok && dataA.status === 'success') {
      articlesItems.value = dataA.data;
    }
  } catch (e) {}
}

const filteredNews = computed(() => {
  return newsItems.value.filter(item => {
    const matchesCat = categoryFilter.value === 'All' || item.category === categoryFilter.value;
    const q = searchQuery.value.toLowerCase();
    const matchesSearch = (item.title || '').toLowerCase().includes(q) || 
      (item.snippet || '').toLowerCase().includes(q) ||
      (item.author || '').toLowerCase().includes(q);
    return matchesCat && matchesSearch;
  });
});

const filteredArticles = computed(() => {
  return articlesItems.value.filter(item => {
    const matchesCat = categoryFilter.value === 'All' || item.category === categoryFilter.value;
    const q = searchQuery.value.toLowerCase();
    const matchesSearch = (item.title || '').toLowerCase().includes(q) || 
      (item.snippet || '').toLowerCase().includes(q) ||
      (item.author || '').toLowerCase().includes(q);
    return matchesCat && matchesSearch;
  });
});

function openAddModal() {
  isEditing.value = false;
  form.value = {
    id: null,
    title: '',
    category: activeTab.value === 'news' ? 'Events' : 'Technology',
    author: authService.getUser()?.name || (activeTab.value === 'news' ? 'Media Team' : 'Editorial Team'),
    snippet: '',
    content: '',
    image: '',
    status: 'published'
  };
  showModal.value = true;
}

function openEditNews(item) {
  isEditing.value = true;
  form.value = { ...item };
  showModal.value = true;
}

function openEditArticle(item) {
  isEditing.value = true;
  form.value = { ...item };
  showModal.value = true;
}

async function uploadImage(event) {
  const file = event.target.files?.[0];
  if (!file) return;

  const token = authService.token.value || '';
  const formData = new FormData();
  formData.append('file', file);
  formData.append('token', token);
  uploadingImage.value = true;

  try {
    const res = await fetch(`./api.php?action=upload&folder=media&token=${encodeURIComponent(token)}`, {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${token}`
      },
      body: formData
    });

    const text = await res.text();
    let data;
    try {
      data = JSON.parse(text);
    } catch(parseErr) {
      console.error('Non-JSON server response:', text);
      emit('notify', (props.lang === 'ar' ? 'خطأ في استجابة الخادم: ' : 'Server error: ') + text.slice(0, 100), 'error');
      return;
    }

    if (res.ok && data.status === 'success') {
      form.value.image = data.url;
      emit('notify', props.lang === 'ar' ? 'تم رفع الصورة بنجاح' : 'Image uploaded successfully', 'success');
    } else {
      emit('notify', data.message || (props.lang === 'ar' ? 'فشل رفع الصورة' : 'Upload failed'), 'error');
    }
  } catch (e) {
    emit('notify', (props.lang === 'ar' ? 'خطأ أثناء رفع الصورة: ' : 'Upload error: ') + e.message, 'error');
  } finally {
    uploadingImage.value = false;
  }
}

async function saveContent() {
  if (!form.value.image) {
    emit('notify', props.lang === 'ar' ? 'يرجى اختيار ورفع صورة أولاً من جهازك' : 'Please select and upload an image first', 'error');
    return;
  }
  saving.value = true;
  const token = authService.token.value || '';
  const actionName = activeTab.value === 'news' ? 'news' : 'articles';
  const method = isEditing.value ? 'PUT' : 'POST';
  const url = isEditing.value 
    ? `./api.php?action=${actionName}&id=${form.value.id}&token=${encodeURIComponent(token)}` 
    : `./api.php?action=${actionName}&token=${encodeURIComponent(token)}`;

  try {
    const res = await fetch(url, {
      method,
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${token}`
      },
      body: JSON.stringify(form.value)
    });

    const text = await res.text();
    let data;
    try {
      data = JSON.parse(text);
    } catch(err) {
      emit('notify', 'Response error: ' + text.slice(0, 100), 'error');
      return;
    }

    if (res.ok && data.status === 'success') {
      emit('notify', data.message || 'Saved successfully', 'success');
      showModal.value = false;
      await loadData();
    } else {
      emit('notify', data.message || 'Operation failed', 'error');
    }
  } catch (e) {
    emit('notify', (props.lang === 'ar' ? 'فشل حفظ المحتوى: ' : 'Failed to save content: ') + e.message, 'error');
  } finally {
    saving.value = false;
  }
}

async function deleteNews(item) {
  if (!confirm(props.lang === 'ar' ? `هل أنت متأكد من حذف الخبر "${item.title}"؟` : `Are you sure you want to delete "${item.title}"?`)) {
    return;
  }
  const token = authService.token.value || '';
  try {
    const res = await fetch(`./api.php?action=news&id=${item.id}`, {
      method: 'DELETE',
      headers: { 'Authorization': `Bearer ${token}` }
    });
    const data = await res.json();
    if (res.ok && data.status === 'success') {
      emit('notify', data.message || 'News deleted', 'success');
      await loadData();
    }
  } catch (e) {}
}

async function deleteArticle(item) {
  if (!confirm(props.lang === 'ar' ? `هل أنت متأكد من حذف المقال "${item.title}"؟` : `Are you sure you want to delete "${item.title}"?`)) {
    return;
  }
  const token = authService.token.value || '';
  try {
    const res = await fetch(`./api.php?action=articles&id=${item.id}`, {
      method: 'DELETE',
      headers: { 'Authorization': `Bearer ${token}` }
    });
    const data = await res.json();
    if (res.ok && data.status === 'success') {
      emit('notify', data.message || 'Article deleted', 'success');
      await loadData();
    }
  } catch (e) {}
}

onMounted(() => {
  loadData();
});
</script>
