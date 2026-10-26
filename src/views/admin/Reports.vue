<template>
  <div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div>
        <h1 class="text-2xl font-black text-slate-900">{{ t('reports') }} &amp; {{ t('exportExcel') }}</h1>
        <p class="text-xs text-slate-500 mt-1">
          {{ lang === 'ar' ? 'توليد تقارير إدارية مفصلة وتصدير ملفات Excel وZIP لجميع البيانات' : 'Generate structured administrative summaries, Excel workbooks, and bundled archives.' }}
        </p>
      </div>

      <div>
        <button 
          @click="handleDownloadAllZip"
          class="px-5 py-2.5 bg-blue-900 hover:bg-blue-800 text-white text-xs font-bold rounded-xl shadow-md transition-all flex items-center gap-2"
        >
          <FolderArchive class="w-4 h-4" />
          <span>{{ t('downloadAllZip') }}</span>
        </button>
      </div>
    </div>

    <!-- Generator Filter Bar (Matching Screen 18) -->
    <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs flex flex-wrap items-center gap-4">
      <div class="flex-1 min-w-[200px]">
        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">{{ t('reportType') }}</label>
        <select 
          v-model="selectedReportType" 
          class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-semibold focus:outline-none focus:ring-2 focus:ring-blue-600"
        >
          <option value="Members">Members Report</option>
          <option value="Applications">Applications Report</option>
          <option value="Events">Events Report</option>
          <option value="Volunteers">Volunteers Report</option>
        </select>
      </div>

      <div class="w-40">
        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">From Date</label>
        <input 
          v-model="fromDate" 
          type="date" 
          class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-600" 
        />
      </div>

      <div class="w-40">
        <label class="block text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">To Date</label>
        <input 
          v-model="toDate" 
          type="date" 
          class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs focus:outline-none focus:ring-2 focus:ring-blue-600" 
        />
      </div>

      <div class="pt-5">
        <button 
          @click="generateSelectedReport"
          class="px-5 py-2.5 bg-blue-900 hover:bg-blue-800 text-white font-bold rounded-xl text-xs shadow-xs transition-colors flex items-center gap-1.5"
        >
          <RefreshCw class="w-3.5 h-3.5" />
          <span>{{ t('generateReport') }}</span>
        </button>
      </div>
    </div>

    <!-- Reports Table (Matching Screen 18) -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-left text-xs text-slate-600">
          <thead class="bg-slate-50 text-slate-400 font-semibold uppercase tracking-wider border-b border-slate-100">
            <tr>
              <th class="py-3.5 px-6">Report Type</th>
              <th class="py-3.5 px-6">Format</th>
              <th class="py-3.5 px-6">Last Generated</th>
              <th class="py-3.5 px-6 text-right">Download</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100 font-medium">
            <tr 
              v-for="rep in reportList" 
              :key="rep.id"
              class="hover:bg-slate-50 transition-colors"
            >
              <td class="py-3.5 px-6 font-bold text-slate-900 whitespace-nowrap flex items-center gap-2">
                <FileSpreadsheet class="w-4 h-4 text-emerald-600" />
                <span>{{ rep.title }}</span>
              </td>
              <td class="py-3.5 px-6 whitespace-nowrap">
                <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-emerald-50 text-emerald-800 border border-emerald-200">
                  {{ rep.format }}
                </span>
              </td>
              <td class="py-3.5 px-6 text-slate-500 whitespace-nowrap">{{ rep.lastGenerated }}</td>
              <td class="py-3.5 px-6 text-right whitespace-nowrap">
                <button 
                  @click="downloadReport(rep.id)"
                  class="p-2 text-blue-900 hover:bg-blue-50 rounded-xl transition-colors" 
                  title="Download File"
                >
                  <Download class="w-4 h-4" />
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Bottom Download All (ZIP) bar (Matching Screen 18) -->
      <div class="p-6 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
        <div class="text-xs text-slate-500">
          Click button to compress all Excel tables and logs into an authenticated ZIP bundle.
        </div>
        <button 
          @click="handleDownloadAllZip"
          class="px-6 py-2.5 bg-blue-900 hover:bg-blue-800 text-white font-bold rounded-xl text-xs shadow-md transition-all flex items-center gap-2"
        >
          <FolderArchive class="w-4 h-4" />
          <span>{{ t('downloadAllZip') }}</span>
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { 
  FileSpreadsheet, 
  FolderArchive, 
  Download, 
  RefreshCw 
} from 'lucide-vue-next';
import { storageService } from '../../services/storage';
import { exportToExcel } from '../../services/excelExport';
import { downloadAllReportsZip } from '../../services/zipExport';

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

const emit = defineEmits(['notify']);

const selectedReportType = ref('Members');
const fromDate = ref('2025-01-01');
const toDate = ref('2025-05-31');

const reportList = ref([
  { id: 'members', title: 'Members Report', format: 'Excel', lastGenerated: 'Apr 10, 2025' },
  { id: 'applications', title: 'Applications Report', format: 'Excel', lastGenerated: 'Apr 8, 2025' },
  { id: 'events', title: 'Events Report', format: 'Excel', lastGenerated: 'May 2, 2025' },
  { id: 'volunteers', title: 'Volunteers Report', format: 'Excel', lastGenerated: 'Apr 1, 2025' },
]);

function generateSelectedReport() {
  const target = reportList.value.find(r => r.id === selectedReportType.value.toLowerCase());
  if (target) {
    target.lastGenerated = new Date().toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
  }
  downloadReport(selectedReportType.value.toLowerCase());
  emit('notify', `${selectedReportType.value} Report generated and ready!`, 'success');
}

function downloadReport(id) {
  if (id === 'members') {
    const data = storageService.getMembers().map(m => ({
      'Member ID': m.id, 'Full Name': m.name, 'Email': m.email, 'Team': m.team, 'Year': m.year, 'Status': m.status
    }));
    exportToExcel(data, 'IEEE_AAU_Members_Report', 'Members');
  } else if (id === 'applications') {
    const data = storageService.getApplications().map(a => ({
      'Application ID': a.id, 'Name': a.name, 'Email': a.email, 'Major': a.major, 'Status': a.status, 'Applied On': a.appliedOn
    }));
    exportToExcel(data, 'IEEE_AAU_Applications_Report', 'Applications');
  } else if (id === 'events') {
    const data = storageService.getEvents().map(e => ({
      'Event ID': e.id, 'Title': e.title, 'Date': e.date, 'Time': e.time, 'Location': e.location, 'Status': e.status
    }));
    exportToExcel(data, 'IEEE_AAU_Events_Report', 'Events');
  } else if (id === 'volunteers') {
    const data = storageService.getVolunteers().map(v => ({
      'ID': v.id, 'Name': v.name, 'Email': v.email, 'Team': v.team, 'Activities': (v.activities || []).join(', '), 'Status': v.status
    }));
    exportToExcel(data, 'IEEE_AAU_Volunteers_Report', 'Volunteers');
  }
}

async function handleDownloadAllZip() {
  emit('notify', 'Bundling all reports into ZIP...', 'info');
  await downloadAllReportsZip();
  emit('notify', 'ZIP bundle downloaded successfully!', 'success');
}
</script>
