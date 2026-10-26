<template>
  <div class="min-h-screen py-16 px-4 flex items-center justify-center bg-slate-50 dark:bg-slate-900 transition-colors">
    <div class="max-w-md w-full bg-white dark:bg-slate-800 rounded-3xl p-8 border border-slate-200 dark:border-slate-700 shadow-xl space-y-6">
      <!-- Brand & Header -->
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
          {{ lang === 'ar' ? 'تسجيل الدخول' : 'Sign In to Your Account' }}
        </h1>
        <p class="text-xs text-slate-500 dark:text-slate-400">
          {{ lang === 'ar' ? 'أدخل بريدك الإلكتروني وكلمة المرور للمتابعة' : 'Enter your credentials to access your dashboard or account.' }}
        </p>
      </div>

      <!-- Login Form -->
      <form @submit.prevent="handleLogin" class="space-y-4 text-xs">
        <div>
          <label class="block font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">
            {{ lang === 'ar' ? 'البريد الإلكتروني' : 'Email Address' }} *
          </label>
          <input 
            v-model="email" 
            required 
            type="email" 
            placeholder="name@aau.edu" 
            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-600"
          />
        </div>

        <div>
          <div class="flex items-center justify-between mb-1">
            <label class="font-bold text-slate-700 dark:text-slate-300 uppercase">
              {{ lang === 'ar' ? 'كلمة المرور' : 'Password' }} *
            </label>
            <router-link to="/forgot-password" class="text-blue-900 dark:text-blue-400 hover:underline">
              {{ lang === 'ar' ? 'نسيت كلمة المرور؟' : 'Forgot Password?' }}
            </router-link>
          </div>
          <input 
            v-model="password" 
            required 
            type="password" 
            placeholder="••••••••" 
            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 dark:text-white focus:outline-none focus:ring-2 focus:ring-blue-600"
          />
        </div>

        <div class="flex items-center justify-between">
          <label class="flex items-center gap-2 cursor-pointer text-slate-600 dark:text-slate-300">
            <input type="checkbox" v-model="rememberMe" class="rounded text-blue-900 focus:ring-blue-600" />
            <span>{{ lang === 'ar' ? 'تذكرني' : 'Remember Me' }}</span>
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
          <LogIn class="w-4 h-4" />
          <span>{{ loading ? (lang === 'ar' ? 'جاري التحقق...' : 'Signing In...') : (lang === 'ar' ? 'تسجيل الدخول' : 'Sign In') }}</span>
        </button>
      </form>

      <!-- Register Link -->
      <div class="text-center text-xs text-slate-500 dark:text-slate-400 border-t dark:border-slate-700 pt-4">
        {{ lang === 'ar' ? 'ليس لديك حساب؟' : "Don't have an account?" }}
        <router-link to="/register" class="text-blue-900 dark:text-blue-400 font-bold hover:underline ml-1">
          {{ lang === 'ar' ? 'إنشاء حساب جديد' : 'Create an Account' }}
        </router-link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { LogIn } from 'lucide-vue-next';
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
const route = useRoute();

const email = ref('');
const password = ref('');
const rememberMe = ref(true);
const loading = ref(false);
const errorMsg = ref('');

async function handleLogin() {
  loading.value = true;
  errorMsg.value = '';

  const res = await authService.login(email.value, password.value);
  loading.value = false;

  if (res.success) {
    emit('notify', props.lang === 'ar' ? `مرحباً بك مجدداً ${res.user.name}!` : `Welcome back, ${res.user.name}!`, 'success');
    
    // Redirect logic: if redirect query param exists, go there; else if admin -> /admin, else -> /
    const redirectUrl = route.query.redirect || res.redirect || '/';
    router.push(redirectUrl);
  } else {
    errorMsg.value = res.message;
  }
}
</script>
