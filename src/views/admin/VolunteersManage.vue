<template>
  <div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div>
        <h1 class="text-2xl font-black text-slate-900 dark:text-white">{{ t('volunteers') }}</h1>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
          {{ lang === 'ar' ? 'فرز ومراجعة طلبات التطوع وفق مواصفة النموذج v0.3.3، استعراض كافة الإجابات وتصدير ملف Excel كامل' : 'Review volunteer candidates across 8 committees, inspect all 7 general & team answers, and export comprehensive Excel sheets.' }}
        </p>
      </div>

      <button 
        @click="handleExport"
        class="px-4 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-xl shadow-xs transition-all flex items-center gap-2 self-start sm:self-auto"
      >
        <FileSpreadsheet class="w-4 h-4" />
        <span>{{ lang === 'ar' ? 'تصدير كافة البيانات إلى Excel' : 'Export Full Data to Excel' }}</span>
      </button>
    </div>

    <!-- Filters Bar -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 border border-slate-200/80 dark:border-slate-700 shadow-xs flex flex-wrap items-center gap-3">
      <!-- Team Filter -->
      <select 
        v-model="teamFilter" 
        :disabled="isCoordinator"
        class="px-3 py-2 bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-xs font-semibold dark:text-white"
      >
        <option value="All">All Teams (كل الفرق)</option>
        <option value="media">Media (الإعلام)</option>
        <option value="technical">Technical (التقني)</option>
        <option value="magazine">Magazine (المجلة)</option>
        <option value="program">Program (البرامج)</option>
        <option value="pr">PR (العلاقات العامة)</option>
        <option value="hr">HR (الموارد البشرية)</option>
        <option value="presenters">Presenters (التقديم)</option>
        <option value="speaker">Speaker (المتحدثون)</option>
      </select>

      <!-- Status Filter -->
      <select 
        v-model="statusFilter" 
        class="px-3 py-2 bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-xs font-semibold dark:text-white"
      >
        <option value="All">All Statuses (كل الحالات)</option>
        <option value="new">New (جديد)</option>
        <option value="screening">Screening (قيد الفرز)</option>
        <option value="candidate_interview">Candidate for Interview (مرشح للمعاينة)</option>
        <option value="accepted">Accepted (مقبول)</option>
        <option value="rejected">Rejected (مرفوض)</option>
      </select>

      <!-- Search -->
      <div class="relative flex-1 min-w-[200px]">
        <input 
          v-model="searchQuery" 
          type="text" 
          placeholder="Search name, email, phone, major..." 
          class="w-full pl-8 pr-3 py-2 bg-slate-50 dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-xs dark:text-white"
        />
        <Search class="w-3.5 h-3.5 text-slate-400 absolute left-2.5 top-2.5" />
      </div>
    </div>

    <!-- Candidates Table -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/80 dark:border-slate-700 shadow-xs overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-left text-xs text-slate-600 dark:text-slate-300">
          <thead class="bg-slate-50 dark:bg-slate-700/50 text-slate-400 font-semibold uppercase tracking-wider border-b border-slate-100 dark:border-slate-700">
            <tr>
              <th class="py-3.5 px-6">ID</th>
              <th class="py-3.5 px-6">Candidate Name</th>
              <th class="py-3.5 px-6">Team &amp; Specialty</th>
              <th class="py-3.5 px-6">College &amp; Batch</th>
              <th class="py-3.5 px-6">Phone / WhatsApp</th>
              <th class="py-3.5 px-6">Status</th>
              <th class="py-3.5 px-6 text-right">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100 dark:divide-slate-700 font-medium">
            <tr 
              v-for="vol in filteredVolunteers" 
              :key="vol.id"
              class="hover:bg-slate-50 dark:hover:bg-slate-700/40 transition-colors cursor-pointer"
            >
              <td class="py-3.5 px-6 font-mono font-bold text-slate-900 dark:text-white">{{ vol.id }}</td>
              <td class="py-3.5 px-6 whitespace-nowrap">
                <div class="font-bold text-slate-900 dark:text-white">{{ vol.full_name || vol.name }}</div>
                <div class="text-[11px] text-slate-400">{{ vol.email }}</div>
              </td>
              <td class="py-3.5 px-6 whitespace-nowrap">
                <span class="px-2 py-0.5 rounded text-[11px] font-bold uppercase bg-blue-50 dark:bg-blue-900/30 text-blue-900 dark:text-blue-300">
                  {{ vol.team_id || vol.team }}
                </span>
                <span v-if="vol.specialty_id" class="text-[10px] text-slate-500 block mt-0.5 capitalize">
                  ({{ vol.specialty_id.replace('_', ' ') }})
                </span>
              </td>
              <td class="py-3.5 px-6 whitespace-nowrap">
                <div>{{ vol.college || 'Engineering' }}</div>
                <span class="text-[11px] text-slate-400">{{ vol.major || '' }} • Batch {{ vol.batch || '26' }}</span>
              </td>
              <td class="py-3.5 px-6 whitespace-nowrap font-mono text-[11px]" dir="ltr">
                <div>{{ vol.phone }}</div>
                <div v-if="vol.whatsapp" class="text-emerald-600 text-[10px] font-sans">WA: {{ vol.whatsapp }}</div>
              </td>
              <td class="py-3.5 px-6 whitespace-nowrap">
                <span 
                  class="px-2.5 py-1 rounded-md text-[11px] font-bold border"
                  :class="{
                    'bg-blue-50 text-blue-800 border-blue-200 dark:bg-blue-950/40 dark:text-blue-300': vol.status === 'new',
                    'bg-amber-50 text-amber-800 border-amber-200 dark:bg-amber-950/40 dark:text-amber-300': vol.status === 'screening' || vol.status === 'Pending',
                    'bg-purple-50 text-purple-800 border-purple-200 dark:bg-purple-950/40 dark:text-purple-300': vol.status === 'candidate_interview',
                    'bg-emerald-50 text-emerald-800 border-emerald-200 dark:bg-emerald-950/40 dark:text-emerald-300': vol.status === 'accepted' || vol.status === 'Accepted',
                    'bg-rose-50 text-rose-800 border-rose-200 dark:bg-rose-950/40 dark:text-rose-300': vol.status === 'rejected' || vol.status === 'Rejected'
                  }"
                >
                  {{ vol.status }}
                </span>
              </td>
              <td class="py-3.5 px-6 text-right whitespace-nowrap space-x-1">
                <button 
                  @click="openDetails(vol)"
                  class="p-1.5 text-blue-900 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-slate-700 rounded-lg"
                  title="View Full Application Details"
                >
                  <Eye class="w-4 h-4" />
                </button>
                <button 
                  @click="updateStatus(vol, 'accepted')"
                  class="p-1.5 text-emerald-600 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 rounded-lg"
                  title="Accept Candidate"
                >
                  <CheckCircle class="w-4 h-4" />
                </button>
                <button 
                  @click="updateStatus(vol, 'rejected')"
                  class="p-1.5 text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-900/20 rounded-lg"
                  title="Reject Candidate"
                >
                  <XCircle class="w-4 h-4" />
                </button>
              </td>
            </tr>

            <tr v-if="filteredVolunteers.length === 0">
              <td colspan="7" class="py-8 text-center text-slate-400">
                {{ lang === 'ar' ? 'لا يوجد متطوعون مطابقون لهذا الفلتر' : 'No volunteer candidates found for this filter.' }}
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Application Full Details & Candidate Scoring Modal -->
    <div v-if="selectedCandidate" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
      <div class="bg-white dark:bg-slate-800 rounded-3xl max-w-3xl w-full p-6 sm:p-8 space-y-6 shadow-2xl border border-slate-100 dark:border-slate-700 max-h-[92vh] overflow-y-auto">
        
        <!-- Header -->
        <div class="flex items-center justify-between border-b dark:border-slate-700 pb-4">
          <div>
            <div class="flex items-center gap-2">
              <span class="text-xs font-mono font-bold text-blue-900 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30 px-2 py-0.5 rounded">
                #{{ selectedCandidate.id }}
              </span>
              <span 
                class="px-2 py-0.5 rounded text-[11px] font-bold uppercase border"
                :class="{
                  'bg-blue-50 text-blue-800 border-blue-200': selectedCandidate.status === 'new',
                  'bg-amber-50 text-amber-800 border-amber-200': selectedCandidate.status === 'screening',
                  'bg-purple-50 text-purple-800 border-purple-200': selectedCandidate.status === 'candidate_interview',
                  'bg-emerald-50 text-emerald-800 border-emerald-200': selectedCandidate.status === 'accepted',
                  'bg-rose-50 text-rose-800 border-rose-200': selectedCandidate.status === 'rejected'
                }"
              >
                {{ selectedCandidate.status }}
              </span>
            </div>
            <h3 class="text-2xl font-black text-slate-900 dark:text-white mt-1">
              {{ selectedCandidate.full_name || selectedCandidate.name }}
            </h3>
            <p class="text-[11px] text-slate-400">
              Submitted: {{ selectedCandidate.submitted_at || selectedCandidate.appliedOn || 'Recently' }} • Terms {{ selectedCandidate.terms_version || 'v2.0' }}
            </p>
          </div>
          <button @click="selectedCandidate = null" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-2 rounded-lg">
            <X class="w-6 h-6" />
          </button>
        </div>

        <!-- Section 1: Academic & Contact Overview -->
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 text-xs bg-slate-50 dark:bg-slate-700/40 p-4 rounded-2xl border border-slate-100 dark:border-slate-700">
          <div>
            <span class="text-slate-400 block text-[10px] uppercase font-bold">الجامعة (University)</span>
            <strong class="text-slate-800 dark:text-slate-200">{{ selectedCandidate.university || 'Alzaeim Alazhari University' }}</strong>
          </div>
          <div>
            <span class="text-slate-400 block text-[10px] uppercase font-bold">الكلية والتخصص (College &amp; Major)</span>
            <strong class="text-slate-800 dark:text-slate-200">{{ selectedCandidate.college }} • {{ selectedCandidate.major }}</strong>
          </div>
          <div>
            <span class="text-slate-400 block text-[10px] uppercase font-bold">الدفعة الأكاديمية (Batch)</span>
            <strong class="text-slate-800 dark:text-slate-200">Batch {{ selectedCandidate.batch || '26' }}</strong>
          </div>
          <div>
            <span class="text-slate-400 block text-[10px] uppercase font-bold">اللجنة المختارة (Team)</span>
            <strong class="text-blue-900 dark:text-blue-400 uppercase font-black">{{ selectedCandidate.team_id || selectedCandidate.team }}</strong>
          </div>
          <div>
            <span class="text-slate-400 block text-[10px] uppercase font-bold">المسار التخصصي (Specialty)</span>
            <strong class="capitalize text-slate-800 dark:text-slate-200">{{ selectedCandidate.specialty_id || 'General' }}</strong>
          </div>
          <div>
            <span class="text-slate-400 block text-[10px] uppercase font-bold">البريد الإلكتروني (Email)</span>
            <a :href="'mailto:' + selectedCandidate.email" class="text-blue-600 hover:underline break-all">{{ selectedCandidate.email }}</a>
          </div>
          <div>
            <span class="text-slate-400 block text-[10px] uppercase font-bold">الهاتف (Phone)</span>
            <strong dir="ltr" class="font-mono text-slate-800 dark:text-slate-200">{{ selectedCandidate.phone }}</strong>
          </div>
          <div>
            <span class="text-slate-400 block text-[10px] uppercase font-bold">رقم الواتساب (WhatsApp)</span>
            <a 
              :href="'https://wa.me/' + (selectedCandidate.whatsapp || selectedCandidate.phone).replace(/[^0-9]/g, '')" 
              target="_blank" 
              dir="ltr" 
              class="font-mono text-emerald-600 hover:underline flex items-center gap-1"
            >
              <span>{{ selectedCandidate.whatsapp || selectedCandidate.phone }}</span>
              <ExternalLink class="w-3 h-3" />
            </a>
          </div>
        </div>

        <!-- Section 2: Why Choose You Question (Spec v0.3.3 Section 5) -->
        <div class="p-5 rounded-2xl bg-amber-50/80 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-800 space-y-1.5 text-xs">
          <strong class="text-amber-900 dark:text-amber-300 uppercase block font-black text-sm">
            ★ لماذا نختارك من بين كل المتقدمين؟ (Why Should We Choose You):
          </strong>
          <p class="text-slate-900 dark:text-slate-100 leading-relaxed font-sans text-sm whitespace-pre-line">
            {{ selectedCandidate.why_choose_you || 'لم يتم تسجيل إجابة.' }}
          </p>
        </div>

        <!-- Section 3: All 7 General Questions (Spec v0.3.3 Section 3) -->
        <div class="space-y-3">
          <h4 class="font-black text-slate-900 dark:text-white uppercase text-xs flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-blue-600"></span>
            <span>الأسئلة العامة لنموذج التطوع (General Questionnaire - 7 Questions)</span>
          </h4>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-xs">
            <!-- Q1: About IEEE -->
            <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-700 space-y-1">
              <span class="text-slate-400 block font-bold text-[11px]">1. ماذا تعرف عن منظمة IEEE وفرع جامعة الزعيم الأزهري؟</span>
              <p class="text-slate-800 dark:text-slate-200 leading-relaxed">{{ generalAnswers.q1_about_ieee || 'No answer' }}</p>
            </div>

            <!-- Q2: Attended Events -->
            <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-700 space-y-1">
              <span class="text-slate-400 block font-bold text-[11px]">2. هل حضرت أياً من فعالياتنا السابقة؟</span>
              <p class="text-slate-800 dark:text-slate-200 font-semibold">
                {{ generalAnswers.q2_attended === 'yes' ? 'نعم (Yes)' : 'لا (No)' }}
                <span v-if="generalAnswers.q2_event_details" class="block font-normal text-slate-600 dark:text-slate-400 mt-0.5">
                  • الفعاليات: {{ generalAnswers.q2_event_details }}
                </span>
              </p>
            </div>

            <!-- Q3: Why Join -->
            <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-700 space-y-1">
              <span class="text-slate-400 block font-bold text-[11px]">3. ما الدافع والهدف الأساسي من رغبتك في الانضمام للفرع؟</span>
              <p class="text-slate-800 dark:text-slate-200 leading-relaxed">{{ generalAnswers.q3_why_join || 'No answer' }}</p>
            </div>

            <!-- Q4: Commitment Rating -->
            <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-700 space-y-1">
              <span class="text-slate-400 block font-bold text-[11px]">4. تقييمك لمدى التزامك وجديتك في التطوع (من 1 إلى 10)</span>
              <div class="flex items-center gap-2">
                <span class="px-2.5 py-1 rounded-lg bg-blue-100 dark:bg-blue-900/50 text-blue-900 dark:text-blue-300 font-mono font-bold text-sm">
                  {{ generalAnswers.q4_commitment || 'N/A' }} / 10
                </span>
              </div>
            </div>

            <!-- Q5: Meeting Type -->
            <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-700 space-y-1">
              <span class="text-slate-400 block font-bold text-[11px]">5. تفضيلك للاجتماعات والأنشطة</span>
              <p class="text-slate-800 dark:text-slate-200 font-bold capitalize">
                {{ generalAnswers.q5_meeting_type === 'online' ? 'عبر الإنترنت (Online)' : (generalAnswers.q5_meeting_type === 'offline' ? 'حضورياً (In-Person)' : 'كلاهما متاح (Both Online & In-person)') }}
              </p>
            </div>

            <!-- Q6: Prior Volunteering -->
            <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-700 space-y-1">
              <span class="text-slate-400 block font-bold text-[11px]">6. هل سبق لك التطوع في أي منظمة أو جهة أخرى؟</span>
              <p class="text-slate-800 dark:text-slate-200 font-semibold">
                {{ generalAnswers.q6_volunteered === 'yes' ? 'نعم (Yes)' : 'لا (No)' }}
                <span v-if="generalAnswers.q6_org_details" class="block font-normal text-slate-600 dark:text-slate-400 mt-0.5">
                  • الجهات: {{ generalAnswers.q6_org_details }}
                </span>
              </p>
            </div>

            <!-- Q7: English Level -->
            <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-700 space-y-1 md:col-span-2">
              <span class="text-slate-400 block font-bold text-[11px]">7. مستواك في اللغة الإنجليزية (English Level)</span>
              <span class="px-2.5 py-1 rounded-md bg-purple-50 dark:bg-purple-900/40 text-purple-900 dark:text-purple-300 font-bold capitalize">
                {{ generalAnswers.q7_english_level || 'Intermediate' }}
              </span>
            </div>
          </div>
        </div>

        <!-- Section 4: Team-Specific Answers (Spec v0.3.3 Section 4) -->
        <div class="space-y-3">
          <h4 class="font-black text-slate-900 dark:text-white uppercase text-xs flex items-center gap-2">
            <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
            <span>الأسئلة التخصصية للجنة ({{ selectedCandidate.team_id || selectedCandidate.team }} Specific Questions)</span>
          </h4>
          
          <div class="bg-slate-50 dark:bg-slate-700/40 rounded-2xl p-4 border border-slate-200 dark:border-slate-700 space-y-2 text-xs">
            <div v-if="teamAnswersList.length > 0" class="space-y-3">
              <div v-for="item in teamAnswersList" :key="item.key" class="border-b dark:border-slate-700 pb-2.5 last:border-0 last:pb-0">
                <span class="text-slate-400 block text-[11px] font-bold">{{ item.label }}</span>
                <p class="text-slate-800 dark:text-slate-200 leading-relaxed font-medium mt-0.5 whitespace-pre-line">{{ item.value }}</p>
              </div>
            </div>
            <p v-else class="text-slate-400 italic">لا توجد إجابات تخصصية مسجلة لهذه اللجنة.</p>
          </div>
        </div>

        <!-- Section 5: Reviewer Evaluation & Pipeline Controls -->
        <div class="space-y-3 pt-3 border-t dark:border-slate-700 text-xs">
          <div>
            <label class="block font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">
              ملاحظات المقيم ولجنة المقابلات (Internal Reviewer Notes)
            </label>
            <textarea 
              v-model="reviewerNotes" 
              rows="2" 
              placeholder="سجل ملاحظات الفرز أو تقييم المقابلة الشخصية هنا..." 
              class="w-full px-3 py-2 border rounded-xl dark:bg-slate-700 dark:border-slate-600 dark:text-white"
            ></textarea>
          </div>

          <!-- Status Pipeline Buttons -->
          <div class="flex flex-wrap items-center justify-between gap-2 pt-2">
            <div class="flex items-center gap-1.5 flex-wrap">
              <button 
                @click="updateStatus(selectedCandidate, 'screening', reviewerNotes)" 
                class="px-3 py-1.5 rounded-lg bg-amber-100 hover:bg-amber-200 text-amber-900 font-bold transition-colors"
              >
                Screening (قيد الفرز)
              </button>
              <button 
                @click="updateStatus(selectedCandidate, 'candidate_interview', reviewerNotes)" 
                class="px-3 py-1.5 rounded-lg bg-purple-100 hover:bg-purple-200 text-purple-900 font-bold transition-colors"
              >
                Schedule Interview (جدولة معاينة)
              </button>
              <button 
                @click="updateStatus(selectedCandidate, 'accepted', reviewerNotes)" 
                class="px-3.5 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white font-bold transition-colors"
              >
                Accept (قبول)
              </button>
              <button 
                @click="updateStatus(selectedCandidate, 'rejected', reviewerNotes)" 
                class="px-3.5 py-1.5 rounded-lg bg-rose-600 hover:bg-rose-700 text-white font-bold transition-colors"
              >
                Reject (رفض)
              </button>
            </div>
            <button @click="selectedCandidate = null" class="px-4 py-1.5 border dark:border-slate-600 rounded-lg text-slate-600 dark:text-slate-400">
              Close (إغلاق)
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { FileSpreadsheet, Search, Eye, CheckCircle, XCircle, X, ExternalLink } from 'lucide-vue-next';
import { authService } from '../../services/auth';
import { storageService } from '../../services/storage';
import { exportToExcel } from '../../services/excelExport';

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

const volunteers = ref([]);
const teamFilter = ref('All');
const statusFilter = ref('All');
const searchQuery = ref('');
const selectedCandidate = ref(null);
const reviewerNotes = ref('');

const userRole = computed(() => authService.getRole());
const isCoordinator = computed(() => ['programs_coordinator', 'magazine_coordinator', 'media_coordinator'].includes(userRole.value));

async function loadVolunteers() {
  const token = authService.token.value || '';
  try {
    const res = await fetch('./api.php?action=volunteer_applications', {
      headers: { 'Authorization': `Bearer ${token}` }
    });
    const data = await res.json();
    if (res.ok && data.status === 'success') {
      volunteers.value = data.data;
      return;
    }
  } catch (e) {}

  // Local storage fallback
  volunteers.value = storageService.getVolunteers();
}

const filteredVolunteers = computed(() => {
  return volunteers.value.filter(v => {
    const vTeam = (v.team_id || v.team || '').toLowerCase();
    const matchesTeam = teamFilter.value === 'All' || vTeam === teamFilter.value.toLowerCase();
    const matchesStatus = statusFilter.value === 'All' || v.status === statusFilter.value;
    const q = searchQuery.value.toLowerCase();
    const name = (v.full_name || v.name || '').toLowerCase();
    const email = (v.email || '').toLowerCase();
    const phone = (v.phone || '').toLowerCase();
    const major = (v.major || '').toLowerCase();
    const matchesSearch = name.includes(q) || email.includes(q) || phone.includes(q) || major.includes(q) || (v.id || '').toString().includes(q);
    return matchesTeam && matchesStatus && matchesSearch;
  });
});

function openDetails(candidate) {
  selectedCandidate.value = candidate;
  reviewerNotes.value = candidate.reviewer_notes || '';
}

function parseJson(raw) {
  if (!raw) return {};
  if (typeof raw === 'object') return raw;
  try {
    return JSON.parse(raw);
  } catch (e) {
    return {};
  }
}

const generalAnswers = computed(() => {
  if (!selectedCandidate.value) return {};
  return parseJson(selectedCandidate.value.general_answers);
});

const teamAnswersList = computed(() => {
  if (!selectedCandidate.value) return [];
  const parsed = parseJson(selectedCandidate.value.team_answers);
  const labelsMap = {
    program_exp: 'هل لديك خبرة سابقة في تنظيم البرامج؟',
    program_exp_details: 'تفاصيل خبرة تنظيم البرامج والفعاليات',
    program_why: 'لماذا اخترت لجنة البرامج والفعاليات؟',
    media_track: 'المسار الإعلامي المفضل',
    design_tools: 'برامج وأدوات التصميم التي تتقنها',
    portfolio_url: 'رابط معرض الأعمال أو البورتفوليو',
    video_exp: 'الخبرة في المونتاج وتحرير الفيديو',
    photography_gear: 'معدات التصوير والخبرة الميدانية',
    tech_track: 'المسار التقني والبرمجي',
    coding_languages: 'لغات البرمجة والتقنيات التي تتقنها',
    github_url: 'رابط حساب GitHub أو المشاريع السابقة',
    magazine_track: 'المسار المفضل في مجلة الفرع',
    writing_experience: 'الخبرة في الكتابة والتحرير والتدقيق اللغوي',
    pr_sponsorship_exp: 'الخبرة في العلاقات العامة واستقطاب الرعاة',
    hr_recruitment_exp: 'الخبرة في الموارد البشرية وإدارة الفرق',
    presenter_experience: 'الخبرة في التقديم المسرحي والخطابة'
  };

  return Object.entries(parsed).map(([k, v]) => ({
    key: k,
    label: labelsMap[k] || k.replace(/_/g, ' '),
    value: String(v)
  }));
});

async function updateStatus(vol, status, notes = '') {
  const token = authService.token.value || '';
  try {
    await fetch('./api.php?action=update_volunteer_status', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${token}`
      },
      body: JSON.stringify({
        id: vol.id,
        status,
        notes
      })
    });
  } catch (e) {}

  vol.status = status;
  vol.reviewer_notes = notes;
  emit('notify', props.lang === 'ar' ? `تم تحديث حالة المرشح إلى ${status}` : `Candidate status updated to ${status}`, 'success');
  selectedCandidate.value = null;
}

function formatAnswersForExcel(obj) {
  if (!obj || typeof obj !== 'object') return '';
  return Object.entries(obj).map(([k, v]) => `${k}: ${v}`).join(' | ');
}

function handleExport() {
  const data = filteredVolunteers.value.map(v => {
    const g = parseJson(v.general_answers);
    const t = parseJson(v.team_answers);

    return {
      'Candidate ID': v.id,
      'Full Name (الاسم الرباعي)': v.full_name || v.name,
      'University (الجامعة)': v.university || 'Alzaeim Alazhari University',
      'College (الكلية)': v.college || 'Engineering',
      'Major (التخصص)': v.major || '',
      'Batch (الدفعة)': v.batch || '',
      'Email (البريد)': v.email,
      'Phone (الهاتف)': v.phone,
      'WhatsApp (واتساب)': v.whatsapp || v.phone,
      'Team (اللجنة)': v.team_id || v.team,
      'Specialty (المسار)': v.specialty_id || 'General',
      'Q1: About IEEE AAU SB': g.q1_about_ieee || '',
      'Q2: Past Events Attended': g.q2_attended === 'yes' ? `Yes (${g.q2_event_details || ''})` : (g.q2_attended || 'No'),
      'Q3: Motivation / Why Join': g.q3_why_join || '',
      'Q4: Commitment Rating (1-10)': g.q4_commitment ? `${g.q4_commitment}/10` : '',
      'Q5: Meeting Preference': g.q5_meeting_type || '',
      'Q6: Prior Volunteering': g.q6_volunteered === 'yes' ? `Yes (${g.q6_org_details || ''})` : (g.q6_volunteered || 'No'),
      'Q7: English Level': g.q7_english_level || '',
      'Team Specific Answers (إجابات اللجنة)': formatAnswersForExcel(t),
      'Why Should We Choose You (لماذا نختارك)': v.why_choose_you || '',
      'Application Status': v.status,
      'Reviewer Notes': v.reviewer_notes || '',
      'Terms Version': v.terms_version || 'v2.0',
      'Submission Date': v.submitted_at || v.appliedOn || ''
    };
  });

  exportToExcel(data, 'IEEE_AAU_Full_Volunteer_Applications_v033', 'Volunteers');
  emit('notify', props.lang === 'ar' ? 'تم تصدير كافة بيانات المتقدمين وإجاباتهم إلى ملف Excel بنجاح!' : 'All candidate responses exported to Excel successfully!', 'success');
}

onMounted(() => {
  if (userRole.value === 'programs_coordinator') teamFilter.value = 'program';
  if (userRole.value === 'magazine_coordinator') teamFilter.value = 'magazine';
  if (userRole.value === 'media_coordinator') teamFilter.value = 'media';

  loadVolunteers();
});
</script>
