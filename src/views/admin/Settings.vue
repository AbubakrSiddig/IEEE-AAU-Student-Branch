<template>
  <div class="space-y-6 max-w-4xl">
    <div>
      <h1 class="text-2xl font-black text-slate-900">{{ t('settings') }}</h1>
      <p class="text-xs text-slate-500 mt-1">
        {{ lang === 'ar' ? 'إعدادات المنصة، الهوية البصرية، وقاعدة البيانات المؤقتة' : 'Platform configuration, branch metadata, and database state management.' }}
      </p>
    </div>

    <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200/80 shadow-xs space-y-6">
      <h2 class="text-base font-bold text-slate-900 border-b pb-3">Branch Details</h2>
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
        <div>
          <label class="block font-bold text-slate-700 uppercase mb-1">Branch Name</label>
          <input type="text" value="IEEE AAU Student Branch" class="w-full px-3 py-2 border rounded-xl bg-slate-50" />
        </div>
        <div>
          <label class="block font-bold text-slate-700 uppercase mb-1">Affiliated University</label>
          <input type="text" value="Alzaeim Alazhari University" class="w-full px-3 py-2 border rounded-xl bg-slate-50" />
        </div>
        <div>
          <label class="block font-bold text-slate-700 uppercase mb-1">Official Contact Email</label>
          <input type="email" value="info@ieee-aau.org" class="w-full px-3 py-2 border rounded-xl bg-slate-50" />
        </div>
        <div>
          <label class="block font-bold text-slate-700 uppercase mb-1">Support Phone</label>
          <input type="text" value="+249 123 456 789" class="w-full px-3 py-2 border rounded-xl bg-slate-50" />
        </div>
      </div>

      <div class="pt-4 border-t">
        <h3 class="text-sm font-bold text-slate-900 mb-2">Seed Data Management</h3>
        <p class="text-xs text-slate-500 mb-4">
          Reset all applications, members, events and logs to the original default mockup state.
        </p>
        <button 
          @click="resetAllData"
          class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white font-bold text-xs rounded-xl shadow-xs transition-colors"
        >
          Reset All Data to Wireframe Defaults
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { storageService } from '../../services/storage';

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

function resetAllData() {
  if (confirm('Are you sure you want to restore the default sample data?')) {
    storageService.resetAll();
    emit('notify', 'Database reset to default wireframe dataset!', 'info');
    setTimeout(() => {
      window.location.reload();
    }, 1000);
  }
}
</script>
