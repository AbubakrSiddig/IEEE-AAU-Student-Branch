<template>
  <div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div>
        <h1 class="text-2xl font-black text-slate-900 dark:text-white">
          {{ lang === 'ar' ? 'إدارة أعداد ومجلة الفرع' : 'Magazine Issues Management' }}
        </h1>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
          {{ lang === 'ar' ? 'التحكم في نشر وتعديل أعداد مجلة IEEE AAU SB، رفع ملفات PDF وأغلفة النسخ' : 'Manage periodic magazine publications, upload issue PDFs, covers, and volume details.' }}
        </p>
      </div>

      <button 
        v-if="canEdit"
        @click="openAddModal"
        class="px-4 py-2.5 bg-blue-900 hover:bg-blue-800 text-white text-xs font-bold rounded-xl shadow-xs transition-all flex items-center gap-2 self-start sm:self-auto"
      >
        <Plus class="w-4 h-4" />
        <span>{{ lang === 'ar' ? 'إضافة عدد جديد' : 'New Magazine Issue' }}</span>
      </button>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
      <div class="p-4 bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/80 dark:border-slate-700 shadow-xs">
        <span class="text-[11px] font-bold text-slate-400 uppercase">{{ lang === 'ar' ? 'إجمالي الأعداد' : 'Total Issues' }}</span>
        <div class="text-2xl font-black text-slate-900 dark:text-white mt-1">{{ issues.length }}</div>
      </div>
      <div class="p-4 bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/80 dark:border-slate-700 shadow-xs">
        <span class="text-[11px] font-bold text-slate-400 uppercase">{{ lang === 'ar' ? 'أعداد منشورة' : 'Published' }}</span>
        <div class="text-2xl font-black text-emerald-600 mt-1">
          {{ issues.filter(i => i.status === 'published').length }}
        </div>
      </div>
      <div class="p-4 bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/80 dark:border-slate-700 shadow-xs">
        <span class="text-[11px] font-bold text-slate-400 uppercase">{{ lang === 'ar' ? 'أعداد مخفية / مسودة' : 'Hidden / Draft' }}</span>
        <div class="text-2xl font-black text-amber-600 mt-1">
          {{ issues.filter(i => i.status === 'hidden' || i.status === 'draft').length }}
        </div>
      </div>
      <div class="p-4 bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/80 dark:border-slate-700 shadow-xs">
        <span class="text-[11px] font-bold text-slate-400 uppercase">{{ lang === 'ar' ? 'أحدث سنة إصدار' : 'Latest Year' }}</span>
        <div class="text-2xl font-black text-blue-600 dark:text-blue-400 mt-1">
          {{ issues[0]?.year || '2025' }}
        </div>
      </div>
    </div>

    <!-- Filter & Search Bar -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 border border-slate-200/80 dark:border-slate-700 shadow-xs flex flex-wrap items-center justify-between gap-3">
      <div class="flex items-center gap-2">
        <button 
          v-for="st in ['all', 'published', 'hidden', 'archived']" 
          :key="st"
          @click="statusFilter = st"
          class="px-3 py-1.5 rounded-lg text-xs font-bold capitalize transition-colors"
          :class="statusFilter === st 
            ? 'bg-blue-900 dark:bg-blue-600 text-white' 
            : 'bg-slate-50 dark:bg-slate-700 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-600'"
        >
          {{ st }}
        </button>
      </div>

      <div class="relative w-full sm:w-64">
        <input 
          v-model="searchQuery" 
          type="text" 
          :placeholder="lang === 'ar' ? 'بحث في أعداد المجلة...' : 'Search issues by title, volume...'"
          class="w-full pl-9 pr-3 py-2 bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-xs dark:text-white"
        />
        <Search class="w-3.5 h-3.5 text-slate-400 absolute left-3 top-2.5" />
      </div>
    </div>

    <!-- Issues Table -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/80 dark:border-slate-700 shadow-xs overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-left text-xs text-slate-600 dark:text-slate-300">
          <thead class="bg-slate-50 dark:bg-slate-700/50 text-slate-400 font-semibold uppercase tracking-wider border-b border-slate-100 dark:border-slate-700">
            <tr>
              <th class="py-3.5 px-6">Cover</th>
              <th class="py-3.5 px-6">Issue &amp; Title</th>
              <th class="py-3.5 px-6">Year &amp; Published Date</th>
              <th class="py-3.5 px-6">Status</th>
              <th class="py-3.5 px-6">PDF File</th>
              <th class="py-3.5 px-6 text-right">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100 dark:divide-slate-700 font-medium">
            <tr 
              v-for="item in filteredIssues" 
              :key="item.id"
              class="hover:bg-slate-50 dark:hover:bg-slate-700/40 transition-colors"
            >
              <td class="py-3.5 px-6">
                <img 
                  :src="item.cover" 
                  :alt="item.volume" 
                  class="w-12 h-16 object-cover rounded-lg shadow-xs bg-slate-100 dark:bg-slate-700 border dark:border-slate-600"
                />
              </td>
              <td class="py-3.5 px-6">
                <div class="font-bold text-slate-900 dark:text-white text-sm">{{ item.title }}</div>
                <div class="text-[11px] font-mono text-blue-900 dark:text-blue-400 mt-0.5">{{ item.volume }}</div>
                <div class="text-[11px] text-slate-400 max-w-xs truncate mt-0.5">{{ item.description }}</div>
              </td>
              <td class="py-3.5 px-6 whitespace-nowrap">
                <div class="font-bold text-slate-900 dark:text-white">{{ item.year }}</div>
                <div class="text-[11px] text-slate-400">{{ item.published_date }}</div>
              </td>
              <td class="py-3.5 px-6 whitespace-nowrap">
                <span 
                  class="px-2.5 py-1 rounded-md text-[11px] font-bold border"
                  :class="{
                    'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-800 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800': item.status === 'published',
                    'bg-amber-50 dark:bg-amber-950/40 text-amber-800 dark:text-amber-300 border-amber-200 dark:border-amber-800': item.status === 'hidden' || item.status === 'draft',
                    'bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-400 border-slate-200 dark:border-slate-600': item.status === 'archived'
                  }"
                >
                  {{ item.status }}
                </span>
              </td>
              <td class="py-3.5 px-6 whitespace-nowrap">
                <a 
                  v-if="item.file_url && item.file_url !== '#'"
                  :href="item.file_url" 
                  target="_blank"
                  class="text-blue-900 dark:text-blue-400 hover:underline flex items-center gap-1 font-semibold"
                >
                  <FileText class="w-3.5 h-3.5" />
                  <span>View PDF</span>
                </a>
                <span v-else class="text-slate-400 text-[11px] italic">No file attached</span>
              </td>
              <td class="py-3.5 px-6 text-right whitespace-nowrap space-x-1">
                <button 
                  v-if="canEdit"
                  @click="openEditModal(item)"
                  class="p-1.5 text-blue-900 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-slate-700 rounded-lg"
                  title="Edit Issue"
                >
                  <Edit3 class="w-4 h-4" />
                </button>
                <button 
                  v-if="canEdit"
                  @click="deleteIssue(item)"
                  class="p-1.5 text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-900/20 rounded-lg"
                  title="Delete Issue"
                >
                  <Trash2 class="w-4 h-4" />
                </button>
              </td>
            </tr>

            <tr v-if="filteredIssues.length === 0">
              <td colspan="6" class="py-8 text-center text-slate-400">
                {{ lang === 'ar' ? 'لا توجد أعداد مجلة مطابقة للبحث' : 'No magazine issues found.' }}
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
              {{ isEditing ? (lang === 'ar' ? 'تعديل بيانات العدد' : 'Edit Magazine Issue') : (lang === 'ar' ? 'إضافة عدد جديد من المجلة' : 'New Magazine Issue') }}
            </h3>
            <p class="text-xs text-slate-400 mt-0.5">
              {{ lang === 'ar' ? 'أدخل تفاصيل النسخة وارفع الغلاف وملف PDF' : 'Specify volume details, upload cover image and magazine PDF' }}
            </p>
          </div>
          <button @click="showModal = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
            <X class="w-5 h-5" />
          </button>
        </div>

        <form @submit.prevent="saveIssue" class="space-y-4 text-xs">
          <!-- Volume and Title -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
              <label class="block font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">
                {{ lang === 'ar' ? 'رقم النسخة / المجلد' : 'Volume / Issue Number' }} *
              </label>
              <input 
                v-model="form.volume" 
                required 
                type="text" 
                placeholder="Vol. 13 | Issue 1" 
                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-600"
              />
            </div>
            <div>
              <label class="block font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">
                {{ lang === 'ar' ? 'عنوان العدد' : 'Issue Title' }} *
              </label>
              <input 
                v-model="form.title" 
                required 
                type="text" 
                placeholder="Engineering a Smarter Tomorrow" 
                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-600"
              />
            </div>
          </div>

          <!-- Year and Published Date -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
              <label class="block font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">
                {{ lang === 'ar' ? 'السنة' : 'Year' }} *
              </label>
              <input 
                v-model="form.year" 
                required 
                type="text" 
                placeholder="2025" 
                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-600"
              />
            </div>
            <div>
              <label class="block font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">
                {{ lang === 'ar' ? 'تاريخ الإصدار' : 'Publication Date' }} *
              </label>
              <input 
                v-model="form.published_date" 
                required 
                type="text" 
                placeholder="April 2025" 
                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-600"
              />
            </div>
          </div>

          <!-- Description -->
          <div>
            <label class="block font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">
              {{ lang === 'ar' ? 'نبذة عن العدد والمحتويات' : 'Issue Summary & Articles Included' }}
            </label>
            <textarea 
              v-model="form.description" 
              rows="3" 
              placeholder="Cutting-edge student research, AI advancements, and branch project updates..."
              class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-600"
            ></textarea>
          </div>

          <!-- Direct Cover Image Upload (No URL required) -->
          <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 space-y-3">
            <div class="flex items-center justify-between">
              <label class="block font-bold text-slate-700 dark:text-slate-300 uppercase text-xs flex items-center gap-2">
                <Camera class="w-4 h-4 text-blue-900 dark:text-blue-400" />
                <span>{{ lang === 'ar' ? 'غلاف المجلة (صورة الغلاف)' : 'Magazine Cover Image' }} *</span>
              </label>
              <span v-if="form.cover" class="text-[10px] text-emerald-600 dark:text-emerald-400 font-semibold flex items-center gap-1">
                <Check class="w-3 h-3" /> {{ lang === 'ar' ? 'تم اختيار الغلاف' : 'Cover ready' }}
              </span>
            </div>

            <!-- If Cover uploaded -->
            <div v-if="form.cover" class="flex flex-col sm:flex-row items-center gap-4 p-3 bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-600">
              <img :src="form.cover" alt="Cover Preview" class="w-20 h-28 object-cover rounded-lg shadow-xs border border-slate-200 dark:border-slate-700 shrink-0" />
              <div class="flex-1 space-y-2 text-center sm:text-left w-full">
                <div class="text-[11px] font-mono text-slate-400 truncate max-w-xs">{{ form.cover }}</div>
                <div class="flex items-center gap-2 justify-center sm:justify-start">
                  <label class="px-3 py-1.5 bg-blue-900 text-white hover:bg-blue-800 rounded-lg text-xs font-bold cursor-pointer transition-colors inline-flex items-center gap-1.5 shadow-xs">
                    <Upload class="w-3.5 h-3.5" />
                    <span>{{ uploadingCover ? (lang === 'ar' ? 'جاري الرفع...' : 'Uploading...') : (lang === 'ar' ? 'تغيير صورة الغلاف من الجهاز' : 'Change Cover') }}</span>
                    <input type="file" accept="image/*" class="hidden" @change="e => uploadFile(e, 'cover')" />
                  </label>
                  <button 
                    type="button" 
                    @click="form.cover = ''" 
                    class="px-3 py-1.5 text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/30 rounded-lg text-xs font-semibold border border-rose-200 dark:border-rose-900"
                  >
                    {{ lang === 'ar' ? 'حذف' : 'Remove' }}
                  </button>
                </div>
              </div>
            </div>

            <!-- If NO cover uploaded yet: Clickable Dropzone -->
            <div v-else>
              <label class="border-2 border-dashed border-slate-300 dark:border-slate-600 hover:border-blue-500 dark:hover:border-blue-400 rounded-2xl p-6 flex flex-col items-center justify-center gap-2 cursor-pointer bg-white dark:bg-slate-800 transition-all group">
                <div class="w-12 h-12 rounded-full bg-blue-50 dark:bg-blue-950/60 text-blue-900 dark:text-blue-400 flex items-center justify-center group-hover:scale-110 transition-transform">
                  <Upload class="w-6 h-6" />
                </div>
                <div class="text-center space-y-1">
                  <p class="text-xs font-bold text-slate-800 dark:text-slate-200">
                    {{ uploadingCover ? (lang === 'ar' ? 'جاري رفع الغلاف...' : 'Uploading cover...') : (lang === 'ar' ? 'انقر لاختيار صورة الغلاف من جهازك' : 'Click to select cover image from device') }}
                  </p>
                  <p class="text-[10px] text-slate-400">
                    {{ lang === 'ar' ? 'الملفات المدعومة: PNG, JPG, WebP' : 'Supported: PNG, JPG, WebP' }}
                  </p>
                </div>
                <input type="file" accept="image/*" class="hidden" @change="e => uploadFile(e, 'cover')" />
              </label>
            </div>
          </div>

          <!-- Direct PDF Upload (No URL required) -->
          <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 space-y-3">
            <div class="flex items-center justify-between">
              <label class="block font-bold text-slate-700 dark:text-slate-300 uppercase text-xs flex items-center gap-2">
                <FileText class="w-4 h-4 text-rose-600" />
                <span>{{ lang === 'ar' ? 'ملف المجلة الرقمي (PDF)' : 'Magazine PDF File' }}</span>
              </label>
              <span v-if="form.file_url && form.file_url !== '#'" class="text-[10px] text-emerald-600 dark:text-emerald-400 font-semibold flex items-center gap-1">
                <Check class="w-3 h-3" /> {{ lang === 'ar' ? 'تم اختيار الملف بنجاح' : 'PDF ready' }}
              </span>
            </div>

            <!-- If PDF uploaded -->
            <div v-if="form.file_url && form.file_url !== '#'" class="flex flex-col sm:flex-row items-center gap-4 p-3 bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-600">
              <div class="w-12 h-14 rounded-lg bg-rose-50 dark:bg-rose-950/40 text-rose-600 flex items-center justify-center shrink-0">
                <FileText class="w-6 h-6" />
              </div>
              <div class="flex-1 space-y-1.5 text-center sm:text-left w-full">
                <div class="text-xs font-bold text-slate-900 dark:text-white truncate max-w-sm">{{ form.file_url }}</div>
                <div class="flex items-center gap-2 justify-center sm:justify-start">
                  <label class="px-3 py-1.5 bg-slate-800 dark:bg-slate-700 text-white hover:bg-slate-900 rounded-lg text-xs font-bold cursor-pointer transition-colors inline-flex items-center gap-1.5 shadow-xs">
                    <Upload class="w-3.5 h-3.5" />
                    <span>{{ uploadingPdf ? (lang === 'ar' ? 'جاري الرفع...' : 'Uploading...') : (lang === 'ar' ? 'استبدال ملف الـ PDF من الجهاز' : 'Replace PDF') }}</span>
                    <input type="file" accept="application/pdf" class="hidden" @change="e => uploadFile(e, 'pdf')" />
                  </label>
                  <a :href="form.file_url" target="_blank" class="px-3 py-1.5 text-blue-600 hover:bg-blue-50 dark:hover:bg-blue-950/30 rounded-lg text-xs font-semibold border border-blue-200 dark:border-blue-900 inline-flex items-center gap-1">
                    <ExternalLink class="w-3 h-3" />
                    <span>{{ lang === 'ar' ? 'معاينة' : 'View' }}</span>
                  </a>
                </div>
              </div>
            </div>

            <!-- If NO PDF uploaded yet -->
            <div v-else>
              <label class="border-2 border-dashed border-slate-300 dark:border-slate-600 hover:border-rose-500 dark:hover:border-rose-400 rounded-2xl p-6 flex flex-col items-center justify-center gap-2 cursor-pointer bg-white dark:bg-slate-800 transition-all group">
                <div class="w-12 h-12 rounded-full bg-rose-50 dark:bg-rose-950/60 text-rose-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                  <FileText class="w-6 h-6" />
                </div>
                <div class="text-center space-y-1">
                  <p class="text-xs font-bold text-slate-800 dark:text-slate-200">
                    {{ uploadingPdf ? (lang === 'ar' ? 'جاري رفع ملف PDF...' : 'Uploading PDF...') : (lang === 'ar' ? 'انقر لاختيار ملف المجلة (PDF) من جهازك' : 'Click to select Magazine PDF from device') }}
                  </p>
                  <p class="text-[10px] text-slate-400">
                    {{ lang === 'ar' ? 'الملفات المدعومة: PDF حتى 64 ميجابايت' : 'Supported: PDF files up to 64MB' }}
                  </p>
                </div>
                <input type="file" accept="application/pdf" class="hidden" @change="e => uploadFile(e, 'pdf')" />
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
              <option value="published">Published (منشور للعامة)</option>
              <option value="hidden">Hidden (مخفي مؤقتاً)</option>
              <option value="archived">Archived (مؤرشف)</option>
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
              <span>{{ saving ? (lang === 'ar' ? 'جاري الحفظ...' : 'Saving...') : (lang === 'ar' ? 'حفظ البيانات' : 'Save Issue') }}</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { Plus, Search, Edit3, Trash2, FileText, Upload, Check, X, Camera, ExternalLink } from 'lucide-vue-next';
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

const issues = ref([]);
const statusFilter = ref('all');
const searchQuery = ref('');
const showModal = ref(false);
const isEditing = ref(false);
const saving = ref(false);
const uploadingCover = ref(false);
const uploadingPdf = ref(false);

const canEdit = computed(() => authService.can('magazine', 'edit'));

const form = ref({
  id: null,
  volume: '',
  title: '',
  year: new Date().getFullYear().toString(),
  published_date: '',
  description: '',
  cover: '',
  file_url: '#',
  status: 'published'
});

async function loadIssues() {
  try {
    const res = await fetch('./api.php?action=magazines');
    const data = await res.json();
    if (res.ok && data.status === 'success') {
      issues.value = data.data;
    }
  } catch (e) {}
}

const filteredIssues = computed(() => {
  return issues.value.filter(item => {
    const matchesStatus = statusFilter.value === 'all' || item.status === statusFilter.value;
    const q = searchQuery.value.toLowerCase();
    const matchesSearch = (item.title || '').toLowerCase().includes(q) || 
      (item.volume || '').toLowerCase().includes(q) || 
      (item.year || '').toString().includes(q);
    return matchesStatus && matchesSearch;
  });
});

function openAddModal() {
  isEditing.value = false;
  form.value = {
    id: null,
    volume: `Vol. 13 | Issue 1`,
    title: '',
    year: new Date().getFullYear().toString(),
    published_date: `${new Date().toLocaleString('en-US', { month: 'long' })} ${new Date().getFullYear()}`,
    description: '',
    cover: '',
    file_url: '',
    status: 'published'
  };
  showModal.value = true;
}

function openEditModal(item) {
  isEditing.value = true;
  form.value = { ...item };
  showModal.value = true;
}

async function uploadFile(event, type) {
  const file = event.target.files?.[0];
  if (!file) return;

  const token = authService.token.value || '';
  const formData = new FormData();
  formData.append('file', file);
  formData.append('token', token);

  const folder = type === 'cover' ? 'covers' : 'magazines';
  if (type === 'cover') uploadingCover.value = true;
  else uploadingPdf.value = true;

  try {
    const res = await fetch(`./api.php?action=upload&folder=${folder}&token=${encodeURIComponent(token)}`, {
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
      if (type === 'cover') {
        form.value.cover = data.url;
      } else {
        form.value.file_url = data.url;
      }
      emit('notify', props.lang === 'ar' ? 'تم رفع الملف بنجاح' : 'File uploaded successfully', 'success');
    } else {
      emit('notify', data.message || (props.lang === 'ar' ? 'فشل رفع الملف' : 'Upload failed'), 'error');
    }
  } catch (err) {
    console.error('Upload network error:', err);
    emit('notify', (props.lang === 'ar' ? 'خطأ أثناء رفع الملف: ' : 'Upload error: ') + err.message, 'error');
  } finally {
    uploadingCover.value = false;
    uploadingPdf.value = false;
  }
}

async function saveIssue() {
  if (!form.value.cover) {
    emit('notify', props.lang === 'ar' ? 'يرجى اختيار ورفع صورة غلاف المجلة أولاً' : 'Please upload magazine cover first', 'error');
    return;
  }
  saving.value = true;
  const token = authService.token.value || '';
  const method = isEditing.value ? 'PUT' : 'POST';
  const url = isEditing.value 
    ? `./api.php?action=magazines&id=${form.value.id}&token=${encodeURIComponent(token)}` 
    : `./api.php?action=magazines&token=${encodeURIComponent(token)}`;

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
      await loadIssues();
    } else {
      emit('notify', data.message || 'Operation failed', 'error');
    }
  } catch (e) {
    emit('notify', (props.lang === 'ar' ? 'فشل حفظ العدد: ' : 'Failed to save issue: ') + e.message, 'error');
  } finally {
    saving.value = false;
  }
}

async function deleteIssue(item) {
  if (!confirm(props.lang === 'ar' ? `هل أنت متأكد من حذف العدد "${item.title}"؟` : `Are you sure you want to delete "${item.title}"?`)) {
    return;
  }

  const token = authService.token.value || '';
  try {
    const res = await fetch(`./api.php?action=magazines&id=${item.id}&token=${encodeURIComponent(token)}`, {
      method: 'DELETE',
      headers: {
        'Authorization': `Bearer ${token}`
      }
    });
    const data = await res.json();
    if (res.ok && data.status === 'success') {
      emit('notify', data.message || 'Deleted successfully', 'success');
      await loadIssues();
    }
  } catch (e) {}
}

onMounted(() => {
  loadIssues();
});
</script>
