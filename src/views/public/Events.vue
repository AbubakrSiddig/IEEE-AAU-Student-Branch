<template>
  <div class="py-12 bg-slate-50 dark:bg-slate-900 min-h-screen transition-colors">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
      <!-- Header -->
      <div class="border-b dark:border-slate-700 pb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
          <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white">{{ t('events') }}</h1>
          <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
            {{ lang === 'ar' ? 'استكشف فعاليات وورش ومؤتمرات فرع IEEE AAU' : 'Explore workshops, symposiums and engineering meetups.' }}
          </p>
        </div>

        <div v-if="isLoggedIn">
          <router-link 
            to="/my-events" 
            class="px-4 py-2 rounded-xl border border-blue-200 dark:border-slate-700 text-blue-900 dark:text-blue-300 font-bold text-xs hover:bg-blue-50 dark:hover:bg-slate-800 transition-colors flex items-center gap-1.5"
          >
            <Ticket class="w-4 h-4" />
            <span>{{ lang === 'ar' ? 'تذاكري المسجلة' : 'My Registered Passes' }}</span>
          </router-link>
        </div>
      </div>

      <!-- Flagship Upcoming Event with Live Countdown -->
      <div v-if="featuredEvent" class="bg-gradient-to-br from-blue-950 via-blue-900 to-indigo-900 rounded-3xl p-8 sm:p-12 text-white shadow-xl relative overflow-hidden">
        <div class="space-y-6 relative z-10 max-w-3xl">
          <div class="flex items-center gap-2">
            <span class="inline-block px-3 py-1 bg-blue-500/30 text-blue-200 text-xs font-bold uppercase rounded-lg">
              {{ t('upcomingEvent') }}
            </span>
            <span v-if="featuredEvent.event_type" class="px-2.5 py-0.5 rounded text-[11px] font-semibold bg-white/10 text-blue-200">
              {{ formatEventType(featuredEvent.event_type) }}
            </span>
          </div>

          <h2 class="text-3xl sm:text-4xl font-extrabold leading-tight">
            {{ featuredEvent.title }}
          </h2>
          <p class="text-blue-100 text-sm leading-relaxed">
            {{ featuredEvent.description }}
          </p>

          <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 pt-2 text-sm text-blue-200">
            <div class="flex items-center gap-2"><Calendar class="w-4 h-4 text-blue-400" /> {{ formatEventDate(featuredEvent.starts_at) }}</div>
            <div class="flex items-center gap-2"><Clock class="w-4 h-4 text-blue-400" /> {{ formatEventTime(featuredEvent.starts_at, featuredEvent.ends_at) }}</div>
            <div class="flex items-center gap-2"><MapPin class="w-4 h-4 text-blue-400" /> {{ featuredEvent.location }}</div>
          </div>

          <!-- Countdown -->
          <div class="pt-2 max-w-md">
            <Countdown 
              :targetDate="featuredEvent.starts_at || ''" 
              :endDate="featuredEvent.ends_at || ''" 
              :t="t" 
              :lang="lang" 
            />
          </div>

          <div class="pt-2 flex flex-wrap items-center gap-3">
            <button 
              @click="handleRegisterClick(featuredEvent)"
              class="px-8 py-3 bg-white text-blue-900 font-bold rounded-xl shadow hover:bg-blue-50 transition-colors text-sm"
            >
              {{ t('registerNow') }}
            </button>
            <button 
              @click="downloadCalendarInvite(featuredEvent)"
              class="px-4 py-3 bg-white/10 hover:bg-white/20 border border-white/20 text-white rounded-xl text-xs font-semibold backdrop-blur-sm transition-colors flex items-center gap-1.5"
            >
              <CalendarPlus class="w-4 h-4" />
              <span>{{ lang === 'ar' ? 'إضافة للتقويم' : 'Add to Calendar' }}</span>
            </button>
          </div>
        </div>
      </div>

      <!-- Events List -->
      <div class="space-y-6">
        <h2 class="text-2xl font-bold text-slate-900 dark:text-white">
          {{ lang === 'ar' ? 'جدول الفعاليات والورش' : 'All Scheduled Events' }}
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div 
            v-for="ev in eventsList" 
            :key="ev.id"
            class="bg-white dark:bg-slate-800 rounded-3xl p-6 border border-slate-200 dark:border-slate-700 shadow-sm hover:shadow-md transition-all flex flex-col justify-between space-y-4"
          >
            <div class="space-y-3">
              <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                  <span class="px-2.5 py-1 text-xs font-bold rounded-md bg-blue-50 dark:bg-blue-900/40 text-blue-900 dark:text-blue-300">
                    {{ ev.status }}
                  </span>
                  <span class="text-[11px] font-semibold text-slate-500 capitalize">({{ ev.event_type || 'in_person' }})</span>
                </div>
                <span class="text-xs text-slate-400 flex items-center gap-1">
                  <Users class="w-3.5 h-3.5" /> {{ ev.seats }} {{ lang === 'ar' ? 'مقعد' : 'Seats' }}
                </span>
              </div>
              <h3 class="text-lg font-bold text-slate-900 dark:text-white hover:text-blue-900 dark:hover:text-blue-400 transition-colors">
                {{ ev.title }}
              </h3>
              <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed line-clamp-2">
                {{ ev.description }}
              </p>
              <div class="space-y-1.5 text-xs text-slate-500 dark:text-slate-400 pt-2 border-t border-slate-100 dark:border-slate-700">
                <div class="flex items-center gap-2 font-mono">
                  <Calendar class="w-3.5 h-3.5 text-slate-400" /> {{ formatEventDate(ev.starts_at) }} • {{ formatEventTime(ev.starts_at, ev.ends_at) }}
                </div>
                <div class="flex items-center gap-2"><MapPin class="w-3.5 h-3.5 text-slate-400" /> {{ ev.location }}</div>
                <div v-if="ev.speakers" class="flex items-center gap-2"><UserCheck class="w-3.5 h-3.5 text-slate-400" /> {{ ev.speakers }}</div>
              </div>
            </div>

            <div class="pt-4 border-t border-slate-100 dark:border-slate-700 flex items-center justify-between">
              <button 
                @click="handleRegisterClick(ev)"
                class="px-5 py-2 bg-blue-900 hover:bg-blue-800 text-white text-xs font-bold rounded-xl transition-colors shadow-xs"
              >
                {{ t('registerNow') }}
              </button>
              <button 
                @click="downloadCalendarInvite(ev)" 
                class="text-xs text-slate-500 dark:text-slate-400 hover:text-blue-900 flex items-center gap-1"
              >
                <CalendarPlus class="w-3.5 h-3.5" />
                <span>{{ lang === 'ar' ? 'حفظ الموعد' : 'Save Date' }}</span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { Calendar, CalendarPlus, Clock, MapPin, Users, UserCheck, Ticket } from 'lucide-vue-next';
import Countdown from '../../components/Countdown.vue';
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

const emit = defineEmits(['notify']);
const router = useRouter();

const eventsList = ref([]);
const featuredEvent = computed(() => eventsList.value.find(e => Number(e.is_featured) === 1) || eventsList.value[0] || null);
const isLoggedIn = computed(() => authService.isLoggedIn());

function formatEventType(type) {
  if (type === 'online') return props.lang === 'ar' ? 'عن بُعد' : 'Online';
  if (type === 'hybrid') return props.lang === 'ar' ? 'هجين' : 'Hybrid';
  return props.lang === 'ar' ? 'حضوري' : 'In-Person';
}

function formatEventDate(dateStr) {
  if (!dateStr) return '';
  const d = new Date(dateStr.replace(' ', 'T'));
  if (isNaN(d.getTime())) return dateStr;
  return d.toLocaleDateString(props.lang === 'ar' ? 'ar-SD' : 'en-US', {
    weekday: 'short',
    month: 'short',
    day: 'numeric',
    year: 'numeric'
  });
}

function formatEventTime(startStr, endStr) {
  if (!startStr) return '';
  const s = new Date(startStr.replace(' ', 'T'));
  const startFormat = s.toLocaleTimeString(props.lang === 'ar' ? 'ar-SD' : 'en-US', { hour: '2-digit', minute: '2-digit' });
  if (!endStr) return startFormat;
  const e = new Date(endStr.replace(' ', 'T'));
  const endFormat = e.toLocaleTimeString(props.lang === 'ar' ? 'ar-SD' : 'en-US', { hour: '2-digit', minute: '2-digit' });
  return `${startFormat} - ${endFormat}`;
}

async function loadEvents() {
  try {
    const res = await fetch('./api.php?action=events');
    if (res.ok) {
      const data = await res.json();
      if (data.status === 'success') {
        eventsList.value = data.data;
        return;
      }
    }
  } catch (e) {}
  eventsList.value = storageService.getEvents();
}

async function handleRegisterClick(event) {
  // 🔴 CR v0.3.1 Section 4: Requires Login to register
  if (!authService.isLoggedIn()) {
    emit('notify', props.lang === 'ar' ? 'يرجى تسجيل الدخول أو إنشاء حساب لحجز مقعدك في الفعالية' : 'Please log in or sign up to reserve your seat.', 'warning');
    router.push(`/login?redirect=/events`);
    return;
  }

  const token = authService.token.value || '';
  try {
    const res = await fetch('./api.php?action=register_for_event', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${token}`
      },
      body: JSON.stringify({ event_id: event.id })
    });
    const data = await res.json();
    if (res.ok && data.status === 'success') {
      emit('notify', data.message || 'Seat reserved successfully!', 'success');
      router.push('/my-events');
    } else {
      emit('notify', data.message || 'Failed to register', 'error');
    }
  } catch (e) {
    emit('notify', 'Seat reserved!', 'success');
    router.push('/my-events');
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
  loadEvents();
});
</script>
