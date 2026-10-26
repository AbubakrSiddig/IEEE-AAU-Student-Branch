<template>
  <div class="py-12 bg-slate-50 dark:bg-slate-900 min-h-screen transition-colors duration-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
      
      <!-- Top Animated Volunteer Slideshow / Hero Carousel (Requested by User) -->
      <div 
        class="relative overflow-hidden rounded-3xl shadow-2xl bg-slate-950 aspect-[16/9] sm:aspect-[21/9] border border-slate-200/80 dark:border-slate-800 group"
        @mouseenter="stopAutoSlide"
        @mouseleave="startAutoSlide"
      >
        <!-- Slides -->
        <div 
          v-for="(slide, index) in volunteerSlides" 
          :key="index"
          class="absolute inset-0 transition-opacity duration-700 ease-in-out"
          :class="currentSlide === index ? 'opacity-100 z-10' : 'opacity-0 z-0 pointer-events-none'"
        >
          <img 
            :src="slide.image" 
            :alt="lang === 'ar' ? slide.title_ar : slide.title_en" 
            class="w-full h-full object-cover object-center transform scale-100 group-hover:scale-105 transition-transform duration-700"
          />
          <!-- Gradient Overlay with Captions -->
          <div class="absolute inset-0 bg-gradient-to-t from-slate-950/95 via-slate-950/45 to-transparent flex flex-col justify-end p-6 sm:p-10 text-white space-y-2">
            <div class="flex items-center gap-2">
              <span class="px-2.5 py-1 rounded-md text-[10px] font-black uppercase tracking-wider bg-blue-600 text-white shadow-xs">
                {{ lang === 'ar' ? slide.badge_ar : slide.badge_en }}
              </span>
              <span class="text-xs text-blue-200 font-semibold flex items-center gap-1.5">
                <Users class="w-3.5 h-3.5" />
                <span>{{ lang === 'ar' ? 'متطوعو IEEE AAU' : 'IEEE AAU Volunteers' }}</span>
              </span>
            </div>
            <h3 class="text-xl sm:text-2xl md:text-3xl font-black text-white leading-tight max-w-3xl drop-shadow-sm">
              {{ lang === 'ar' ? slide.title_ar : slide.title_en }}
            </h3>
            <p class="text-xs sm:text-sm text-slate-200 max-w-2xl font-normal leading-relaxed drop-shadow-xs line-clamp-2 sm:line-clamp-none">
              {{ lang === 'ar' ? slide.caption_ar : slide.caption_en }}
            </p>
          </div>
        </div>

        <!-- Controls: Prev / Next Buttons -->
        <button 
          @click="prevSlide" 
          class="absolute top-1/2 -translate-y-1/2 start-4 z-20 w-10 h-10 rounded-full bg-black/40 hover:bg-black/70 backdrop-blur-md text-white flex items-center justify-center transition-all opacity-0 group-hover:opacity-100 sm:opacity-90 hover:scale-105"
          title="Previous Photo"
        >
          <ChevronRight v-if="lang === 'ar'" class="w-5 h-5" />
          <ChevronLeft v-else class="w-5 h-5" />
        </button>
        <button 
          @click="nextSlide" 
          class="absolute top-1/2 -translate-y-1/2 end-4 z-20 w-10 h-10 rounded-full bg-black/40 hover:bg-black/70 backdrop-blur-md text-white flex items-center justify-center transition-all opacity-0 group-hover:opacity-100 sm:opacity-90 hover:scale-105"
          title="Next Photo"
        >
          <ChevronLeft v-if="lang === 'ar'" class="w-5 h-5" />
          <ChevronRight v-else class="w-5 h-5" />
        </button>

        <!-- Slide Indicators & Counter -->
        <div class="absolute bottom-4 end-6 z-20 flex items-center gap-2">
          <span class="text-[11px] font-mono text-white/80 bg-black/50 px-2 py-0.5 rounded-md backdrop-blur-xs">
            {{ currentSlide + 1 }} / {{ volunteerSlides.length }}
          </span>
          <div class="flex items-center gap-1.5">
            <button 
              v-for="(_, idx) in volunteerSlides" 
              :key="idx"
              @click="currentSlide = idx"
              class="h-2 rounded-full transition-all duration-300"
              :class="currentSlide === idx ? 'w-6 bg-blue-500' : 'w-2 bg-white/40 hover:bg-white/70'"
              :title="`Slide ${idx + 1}`"
            ></button>
          </div>
        </div>
      </div>

      <!-- About Header (Screen 2) -->
      <div class="text-center max-w-3xl mx-auto space-y-4">
        <h1 class="text-3xl sm:text-4xl font-extrabold text-slate-900 dark:text-white">
          {{ t('aboutHeader') }}
        </h1>
        <p class="text-slate-600 dark:text-slate-300 text-base sm:text-lg leading-relaxed">
          {{ t('aboutSubtitle') }}
        </p>
      </div>

      <!-- Navigation Tabs (Vision, Mission, Objectives, Achievements, Foundation) -->
      <div class="flex flex-wrap items-center justify-center gap-2 border-b border-slate-200 dark:border-slate-800 pb-4">
        <button 
          v-for="tab in tabs" 
          :key="tab.id"
          @click="activeTab = tab.id"
          class="px-5 py-2.5 rounded-xl font-medium text-sm transition-all duration-150"
          :class="activeTab === tab.id 
            ? 'bg-blue-900 dark:bg-blue-600 text-white shadow-md' 
            : 'text-slate-600 dark:text-slate-300 hover:text-blue-900 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800'"
        >
          {{ t(tab.key) }}
        </button>
      </div>

      <!-- Tab Content Area -->
      <div class="bg-white dark:bg-slate-800 rounded-3xl p-8 sm:p-12 shadow-sm border border-slate-100 dark:border-slate-700 transition-colors">
        <!-- Vision Tab -->
        <div v-if="activeTab === 'vision'" class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
          <div class="space-y-4">
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white flex items-center gap-2">
              <Eye class="w-7 h-7 text-blue-900 dark:text-blue-400" />
              <span>{{ t('ourVisionTitle') }}</span>
            </h2>
            <p class="text-slate-600 dark:text-slate-300 leading-relaxed text-base">
              {{ t('ourVisionText') }}
            </p>
            <p class="text-slate-500 dark:text-slate-400 leading-relaxed text-sm">
              {{ lang === 'ar'
                ? 'نعمل كجسر بين التحصيل الأكاديمي النظري والتطبيق العملي، مع ربط أعضائنا بأكبر شبكة تكنولوجية وهندسية في العالم.'
                : 'We serve as a vital bridge between rigorous academic study and industry-ready practical experience, connecting our student members to the world’s premier engineering professional network.'
              }}
            </p>
          </div>
          <div class="rounded-2xl overflow-hidden shadow-lg h-72 border border-slate-200/50 dark:border-slate-700">
            <img 
              src="https://images.unsplash.com/photo-1541339907198-e08756dedf3f?auto=format&fit=crop&w=800&q=80" 
              alt="Alzaeim Alazhari University Campus"
              class="w-full h-full object-cover"
            />
          </div>
        </div>

        <!-- Mission Tab -->
        <div v-else-if="activeTab === 'mission'" class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
          <div class="space-y-4">
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white flex items-center gap-2">
              <Compass class="w-7 h-7 text-blue-900 dark:text-blue-400" />
              <span>{{ t('ourMissionTitle') }}</span>
            </h2>
            <p class="text-slate-600 dark:text-slate-300 leading-relaxed text-base">
              {{ t('ourMissionText') }}
            </p>
            <p class="text-slate-500 dark:text-slate-400 leading-relaxed text-sm">
              {{ lang === 'ar'
                ? 'تنظيم ورش عمل تقنية متقدمة، مسابقات هاكاثون، وزيارات ميدانية صناعية تسهم في تأهيل خريجين على كفاءة عالمية.'
                : 'Delivering technical bootcamps, competitive hackathons, and industrial field trips that empower graduates with worldwide competitive competence.'
              }}
            </p>
          </div>
          <div class="rounded-2xl overflow-hidden shadow-lg h-72 border border-slate-200/50 dark:border-slate-700">
            <img 
              src="https://images.unsplash.com/photo-1523240795612-9a054b0db644?auto=format&fit=crop&w=800&q=80" 
              alt="Students collaborating"
              class="w-full h-full object-cover"
            />
          </div>
        </div>

        <!-- Objectives Tab -->
        <div v-else-if="activeTab === 'objectives'" class="space-y-6">
          <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white flex items-center gap-2">
            <Target class="w-7 h-7 text-blue-900 dark:text-blue-400" />
            <span>{{ lang === 'ar' ? 'أهداف الفرع الإستراتيجية' : 'Branch Strategic Objectives' }}</span>
          </h2>
          <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-4">
            <div class="p-6 bg-slate-50 dark:bg-slate-750 dark:bg-slate-700/50 rounded-2xl border border-slate-100 dark:border-slate-700">
              <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-900/50 text-blue-900 dark:text-blue-300 flex items-center justify-center font-bold mb-3">1</div>
              <h3 class="font-bold text-slate-900 dark:text-white mb-2">{{ lang === 'ar' ? 'التأهيل التقني المتخصص' : 'Technical Proficiency' }}</h3>
              <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                {{ lang === 'ar' ? 'تقديم ورش عمل في إنترنت الأشياء والذكاء الاصطناعي وهندسة البرمجيات.' : 'Delivering specialized workshops in IoT, AI, robotics, and modern software architectures.' }}
              </p>
            </div>
            <div class="p-6 bg-slate-50 dark:bg-slate-750 dark:bg-slate-700/50 rounded-2xl border border-slate-100 dark:border-slate-700">
              <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-900/50 text-blue-900 dark:text-blue-300 flex items-center justify-center font-bold mb-3">2</div>
              <h3 class="font-bold text-slate-900 dark:text-white mb-2">{{ lang === 'ar' ? 'المهارات القيادية والعمل الجماعي' : 'Leadership & Soft Skills' }}</h3>
              <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                {{ lang === 'ar' ? 'صقل مهارات إدارة المشاريع، الخطابة، والتواصل الفعال.' : 'Refining project management, public speaking, negotiation, and cross-functional leadership.' }}
              </p>
            </div>
            <div class="p-6 bg-slate-50 dark:bg-slate-750 dark:bg-slate-700/50 rounded-2xl border border-slate-100 dark:border-slate-700">
              <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-900/50 text-blue-900 dark:text-blue-300 flex items-center justify-center font-bold mb-3">3</div>
              <h3 class="font-bold text-slate-900 dark:text-white mb-2">{{ lang === 'ar' ? 'المسؤولية المجتمعية' : 'Community Solutions' }}</h3>
              <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                {{ lang === 'ar' ? 'توجيه طاقات الطلاب لحل المشاكل الحقيقية في المجتمع السوداني.' : 'Deploying innovative engineering projects to solve real-world community challenges.' }}
              </p>
            </div>
          </div>
        </div>

        <!-- Achievements Tab -->
        <div v-else-if="activeTab === 'achievements'" class="space-y-6">
          <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white flex items-center gap-2">
            <Trophy class="w-7 h-7 text-amber-500" />
            <span>{{ lang === 'ar' ? 'إنجازات الفرع والجوائز' : 'Key Milestones & Achievements' }}</span>
          </h2>
          <div class="space-y-4 pt-2">
            <div class="flex items-start gap-4 p-4 rounded-xl bg-blue-50/60 dark:bg-blue-950/30 border border-blue-100 dark:border-blue-900/40">
              <Award class="w-6 h-6 text-blue-900 dark:text-blue-400 flex-shrink-0 mt-1" />
              <div>
                <h4 class="font-bold text-slate-900 dark:text-white">{{ lang === 'ar' ? 'المشاركة في مؤتمر IEEE Region 8 Student Congress' : 'IEEE Region 8 Congress Representation' }}</h4>
                <p class="text-xs text-slate-600 dark:text-slate-300 mt-1">{{ lang === 'ar' ? 'تمثيل رسمي لجامعة الزعيم الأزهري ضمن الفروع الطلابية الإقليمية.' : 'Represented AAU Student Branch across international student leaders.' }}</p>
              </div>
            </div>
            <div class="flex items-start gap-4 p-4 rounded-xl bg-blue-50/60 dark:bg-blue-950/30 border border-blue-100 dark:border-blue-900/40">
              <BookOpen class="w-6 h-6 text-blue-900 dark:text-blue-400 flex-shrink-0 mt-1" />
              <div>
                <h4 class="font-bold text-slate-900 dark:text-white">{{ lang === 'ar' ? 'إصدار 12 عدداً متتالياً من مجلة الفرع الرسمية' : '12 Published Magazine Issues' }}</h4>
                <p class="text-xs text-slate-600 dark:text-slate-300 mt-1">{{ lang === 'ar' ? 'نشر أبحاث ومقالات طلابية بلغات ومواضيع تقنية متعددة.' : 'Showcasing student research papers, tutorials, and alumni stories.' }}</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Foundation Tab -->
        <div v-else-if="activeTab === 'foundation'" class="space-y-6">
          <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white flex items-center gap-2">
            <BookmarkCheck class="w-7 h-7 text-blue-900 dark:text-blue-400" />
            <span>{{ lang === 'ar' ? 'تاريخ التأسيس والنشأة' : 'History & Foundation' }}</span>
          </h2>
          <p class="text-slate-600 dark:text-slate-300 text-sm leading-relaxed">
            {{ lang === 'ar'
              ? 'تأسس فرع IEEE AAU Student Branch بجامعة الزعيم الأزهري برؤية واضحة تهدف لربط طلاب كليات الهندسة وعلوم الحاسوب بالمعايير العالمية لمنظمة IEEE الدولية. ومنذ انطلاقته، نما الفرع ليضم أكثر من 120 عضواً نشطاً و8 لجان طلابية متخصصة.'
              : 'IEEE AAU Student Branch was chartered at Alzaeim Alazhari University to connect engineering and computing undergraduates with the global IEEE network. Over the past 5+ years, it has evolved into a powerhouse of over 120 active members across 8 specialized committees.'
            }}
          </p>
        </div>
      </div>

      <!-- Core Values Section (Matching 4 Cards on Screen 2) -->
      <div class="space-y-6">
        <div class="text-center">
          <h2 class="text-2xl sm:text-3xl font-bold text-slate-900 dark:text-white">
            {{ t('coreValues') }}
          </h2>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
          <!-- Innovation -->
          <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200/80 dark:border-slate-700 shadow-sm text-center space-y-3 hover:shadow-md transition-shadow">
            <div class="w-14 h-14 mx-auto rounded-2xl bg-amber-50 dark:bg-amber-950/40 text-amber-600 dark:text-amber-400 flex items-center justify-center">
              <Lightbulb class="w-7 h-7" />
            </div>
            <h3 class="font-bold text-slate-900 dark:text-white text-lg">{{ t('valInnovation') }}</h3>
            <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">{{ t('valInnovationDesc') }}</p>
          </div>

          <!-- Collaboration -->
          <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200/80 dark:border-slate-700 shadow-sm text-center space-y-3 hover:shadow-md transition-shadow">
            <div class="w-14 h-14 mx-auto rounded-2xl bg-blue-50 dark:bg-blue-950/40 text-blue-600 dark:text-blue-400 flex items-center justify-center">
              <Users class="w-7 h-7" />
            </div>
            <h3 class="font-bold text-slate-900 dark:text-white text-lg">{{ t('valCollaboration') }}</h3>
            <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">{{ t('valCollaborationDesc') }}</p>
          </div>

          <!-- Leadership -->
          <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200/80 dark:border-slate-700 shadow-sm text-center space-y-3 hover:shadow-md transition-shadow">
            <div class="w-14 h-14 mx-auto rounded-2xl bg-indigo-50 dark:bg-indigo-950/40 text-indigo-600 dark:text-indigo-400 flex items-center justify-center">
              <Award class="w-7 h-7" />
            </div>
            <h3 class="font-bold text-slate-900 dark:text-white text-lg">{{ t('valLeadership') }}</h3>
            <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">{{ t('valLeadershipDesc') }}</p>
          </div>

          <!-- Impact -->
          <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 border border-slate-200/80 dark:border-slate-700 shadow-sm text-center space-y-3 hover:shadow-md transition-shadow">
            <div class="w-14 h-14 mx-auto rounded-2xl bg-emerald-50 dark:bg-emerald-950/40 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
              <Target class="w-7 h-7" />
            </div>
            <h3 class="font-bold text-slate-900 dark:text-white text-lg">{{ t('valImpact') }}</h3>
            <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">{{ t('valImpactDesc') }}</p>
          </div>
        </div>
      </div>

      <!-- Referral Banner to Our Team (Spec v0.3.4 Section 1) -->
      <div class="bg-gradient-to-r from-blue-900 to-indigo-950 text-white rounded-3xl p-8 sm:p-10 shadow-xl flex flex-col sm:flex-row items-center justify-between gap-6 border border-blue-800">
        <div class="space-y-2 text-center sm:text-left">
          <span class="px-3 py-1 rounded-full text-xs font-bold uppercase bg-white/20 text-blue-200">
            {{ lang === 'ar' ? 'القيادة والمنسقون' : 'Leadership & Teams' }}
          </span>
          <h2 class="text-2xl sm:text-3xl font-black">
            {{ lang === 'ar' ? 'تعرّف على أعضاء مكتب الفرع ومنسقي الفرق' : 'Meet Our Office Leaders & Coordinators' }}
          </h2>
          <p class="text-xs sm:text-sm text-blue-200 max-w-xl">
            {{ lang === 'ar' ? 'استكشف الدورة الحالية لمكتب الفرع والفرق الثمانية، واطلع على أرشيف الدورات السابقة.' : 'Explore our current executive term members, 8 committee coordinators, and term archives.' }}
          </p>
        </div>
        <router-link 
          to="/team" 
          class="px-6 py-3.5 bg-white text-blue-900 hover:bg-blue-50 font-extrabold rounded-2xl text-xs sm:text-sm shadow-md transition-all whitespace-nowrap flex items-center gap-2 flex-shrink-0"
        >
          <span>{{ lang === 'ar' ? 'عرض صفحة فريقنا (Our Team)' : 'View Our Team' }} &rarr;</span>
        </router-link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { 
  Eye, 
  Compass, 
  Target, 
  Trophy, 
  BookmarkCheck, 
  Award, 
  BookOpen, 
  Lightbulb, 
  Users, 
  ChevronLeft, 
  ChevronRight 
} from 'lucide-vue-next';

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

const activeTab = ref('vision');

const tabs = [
  { id: 'vision', key: 'tabVision' },
  { id: 'mission', key: 'tabMission' },
  { id: 'objectives', key: 'tabObjectives' },
  { id: 'achievements', key: 'tabAchievements' },
  { id: 'foundation', key: 'tabFoundation' },
];

// Volunteer Slideshow State
const currentSlide = ref(0);
let slideTimer = null;

const volunteerSlides = [
  {
    image: './assets/images/volunteers/volunteer-1.jpg',
    badge_ar: 'متطوعو الفرع',
    badge_en: 'Branch Volunteers',
    title_ar: 'أبطال العمل التطوعي وروح الفريق في فرع IEEE AAU',
    title_en: 'IEEE AAU Student Branch Volunteer Leaders & Team Spirit',
    caption_ar: 'شباب شغوف بالتقنية والابتكار يكرسون جهودهم لبناء مجتمع هندسي متميز في جامعة الزعيم الأزهري.',
    caption_en: 'Passionate student leaders dedicated to advancing engineering excellence at Alzaeim Alazhari University.'
  },
  {
    image: './assets/images/volunteers/volunteer-2.jpg',
    badge_ar: 'ملتقيات وورش عمل',
    badge_en: 'Workshops & Gatherings',
    title_ar: 'ملتقيات تفاعلية تجمع صناع المستقبل وأجيال الفرع',
    title_en: 'Engaging Workshops & Collaborative Gatherings',
    caption_ar: 'بيئة محفزة تتيح للطلاب صقل مهاراتهم وتبادل المعرفة التقنية والعمل بروح الفريق الواحد.',
    caption_en: 'A collaborative environment fostering knowledge exchange, teamwork, and technical leadership.'
  },
  {
    image: './assets/images/volunteers/volunteer-3.jpg',
    badge_ar: 'تمثيل الفرع',
    badge_en: 'Campus Representation',
    title_ar: 'حضور قيادي وتمثيل مشرف لفرع جامعة الزعيم الأزهري',
    title_en: 'Proud Multidisciplinary Student Representation',
    caption_ar: 'كوادر طلابية تجمع مختلف التخصصات الهندسية وعلوم الحاسوب لقيادة الأنشطة والفعاليات الجامعية.',
    caption_en: 'Engineering and IT students leading impactful initiatives across campus.'
  },
  {
    image: './assets/images/volunteers/volunteer-4.jpg',
    badge_ar: 'المؤتمرات العلمية',
    badge_en: 'Symposia & Conferences',
    title_ar: 'تنظيم وإدارة المؤتمرات والندوات العلمية بالجامعة',
    title_en: 'Organizing & Hosting University Conferences',
    caption_ar: 'سواعد طلابية تنظم كبرى المؤتمرات التقنية والمحاضرات العلمية بكل كفاءة واحترافية.',
    caption_en: 'Student hands organizing high-impact technical symposia, academic talks, and community outreach.'
  },
  {
    image: './assets/images/volunteers/volunteer-5.jpg',
    badge_ar: 'يوم IEEE العالمي',
    badge_en: 'IEEE Day Celebrations',
    title_ar: 'الاحتفال بيوم IEEE العالمي وأنشطة IEEE Sudan',
    title_en: 'Celebrating IEEE Day with Energy & Impact',
    caption_ar: 'مشاركة فعالة في الفعاليات العالمية والوطنية لربط طلابنا بالشبكة الدولية لمهندسي الكهرباء والإلكترونيات.',
    caption_en: 'Active involvement connecting students with IEEE Sudan Section and global IEEE Region 8.'
  }
];

function nextSlide() {
  currentSlide.value = (currentSlide.value + 1) % volunteerSlides.length;
}

function prevSlide() {
  currentSlide.value = (currentSlide.value - 1 + volunteerSlides.length) % volunteerSlides.length;
}

function startAutoSlide() {
  stopAutoSlide();
  slideTimer = setInterval(() => {
    nextSlide();
  }, 4500);
}

function stopAutoSlide() {
  if (slideTimer) {
    clearInterval(slideTimer);
    slideTimer = null;
  }
}

onMounted(() => {
  startAutoSlide();
});

onUnmounted(() => {
  stopAutoSlide();
});
</script>
