<template>
  <div class="py-12 bg-slate-50 dark:bg-slate-900 min-h-screen transition-colors duration-200">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
      <div class="border-b border-slate-200 dark:border-slate-800 pb-6">
        <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white">{{ t('contact') }}</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">
          {{ t('contactSubtitle') }}
        </p>
      </div>

      <!-- Main Contact Grid (Matching Screen 7) -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">
        <!-- Contact Information Block (Matching Screen 7) -->
        <div class="bg-gradient-to-br from-blue-900 via-blue-950 to-[#0B192C] text-white rounded-3xl p-8 sm:p-10 shadow-xl space-y-8 border border-blue-800/40">
          <div>
            <h2 class="text-2xl font-black mb-2">IEEE AAU SB</h2>
            <p class="text-sm text-blue-200 leading-relaxed">
              {{ lang === 'ar' 
                ? 'مقر فرع طلاب IEEE بجامعة الزعيم الأزهري، الخرطوم، السودان. نحن هنا للإجابة على جميع الاستفسارات ودعم شراكات الطلاب والجهات الراعية.'
                : 'Headquartered at Alzaeim Alazhari University, Khartoum, Sudan. We welcome inquiries, student collaborations, and sponsorship partnerships.' 
              }}
            </p>
          </div>

          <div class="space-y-4 text-sm text-blue-100">
            <!-- Address -->
            <div class="flex items-start gap-3">
              <MapPin class="w-5 h-5 text-blue-400 mt-0.5 flex-shrink-0" />
              <div>
                <strong class="block text-white text-xs uppercase tracking-wider">{{ t('addressLabel') }}</strong>
                <span>{{ t('addressValue') }}</span>
              </div>
            </div>

            <!-- Email -->
            <div class="flex items-start gap-3">
              <Mail class="w-5 h-5 text-blue-400 mt-0.5 flex-shrink-0" />
              <div>
                <strong class="block text-white text-xs uppercase tracking-wider">{{ t('emailLabel') }}</strong>
                <a href="mailto:info@ieee-aau.org" class="hover:underline">info@ieee-aau.org</a>
              </div>
            </div>

            <!-- Phone -->
            <div class="flex items-start gap-3">
              <Phone class="w-5 h-5 text-blue-400 mt-0.5 flex-shrink-0" />
              <div>
                <strong class="block text-white text-xs uppercase tracking-wider">{{ t('phoneLabel') }}</strong>
                <span dir="ltr">+249 123 456 789</span>
              </div>
            </div>
          </div>

          <!-- Social Icons (Matching Screen 7) -->
          <div class="pt-4 border-t border-blue-800/80">
            <span class="block text-xs uppercase tracking-widest text-blue-300 font-semibold mb-3">
              {{ lang === 'ar' ? 'تابعنا على المنصات الرسمية' : 'Connect On Social Channels' }}
            </span>
            <div class="flex items-center gap-4 text-white">
              <a href="https://linkedin.com" target="_blank" class="w-10 h-10 rounded-xl bg-white/10 hover:bg-white/20 flex items-center justify-center transition-colors">
                <Linkedin class="w-5 h-5" />
              </a>
              <a href="https://x.com" target="_blank" class="w-10 h-10 rounded-xl bg-white/10 hover:bg-white/20 flex items-center justify-center transition-colors">
                <Twitter class="w-5 h-5" />
              </a>
              <a href="https://facebook.com" target="_blank" class="w-10 h-10 rounded-xl bg-white/10 hover:bg-white/20 flex items-center justify-center transition-colors">
                <Facebook class="w-5 h-5" />
              </a>
              <a href="https://instagram.com" target="_blank" class="w-10 h-10 rounded-xl bg-white/10 hover:bg-white/20 flex items-center justify-center transition-colors">
                <Instagram class="w-5 h-5" />
              </a>
            </div>
          </div>
        </div>

        <!-- Contact Form -->
        <div class="bg-white dark:bg-slate-800 rounded-3xl p-8 sm:p-10 border border-slate-200 dark:border-slate-700 shadow-sm space-y-6 transition-colors">
          <h2 class="text-xl font-bold text-slate-900 dark:text-white">{{ t('sendMessage') }}</h2>
          <form @submit.prevent="submitMessage" class="space-y-4">
            <div>
              <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">{{ t('fullName') }} *</label>
              <input 
                v-model="form.name" 
                required 
                type="text" 
                class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-700 dark:text-white border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white dark:focus:bg-slate-700" 
              />
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">{{ t('email') }} *</label>
              <input 
                v-model="form.email" 
                required 
                type="email" 
                class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-700 dark:text-white border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white dark:focus:bg-slate-700" 
              />
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">{{ t('subject') }}</label>
              <input 
                v-model="form.subject" 
                type="text" 
                class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-700 dark:text-white border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white dark:focus:bg-slate-700" 
              />
            </div>
            <div>
              <label class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">{{ t('message') }} *</label>
              <textarea 
                v-model="form.message" 
                required 
                rows="4" 
                class="w-full px-4 py-2.5 bg-slate-50 dark:bg-slate-700 dark:text-white border border-slate-200 dark:border-slate-600 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-600 focus:bg-white dark:focus:bg-slate-700"
              ></textarea>
            </div>
            <div class="pt-2">
              <button 
                type="submit" 
                :disabled="submitting"
                class="w-full py-3 bg-blue-900 dark:bg-blue-600 hover:bg-blue-800 dark:hover:bg-blue-500 text-white font-bold rounded-xl shadow transition-all text-sm flex items-center justify-center gap-2"
              >
                <Send class="w-4 h-4" />
                <span>{{ submitting ? (lang === 'ar' ? 'جاري الإرسال...' : 'Sending...') : t('sendMessage') }}</span>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { MapPin, Mail, Phone, Linkedin, Twitter, Facebook, Instagram, Send } from 'lucide-vue-next';

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

const form = ref({
  name: '',
  email: '',
  subject: '',
  message: ''
});

const submitting = ref(false);

function submitMessage() {
  submitting.value = true;
  setTimeout(() => {
    submitting.value = false;
    emit('notify', props.lang === 'ar' ? 'شكراً لتواصلك معنا! سنرد على رسالتك في أقرب وقت.' : 'Thank you for reaching out! We will reply shortly.', 'success');
    form.value = { name: '', email: '', subject: '', message: '' };
  }, 600);
}
</script>
