<template>
  <div v-if="state === 'upcoming'" class="space-y-4">
    <div class="grid grid-cols-4 gap-2 sm:gap-4 text-center">
      <div class="bg-white/90 dark:bg-slate-800/90 backdrop-blur border border-blue-100 dark:border-slate-700 rounded-xl p-3 shadow-sm transition-colors">
        <span class="block text-2xl sm:text-3xl font-extrabold text-blue-900 dark:text-blue-400 font-mono">{{ days }}</span>
        <span class="text-xs uppercase font-semibold text-slate-500 dark:text-slate-400 tracking-wider">{{ t('countdownDays') }}</span>
      </div>
      <div class="bg-white/90 dark:bg-slate-800/90 backdrop-blur border border-blue-100 dark:border-slate-700 rounded-xl p-3 shadow-sm transition-colors">
        <span class="block text-2xl sm:text-3xl font-extrabold text-blue-900 dark:text-blue-400 font-mono">{{ hours }}</span>
        <span class="text-xs uppercase font-semibold text-slate-500 dark:text-slate-400 tracking-wider">{{ t('countdownHours') }}</span>
      </div>
      <div class="bg-white/90 dark:bg-slate-800/90 backdrop-blur border border-blue-100 dark:border-slate-700 rounded-xl p-3 shadow-sm transition-colors">
        <span class="block text-2xl sm:text-3xl font-extrabold text-blue-900 dark:text-blue-400 font-mono">{{ minutes }}</span>
        <span class="text-xs uppercase font-semibold text-slate-500 dark:text-slate-400 tracking-wider">{{ t('countdownMins') }}</span>
      </div>
      <div class="bg-white/90 dark:bg-slate-800/90 backdrop-blur border border-blue-100 dark:border-slate-700 rounded-xl p-3 shadow-sm transition-colors">
        <span class="block text-2xl sm:text-3xl font-extrabold text-blue-600 dark:text-blue-300 font-mono animate-pulse">{{ seconds }}</span>
        <span class="text-xs uppercase font-semibold text-slate-500 dark:text-slate-400 tracking-wider">{{ t('countdownSecs') }}</span>
      </div>
    </div>
  </div>

  <!-- Live Now State -->
  <div v-else-if="state === 'live'" class="bg-rose-500/20 border border-rose-500/40 rounded-2xl p-6 text-center text-white backdrop-blur-md">
    <div class="inline-flex items-center gap-2 px-3 py-1 bg-rose-600 text-white font-black text-xs uppercase tracking-wider rounded-full animate-bounce mb-2">
      <span class="w-2 h-2 rounded-full bg-white animate-ping"></span>
      <span>{{ lang === 'ar' ? '🔴 جارية الآن Live' : '🔴 LIVE NOW' }}</span>
    </div>
    <p class="text-sm text-rose-100 font-medium">
      {{ lang === 'ar' ? 'الفعالية منعقدة حالياً! انضم للحضور الآن.' : 'This event is currently in progress. Join now!' }}
    </p>
  </div>

  <!-- No upcoming event state -->
  <div v-else class="bg-white/10 dark:bg-slate-800/60 border border-white/20 dark:border-slate-700 rounded-2xl p-6 text-center text-white backdrop-blur-sm">
    <Calendar class="w-8 h-8 mx-auto text-blue-300 mb-2 opacity-80" />
    <h4 class="text-base font-bold text-white mb-1">
      {{ lang === 'ar' ? 'ترقبوا فعاليتنا القادمة' : 'Stay Tuned for Upcoming Events' }}
    </h4>
    <p class="text-xs text-blue-200">
      {{ lang === 'ar' ? 'سيتم الإعلان عن الفعاليات والورش القادمة قريباً جداً.' : 'New workshops and symposiums will be scheduled soon.' }}
    </p>
  </div>
</template>

<script setup>
import { ref, watch, onMounted, onUnmounted } from 'vue';
import { Calendar } from 'lucide-vue-next';

const props = defineProps({
  targetDate: {
    type: String,
    default: ''
  },
  endDate: {
    type: String,
    default: ''
  },
  serverTime: {
    type: String,
    default: ''
  },
  t: {
    type: Function,
    required: true
  },
  lang: {
    type: String,
    default: 'ar'
  }
});

const emit = defineEmits(['state-change']);

const state = ref('upcoming'); // 'upcoming' | 'live' | 'none'
const days = ref('00');
const hours = ref('00');
const minutes = ref('00');
const seconds = ref('00');

let timer = null;

function updateCountdown() {
  if (!props.targetDate) {
    state.value = 'none';
    emit('state-change', 'none');
    return;
  }

  // Parse target date and end date
  const target = new Date(props.targetDate.replace(' ', 'T')).getTime();
  const end = props.endDate ? new Date(props.endDate.replace(' ', 'T')).getTime() : target + (4 * 3600 * 1000);
  const now = new Date().getTime();

  // If currently between starts_at and ends_at => LIVE
  if (now >= target && now <= end) {
    state.value = 'live';
    emit('state-change', 'live');
    return;
  }

  // If past ends_at => expired / none
  if (now > end) {
    state.value = 'none';
    emit('state-change', 'none');
    return;
  }

  // Still upcoming
  state.value = 'upcoming';
  emit('state-change', 'upcoming');
  const diff = target - now;

  if (diff <= 0) {
    days.value = '00';
    hours.value = '00';
    minutes.value = '00';
    seconds.value = '00';
    return;
  }

  const d = Math.floor(diff / (1000 * 60 * 60 * 24));
  const h = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  const m = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
  const s = Math.floor((diff % (1000 * 60)) / 1000);

  days.value = String(d).padStart(2, '0');
  hours.value = String(h).padStart(2, '0');
  minutes.value = String(m).padStart(2, '0');
  seconds.value = String(s).padStart(2, '0');
}

watch(() => props.targetDate, () => {
  updateCountdown();
});

onMounted(() => {
  updateCountdown();
  timer = setInterval(updateCountdown, 1000);
});

onUnmounted(() => {
  if (timer) clearInterval(timer);
});
</script>
