<template>
  <div class="py-12 bg-slate-50 dark:bg-slate-900 min-h-screen transition-colors duration-200 font-sans">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-16">
      
      <!-- Page Header & Term Selector -->
      <div class="text-center max-w-3xl mx-auto space-y-4">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-blue-100 dark:bg-blue-900/40 text-blue-900 dark:text-blue-300">
          <span>{{ currentTerm?.status === 'current' ? (lang === 'ar' ? 'الدورة الحالية' : 'Current Active Term') : (lang === 'ar' ? 'أرشيف الدورات السابقة' : 'Past Term Archive') }}</span>
        </div>

        <h1 class="text-3xl sm:text-5xl font-black text-slate-900 dark:text-white tracking-tight">
          {{ lang === 'ar' ? 'فريق عمل مكتب الفرع والمنسقين' : 'Our Team & Leadership' }}
        </h1>
        
        <!-- Term Title & Archive Dropdown -->
        <div class="flex flex-wrap items-center justify-center gap-3 pt-2">
          <span class="text-base sm:text-xl font-bold text-slate-700 dark:text-slate-300">
            {{ lang === 'ar' ? 'دورة المكتب:' : 'Office Term:' }}
            <span class="text-blue-900 dark:text-blue-400 font-extrabold ml-1 font-mono">{{ currentTerm?.title || '2025 – 2026' }}</span>
          </span>

          <!-- Previous Terms Dropdown -->
          <div class="relative inline-block text-left" v-if="archiveTerms.length > 0">
            <button 
              @click="archiveDropdownOpen = !archiveDropdownOpen"
              class="px-3.5 py-1.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 hover:border-blue-500 rounded-xl text-xs font-bold text-slate-700 dark:text-slate-200 shadow-xs flex items-center gap-1.5 transition-colors"
            >
              <span>{{ lang === 'ar' ? 'الدورات السابقة' : 'Archive Terms' }}</span>
              <ChevronDown class="w-3.5 h-3.5 text-slate-400" />
            </button>

            <!-- Dropdown Menu -->
            <div 
              v-if="archiveDropdownOpen" 
              @click="archiveDropdownOpen = false"
              class="absolute right-0 mt-2 w-52 bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-200 dark:border-slate-700 py-1.5 z-30 text-xs"
            >
              <div class="px-3 py-1.5 text-[10px] font-bold uppercase text-slate-400 border-b dark:border-slate-700">
                {{ lang === 'ar' ? 'اختر دورة سابقة لعرضها' : 'Select Term to View' }}
              </div>
              <button 
                v-for="t in archiveTerms" 
                :key="t.id"
                @click="switchTerm(t.id)"
                class="w-full text-left px-3.5 py-2 hover:bg-slate-50 dark:hover:bg-slate-700/60 flex items-center justify-between transition-colors"
                :class="currentTerm?.id === t.id ? 'bg-blue-50/70 dark:bg-slate-700 font-bold text-blue-900 dark:text-blue-300' : 'text-slate-700 dark:text-slate-200'"
              >
                <span>{{ t.title }}</span>
                <span v-if="t.status === 'current'" class="text-[9px] px-1.5 py-0.5 rounded bg-emerald-100 text-emerald-800 dark:bg-emerald-900/40 dark:text-emerald-300 font-bold uppercase">Active</span>
              </button>
            </div>
          </div>
        </div>

        <p class="text-slate-500 dark:text-slate-400 text-sm max-w-2xl mx-auto">
          {{ lang === 'ar' 
            ? 'يقود فرع IEEE جامعة الزعيم الأزهري نخبة من الطلاب الشغوفين بالتقنية والهندسة والعمل التطوعي لخدمة المجتمع وبناء قادة الغد.' 
            : 'Driven by passionate student leaders dedicated to engineering excellence, global IEEE standards, and impactful community empowerment.' 
          }}
        </p>
      </div>

      <!-- SECTION 1: Office Members (5 Key Positions) -->
      <div class="space-y-8">
        <div class="text-center space-y-1 border-b border-slate-200 dark:border-slate-800 pb-4">
          <h2 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white flex items-center justify-center gap-2">
            <ShieldCheck class="w-7 h-7 text-blue-900 dark:text-blue-400" />
            <span>{{ lang === 'ar' ? 'أعضاء المكتب التنفيذي' : 'Executive Office Members' }}</span>
          </h2>
          <p class="text-xs text-slate-500 dark:text-slate-400">
            {{ lang === 'ar' ? 'المناصب الخمسة القيادية لإدارة فرع IEEE بجامعة الزعيم الأزهري' : '5 Core Leadership Positions Driving the Student Branch' }}
          </p>
        </div>

        <!-- 1. Executive Leadership: Chairman & Vice Chair (Row 1 - 2 Columns) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 max-w-2xl mx-auto">
          <div 
            v-for="member in topTwoOffice" 
            :key="member.position_id"
            @click="openMemberModal(member)"
            class="bg-white dark:bg-slate-800 rounded-3xl p-6 border-2 border-blue-600/30 dark:border-blue-500/30 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300 text-center space-y-4 cursor-pointer group flex flex-col justify-between"
          >
            <!-- Photo or Fallback Avatar -->
            <div class="relative w-24 h-24 mx-auto rounded-full overflow-hidden border-2 border-blue-500/40 dark:border-blue-400/40 shadow-sm group-hover:scale-105 transition-transform duration-300 bg-slate-100 dark:bg-slate-700 flex items-center justify-center flex-shrink-0">
              <img 
                v-if="member.photo_path" 
                :src="resolvePhoto(member.photo_path)" 
                :alt="member.name_en"
                class="w-full h-full object-cover"
                loading="lazy"
              />
              <User v-else class="w-12 h-12 text-slate-400 dark:text-slate-500" />
            </div>

            <!-- Titles & Name -->
            <div class="space-y-1 flex-1">
              <span 
                class="inline-block px-2.5 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider"
                :class="member.position_key === 'chairman' 
                  ? 'bg-amber-100 dark:bg-amber-950/60 text-amber-900 dark:text-amber-300 border border-amber-300 dark:border-amber-700' 
                  : 'bg-blue-50 dark:bg-blue-900/30 text-blue-900 dark:text-blue-300 border border-blue-200 dark:border-blue-800'"
              >
                {{ member.position_key === 'chairman' ? '👑 ' : '⭐ ' }}{{ lang === 'ar' ? member.title_ar : member.title_en }}
              </span>
              <h3 class="font-extrabold text-slate-900 dark:text-white text-base">
                {{ lang === 'ar' ? (member.name_ar || member.name_en) : (member.name_en || member.name_ar) }}
              </h3>
              <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-2">
                {{ lang === 'ar' ? member.bio_ar : member.bio_en }}
              </p>
            </div>

            <!-- Social Links -->
            <div class="flex items-center justify-center gap-2 pt-1 text-slate-400">
              <a 
                v-if="member.linkedin_url" 
                :href="member.linkedin_url" 
                target="_blank" 
                @click.stop
                class="p-1.5 hover:text-blue-600 dark:hover:text-blue-400 transition-colors"
                title="LinkedIn"
              >
                <Linkedin class="w-4 h-4" />
              </a>
              <a 
                v-if="member.email_public" 
                :href="'mailto:' + member.email_public" 
                @click.stop
                class="p-1.5 hover:text-blue-600 dark:hover:text-blue-400 transition-colors"
                title="Email"
              >
                <Mail class="w-4 h-4" />
              </a>
            </div>
          </div>
        </div>

        <!-- 2. Remaining 3 Office Members: Secretary, Treasurer, HR (Row 2 - 3 Columns) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 max-w-5xl mx-auto">
          <div 
            v-for="member in remainingThreeOffice" 
            :key="member.position_id"
            @click="openMemberModal(member)"
            class="bg-white dark:bg-slate-800 rounded-3xl p-6 border border-slate-200/80 dark:border-slate-700 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300 text-center space-y-4 cursor-pointer group flex flex-col justify-between"
          >
            <!-- Photo or Fallback Avatar -->
            <div class="relative w-24 h-24 mx-auto rounded-full overflow-hidden border-2 border-slate-200 dark:border-slate-600 shadow-sm group-hover:scale-105 transition-transform duration-300 bg-slate-100 dark:bg-slate-700 flex items-center justify-center flex-shrink-0">
              <img 
                v-if="member.photo_path" 
                :src="resolvePhoto(member.photo_path)" 
                :alt="member.name_en"
                class="w-full h-full object-cover"
                loading="lazy"
              />
              <User v-else class="w-12 h-12 text-slate-400 dark:text-slate-500" />
            </div>

            <!-- Details -->
            <div class="space-y-1 flex-1">
              <span class="inline-block px-2.5 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider bg-blue-50 dark:bg-blue-900/30 text-blue-900 dark:text-blue-300">
                {{ lang === 'ar' ? member.title_ar : member.title_en }}
              </span>
              <h3 class="font-extrabold text-slate-900 dark:text-white text-base">
                {{ lang === 'ar' ? (member.name_ar || member.name_en) : (member.name_en || member.name_ar) }}
              </h3>
              <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-2">
                {{ lang === 'ar' ? member.bio_ar : member.bio_en }}
              </p>
            </div>

            <!-- Links -->
            <div class="flex items-center justify-center gap-2 pt-1 text-slate-400">
              <a 
                v-if="member.linkedin_url" 
                :href="member.linkedin_url" 
                target="_blank" 
                @click.stop
                class="p-1.5 hover:text-blue-600 dark:hover:text-blue-400 transition-colors"
                title="LinkedIn"
              >
                <Linkedin class="w-4 h-4" />
              </a>
              <a 
                v-if="member.email_public" 
                :href="'mailto:' + member.email_public" 
                @click.stop
                class="p-1.5 hover:text-blue-600 dark:hover:text-blue-400 transition-colors"
                title="Email"
              >
                <Mail class="w-4 h-4" />
              </a>
            </div>
          </div>
        </div>
      </div>

      <!-- SECTION 2: Team Coordinators (8 Committees) -->
      <div class="space-y-8 pt-6">
        <div class="text-center space-y-1 border-b border-slate-200 dark:border-slate-800 pb-4">
          <h2 class="text-2xl sm:text-3xl font-black text-slate-900 dark:text-white flex items-center justify-center gap-2">
            <Users class="w-7 h-7 text-blue-900 dark:text-blue-400" />
            <span>{{ lang === 'ar' ? 'منسقو الفرق الثمانية' : '8 Committee Coordinators' }}</span>
          </h2>
          <p class="text-xs text-slate-500 dark:text-slate-400">
            {{ lang === 'ar' ? 'قادة الفرق التخصصية المسؤولة عن أنشطة ومشاريع الفرع' : 'Specialized Coordinators Driving Student Committees' }}
          </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
          <div 
            v-for="coord in coordinators" 
            :key="coord.position_id"
            @click="openMemberModal(coord)"
            class="bg-white dark:bg-slate-800 rounded-3xl p-6 border border-slate-200/80 dark:border-slate-700 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300 text-center space-y-4 cursor-pointer group relative"
          >
            <!-- Same Person Badge for HR Coordinator according to Rule 1.3 -->
            <div 
              v-if="coord.position_key === 'hr'" 
              class="absolute top-3 right-3 px-2 py-0.5 rounded-full text-[9px] font-bold bg-purple-100 dark:bg-purple-900/40 text-purple-900 dark:text-purple-300 border border-purple-200 dark:border-purple-800"
              :title="lang === 'ar' ? 'نفس الشخص: مسؤول HR في المكتب ومنسق الفريق' : 'Same person holds Office HR and HR Coordinator'"
            >
              ★ {{ lang === 'ar' ? 'نفس الشخص' : 'Dual Role' }}
            </div>

            <!-- Photo or Fallback Avatar -->
            <div class="relative w-20 h-20 mx-auto rounded-full overflow-hidden border-2 border-slate-200 dark:border-slate-600 shadow-sm group-hover:scale-105 transition-transform duration-300 bg-slate-100 dark:bg-slate-700 flex items-center justify-center">
              <img 
                v-if="coord.photo_path" 
                :src="resolvePhoto(coord.photo_path)" 
                :alt="coord.name_en"
                class="w-full h-full object-cover"
                loading="lazy"
              />
              <User v-else class="w-10 h-10 text-slate-400 dark:text-slate-500" />
            </div>

            <!-- Details -->
            <div class="space-y-1">
              <span class="inline-block px-2.5 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300">
                {{ lang === 'ar' ? coord.title_ar : coord.title_en }}
              </span>
              <h3 class="font-extrabold text-slate-900 dark:text-white text-base">
                {{ lang === 'ar' ? (coord.name_ar || coord.name_en) : (coord.name_en || coord.name_ar) }}
              </h3>
              <p class="text-xs text-slate-500 dark:text-slate-400 line-clamp-2">
                {{ lang === 'ar' ? coord.bio_ar : coord.bio_en }}
              </p>
            </div>

            <!-- Links -->
            <div class="flex items-center justify-center gap-2 pt-1 text-slate-400">
              <a 
                v-if="coord.linkedin_url" 
                :href="coord.linkedin_url" 
                target="_blank" 
                @click.stop
                class="p-1.5 hover:text-blue-600 transition-colors"
              >
                <Linkedin class="w-4 h-4" />
              </a>
              <a 
                v-if="coord.email_public" 
                :href="'mailto:' + coord.email_public" 
                @click.stop
                class="p-1.5 hover:text-blue-600 transition-colors"
              >
                <Mail class="w-4 h-4" />
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Member Bio & Profile Modal -->
    <div v-if="selectedMember" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
      <div class="bg-white dark:bg-slate-800 rounded-3xl max-w-md w-full p-6 sm:p-8 space-y-5 shadow-2xl border border-slate-100 dark:border-slate-700 text-center relative">
        <button 
          @click="selectedMember = null" 
          class="absolute top-5 right-5 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-1"
        >
          <X class="w-5 h-5" />
        </button>

        <div class="w-24 h-24 mx-auto rounded-full overflow-hidden border-2 border-blue-600 shadow-md bg-slate-100 dark:bg-slate-700 flex items-center justify-center">
          <img 
            v-if="selectedMember.photo_path" 
            :src="resolvePhoto(selectedMember.photo_path)" 
            :alt="selectedMember.name_en"
            class="w-full h-full object-cover"
          />
          <User v-else class="w-12 h-12 text-slate-400 dark:text-slate-500" />
        </div>

        <div class="space-y-1">
          <span class="inline-block px-3 py-0.5 rounded-full text-xs font-bold bg-blue-50 dark:bg-blue-900/40 text-blue-900 dark:text-blue-300">
            {{ lang === 'ar' ? selectedMember.title_ar : selectedMember.title_en }}
          </span>
          <h3 class="text-xl font-black text-slate-900 dark:text-white">
            {{ lang === 'ar' ? (selectedMember.name_ar || selectedMember.name_en) : (selectedMember.name_en || selectedMember.name_ar) }}
          </h3>
        </div>

        <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed whitespace-pre-line text-left" :dir="lang === 'ar' ? 'rtl' : 'ltr'">
          {{ lang === 'ar' ? (selectedMember.bio_ar || selectedMember.bio_en || 'لا توجد نبذة مختصرة مسجلة.') : (selectedMember.bio_en || selectedMember.bio_ar || 'No bio recorded.') }}
        </p>

        <div class="pt-3 border-t dark:border-slate-700 flex items-center justify-center gap-4 text-xs">
          <a 
            v-if="selectedMember.linkedin_url" 
            :href="selectedMember.linkedin_url" 
            target="_blank" 
            class="px-4 py-2 bg-blue-50 dark:bg-slate-700 text-blue-900 dark:text-blue-300 rounded-xl font-bold flex items-center gap-1.5 hover:bg-blue-100 transition-colors"
          >
            <Linkedin class="w-4 h-4" />
            <span>LinkedIn</span>
          </a>
          <a 
            v-if="selectedMember.email_public" 
            :href="'mailto:' + selectedMember.email_public" 
            class="px-4 py-2 bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-200 rounded-xl font-bold flex items-center gap-1.5 hover:bg-slate-200 transition-colors"
          >
            <Mail class="w-4 h-4" />
            <span>{{ lang === 'ar' ? 'تواصل عبر البريد' : 'Contact Email' }}</span>
          </a>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import { ShieldCheck, Users, Linkedin, Mail, ChevronDown, X, User } from 'lucide-vue-next';

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

const route = useRoute();

const currentTerm = ref(null);
const officeMembers = ref([]);
const coordinators = ref([]);
const archiveTerms = ref([]);
const archiveDropdownOpen = ref(false);
const selectedMember = ref(null);

async function loadTeamData(termId = null) {
  try {
    const url = termId ? `./api.php?action=current_term&id=${termId}` : './api.php?action=current_term';
    const res = await fetch(url);
    const data = await res.json();
    if (res.ok && data.status === 'success' && data.data) {
      currentTerm.value = data.data.term;
      officeMembers.value = data.data.office_members;
      coordinators.value = data.data.coordinators;
      archiveTerms.value = data.data.archive_terms;
    }
  } catch (e) {
    console.error('Failed to load team data:', e);
  }
}

function switchTerm(termId) {
  loadTeamData(termId);
}

const topTwoOffice = computed(() => {
  const chair = officeMembers.value.find(m => m.position_key === 'chairman') || officeMembers.value[0];
  const vice = officeMembers.value.find(m => m.position_key === 'vice_chair') || officeMembers.value[1];
  const list = [];
  if (chair) list.push(chair);
  if (vice && vice !== chair) list.push(vice);
  return list;
});

const remainingThreeOffice = computed(() => {
  const topKeys = ['chairman', 'vice_chair'];
  return officeMembers.value.filter(m => !topKeys.includes(m.position_key));
});

function openMemberModal(member) {
  selectedMember.value = member;
}

function resolvePhoto(path) {
  if (!path) return '';
  if (path.startsWith('http://') || path.startsWith('https://') || path.startsWith('data:')) {
    return path;
  }
  const clean = path.replace(/^\/+/, '');
  return './' + clean;
}

onMounted(() => {
  const paramId = route.params.termId;
  loadTeamData(paramId);
});
</script>
