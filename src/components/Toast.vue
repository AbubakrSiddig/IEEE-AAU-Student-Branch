<template>
  <div class="fixed bottom-6 right-6 z-50 flex flex-col gap-2 max-w-sm w-full pointer-events-none">
    <transition-group name="toast">
      <div 
        v-for="toast in toasts" 
        :key="toast.id" 
        class="pointer-events-auto p-4 rounded-xl shadow-lg border flex items-center gap-3 backdrop-blur-md transition-all duration-300"
        :class="{
          'bg-emerald-50 border-emerald-200 text-emerald-900': toast.type === 'success',
          'bg-rose-50 border-rose-200 text-rose-900': toast.type === 'error',
          'bg-blue-50 border-blue-200 text-blue-900': toast.type === 'info',
          'bg-amber-50 border-amber-200 text-amber-900': toast.type === 'warning'
        }"
      >
        <div class="flex-shrink-0">
          <CheckCircle v-if="toast.type === 'success'" class="w-5 h-5 text-emerald-600" />
          <AlertCircle v-else-if="toast.type === 'error'" class="w-5 h-5 text-rose-600" />
          <AlertTriangle v-else-if="toast.type === 'warning'" class="w-5 h-5 text-amber-600" />
          <Info v-else class="w-5 h-5 text-blue-600" />
        </div>
        <div class="text-sm font-medium flex-1">
          {{ toast.message }}
        </div>
        <button @click="remove(toast.id)" class="text-slate-400 hover:text-slate-600">
          <X class="w-4 h-4" />
        </button>
      </div>
    </transition-group>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { CheckCircle, AlertCircle, AlertTriangle, Info, X } from 'lucide-vue-next';

const toasts = ref([]);

let toastCount = 0;

function showToast(message, type = 'success', duration = 3500) {
  const id = ++toastCount;
  toasts.value.push({ id, message, type });
  setTimeout(() => {
    remove(id);
  }, duration);
}

function remove(id) {
  toasts.value = toasts.value.filter(t => t.id !== id);
}

defineExpose({ showToast });
</script>

<style scoped>
.toast-enter-active,
.toast-leave-active {
  transition: all 0.3s ease;
}
.toast-enter-from {
  opacity: 0;
  transform: translateY(20px) scale(0.95);
}
.toast-leave-to {
  opacity: 0;
  transform: translateX(50px);
}
</style>
