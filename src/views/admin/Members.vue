<template>
  <div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div>
        <h1 class="text-2xl font-black text-slate-900">{{ t('members') }}</h1>
        <p class="text-xs text-slate-500 mt-1">
          {{ lang === 'ar' ? 'سجل الأعضاء المسجلين والفرق واللجان الطلابية التابعة للفرع' : 'Manage registered IEEE student members, teams, positions, and active records.' }}
        </p>
      </div>

      <div class="flex items-center gap-2">
        <button 
          @click="showAddModal = true"
          class="px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white text-xs font-semibold rounded-xl shadow-xs transition-all flex items-center gap-1.5"
        >
          <UserPlus class="w-4 h-4" />
          <span>{{ lang === 'ar' ? 'إضافة عضو' : 'Add Member' }}</span>
        </button>

        <button 
          @click="handleExport"
          class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold rounded-xl shadow-xs transition-all flex items-center gap-1.5"
        >
          <FileSpreadsheet class="w-4 h-4" />
          <span>{{ t('exportExcel') }}</span>
        </button>
      </div>
    </div>

    <!-- Filter Bar (Matching Screen 16) -->
    <div class="bg-white rounded-2xl p-4 border border-slate-200/80 shadow-xs flex flex-wrap items-center gap-3">
      <!-- Team Filter -->
      <select 
        v-model="teamFilter" 
        class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:ring-2 focus:ring-blue-600"
      >
        <option value="All">All Teams</option>
        <option value="Media">Media</option>
        <option value="Events">Events</option>
        <option value="Tech">Tech</option>
        <option value="Design">Design</option>
        <option value="PR">PR</option>
      </select>

      <!-- Year Filter -->
      <select 
        v-model="yearFilter" 
        class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:outline-none focus:ring-2 focus:ring-blue-600"
      >
        <option value="All">All Years</option>
        <option value="1st">1st Year</option>
        <option value="2nd">2nd Year</option>
        <option value="3rd">3rd Year</option>
        <option value="4th">4th Year</option>
        <option value="5th">5th Year</option>
      </select>

      <!-- Search Input -->
      <div class="relative flex-1 min-w-[200px]">
        <input 
          v-model="searchQuery" 
          type="text" 
          placeholder="Search member name or email..." 
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

    <!-- Members Table (Matching Screen 16) -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-left text-xs text-slate-600">
          <thead class="bg-slate-50 text-slate-400 font-semibold uppercase tracking-wider border-b border-slate-100">
            <tr>
              <th class="py-3.5 px-6">ID</th>
              <th class="py-3.5 px-6">Name</th>
              <th class="py-3.5 px-6">Email</th>
              <th class="py-3.5 px-6">Team</th>
              <th class="py-3.5 px-6">Year</th>
              <th class="py-3.5 px-6">Status</th>
              <th class="py-3.5 px-6 text-right">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100 font-medium">
            <tr 
              v-for="member in paginatedMembers" 
              :key="member.id"
              class="hover:bg-slate-50 transition-colors"
            >
              <td class="py-3.5 px-6 font-bold text-slate-900 whitespace-nowrap">{{ member.id }}</td>
              <td class="py-3.5 px-6 font-semibold text-slate-800 whitespace-nowrap">{{ member.name }}</td>
              <td class="py-3.5 px-6 text-slate-500 whitespace-nowrap">{{ member.email }}</td>
              <td class="py-3.5 px-6 whitespace-nowrap">
                <span class="px-2 py-0.5 rounded text-[11px] font-bold bg-blue-50 text-blue-900 border border-blue-100">
                  {{ member.team }}
                </span>
              </td>
              <td class="py-3.5 px-6 text-slate-700 whitespace-nowrap">{{ member.year }}</td>
              <td class="py-3.5 px-6 whitespace-nowrap">
                <span class="px-2.5 py-1 rounded-md text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                  {{ member.status }}
                </span>
              </td>
              <td class="py-3.5 px-6 text-right whitespace-nowrap space-x-1">
                <button 
                  @click="editMember(member)"
                  class="p-1.5 text-slate-500 hover:text-blue-900 hover:bg-blue-50 rounded-lg transition-colors" 
                  title="Edit Member"
                >
                  <Edit2 class="w-4 h-4" />
                </button>
                <button 
                  @click="deleteMember(member.id)"
                  class="p-1.5 text-rose-500 hover:bg-rose-50 rounded-lg transition-colors" 
                  title="Archive Member"
                >
                  <Trash2 class="w-4 h-4" />
                </button>
              </td>
            </tr>

            <tr v-if="filteredMembers.length === 0">
              <td colspan="7" class="py-8 text-center text-slate-400">
                No members found matching the selected criteria.
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination (Matching Screen 16) -->
      <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
        <div>
          Showing {{ filteredMembers.length > 0 ? (currentPage - 1) * pageSize + 1 : 0 }} to {{ Math.min(currentPage * pageSize, filteredMembers.length) }} of {{ filteredMembers.length }} entries
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

    <!-- Add / Edit Member Modal -->
    <div v-if="showAddModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
      <div class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 space-y-4 shadow-2xl">
        <div class="flex items-center justify-between border-b pb-3">
          <h3 class="font-bold text-slate-900 text-lg">
            {{ editId ? 'Edit Member Record' : 'Add New Member' }}
          </h3>
          <button @click="showAddModal = false; editId = null" class="text-slate-400 hover:text-slate-600"><X class="w-5 h-5" /></button>
        </div>
        <form @submit.prevent="saveMember" class="space-y-3 text-xs">
          <div>
            <label class="block font-bold text-slate-700 uppercase mb-1">Full Name *</label>
            <input v-model="memberForm.name" required type="text" class="w-full px-3 py-2 border rounded-xl" />
          </div>
          <div>
            <label class="block font-bold text-slate-700 uppercase mb-1">Email *</label>
            <input v-model="memberForm.email" required type="email" class="w-full px-3 py-2 border rounded-xl" />
          </div>
          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="block font-bold text-slate-700 uppercase mb-1">Team *</label>
              <select v-model="memberForm.team" class="w-full px-3 py-2 border rounded-xl">
                <option value="Media">Media</option>
                <option value="Events">Events</option>
                <option value="Tech">Tech</option>
                <option value="Design">Design</option>
                <option value="PR">PR</option>
              </select>
            </div>
            <div>
              <label class="block font-bold text-slate-700 uppercase mb-1">Academic Year *</label>
              <select v-model="memberForm.year" class="w-full px-3 py-2 border rounded-xl">
                <option value="1st">1st</option>
                <option value="2nd">2nd</option>
                <option value="3rd">3rd</option>
                <option value="4th">4th</option>
                <option value="5th">5th</option>
              </select>
            </div>
          </div>
          <div>
            <label class="block font-bold text-slate-700 uppercase mb-1">Role / Position</label>
            <input v-model="memberForm.role" type="text" placeholder="e.g. Committee Member or Lead" class="w-full px-3 py-2 border rounded-xl" />
          </div>
          <div class="pt-3 border-t flex justify-end gap-2">
            <button type="button" @click="showAddModal = false" class="px-4 py-2 border rounded-xl text-slate-600">Cancel</button>
            <button type="submit" class="px-5 py-2 bg-blue-900 text-white font-bold rounded-xl hover:bg-blue-800">
              {{ editId ? 'Update Record' : 'Save Member' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { 
  FileSpreadsheet, 
  Search, 
  UserPlus, 
  Edit2, 
  Trash2, 
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

const members = ref(storageService.getMembers());
const teamFilter = ref('All');
const yearFilter = ref('All');
const searchQuery = ref('');
const showAddModal = ref(false);
const editId = ref(null);

const memberForm = ref({
  name: '',
  email: '',
  team: 'Media',
  year: '2nd',
  role: 'Member'
});

const currentPage = ref(1);
const pageSize = 5;

const filteredMembers = computed(() => {
  return members.value.filter(m => {
    const matchesTeam = teamFilter.value === 'All' || m.team === teamFilter.value;
    const matchesYear = yearFilter.value === 'All' || m.year === yearFilter.value;
    const q = searchQuery.value.toLowerCase();
    const matchesSearch = m.name.toLowerCase().includes(q) ||
                          m.email.toLowerCase().includes(q) ||
                          m.id.toLowerCase().includes(q);
    return matchesTeam && matchesYear && matchesSearch;
  });
});

const totalPages = computed(() => Math.max(1, Math.ceil(filteredMembers.value.length / pageSize)));

const paginatedMembers = computed(() => {
  const start = (currentPage.value - 1) * pageSize;
  return filteredMembers.value.slice(start, start + pageSize);
});

function resetFilters() {
  teamFilter.value = 'All';
  yearFilter.value = 'All';
  searchQuery.value = '';
  currentPage.value = 1;
}

function editMember(m) {
  editId.value = m.id;
  memberForm.value = {
    name: m.name,
    email: m.email,
    team: m.team,
    year: m.year,
    role: m.role || 'Member'
  };
  showAddModal.value = true;
}

function saveMember() {
  if (editId.value) {
    const mem = members.value.find(m => m.id === editId.value);
    if (mem) {
      mem.name = memberForm.value.name;
      mem.email = memberForm.value.email;
      mem.team = memberForm.value.team;
      mem.year = memberForm.value.year;
      mem.role = memberForm.value.role;
      storageService.saveMembers(members.value);
      emit('notify', `Member ${mem.id} record updated!`, 'success');
    }
  } else {
    storageService.addMember(memberForm.value);
    members.value = storageService.getMembers();
    emit('notify', 'New member enrolled successfully!', 'success');
  }
  showAddModal.value = false;
  editId.value = null;
  memberForm.value = { name: '', email: '', team: 'Media', year: '2nd', role: 'Member' };
}

function deleteMember(id) {
  if (confirm(`Archive member record ${id}?`)) {
    members.value = members.value.filter(m => m.id !== id);
    storageService.saveMembers(members.value);
    emit('notify', `Member ${id} archived.`, 'info');
  }
}

function handleExport() {
  const exportData = filteredMembers.value.map(m => ({
    'Member ID': m.id,
    'Name': m.name,
    'Email': m.email,
    'Team': m.team,
    'Academic Year': m.year,
    'Role': m.role || 'Member',
    'Status': m.status,
    'Joined Date': m.joinDate || '2024-10-01'
  }));

  const ok = exportToExcel(exportData, 'IEEE_AAU_Members', 'Members');
  if (ok) {
    emit('notify', 'Members exported to Excel successfully!', 'success');
  }
}
</script>
