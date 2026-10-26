<template>
  <div class="py-12 bg-slate-50 min-h-screen">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
      <div class="text-center space-y-2">
        <h1 class="text-3xl font-extrabold text-slate-900">
          {{ lang === 'ar' ? 'متابعة حالة طلب العضوية' : 'Track Membership Application' }}
        </h1>
        <p class="text-sm text-slate-500">
          {{ lang === 'ar' ? 'أدخل رقم طلبك (مثل #001) أو بريدك الإلكتروني لمعرفة الحالة الحالية' : 'Enter your Application ID (e.g. #001) or Email Address to check review status.' }}
        </p>
      </div>

      <!-- Search Card -->
      <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm space-y-4">
        <div class="flex flex-col sm:flex-row gap-3">
          <input 
            v-model="query" 
            type="text" 
            placeholder="e.g. #001 or ahmed@aau.edu" 
            class="flex-1 px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white"
            @keyup.enter="handleSearch"
          />
          <button 
            @click="handleSearch"
            class="px-6 py-3 bg-blue-900 hover:bg-blue-800 text-white font-bold rounded-xl text-sm shadow-md transition-all flex items-center justify-center gap-2"
          >
            <Search class="w-4 h-4" />
            <span>{{ lang === 'ar' ? 'استعلام' : 'Track Status' }}</span>
          </button>
        </div>
      </div>

      <!-- Result Card -->
      <div v-if="searchResult" class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-lg space-y-6">
        <div class="flex items-center justify-between border-b pb-4">
          <div>
            <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Application</span>
            <h2 class="text-2xl font-black text-slate-900">{{ searchResult.id }}</h2>
          </div>
          <span 
            class="px-4 py-1.5 rounded-full text-xs font-extrabold uppercase tracking-wider"
            :class="{
              'bg-amber-100 text-amber-800 border border-amber-200': searchResult.status === 'Pending',
              'bg-emerald-100 text-emerald-800 border border-emerald-200': searchResult.status === 'Accepted',
              'bg-rose-100 text-rose-800 border border-rose-200': searchResult.status === 'Rejected'
            }"
          >
            {{ searchResult.status }}
          </span>
        </div>

        <div class="grid grid-cols-2 gap-4 text-xs sm:text-sm">
          <div>
            <span class="text-slate-400 block">{{ t('fullName') }}</span>
            <strong class="text-slate-800">{{ searchResult.name }}</strong>
          </div>
          <div>
            <span class="text-slate-400 block">{{ t('email') }}</span>
            <strong class="text-slate-800">{{ searchResult.email }}</strong>
          </div>
          <div>
            <span class="text-slate-400 block">{{ t('department') }}</span>
            <strong class="text-slate-800">{{ searchResult.major }}</strong>
          </div>
          <div>
            <span class="text-slate-400 block">{{ t('appliedOn') }}</span>
            <strong class="text-slate-800">{{ searchResult.appliedOn }}</strong>
          </div>
        </div>

        <div class="p-4 rounded-2xl bg-blue-50/60 border border-blue-100 text-xs text-blue-900 space-y-1">
          <strong class="block font-bold">{{ lang === 'ar' ? 'ملاحظة لجنة العضوية:' : 'Committee Review Note:' }}</strong>
          <p>{{ searchResult.notes || (lang === 'ar' ? 'طلبك قيد الفرز والمراجعة الإدارية.' : 'Your application is currently being processed by the branch committee.') }}</p>
        </div>
      </div>

      <div v-else-if="searched && !searchResult" class="text-center py-12 bg-white rounded-3xl border border-slate-200">
        <p class="text-slate-500 text-sm">
          {{ lang === 'ar' ? 'لم يتم العثور على أي طلب يطابق هذا الرقم أو البريد.' : 'No application found with the provided details. Please verify and try again.' }}
        </p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { Search } from 'lucide-vue-next';
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

const query = ref('');
const searched = ref(false);
const searchResult = ref(null);

function handleSearch() {
  if (!query.value.trim()) return;
  searched.value = true;
  const apps = storageService.getApplications();
  const q = query.value.trim().toLowerCase();
  searchResult.value = apps.find(a => 
    a.id.toLowerCase() === q || 
    a.id.toLowerCase() === `#${q}` ||
    a.email.toLowerCase() === q
  ) || null;
}
</script>
