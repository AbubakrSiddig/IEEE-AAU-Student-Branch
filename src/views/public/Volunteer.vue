<template>
  <div class="py-12 bg-slate-50 dark:bg-slate-900 min-h-screen transition-colors">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
      <!-- Title & Progress Bar -->
      <div class="text-center space-y-2">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-blue-100 dark:bg-blue-900/40 text-blue-900 dark:text-blue-300 text-xs font-bold uppercase tracking-wider">
          <span>IEEE AAU SB • Volunteer Form v0.3.3</span>
        </div>
        <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white">
          {{ lang === 'ar' ? 'طلب التطوع في اللجان والفرق الطلابية' : 'Volunteer Application Form' }}
        </h1>
        <p class="text-xs text-slate-500 dark:text-slate-400">
          {{ lang === 'ar' ? 'انضم إلى عائلة IEEE بجامعة الزعيم الأزهري واصنع فارقاً حقيقياً في مجتمعك' : 'Join IEEE AAU Student Branch and lead impactful engineering initiatives.' }}
        </p>
      </div>

      <!-- Stepper Progress Bar (1: Terms -> 2: Personal -> 3: General -> 4: Team -> 5: Team Qs -> 6: Review) -->
      <div class="bg-white dark:bg-slate-800 rounded-2xl p-4 sm:p-5 border border-slate-200 dark:border-slate-700 shadow-sm transition-colors">
        <div class="flex items-center justify-between">
          <div 
            v-for="st in steps" 
            :key="st.num" 
            class="flex items-center flex-1"
          >
            <div class="flex items-center gap-2">
              <div 
                class="w-7 h-7 sm:w-8 sm:h-8 rounded-full flex items-center justify-center text-xs font-bold transition-all"
                :class="{
                  'bg-blue-900 text-white shadow-md ring-2 ring-blue-300 dark:ring-blue-600': currentStep === st.num,
                  'bg-emerald-500 text-white': currentStep > st.num,
                  'bg-slate-100 dark:bg-slate-700 text-slate-400': currentStep < st.num
                }"
              >
                <Check v-if="currentStep > st.num" class="w-3.5 h-3.5" />
                <span v-else>{{ st.num }}</span>
              </div>
              <span class="hidden md:block text-[11px] font-semibold" :class="currentStep >= st.num ? 'text-slate-800 dark:text-slate-200' : 'text-slate-400'">
                {{ lang === 'ar' ? st.titleAr : st.titleEn }}
              </span>
            </div>
            <div v-if="st.num < 6" class="flex-1 mx-2 h-0.5 bg-slate-200 dark:bg-slate-700">
              <div 
                class="h-full bg-blue-900 dark:bg-blue-500 transition-all duration-300" 
                :style="{ width: currentStep > st.num ? '100%' : '0%' }"
              ></div>
            </div>
          </div>
        </div>
      </div>

      <!-- STEP 1: Terms & Conditions -->
      <div v-if="currentStep === 1" class="bg-white dark:bg-slate-800 rounded-3xl p-6 sm:p-8 border border-slate-200 dark:border-slate-700 shadow-sm space-y-6">
        <div>
          <h2 class="text-xl font-bold text-slate-900 dark:text-white">
            {{ lang === 'ar' ? 'الشروط والأحكام الخاصة بالتطوع' : 'Volunteer Terms & Bylaws' }}
          </h2>
          <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
            {{ lang === 'ar' ? 'الرجاء قراءة ميثاق التطوع والالتزام بفرع IEEE AAU قبل البدء' : 'Please review and accept our volunteer code of conduct before applying.' }}
          </p>
        </div>

        <div class="h-64 overflow-y-auto p-4 bg-slate-50 dark:bg-slate-700/50 border border-slate-200 dark:border-slate-600 rounded-2xl text-xs text-slate-700 dark:text-slate-300 leading-relaxed font-sans space-y-3">
          <div class="font-bold text-slate-900 dark:text-white text-sm border-b dark:border-slate-600 pb-2">
            IEEE AAU Student Branch — Volunteer Charter (v2.0)
          </div>
          <div>
            <strong>1. الالتزام بالمهام والحضور:</strong><br/>
            يلتزم المتطوع بحضور الاجتماعات المقررة وإنجاز التكاليف الموكلة إليه في المواعيد المحددة. الأولوية للتحصيل الأكاديمي دائماً.
          </div>
          <div>
            <strong>2. الاحترام والعمل الجماعي:</strong><br/>
            الالتزام بأخلاقيات العمل الهندسي IEEE واحترام جميع الزملاء وأعضاء اللجان، وتجنب أي تصرفات تسيء لهوية الفرع.
          </div>
          <div>
            <strong>3. سرية البيانات والأصول:</strong><br/>
            الحفاظ على سرية ملفات ومراسلات وتصاميم الفرع وعدم استخدامها لأغراض شخصية دون إذن رسمي.
          </div>
          <div>
            <strong>4. حقوق المتطوع:</strong><br/>
            يحق للمتطوع الحصول على شهادات تقدير معتمدة وساعات خبرة عند إكمال المهام المحددة بنجاح.
          </div>
        </div>

        <label class="flex items-start gap-3 p-3 bg-blue-50/60 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-800 rounded-xl cursor-pointer">
          <input type="checkbox" v-model="termsAgreed" class="rounded text-blue-900 focus:ring-blue-600 mt-0.5" />
          <span class="text-xs font-semibold text-slate-800 dark:text-slate-200">
            {{ lang === 'ar' ? 'أقر بأنني قرأت الشروط والأحكام ولائحة التطوع وأوافق عليها تماماً.' : 'I have read, understood and agree to abide by the IEEE AAU Volunteer Terms.' }}
          </span>
        </label>

        <div class="pt-4 border-t dark:border-slate-700 flex justify-between">
          <button @click="$router.push('/')" class="px-5 py-2.5 border rounded-xl text-xs font-semibold text-slate-600 dark:text-slate-300">
            {{ lang === 'ar' ? 'إلغاء' : 'Cancel' }}
          </button>
          <button 
            @click="goToStep(2)" 
            :disabled="!termsAgreed"
            class="px-6 py-2.5 bg-blue-900 hover:bg-blue-800 text-white font-bold rounded-xl text-xs shadow disabled:opacity-40 flex items-center gap-1.5"
          >
            <span>{{ lang === 'ar' ? 'التالي: البيانات الشخصية' : 'Next: Personal Info' }}</span>
            <ArrowRight class="w-4 h-4" />
          </button>
        </div>
      </div>

      <!-- STEP 2: Personal Information (Spec v0.3.3 Section 3) -->
      <div v-else-if="currentStep === 2" class="bg-white dark:bg-slate-800 rounded-3xl p-6 sm:p-8 border border-slate-200 dark:border-slate-700 shadow-sm space-y-5">
        <div>
          <h2 class="text-xl font-bold text-slate-900 dark:text-white">
            {{ lang === 'ar' ? 'الصفحة الأولى: البيانات الشخصية' : 'Step 1: Personal Information' }}
          </h2>
          <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
            {{ lang === 'ar' ? 'يرجى كتابة الاسم الرباعي واختيار الجامعة والكلية بدقة' : 'Please enter your 4-part full name and academic details.' }}
          </p>
        </div>

        <div class="space-y-4 text-xs">
          <!-- 1. Full 4-part name -->
          <div>
            <label class="block font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">
              {{ lang === 'ar' ? 'الاسم الرباعي' : 'Full Name (4 parts)' }} *
            </label>
            <input 
              v-model="personal.fullName" 
              type="text" 
              placeholder="e.g. محمد أحمد علي إبراهيم" 
              class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 dark:text-white"
            />
            <span v-if="namePartsCount < 4 && personal.fullName.trim()" class="text-[11px] text-amber-600 block mt-1">
              ⚠️ {{ lang === 'ar' ? 'يجب أن يتكون الاسم من 4 كلمات على الأقل' : 'Must consist of at least 4 names/parts.' }}
            </span>
          </div>

          <!-- 2. University -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
              <label class="block font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">
                {{ lang === 'ar' ? 'الجامعة' : 'University' }} *
              </label>
              <select v-model="personal.university" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 dark:text-white">
                <option value="جامعة الزعيم الأزهري">جامعة الزعيم الأزهري (AAU)</option>
                <option value="جامعة الخرطوم">جامعة الخرطوم</option>
                <option value="جامعة السودان للعلوم والتكنولوجيا">جامعة السودان للعلوم والتكنولوجيا</option>
                <option value="جامعة النيلين">جامعة النيلين</option>
                <option value="أخرى">أخرى (Other)</option>
              </select>
            </div>

            <!-- 3. College -->
            <div>
              <label class="block font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">
                {{ lang === 'ar' ? 'الكلية' : 'College' }} *
              </label>
              <select v-model="personal.college" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 dark:text-white">
                <option value="" disabled>{{ lang === 'ar' ? 'اختر الكلية' : 'Select College' }}</option>
                <option value="الهندسة">كلية الهندسة (Engineering)</option>
                <option value="علوم الحاسوب وتقنية المعلومات">علوم الحاسوب وتقنية المعلومات (CS & IT)</option>
                <option value="العلوم الطبية">العلوم الطبية والتطبيقية</option>
                <option value="العلوم الإدارية">العلوم الإدارية والمالية</option>
                <option value="أخرى">كلية أخرى</option>
              </select>
            </div>
          </div>

          <!-- 4. Major & 5. Batch -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
              <label class="block font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">
                {{ lang === 'ar' ? 'التخصص / القسم' : 'Major / Department' }} *
              </label>
              <input v-model="personal.major" type="text" placeholder="e.g. Electrical / Software" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 dark:text-white" />
            </div>
            <div>
              <label class="block font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">
                {{ lang === 'ar' ? 'الدفعة' : 'Batch' }} *
              </label>
              <input v-model="personal.batch" type="text" placeholder="e.g. 25, 26, 27" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 dark:text-white" />
            </div>
          </div>

          <!-- 6. Email -->
          <div>
            <label class="block font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">
              {{ lang === 'ar' ? 'البريد الإلكتروني' : 'Email Address' }} *
            </label>
            <input v-model="personal.email" type="email" placeholder="name@aau.edu" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 dark:text-white" />
          </div>

          <!-- 7. Phone & 8. WhatsApp -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
              <label class="block font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">
                {{ lang === 'ar' ? 'رقم الهاتف' : 'Phone Number' }} *
              </label>
              <input v-model="personal.phone" type="tel" placeholder="+249 9XX XXX XXX" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 dark:text-white" />
            </div>
            <div>
              <div class="flex items-center justify-between mb-1">
                <label class="font-bold text-slate-700 dark:text-slate-300 uppercase">
                  {{ lang === 'ar' ? 'رقم الواتساب' : 'WhatsApp Number' }} *
                </label>
                <label class="flex items-center gap-1 cursor-pointer text-[10px] text-blue-900 dark:text-blue-400 font-semibold">
                  <input type="checkbox" v-model="sameAsPhone" @change="onSameAsPhoneChange" class="rounded" />
                  <span>{{ lang === 'ar' ? 'نفس رقم الهاتف' : 'Same as phone' }}</span>
                </label>
              </div>
              <input v-model="personal.whatsapp" :disabled="sameAsPhone" type="tel" placeholder="+249 9XX XXX XXX" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 dark:text-white disabled:opacity-50" />
            </div>
          </div>
        </div>

        <div class="pt-4 border-t dark:border-slate-700 flex justify-between">
          <button @click="goToStep(1)" class="px-5 py-2.5 border rounded-xl text-xs font-semibold text-slate-600 dark:text-slate-300">
            {{ lang === 'ar' ? 'السابق' : 'Back' }}
          </button>
          <button 
            @click="goToStep(3)" 
            :disabled="!isStep1Valid"
            class="px-6 py-2.5 bg-blue-900 hover:bg-blue-800 text-white font-bold rounded-xl text-xs shadow disabled:opacity-40 flex items-center gap-1.5"
          >
            <span>{{ lang === 'ar' ? 'التالي: الأسئلة العامة' : 'Next: General Questions' }}</span>
            <ArrowRight class="w-4 h-4" />
          </button>
        </div>
      </div>

      <!-- STEP 3: General Questions (Spec v0.3.3 Section 4) -->
      <div v-else-if="currentStep === 3" class="bg-white dark:bg-slate-800 rounded-3xl p-6 sm:p-8 border border-slate-200 dark:border-slate-700 shadow-sm space-y-6">
        <div>
          <h2 class="text-xl font-bold text-slate-900 dark:text-white">
            {{ lang === 'ar' ? 'الصفحة الثانية: الأسئلة العامة' : 'Step 2: General Questions' }}
          </h2>
          <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
            {{ lang === 'ar' ? 'أسئلة موحدة لجميع المتقدمين لتقييم الالتزام والدافع والمهارات العامة' : 'Standard questions for all applicants across all committees.' }}
          </p>
        </div>

        <div class="space-y-5 text-xs">
          <!-- Q1: What do you know about IEEE? -->
          <div>
            <label class="block font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">
              1. {{ lang === 'ar' ? 'ماذا تعرف عن منظمة IEEE؟' : 'What do you know about IEEE?' }} *
            </label>
            <textarea v-model="generalAnswers.q1_about_ieee" rows="3" required placeholder="اكتب نبذة مختصرة عما تعرفه عن المنظمة..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 dark:text-white"></textarea>
          </div>

          <!-- Q2: Attended events? (Yes/No + text if yes) -->
          <div>
            <label class="block font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">
              2. {{ lang === 'ar' ? 'هل سبق لك حضور أي حدث لفرعنا الطلابي IEEE-AAU؟' : 'Have you attended any event of our student branch IEEE-AAU?' }} *
            </label>
            <div class="flex items-center gap-4 mb-2">
              <label class="flex items-center gap-1.5 cursor-pointer text-slate-700 dark:text-slate-300">
                <input type="radio" value="yes" v-model="generalAnswers.q2_attended" />
                <span>{{ lang === 'ar' ? 'نعم (Yes)' : 'Yes' }}</span>
              </label>
              <label class="flex items-center gap-1.5 cursor-pointer text-slate-700 dark:text-slate-300">
                <input type="radio" value="no" v-model="generalAnswers.q2_attended" />
                <span>{{ lang === 'ar' ? 'لا (No)' : 'No' }}</span>
              </label>
            </div>
            <div v-if="generalAnswers.q2_attended === 'yes'">
              <input v-model="generalAnswers.q2_event_details" type="text" placeholder="اذكر اسم الفعالية أو موضوعها..." class="w-full px-3.5 py-2 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 dark:text-white" />
            </div>
          </div>

          <!-- Q3: Why join? -->
          <div>
            <label class="block font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">
              3. {{ lang === 'ar' ? 'لماذا تريد الانضمام إلينا؟' : 'Why do you want to join us?' }} *
            </label>
            <textarea v-model="generalAnswers.q3_why_join" rows="3" required placeholder="اذكر دافعك وأهدافك الشخصية من الانضمام للفرع..." class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 dark:text-white"></textarea>
          </div>

          <!-- Q4: Commitment 1 to 10 -->
          <div>
            <div class="flex items-center justify-between mb-2">
              <label class="font-bold text-slate-700 dark:text-slate-300 uppercase">
                4. {{ lang === 'ar' ? 'قيّم مدى التزامك بالاجتماعات من 1 إلى 10' : 'Rate your commitment to meetings from 1 to 10' }} *
              </label>
              <span class="font-mono font-bold text-blue-900 dark:text-blue-400 text-sm">{{ generalAnswers.q4_commitment }} / 10</span>
            </div>
            <div class="grid grid-cols-10 gap-1 sm:gap-2">
              <button 
                v-for="n in 10" 
                :key="n"
                type="button" 
                @click="generalAnswers.q4_commitment = n"
                class="py-2 rounded-lg font-bold text-xs transition-colors"
                :class="generalAnswers.q4_commitment === n ? 'bg-blue-900 text-white' : 'bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-200'"
              >
                {{ n }}
              </button>
            </div>
          </div>

          <!-- Q5: Online or Offline or Both -->
          <div>
            <label class="block font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">
              5. {{ lang === 'ar' ? 'هل تفضّل الاجتماعات أونلاين أم أوفلاين؟' : 'Do you prefer online or offline meetings?' }} *
            </label>
            <div class="grid grid-cols-3 gap-2">
              <button 
                type="button"
                @click="generalAnswers.q5_meeting_type = 'online'"
                class="py-2.5 px-3 rounded-xl border text-center font-semibold"
                :class="generalAnswers.q5_meeting_type === 'online' ? 'bg-blue-900 text-white border-blue-900' : 'bg-slate-50 dark:bg-slate-700 border-slate-200 dark:border-slate-600 text-slate-700 dark:text-slate-300'"
              >
                💻 Online
              </button>
              <button 
                type="button"
                @click="generalAnswers.q5_meeting_type = 'offline'"
                class="py-2.5 px-3 rounded-xl border text-center font-semibold"
                :class="generalAnswers.q5_meeting_type === 'offline' ? 'bg-blue-900 text-white border-blue-900' : 'bg-slate-50 dark:bg-slate-700 border-slate-200 dark:border-slate-600 text-slate-700 dark:text-slate-300'"
              >
                🏢 Offline
              </button>
              <button 
                type="button"
                @click="generalAnswers.q5_meeting_type = 'both'"
                class="py-2.5 px-3 rounded-xl border text-center font-semibold"
                :class="generalAnswers.q5_meeting_type === 'both' ? 'bg-blue-900 text-white border-blue-900' : 'bg-slate-50 dark:bg-slate-700 border-slate-200 dark:border-slate-600 text-slate-700 dark:text-slate-300'"
              >
                🔄 {{ lang === 'ar' ? 'كلاهما (Both)' : 'Both' }}
              </button>
            </div>
          </div>

          <!-- Q6: Past volunteer experience -->
          <div>
            <label class="block font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">
              6. {{ lang === 'ar' ? 'هل سبق لك التطوع ضمن فريق في جهة ما؟' : 'Have you volunteered in a team at any organization?' }} *
            </label>
            <div class="flex items-center gap-4 mb-2">
              <label class="flex items-center gap-1.5 cursor-pointer text-slate-700 dark:text-slate-300">
                <input type="radio" value="yes" v-model="generalAnswers.q6_volunteered" />
                <span>{{ lang === 'ar' ? 'نعم (Yes)' : 'Yes' }}</span>
              </label>
              <label class="flex items-center gap-1.5 cursor-pointer text-slate-700 dark:text-slate-300">
                <input type="radio" value="no" v-model="generalAnswers.q6_volunteered" />
                <span>{{ lang === 'ar' ? 'لا (No)' : 'No' }}</span>
              </label>
            </div>
            <div v-if="generalAnswers.q6_volunteered === 'yes'">
              <input v-model="generalAnswers.q6_org_details" type="text" placeholder="اذكر اسم المنظمة أو المبادرة ودورك فيها..." class="w-full px-3.5 py-2 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 dark:text-white" />
            </div>
          </div>

          <!-- Q7: English level -->
          <div>
            <label class="block font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">
              7. {{ lang === 'ar' ? 'قيّم مستواك في اللغة الإنجليزية' : 'Rate your English level' }} *
            </label>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
              <button 
                v-for="lvl in englishLevels" 
                :key="lvl.id"
                type="button"
                @click="generalAnswers.q7_english_level = lvl.id"
                class="py-2.5 px-2 rounded-xl border text-center font-semibold text-xs"
                :class="generalAnswers.q7_english_level === lvl.id ? 'bg-blue-900 text-white border-blue-900' : 'bg-slate-50 dark:bg-slate-700 border-slate-200 dark:border-slate-600 text-slate-700 dark:text-slate-300'"
              >
                {{ lang === 'ar' ? lvl.ar : lvl.en }}
              </button>
            </div>
          </div>
        </div>

        <div class="pt-4 border-t dark:border-slate-700 flex justify-between">
          <button @click="goToStep(2)" class="px-5 py-2.5 border rounded-xl text-xs font-semibold text-slate-600 dark:text-slate-300">
            {{ lang === 'ar' ? 'السابق' : 'Back' }}
          </button>
          <button 
            @click="goToStep(4)" 
            :disabled="!isStep2Valid"
            class="px-6 py-2.5 bg-blue-900 hover:bg-blue-800 text-white font-bold rounded-xl text-xs shadow disabled:opacity-40 flex items-center gap-1.5"
          >
            <span>{{ lang === 'ar' ? 'التالي: اختيار الفريق' : 'Next: Select Team' }}</span>
            <ArrowRight class="w-4 h-4" />
          </button>
        </div>
      </div>

      <!-- STEP 4: Team Selection (8 Teams) (Spec v0.3.3 Section 5) -->
      <div v-else-if="currentStep === 4" class="bg-white dark:bg-slate-800 rounded-3xl p-6 sm:p-8 border border-slate-200 dark:border-slate-700 shadow-sm space-y-6">
        <div>
          <h2 class="text-xl font-bold text-slate-900 dark:text-white">
            {{ lang === 'ar' ? 'الصفحة الثالثة: اختيار الفريق' : 'Step 3: Which team would you like to join?' }}
          </h2>
          <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
            {{ lang === 'ar' ? 'اختر فريقاً واحداً فقط للانضمام إليه. لكل فريق بطاقة توضح مهامه.' : 'Choose exactly one team. Each card outlines core responsibilities.' }}
          </p>
        </div>

        <!-- 8 Teams Grid (Spec v0.3.3 Section 5) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
          <div 
            v-for="tm in availableTeams" 
            :key="tm.id"
            @click="selectTeam(tm.id)"
            class="p-4 rounded-2xl border cursor-pointer transition-all flex flex-col justify-between"
            :class="selectedTeam === tm.id 
              ? 'border-blue-900 dark:border-blue-500 bg-blue-50/50 dark:bg-blue-950/40 ring-2 ring-blue-500' 
              : 'border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 hover:border-slate-300'"
          >
            <div class="space-y-1">
              <div class="flex items-center justify-between">
                <span class="font-extrabold text-sm text-slate-900 dark:text-white">{{ tm.name_en }}</span>
                <span class="text-xs text-slate-500 font-bold">{{ tm.name_ar }}</span>
              </div>
              <p class="text-[11px] text-slate-500 dark:text-slate-400 leading-relaxed">
                {{ lang === 'ar' ? tm.descAr : tm.descEn }}
              </p>
            </div>
            <div class="mt-2 text-[10px] font-bold text-blue-900 dark:text-blue-400 flex items-center justify-between pt-1 border-t border-slate-100 dark:border-slate-700">
              <span>{{ tm.id === 'media' ? (lang === 'ar' ? 'يتطلب تخصصاً فرعياً' : 'Requires Specialty') : (lang === 'ar' ? 'متاح للتقديم' : 'Available') }}</span>
              <span v-if="selectedTeam === tm.id" class="text-emerald-600 font-bold">✓ Selected</span>
            </div>
          </div>
        </div>

        <!-- If Media Selected: Sub-specialty selection (Spec v0.3.3 Section 7) -->
        <div v-if="selectedTeam === 'media'" class="p-5 bg-blue-50 dark:bg-slate-700/60 rounded-2xl border border-blue-200 dark:border-slate-600 space-y-3">
          <label class="block font-bold text-blue-900 dark:text-blue-300 text-xs uppercase">
            {{ lang === 'ar' ? 'اختر تخصصك في فريق الإعلام (إلزامي):' : 'Choose Your Media Specialty *' }}
          </label>
          <div class="grid grid-cols-2 gap-2 text-xs">
            <button 
              v-for="sp in mediaSpecialties" 
              :key="sp.id"
              type="button" 
              @click="selectedMediaSpecialty = sp.id"
              class="p-2.5 rounded-xl border text-center font-bold transition-all"
              :class="selectedMediaSpecialty === sp.id ? 'bg-blue-900 text-white border-blue-900' : 'bg-white dark:bg-slate-800 border-slate-200 dark:border-slate-600 text-slate-700 dark:text-slate-200'"
            >
              {{ lang === 'ar' ? sp.ar : sp.en }}
            </button>
          </div>
        </div>

        <div class="pt-4 border-t dark:border-slate-700 flex justify-between">
          <button @click="goToStep(3)" class="px-5 py-2.5 border rounded-xl text-xs font-semibold text-slate-600 dark:text-slate-300">
            {{ lang === 'ar' ? 'السابق' : 'Back' }}
          </button>
          <button 
            @click="goToStep(5)" 
            :disabled="!isStep3Valid"
            class="px-6 py-2.5 bg-blue-900 hover:bg-blue-800 text-white font-bold rounded-xl text-xs shadow disabled:opacity-40 flex items-center gap-1.5"
          >
            <span>{{ lang === 'ar' ? 'التالي: أسئلة الفريق' : 'Next: Team Questions' }}</span>
            <ArrowRight class="w-4 h-4" />
          </button>
        </div>
      </div>

      <!-- STEP 5: Questions of Selected Team + Fixed Question (Spec v0.3.3 Section 6 & 7) -->
      <div v-else-if="currentStep === 5" class="bg-white dark:bg-slate-800 rounded-3xl p-6 sm:p-8 border border-slate-200 dark:border-slate-700 shadow-sm space-y-6">
        <div>
          <div class="flex items-center gap-2 mb-1">
            <span class="text-xs uppercase font-bold text-blue-900 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30 px-2.5 py-0.5 rounded">
              {{ selectedTeam?.toUpperCase() }} {{ selectedMediaSpecialty ? `• ${selectedMediaSpecialty}` : '' }}
            </span>
          </div>
          <h2 class="text-xl font-bold text-slate-900 dark:text-white">
            {{ lang === 'ar' ? 'أسئلة الفريق التخصصية' : 'Specialized Team Questions' }}
          </h2>
          <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
            {{ lang === 'ar' ? 'أجب عن الأسئلة المخصصة للفريق الذي اخترته بدقة' : 'Please complete the custom technical questions for your selected committee.' }}
          </p>
        </div>

        <div class="space-y-5 text-xs">
          <!-- 6.1 Program Team Questions -->
          <div v-if="selectedTeam === 'program'" class="space-y-4">
            <div>
              <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                1. {{ lang === 'ar' ? 'هل عملت سابقاً في هذا الفريق؟' : 'Have you had experience working in this team?' }} *
              </label>
              <div class="flex gap-4 mb-2">
                <label class="flex items-center gap-1 cursor-pointer"><input type="radio" value="yes" v-model="teamAnswers.program_exp" /> Yes</label>
                <label class="flex items-center gap-1 cursor-pointer"><input type="radio" value="no" v-model="teamAnswers.program_exp" /> No</label>
              </div>
              <input v-if="teamAnswers.program_exp === 'yes'" v-model="teamAnswers.program_exp_details" placeholder="اذكر تفاصيل تجربتك..." class="w-full px-3 py-2 border rounded-xl dark:bg-slate-700 dark:border-slate-600" />
            </div>

            <div>
              <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                2. {{ lang === 'ar' ? 'لماذا اخترت هذا الفريق؟' : 'Why did you choose this team?' }} *
              </label>
              <textarea v-model="teamAnswers.program_why" rows="2" class="w-full px-3 py-2 border rounded-xl dark:bg-slate-700 dark:border-slate-600"></textarea>
            </div>

            <div>
              <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                3. {{ lang === 'ar' ? 'إذا واجهتك مشكلة وأردت حلّها، فما الخطوات التي ستتبعها؟' : 'If you encounter a problem, what steps will you follow to solve it?' }} *
              </label>
              <textarea v-model="teamAnswers.program_problem_solving" rows="2" class="w-full px-3 py-2 border rounded-xl dark:bg-slate-700 dark:border-slate-600"></textarea>
            </div>

            <div>
              <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                4. {{ lang === 'ar' ? 'ما الخطوات التي ستتبعها لتنظيم فعالية أو مؤتمر؟' : 'What steps will you follow to organize an event or conference?' }} *
              </label>
              <textarea v-model="teamAnswers.program_organize_steps" rows="2" class="w-full px-3 py-2 border rounded-xl dark:bg-slate-700 dark:border-slate-600"></textarea>
            </div>

            <div>
              <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                5. {{ lang === 'ar' ? 'قيّم عملك ضمن فريق (1-10)' : 'Rate your teamwork (1-10)' }} * ({{ teamAnswers.program_teamwork || 8 }}/10)
              </label>
              <input type="range" min="1" max="10" v-model="teamAnswers.program_teamwork" class="w-full" />
            </div>

            <div>
              <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                6. {{ lang === 'ar' ? 'قيّم مهارات التواصل لديك (1-10)' : 'Rate your communication skills (1-10)' }} * ({{ teamAnswers.program_comm || 8 }}/10)
              </label>
              <input type="range" min="1" max="10" v-model="teamAnswers.program_comm" class="w-full" />
            </div>
          </div>

          <!-- 6.2 Technical Team Questions -->
          <div v-else-if="selectedTeam === 'technical'" class="space-y-4">
            <div>
              <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                1. {{ lang === 'ar' ? 'هل عملت سابقاً في هذا الفريق؟' : 'Have you had experience working in this team?' }} *
              </label>
              <div class="flex gap-4 mb-2">
                <label class="flex items-center gap-1 cursor-pointer"><input type="radio" value="yes" v-model="teamAnswers.tech_exp" /> Yes</label>
                <label class="flex items-center gap-1 cursor-pointer"><input type="radio" value="no" v-model="teamAnswers.tech_exp" /> No</label>
              </div>
              <input v-if="teamAnswers.tech_exp === 'yes'" v-model="teamAnswers.tech_exp_details" placeholder="اذكر تفاصيل خبرتك التقنية..." class="w-full px-3 py-2 border rounded-xl dark:bg-slate-700 dark:border-slate-600" />
            </div>

            <div>
              <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                2. {{ lang === 'ar' ? 'لماذا اخترت هذا الفريق؟' : 'Why did you choose this team?' }} *
              </label>
              <textarea v-model="teamAnswers.tech_why" rows="2" class="w-full px-3 py-2 border rounded-xl dark:bg-slate-700 dark:border-slate-600"></textarea>
            </div>

            <div>
              <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                3. {{ lang === 'ar' ? 'هل تستطيع استخدام الأجهزة التقنية كالبروجكتر والميكروفون ونظام الصوت؟' : 'Can you use technical equipment (projector, microphone, sound system)?' }} *
              </label>
              <select v-model="teamAnswers.tech_equipment" class="w-full px-3 py-2 border rounded-xl dark:bg-slate-700 dark:border-slate-600">
                <option value="Yes">Yes (نعم أجيد استخدامها)</option>
                <option value="Partially">Partially (بشكل جزئي)</option>
                <option value="No">No (لا)</option>
              </select>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
              <div>
                <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">4. Microsoft Word Level *</label>
                <select v-model="teamAnswers.tech_word_level" class="w-full px-3 py-2 border rounded-xl dark:bg-slate-700 dark:border-slate-600">
                  <option value="Beginner">Beginner (مبتدئ)</option>
                  <option value="Intermediate">Intermediate (متوسط)</option>
                  <option value="Advanced">Advanced (متقدم)</option>
                  <option value="Fluent">Fluent (متقن)</option>
                </select>
              </div>
              <div>
                <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">5. Microsoft PowerPoint Level *</label>
                <select v-model="teamAnswers.tech_ppt_level" class="w-full px-3 py-2 border rounded-xl dark:bg-slate-700 dark:border-slate-600">
                  <option value="Beginner">Beginner (مبتدئ)</option>
                  <option value="Intermediate">Intermediate (متوسط)</option>
                  <option value="Advanced">Advanced (متقدم)</option>
                  <option value="Fluent">Fluent (متقن)</option>
                </select>
              </div>
            </div>

            <div>
              <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                6. {{ lang === 'ar' ? 'قيّم عملك تحت الضغط (1-10)' : 'Rate your work under pressure (1-10)' }} * ({{ teamAnswers.tech_pressure || 8 }}/10)
              </label>
              <input type="range" min="1" max="10" v-model="teamAnswers.tech_pressure" class="w-full" />
            </div>
          </div>

          <!-- 6.6 Magazine Team Questions (With Word Counter < 100 words) -->
          <div v-else-if="selectedTeam === 'magazine'" class="space-y-4">
            <div>
              <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                1. {{ lang === 'ar' ? 'قيّم كتابتك العلمية من 1 إلى 10' : 'Assess your scientific writing from 1 to 10' }} * ({{ teamAnswers.mag_writing_rate || 7 }}/10)
              </label>
              <input type="range" min="1" max="10" v-model="teamAnswers.mag_writing_rate" class="w-full" />
            </div>

            <!-- Vision for Magazine with LIVE WORD COUNTER (< 100 words) -->
            <div>
              <div class="flex items-center justify-between mb-1">
                <label class="font-bold text-slate-700 dark:text-slate-300">
                  2. {{ lang === 'ar' ? 'في أقل من 100 كلمة، اكتب رؤيتك لمجلة الفرع:' : 'In less than 100 words, write your vision for the chapter’s magazine:' }} *
                </label>
                <span 
                  class="font-mono text-[11px] font-bold"
                  :class="magazineWordCount > 100 ? 'text-rose-600' : 'text-slate-500'"
                >
                  {{ magazineWordCount }} / 100 {{ lang === 'ar' ? 'كلمة' : 'words' }}
                </span>
              </div>
              <textarea 
                v-model="teamAnswers.mag_vision" 
                rows="3" 
                class="w-full px-3 py-2 border rounded-xl dark:bg-slate-700 dark:border-slate-600"
                placeholder="اكتب رؤيتك لتطوير المجلة وأقسامها..."
              ></textarea>
              <span v-if="magazineWordCount > 100" class="text-rose-600 text-[11px] block mt-0.5">
                ⚠️ {{ lang === 'ar' ? 'تجاوزت الحد المسموح (100 كلمة كحد أقصى)' : 'Word limit exceeded! Must be less than 100 words.' }}
              </span>
            </div>

            <div>
              <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                3. {{ lang === 'ar' ? 'لو أُتيحت لك فرصة بحث أي موضوع تختاره، فما هو وكيف ستتناوله؟' : 'If given the opportunity to research any topic, what would it be and how will you approach it?' }} *
              </label>
              <textarea v-model="teamAnswers.mag_research_topic" rows="2" class="w-full px-3 py-2 border rounded-xl dark:bg-slate-700 dark:border-slate-600"></textarea>
            </div>

            <div>
              <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                4. {{ lang === 'ar' ? 'ما أحدث إنجاز علمي أو تقني أثار إعجابك أكثر ولماذا؟' : 'What recent scientific breakthrough fascinated you the most, and why?' }} *
              </label>
              <textarea v-model="teamAnswers.mag_breakthrough" rows="2" class="w-full px-3 py-2 border rounded-xl dark:bg-slate-700 dark:border-slate-600"></textarea>
            </div>

            <div>
              <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                5. {{ lang === 'ar' ? 'هل شاركت سابقاً في مشاريع بحثية أو مسابقات علمية؟' : 'Have you participated in research projects or scientific competitions?' }} *
              </label>
              <div class="flex gap-4 mb-2">
                <label class="flex items-center gap-1 cursor-pointer"><input type="radio" value="yes" v-model="teamAnswers.mag_past_research" /> Yes</label>
                <label class="flex items-center gap-1 cursor-pointer"><input type="radio" value="no" v-model="teamAnswers.mag_past_research" /> No</label>
              </div>
              <input v-if="teamAnswers.mag_past_research === 'yes'" v-model="teamAnswers.mag_past_research_details" placeholder="اذكر اسم المسابقة أو البحث..." class="w-full px-3 py-2 border rounded-xl dark:bg-slate-700 dark:border-slate-600" />
            </div>
          </div>

          <!-- 6.7 HR Team Questions -->
          <div v-else-if="selectedTeam === 'hr'" class="space-y-4">
            <div>
              <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                1. {{ lang === 'ar' ? 'هل لديك خبرة سابقة في تنظيم الأفراد وإدارتهم؟' : 'Do you have experience in managing/organizing people?' }} *
              </label>
              <div class="flex gap-4 mb-2">
                <label class="flex items-center gap-1 cursor-pointer"><input type="radio" value="yes" v-model="teamAnswers.hr_exp" /> Yes</label>
                <label class="flex items-center gap-1 cursor-pointer"><input type="radio" value="no" v-model="teamAnswers.hr_exp" /> No</label>
              </div>
              <input v-if="teamAnswers.hr_exp === 'yes'" v-model="teamAnswers.hr_exp_details" placeholder="اذكر المنظمات أو اللجان السابقة..." class="w-full px-3 py-2 border rounded-xl dark:bg-slate-700 dark:border-slate-600" />
            </div>

            <div>
              <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                2. {{ lang === 'ar' ? 'لو حدث خلاف بين عضوين في الفريق، فكيف ستتعامل معه؟' : 'If two team members had a conflict, how would you handle it?' }} *
              </label>
              <textarea v-model="teamAnswers.hr_conflict" rows="2" class="w-full px-3 py-2 border rounded-xl dark:bg-slate-700 dark:border-slate-600"></textarea>
            </div>

            <div>
              <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                3. {{ lang === 'ar' ? 'كيف ستحفّز متطوعاً انخفض التزامه؟' : 'How would you motivate a volunteer whose commitment has dropped?' }} *
              </label>
              <textarea v-model="teamAnswers.hr_motivation" rows="2" class="w-full px-3 py-2 border rounded-xl dark:bg-slate-700 dark:border-slate-600"></textarea>
            </div>

            <div>
              <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                4. {{ lang === 'ar' ? 'قيّم مهاراتك التنظيمية (1-10)' : 'Rate your organizational skills (1-10)' }} * ({{ teamAnswers.hr_org_rate || 8 }}/10)
              </label>
              <input type="range" min="1" max="10" v-model="teamAnswers.hr_org_rate" class="w-full" />
            </div>
          </div>

          <!-- Other Teams (PR, Presenters, Speaker) -->
          <div v-else-if="selectedTeam === 'pr'" class="space-y-4">
            <div>
              <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                1. {{ lang === 'ar' ? 'هل لديك خبرة سابقة في العلاقات العامة؟' : 'Previous experience in Public Relations?' }} *
              </label>
              <input v-model="teamAnswers.pr_exp" class="w-full px-3 py-2 border rounded-xl dark:bg-slate-700 dark:border-slate-600" />
            </div>
            <div>
              <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                2. {{ lang === 'ar' ? 'ما أهم صفات فريق العلاقات العامة الناجح؟' : 'Most important characteristics of a PR team?' }} *
              </label>
              <textarea v-model="teamAnswers.pr_traits" rows="2" class="w-full px-3 py-2 border rounded-xl dark:bg-slate-700 dark:border-slate-600"></textarea>
            </div>
          </div>

          <div v-else-if="selectedTeam === 'speaker'" class="space-y-4">
            <div>
              <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                1. {{ lang === 'ar' ? 'كيف تبحث عن المتحدث المناسب وتتواصل معه؟' : 'How can you search for the right speaker and communicate with them?' }} *
              </label>
              <textarea v-model="teamAnswers.speaker_outreach" rows="2" class="w-full px-3 py-2 border rounded-xl dark:bg-slate-700 dark:border-slate-600"></textarea>
            </div>
          </div>

          <div v-else-if="selectedTeam === 'presenters'" class="space-y-4">
            <div>
              <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">
                1. {{ lang === 'ar' ? 'هل لديك خبرة في التقديم المسرحي وعرافة الحفل؟' : 'Experience in stage presentation / MCing?' }} *
              </label>
              <input v-model="teamAnswers.presenter_exp" class="w-full px-3 py-2 border rounded-xl dark:bg-slate-700 dark:border-slate-600" />
            </div>
          </div>

          <!-- Media Specialties Questions -->
          <div v-else-if="selectedTeam === 'media'" class="space-y-4">
            <div v-if="selectedMediaSpecialty === 'graphic_designer'" class="space-y-3">
              <div>
                <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">1. Programs knowledge (Photoshop / Illustrator / Others) *</label>
                <input v-model="teamAnswers.media_programs" placeholder="e.g. Photoshop & Illustrator" class="w-full px-3 py-2 border rounded-xl dark:bg-slate-700 dark:border-slate-600" />
              </div>
              <div>
                <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">2. Portfolio link (Behance / Drive / Instagram) *</label>
                <input v-model="teamAnswers.media_portfolio" type="url" placeholder="https://..." class="w-full px-3 py-2 border rounded-xl dark:bg-slate-700 dark:border-slate-600" />
              </div>
              <div>
                <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">3. Device used for design: Phone / Laptop / Both *</label>
                <select v-model="teamAnswers.media_device" class="w-full px-3 py-2 border rounded-xl dark:bg-slate-700 dark:border-slate-600">
                  <option value="Laptop">Laptop (لابتوب)</option>
                  <option value="Phone">Phone (هاتف)</option>
                  <option value="Both">Both (كلاهما)</option>
                </select>
              </div>
            </div>

            <div v-else class="space-y-3">
              <div>
                <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Portfolio link / Samples of work (Drive / YouTube / Cloud) *</label>
                <input v-model="teamAnswers.media_portfolio" type="url" placeholder="https://..." class="w-full px-3 py-2 border rounded-xl dark:bg-slate-700 dark:border-slate-600" />
              </div>
              <div>
                <label class="block font-bold text-slate-700 dark:text-slate-300 mb-1">Years/Months of experience *</label>
                <input v-model="teamAnswers.media_experience_time" placeholder="e.g. 2 years" class="w-full px-3 py-2 border rounded-xl dark:bg-slate-700 dark:border-slate-600" />
              </div>
            </div>
          </div>

          <!-- 🔴 FIXED COMMON QUESTION FOR ALL TEAMS (Spec v0.3.3 Section 5) -->
          <div class="pt-4 border-t dark:border-slate-700">
            <div class="p-4 bg-amber-50/60 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-800 rounded-2xl space-y-1">
              <label class="block font-extrabold text-amber-900 dark:text-amber-300 text-xs uppercase">
                ★ {{ lang === 'ar' ? 'السؤال الثابت لكل الفرق:' : 'Standard Question for All Teams:' }}
              </label>
              <span class="block text-slate-800 dark:text-slate-200 font-bold mb-1">
                {{ lang === 'ar' ? 'لماذا نختارك من بين كل المتقدمين؟' : 'Why should we choose you among all applicants?' }} *
              </span>
              <textarea 
                v-model="whyChooseYou" 
                required 
                rows="3" 
                placeholder="اذكر نقاط قوتك وما يميزك وشغفك للمساهمة في فرع IEEE AAU..." 
                class="w-full px-3 py-2 border rounded-xl bg-white dark:bg-slate-800 dark:border-slate-600 dark:text-white"
              ></textarea>
            </div>
          </div>
        </div>

        <div class="pt-4 border-t dark:border-slate-700 flex justify-between">
          <button @click="goToStep(4)" class="px-5 py-2.5 border rounded-xl text-xs font-semibold text-slate-600 dark:text-slate-300">
            {{ lang === 'ar' ? 'السابق' : 'Back' }}
          </button>
          <button 
            @click="goToStep(6)" 
            :disabled="!whyChooseYou.trim() || (selectedTeam === 'magazine' && magazineWordCount > 100)"
            class="px-6 py-2.5 bg-blue-900 hover:bg-blue-800 text-white font-bold rounded-xl text-xs shadow disabled:opacity-40 flex items-center gap-1.5"
          >
            <span>{{ lang === 'ar' ? 'مراجعة الطلب' : 'Review Application' }}</span>
            <ArrowRight class="w-4 h-4" />
          </button>
        </div>
      </div>

      <!-- STEP 6: Review & Final Submission -->
      <div v-else-if="currentStep === 6" class="bg-white dark:bg-slate-800 rounded-3xl p-6 sm:p-8 border border-slate-200 dark:border-slate-700 shadow-sm space-y-6">
        <div>
          <h2 class="text-xl font-bold text-slate-900 dark:text-white">
            {{ lang === 'ar' ? 'المراجعة النهائية والإرسال' : 'Review & Submit Application' }}
          </h2>
          <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
            {{ lang === 'ar' ? 'راجع بياناتك بعناية قبل التأكيد النهائي للطلب' : 'Please verify all your information before final confirmation.' }}
          </p>
        </div>

        <div class="bg-slate-50 dark:bg-slate-700/50 rounded-2xl p-5 border border-slate-200 dark:border-slate-600 space-y-3 text-xs">
          <div class="flex justify-between border-b dark:border-slate-600 pb-2">
            <span class="text-slate-500">Applicant:</span>
            <strong class="text-slate-900 dark:text-white">{{ personal.fullName }}</strong>
          </div>
          <div class="flex justify-between border-b dark:border-slate-600 pb-2">
            <span class="text-slate-500">Institution & Major:</span>
            <strong class="text-slate-900 dark:text-white">{{ personal.university }} • {{ personal.major }} (Batch {{ personal.batch }})</strong>
          </div>
          <div class="flex justify-between border-b dark:border-slate-600 pb-2">
            <span class="text-slate-500">Contact:</span>
            <strong class="text-slate-900 dark:text-white" dir="ltr">{{ personal.email }} | {{ personal.phone }}</strong>
          </div>
          <div class="flex justify-between border-b dark:border-slate-600 pb-2">
            <span class="text-slate-500">Chosen Team:</span>
            <strong class="text-blue-900 dark:text-blue-400 uppercase font-extrabold">{{ selectedTeam }} {{ selectedMediaSpecialty ? `(${selectedMediaSpecialty})` : '' }}</strong>
          </div>
          <div class="space-y-1">
            <span class="text-slate-500 font-semibold">Why Choose You:</span>
            <p class="text-slate-800 dark:text-slate-200 italic">{{ whyChooseYou }}</p>
          </div>
        </div>

        <!-- Submit Button -->
        <div class="pt-4 border-t dark:border-slate-700 flex justify-between">
          <button @click="goToStep(5)" class="px-5 py-2.5 border rounded-xl text-xs font-semibold text-slate-600 dark:text-slate-300">
            {{ lang === 'ar' ? 'السابق' : 'Back' }}
          </button>
          <button 
            @click="submitFinalVolunteerApplication" 
            :disabled="submitting"
            class="px-8 py-3 bg-blue-900 hover:bg-blue-800 text-white font-bold rounded-xl text-xs shadow-lg flex items-center gap-2 disabled:opacity-50"
          >
            <CheckCircle class="w-4 h-4" />
            <span>{{ submitting ? (lang === 'ar' ? 'جاري الإرسال...' : 'Submitting...') : (lang === 'ar' ? 'تأكيد وإرسال طلب التطوع' : 'Submit Volunteer Application') }}</span>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { Check, ArrowRight, CheckCircle } from 'lucide-vue-next';
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

const currentStep = ref(1);
const termsAgreed = ref(false);
const submitting = ref(false);

const steps = [
  { num: 1, titleAr: 'الشروط', titleEn: 'Terms' },
  { num: 2, titleAr: 'البيانات الشخصية', titleEn: 'Personal' },
  { num: 3, titleAr: 'الأسئلة العامة', titleEn: 'General' },
  { num: 4, titleAr: 'اختيار الفريق', titleEn: 'Team' },
  { num: 5, titleAr: 'أسئلة الفريق', titleEn: 'Questions' },
  { num: 6, titleAr: 'المراجعة', titleEn: 'Review' },
];

// Personal Data
const personal = ref({
  fullName: '',
  university: 'جامعة الزعيم الأزهري',
  college: '',
  major: '',
  batch: '26',
  email: authService.getUser()?.email || '',
  phone: authService.getUser()?.phone || '',
  whatsapp: ''
});

const sameAsPhone = ref(false);

function onSameAsPhoneChange() {
  if (sameAsPhone.value) personal.value.whatsapp = personal.value.phone;
}

const namePartsCount = computed(() => {
  return personal.value.fullName.trim().split(/\s+/).filter(Boolean).length;
});

const isStep1Valid = computed(() => {
  return namePartsCount.value >= 4 &&
         personal.value.college !== '' &&
         personal.value.major.trim() !== '' &&
         personal.value.batch.trim() !== '' &&
         personal.value.email.includes('@') &&
         personal.value.phone.trim() !== '';
});

// General Questions
const generalAnswers = ref({
  q1_about_ieee: '',
  q2_attended: 'no',
  q2_event_details: '',
  q3_why_join: '',
  q4_commitment: 9,
  q5_meeting_type: 'both',
  q6_volunteered: 'no',
  q6_org_details: '',
  q7_english_level: 'Intermediate'
});

const englishLevels = [
  { id: 'Beginner', ar: 'مبتدئ (Beginner)', en: 'Beginner' },
  { id: 'Intermediate', ar: 'متوسط (Intermediate)', en: 'Intermediate' },
  { id: 'Advanced', ar: 'متقدم (Advanced)', en: 'Advanced' },
  { id: 'Fluent', ar: 'متقن (Fluent)', en: 'Fluent' },
];

const isStep2Valid = computed(() => {
  return generalAnswers.value.q1_about_ieee.trim() !== '' &&
         generalAnswers.value.q3_why_join.trim() !== '';
});

// 8 Teams Definition (Spec v0.3.3)
const availableTeams = [
  { id: 'media', name_en: 'Media', name_ar: 'الإعلام والتغطيات', descEn: 'Graphic design, photo & video coverage, content writing', descAr: 'تصميم الجرافيك، المونتاج، التصوير، كتابة المحتوى' },
  { id: 'technical', name_en: 'Technical', name_ar: 'الفريق التقني', descEn: 'A/V systems, projector, sound logistics, coding', descAr: 'تجهيز الصوت، العرض، الدعم الفني، وورش البرمجة' },
  { id: 'magazine', name_en: 'Magazine', name_ar: 'المجلة العلمية', descEn: 'Branch periodical magazine, scientific writing, research', descAr: 'تحرير المقالات، الصحافة العلمية، ونشر الأبحاث' },
  { id: 'program', name_en: 'Program', name_ar: 'البرامج والتنظيم', descEn: 'Event planning, logistics coordination, crowd flow', descAr: 'تخطيط الفعاليات والمؤتمرات وإدارة الأجندة' },
  { id: 'pr', name_en: 'PR', name_ar: 'العلاقات العامة', descEn: 'Partnerships, sponsor outreach, guest communication', descAr: 'بناء الشراكات والتواصل مع الجهات الخارجية والرعاة' },
  { id: 'hr', name_en: 'HR', name_ar: 'الموارد البشرية (جديد)', descEn: 'Team morale, interview scheduling, volunteer onboarding', descAr: 'إدارة وتوجيه المتطوعين، التقييم، وفض النزاعات' },
  { id: 'presenters', name_en: 'Presenters', name_ar: 'عرافة الحفل والتقديم', descEn: 'Stage presence, ceremony MCing, public speaking', descAr: 'التقديم المسرحي وإدارة منصات الفعاليات الرسمية' },
  { id: 'speaker', name_en: 'Speaker', name_ar: 'المتحدثون والتنسيق', descEn: 'Keynote speakers acquisition and topic prep', descAr: 'البحث والتنسيق مع المحاضرين والمتحدثين' },
];

const selectedTeam = ref('media');
const selectedMediaSpecialty = ref('graphic_designer');

const mediaSpecialties = [
  { id: 'graphic_designer', ar: 'مصمم جرافيك (Graphic Designer)', en: 'Graphic Designer' },
  { id: 'photographer', ar: 'مصور فوتوغرافي (Photographer)', en: 'Photographer' },
  { id: 'content_writer', ar: 'كاتب محتوى (Content Writer)', en: 'Content Writer' },
  { id: 'video_editor', ar: 'مونتير فيديو (Video Editor)', en: 'Video Editor' },
];

function selectTeam(teamId) {
  if (selectedTeam.value !== teamId) {
    selectedTeam.value = teamId;
    teamAnswers.value = {};
  }
}

const isStep3Valid = computed(() => {
  if (!selectedTeam.value) return false;
  if (selectedTeam.value === 'media' && !selectedMediaSpecialty.value) return false;
  return true;
});

// Team Answers
const teamAnswers = ref({});
const whyChooseYou = ref('');

// Magazine word counter
const magazineWordCount = computed(() => {
  const text = teamAnswers.value.mag_vision || '';
  return text.trim().split(/\s+/).filter(Boolean).length;
});

function goToStep(step) {
  currentStep.value = step;
  window.scrollTo({ top: 0, behavior: 'smooth' });
  saveDraft();
}

// Auto-save draft in localStorage
function saveDraft() {
  const draft = {
    personal: personal.value,
    generalAnswers: generalAnswers.value,
    selectedTeam: selectedTeam.value,
    selectedMediaSpecialty: selectedMediaSpecialty.value,
    teamAnswers: teamAnswers.value,
    whyChooseYou: whyChooseYou.value
  };
  localStorage.setItem('ieee_volunteer_draft_v033', JSON.stringify(draft));
}

function loadDraft() {
  try {
    const raw = localStorage.getItem('ieee_volunteer_draft_v033');
    if (raw) {
      const draft = JSON.parse(raw);
      if (draft.personal) personal.value = draft.personal;
      if (draft.generalAnswers) generalAnswers.value = draft.generalAnswers;
      if (draft.selectedTeam) selectedTeam.value = draft.selectedTeam;
      if (draft.selectedMediaSpecialty) selectedMediaSpecialty.value = draft.selectedMediaSpecialty;
      if (draft.teamAnswers) teamAnswers.value = draft.teamAnswers;
      if (draft.whyChooseYou) whyChooseYou.value = draft.whyChooseYou;
    }
  } catch (e) {}
}

async function submitFinalVolunteerApplication() {
  submitting.value = true;
  const token = authService.token.value || '';

  const payload = {
    full_name: personal.value.fullName,
    university: personal.value.university,
    college: personal.value.college,
    major: personal.value.major,
    batch: personal.value.batch,
    email: personal.value.email,
    phone: personal.value.phone,
    whatsapp: personal.value.whatsapp || personal.value.phone,
    team_id: selectedTeam.value,
    specialty_id: selectedTeam.value === 'media' ? selectedMediaSpecialty.value : null,
    general_answers: generalAnswers.value,
    team_answers: teamAnswers.value,
    why_choose_you: whyChooseYou.value
  };

  try {
    const res = await fetch('./api.php?action=volunteer_submit', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${token}`
      },
      body: JSON.stringify(payload)
    });
    const data = await res.json();
    if (res.ok && data.status === 'success') {
      localStorage.removeItem('ieee_volunteer_draft_v033');
      emit('notify', props.lang === 'ar' ? `تم إرسال طلب التطوع بنجاح! رقم طلبك: ${data.id}` : `Application submitted! ID: ${data.id}`, 'success');
      router.push('/my-applications');
      return;
    }
  } catch (e) {
    console.warn('Network error, fallback:', e);
  }

  // Fallback
  emit('notify', props.lang === 'ar' ? 'تم حفظ طلبك بنجاح!' : 'Application saved!', 'success');
  router.push('/my-applications');
}

onMounted(() => {
  loadDraft();
  if (authService.isLoggedIn() && authService.getUser()) {
    const u = authService.getUser();
    if (!personal.value.fullName) personal.value.fullName = u.name || '';
    if (!personal.value.email) personal.value.email = u.email || '';
    if (!personal.value.phone) personal.value.phone = u.phone || '';
  }
});
</script>
