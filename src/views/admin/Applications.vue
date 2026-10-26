<template>
  <div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div>
        <h1 class="text-2xl font-black text-slate-900">{{ t('applications') }}</h1>
        <p class="text-xs text-slate-500 mt-1">
          {{ lang === 'ar' ? 'إدارة ومراجعة طلبات الانضمام للفرع الطلابي وتغيير حالاتها' : 'Review membership applications, update screening status, and export applicant data.' }}
        </p>
      </div>

      <div class="flex items-center gap-2">
        <button 
          @click="handleExport"
          class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-xl shadow-xs transition-all flex items-center gap-1.5"
        >
          <FileSpreadsheet class="w-4 h-4" />
          <span>{{ t('exportExcel') }}</span>
        </button>
      </div>
    </div>

    <!-- Filter Bar (Matching Screen 15) -->
    <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-xs flex flex-wrap items-center gap-3">
      <!-- Status Filter -->
      <select 
        v-model="statusFilter" 
        class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:ring-2 focus:ring-blue-600"
      >
        <option value="All">All Status</option>
        <option value="Pending">Pending</option>
        <option value="Accepted">Accepted</option>
        <option value="Rejected">Rejected</option>
      </select>

      <!-- Major Filter -->
      <select 
        v-model="majorFilter" 
        class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:ring-2 focus:ring-blue-600"
      >
        <option value="All">All Majors</option>
        <option value="CS">Computer Science (CS)</option>
        <option value="EE">Electrical Engineering (EE)</option>
        <option value="IT">Information Technology (IT)</option>
      </select>

      <!-- Search Input -->
      <div class="relative flex-1 min-w-[200px]">
        <input 
          v-model="searchQuery" 
          type="text" 
          placeholder="Search name, email, ID..." 
          class="w-full pl-8 pr-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-600"
        />
        <Search class="w-3.5 h-3.5 text-slate-400 absolute left-2.5 top-2.5" />
      </div>

      <button 
        @click="resetFilters" 
        class="px-3 py-2 text-xs text-slate-500 hover:text-slate-800 hover:bg-slate-100 rounded-xl"
      >
        Reset
      </button>
    </div>

    <!-- Applications Table (Matching Screen 15) -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-left text-xs text-slate-600">
          <thead class="bg-slate-50 text-slate-400 font-semibold uppercase tracking-wider border-b border-slate-100">
            <tr>
              <th class="py-3.5 px-6">ID</th>
              <th class="py-3.5 px-6">Name</th>
              <th class="py-3.5 px-6">Email</th>
              <th class="py-3.5 px-6">Major</th>
              <th class="py-3.5 px-6">Status</th>
              <th class="py-3.5 px-6">Applied On</th>
              <th class="py-3.5 px-6 text-right">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100 font-medium">
            <tr 
              v-for="app in paginatedApplications" 
              :key="app.id"
              class="hover:bg-slate-50 transition-colors"
            >
              <td class="py-3.5 px-6 font-bold text-slate-900 whitespace-nowrap">{{ app.id }}</td>
              <td class="py-3.5 px-6 font-semibold text-slate-800 whitespace-nowrap">{{ app.name }}</td>
              <td class="py-3.5 px-6 text-slate-500 whitespace-nowrap">{{ app.email }}</td>
              <td class="py-3.5 px-6 text-slate-700 whitespace-nowrap">{{ app.major }}</td>
              <td class="py-3.5 px-6 whitespace-nowrap">
                <span 
                  class="px-2.5 py-1 rounded-md text-[11px] font-bold"
                  :class="{
                    'bg-amber-50 text-amber-700 border border-amber-200': app.status === 'Pending',
                    'bg-emerald-50 text-emerald-700 border border-emerald-200': app.status === 'Accepted',
                    'bg-rose-50 text-rose-700 border border-rose-200': app.status === 'Rejected'
                  }"
                >
                  {{ app.status }}
                </span>
              </td>
              <td class="py-3.5 px-6 text-slate-400 whitespace-nowrap">{{ app.appliedOn }}</td>
              <td class="py-3.5 px-6 text-right whitespace-nowrap space-x-1">
                <button 
                  @click="selectedApp = app"
                  class="p-1.5 text-slate-500 hover:text-blue-900 hover:bg-blue-50 rounded-lg transition-colors" 
                  title="View Details"
                >
                  <Eye class="w-4 h-4" />
                </button>
                <button 
                  @click="quickChangeStatus(app, 'Accepted')"
                  class="p-1.5 text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors" 
                  title="Accept Application"
                >
                  <CheckCircle class="w-4 h-4" />
                </button>
                <button 
                  @click="quickChangeStatus(app, 'Rejected')"
                  class="p-1.5 text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" 
                  title="Reject Application"
                >
                  <XCircle class="w-4 h-4" />
                </button>
              </td>
            </tr>

            <tr v-if="filteredApplications.length === 0">
              <td colspan="7" class="py-8 text-center text-slate-400">
                No applications found matching the selected filters.
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination (Matching Screen 15) -->
      <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
        <div>
          Showing {{ filteredApplications.length > 0 ? (currentPage - 1) * pageSize + 1 : 0 }} to {{ Math.min(currentPage * pageSize, filteredApplications.length) }} of {{ filteredApplications.length }} entries
        </div>
        <div class="flex items-center gap-1">
          <button 
            @click="currentPage > 1 && currentPage--" 
            :disabled="currentPage === 1"
            class="p-1.5 rounded-lg border border-slate-200 hover:bg-slate-50 disabled:opacity-40"
          >
            <ChevronLeft class="w-3.5 h-3.5" />
          </button>
          <button 
            v-for="p in totalPages" 
            :key="p"
            @click="currentPage = p"
            class="w-7 h-7 rounded-lg text-xs font-bold transition-colors"
            :class="currentPage === p ? 'bg-blue-900 text-white' : 'hover:bg-slate-100 text-slate-700'"
          >
            {{ p }}
          </button>
          <button 
            @click="currentPage < totalPages && currentPage++" 
            :disabled="currentPage === totalPages"
            class="p-1.5 rounded-lg border border-slate-200 hover:bg-slate-50 disabled:opacity-40"
          >
            <ChevronRight class="w-3.5 h-3.5" />
          </button>
        </div>
      </div>
    </div>

    <!-- Application Detail Modal -->
    <div v-if="selectedApp" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
      <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 space-y-5 shadow-2xl">
        <div class="flex items-center justify-between border-b pb-3">
          <div>
            <span class="text-xs font-bold text-slate-400">Application</span>
            <h3 class="text-xl font-black text-slate-900">{{ selectedApp.id }} — {{ selectedApp.name }}</h3>
          </div>
          <button @click="selectedApp = null" class="text-slate-400 hover:text-slate-600"><X class="w-5 h-5" /></button>
        </div>

        <div class="grid grid-cols-2 gap-4 text-xs">
          <div>
            <span class="text-slate-400 block">Email Address</span>
            <strong class="text-slate-800 text-sm">{{ selectedApp.email }}</strong>
          </div>
          <div>
            <span class="text-slate-400 block">Phone</span>
            <strong class="text-slate-800 text-sm" dir="ltr">{{ selectedApp.phone || 'N/A' }}</strong>
          </div>
          <div>
            <span class="text-slate-400 block">College & Major</span>
            <strong class="text-slate-800">{{ selectedApp.college || 'Engineering' }} ({{ selectedApp.major }})</strong>
          </div>
          <div>
            <span class="text-slate-400 block">Academic Year</span>
            <strong class="text-slate-800">{{ selectedApp.year }} Year</strong>
          </div>
          <div>
            <span class="text-slate-400 block">Terms Accepted</span>
            <strong class="text-slate-800">{{ selectedApp.termsVersion || 'v2.0' }} ({{ selectedApp.termsAcceptedAt || selectedApp.appliedOn }})</strong>
          </div>
          <div>
            <span class="text-slate-400 block">Current Status</span>
            <span class="inline-block mt-0.5 px-2.5 py-0.5 rounded font-bold text-xs" :class="{
              'bg-amber-100 text-amber-800': selectedApp.status === 'Pending',
              'bg-emerald-100 text-emerald-800': selectedApp.status === 'Accepted',
              'bg-rose-100 text-rose-800': selectedApp.status === 'Rejected'
            }">{{ selectedApp.status }}</span>
          </div>
        </div>

        <div class="p-3 bg-slate-50 rounded-xl text-xs space-y-1">
          <span class="font-bold text-slate-700">Internal Review Notes:</span>
          <p class="text-slate-600">{{ selectedApp.notes || 'No notes added yet.' }}</p>
        </div>

        <!-- Change Status Action Buttons -->
        <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
          <div class="flex items-center gap-2">
            <button 
              @click="quickChangeStatus(selectedApp, 'Accepted'); selectedApp = null"
              class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs"
            >
              Accept
            </button>
            <button 
              @click="quickChangeStatus(selectedApp, 'Rejected'); selectedApp = null"
              class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-xl text-xs"
            >
              Reject
            </button>
            <button 
              @click="quickChangeStatus(selectedApp, 'Pending'); selectedApp = null"
              class="px-3 py-2 bg-amber-500 hover:bg-amber-600 text-white font-bold rounded-xl text-xs"
            >
              Pending
            </button>
          </div>
          <button @click="selectedApp = null" class="px-4 py-2 bg-slate-100 text-slate-700 text-xs font-semibold rounded-xl">
            Close
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { 
  FileSpreadsheet, 
  Search, 
  Eye, 
  CheckCircle, 
  XCircle, 
  ChevronLeft, 
  ChevronRight, 
  X 
} from 'lucide-vue-next';
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

const applications = ref(storageService.getApplications());
const statusFilter = ref('All');
const majorFilter = ref('All');
const searchQuery = ref('');
const selectedApp = ref(null);

const currentPage = ref(1);
const pageSize = 5;

const filteredApplications = computed(() => {
  return applications.value.filter(app => {
    const matchesStatus = statusFilter.value === 'All' || app.status === statusFilter.value;
    const matchesMajor = majorFilter.value === 'All' || app.major === majorFilter.value;
    const q = searchQuery.value.toLowerCase();
    const matchesSearch = app.name.toLowerCase().includes(q) ||
                          app.email.toLowerCase().includes(q) ||
                          app.id.toLowerCase().includes(q);
    return matchesStatus && matchesMajor && matchesSearch;
  });
});

const totalPages = computed(() => Math.max(1, Math.ceil(filteredApplications.value.length / pageSize)));

const paginatedApplications = computed(() => {
  const start = (currentPage.value - 1) * pageSize;
  return filteredApplications.value.slice(start, start + pageSize);
});

function resetFilters() {
  statusFilter.value = 'All';
  majorFilter.value = 'All';
  searchQuery.value = '';
  currentPage.value = 1;
}

function quickChangeStatus(app, newStatus) {
  storageService.updateApplicationStatus(app.id, newStatus);
  applications.value = storageService.getApplications();
  emit('notify', `Application ${app.id} marked as ${newStatus}`, 'info');
}

function handleExport() {
  const exportData = filteredApplications.value.map(a => ({
    'Application ID': a.id,
    'Name': a.name,
    'Email': a.email,
    'Phone': a.phone || '',
    'Major': a.major,
    'College': a.college || '',
    'Year': a.year,
    'Status': a.status,
    'Applied On': a.appliedOn,
    'Terms Version': a.termsVersion || 'v2.0'
  }));

  const ok = exportToExcel(exportData, 'IEEE_AAU_Applications', 'Applications');
  if (ok) {
    emit('notify', 'Filtered applications exported to Excel successfully!', 'success');
  }
}
</script>
