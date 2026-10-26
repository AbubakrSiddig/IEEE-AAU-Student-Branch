<template>
  <footer class="bg-[#0B192C] text-white border-t border-slate-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
        <!-- Logo & Mission Column -->
        <div class="space-y-4 md:col-span-2">
          <router-link to="/" class="inline-block">
            <img 
              src="/assets/images/logo-white.png" 
              alt="IEEE AAU Student Branch" 
              class="h-12 w-auto object-contain"
            />
          </router-link>
          <p class="text-slate-300 text-sm max-w-md leading-relaxed">
            {{ lang === 'ar' 
              ? 'فرع طلاب IEEE بجامعة الزعيم الأزهري - منصة طلابية تسعى لتمكين العقول الشابة وبناء مجتمع هندسي وتقني ملهم ومبتكر.'
              : 'IEEE AAU Student Branch — Empowering young minds and fostering technological innovation and excellence for the benefit of humanity.' 
            }}
          </p>
          <div class="flex items-center gap-4 text-slate-400">
            <a href="https://linkedin.com" target="_blank" class="hover:text-white transition-colors" title="LinkedIn">
              <Linkedin class="w-5 h-5" />
            </a>
            <a href="https://x.com" target="_blank" class="hover:text-white transition-colors" title="X (Twitter)">
              <Twitter class="w-5 h-5" />
            </a>
            <a href="https://facebook.com" target="_blank" class="hover:text-white transition-colors" title="Facebook">
              <Facebook class="w-5 h-5" />
            </a>
            <a href="https://instagram.com" target="_blank" class="hover:text-white transition-colors" title="Instagram">
              <Instagram class="w-5 h-5" />
            </a>
          </div>
        </div>

        <!-- Quick Links -->
        <div>
          <h4 class="text-sm font-bold uppercase tracking-wider text-blue-300 mb-4">
            {{ lang === 'ar' ? 'روابط سريعة' : 'Quick Links' }}
          </h4>
          <ul class="space-y-2.5 text-sm text-slate-300">
            <li><router-link to="/about" class="hover:text-white transition-colors">{{ t('about') }}</router-link></li>
            <li><router-link to="/news" class="hover:text-white transition-colors">{{ t('news') }}</router-link></li>
            <li><router-link to="/events" class="hover:text-white transition-colors">{{ t('events') }}</router-link></li>
            <li><router-link to="/articles" class="hover:text-white transition-colors">{{ t('articles') }}</router-link></li>
            <li><router-link to="/magazine" class="hover:text-white transition-colors">{{ t('magazine') }}</router-link></li>
          </ul>
        </div>

        <!-- Contact & Location Info -->
        <div>
          <h4 class="text-sm font-bold uppercase tracking-wider text-blue-300 mb-4">
            {{ lang === 'ar' ? 'معلومات التواصل' : 'Contact Us' }}
          </h4>
          <ul class="space-y-3 text-sm text-slate-300">
            <li class="flex items-start gap-2.5">
              <MapPin class="w-4 h-4 text-blue-400 mt-1 flex-shrink-0" />
              <span>{{ t('addressValue') }}</span>
            </li>
            <li class="flex items-center gap-2.5">
              <Mail class="w-4 h-4 text-blue-400 flex-shrink-0" />
              <a href="mailto:info@ieee-aau.org" class="hover:text-white">info@ieee-aau.org</a>
            </li>
            <li class="flex items-center gap-2.5">
              <Phone class="w-4 h-4 text-blue-400 flex-shrink-0" />
              <span dir="ltr">+249 123 456 789</span>
            </li>
            <li class="pt-2">
              <router-link to="/track-application" class="inline-flex items-center gap-1.5 text-xs text-blue-300 hover:text-white font-medium underline">
                <span>{{ t('viewMyApplications') }}</span>
                <ArrowRight class="w-3.5 h-3.5" />
              </router-link>
            </li>
          </ul>
        </div>
      </div>

      <!-- Bottom Bar -->
      <div class="mt-12 pt-6 border-t border-slate-800 flex flex-col sm:flex-row items-center justify-between text-xs text-slate-400 gap-4">
        <div>
          &copy; {{ new Date().getFullYear() }} IEEE AAU Student Branch. All rights reserved.
        </div>
        <div class="flex items-center gap-6">
          <router-link to="/join" class="hover:text-white transition-colors">{{ t('stepTerms') }}</router-link>
          <router-link to="/contact" class="hover:text-white transition-colors">{{ t('contact') }}</router-link>
          <router-link to="/admin" class="hover:text-white transition-colors">{{ t('adminDashboard') }}</router-link>
        </div>
      </div>
    </div>
  </footer>
</template>

<script setup>
import { MapPin, Mail, Phone, Linkedin, Twitter, Facebook, Instagram, ArrowRight } from 'lucide-vue-next';

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
</script>
