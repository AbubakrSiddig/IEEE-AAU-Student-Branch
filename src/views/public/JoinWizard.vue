<template>
  <div class="py-12 bg-slate-50 min-h-screen">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
      <!-- Title -->
      <div v-if="currentStep < 5" class="text-center space-y-2">
        <h1 class="text-3xl font-extrabold text-slate-900">
          {{ lang === 'ar' ? 'طلب الانضمام إلى الفرع الطلابي' : 'IEEE AAU SB Membership Application' }}
        </h1>
        <p class="text-sm text-slate-500">
          {{ lang === 'ar' ? 'أكمل الخطوات التالية لتقديم طلب العضوية الرسمي' : 'Complete the following steps to submit your official membership request.' }}
        </p>
      </div>

      <!-- Stepper Progress Bar (Screens 10, 11, 12, 13) -->
      <div v-if="currentStep < 5" class="bg-white rounded-2xl p-4 sm:p-6 border border-slate-200 shadow-sm">
        <div class="flex items-center justify-between">
          <div 
            v-for="(st, idx) in steps" 
            :key="st.num" 
            class="flex items-center flex-1"
          >
            <div class="flex items-center gap-2 sm:gap-3">
              <div 
                class="w-8 h-8 sm:w-9 sm:h-9 rounded-full flex items-center justify-center text-xs sm:text-sm font-bold transition-all duration-200"
                :class="{
                  'bg-blue-900 text-white shadow-md': currentStep === st.num,
                  'bg-emerald-500 text-white': currentStep > st.num,
                  'bg-slate-100 text-slate-400': currentStep < st.num
                }"
              >
                <Check v-if="currentStep > st.num" class="w-4 h-4" />
                <span v-else>{{ st.num }}</span>
              </div>
              <div class="hidden md:block text-left">
                <span class="block text-xs font-semibold" :class="currentStep >= st.num ? 'text-slate-900' : 'text-slate-400'">
                  {{ t(st.key) }}
                </span>
              </div>
            </div>
            <div v-if="idx < steps.length - 1" class="flex-1 mx-2 sm:mx-4 h-0.5 bg-slate-200">
              <div 
                class="h-full bg-blue-900 transition-all duration-300" 
                :style="{ width: currentStep > st.num ? '100%' : '0%' }"
              ></div>
            </div>
          </div>
        </div>
      </div>

      <!-- STEP 1: Terms & Conditions (Matching Screen 10) -->
      <div v-if="currentStep === 1" class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm space-y-6">
        <div>
          <h2 class="text-xl font-bold text-slate-900">{{ t('stepTerms') }}</h2>
          <p class="text-xs text-slate-500 mt-1">
            {{ lang === 'ar' ? 'يرجى قراءة الشروط والأحكام بعناية قبل المتابعة' : 'Please read and acknowledge the official branch bylaws and code of conduct.' }}
          </p>
        </div>

        <!-- Inner Scrolling Terms Box (Matching Screen 10) -->
        <div class="h-64 overflow-y-auto p-4 bg-slate-50 border border-slate-200 rounded-2xl text-xs text-slate-700 leading-relaxed font-sans space-y-3">
          <div class="font-bold text-slate-900 text-sm border-b pb-2">
            IEEE AAU Student Branch — Terms & Conditions (Version 2.0)
          </div>
          <div>
            <strong>1. Introduction</strong><br/>
            These terms and conditions govern your participation in IEEE AAU Student Branch activities, committees, events, and programs.
          </div>
          <div>
            <strong>2. Membership and Participation</strong><br/>
            By registering, you agree to abide by the IEEE Code of Conduct, Alzaeim Alazhari University regulations, and Branch Executive directives. Active involvement and continuous learning are standard expectations.
          </div>
          <div>
            <strong>3. Code of Conduct & Integrity</strong><br/>
            - Professionalism, mutual respect, and ethical conduct must be upheld at all times.<br/>
            - Confidential branch materials and communications must remain private and secure.<br/>
            - Unauthorized public representation of the branch is prohibited without prior approval.
          </div>
          <div>
            <strong>4. Academic Precedence</strong><br/>
            Academic responsibilities take priority over student activities. IEEE AAU events will not mandate absence from classes or official examinations.
          </div>
          <div>
            <strong>5. Volunteer Rights & Certificates</strong><br/>
            Members and volunteers completing assigned responsibilities with distinction receive verified IEEE certificates and recommendation letters.
          </div>
        </div>

        <!-- Mandatory Checkbox (Matching Screen 10) -->
        <div class="pt-2">
          <label class="flex items-start gap-3 p-3 bg-blue-50/50 border border-blue-100 rounded-xl cursor-pointer">
            <input 
              type="checkbox" 
              v-model="termsAgreed" 
              class="rounded text-blue-900 focus:ring-blue-600 mt-0.5" 
            />
            <span class="text-xs sm:text-sm font-semibold text-slate-800">
              {{ t('termsAgreementCheckbox') }}
            </span>
          </label>
        </div>

        <!-- Download Terms (PDF) button (Matching Screen 10) -->
        <div>
          <button 
            type="button" 
            @click="handleDownloadPDF"
            class="text-xs font-bold text-blue-900 hover:text-blue-700 flex items-center gap-1.5 underline"
          >
            <Download class="w-4 h-4" />
            <span>{{ t('downloadTermsPDF') }}</span>
          </button>
        </div>

        <!-- Action Buttons (Cancel / Next) (Matching Screen 10) -->
        <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
          <button 
            type="button" 
            @click="$router.push('/')"
            class="px-5 py-2.5 border border-slate-200 text-slate-600 font-semibold text-sm rounded-xl hover:bg-slate-50 transition-colors"
          >
            {{ t('cancel') }}
          </button>
          <button 
            type="button" 
            @click="goToStep(2)"
            :disabled="!termsAgreed"
            class="px-6 py-2.5 bg-blue-900 text-white font-semibold text-sm rounded-xl shadow-md hover:bg-blue-800 transition-all disabled:opacity-40 disabled:cursor-not-allowed flex items-center gap-1.5"
          >
            <span>{{ t('next') }}</span>
            <ArrowRight class="w-4 h-4" />
          </button>
        </div>
      </div>

      <!-- STEP 2: Personal Information (Matching Screen 11) -->
      <div v-else-if="currentStep === 2" class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm space-y-6">
        <div>
          <h2 class="text-xl font-bold text-slate-900">{{ t('stepPersonal') }}</h2>
          <p class="text-xs text-slate-500 mt-1">
            {{ lang === 'ar' ? 'الرجاء إدخال بياناتك الشخصية بدقة' : 'Please provide your accurate personal details.' }}
          </p>
        </div>

        <div class="space-y-4">
          <!-- Full Name -->
          <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">{{ t('fullName') }} *</label>
            <input 
              v-model="formData.name" 
              type="text" 
              placeholder="e.g. Ahmed Mohamed" 
              class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white"
            />
          </div>

          <!-- Email -->
          <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">{{ t('email') }} *</label>
            <input 
              v-model="formData.email" 
              type="email" 
              placeholder="ahmed@aau.edu" 
              class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white"
            />
          </div>

          <!-- Phone Number -->
          <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">{{ t('phoneNumber') }} *</label>
            <input 
              v-model="formData.phone" 
              type="tel" 
              placeholder="+249 912 345 678" 
              class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white"
            />
          </div>

          <!-- Date of Birth (Matching Screen 11) -->
          <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">{{ t('dob') }} *</label>
            <input 
              v-model="formData.dob" 
              type="date" 
              class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white"
            />
          </div>

          <!-- Gender (Matching Screen 11) -->
          <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">{{ t('gender') }} *</label>
            <select 
              v-model="formData.gender" 
              class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white"
            >
              <option value="" disabled>{{ lang === 'ar' ? 'اختر الجنس' : 'Select Gender' }}</option>
              <option value="Male">{{ t('male') }}</option>
              <option value="Female">{{ t('female') }}</option>
            </select>
          </div>

          <!-- University / College (Matching Screen 11) -->
          <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">{{ lang === 'ar' ? 'الجامعة' : 'University / Institution' }} *</label>
            <input 
              v-model="formData.university" 
              type="text" 
              readonly
              class="w-full px-4 py-2.5 bg-slate-100 border border-slate-200 rounded-xl text-sm text-slate-700 font-semibold cursor-not-allowed"
            />
          </div>
        </div>

        <!-- Buttons (Back / Next) -->
        <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
          <button 
            type="button" 
            @click="goToStep(1)"
            class="px-5 py-2.5 border border-slate-200 text-slate-600 font-semibold text-sm rounded-xl hover:bg-slate-50 transition-colors"
          >
            {{ t('back') }}
          </button>
          <button 
            type="button" 
            @click="goToStep(3)"
            :disabled="!isStep2Valid"
            class="px-6 py-2.5 bg-blue-900 text-white font-semibold text-sm rounded-xl shadow-md hover:bg-blue-800 transition-all disabled:opacity-40 disabled:cursor-not-allowed flex items-center gap-1.5"
          >
            <span>{{ t('next') }}</span>
            <ArrowRight class="w-4 h-4" />
          </button>
        </div>
      </div>

      <!-- STEP 3: Academic Information (Matching Screen 12) -->
      <div v-else-if="currentStep === 3" class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm space-y-6">
        <div>
          <h2 class="text-xl font-bold text-slate-900">{{ t('stepAcademic') }}</h2>
          <p class="text-xs text-slate-500 mt-1">
            {{ lang === 'ar' ? 'البيانات الأكاديمية والتخصص بالجامعة' : 'Academic standing, major, and committee preference.' }}
          </p>
        </div>

        <div class="space-y-4">
          <!-- College Dropdown (Matching Screen 12) -->
          <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">{{ t('college') }} *</label>
            <select 
              v-model="formData.college" 
              class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white"
            >
              <option value="" disabled>{{ lang === 'ar' ? 'اختر الكلية' : 'Select your college' }}</option>
              <option value="Computer Science & IT">{{ lang === 'ar' ? 'كلية علوم الحاسوب وتقنية المعلومات' : 'Faculty of Computer Science & IT' }}</option>
              <option value="Engineering">{{ lang === 'ar' ? 'كلية الهندسة' : 'Faculty of Engineering' }}</option>
              <option value="Medical Sciences">{{ lang === 'ar' ? 'العلوم الطبية والتطبيقية' : 'Faculty of Medical Sciences' }}</option>
              <option value="Business Administration">{{ lang === 'ar' ? 'العلوم الإدارية والمالية' : 'Faculty of Business' }}</option>
            </select>
          </div>

          <!-- Department Dropdown (Matching Screen 12) -->
          <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">{{ t('department') }} *</label>
            <select 
              v-model="formData.department" 
              class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white"
            >
              <option value="" disabled>{{ lang === 'ar' ? 'اختر القسم / التخصص' : 'Select your department' }}</option>
              <option value="CS">Computer Science (CS)</option>
              <option value="IT">Information Technology (IT)</option>
              <option value="EE">Electrical & Electronic Engineering (EE)</option>
              <option value="Biomedical">Biomedical Engineering</option>
              <option value="Civil">Civil Engineering</option>
            </select>
          </div>

          <!-- Year of Study (Matching Screen 12) -->
          <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">{{ t('yearOfStudy') }} *</label>
            <select 
              v-model="formData.year" 
              class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white"
            >
              <option value="" disabled>{{ lang === 'ar' ? 'اختر السنة الدراسية' : 'Select year' }}</option>
              <option value="1st">{{ lang === 'ar' ? 'السنة الأولى (1st)' : '1st Year' }}</option>
              <option value="2nd">{{ lang === 'ar' ? 'السنة الثانية (2nd)' : '2nd Year' }}</option>
              <option value="3rd">{{ lang === 'ar' ? 'السنة الثالثة (3rd)' : '3rd Year' }}</option>
              <option value="4th">{{ lang === 'ar' ? 'السنة الرابعة (4th)' : '4th Year' }}</option>
              <option value="5th">{{ lang === 'ar' ? 'السنة الخامسة (5th)' : '5th Year' }}</option>
            </select>
          </div>

          <!-- Student ID (optional) (Matching Screen 12) -->
          <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">{{ t('studentId') }}</label>
            <input 
              v-model="formData.studentId" 
              type="text" 
              placeholder="e.g. 2021-CS-049" 
              class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white"
            />
          </div>

          <!-- Preferred Team -->
          <div>
            <label class="block text-xs font-bold text-slate-700 uppercase mb-1">{{ lang === 'ar' ? 'الفريق المرغوب' : 'Preferred Team / Committee' }} *</label>
            <select 
              v-model="formData.preferredTeam" 
              class="w-full px-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white"
            >
              <option value="Tech">Technical / Coding Team</option>
              <option value="Media">Media & Publicity Team</option>
              <option value="Design">Design & Visual Branding</option>
              <option value="Events">Events & Logistics</option>
              <option value="PR">Public Relations & Outreach</option>
            </select>
          </div>
        </div>

        <!-- Buttons (Back / Next) -->
        <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
          <button 
            type="button" 
            @click="goToStep(2)"
            class="px-5 py-2.5 border border-slate-200 text-slate-600 font-semibold text-sm rounded-xl hover:bg-slate-50 transition-colors"
          >
            {{ t('back') }}
          </button>
          <button 
            type="button" 
            @click="goToStep(4)"
            :disabled="!isStep3Valid"
            class="px-6 py-2.5 bg-blue-900 text-white font-semibold text-sm rounded-xl shadow-md hover:bg-blue-800 transition-all disabled:opacity-40 disabled:cursor-not-allowed flex items-center gap-1.5"
          >
            <span>{{ t('next') }}</span>
            <ArrowRight class="w-4 h-4" />
          </button>
        </div>
      </div>

      <!-- STEP 4: Review & Submit (Requirements Section 6-B) -->
      <div v-else-if="currentStep === 4" class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm space-y-6">
        <div>
          <h2 class="text-xl font-bold text-slate-900">{{ t('stepReview') }}</h2>
          <p class="text-xs text-slate-500 mt-1">
            {{ t('reviewNotice') }}
          </p>
        </div>

        <div class="bg-slate-50 rounded-2xl p-5 border border-slate-200 space-y-3 text-sm">
          <div class="flex justify-between py-1 border-b border-slate-200">
            <span class="text-slate-500 font-medium">{{ t('fullName') }}:</span>
            <span class="font-bold text-slate-900">{{ formData.name }}</span>
          </div>
          <div class="flex justify-between py-1 border-b border-slate-200">
            <span class="text-slate-500 font-medium">{{ t('email') }}:</span>
            <span class="font-bold text-slate-900">{{ formData.email }}</span>
          </div>
          <div class="flex justify-between py-1 border-b border-slate-200">
            <span class="text-slate-500 font-medium">{{ t('phoneNumber') }}:</span>
            <span class="font-bold text-slate-900" dir="ltr">{{ formData.phone }}</span>
          </div>
          <div class="flex justify-between py-1 border-b border-slate-200">
            <span class="text-slate-500 font-medium">{{ t('college') }}:</span>
            <span class="font-bold text-slate-900">{{ formData.college }}</span>
          </div>
          <div class="flex justify-between py-1 border-b border-slate-200">
            <span class="text-slate-500 font-medium">{{ t('department') }}:</span>
            <span class="font-bold text-slate-900">{{ formData.department }}</span>
          </div>
          <div class="flex justify-between py-1 border-b border-slate-200">
            <span class="text-slate-500 font-medium">{{ t('yearOfStudy') }}:</span>
            <span class="font-bold text-slate-900">{{ formData.year }}</span>
          </div>
          <div class="flex justify-between py-1 border-b border-slate-200">
            <span class="text-slate-500 font-medium">{{ lang === 'ar' ? 'الفريق' : 'Team' }}:</span>
            <span class="font-bold text-blue-900">{{ formData.preferredTeam }}</span>
          </div>
          <div class="flex justify-between py-1 text-xs text-slate-500">
            <span>{{ t('termsAcceptedOn') }}:</span>
            <span>v2.0 ({{ new Date().toLocaleDateString() }})</span>
          </div>
        </div>

        <!-- Buttons -->
        <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
          <button 
            type="button" 
            @click="goToStep(3)"
            class="px-5 py-2.5 border border-slate-200 text-slate-600 font-semibold text-sm rounded-xl hover:bg-slate-50 transition-colors"
          >
            {{ t('back') }}
          </button>
          <button 
            type="button" 
            @click="submitFinalApplication"
            :disabled="isSubmitting"
            class="px-8 py-3 bg-blue-900 text-white font-bold text-sm rounded-xl shadow-lg hover:bg-blue-800 transition-all flex items-center gap-2"
          >
            <CheckCircle class="w-4 h-4" />
            <span>{{ lang === 'ar' ? 'تأكيد وإرسال الطلب' : 'Submit Application' }}</span>
          </button>
        </div>
      </div>

      <!-- STEP 5: Registration Success Screen (Matching Screen 13) -->
      <div v-else-if="currentStep === 5" class="bg-white rounded-3xl p-8 sm:p-14 border border-slate-200 shadow-xl text-center space-y-6">
        <!-- Big Checkmark in Circle (Matching Screen 13) -->
        <div class="w-20 h-20 mx-auto rounded-full bg-emerald-50 border-4 border-emerald-500 text-emerald-600 flex items-center justify-center shadow-lg">
          <Check class="w-10 h-10 stroke-[3]" />
        </div>

        <div class="space-y-2">
          <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900">
            {{ t('successTitle') }}
          </h2>
          <p class="text-sm text-slate-600 max-w-md mx-auto leading-relaxed">
            {{ t('successSubtitle') }}
          </p>
        </div>

        <!-- Generated Application ID Badge -->
        <div class="inline-block bg-blue-50 border border-blue-200 rounded-2xl px-6 py-3">
          <span class="block text-xs uppercase font-bold text-blue-700 tracking-wider">
            {{ t('applicationNumber') }}
          </span>
          <span class="text-2xl font-black text-blue-900 tracking-wider">
            {{ generatedId }}
          </span>
        </div>

        <!-- Buttons (Back to Home / View My Applications) (Matching Screen 13) -->
        <div class="pt-6 flex flex-col sm:flex-row items-center justify-center gap-4">
          <router-link 
            to="/" 
            class="w-full sm:w-auto px-8 py-3 bg-blue-900 hover:bg-blue-800 text-white font-bold rounded-xl shadow-md text-sm transition-all"
          >
            {{ t('backToHome') }}
          </router-link>

          <router-link 
            to="/track-application" 
            class="w-full sm:w-auto px-8 py-3 border border-slate-300 text-slate-700 font-semibold rounded-xl text-sm hover:bg-slate-50 transition-colors"
          >
            {{ t('viewMyApplications') }}
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Check, ArrowRight, Download, CheckCircle } from 'lucide-vue-next';
import confetti from 'canvas-confetti';
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

const currentStep = ref(1);
const termsAgreed = ref(false);
const isSubmitting = ref(false);
const generatedId = ref('#001');

const steps = [
  { num: 1, key: 'stepTerms' },
  { num: 2, key: 'stepPersonal' },
  { num: 3, key: 'stepAcademic' },
  { num: 4, key: 'stepReview' },
];

const formData = ref({
  name: '',
  email: '',
  phone: '',
  dob: '',
  gender: '',
  university: 'Alzaeim Alazhari University',
  college: '',
  department: '',
  year: '',
  studentId: '',
  preferredTeam: 'Tech'
});

const isStep2Valid = computed(() => {
  return formData.value.name.trim() !== '' &&
         formData.value.email.includes('@') &&
         formData.value.phone.trim() !== '' &&
         formData.value.dob !== '' &&
         formData.value.gender !== '';
});

const isStep3Valid = computed(() => {
  return formData.value.college !== '' &&
         formData.value.department !== '' &&
         formData.value.year !== '';
});

function goToStep(step) {
  currentStep.value = step;
  window.scrollTo({ top: 0, behavior: 'smooth' });
}

function handleDownloadPDF() {
  downloadTermsPDF('v2.0');
  emit('notify', 'Terms and Conditions PDF downloaded!', 'success');
}

function submitFinalApplication() {
  isSubmitting.value = true;
  
  const createdApp = storageService.addApplication({
    name: formData.value.name,
    email: formData.value.email,
    phone: formData.value.phone,
    major: formData.value.department,
    college: formData.value.college,
    year: formData.value.year,
    studentId: formData.value.studentId,
    preferredTeam: formData.value.preferredTeam,
    termsVersion: 'v2.0',
    termsAcceptedAt: new Date().toISOString().replace('T', ' ').slice(0, 16)
  });

  generatedId.value = createdApp.id;

  // Trigger celebration confetti
  try {
    confetti({
      particleCount: 100,
      spread: 70,
      origin: { y: 0.6 }
    });
  } catch (e) {
    // Ignore if canvas confetti not supported
  }

  isSubmitting.value = false;
  currentStep.value = 5;
  emit('notify', `Application ${createdApp.id} submitted successfully!`, 'success');
}
</script>
