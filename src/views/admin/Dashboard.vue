<template>
  <div class="space-y-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div>
        <h1 class="text-2xl font-black text-slate-900">{{ t('dashboard') }}</h1>
        <p class="text-xs text-slate-500 mt-1">
          {{ lang === 'ar' ? 'نظرة عامة على الإحصائيات، نمو العضوية، وأحدث العمليات' : 'Overview of branch membership, applications pipeline, and recent operations.' }}
        </p>
      </div>

      <div class="flex items-center gap-3">
        <router-link 
          to="/admin/reports"
          class="px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white text-xs font-semibold rounded-xl shadow transition-all flex items-center gap-2"
        >
          <FileSpreadsheet class="w-4 h-4" />
          <span>{{ t('exportExcel') }}</span>
        </router-link>
      </div>
    </div>

    <!-- Metric Stat Cards (Matching Screen 14) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
      <!-- Total Applications -->
      <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs space-y-2">
        <div class="flex items-center justify-between">
          <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ t('totalApplications') }}</span>
          <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-900 flex items-center justify-center">
            <FileText class="w-4 h-4" />
          </div>
        </div>
        <div class="flex items-baseline gap-2">
          <span class="text-3xl font-black text-slate-900">{{ applicationsCount }}</span>
          <span class="text-xs font-bold text-emerald-600 flex items-center gap-0.5">
            <ArrowUpRight class="w-3.5 h-3.5" /> +12%
          </span>
        </div>
      </div>

      <!-- Total Members -->
      <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs space-y-2">
        <div class="flex items-center justify-between">
          <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ t('totalMembers') }}</span>
          <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-900 flex items-center justify-center">
            <Users class="w-4 h-4" />
          </div>
        </div>
        <div class="flex items-baseline gap-2">
          <span class="text-3xl font-black text-slate-900">{{ membersCount }}</span>
          <span class="text-xs font-bold text-emerald-600 flex items-center gap-0.5">
            <ArrowUpRight class="w-3.5 h-3.5" /> +6%
          </span>
        </div>
      </div>

      <!-- Active Volunteers -->
      <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs space-y-2">
        <div class="flex items-center justify-between">
          <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ t('activeVolunteers') }}</span>
          <div class="w-8 h-8 rounded-lg bg-purple-50 text-purple-900 flex items-center justify-center">
            <HeartHandshake class="w-4 h-4" />
          </div>
        </div>
        <div class="flex items-baseline gap-2">
          <span class="text-3xl font-black text-slate-900">{{ volunteersCount }}</span>
          <span class="text-xs font-bold text-emerald-600 flex items-center gap-0.5">
            <ArrowUpRight class="w-3.5 h-3.5" /> +15%
          </span>
        </div>
      </div>

      <!-- Upcoming Events -->
      <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs space-y-2">
        <div class="flex items-center justify-between">
          <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ t('upcomingEventsCount') }}</span>
          <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-900 flex items-center justify-center">
            <Calendar class="w-4 h-4" />
          </div>
        </div>
        <div class="flex items-baseline gap-2">
          <span class="text-3xl font-black text-slate-900">{{ eventsCount }}</span>
          <span class="text-xs font-bold text-emerald-600 flex items-center gap-0.5">
            <ArrowUpRight class="w-3.5 h-3.5" /> +20%
          </span>
        </div>
      </div>
    </div>

    <!-- Charts Row (Matching Screen 14) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- Membership Growth Line Chart (2 Cols) -->
      <div class="lg:col-span-2 bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs space-y-4">
        <div class="flex items-center justify-between">
          <div>
            <h3 class="font-bold text-slate-900 text-sm">{{ t('membershipGrowth') }}</h3>
            <span class="text-[11px] text-slate-400">Monthly registered branch members</span>
          </div>
          <span class="text-xs font-bold text-blue-900 bg-blue-50 px-2.5 py-1 rounded-md">2025</span>
        </div>
        <div class="h-64 relative flex items-center justify-center">
          <canvas ref="growthChartRef"></canvas>
        </div>
      </div>

      <!-- Applications by Status Doughnut Chart (1 Col) -->
      <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs space-y-4">
        <div>
          <h3 class="font-bold text-slate-900 text-sm">{{ t('applicationsByStatus') }}</h3>
          <span class="text-[11px] text-slate-400">Distribution of review pipeline</span>
        </div>
        <div class="h-64 relative flex items-center justify-center">
          <canvas ref="statusChartRef"></canvas>
        </div>
      </div>
    </div>

    <!-- Recent Activities Table (Matching Screen 14) -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
      <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
        <h3 class="font-bold text-slate-900 text-sm">{{ t('recentActivities') }}</h3>
        <span class="text-xs text-slate-400">{{ activities.length }} records</span>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full text-left text-xs text-slate-600">
          <thead class="bg-slate-50 text-slate-400 font-semibold uppercase tracking-wider border-b border-slate-100">
            <tr>
              <th class="py-3 px-6">Date</th>
              <th class="py-3 px-6">Action</th>
              <th class="py-3 px-6">User</th>
              <th class="py-3 px-6">Details</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100 font-medium">
            <tr v-for="(act, idx) in activities.slice(0, 6)" :key="idx" class="hover:bg-slate-50 transition-colors">
              <td class="py-3 px-6 text-slate-400 whitespace-nowrap">{{ act.date }}</td>
              <td class="py-3 px-6 font-bold text-slate-900 whitespace-nowrap">{{ act.action }}</td>
              <td class="py-3 px-6 text-blue-900 whitespace-nowrap">{{ act.user }}</td>
              <td class="py-3 px-6 text-slate-600">{{ act.details }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import { 
  FileText, 
  Users, 
  HeartHandshake, 
  Calendar, 
  ArrowUpRight, 
  FileSpreadsheet 
} from 'lucide-vue-next';
import Chart from 'chart.js/auto';
import { storageService } from '../../services/storage';

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

const growthChartRef = ref(null);
const statusChartRef = ref(null);

const apps = storageService.getApplications();
const members = storageService.getMembers();
const volunteers = storageService.getVolunteers();
const events = storageService.getEvents();
const activities = storageService.getActivities();

const applicationsCount = computed(() => 240 + apps.length);
const membersCount = computed(() => 175 + members.length);
const volunteersCount = computed(() => 60 + volunteers.length);
const eventsCount = computed(() => events.length);

onMounted(() => {
  // Line Chart: Membership Growth (Matching Screen 14)
  if (growthChartRef.value) {
    new Chart(growthChartRef.value, {
      type: 'line',
      data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
        datasets: [{
          label: 'Members',
          data: [65, 80, 110, 125, 145, 160, 180],
          borderColor: '#00629B',
          backgroundColor: 'rgba(0, 98, 155, 0.08)',
          tension: 0.35,
          fill: true,
          pointBackgroundColor: '#002855',
          pointRadius: 4,
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: false }
        },
        scales: {
          y: {
            grid: { color: '#f1f5f9' },
            ticks: { font: { size: 10 } }
          },
          x: {
            grid: { display: false },
            ticks: { font: { size: 10 } }
          }
        }
      }
    });
  }

  // Doughnut Chart: Applications by Status (Matching Screen 14: Pending 45, Accepted 120, Rejected 20)
  if (statusChartRef.value) {
    new Chart(statusChartRef.value, {
      type: 'doughnut',
      data: {
        labels: ['Pending', 'Accepted', 'Rejected'],
        datasets: [{
          data: [45, 120, 20],
          backgroundColor: ['#F59E0B', '#10B981', '#EF4444'],
          borderWidth: 2,
          borderColor: '#ffffff',
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            position: 'bottom',
            labels: {
              boxWidth: 10,
              font: { size: 11 }
            }
          }
        },
        cutout: '70%'
      }
    });
  }
});
</script>
