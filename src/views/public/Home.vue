<template>
  <div class="dark:bg-slate-900 transition-colors duration-200">
    <!-- Hero Section -->
    <section class="relative bg-gradient-to-br from-[#001f3f] via-[#002855] to-[#0A5C99] text-white py-24 sm:py-32 overflow-hidden">
      <!-- Background Decorative Elements -->
      <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:16px_16px] pointer-events-none"></div>
      <div class="absolute -top-40 -right-40 w-96 h-96 bg-blue-500/20 rounded-full blur-3xl pointer-events-none"></div>
      <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-cyan-500/20 rounded-full blur-3xl pointer-events-none"></div>

      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="max-w-3xl space-y-6">
          <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-blue-400/10 border border-blue-400/20 text-blue-200 text-xs font-semibold uppercase tracking-wider backdrop-blur-sm">
            <span class="w-2 h-2 rounded-full bg-blue-400 animate-ping"></span>
            <span>IEEE AAU Student Branch • Alzaeim Alazhari University</span>
          </div>

          <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight leading-tight">
            {{ t('branchTitle') }}
            <span class="block text-blue-300 font-medium text-2xl sm:text-3xl mt-2 font-serif italic">
              {{ t('heroSubtitle') }}
            </span>
          </h1>

          <p class="text-lg sm:text-xl text-blue-100 max-w-2xl font-light leading-relaxed">
            {{ t('heroDescription') }}
          </p>

          <div class="flex flex-wrap items-center gap-4 pt-4">
            <router-link 
              to="/volunteer" 
              class="px-8 py-3.5 bg-white text-blue-900 font-bold rounded-xl shadow-lg hover:bg-blue-50 hover:shadow-xl transition-all duration-200 transform hover:-translate-y-0.5 active:translate-y-0 flex items-center gap-2"
            >
              <span>{{ lang === 'ar' ? 'تطوع معنا' : 'Volunteer With Us' }}</span>
              <ArrowRight class="w-4 h-4" />
            </router-link>

            <router-link 
              to="/events" 
              class="px-8 py-3.5 bg-blue-800/40 border border-white/20 text-white font-semibold rounded-xl hover:bg-blue-800/70 transition-all duration-200 backdrop-blur-sm"
            >
              {{ t('exploreEvents') }}
            </router-link>

            <router-link 
              to="/join" 
              class="px-6 py-3.5 text-blue-200 hover:text-white font-medium transition-colors"
            >
              {{ t('joinUs') }} &rarr;
            </router-link>
          </div>
        </div>
      </div>
    </section>

    <!-- Animated Statistics Bar (Matching Screen 1) -->
    <section class="relative -mt-10 z-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-100 dark:border-slate-700 p-6 sm:p-8 grid grid-cols-2 lg:grid-cols-4 gap-6 text-center divide-y lg:divide-y-0 lg:divide-x divide-slate-100 dark:divide-slate-700 transition-colors">
        <div class="p-4">
          <div class="text-3xl sm:text-4xl font-extrabold text-blue-900 dark:text-blue-400">
            120<span class="text-blue-500">+</span>
          </div>
          <div class="text-sm font-medium text-slate-500 dark:text-slate-400 mt-1">
            {{ t('activeMembers') }}
          </div>
        </div>

        <div class="p-4">
          <div class="text-3xl sm:text-4xl font-extrabold text-blue-900 dark:text-blue-400">
            15<span class="text-blue-500">+</span>
          </div>
          <div class="text-sm font-medium text-slate-500 dark:text-slate-400 mt-1">
            {{ t('eventsPerYear') }}
          </div>
        </div>

        <div class="p-4">
          <div class="text-3xl sm:text-4xl font-extrabold text-blue-900 dark:text-blue-400">
            8
          </div>
          <div class="text-sm font-medium text-slate-500 dark:text-slate-400 mt-1">
            {{ t('studentTeams') }}
          </div>
        </div>

        <div class="p-4">
          <div class="text-3xl sm:text-4xl font-extrabold text-blue-900 dark:text-blue-400">
            5<span class="text-blue-500">+</span>
          </div>
          <div class="text-sm font-medium text-slate-500 dark:text-slate-400 mt-1">
            {{ t('yearsOfImpact') }}
          </div>
        </div>
      </div>
    </section>

    <!-- 🔴 Upcoming Event Spotlight with Live Countdown from DB (Critical Fix 6 & 7) -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
      <div class="bg-gradient-to-r from-blue-900 via-blue-800 to-indigo-900 rounded-3xl text-white p-8 sm:p-12 shadow-xl relative overflow-hidden">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center relative z-10">
          <div class="space-y-4">
            <div class="flex items-center gap-2">
              <span 
                class="inline-block px-3 py-1 text-xs font-bold uppercase rounded-lg"
                :class="eventState === 'live' ? 'bg-rose-500 text-white animate-pulse' : 'bg-blue-500/30 text-blue-200'"
              >
                {{ eventState === 'live' ? (lang === 'ar' ? '🔴 جارية الآن Live' : '🔴 LIVE NOW') : t('upcomingEvent') }}
              </span>
              <span v-if="upcomingEvent?.event_type" class="px-2.5 py-0.5 rounded text-[11px] font-semibold bg-white/10 text-blue-200">
                {{ formatEventType(upcomingEvent.event_type) }}
              </span>
            </div>

            <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight">
              {{ upcomingEvent ? upcomingEvent.title : (lang === 'ar' ? 'ترقبوا فعاليتنا القادمة' : 'Stay Tuned for Our Next Event') }}
            </h2>

            <p class="text-blue-100 text-sm leading-relaxed">
              {{ upcomingEvent ? upcomingEvent.description : (lang === 'ar' ? 'نعمل على تنظيم ورش عمل ومؤتمرات تقنية ملهمة تسهم في تطوير مهاراتكم الهندسية. ترقبوا الإعلان قريباً!' : 'We are preparing cutting-edge engineering bootcamps and workshops. Dates will be announced shortly.') }}
            </p>

            <div v-if="upcomingEvent" class="flex flex-wrap gap-4 text-xs sm:text-sm text-blue-200 pt-2">
              <span class="flex items-center gap-1.5">
                <Calendar class="w-4 h-4 text-blue-400" /> {{ formatEventDate(upcomingEvent.starts_at) }}
              </span>
              <span class="flex items-center gap-1.5">
                <Clock class="w-4 h-4 text-blue-400" /> {{ formatEventTime(upcomingEvent.starts_at, upcomingEvent.ends_at) }}
              </span>
              <span class="flex items-center gap-1.5">
                <MapPin class="w-4 h-4 text-blue-400" /> {{ upcomingEvent.location }}
              </span>
            </div>

            <div class="pt-4 flex flex-wrap items-center gap-3">
              <router-link 
                v-if="upcomingEvent"
                to="/events" 
                class="inline-flex items-center gap-2 px-6 py-2.5 bg-white text-blue-900 font-bold rounded-xl hover:bg-blue-50 transition-colors shadow-md text-sm"
              >
                <span>{{ t('registerNow') }}</span>
                <ArrowRight class="w-4 h-4" />
              </router-link>

              <button 
                v-if="upcomingEvent"
                @click="downloadCalendarInvite(upcomingEvent)"
                class="inline-flex items-center gap-2 px-4 py-2.5 bg-white/10 hover:bg-white/20 border border-white/20 text-white rounded-xl text-xs font-semibold backdrop-blur-sm transition-colors"
                title="Add to Calendar"
              >
                <CalendarPlus class="w-4 h-4" />
                <span>{{ lang === 'ar' ? 'إضافة للتقويم' : 'Add to Calendar' }}</span>
              </button>
            </div>
          </div>

          <!-- Live Countdown Component linked to DB starts_at -->
          <div>
            <div class="bg-white/10 backdrop-blur-md rounded-2xl p-6 border border-white/20">
              <h3 class="text-sm font-semibold text-blue-200 uppercase tracking-wider text-center mb-4">
                {{ lang === 'ar' ? 'العد التنازلي للفعالية القادمة' : 'Next Event Countdown' }}
              </h3>
              <Countdown 
                :targetDate="upcomingEvent?.starts_at || ''" 
                :endDate="upcomingEvent?.ends_at || ''" 
                :serverTime="serverTime" 
                :t="t" 
                :lang="lang" 
                @state-change="onCountdownStateChange"
              />
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Latest News Section (Matching Screen 1) -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <div class="flex items-center justify-between mb-8">
        <div>
          <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white">
            {{ t('latestNews') }}
          </h2>
          <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
            {{ lang === 'ar' ? 'أحدث أخبار وأنشطة الفرع' : 'Discover recent branch milestones and announcements.' }}
          </p>
        </div>
        <router-link to="/news" class="text-sm font-bold text-blue-900 dark:text-blue-400 hover:underline flex items-center gap-1">
          <span>{{ t('viewAll') }}</span>
          <ArrowRight class="w-4 h-4" />
        </router-link>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <article 
          v-for="item in latestNews" 
          :key="item.id"
          class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-100 dark:border-slate-700 shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden group flex flex-col"
        >
          <div class="h-48 overflow-hidden relative">
            <img 
              :src="item.image" 
              :alt="item.title"
              class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
            />
            <span class="absolute top-3 left-3 bg-blue-900/80 backdrop-blur text-white text-xs px-2.5 py-1 rounded-md font-medium">
              {{ item.category }}
            </span>
          </div>
          <div class="p-5 flex-1 flex flex-col justify-between">
            <div>
              <div class="text-xs font-semibold text-slate-400 dark:text-slate-500 mb-2 flex items-center gap-1.5">
                <Calendar class="w-3.5 h-3.5" />
                <span>{{ item.date }}</span>
              </div>
              <h3 class="font-bold text-slate-900 dark:text-white text-base group-hover:text-blue-900 dark:group-hover:text-blue-400 transition-colors line-clamp-2">
                {{ item.title }}
              </h3>
              <p class="text-xs text-slate-500 dark:text-slate-400 mt-2 line-clamp-2 leading-relaxed">
                {{ item.snippet }}
              </p>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 dark:border-slate-700 flex items-center justify-between text-xs">
              <span class="text-slate-400 dark:text-slate-500 font-medium">{{ item.author }}</span>
              <router-link to="/news" class="text-blue-900 dark:text-blue-400 font-bold hover:underline">
                {{ lang === 'ar' ? 'اقرأ المزيد' : 'Read More' }} &rarr;
              </router-link>
            </div>
          </div>
        </article>
      </div>
    </section>

    <!-- Magazine & Publication Teaser (Matching Screen 5) -->
    <section class="bg-slate-100/70 dark:bg-slate-800/40 py-16 transition-colors">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-slate-800 rounded-3xl p-8 sm:p-12 border border-slate-200 dark:border-slate-700 shadow-sm flex flex-col lg:flex-row items-center gap-8">
          <div class="w-48 sm:w-56 h-72 bg-gradient-to-tr from-blue-900 to-indigo-800 rounded-xl shadow-xl flex-shrink-0 flex items-center justify-center p-6 text-center text-white relative overflow-hidden group">
            <img 
              v-if="currentLatestMagazine.cover"
              :src="currentLatestMagazine.cover" 
              alt="Magazine Cover" 
              class="absolute inset-0 w-full h-full object-cover opacity-70 group-hover:scale-105 transition-transform"
            />
            <div class="relative z-10 bg-black/40 p-3 rounded-xl backdrop-blur-xs">
              <div class="text-xs uppercase font-bold tracking-widest text-blue-300">IEEE AAU</div>
              <div class="text-lg font-black mt-1">MAGAZINE</div>
              <div class="text-[11px] mt-2 text-blue-100 font-semibold">{{ currentLatestMagazine.volume }}</div>
            </div>
          </div>

          <div class="flex-1 space-y-4">
            <span class="text-xs font-bold uppercase tracking-wider text-blue-900 dark:text-blue-300 bg-blue-50 dark:bg-blue-900/30 px-3 py-1 rounded-md">
              {{ t('latestIssue') }}
            </span>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white">
              {{ currentLatestMagazine.title }}
            </h2>
            <p class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed max-w-2xl">
              {{ currentLatestMagazine.description }}
            </p>
            <div class="flex flex-wrap items-center gap-4 pt-2">
              <button 
                @click="downloadLatestPDF"
                class="px-6 py-2.5 bg-blue-900 hover:bg-blue-800 text-white text-sm font-semibold rounded-xl transition-colors shadow-sm flex items-center gap-2"
              >
                <Download class="w-4 h-4" />
                <span>{{ t('downloadPDF') }}</span>
              </button>
              <router-link 
                to="/magazine" 
                class="px-6 py-2.5 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-200 text-sm font-medium rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors"
              >
                {{ t('pastIssues') }}
              </router-link>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Meet Our Team Teaser (Spec v0.3.4 Section 1.7) -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 space-y-8">
      <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
          <span class="text-xs font-bold uppercase tracking-wider text-blue-900 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30 px-3 py-1 rounded-md">
            {{ lang === 'ar' ? 'القيادة التنفيذية' : 'Branch Leadership' }}
          </span>
          <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white mt-2">
            {{ lang === 'ar' ? 'تعرّف على فريقنا' : 'Meet Our Team' }}
          </h2>
          <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
            {{ lang === 'ar' ? 'أعضاء مكتب الفرع التنفيذي ومنسقو الفرق الثمانية' : 'Executive office members and committee coordinators driving IEEE AAU SB.' }}
          </p>
        </div>

        <router-link 
          to="/team" 
          class="inline-flex items-center gap-1.5 text-xs sm:text-sm font-bold text-blue-900 dark:text-blue-400 hover:underline"
        >
          <span>{{ lang === 'ar' ? 'عرض كل الفريق والأرشيف' : 'View Full Team & Archives' }}</span>
          <ArrowRight class="w-4 h-4" />
        </router-link>
      </div>

      <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
        <div 
          v-for="m in officeMembersSummary" 
          :key="m.position_key"
          class="bg-white dark:bg-slate-800 rounded-2xl p-5 border border-slate-200/80 dark:border-slate-700 text-center space-y-3 hover:shadow-md transition-shadow"
        >
          <div class="w-20 h-20 mx-auto rounded-full overflow-hidden border-2 border-slate-200 dark:border-slate-700 bg-slate-100 dark:bg-slate-700 flex items-center justify-center">
            <img v-if="m.photo_path" :src="m.photo_path" :alt="m.name_en" class="w-full h-full object-cover" />
            <User v-else class="w-10 h-10 text-slate-400 dark:text-slate-500" />
          </div>
          <div>
            <h4 class="font-bold text-slate-900 dark:text-white text-xs truncate">{{ lang === 'ar' ? (m.name_ar || m.name_en) : (m.name_en || m.name_ar) }}</h4>
            <span class="text-[10px] text-blue-600 dark:text-blue-400 block font-semibold mt-0.5 truncate">{{ lang === 'ar' ? m.title_ar : m.title_en }}</span>
          </div>
        </div>
      </div>
    </section>

    <!-- Call to Action Banner -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
      <div class="bg-gradient-to-r from-blue-950 via-blue-900 to-indigo-900 rounded-3xl p-10 sm:p-14 text-center text-white shadow-xl">
        <h2 class="text-3xl sm:text-4xl font-extrabold">
          {{ t('callToActionTitle') }}
        </h2>
        <p class="text-blue-100 max-w-2xl mx-auto mt-4 text-sm sm:text-base leading-relaxed">
          {{ t('callToActionSubtitle') }}
        </p>
        <div class="mt-8 flex flex-wrap items-center justify-center gap-4">
          <router-link 
            to="/volunteer" 
            class="px-8 py-3.5 bg-white text-blue-900 font-bold rounded-xl shadow-lg hover:bg-blue-50 transition-all text-sm"
          >
            {{ lang === 'ar' ? 'قدّم طلب التطوع الآن' : 'Apply to Volunteer' }}
          </router-link>
          <router-link 
            to="/join" 
            class="px-8 py-3.5 bg-blue-800/60 border border-white/20 text-white font-medium rounded-xl hover:bg-blue-800 transition-all text-sm"
          >
            {{ t('joinUs') }}
          </router-link>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { ArrowRight, Calendar, CalendarPlus, Clock, MapPin, Download, User } from 'lucide-vue-next';
import Countdown from '../../components/Countdown.vue';
import { storageService } from '../../services/storage';
import { downloadTermsPDF } from '../../services/pdfExport';

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

const upcomingEvent = ref(null);
const eventState = ref('upcoming');
const serverTime = ref('');
const officeMembersSummary = ref([]);
const dynamicLatestMagazine = ref(null);

const allNews = storageService.getNews();
const latestNews = computed(() => allNews.slice(0, 3));

async function fetchLatestMagazine() {
  try {
    const res = await fetch('./api.php?action=magazines');
    if (res.ok) {
      const data = await res.json();
      if (data.status === 'success' && Array.isArray(data.data) && data.data.length > 0) {
        // Find latest published issue (ordered by id DESC in backend)
        const latest = data.data.find(m => m.status === 'published') || data.data[0];
        if (latest) {
          dynamicLatestMagazine.value = latest;
        }
      }
    }
  } catch (e) {
    console.warn('Fallback to local magazine:', e);
  }
}

const currentLatestMagazine = computed(() => {
  return dynamicLatestMagazine.value || storageService.getMagazines().latest;
});

async function fetchTeamSummary() {
  try {
    const res = await fetch('./api.php?action=current_term');
    if (res.ok) {
      const data = await res.json();
      if (data.status === 'success' && data.data) {
        officeMembersSummary.value = data.data.office_members || [];
      }
    }
  } catch (e) {}
}

async function fetchNextUpcomingEvent() {
  try {
    const res = await fetch('./api.php?action=next_event');
    if (res.ok) {
      const data = await res.json();
      if (data.status === 'success') {
        upcomingEvent.value = data.event;
        eventState.value = data.event_state;
        serverTime.value = data.server_time;
        return;
      }
    }
  } catch (e) {
    console.warn('API fetch fallback to local storage:', e);
  }

  // Fallback to local storage if API unreachable
  const events = storageService.getEvents();
  upcomingEvent.value = events[0] || null;
}

function onCountdownStateChange(newState) {
  eventState.value = newState;
}

function formatEventType(type) {
  if (type === 'online') return props.lang === 'ar' ? 'عن بُعد (Online)' : 'Online';
  if (type === 'hybrid') return props.lang === 'ar' ? 'هجين (Hybrid)' : 'Hybrid';
  return props.lang === 'ar' ? 'حضوري' : 'In-Person';
}

function formatEventDate(dateStr) {
  if (!dateStr) return '';
  const d = new Date(dateStr.replace(' ', 'T'));
  if (isNaN(d.getTime())) return dateStr;
  return d.toLocaleDateString(props.lang === 'ar' ? 'ar-SD' : 'en-US', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric'
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

function downloadCalendarInvite(event) {
  if (!event) return;
  const cleanStart = (event.starts_at || '').replace(/[- :]/g, '').slice(0, 15) + 'Z';
  const cleanEnd = (event.ends_at || event.starts_at).replace(/[- :]/g, '').slice(0, 15) + 'Z';
  
  const icsContent = `BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//IEEE AAU Student Branch//NONSGML Event//EN
BEGIN:VEVENT
UID:${event.id || 'EV-01'}@ieee-aau.org
DTSTAMP:${cleanStart}
DTSTART:${cleanStart}
DTEND:${cleanEnd}
SUMMARY:${event.title}
DESCRIPTION:${(event.description || '').replace(/\n/g, '\\n')}
LOCATION:${event.location}
STATUS:CONFIRMED
END:VEVENT
END:VCALENDAR`;

  const blob = new Blob([icsContent], { type: 'text/calendar;charset=utf-8' });
  const url = window.URL.createObjectURL(blob);
  const a = document.createElement('a');
  a.href = url;
  a.download = `${event.title.replace(/\s+/g, '_')}.ics`;
  a.click();
  window.URL.revokeObjectURL(url);
  emit('notify', props.lang === 'ar' ? 'تم تنزيل ملف إضافة الفعالية للتقويم!' : 'Calendar invite downloaded!', 'success');
}

function downloadLatestPDF() {
  const fileUrl = currentLatestMagazine.value?.file_url;
  if (fileUrl && fileUrl !== '#') {
    window.open(fileUrl, '_blank');
    emit('notify', props.lang === 'ar' ? 'جاري فتح وتحميل ملف المجلة...' : 'Opening magazine PDF...', 'success');
  } else {
    downloadTermsPDF('v2.0');
    emit('notify', 'IEEE AAU Magazine PDF download started!', 'success');
  }
}

onMounted(() => {
  fetchNextUpcomingEvent();
  fetchTeamSummary();
  fetchLatestMagazine();
});
</script>
