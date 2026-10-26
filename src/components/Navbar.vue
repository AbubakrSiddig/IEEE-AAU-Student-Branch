<template>
  <header class="sticky top-0 z-40 bg-white/95 dark:bg-slate-900/95 backdrop-blur-md border-b border-slate-100 dark:border-slate-800 shadow-xs transition-colors duration-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between h-20">
        <!-- Brand Logo (Changes in Dark Mode) -->
        <router-link to="/" class="flex items-center gap-3 group shrink-0">
          <img 
            src="/assets/images/logo-blue.png" 
            alt="IEEE AAU Student Branch Logo" 
            class="h-11 sm:h-12 w-auto object-contain transition-transform group-hover:scale-105 duration-200 dark:hidden"
          />
          <img 
            src="/assets/images/logo-white.png" 
            alt="IEEE AAU Student Branch Logo" 
            class="h-11 sm:h-12 w-auto object-contain transition-transform group-hover:scale-105 duration-200 hidden dark:block"
          />
        </router-link>

        <!-- Desktop Navigation Links (Direct Links as requested) -->
        <nav class="hidden lg:flex items-center gap-1 xl:gap-1.5">
          <router-link 
            v-for="link in navLinks" 
            :key="link.path" 
            :to="link.path"
            class="px-2.5 xl:px-3 py-2 text-xs xl:text-sm font-medium text-slate-700 dark:text-slate-200 hover:text-blue-900 dark:hover:text-blue-400 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors whitespace-nowrap"
            active-class="!text-blue-900 dark:!text-blue-400 !font-bold !bg-blue-50/70 dark:!bg-slate-800"
          >
            {{ t(link.key) }}
          </router-link>
        </nav>

        <!-- Action Items (Right) -->
        <div class="hidden sm:flex items-center gap-2 xl:gap-2.5 shrink-0">
          <!-- Search trigger -->
          <button 
            @click="$emit('open-search')" 
            class="p-2 text-slate-500 hover:text-blue-900 dark:text-slate-400 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition-colors"
            title="Search"
          >
            <Search class="w-4 h-4" />
          </button>

          <!-- Dark Mode Toggle (☀️ / 🌙 / System) -->
          <button 
            @click="cycleTheme" 
            class="p-2 text-slate-500 hover:text-blue-900 dark:text-slate-400 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition-colors"
            :title="`Current theme: ${currentTheme}`"
          >
            <Sun v-if="currentTheme === 'light'" class="w-4 h-4 text-amber-500" />
            <Moon v-else-if="currentTheme === 'dark'" class="w-4 h-4 text-blue-400" />
            <Monitor v-else class="w-4 h-4 text-slate-400" />
          </button>

          <!-- Language Switcher -->
          <button 
            @click="toggleLang" 
            class="px-2.5 py-1.5 text-xs font-bold border border-slate-200 dark:border-slate-700 hover:border-blue-400 rounded-xl text-slate-700 dark:text-slate-300 transition-colors flex items-center gap-1.5 bg-slate-50 dark:bg-slate-800"
          >
            <Globe class="w-3.5 h-3.5 text-slate-400" />
            <span>{{ lang === 'ar' ? 'English' : 'العربية' }}</span>
          </button>

          <!-- User Authentication State (ICON ONLY when logged in - username hidden to save space) -->
          <div v-if="isLoggedIn" class="relative user-dropdown-container">
            <button 
              @click="userMenuOpen = !userMenuOpen"
              class="w-9 h-9 rounded-full bg-blue-900 hover:bg-blue-800 text-white font-bold text-xs flex items-center justify-center shadow-xs hover:shadow-md transition-all active:scale-95 ring-2 ring-blue-100 dark:ring-blue-950 focus:outline-none"
              :title="userName + ' (' + userRole + ')'"
            >
              <span>{{ userInitial }}</span>
            </button>

            <!-- Dropdown Menu (Opens on click of the avatar icon) -->
            <transition
              enter-active-class="transition ease-out duration-150"
              enter-from-class="opacity-0 translate-y-1 scale-95"
              enter-to-class="opacity-100 translate-y-0 scale-100"
              leave-active-class="transition ease-in duration-100"
              leave-from-class="opacity-100 translate-y-0 scale-100"
              leave-to-class="opacity-0 translate-y-1 scale-95"
            >
              <div 
                v-if="userMenuOpen" 
                @click="userMenuOpen = false"
                class="absolute ltr:right-0 rtl:left-0 mt-2 w-52 bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-100 dark:border-slate-700 py-2 z-50 text-xs"
              >
                <!-- User Info Header inside dropdown -->
                <div class="px-4 py-2.5 border-b dark:border-slate-700/80">
                  <span class="block font-bold text-slate-900 dark:text-white text-sm truncate">{{ userName }}</span>
                  <span class="inline-block px-2 py-0.5 rounded text-[10px] font-semibold bg-blue-50 dark:bg-blue-950 text-blue-700 dark:text-blue-300 capitalize mt-1">{{ userRole }}</span>
                </div>

                <div class="p-1 space-y-0.5">
                  <router-link to="/profile" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700/60 font-medium">
                    <User class="w-4 h-4 text-slate-400" />
                    <span>{{ lang === 'ar' ? 'حسابي الشخصي' : 'My Profile' }}</span>
                  </router-link>
                  <router-link to="/my-events" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700/60 font-medium">
                    <Calendar class="w-4 h-4 text-slate-400" />
                    <span>{{ lang === 'ar' ? 'فعالياتي المسجلة' : 'My Events' }}</span>
                  </router-link>
                  <router-link to="/my-applications" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700/60 font-medium">
                    <FileText class="w-4 h-4 text-slate-400" />
                    <span>{{ lang === 'ar' ? 'طلبات التطوع' : 'My Applications' }}</span>
                  </router-link>
                  <router-link v-if="isAdmin" to="/admin" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-blue-900 dark:text-blue-400 font-bold hover:bg-blue-50 dark:hover:bg-slate-700/60 border-t dark:border-slate-700/60">
                    <ShieldCheck class="w-4 h-4" />
                    <span>{{ lang === 'ar' ? 'لوحة التحكم الإدارية' : 'Admin Portal' }}</span>
                  </router-link>
                </div>

                <div class="pt-1 border-t dark:border-slate-700/80 px-1">
                  <button @click="logout" class="w-full text-left rtl:text-right flex items-center gap-2.5 px-3 py-2 rounded-xl text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/40 font-semibold">
                    <LogOut class="w-4 h-4" />
                    <span>{{ lang === 'ar' ? 'تسجيل الخروج' : 'Log Out' }}</span>
                  </button>
                </div>
              </div>
            </transition>
          </div>

          <!-- Visitor Sign In -->
          <router-link 
            v-else 
            to="/login"
            class="px-3 py-1.5 text-xs font-semibold text-slate-700 dark:text-slate-200 hover:text-blue-900 border border-slate-200 dark:border-slate-700 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 transition-colors"
          >
            {{ lang === 'ar' ? 'دخول' : 'Sign In' }}
          </router-link>

          <!-- Primary CTA Button: Volunteer Now -->
          <router-link 
            to="/volunteer" 
            class="inline-flex items-center justify-center px-4 py-2 text-xs font-bold text-white bg-blue-900 hover:bg-blue-800 rounded-xl shadow-xs hover:shadow-md transition-all active:scale-95 whitespace-nowrap"
          >
            {{ t('volunteer') }}
          </router-link>
        </div>

        <!-- Mobile Header Controls -->
        <div class="flex lg:hidden items-center gap-2">
          <button @click="cycleTheme" class="p-1.5 text-slate-600 dark:text-slate-300">
            <Sun v-if="currentTheme === 'light'" class="w-4 h-4 text-amber-500" />
            <Moon v-else class="w-4 h-4 text-blue-400" />
          </button>
          <button @click="toggleLang" class="px-2 py-1 text-xs font-bold border rounded-lg dark:text-white dark:border-slate-700">
            {{ lang === 'ar' ? 'EN' : 'عربي' }}
          </button>
          <button 
            @click="mobileOpen = !mobileOpen" 
            class="p-2 text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-xl transition-colors"
          >
            <Menu v-if="!mobileOpen" class="w-6 h-6" />
            <X v-else class="w-6 h-6" />
          </button>
        </div>
      </div>
    </div>

    <!-- Mobile Drawer -->
    <div 
      v-if="mobileOpen" 
      class="lg:hidden bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 px-5 pt-3 pb-6 space-y-2 max-h-[85vh] overflow-y-auto"
    >
      <router-link 
        v-for="link in navLinks" 
        :key="link.path" 
        :to="link.path"
        @click="mobileOpen = false"
        class="block px-3.5 py-2.5 rounded-xl text-xs font-medium text-slate-700 dark:text-slate-200 hover:bg-blue-50 dark:hover:bg-slate-800 transition-colors"
        active-class="!text-blue-900 dark:!text-blue-400 !font-bold !bg-blue-50/70 dark:!bg-slate-800"
      >
        {{ t(link.key) }}
      </router-link>

      <div class="pt-3 border-t dark:border-slate-800 flex flex-col gap-2">
        <router-link 
          to="/volunteer" 
          @click="mobileOpen = false"
          class="w-full py-2.5 rounded-xl text-xs font-bold bg-blue-900 hover:bg-blue-800 text-white text-center shadow-xs"
        >
          {{ t('volunteer') }}
        </router-link>

        <router-link 
          v-if="isLoggedIn" 
          to="/profile" 
          @click="mobileOpen = false"
          class="w-full py-2 rounded-xl text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-white text-center border border-slate-200 dark:border-slate-700"
        >
          {{ userName }} ({{ lang === 'ar' ? 'حسابي' : 'Profile' }})
        </router-link>
        <router-link 
          v-else 
          to="/login" 
          @click="mobileOpen = false"
          class="w-full py-2 rounded-xl text-xs font-semibold bg-slate-100 dark:bg-slate-800 text-slate-800 dark:text-white text-center border border-slate-200 dark:border-slate-700"
        >
          {{ lang === 'ar' ? 'تسجيل الدخول' : 'Sign In' }}
        </router-link>
      </div>
    </div>
  </header>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { useRouter } from 'vue-router';
import { 
  Search, 
  Globe, 
  Sun, 
  Moon, 
  Monitor, 
  User, 
  Calendar, 
  FileText, 
  ShieldCheck, 
  LogOut, 
  Menu, 
  X 
} from 'lucide-vue-next';
import { authService } from '../services/auth';
import { themeService } from '../services/theme';

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

const emit = defineEmits(['toggle-lang', 'open-search', 'notify']);
const router = useRouter();

const mobileOpen = ref(false);
const userMenuOpen = ref(false);
const currentTheme = ref(themeService.getTheme());

const isLoggedIn = computed(() => authService.isLoggedIn());
const userName = computed(() => authService.getUser()?.name || 'User');
const userInitial = computed(() => userName.value.charAt(0).toUpperCase());
const userRole = computed(() => authService.getRole());
const isAdmin = computed(() => userRole.value !== 'registered_user' && userRole.value !== 'visitor');

// Previous direct nav links
const navLinks = [
  { path: '/', key: 'home' },
  { path: '/about', key: 'about' },
  { path: '/team', key: 'ourTeam' },
  { path: '/news', key: 'news' },
  { path: '/events', key: 'events' },
  { path: '/articles', key: 'articles' },
  { path: '/magazine', key: 'magazine' },
  { path: '/contact', key: 'contact' },
];

function toggleLang() {
  emit('toggle-lang');
}

function cycleTheme() {
  const next = themeService.toggle();
  currentTheme.value = next;
  emit('notify', `Theme: ${next}`, 'info');
}

function logout() {
  authService.logout();
  userMenuOpen.value = false;
  emit('notify', props.lang === 'ar' ? 'تم تسجيل الخروج بنجاح.' : 'Logged out successfully.', 'info');
  router.push('/login');
}

// Close dropdown on outside click
function handleDocClick(e) {
  if (!e.target.closest('.user-dropdown-container')) {
    userMenuOpen.value = false;
  }
}

// Close menu on route changes
router.afterEach(() => {
  mobileOpen.value = false;
  userMenuOpen.value = false;
});

onMounted(() => {
  document.addEventListener('click', handleDocClick);
});

onUnmounted(() => {
  document.removeEventListener('click', handleDocClick);
});
</script>
