<template>
  <div class="py-12 bg-slate-50 dark:bg-slate-900 min-h-screen transition-colors">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
      <div class="flex items-center justify-between border-b dark:border-slate-700 pb-4">
        <div>
          <h1 class="text-2xl font-black text-slate-900 dark:text-white">
            {{ lang === 'ar' ? 'طلباتي ومتابعة التقديم' : 'My Applications' }}
          </h1>
          <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
            {{ lang === 'ar' ? 'متابعة مراحل فرز طلبات التطوع والعضوية ومواعيد المعاينات' : 'Track the review pipeline and screening status of your submissions.' }}
          </p>
        </div>
        <router-link to="/volunteer" class="px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white rounded-xl text-xs font-bold transition-colors">
          {{ lang === 'ar' ? 'تقديم طلب جديد' : 'New Application' }}
        </router-link>
      </div>

      <!-- Applications List -->
      <div v-if="applications.length > 0" class="space-y-4">
        <div 
          v-for="app in applications" 
          :key="app.id"
          class="bg-white dark:bg-slate-800 rounded-3xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm space-y-4"
        >
          <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b dark:border-slate-700 pb-3">
            <div>
              <span class="text-xs font-mono font-bold text-blue-900 dark:text-blue-400">{{ app.id }}</span>
              <h3 class="text-base font-bold text-slate-900 dark:text-white mt-0.5">
                {{ app.team ? `${app.team} Team Application` : 'Volunteer Application' }}
              </h3>
            </div>

            <!-- Status Badge -->
            <span 
              class="inline-block px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider"
              :class="{
                'bg-blue-50 text-blue-800 border border-blue-200': app.status === 'new',
                'bg-amber-50 text-amber-800 border border-amber-200': app.status === 'screening' || app.status === 'Pending',
                'bg-purple-50 text-purple-800 border border-purple-200': app.status === 'candidate_interview',
                'bg-emerald-50 text-emerald-800 border border-emerald-200': app.status === 'accepted' || app.status === 'Accepted',
                'bg-rose-50 text-rose-800 border border-rose-200': app.status === 'rejected' || app.status === 'Rejected'
              }"
            >
              {{ formatStatus(app.status) }}
            </span>
          </div>

          <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs text-slate-600 dark:text-slate-300">
            <div>
              <span class="text-slate-400 block text-[11px]">Applicant</span>
              <strong>{{ app.full_name || app.name }}</strong>
            </div>
            <div>
              <span class="text-slate-400 block text-[11px]">Applied Date</span>
              <strong>{{ app.submitted_at?.slice(0, 10) || app.appliedOn }}</strong>
            </div>
            <div>
              <span class="text-slate-400 block text-[11px]">Terms Version</span>
              <strong>{{ app.terms_version || 'v2.0' }}</strong>
            </div>
            <div>
              <span class="text-slate-400 block text-[11px]">Batch</span>
              <strong>{{ app.batch || '2025' }}</strong>
            </div>
          </div>

          <!-- Reviewer Note if available -->
          <div v-if="app.reviewer_notes" class="p-3 bg-blue-50/50 dark:bg-slate-700/50 rounded-xl text-xs text-blue-900 dark:text-blue-300">
            <strong>{{ lang === 'ar' ? 'ملاحظة الإدارة:' : 'HR Note:' }}</strong> {{ app.reviewer_notes }}
          </div>
        </div>
      </div>

      <div v-else class="text-center py-16 bg-white dark:bg-slate-800 rounded-3xl border border-dashed border-slate-300 dark:border-slate-700 space-y-3">
        <FileText class="w-12 h-12 mx-auto text-slate-300 dark:text-slate-600" />
        <h3 class="text-base font-bold text-slate-800 dark:text-white">
          {{ lang === 'ar' ? 'لا توجد طلبات مسجلة بحسابك حالياً' : 'No applications found under this account.' }}
        </h3>
        <p class="text-xs text-slate-400 max-w-sm mx-auto">
          {{ lang === 'ar' ? 'يمكنك تقديم طلب انضمام كمتطوع أو عضو رسمي في الفرع الآن.' : 'You can submit an application to volunteer or join as an official IEEE member.' }}
        </p>
        <router-link to="/volunteer" class="inline-block mt-2 px-6 py-2.5 bg-blue-900 text-white font-bold rounded-xl text-xs hover:bg-blue-800">
          {{ lang === 'ar' ? 'تقديم طلب تطوع' : 'Apply to Volunteer' }}
        </router-link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { FileText } from 'lucide-vue-next';
import { authService } from '../../services/auth';
import { storageService } from '../../services/storage';

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

const router = useRouter();
const applications = ref([]);

function formatStatus(s) {
  if (s === 'new') return props.lang === 'ar' ? 'جديد (New)' : 'New';
  if (s === 'screening' || s === 'Pending') return props.lang === 'ar' ? 'قيد الفرز (Screening)' : 'Screening';
  if (s === 'candidate_interview') return props.lang === 'ar' ? 'مرشح للمعاينة (Interview)' : 'Candidate Interview';
  if (s === 'accepted' || s === 'Accepted') return props.lang === 'ar' ? 'مقبول (Accepted) 🎉' : 'Accepted';
  if (s === 'rejected' || s === 'Rejected') return props.lang === 'ar' ? 'مرفوض (Rejected)' : 'Rejected';
  return s;
}

function loadMyApps() {
  const user = authService.getUser();
  if (!user) return;

  // Combine storage apps and check
  const allApps = storageService.getApplications();
  applications.value = allApps.filter(a => a.email?.toLowerCase() === user.email?.toLowerCase());

  // If empty, fetch from MySQL via API
  if (applications.value.length === 0) {
    const token = authService.token.value || '';
    fetch('./api.php?action=volunteer_applications', {
      headers: { 'Authorization': `Bearer ${token}` }
    })
    .then(r => r.json())
    .then(d => {
      if (d.status === 'success' && d.data) {
        applications.value = d.data.filter(a => a.email?.toLowerCase() === user.email?.toLowerCase());
      }
    })
    .catch(() => {});
  }
}

onMounted(() => {
  if (!authService.isLoggedIn()) {
    router.push('/login?redirect=/my-applications');
    return;
  }
  loadMyApps();
});
</script>
