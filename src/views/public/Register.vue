<template>
  <div class="min-h-screen py-16 px-4 flex items-center justify-center bg-slate-50 dark:bg-slate-900 transition-colors">
    <div class="max-w-md w-full bg-white dark:bg-slate-800 rounded-3xl p-8 border border-slate-200 dark:border-slate-700 shadow-xl space-y-6">
      <!-- Header -->
      <div class="text-center space-y-2">
        <router-link to="/" class="inline-block">
          <img 
            src="/assets/images/logo-blue.png" 
            alt="IEEE AAU SB" 
            class="h-12 mx-auto object-contain dark:hidden"
          />
          <img 
            src="/assets/images/logo-white.png" 
            alt="IEEE AAU SB" 
            class="h-12 mx-auto object-contain hidden dark:block"
          />
        </router-link>
        <h1 class="text-2xl font-black text-slate-900 dark:text-white">
          {{ lang === 'ar' ? 'إنشاء حساب جديد' : 'Create an Account' }}
        </h1>
        <p class="text-xs text-slate-500 dark:text-slate-400">
          {{ lang === 'ar' ? 'سجّل حسابك لحضور الفعاليات وتقديم طلبات التطوع ومتابعتها' : 'Create your student account to register for events and manage applications.' }}
        </p>
      </div>

      <!-- Form -->
      <form @submit.prevent="handleRegister" class="space-y-4 text-xs">
        <div>
          <label class="block font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">
            {{ lang === 'ar' ? 'الاسم الرباعي' : 'Full Name' }} *
          </label>
          <input 
            v-model="name" 
            required 
            type="text" 
            placeholder="e.g. Ahmed Mohamed Ali" 
            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-600"
          />
        </div>

        <div>
          <label class="block font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">
            {{ lang === 'ar' ? 'البريد الإلكتروني' : 'Email Address' }} *
          </label>
          <input 
            v-model="email" 
            required 
            type="email" 
            placeholder="student@aau.edu" 
            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-600"
          />
        </div>

        <div>
          <label class="block font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">
            {{ lang === 'ar' ? 'رقم الهاتف' : 'Phone Number' }} *
          </label>
          <input 
            v-model="phone" 
            required 
            type="tel" 
            placeholder="+249 9XX XXX XXX" 
            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-600"
          />
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
          <div>
            <label class="block font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">
              {{ lang === 'ar' ? 'كلمة المرور' : 'Password' }} *
            </label>
            <input 
              v-model="password" 
              required 
              type="password" 
              placeholder="••••••••" 
              class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-600"
            />
          </div>
          <div>
            <label class="block font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">
              {{ lang === 'ar' ? 'تأكيد كلمة المرور' : 'Confirm Password' }} *
            </label>
            <input 
              v-model="passwordConfirm" 
              required 
              type="password" 
              placeholder="••••••••" 
              class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-600"
            />
          </div>
        </div>

        <div class="pt-1">
          <label class="flex items-start gap-2.5 text-xs text-slate-600 dark:text-slate-400 cursor-pointer">
            <input type="checkbox" required v-model="termsAgreed" class="rounded text-blue-900 focus:ring-blue-600 mt-0.5" />
            <span>
              {{ lang === 'ar' ? 'أوافق على الشروط والأحكام وسياسة خصوصية بيانات الفرع' : 'I agree to the IEEE AAU SB Terms & Conditions and Privacy Policy.' }}
            </span>
          </label>
        </div>

        <!-- Error Banner -->
        <div v-if="errorMsg" class="p-3 bg-rose-50 dark:bg-rose-900/30 border border-rose-200 dark:border-rose-800 text-rose-700 dark:text-rose-300 rounded-xl">
          {{ errorMsg }}
        </div>

        <button 
          type="submit" 
          :disabled="loading"
          class="w-full py-3 bg-blue-900 hover:bg-blue-800 text-white font-bold rounded-xl shadow-md transition-all text-sm flex items-center justify-center gap-2 disabled:opacity-50"
        >
          <UserPlus class="w-4 h-4" />
          <span>{{ loading ? (lang === 'ar' ? 'جاري التسجيل...' : 'Creating...') : (lang === 'ar' ? 'إنشاء الحساب' : 'Create Account') }}</span>
        </button>
      </form>

      <!-- Login Link -->
      <div class="text-center text-xs text-slate-500 dark:text-slate-400 border-t dark:border-slate-700 pt-4">
        {{ lang === 'ar' ? 'لديك حساب بالفعل؟' : 'Already have an account?' }}
        <router-link to="/login" class="text-blue-900 dark:text-blue-400 font-bold hover:underline ml-1">
          {{ lang === 'ar' ? 'تسجيل الدخول' : 'Sign In' }}
        </router-link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { UserPlus } from 'lucide-vue-next';
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

const name = ref('');
const email = ref('');
const phone = ref('');
const password = ref('');
const passwordConfirm = ref('');
const termsAgreed = ref(false);
const loading = ref(false);
const errorMsg = ref('');

async function handleRegister() {
  if (password.value !== passwordConfirm.value) {
    errorMsg.value = props.lang === 'ar' ? 'كلمات المرور غير متطابقة!' : 'Passwords do not match!';
    return;
  }

  loading.value = true;
  errorMsg.value = '';

  const res = await authService.register(name.value, email.value, password.value, phone.value);
  loading.value = false;

  if (res.success) {
    emit('notify', props.lang === 'ar' ? 'تم إنشاء الحساب بنجاح!' : 'Account registered successfully!', 'success');
    router.push('/');
  } else {
    errorMsg.value = res.message;
  }
}
</script>
