<template>
  <div class="min-h-screen bg-slate-100 dark:bg-slate-900 flex flex-col lg:flex-row font-sans transition-colors duration-200">
    <!-- Mobile Sidebar Drawer Overlay -->
    <div 
      v-if="mobileSidebarOpen" 
      @click="mobileSidebarOpen = false" 
      class="fixed inset-0 z-40 bg-black/60 lg:hidden backdrop-blur-sm"
    ></div>

    <!-- Admin Sidebar (Matching Screen 14 + Multi-role RBAC Matrix) -->
    <aside 
      class="fixed inset-y-0 left-0 z-50 w-64 bg-[#0B192C] text-white flex flex-col justify-between transition-transform duration-300 lg:static lg:translate-x-0"
      :class="mobileSidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    >
      <div>
        <!-- Sidebar Brand Header -->
        <div class="h-20 px-6 flex items-center justify-between border-b border-slate-800">
          <router-link to="/admin" class="flex items-center gap-3">
            <img 
              src="/assets/images/logo-white.png" 
              alt="IEEE AAU SB" 
              class="h-10 w-auto object-contain"
            />
          </router-link>
          <button @click="mobileSidebarOpen = false" class="lg:hidden text-slate-400 hover:text-white">
            <X class="w-5 h-5" />
          </button>
        </div>

        <!-- Role Badge (CR v0.3.1 Section 5) -->
        <div class="px-5 py-3 border-b border-slate-800/80 bg-slate-900/40">
          <span class="block text-[10px] text-slate-400 uppercase font-semibold">Active Role</span>
          <span class="block text-xs font-bold text-blue-300">{{ roleDisplayName }}</span>
        </div>

        <!-- 🔴 Dynamic Navigation Links filtered by RBAC Matrix (Section 5.2) -->
        <nav class="p-4 space-y-1.5 overflow-y-auto max-h-[calc(100vh-210px)]">
          <router-link 
            v-for="item in visibleNavItems" 
            :key="item.path"
            :to="item.path"
            @click="mobileSidebarOpen = false"
            class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-xs sm:text-sm font-medium transition-all"
            :class="$route.path === item.path 
              ? 'bg-blue-600 text-white font-bold shadow-md shadow-blue-900/50' 
              : 'text-slate-300 hover:bg-slate-800/80 hover:text-white'"
          >
            <component :is="item.icon" class="w-4 h-4 flex-shrink-0" />
            <span>{{ t(item.key) }}</span>
          </router-link>
        </nav>
      </div>

      <!-- Bottom Profile / Public Site Switcher -->
      <div class="p-4 border-t border-slate-800 space-y-2">
        <router-link 
          to="/" 
          class="flex items-center justify-center gap-2 px-3 py-2 text-xs font-semibold rounded-lg bg-slate-800/70 text-slate-300 hover:bg-slate-800 hover:text-white transition-colors"
        >
          <ExternalLink class="w-3.5 h-3.5" />
          <span>{{ lang === 'ar' ? 'الذهاب للموقع العام' : 'Public Website' }}</span>
        </router-link>

        <button 
          @click="logout" 
          class="w-full flex items-center justify-center gap-2 px-3 py-2 text-xs font-semibold rounded-lg text-rose-400 hover:bg-rose-950/40 transition-colors"
        >
          <LogOut class="w-3.5 h-3.5" />
          <span>{{ lang === 'ar' ? 'تسجيل الخروج' : 'Log Out' }}</span>
        </button>
      </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0">
      <!-- Admin Top Header -->
      <header class="h-20 bg-white dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700 px-4 sm:px-8 flex items-center justify-between sticky top-0 z-30 shadow-xs transition-colors">
        <div class="flex items-center gap-3">
          <!-- Mobile Sidebar Toggle -->
          <button 
            @click="mobileSidebarOpen = true" 
            class="lg:hidden p-2 text-slate-600 dark:text-slate-300 hover:text-slate-900 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700"
          >
            <Menu class="w-6 h-6" />
          </button>

          <!-- Top Search -->
          <div class="relative hidden sm:block w-72">
            <input 
              v-model="adminSearch"
              type="text" 
              :placeholder="t('search')" 
              class="w-full pl-9 pr-4 py-2 text-xs bg-slate-50 dark:bg-slate-700 dark:text-white border border-slate-200 dark:border-slate-600 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-600"
            />
            <Search class="w-3.5 h-3.5 text-slate-400 absolute left-3 top-2.5" />
          </div>
        </div>

        <div class="flex items-center gap-3">
          <!-- 🔴 Dark Mode Toggle in Admin Header -->
          <button 
            @click="cycleTheme"
            class="p-2 text-slate-500 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg transition-colors"
            title="Toggle Theme"
          >
            <Sun v-if="currentTheme === 'light'" class="w-4 h-4 text-amber-500" />
            <Moon v-else-if="currentTheme === 'dark'" class="w-4 h-4 text-blue-400" />
            <Monitor v-else class="w-4 h-4 text-slate-400" />
          </button>

          <!-- Language switcher in admin -->
          <button 
            @click="$emit('toggle-lang')"
            class="px-2.5 py-1.5 text-xs font-bold border border-slate-200 dark:border-slate-700 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200"
          >
            {{ lang === 'ar' ? 'English' : 'العربية' }}
          </button>

          <!-- User Profile Dropdown -->
          <div class="flex items-center gap-2 pl-2 border-l border-slate-200 dark:border-slate-700">
            <div class="w-9 h-9 rounded-full bg-blue-900 dark:bg-blue-600 text-white font-bold text-xs flex items-center justify-center">
              {{ userName.slice(0, 2).toUpperCase() }}
            </div>
            <div class="hidden md:block text-left">
              <span class="block text-xs font-bold text-slate-900 dark:text-white">{{ userName }}</span>
              <span class="block text-[10px] text-blue-600 dark:text-blue-400">{{ roleDisplayName }}</span>
            </div>
          </div>
        </div>
      </header>

      <!-- View Page Content -->
      <main class="flex-1 p-4 sm:p-8 overflow-y-auto">
        <router-view 
          :t="t" 
          :lang="lang" 
          :adminSearch="adminSearch" 
          @notify="(msg, type) => $emit('notify', msg, type)" 
        />
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { 
  LayoutDashboard, 
  FileText, 
  Users, 
  HeartHandshake, 
  Calendar, 
  BarChart3, 
  ScrollText, 
  Settings as SettingsIcon, 
  BookOpen,
  Newspaper,
  Award,
  Menu, 
  X, 
  Search, 
  ExternalLink,
  LogOut,
  Sun,
  Moon,
  Monitor
} from 'lucide-vue-next';
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

const emit = defineEmits(['toggle-lang', 'notify']);
const router = useRouter();

const mobileSidebarOpen = ref(false);
const adminSearch = ref('');
const currentTheme = ref(themeService.getTheme());

const userName = computed(() => authService.getUser()?.name || 'Admin');
const userRole = computed(() => authService.getRole());

const roleDisplayName = computed(() => {
  const map = {
    office_member: props.lang === 'ar' ? 'أعضاء المكتب (مدير عام)' : 'Office Member (Super Admin)',
    hr: props.lang === 'ar' ? 'مسؤول الموارد البشرية (HR)' : 'HR Coordinator',
    programs_coordinator: props.lang === 'ar' ? 'منسق البرامج والفعاليات' : 'Programs Coordinator',
    magazine_coordinator: props.lang === 'ar' ? 'منسق المجلة والتحرير' : 'Magazine Coordinator',
    media_coordinator: props.lang === 'ar' ? 'منسق فريق الإعلام' : 'Media Coordinator',
    registered_user: props.lang === 'ar' ? 'عضو / طالب' : 'Registered Student'
  };
  return map[userRole.value] || userRole.value;
});

// All potential admin links
const allNavItems = [
  { path: '/admin', key: 'dashboard', module: 'dashboard', icon: LayoutDashboard },
  { path: '/admin/applications', key: 'applications', module: 'applications', icon: FileText },
  { path: '/admin/members', key: 'members', module: 'members', icon: Users },
  { path: '/admin/volunteers', key: 'volunteers', module: 'volunteers', icon: HeartHandshake },
  { path: '/admin/magazines', key: 'magazineAdmin', module: 'magazine', icon: BookOpen },
  { path: '/admin/media', key: 'mediaAdmin', module: 'news', icon: Newspaper },
  { path: '/admin/office-terms', key: 'officeTermsAdmin', module: 'office_terms', icon: Award },
  { path: '/admin/events', key: 'events', module: 'events', icon: Calendar },
  { path: '/admin/reports', key: 'reports', module: 'reports', icon: BarChart3 },
  { path: '/admin/terms', key: 'termsManagement', module: 'terms', icon: ScrollText },
  { path: '/admin/settings', key: 'settings', module: 'settings', icon: SettingsIcon },
];

// 🔴 Filter nav items according to user permissions (CR v0.3.1 Section 5.2)
const visibleNavItems = computed(() => {
  return allNavItems.filter(item => authService.can(item.module, 'view'));
});

function cycleTheme() {
  const next = themeService.toggle();
  currentTheme.value = next;
  emit('notify', `Theme: ${next}`, 'info');
}

function logout() {
  authService.logout();
  emit('notify', props.lang === 'ar' ? 'تم تسجيل الخروج بنجاح.' : 'Logged out successfully.', 'info');
  router.push('/login');
}

onMounted(() => {
  // If not logged in, redirect to login
  if (!authService.isLoggedIn()) {
    router.push('/login?redirect=/admin');
    return;
  }
  // If regular student without admin role, redirect to public home
  if (userRole.value === 'registered_user') {
    emit('notify', props.lang === 'ar' ? 'تنبيه: حسابك لا يملك صلاحية الدخول للوحة التحكم' : 'Access denied: Student account has no admin permissions.', 'error');
    router.push('/');
  }
});
</script>
