<template>
  <div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div>
        <h1 class="text-2xl font-black text-slate-900 dark:text-white">{{ t('events') }}</h1>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
          {{ lang === 'ar' ? 'جدولة وتحديث الفعاليات بحقول التاريخ والوقت الحقيقية ومزامنة العدّاد التنازلي' : 'Schedule events with genuine Date-Time pickers, seat capacity, and live homepage synchronization.' }}
        </p>
      </div>

      <div>
        <button 
          @click="openCreateModal"
          class="px-4 py-2 bg-blue-900 hover:bg-blue-800 text-white text-xs font-semibold rounded-xl shadow-xs transition-all flex items-center gap-1.5"
        >
          <CalendarPlus class="w-4 h-4" />
          <span>{{ lang === 'ar' ? 'إضافة فعالية جديدة' : 'Add New Event' }}</span>
        </button>
      </div>
    </div>

    <!-- Events Management Table (Matching Screen 17 + Real Datetime) -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/80 dark:border-slate-700 shadow-xs overflow-hidden transition-colors">
      <div class="overflow-x-auto">
        <table class="w-full text-left text-xs text-slate-600 dark:text-slate-300">
          <thead class="bg-slate-50 dark:bg-slate-700/50 text-slate-400 dark:text-slate-400 font-semibold uppercase tracking-wider border-b border-slate-100 dark:border-slate-700">
            <tr>
              <th class="py-3.5 px-6">Title</th>
              <th class="py-3.5 px-6">Starts At</th>
              <th class="py-3.5 px-6">Ends At</th>
              <th class="py-3.5 px-6">Type &amp; Location</th>
              <th class="py-3.5 px-6">Seats</th>
              <th class="py-3.5 px-6">Status</th>
              <th class="py-3.5 px-6 text-right">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100 dark:divide-slate-700 font-medium">
            <tr 
              v-for="ev in events" 
              :key="ev.id"
              class="hover:bg-slate-50 dark:hover:bg-slate-700/40 transition-colors"
            >
              <td class="py-3.5 px-6 whitespace-nowrap">
                <div class="font-bold text-slate-900 dark:text-white">{{ ev.title }}</div>
                <div v-if="ev.is_featured" class="inline-block mt-0.5 px-2 py-0.2 text-[10px] font-bold bg-amber-100 text-amber-800 rounded">
                  ★ Featured on Homepage
                </div>
              </td>
              <td class="py-3.5 px-6 whitespace-nowrap font-mono text-slate-700 dark:text-slate-300">
                {{ formatDateTime(ev.starts_at) }}
              </td>
              <td class="py-3.5 px-6 whitespace-nowrap font-mono text-slate-500 dark:text-slate-400">
                {{ formatDateTime(ev.ends_at) }}
              </td>
              <td class="py-3.5 px-6 whitespace-nowrap">
                <div class="text-slate-800 dark:text-slate-200">{{ ev.location }}</div>
                <span class="text-[10px] text-blue-600 dark:text-blue-400 capitalize">({{ ev.event_type || 'in_person' }})</span>
              </td>
              <td class="py-3.5 px-6 whitespace-nowrap text-slate-700 dark:text-slate-300">
                {{ ev.seats }}
              </td>
              <td class="py-3.5 px-6 whitespace-nowrap">
                <span 
                  class="px-2.5 py-1 rounded-md text-[11px] font-bold border"
                  :class="{
                    'bg-blue-50 text-blue-900 border-blue-200 dark:bg-blue-900/30 dark:text-blue-300': ev.status === 'upcoming',
                    'bg-rose-50 text-rose-700 border-rose-200 animate-pulse': ev.status === 'live',
                    'bg-slate-100 text-slate-600 border-slate-200': ev.status === 'completed'
                  }"
                >
                  {{ ev.status }}
                </span>
              </td>
              <td class="py-3.5 px-6 text-right whitespace-nowrap space-x-1">
                <button 
                  @click="editEvent(ev)"
                  class="p-1.5 text-slate-500 hover:text-blue-900 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-slate-700 rounded-lg transition-colors" 
                  title="Edit Event"
                >
                  <Edit2 class="w-4 h-4" />
                </button>
                <button 
                  @click="deleteEvent(ev.id)"
                  class="p-1.5 text-rose-500 hover:bg-rose-50 dark:hover:bg-rose-900/20 rounded-lg transition-colors" 
                  title="Remove Event"
                >
                  <Trash2 class="w-4 h-4" />
                </button>
              </td>
            </tr>

            <tr v-if="events.length === 0">
              <td colspan="7" class="py-8 text-center text-slate-400">
                No events currently scheduled.
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- 🔴 Add / Edit Event Modal (With Real Date-Time Pickers & Validations) -->
    <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm">
      <div class="bg-white dark:bg-slate-800 rounded-3xl max-w-lg w-full p-6 sm:p-8 space-y-4 shadow-2xl border border-slate-100 dark:border-slate-700 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between border-b dark:border-slate-700 pb-3">
          <div>
            <h3 class="font-bold text-slate-900 dark:text-white text-lg">
              {{ editId ? (lang === 'ar' ? 'تعديل الفعالية' : 'Edit Event') : (lang === 'ar' ? 'إضافة فعالية جديدة' : 'Add New Event') }}
            </h3>
            <span class="text-[11px] text-slate-400">Timezone: Africa/Khartoum</span>
          </div>
          <button @click="showModal = false; editId = null" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
            <X class="w-5 h-5" />
          </button>
        </div>

        <form @submit.prevent="saveEvent" class="space-y-4 text-xs">
          <!-- Title -->
          <div>
            <label class="block font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">
              {{ lang === 'ar' ? 'عنوان الفعالية' : 'Event Title' }} *
            </label>
            <input 
              v-model="eventForm.title" 
              required 
              type="text" 
              placeholder="e.g. AI & Future Technologies Symposium" 
              class="w-full px-3 py-2 border rounded-xl bg-slate-50 dark:bg-slate-700 dark:border-slate-600 dark:text-white"
            />
          </div>

          <!-- 🔴 REAL DATE-TIME PICKERS (Critical Fix 7) -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
              <label class="block font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">
                {{ lang === 'ar' ? 'تاريخ ووقت البداية' : 'Starts At' }} *
              </label>
              <input 
                v-model="eventForm.starts_at" 
                required 
                type="datetime-local" 
                class="w-full px-3 py-2 border rounded-xl bg-slate-50 dark:bg-slate-700 dark:border-slate-600 dark:text-white font-mono"
              />
            </div>
            <div>
              <label class="block font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">
                {{ lang === 'ar' ? 'تاريخ ووقت النهاية' : 'Ends At' }} *
              </label>
              <input 
                v-model="eventForm.ends_at" 
                required 
                type="datetime-local" 
                class="w-full px-3 py-2 border rounded-xl bg-slate-50 dark:bg-slate-700 dark:border-slate-600 dark:text-white font-mono"
              />
            </div>
          </div>

          <!-- Registration Deadline (Optional) -->
          <div>
            <label class="block font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">
              {{ lang === 'ar' ? 'آخر موعد للتسجيل (اختياري)' : 'Registration Deadline' }}
            </label>
            <input 
              v-model="eventForm.registration_deadline" 
              type="datetime-local" 
              class="w-full px-3 py-2 border rounded-xl bg-slate-50 dark:bg-slate-700 dark:border-slate-600 dark:text-white font-mono"
            />
          </div>

          <!-- Event Type & Seats -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
              <label class="block font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">
                {{ lang === 'ar' ? 'نوع الفعالية' : 'Event Type' }}
              </label>
              <select 
                v-model="eventForm.event_type" 
                class="w-full px-3 py-2 border rounded-xl bg-slate-50 dark:bg-slate-700 dark:border-slate-600 dark:text-white"
              >
                <option value="in_person">حضوري (In-Person)</option>
                <option value="online">عن بُعد (Online)</option>
                <option value="hybrid">هجين (Hybrid)</option>
              </select>
            </div>
            <div>
              <label class="block font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">
                {{ lang === 'ar' ? 'عدد المقاعد المتاحة' : 'Max Seats' }} *
              </label>
              <input 
                v-model="eventForm.seats" 
                required 
                type="number" 
                min="5" 
                max="2000" 
                class="w-full px-3 py-2 border rounded-xl bg-slate-50 dark:bg-slate-700 dark:border-slate-600 dark:text-white"
              />
            </div>
          </div>

          <!-- Location & Meeting Link -->
          <div>
            <label class="block font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">
              {{ lang === 'ar' ? 'المكان / القاعة' : 'Location / Venue' }} *
            </label>
            <input 
              v-model="eventForm.location" 
              required 
              type="text" 
              placeholder="e.g. AAU Main Conference Hall" 
              class="w-full px-3 py-2 border rounded-xl bg-slate-50 dark:bg-slate-700 dark:border-slate-600 dark:text-white"
            />
          </div>

          <div v-if="eventForm.event_type === 'online' || eventForm.event_type === 'hybrid'">
            <label class="block font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">
              {{ lang === 'ar' ? 'رابط الاجتماع (Zoom / Google Meet)' : 'Meeting Link' }}
            </label>
            <input 
              v-model="eventForm.meeting_link" 
              type="url" 
              placeholder="https://ieee.zoom.us/j/..." 
              class="w-full px-3 py-2 border rounded-xl bg-slate-50 dark:bg-slate-700 dark:border-slate-600 dark:text-white"
            />
          </div>

          <!-- Speakers -->
          <div>
            <label class="block font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">
              {{ lang === 'ar' ? 'المتحدثون والضيوف' : 'Speakers / Keynotes' }}
            </label>
            <input 
              v-model="eventForm.speakers" 
              type="text" 
              placeholder="e.g. Dr. Al-Amin & Eng. Maryam" 
              class="w-full px-3 py-2 border rounded-xl bg-slate-50 dark:bg-slate-700 dark:border-slate-600 dark:text-white"
            />
          </div>

          <!-- Description -->
          <div>
            <label class="block font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">
              {{ lang === 'ar' ? 'الوصف والتفاصيل' : 'Description' }}
            </label>
            <textarea 
              v-model="eventForm.description" 
              rows="3" 
              class="w-full px-3 py-2 border rounded-xl bg-slate-50 dark:bg-slate-700 dark:border-slate-600 dark:text-white"
            ></textarea>
          </div>

          <!-- Featured on Homepage Toggle -->
          <div class="pt-1">
            <label class="flex items-center gap-2 cursor-pointer text-slate-700 dark:text-slate-300">
              <input 
                type="checkbox" 
                v-model="eventForm.is_featured" 
                class="rounded text-blue-900 focus:ring-blue-600"
              />
              <span class="font-bold">
                {{ lang === 'ar' ? 'تثبيت في الواجهة الرئيسية (Featured Event)' : 'Feature prominently on Homepage' }}
              </span>
            </label>
          </div>

          <!-- Error Message Banner -->
          <div v-if="errorMessage" class="p-3 rounded-xl bg-rose-50 text-rose-800 border border-rose-200">
            {{ errorMessage }}
          </div>

          <!-- Action Buttons -->
          <div class="pt-3 border-t dark:border-slate-700 flex justify-end gap-2">
            <button 
              type="button" 
              @click="showModal = false" 
              class="px-4 py-2 border rounded-xl text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-700"
            >
              Cancel
            </button>
            <button 
              type="submit" 
              class="px-5 py-2 bg-blue-900 text-white font-bold rounded-xl hover:bg-blue-800 shadow"
            >
              {{ editId ? 'Update Event' : 'Create Event' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { CalendarPlus, Edit2, Trash2, X } from 'lucide-vue-next';
import { storageService } from '../../services/storage';

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

const events = ref([]);
const showModal = ref(false);
const editId = ref(null);
const errorMessage = ref('');

const eventForm = ref({
  title: '',
  starts_at: '',
  ends_at: '',
  registration_deadline: '',
  location: '',
  event_type: 'in_person',
  meeting_link: '',
  speakers: '',
  description: '',
  seats: 100,
  is_featured: false
});

function formatDateTime(str) {
  if (!str) return '—';
  const d = new Date(str.replace(' ', 'T'));
  if (isNaN(d.getTime())) return str;
  return d.toLocaleString(props.lang === 'ar' ? 'ar-SD' : 'en-US', {
    month: 'short',
    day: 'numeric',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  });
}

async function loadEvents() {
  try {
    const res = await fetch('./api.php?action=events');
    if (res.ok) {
      const data = await res.json();
      if (data.status === 'success') {
        events.value = data.data;
        return;
      }
    }
  } catch (e) {
    console.warn('Fallback to local events:', e);
  }
  events.value = storageService.getEvents();
}

function openCreateModal() {
  editId.value = null;
  errorMessage.value = '';
  // Default starts_at to tomorrow 10:00
  const tmrw = new Date();
  tmrw.setDate(tmrw.getDate() + 2);
  tmrw.setHours(10, 0, 0, 0);

  const tmrwEnd = new Date(tmrw);
  tmrwEnd.setHours(14, 0, 0, 0);

  eventForm.value = {
    title: '',
    starts_at: tmrw.toISOString().slice(0, 16),
    ends_at: tmrwEnd.toISOString().slice(0, 16),
    registration_deadline: '',
    location: '',
    event_type: 'in_person',
    meeting_link: '',
    speakers: '',
    description: '',
    seats: 100,
    is_featured: false
  };
  showModal.value = true;
}

function editEvent(ev) {
  editId.value = ev.id;
  errorMessage.value = '';
  eventForm.value = {
    title: ev.title,
    starts_at: (ev.starts_at || '').replace(' ', 'T').slice(0, 16),
    ends_at: (ev.ends_at || '').replace(' ', 'T').slice(0, 16),
    registration_deadline: ev.registration_deadline ? ev.registration_deadline.replace(' ', 'T').slice(0, 16) : '',
    location: ev.location,
    event_type: ev.event_type || 'in_person',
    meeting_link: ev.meeting_link || '',
    speakers: ev.speakers || '',
    description: ev.description || '',
    seats: ev.seats || 100,
    is_featured: Boolean(Number(ev.is_featured))
  };
  showModal.value = true;
}

async function saveEvent() {
  errorMessage.value = '';

  // 🔴 VALIDATION (ends_at must be strictly after starts_at)
  const startTime = new Date(eventForm.value.starts_at).getTime();
  const endTime = new Date(eventForm.value.ends_at).getTime();

  if (endTime <= startTime) {
    errorMessage.value = props.lang === 'ar' 
      ? 'خطأ: يجب أن يكون تاريخ ووقت نهاية الفعالية بعد تاريخ ووقت البداية!' 
      : 'Validation Error: Ends At must be strictly after Starts At!';
    return;
  }

  // Warning check if past date entered
  if (startTime < Date.now()) {
    if (!confirm(props.lang === 'ar' ? 'تنبيه: لقد حددت تاريخ بداية في الماضي، هل تريد المتابعة؟' : 'Warning: Starts At is in the past. Proceed?')) {
      return;
    }
  }

  const token = localStorage.getItem('ieee_aau_token') || '';

  try {
    const res = await fetch(`./api.php?action=events${editId.value ? '&id=' + editId.value : ''}`, {
      method: editId.value ? 'PUT' : 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${token}`
      },
      body: JSON.stringify({
        ...eventForm.value,
        starts_at: eventForm.value.starts_at.replace('T', ' ') + ':00',
        ends_at: eventForm.value.ends_at.replace('T', ' ') + ':00',
        registration_deadline: eventForm.value.registration_deadline ? eventForm.value.registration_deadline.replace('T', ' ') + ':00' : null,
        is_featured: eventForm.value.is_featured ? 1 : 0
      })
    });

    const data = await res.json();
    if (res.ok && data.status === 'success') {
      emit('notify', data.message || 'Event saved successfully!', 'success');
      showModal.value = false;
      await loadEvents();
      return;
    } else {
      errorMessage.value = data.message || 'Operation failed';
      return;
    }
  } catch (e) {
    console.warn('Network error, saving to local store:', e);
  }

  // Local storage fallback
  if (editId.value) {
    const ev = events.value.find(e => e.id === editId.value);
    if (ev) Object.assign(ev, eventForm.value);
    storageService.saveEvents(events.value);
  } else {
    storageService.addEvent(eventForm.value);
    events.value = storageService.getEvents();
  }

  emit('notify', 'Event saved successfully!', 'success');
  showModal.value = false;
}

async function deleteEvent(id) {
  if (confirm(props.lang === 'ar' ? `هل أنت متأكد من حذف الفعالية ${id}؟` : `Delete event ${id}?`)) {
    const token = localStorage.getItem('ieee_aau_token') || '';
    try {
      await fetch(`./api.php?action=events&id=${id}`, {
        method: 'DELETE',
        headers: { 'Authorization': `Bearer ${token}` }
      });
    } catch (e) {}

    events.value = events.value.filter(e => e.id !== id);
    storageService.saveEvents(events.value);
    emit('notify', props.lang === 'ar' ? 'تم حذف الفعالية' : 'Event removed', 'info');
  }
}

onMounted(() => {
  loadEvents();
});
</script>
