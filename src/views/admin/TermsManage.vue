<template>
  <div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div>
        <h1 class="text-2xl font-black text-slate-900">{{ t('termsManagement') }}</h1>
        <p class="text-xs text-slate-500 mt-1">
          {{ lang === 'ar' ? 'إدارة لوائح وشروط العضوية والتطوع، وحفظ سجل الإصدارات التاريخية وتوليد الـ PDF' : 'Manage membership terms versioning, bylaws revisions, and export official PDF documents.' }}
        </p>
      </div>

      <div>
        <button 
          @click="showCreateModal = true"
          class="px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white text-xs font-semibold rounded-xl shadow-xs transition-all flex items-center gap-1.5"
        >
          <Plus class="w-4 h-4" />
          <span>{{ t('createVersion') }}</span>
        </button>
      </div>
    </div>

    <!-- Terms Versions Table (Matching Screen 19) -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
      <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
        <h3 class="font-bold text-slate-900 text-sm">Published Terms &amp; Bylaws Versions</h3>
        <span class="text-xs text-slate-400">Total: {{ termsData.versions.length }}</span>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full text-left text-xs text-slate-600">
          <thead class="bg-slate-50 text-slate-400 font-semibold uppercase tracking-wider border-b border-slate-100">
            <tr>
              <th class="py-3.5 px-6">Version</th>
              <th class="py-3.5 px-6">Title</th>
              <th class="py-3.5 px-6">Effective Date</th>
              <th class="py-3.5 px-6">Status</th>
              <th class="py-3.5 px-6 text-right">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100 font-medium">
            <tr 
              v-for="v in termsData.versions" 
              :key="v.version"
              class="hover:bg-slate-50 transition-colors"
            >
              <td class="py-3.5 px-6 font-bold text-blue-900 whitespace-nowrap">{{ v.version }}</td>
              <td class="py-3.5 px-6 font-semibold text-slate-800 whitespace-nowrap">{{ v.title }}</td>
              <td class="py-3.5 px-6 text-slate-500 whitespace-nowrap">{{ v.effectiveDate }}</td>
              <td class="py-3.5 px-6 whitespace-nowrap">
                <span 
                  class="px-2 py-0.5 rounded text-[11px] font-bold"
                  :class="v.status === 'Active' ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-slate-100 text-slate-600'"
                >
                  {{ v.status }}
                </span>
              </td>
              <td class="py-3.5 px-6 text-right whitespace-nowrap space-x-1">
                <button 
                  @click="selectVersion(v)"
                  class="p-1.5 text-slate-500 hover:text-blue-900 hover:bg-blue-50 rounded-lg transition-colors" 
                  title="Edit Terms"
                >
                  <Edit3 class="w-4 h-4" />
                </button>
                <button 
                  @click="downloadPDF(v)"
                  class="p-1.5 text-blue-900 hover:bg-blue-50 rounded-lg transition-colors" 
                  title="Download PDF"
                >
                  <Download class="w-4 h-4" />
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- View / Edit Terms Section (Matching Screen 19) -->
    <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs space-y-4">
      <div class="flex items-center justify-between border-b pb-3">
        <div>
          <h3 class="font-bold text-slate-900 text-sm">{{ t('viewEditTerms') }}</h3>
          <span class="text-xs text-slate-400">Currently editing: {{ activeVersion.version }} ({{ activeVersion.title }})</span>
        </div>
        <div class="flex items-center gap-2">
          <button 
            @click="downloadPDF(activeVersion)"
            class="px-4 py-2 border border-slate-200 text-slate-700 text-xs font-semibold rounded-xl hover:bg-slate-50 transition-colors flex items-center gap-1.5"
          >
            <Download class="w-4 h-4" />
            <span>Download PDF</span>
          </button>
          <button 
            @click="saveCurrentTerms"
            class="px-5 py-2 bg-blue-900 hover:bg-blue-800 text-white text-xs font-bold rounded-xl shadow-xs transition-colors flex items-center gap-1.5"
          >
            <Save class="w-4 h-4" />
            <span>{{ t('saveChanges') }}</span>
          </button>
        </div>
      </div>

      <div>
        <textarea 
          v-model="activeVersion.content" 
          rows="12" 
          class="w-full p-4 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white leading-relaxed"
        ></textarea>
      </div>
    </div>

    <!-- Create New Version Modal -->
    <div v-if="showCreateModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
      <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 space-y-4 shadow-2xl">
        <div class="flex items-center justify-between border-b pb-3">
          <h3 class="font-bold text-slate-900 text-lg">{{ t('createVersion') }}</h3>
          <button @click="showCreateModal = false" class="text-slate-400 hover:text-slate-600"><X class="w-5 h-5" /></button>
        </div>

        <form @submit.prevent="createNewVersion" class="space-y-3 text-xs">
          <div>
            <label class="block font-bold text-slate-700 uppercase mb-1">Version Identifier *</label>
            <input v-model="newVersionForm.version" required type="text" placeholder="e.g. v2.1" class="w-full px-3 py-2 border rounded-xl" />
          </div>
          <div>
            <label class="block font-bold text-slate-700 uppercase mb-1">Version Title *</label>
            <input v-model="newVersionForm.title" required type="text" placeholder="IEEE AAU SB Terms & Bylaws 2025/2026" class="w-full px-3 py-2 border rounded-xl" />
          </div>
          <div>
            <label class="block font-bold text-slate-700 uppercase mb-1">Terms Content</label>
            <textarea v-model="newVersionForm.content" rows="6" class="w-full px-3 py-2 border rounded-xl font-mono"></textarea>
          </div>
          <div class="pt-3 border-t flex justify-end gap-2">
            <button type="button" @click="showCreateModal = false" class="px-4 py-2 border rounded-xl text-slate-600">Cancel</button>
            <button type="submit" class="px-5 py-2 bg-blue-900 text-white font-bold rounded-xl hover:bg-blue-800">
              Publish Version
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { Plus, Edit3, Download, Save, X } from 'lucide-vue-next';
import { storageService } from '../../services/storage';
import { downloadTermsPDF } from '../../services/pdfExport';

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

const emit = defineEmits(['notify']);

const termsData = ref(storageService.getTerms());
const activeVersion = ref(termsData.value.versions[0] || {});
const showCreateModal = ref(false);

const newVersionForm = ref({
  version: 'v2.1',
  title: 'IEEE AAU SB Terms Revision 2025/2026',
  content: activeVersion.value.content || ''
});

function selectVersion(v) {
  activeVersion.value = v;
}

function saveCurrentTerms() {
  storageService.saveTerms(termsData.value);
  storageService.logActivity('Terms Edited', 'Admin', `Updated ${activeVersion.value.version} content`);
  emit('notify', `Terms version ${activeVersion.value.version} saved successfully!`, 'success');
}

function createNewVersion() {
  storageService.addTermsVersion(newVersionForm.value);
  termsData.value = storageService.getTerms();
  activeVersion.value = termsData.value.versions[0];
  showCreateModal.value = false;
  emit('notify', `Version ${newVersionForm.value.version} published as active terms!`, 'success');
}

function downloadPDF(v) {
  downloadTermsPDF(v.version, v.content);
  emit('notify', `Terms ${v.version} PDF downloaded!`, 'success');
}
</script>
