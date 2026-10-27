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

    <!-- Magazine & Publications Section (Latest 3 Issues) -->
    <section class="bg-slate-100/70 dark:bg-slate-800/40 py-16 transition-colors">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        <!-- Section Header -->
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
          <div>
            <span class="text-xs font-bold uppercase tracking-wider text-blue-900 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30 px-3 py-1 rounded-md">
              {{ lang === 'ar' ? 'إصدارات الفرع' : 'Branch Publications' }}
            </span>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white mt-2 flex items-center gap-2">
              <BookOpen class="w-7 h-7 text-blue-900 dark:text-blue-400" />
              <span>{{ lang === 'ar' ? 'أحدث أعداد مجلة IEEE AAU' : 'Latest IEEE AAU Magazine Issues' }}</span>
            </h2>
            <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400 mt-1">
              {{ lang === 'ar' ? 'تصفّح أحدث الإصدارات الدورية والأبحاث والابتكارات الطلابية الصادرة عن الفرع' : 'Explore the latest periodic publications, student research, and innovation articles.' }}
            </p>
          </div>

          <router-link 
            to="/magazine" 
            class="inline-flex items-center gap-1.5 text-xs sm:text-sm font-bold text-blue-900 dark:text-blue-400 hover:underline"
          >
            <span>{{ lang === 'ar' ? 'عرض جميع الأعداد' : 'View All Issues' }}</span>
            <ArrowRight class="w-4 h-4" />
          </router-link>
        </div>

        <!-- 3 Magazine Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8">
          <div 
            v-for="issue in displayedMagazines" 
            :key="issue.id"
            class="bg-white dark:bg-slate-800 rounded-3xl p-5 border border-slate-200/90 dark:border-slate-700/80 shadow-sm hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 flex flex-col justify-between group"
          >
            <!-- 1. Pure Clean Cover Image (No distorting text overlays) -->
            <div 
              @click="viewMagazine(issue)"
              class="relative aspect-[3/4] w-full rounded-2xl overflow-hidden bg-slate-900 shadow-md border border-slate-200/60 dark:border-slate-700/60 mb-4 cursor-pointer"
            >
              <img 
                v-if="issue.cover"
                :src="resolveCover(issue.cover)" 
                :alt="issue.title" 
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                loading="lazy"
              />
              <div v-else class="w-full h-full flex flex-col items-center justify-center p-4 bg-gradient-to-tr from-blue-950 to-indigo-900 text-white text-center">
                <BookOpen class="w-12 h-12 text-blue-300/80 mb-2" />
                <span class="text-xs font-bold text-blue-200">{{ issue.volume }}</span>
              </div>
            </div>

            <!-- 2. Clean Typography & Issue Meta Below Cover -->
            <div class="space-y-1.5 flex-1">
              <div class="flex items-center justify-between text-xs">
                <span class="font-bold text-blue-900 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30 px-2 py-0.5 rounded-md text-[11px]">
                  {{ issue.volume }}
                </span>
                <span class="text-slate-400 dark:text-slate-500 font-medium text-[11px]">
                  {{ issue.published_date || issue.year }}
                </span>
              </div>
              <h3 
                class="font-extrabold text-slate-900 dark:text-white text-base line-clamp-1 group-hover:text-blue-900 dark:group-hover:text-blue-400 transition-colors pt-1"
                :title="issue.title"
              >
                {{ issue.title }}
              </h3>
              <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-2 leading-relaxed">
                {{ issue.description || (lang === 'ar' ? 'إصدار علمي وثقافي مميز يسلّط الضوء على الابتكارات والمشاريع الطلابية.' : 'Special scientific publication highlighting student innovations and technical projects.') }}
              </p>
            </div>

            <!-- 3. Dual Action Buttons: View & Download -->
            <div class="pt-4 mt-2 border-t border-slate-100 dark:border-slate-700/60 flex items-center gap-2">
              <!-- View Button -->
              <button 
                @click="viewMagazine(issue)"
                class="flex-1 py-2 px-3 bg-blue-50 dark:bg-blue-900/30 hover:bg-blue-100 dark:hover:bg-blue-900/50 text-blue-900 dark:text-blue-300 text-xs font-bold rounded-xl transition-colors flex items-center justify-center gap-1.5"
                :title="lang === 'ar' ? 'عرض المجلة' : 'View Magazine'"
              >
                <Eye class="w-3.5 h-3.5" />
                <span>{{ lang === 'ar' ? 'عرض' : 'View' }}</span>
              </button>

              <!-- Download Button -->
              <button 
                @click="downloadMagazine(issue)"
                class="flex-1 py-2 px-3 bg-blue-900 hover:bg-blue-800 text-white text-xs font-bold rounded-xl shadow-xs transition-colors flex items-center justify-center gap-1.5"
                :title="lang === 'ar' ? 'تنزيل ملف PDF' : 'Download PDF'"
              >
                <Download class="w-3.5 h-3.5" />
                <span>{{ lang === 'ar' ? 'تنزيل' : 'Download' }}</span>
              </button>
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
import { ArrowRight, Calendar, CalendarPlus, Clock, MapPin, Download, User, Eye, BookOpen } from 'lucide-vue-next';
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
const latestMagazines = ref([]);

function resolveCover(path) {
  if (!path) return '';
  if (path.startsWith('http://') || path.startsWith('https://')) return path;
  if (path.startsWith('/')) return '.' + path;
  return './' + path;
}

const allNews = storageService.getNews();
const latestNews = computed(() => allNews.slice(0, 3));

async function fetchLatestMagazine() {
  try {
    const res = await fetch('./api.php?action=magazines');
    if (res.ok) {
      const data = await res.json();
      if (data.status === 'success' && Array.isArray(data.data) && data.data.length > 0) {
        // Filter published and slice latest 3 issues
        const published = data.data.filter(m => m.status === 'published');
        latestMagazines.value = (published.length > 0 ? published : data.data).slice(0, 3);
      }
    }
  } catch (e) {
    console.warn('Fallback to local magazine:', e);
  }
}

const displayedMagazines = computed(() => {
  if (latestMagazines.value && latestMagazines.value.length > 0) {
    return latestMagazines.value;
  }
  const local = storageService.getMagazines();
  const list = [local.latest, ...(local.past || [])].filter(Boolean);
  return list.slice(0, 3);
});

function viewMagazine(issue) {
  const fileUrl = issue.file_url || issue.fileUrl;
  if (fileUrl && fileUrl !== '#') {
    window.open(resolveCover(fileUrl), '_blank');
  } else if (issue.cover) {
    window.open(resolveCover(issue.cover), '_blank');
  } else {
    emit('notify', props.lang === 'ar' ? 'ملف المجلة غير متوفر حالياً' : 'Magazine file currently unavailable', 'info');
  }
}

function downloadMagazine(issue) {
  const fileUrl = issue.file_url || issue.fileUrl;
  if (fileUrl && fileUrl !== '#') {
    const link = document.createElement('a');
    link.href = resolveCover(fileUrl);
    link.download = `${(issue.title || 'IEEE_AAU_Magazine').replace(/\s+/g, '_')}.pdf`;
    link.target = '_blank';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    emit('notify', props.lang === 'ar' ? 'بدأ تنزيل المجلة...' : 'Magazine download started...', 'success');
  } else {
    downloadTermsPDF('v2.0');
    emit('notify', props.lang === 'ar' ? 'بدأ تنزيل نسخة تجريبية من المجلة...' : 'Sample magazine download started...', 'success');
  }
}

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
