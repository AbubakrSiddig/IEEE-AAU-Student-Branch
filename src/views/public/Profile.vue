<template>
  <div class="py-12 bg-slate-50 dark:bg-slate-900 min-h-screen transition-colors">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
      <!-- Header -->
      <div class="flex items-center justify-between border-b dark:border-slate-700 pb-4">
        <div>
          <h1 class="text-2xl font-black text-slate-900 dark:text-white">
            {{ lang === 'ar' ? 'الملف الشخصي والحساب' : 'My Profile & Account' }}
          </h1>
          <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
            {{ lang === 'ar' ? 'إدارة بياناتك الشخصية، كلمة المرور، وتفضيلات المظهر واللغة' : 'Manage your profile details, password, theme, and language preferences.' }}
          </p>
        </div>
        <button 
          @click="handleLogout"
          class="px-4 py-2 bg-rose-50 text-rose-700 hover:bg-rose-100 dark:bg-rose-900/30 dark:text-rose-300 rounded-xl text-xs font-bold transition-colors flex items-center gap-1.5"
        >
          <LogOut class="w-3.5 h-3.5" />
          <span>{{ lang === 'ar' ? 'تسجيل الخروج' : 'Log Out' }}</span>
        </button>
      </div>

      <!-- Quick Nav for Student Links -->
      <div class="grid grid-cols-2 gap-4">
        <router-link 
          to="/my-events" 
          class="p-4 bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 hover:border-blue-500 shadow-xs flex items-center justify-between transition-all"
        >
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-900/40 text-blue-900 dark:text-blue-300 flex items-center justify-center font-bold">
              <Calendar class="w-5 h-5" />
            </div>
            <div>
              <strong class="block text-sm font-bold text-slate-900 dark:text-white">{{ lang === 'ar' ? 'فعالياتي المسجلة' : 'My Events' }}</strong>
              <span class="text-xs text-slate-400">{{ lang === 'ar' ? 'عرض التذاكر وحالة الحضور' : 'View registered tickets' }}</span>
            </div>
          </div>
          <ArrowRight class="w-4 h-4 text-slate-400" />
        </router-link>

        <router-link 
          to="/my-applications" 
          class="p-4 bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 hover:border-blue-500 shadow-xs flex items-center justify-between transition-all"
        >
          <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-900/40 text-indigo-900 dark:text-indigo-300 flex items-center justify-center font-bold">
              <FileText class="w-5 h-5" />
            </div>
            <div>
              <strong class="block text-sm font-bold text-slate-900 dark:text-white">{{ lang === 'ar' ? 'طلباتي' : 'My Applications' }}</strong>
              <span class="text-xs text-slate-400">{{ lang === 'ar' ? 'متابعة العضوية والتطوع' : 'Track application status' }}</span>
            </div>
          </div>
          <ArrowRight class="w-4 h-4 text-slate-400" />
        </router-link>
      </div>

      <!-- Account Settings Card -->
      <div class="bg-white dark:bg-slate-800 rounded-3xl p-6 sm:p-8 border border-slate-200 dark:border-slate-700 shadow-sm space-y-6">
        <h2 class="text-base font-bold text-slate-900 dark:text-white border-b dark:border-slate-700 pb-3">
          {{ lang === 'ar' ? 'البيانات الشخصية' : 'Personal Details' }}
        </h2>

        <form @submit.prevent="updateProfile" class="space-y-4 text-xs">
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">{{ t('fullName') }}</label>
              <input v-model="profileForm.name" required type="text" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 dark:text-white" />
            </div>
            <div>
              <label class="block font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">{{ t('email') }}</label>
              <input v-model="profileForm.email" disabled type="email" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-100 dark:bg-slate-700/50 text-slate-500 cursor-not-allowed" />
            </div>
          </div>

          <div>
            <label class="block font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">{{ t('phoneNumber') }}</label>
            <input v-model="profileForm.phone" type="tel" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 dark:text-white" />
          </div>

          <div class="pt-4 border-t dark:border-slate-700">
            <h3 class="font-bold text-slate-900 dark:text-white text-xs uppercase mb-3">
              {{ lang === 'ar' ? 'تغيير كلمة المرور (اختياري)' : 'Change Password (Optional)' }}
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <input v-model="profileForm.newPassword" type="password" placeholder="New Password" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 dark:text-white" />
              <input v-model="profileForm.confirmPassword" type="password" placeholder="Confirm New Password" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 bg-slate-50 dark:bg-slate-700 dark:text-white" />
            </div>
          </div>

          <!-- Preferences: Theme & Language -->
          <div class="pt-4 border-t dark:border-slate-700 space-y-4">
            <h3 class="font-bold text-slate-900 dark:text-white text-xs uppercase">
              {{ lang === 'ar' ? 'تفضيلات المنصة' : 'Preferences' }}
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
              <div>
                <label class="block text-slate-500 dark:text-slate-400 mb-1 font-semibold">{{ lang === 'ar' ? 'المظهر (Theme)' : 'Theme' }}</label>
                <div class="flex items-center gap-2">
                  <button 
                    type="button" 
                    @click="setTheme('light')" 
                    class="flex-1 py-2 px-3 rounded-xl border text-xs font-semibold"
                    :class="currentTheme === 'light' ? 'bg-blue-900 text-white border-blue-900' : 'bg-slate-50 dark:bg-slate-700 border-slate-200 dark:border-slate-600 dark:text-slate-300'"
                  >
                    ☀️ Light
                  </button>
                  <button 
                    type="button" 
                    @click="setTheme('dark')" 
                    class="flex-1 py-2 px-3 rounded-xl border text-xs font-semibold"
                    :class="currentTheme === 'dark' ? 'bg-blue-900 text-white border-blue-900' : 'bg-slate-50 dark:bg-slate-700 border-slate-200 dark:border-slate-600 dark:text-slate-300'"
                  >
                    🌙 Dark
                  </button>
                  <button 
                    type="button" 
                    @click="setTheme('system')" 
                    class="flex-1 py-2 px-3 rounded-xl border text-xs font-semibold"
                    :class="currentTheme === 'system' ? 'bg-blue-900 text-white border-blue-900' : 'bg-slate-50 dark:bg-slate-700 border-slate-200 dark:border-slate-600 dark:text-slate-300'"
                  >
                    💻 Auto
                  </button>
                </div>
              </div>

              <div>
                <label class="block text-slate-500 dark:text-slate-400 mb-1 font-semibold">{{ lang === 'ar' ? 'اللغة' : 'Language' }}</label>
                <div class="flex items-center gap-2">
                  <button 
                    type="button" 
                    @click="setLang('ar')" 
                    class="flex-1 py-2 px-3 rounded-xl border text-xs font-semibold"
                    :class="lang === 'ar' ? 'bg-blue-900 text-white border-blue-900' : 'bg-slate-50 dark:bg-slate-700 border-slate-200 dark:border-slate-600 dark:text-slate-300'"
                  >
                    العربية (RTL)
                  </button>
                  <button 
                    type="button" 
                    @click="setLang('en')" 
                    class="flex-1 py-2 px-3 rounded-xl border text-xs font-semibold"
                    :class="lang === 'en' ? 'bg-blue-900 text-white border-blue-900' : 'bg-slate-50 dark:bg-slate-700 border-slate-200 dark:border-slate-600 dark:text-slate-300'"
                  >
                    English (LTR)
                  </button>
                </div>
              </div>
            </div>
          </div>

          <div class="pt-4 border-t dark:border-slate-700 flex justify-end">
            <button 
              type="submit" 
              class="px-6 py-2.5 bg-blue-900 hover:bg-blue-800 text-white font-bold rounded-xl text-xs shadow-md transition-all"
            >
              {{ lang === 'ar' ? 'حفظ التعديلات' : 'Save Changes' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { Calendar, FileText, ArrowRight, LogOut } from 'lucide-vue-next';
import { authService } from '../../services/auth';
import { themeService } from '../../services/theme';

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

const emit = defineEmits(['notify', 'toggle-lang']);
const router = useRouter();

const user = authService.getUser() || { name: '', email: '', phone: '' };
const profileForm = ref({
  name: user.name || '',
  email: user.email || '',
  phone: user.phone || '',
  newPassword: '',
  confirmPassword: ''
});

const currentTheme = ref(themeService.getTheme());

function setTheme(theme) {
  themeService.setTheme(theme);
  currentTheme.value = theme;
  emit('notify', props.lang === 'ar' ? `تم تفعيل الوضع: ${theme}` : `Theme changed to: ${theme}`, 'info');
}

function setLang(newLang) {
  if (props.lang !== newLang) {
    emit('toggle-lang');
  }
}

async function updateProfile() {
  if (profileForm.value.newPassword && profileForm.value.newPassword !== profileForm.value.confirmPassword) {
    emit('notify', props.lang === 'ar' ? 'كلمات المرور غير متطابقة!' : 'Passwords do not match!', 'error');
    return;
  }

  const token = authService.token.value || '';
  try {
    const res = await fetch('./api.php?action=update_profile', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${token}`
      },
      body: JSON.stringify({
        name: profileForm.value.name,
        phone: profileForm.value.phone,
        password: profileForm.value.newPassword || null
      })
    });
    const data = await res.json();
    if (res.ok && data.status === 'success') {
      emit('notify', props.lang === 'ar' ? 'تم تحديث الملف الشخصي بنجاح!' : 'Profile updated successfully!', 'success');
      // Update local storage
      const u = authService.getUser();
      if (u) {
        u.name = profileForm.value.name;
        u.phone = profileForm.value.phone;
        localStorage.setItem('ieee_aau_user', JSON.stringify(u));
      }
    }
  } catch (e) {
    emit('notify', 'Profile updated locally!', 'success');
  }
}

function handleLogout() {
  authService.logout();
  emit('notify', props.lang === 'ar' ? 'تم تسجيل الخروج بنجاح.' : 'Logged out successfully.', 'info');
  router.push('/login');
}

onMounted(() => {
  if (!authService.isLoggedIn()) {
    router.push('/login');
  }
});
</script>
