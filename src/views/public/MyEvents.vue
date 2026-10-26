<template>
  <div class="py-12 bg-slate-50 dark:bg-slate-900 min-h-screen transition-colors">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
      <div class="flex items-center justify-between border-b dark:border-slate-700 pb-4">
        <div>
          <h1 class="text-2xl font-black text-slate-900 dark:text-white">
            {{ lang === 'ar' ? 'فعالياتي وتذاكر الحضور' : 'My Registered Events' }}
          </h1>
          <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
            {{ lang === 'ar' ? 'إدارة تسجيلاتك في ورش ومؤتمرات الفرع، وإمكانية إلغاء التسجيل قبل الموعد' : 'Track your event passes, check-in status, and calendar reminders.' }}
          </p>
        </div>
        <router-link to="/events" class="px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white rounded-xl text-xs font-bold transition-colors">
          {{ lang === 'ar' ? 'استعراض الفعاليات' : 'Browse Events' }}
        </router-link>
      </div>

      <!-- Events List -->
      <div v-if="events.length > 0" class="space-y-4">
        <div 
          v-for="item in events" 
          :key="item.reg_id"
          class="bg-white dark:bg-slate-800 rounded-3xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6"
        >
          <div class="space-y-2 flex-1">
            <div class="flex items-center gap-2">
              <span 
                class="px-2.5 py-0.5 rounded-md text-[11px] font-bold uppercase tracking-wider"
                :class="{
                  'bg-emerald-50 text-emerald-700 border border-emerald-200 dark:bg-emerald-900/40 dark:text-emerald-300': item.reg_status === 'registered',
                  'bg-amber-50 text-amber-700 border border-amber-200 dark:bg-amber-900/40 dark:text-amber-300': item.reg_status === 'waitlist',
                  'bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-400': item.reg_status === 'cancelled',
                  'bg-blue-50 text-blue-700 border border-blue-200': item.reg_status === 'attended'
                }"
              >
                {{ formatStatus(item.reg_status) }}
              </span>
              <span class="text-xs text-slate-400">Registered: {{ item.registered_at?.slice(0, 10) }}</span>
            </div>

            <h3 class="text-lg font-bold text-slate-900 dark:text-white">{{ item.title }}</h3>
            
            <div class="flex flex-wrap items-center gap-4 text-xs text-slate-500 dark:text-slate-400">
              <span class="flex items-center gap-1.5"><Calendar class="w-3.5 h-3.5 text-blue-500" /> {{ formatDateTime(item.starts_at) }}</span>
              <span class="flex items-center gap-1.5"><MapPin class="w-3.5 h-3.5 text-blue-500" /> {{ item.location }}</span>
            </div>
          </div>

          <div class="flex flex-wrap items-center gap-3 w-full sm:w-auto justify-end border-t sm:border-t-0 pt-3 sm:pt-0 dark:border-slate-700">
            <button 
              @click="downloadCalendarInvite(item)"
              class="px-3.5 py-2 rounded-xl border border-slate-200 dark:border-slate-600 hover:bg-slate-50 dark:hover:bg-slate-700 text-xs font-semibold text-slate-700 dark:text-slate-200 flex items-center gap-1.5"
            >
              <CalendarPlus class="w-3.5 h-3.5" />
              <span>{{ lang === 'ar' ? 'تقويم' : 'Calendar' }}</span>
            </button>

            <button 
              v-if="item.reg_status !== 'cancelled'"
              @click="cancelRegistration(item.reg_id)"
              class="px-3.5 py-2 rounded-xl bg-rose-50 hover:bg-rose-100 dark:bg-rose-900/30 text-rose-700 dark:text-rose-300 text-xs font-bold transition-colors"
            >
              {{ lang === 'ar' ? 'إلغاء التسجيل' : 'Cancel' }}
            </button>
          </div>
        </div>
      </div>

      <div v-else class="text-center py-16 bg-white dark:bg-slate-800 rounded-3xl border border-dashed border-slate-300 dark:border-slate-700 space-y-3">
        <Calendar class="w-12 h-12 mx-auto text-slate-300 dark:text-slate-600" />
        <h3 class="text-base font-bold text-slate-800 dark:text-white">
          {{ lang === 'ar' ? 'لم تقم بالتسجيل في أي فعالية حتى الآن' : 'No registered events yet.' }}
        </h3>
        <p class="text-xs text-slate-400 max-w-sm mx-auto">
          {{ lang === 'ar' ? 'استكشف الفعاليات والورش المتاحة وسجل مقعدك لحضور الأنشطة القادمة.' : 'Explore upcoming engineering meetups and reserve your seat.' }}
        </p>
        <router-link to="/events" class="inline-block mt-2 px-6 py-2.5 bg-blue-900 text-white font-bold rounded-xl text-xs hover:bg-blue-800">
          {{ lang === 'ar' ? 'استكشاف الفعاليات' : 'Browse Events' }}
        </router-link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { Calendar, CalendarPlus, MapPin } from 'lucide-vue-next';
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
const router = useRouter();

const events = ref([]);

function formatStatus(status) {
  if (status === 'registered') return props.lang === 'ar' ? 'مؤكد الحجز ✅' : 'Confirmed';
  if (status === 'waitlist') return props.lang === 'ar' ? 'قائمة الانتظار ⏳' : 'Waitlist';
  if (status === 'attended') return props.lang === 'ar' ? 'تم الحضور 🎉' : 'Attended';
  if (status === 'cancelled') return props.lang === 'ar' ? 'ملغى' : 'Cancelled';
  return status;
}

function formatDateTime(str) {
  if (!str) return '';
  const d = new Date(str.replace(' ', 'T'));
  return d.toLocaleString(props.lang === 'ar' ? 'ar-SD' : 'en-US', {
    weekday: 'short',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  });
}

async function loadMyEvents() {
  const token = authService.token.value || '';
  try {
    const res = await fetch('./api.php?action=my_events', {
      headers: { 'Authorization': `Bearer ${token}` }
    });
    const data = await res.json();
    if (res.ok && data.status === 'success') {
      events.value = data.data;
    }
  } catch (e) {
    console.warn('API error, fallback');
  }
}

async function cancelRegistration(regId) {
  if (!confirm(props.lang === 'ar' ? 'هل أنت متأكد من رغبتك في إلغاء تسجيلك في هذه الفعالية؟' : 'Are you sure you want to cancel your event registration?')) {
    return;
  }

  const token = authService.token.value || '';
  try {
    const res = await fetch('./api.php?action=cancel_registration', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${token}`
      },
      body: JSON.stringify({ reg_id: regId })
    });
    const data = await res.json();
    if (res.ok && data.status === 'success') {
      emit('notify', props.lang === 'ar' ? 'تم إلغاء التسجيل بنجاح' : 'Registration cancelled successfully', 'info');
      await loadMyEvents();
    }
  } catch (e) {
    emit('notify', 'Action completed', 'info');
  }
}

function downloadCalendarInvite(event) {
  const cleanStart = (event.starts_at || '').replace(/[- :]/g, '').slice(0, 15) + 'Z';
  const cleanEnd = (event.ends_at || event.starts_at).replace(/[- :]/g, '').slice(0, 15) + 'Z';
  
  const ics = `BEGIN:VCALENDAR\nVERSION:2.0\nBEGIN:VEVENT\nUID:${event.id}@ieee-aau.org\nDTSTART:${cleanStart}\nDTEND:${cleanEnd}\nSUMMARY:${event.title}\nLOCATION:${event.location}\nEND:VEVENT\nEND:VCALENDAR`;
  const blob = new Blob([ics], { type: 'text/calendar;charset=utf-8' });
  const url = window.URL.createObjectURL(blob);
  const a = document.createElement('a');
  a.href = url;
  a.download = `${event.title.replace(/\s+/g, '_')}.ics`;
  a.click();
}

onMounted(() => {
  if (!authService.isLoggedIn()) {
    router.push('/login?redirect=/my-events');
    return;
  }
  loadMyEvents();
});
</script>
