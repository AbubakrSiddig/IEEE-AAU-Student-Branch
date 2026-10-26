<template>
  <div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
      <div>
        <div class="flex items-center gap-2">
          <h1 class="text-2xl font-black text-slate-900 dark:text-white">
            {{ lang === 'ar' ? 'إدارة دورات المكتب والمناصب' : 'Office Terms Management' }}
          </h1>
          <span 
            class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider border"
            :class="isChairman ? 'bg-amber-100 text-amber-900 border-amber-300 dark:bg-amber-950/40 dark:text-amber-300' : 'bg-slate-100 text-slate-700 dark:bg-slate-700 dark:text-slate-300 border-slate-200'"
          >
            {{ isChairman ? (lang === 'ar' ? 'صلاحية رئيس الفرع (Chairman)' : 'Chairman Authority') : (lang === 'ar' ? 'عرض فقط' : 'View Only') }}
          </span>
        </div>
        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
          {{ lang === 'ar' 
            ? 'التحكم في دورات مكتب الفرع وتعيين الأشخاص في المناصب الخمسة التنفيذية والفرق الثمانية (وفق المواصفة v0.3.4)' 
            : 'Manage annual branch terms, assign office leadership, and 8 committee coordinators according to Spec v0.3.4.' 
          }}
        </p>
      </div>

      <div class="flex items-center gap-2">
        <button 
          v-if="isChairman"
          @click="openCreateModal"
          class="px-4 py-2.5 bg-blue-900 hover:bg-blue-800 text-white text-xs font-bold rounded-xl shadow-xs transition-all flex items-center gap-2"
        >
          <Plus class="w-4 h-4" />
          <span>{{ lang === 'ar' ? 'دورة مكتب جديدة' : 'New Office Term' }}</span>
        </button>
      </div>
    </div>

    <!-- Notice if View Only -->
    <div v-if="!isChairman" class="p-4 bg-amber-50 dark:bg-amber-950/30 border border-amber-200 dark:border-amber-800 rounded-2xl flex items-center gap-3 text-xs text-amber-900 dark:text-amber-300">
      <ShieldAlert class="w-5 h-5 flex-shrink-0" />
      <span>
        {{ lang === 'ar' 
          ? 'تنبيه: صفحة إدارة الدورات معروضة بصلاحية القراءة فقط. التعديل والإنشاء والنشر محصور برئيس الفرع (Chairman) وفق المواصفة v0.3.4.' 
          : 'Notice: This page is in read-only mode. Creating, modifying, and activating terms is restricted to the Branch Chairman.' 
        }}
      </span>
    </div>

    <!-- Terms List Table (Screen A) -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200/80 dark:border-slate-700 shadow-xs overflow-hidden">
      <div class="p-4 border-b border-slate-100 dark:border-slate-700 flex items-center justify-between">
        <h3 class="font-bold text-slate-900 dark:text-white text-xs uppercase tracking-wider">
          {{ lang === 'ar' ? 'قائمة دورات المكتب' : 'All Office Terms' }}
        </h3>
        <span class="text-xs text-slate-400">{{ terms.length }} {{ lang === 'ar' ? 'دورات مسجلة' : 'terms recorded' }}</span>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full text-left text-xs text-slate-600 dark:text-slate-300">
          <thead class="bg-slate-50 dark:bg-slate-700/50 text-slate-400 font-semibold uppercase tracking-wider border-b border-slate-100 dark:border-slate-700">
            <tr>
              <th class="py-3.5 px-6">{{ lang === 'ar' ? 'الدورة' : 'Term Title' }}</th>
              <th class="py-3.5 px-6">{{ lang === 'ar' ? 'الفترة الزمنية' : 'Period' }}</th>
              <th class="py-3.5 px-6">{{ lang === 'ar' ? 'الحالة' : 'Status' }}</th>
              <th class="py-3.5 px-6">{{ lang === 'ar' ? 'عدد المناصب' : 'Positions' }}</th>
              <th class="py-3.5 px-6 text-right">{{ lang === 'ar' ? 'الإجراءات' : 'Actions' }}</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100 dark:divide-slate-700 font-medium">
            <tr 
              v-for="term in terms" 
              :key="term.id"
              class="hover:bg-slate-50 dark:hover:bg-slate-700/40 transition-colors"
            >
              <td class="py-3.5 px-6 whitespace-nowrap">
                <div class="font-bold text-slate-900 dark:text-white text-sm font-mono">{{ term.title }}</div>
                <div class="text-[10px] text-slate-400">ID: #{{ term.id }}</div>
              </td>
              <td class="py-3.5 px-6 whitespace-nowrap font-mono text-[11px]" dir="ltr">
                {{ term.starts_on }} &rarr; {{ term.ends_on }}
              </td>
              <td class="py-3.5 px-6 whitespace-nowrap">
                <span 
                  class="px-2.5 py-1 rounded-md text-[11px] font-bold border"
                  :class="{
                    'bg-emerald-50 dark:bg-emerald-950/40 text-emerald-800 dark:text-emerald-300 border-emerald-200': term.status === 'current',
                    'bg-slate-100 dark:bg-slate-700 text-slate-600 dark:text-slate-400 border-slate-200': term.status === 'archived',
                    'bg-amber-50 dark:bg-amber-950/40 text-amber-800 dark:text-amber-300 border-amber-200': term.status === 'draft'
                  }"
                >
                  {{ term.status === 'current' ? (lang === 'ar' ? 'حالية (Current)' : 'Current') : (term.status === 'archived' ? (lang === 'ar' ? 'مؤرشفة' : 'Archived') : (lang === 'ar' ? 'مسودة' : 'Draft')) }}
                </span>
              </td>
              <td class="py-3.5 px-6 whitespace-nowrap">
                <span class="px-2 py-0.5 rounded bg-blue-50 dark:bg-blue-900/30 text-blue-900 dark:text-blue-300 font-bold">
                  {{ term.positions_count || 13 }} المناصب
                </span>
              </td>
              <td class="py-3.5 px-6 text-right whitespace-nowrap space-x-1.5">
                <button 
                  v-if="term.status !== 'current' && isChairman"
                  @click="openActivateConfirm(term)"
                  class="px-2.5 py-1 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-[11px] font-bold transition-colors inline-flex items-center gap-1"
                  title="Activate term as current & sync roles"
                >
                  <Zap class="w-3 h-3" />
                  <span>{{ lang === 'ar' ? 'تفعيل الآن' : 'Activate' }}</span>
                </button>

                <button 
                  @click="openEditModal(term)"
                  class="p-1.5 text-blue-900 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-slate-700 rounded-lg transition-colors"
                  :title="isChairman ? 'Edit Term' : 'View Term'"
                >
                  <component :is="isChairman ? Edit3 : Eye" class="w-4 h-4" />
                </button>

                <button 
                  v-if="term.status !== 'current' && isChairman"
                  @click="deleteTerm(term)"
                  class="p-1.5 text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-900/20 rounded-lg transition-colors"
                  title="Delete Term"
                >
                  <Trash2 class="w-4 h-4" />
                </button>
              </td>
            </tr>

            <tr v-if="terms.length === 0">
              <td colspan="5" class="py-8 text-center text-slate-400">
                {{ lang === 'ar' ? 'لا توجد دورات مسجلة' : 'No office terms found.' }}
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Full Term Editor Modal (Screen B) -->
    <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/70 backdrop-blur-sm">
      <div class="bg-white dark:bg-slate-800 rounded-3xl max-w-4xl w-full p-6 sm:p-8 space-y-6 shadow-2xl border border-slate-100 dark:border-slate-700 max-h-[92vh] overflow-y-auto">
        
        <!-- Header -->
        <div class="flex items-center justify-between border-b dark:border-slate-700 pb-3">
          <div>
            <h3 class="text-xl font-black text-slate-900 dark:text-white">
              {{ isEditing ? (lang === 'ar' ? 'تعديل بيانات دورة المكتب والمناصب' : 'Edit Office Term & Positions') : (lang === 'ar' ? 'إنشاء دورة مكتبية جديدة' : 'Create New Office Term') }}
            </h3>
            <p class="text-xs text-slate-400">
              {{ lang === 'ar' ? 'تحديد فترة الدورة والمناصب الخمسة التنفيذية والفرق الثمانية' : 'Configure term dates, office members, and 8 coordinators' }}
            </p>
          </div>
          <button @click="showModal = false" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
            <X class="w-5 h-5" />
          </button>
        </div>

        <form @submit.prevent="saveTerm" class="space-y-6 text-xs">
          <!-- Step 1: Term General Info -->
          <div class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-700/40 border border-slate-200 dark:border-slate-700 space-y-3">
            <h4 class="font-bold text-slate-900 dark:text-white uppercase text-[11px] flex items-center gap-2">
              <Calendar class="w-4 h-4 text-blue-900 dark:text-blue-400" />
              <span>{{ lang === 'ar' ? '1. البيانات الأساسية للدورة' : '1. Term General Period' }}</span>
            </h4>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
              <div>
                <label class="block font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">
                  {{ lang === 'ar' ? 'تاريخ بداية الدورة' : 'Starts On' }} *
                </label>
                <input 
                  v-model="termForm.starts_on" 
                  required 
                  type="date" 
                  @change="autoGenerateTitle"
                  class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 dark:text-white"
                />
              </div>

              <div>
                <label class="block font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">
                  {{ lang === 'ar' ? 'تاريخ نهاية الدورة' : 'Ends On' }} *
                </label>
                <input 
                  v-model="termForm.ends_on" 
                  required 
                  type="date" 
                  @change="autoGenerateTitle"
                  class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 dark:text-white"
                />
              </div>

              <div>
                <label class="block font-bold text-slate-700 dark:text-slate-300 uppercase mb-1">
                  {{ lang === 'ar' ? 'عنوان الدورة' : 'Term Title' }} *
                </label>
                <input 
                  v-model="termForm.title" 
                  required 
                  type="text" 
                  placeholder="2025 – 2026" 
                  class="w-full px-3 py-2 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-800 dark:text-white font-mono font-bold"
                />
              </div>
            </div>

            <!-- Copy From Previous Term Assistant (Feature 2.2-ج) -->
            <div v-if="!isEditing && terms.length > 0" class="pt-2 flex items-center gap-3 border-t dark:border-slate-700">
              <span class="text-slate-500 font-semibold text-[11px]">
                {{ lang === 'ar' ? 'نسخ المناصب من دورة سابقة لتوفير الوقت:' : 'Quick Copy from Previous Term:' }}
              </span>
              <select 
                v-model="copyFromTermId" 
                class="px-3 py-1.5 bg-white dark:bg-slate-800 border rounded-lg text-xs"
              >
                <option :value="null">-- {{ lang === 'ar' ? 'بدء من الصفر' : 'Start Fresh' }} --</option>
                <option v-for="t in terms" :key="t.id" :value="t.id">{{ t.title }} ({{ t.status }})</option>
              </select>
            </div>
          </div>

          <!-- Step 2: 5 Office Positions (Chairman, Vice Chair, Secretary, Treasurer, HR) -->
          <div class="space-y-3">
            <div class="flex items-center justify-between border-b dark:border-slate-700 pb-2">
              <h4 class="font-bold text-slate-900 dark:text-white uppercase text-xs flex items-center gap-2">
                <ShieldCheck class="w-4 h-4 text-blue-900 dark:text-blue-400" />
                <span>{{ lang === 'ar' ? '2. أعضاء المكتب التنفيذي (5 مناصب إلزامية)' : '2. 5 Executive Office Members' }}</span>
              </h4>
              <span class="text-[10px] text-slate-400">{{ officePositions.length }} Positions</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div 
                v-for="pos in officePositions" 
                :key="pos.position_key"
                class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-700/30 border border-slate-200 dark:border-slate-700 space-y-3"
              >
                <div class="flex items-center justify-between">
                  <span class="px-2.5 py-0.5 rounded text-[10px] font-black uppercase tracking-wider bg-blue-100 dark:bg-blue-900/50 text-blue-900 dark:text-blue-300">
                    {{ pos.title_en }}
                  </span>
                  <label class="flex items-center gap-1.5 cursor-pointer text-[11px] text-slate-500">
                    <input type="checkbox" v-model="pos.is_vacant" class="rounded text-blue-900" />
                    <span>{{ lang === 'ar' ? 'منصب شاغر' : 'Vacant' }}</span>
                  </label>
                </div>

                <div v-if="!pos.is_vacant" class="space-y-2">
                  <div class="grid grid-cols-2 gap-2">
                    <div>
                      <label class="block text-[10px] text-slate-400 font-bold uppercase">الاسم بالعربية</label>
                      <input v-model="pos.name_ar" required type="text" class="w-full px-2.5 py-1.5 border rounded-lg dark:bg-slate-800 dark:border-slate-600 dark:text-white" />
                    </div>
                    <div>
                      <label class="block text-[10px] text-slate-400 font-bold uppercase">Name (EN)</label>
                      <input v-model="pos.name_en" required type="text" class="w-full px-2.5 py-1.5 border rounded-lg dark:bg-slate-800 dark:border-slate-600 dark:text-white" />
                    </div>
                  </div>

                  <!-- Direct Photo Upload with Preview & Auto-crop to Square WebP -->
                  <div class="p-3 bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-600 space-y-2">
                    <div class="flex items-center justify-between">
                      <label class="block text-[11px] text-slate-700 dark:text-slate-300 font-bold uppercase flex items-center gap-1.5">
                        <Camera class="w-3.5 h-3.5 text-blue-900 dark:text-blue-400" />
                        <span>{{ lang === 'ar' ? 'صورة العضو الشخصية' : 'Member Photo' }}</span>
                      </label>
                      <span v-if="pos.photo_path" class="text-[10px] text-emerald-600 dark:text-emerald-400 font-semibold flex items-center gap-1">
                        <Check class="w-3 h-3" /> {{ lang === 'ar' ? 'تم اختيار الصورة' : 'Photo ready' }}
                      </span>
                    </div>

                    <div class="flex items-center gap-3">
                      <!-- Avatar Thumbnail Preview -->
                      <div class="w-14 h-14 rounded-full overflow-hidden border-2 border-blue-200 dark:border-blue-900/60 bg-slate-100 dark:bg-slate-700 flex items-center justify-center shrink-0 shadow-xs relative group">
                        <img 
                          v-if="pos.photo_path" 
                          :src="pos.photo_path" 
                          :alt="pos.name_en || pos.name_ar" 
                          class="w-full h-full object-cover"
                        />
                        <div v-else class="text-slate-400 flex flex-col items-center justify-center">
                          <Camera class="w-5 h-5" />
                        </div>
                        <div v-if="pos._uploading" class="absolute inset-0 bg-black/60 flex items-center justify-center text-white">
                          <Loader2 class="w-5 h-5 animate-spin" />
                        </div>
                      </div>

                      <!-- Action Buttons -->
                      <div class="flex-1 space-y-1.5">
                        <div class="flex items-center gap-2 flex-wrap">
                          <!-- Direct File Upload Button -->
                          <label 
                            class="px-3 py-1.5 bg-blue-900 text-white hover:bg-blue-800 rounded-lg text-[11px] font-bold cursor-pointer transition-colors inline-flex items-center gap-1.5 shadow-xs"
                            :class="{ 'opacity-50 pointer-events-none': pos._uploading }"
                          >
                            <Upload class="w-3.5 h-3.5" />
                            <span>{{ pos._uploading ? (lang === 'ar' ? 'جاري رفع واقتصاص الصورة...' : 'Processing...') : (pos.photo_path ? (lang === 'ar' ? 'تغيير الصورة من الجهاز' : 'Change Photo') : (lang === 'ar' ? 'رفع صورة من جهازك' : 'Upload Photo')) }}</span>
                            <input 
                              type="file" 
                              accept="image/*" 
                              class="hidden" 
                              @change="e => handlePhotoUpload(e, pos)" 
                            />
                          </label>

                          <!-- Remove Photo Button -->
                          <button 
                            v-if="pos.photo_path"
                            type="button" 
                            @click="removePhoto(pos)"
                            class="px-2.5 py-1.5 text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/30 rounded-lg text-[11px] font-semibold border border-rose-200 dark:border-rose-900 transition-colors inline-flex items-center gap-1"
                            title="Remove Photo"
                          >
                            <Trash2 class="w-3 h-3" />
                            <span>{{ lang === 'ar' ? 'حذف' : 'Remove' }}</span>
                          </button>
                        </div>
                        
                        <p class="text-[10px] text-slate-400 leading-tight">
                          {{ lang === 'ar' ? 'اختر ملف صورة (JPG, PNG, WebP) - اقتصاص لمربع وضغط تلقائي.' : 'Auto-crops to square and converts to WebP.' }}
                        </p>
                      </div>
                    </div>
                  </div>

                  <!-- LinkedIn URL -->
                  <div>
                    <label class="block text-[10px] text-slate-400 font-bold uppercase mb-1">LinkedIn / البريد الإلكتروني</label>
                    <input v-model="pos.linkedin_url" type="text" placeholder="https://linkedin.com/in/... or email" class="w-full px-2.5 py-1.5 border rounded-lg dark:bg-slate-800 dark:border-slate-600 dark:text-white text-[11px]" />
                  </div>

                  <!-- Linked User for Role Synchronization (Section 3) -->
                  <div>
                    <label class="block text-[10px] text-slate-400 font-bold uppercase">
                      {{ lang === 'ar' ? 'ربط بحساب مستخدم (لمزامنة الصلاحيات تلقائياً):' : 'Link User Account (Role Sync):' }}
                    </label>
                    <select v-model="pos.user_id" class="w-full px-2.5 py-1.5 border rounded-lg dark:bg-slate-800 dark:border-slate-600 dark:text-white text-[11px]">
                      <option :value="null">-- {{ lang === 'ar' ? 'بدون ربط حساب' : 'No User Linked' }} --</option>
                      <option v-for="u in usersList" :key="u.id" :value="u.id">
                        {{ u.name }} ({{ u.email }})
                      </option>
                    </select>
                  </div>
                </div>
                <div v-else class="text-center py-4 text-slate-400 text-xs italic">
                  {{ lang === 'ar' ? 'هذا المنصب مسجل كمنصب شاغر ولا يعرض في الواجهة العامة.' : 'Marked as vacant.' }}
                </div>
              </div>
            </div>
          </div>

          <!-- Step 3: 8 Team Coordinators -->
          <div class="space-y-3 pt-2">
            <div class="flex items-center justify-between border-b dark:border-slate-700 pb-2">
              <h4 class="font-bold text-slate-900 dark:text-white uppercase text-xs flex items-center gap-2">
                <Users class="w-4 h-4 text-blue-900 dark:text-blue-400" />
                <span>{{ lang === 'ar' ? '3. منسقو الفرق الثمانية' : '3. 8 Committee Coordinators' }}</span>
              </h4>
              <span class="text-[10px] text-slate-400">{{ coordinatorPositions.length }} Coordinators</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div 
                v-for="pos in coordinatorPositions" 
                :key="pos.position_key"
                class="p-4 rounded-2xl bg-slate-50 dark:bg-slate-700/30 border border-slate-200 dark:border-slate-700 space-y-3 relative"
              >
                <!-- HR rule notice -->
                <div v-if="pos.position_key === 'hr'" class="p-2.5 bg-purple-50 dark:bg-purple-950/40 border border-purple-200 dark:border-purple-800 rounded-xl text-[10px] text-purple-900 dark:text-purple-300">
                  ★ <strong>قاعدة HR المعتمدة:</strong> منسق فريق HR ومسؤول HR بالمكتب شخص واحد. يتم تعديل بياناته من قسم المكتب أعلاه، وتنعكس هنا تلقائياً.
                </div>

                <div class="flex items-center justify-between">
                  <span class="px-2.5 py-0.5 rounded text-[10px] font-black uppercase tracking-wider bg-slate-200 dark:bg-slate-700 text-slate-800 dark:text-slate-200">
                    {{ pos.title_en }}
                  </span>
                  <label v-if="pos.position_key !== 'hr'" class="flex items-center gap-1.5 cursor-pointer text-[11px] text-slate-500">
                    <input type="checkbox" v-model="pos.is_vacant" class="rounded text-blue-900" />
                    <span>{{ lang === 'ar' ? 'شاغر' : 'Vacant' }}</span>
                  </label>
                </div>

                <div v-if="!pos.is_vacant" class="space-y-2">
                  <div class="grid grid-cols-2 gap-2">
                    <div>
                      <label class="block text-[10px] text-slate-400 font-bold uppercase">الاسم بالعربية</label>
                      <input 
                        v-model="pos.name_ar" 
                        :disabled="pos.position_key === 'hr'"
                        required 
                        type="text" 
                        class="w-full px-2.5 py-1.5 border rounded-lg dark:bg-slate-800 dark:border-slate-600 dark:text-white disabled:opacity-60" 
                      />
                    </div>
                    <div>
                      <label class="block text-[10px] text-slate-400 font-bold uppercase">Name (EN)</label>
                      <input 
                        v-model="pos.name_en" 
                        :disabled="pos.position_key === 'hr'"
                        required 
                        type="text" 
                        class="w-full px-2.5 py-1.5 border rounded-lg dark:bg-slate-800 dark:border-slate-600 dark:text-white disabled:opacity-60" 
                      />
                    </div>
                  </div>

                  <!-- Coordinator Photo Upload -->
                  <div v-if="pos.position_key === 'hr'" class="p-3 bg-purple-50 dark:bg-purple-950/40 border border-purple-200 dark:border-purple-800 rounded-xl space-y-2">
                    <div class="flex items-center gap-3">
                      <div class="w-12 h-12 rounded-full overflow-hidden border-2 border-purple-300 dark:border-purple-700 bg-slate-100 dark:bg-slate-800 flex items-center justify-center shrink-0">
                        <img v-if="pos.photo_path" :src="pos.photo_path" class="w-full h-full object-cover" />
                        <Camera v-else class="w-5 h-5 text-purple-400" />
                      </div>
                      <div class="text-[11px] text-purple-900 dark:text-purple-300">
                        <span class="font-bold">الصورة والبيانات مرتبطة تلقائياً بمسؤول HR في المكتب.</span>
                        <p class="text-[10px] text-purple-700 dark:text-purple-400">لتحديث الصورة، قم برفعها في قسم المكتب التنفيذي أعلاه.</p>
                      </div>
                    </div>
                  </div>

                  <div v-else class="p-3 bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-600 space-y-2">
                    <div class="flex items-center justify-between">
                      <label class="block text-[11px] text-slate-700 dark:text-slate-300 font-bold uppercase flex items-center gap-1.5">
                        <Camera class="w-3.5 h-3.5 text-blue-900 dark:text-blue-400" />
                        <span>{{ lang === 'ar' ? 'صورة المنسق' : 'Coordinator Photo' }}</span>
                      </label>
                      <span v-if="pos.photo_path" class="text-[10px] text-emerald-600 dark:text-emerald-400 font-semibold flex items-center gap-1">
                        <Check class="w-3 h-3" /> {{ lang === 'ar' ? 'تم اختيار الصورة' : 'Photo ready' }}
                      </span>
                    </div>

                    <div class="flex items-center gap-3">
                      <!-- Avatar Thumbnail Preview -->
                      <div class="w-14 h-14 rounded-full overflow-hidden border-2 border-slate-200 dark:border-slate-700 bg-slate-100 dark:bg-slate-700 flex items-center justify-center shrink-0 shadow-xs relative group">
                        <img 
                          v-if="pos.photo_path" 
                          :src="pos.photo_path" 
                          :alt="pos.name_en || pos.name_ar" 
                          class="w-full h-full object-cover"
                        />
                        <div v-else class="text-slate-400 flex flex-col items-center justify-center">
                          <Camera class="w-5 h-5" />
                        </div>
                        <div v-if="pos._uploading" class="absolute inset-0 bg-black/60 flex items-center justify-center text-white">
                          <Loader2 class="w-5 h-5 animate-spin" />
                        </div>
                      </div>

                      <!-- Action Buttons -->
                      <div class="flex-1 space-y-1.5">
                        <div class="flex items-center gap-2 flex-wrap">
                          <!-- Direct File Upload Button -->
                          <label 
                            class="px-3 py-1.5 bg-slate-800 dark:bg-slate-700 text-white hover:bg-slate-900 rounded-lg text-[11px] font-bold cursor-pointer transition-colors inline-flex items-center gap-1.5 shadow-xs"
                            :class="{ 'opacity-50 pointer-events-none': pos._uploading }"
                          >
                            <Upload class="w-3.5 h-3.5" />
                            <span>{{ pos._uploading ? (lang === 'ar' ? 'جاري الرفع...' : 'Processing...') : (pos.photo_path ? (lang === 'ar' ? 'تغيير الصورة' : 'Change') : (lang === 'ar' ? 'رفع صورة من جهازك' : 'Upload Photo')) }}</span>
                            <input 
                              type="file" 
                              accept="image/*" 
                              class="hidden" 
                              @change="e => handlePhotoUpload(e, pos)" 
                            />
                          </label>

                          <!-- Remove Photo Button -->
                          <button 
                            v-if="pos.photo_path"
                            type="button" 
                            @click="removePhoto(pos)"
                            class="px-2.5 py-1.5 text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/30 rounded-lg text-[11px] font-semibold border border-rose-200 dark:border-rose-900 transition-colors inline-flex items-center gap-1"
                            title="Remove Photo"
                          >
                            <Trash2 class="w-3 h-3" />
                            <span>{{ lang === 'ar' ? 'حذف' : 'Remove' }}</span>
                          </button>
                        </div>
                        
                        <p class="text-[10px] text-slate-400 leading-tight">
                          {{ lang === 'ar' ? 'اقتصاص تلقائي لمربع وضغط WebP.' : 'Auto-cropped to square WebP.' }}
                        </p>
                      </div>
                    </div>
                  </div>

                  <!-- LinkedIn / Email -->
                  <div>
                    <label class="block text-[10px] text-slate-400 font-bold uppercase mb-1">LinkedIn / البريد</label>
                    <input 
                      v-model="pos.linkedin_url" 
                      :disabled="pos.position_key === 'hr'"
                      type="text" 
                      placeholder="https://..."
                      class="w-full px-2.5 py-1.5 border rounded-lg dark:bg-slate-800 dark:border-slate-600 dark:text-white text-[11px] disabled:opacity-60" 
                    />
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Actions -->
          <div class="flex items-center justify-end gap-2 pt-4 border-t dark:border-slate-700">
            <button 
              type="button" 
              @click="showModal = false" 
              class="px-4 py-2 border dark:border-slate-600 rounded-xl text-slate-600 dark:text-slate-300 font-semibold"
            >
              {{ lang === 'ar' ? 'إلغاء' : 'Cancel' }}
            </button>
            <button 
              v-if="isChairman"
              type="submit" 
              :disabled="saving"
              class="px-6 py-2 bg-blue-900 hover:bg-blue-800 text-white font-bold rounded-xl shadow-xs transition-all flex items-center gap-1.5 disabled:opacity-50"
            >
              <Check class="w-4 h-4" />
              <span>{{ saving ? (lang === 'ar' ? 'جاري الحفظ...' : 'Saving...') : (lang === 'ar' ? 'حفظ وتحديث الدورة' : 'Save Term') }}</span>
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- Activation Confirmation & Role Synchronization Dialog (Section 3) -->
    <div v-if="activatingTerm" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/70 backdrop-blur-sm">
      <div class="bg-white dark:bg-slate-800 rounded-3xl max-w-md w-full p-6 sm:p-8 space-y-5 shadow-2xl border border-slate-100 dark:border-slate-700">
        <div class="flex items-center gap-3 text-emerald-600">
          <Zap class="w-6 h-6" />
          <h3 class="text-lg font-black text-slate-900 dark:text-white">
            {{ lang === 'ar' ? 'تأكيد تفعيل الدورة ومزامنة الصلاحيات' : 'Activate Term & Sync Roles' }}
          </h3>
        </div>

        <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
          {{ lang === 'ar'
            ? `أنت على وشك تفعيل دورة "${activatingTerm.title}" لتصبح الدورة الحالية النشطة للفرع. سيتم نقل الدورة الحالية السابقة للأرشيف ومزامنة الصلاحيات الإدارية للأشخاص المرتبطين بحسابات مستخدمين.`
            : `You are about to activate "${activatingTerm.title}" as CURRENT. The previous term will be archived, and linked administrative user roles will be synchronized.`
          }}
        </p>

        <div class="p-3 bg-slate-50 dark:bg-slate-700/50 rounded-xl text-[11px] space-y-1 text-slate-600 dark:text-slate-300">
          <div>• رئيس الفرع: يُمنح صلاحية Chairman</div>
          <div>• أعضاء المكتب: يُمنحون دور Office Member</div>
          <div>• منسقو الفرق: يُمنحون أدوار منسقي اللجان الخاصة بهم</div>
        </div>

        <div class="flex items-center justify-end gap-2 pt-2 border-t dark:border-slate-700">
          <button @click="activatingTerm = null" class="px-4 py-2 border dark:border-slate-600 rounded-xl text-slate-600 dark:text-slate-400">
            {{ lang === 'ar' ? 'إلغاء' : 'Cancel' }}
          </button>
          <button 
            @click="confirmActivate" 
            :disabled="activating"
            class="px-5 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow-xs transition-all disabled:opacity-50"
          >
            {{ activating ? 'جاري التفعيل...' : (lang === 'ar' ? 'تأكيد التفعيل الفوري' : 'Confirm Activation') }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { 
  Plus, 
  Calendar, 
  ShieldCheck, 
  Users, 
  Edit3, 
  Eye, 
  Trash2, 
  Zap, 
  ShieldAlert, 
  Check, 
  X,
  Camera,
  Upload,
  Loader2
} from 'lucide-vue-next';
import { authService } from '../../services/auth';

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

const terms = ref([]);
const usersList = ref([]);
const showModal = ref(false);
const isEditing = ref(false);
const saving = ref(false);
const activating = ref(false);
const activatingTerm = ref(null);
const copyFromTermId = ref(null);

const isChairman = computed(() => authService.isChairman());

const termForm = ref({
  id: null,
  title: '',
  starts_on: '',
  ends_on: '',
  status: 'draft'
});

const officePositions = ref([]);
const coordinatorPositions = ref([]);

function autoGenerateTitle() {
  if (termForm.value.starts_on && termForm.value.ends_on) {
    const y1 = new Date(termForm.value.starts_on).getFullYear();
    const y2 = new Date(termForm.value.ends_on).getFullYear();
    if (!termForm.value.title || termForm.value.title.includes('–')) {
      termForm.value.title = `${y1} – ${y2}`;
    }
  }
}

async function loadTerms() {
  const token = authService.token.value || '';
  try {
    const res = await fetch(`./api.php?action=office_terms&token=${encodeURIComponent(token)}`, {
      headers: { 'Authorization': `Bearer ${token}` }
    });
    const data = await res.json();
    if (res.ok && data.status === 'success') {
      terms.value = data.data;
    }
  } catch (e) {}

  // Also load users list for role sync dropdown
  try {
    const resU = await fetch('./api.php?action=members');
    const dataU = await resU.json();
    if (resU.ok && dataU.status === 'success') {
      usersList.value = dataU.data || [];
    }
  } catch (e) {}
}

function getDefaultPositions() {
  return [
    // 5 Office positions
    { position_key: 'chairman', position_group: 'office', title_en: 'IEEE AAU Chairman', title_ar: 'رئيس فرع IEEE AAU', sort_order: 1, is_vacant: false, name_ar: '', name_en: '', photo_path: '', bio_ar: '', bio_en: '', linkedin_url: '', email_public: '', user_id: null },
    { position_key: 'vice_chair', position_group: 'office', title_en: 'Vice Chair', title_ar: 'نائب الرئيس', sort_order: 2, is_vacant: false, name_ar: '', name_en: '', photo_path: '', bio_ar: '', bio_en: '', linkedin_url: '', email_public: '', user_id: null },
    { position_key: 'secretary', position_group: 'office', title_en: 'Secretary', title_ar: 'أمين السر', sort_order: 3, is_vacant: false, name_ar: '', name_en: '', photo_path: '', bio_ar: '', bio_en: '', linkedin_url: '', email_public: '', user_id: null },
    { position_key: 'treasurer', position_group: 'office', title_en: 'Treasurer', title_ar: 'أمين الصندوق', sort_order: 4, is_vacant: false, name_ar: '', name_en: '', photo_path: '', bio_ar: '', bio_en: '', linkedin_url: '', email_public: '', user_id: null },
    { position_key: 'hr', position_group: 'office', title_en: 'Human Resources Officer', title_ar: 'مسؤول الموارد البشرية', sort_order: 5, is_vacant: false, name_ar: '', name_en: '', photo_path: '', bio_ar: '', bio_en: '', linkedin_url: '', email_public: '', user_id: null },
    
    // 8 Committee Coordinators
    { position_key: 'media', position_group: 'coordinator', team_id: 'media', title_en: 'Media Team Coordinator', title_ar: 'منسق فريق الإعلام', sort_order: 6, is_vacant: false, name_ar: '', name_en: '', photo_path: '', bio_ar: '', bio_en: '', linkedin_url: '', email_public: '' },
    { position_key: 'technical', position_group: 'coordinator', team_id: 'technical', title_en: 'Technical Team Coordinator', title_ar: 'منسق الفريق التقني', sort_order: 7, is_vacant: false, name_ar: '', name_en: '', photo_path: '', bio_ar: '', bio_en: '', linkedin_url: '', email_public: '' },
    { position_key: 'magazine', position_group: 'coordinator', team_id: 'magazine', title_en: 'Magazine Team Coordinator', title_ar: 'منسق فريق المجلة', sort_order: 8, is_vacant: false, name_ar: '', name_en: '', photo_path: '', bio_ar: '', bio_en: '', linkedin_url: '', email_public: '' },
    { position_key: 'program', position_group: 'coordinator', team_id: 'program', title_en: 'Program Team Coordinator', title_ar: 'منسق فريق البرامج', sort_order: 9, is_vacant: false, name_ar: '', name_en: '', photo_path: '', bio_ar: '', bio_en: '', linkedin_url: '', email_public: '' },
    { position_key: 'pr', position_group: 'coordinator', team_id: 'pr', title_en: 'Public Relations Coordinator', title_ar: 'منسق العلاقات العامة', sort_order: 10, is_vacant: false, name_ar: '', name_en: '', photo_path: '', bio_ar: '', bio_en: '', linkedin_url: '', email_public: '' },
    { position_key: 'hr', position_group: 'coordinator', team_id: 'hr', title_en: 'HR Team Coordinator', title_ar: 'منسق فريق الموارد البشرية', sort_order: 11, is_vacant: false, name_ar: '', name_en: '', photo_path: '', bio_ar: '', bio_en: '', linkedin_url: '', email_public: '' },
    { position_key: 'presenters', position_group: 'coordinator', team_id: 'presenters', title_en: 'Presenters Team Coordinator', title_ar: 'منسق فريق التقديم', sort_order: 12, is_vacant: false, name_ar: '', name_en: '', photo_path: '', bio_ar: '', bio_en: '', linkedin_url: '', email_public: '' },
    { position_key: 'speaker', position_group: 'coordinator', team_id: 'speaker', title_en: 'Speaker Relations Coordinator', title_ar: 'منسق المتحدثين', sort_order: 13, is_vacant: false, name_ar: '', name_en: '', photo_path: '', bio_ar: '', bio_en: '', linkedin_url: '', email_public: '' },
  ];
}

function openCreateModal() {
  isEditing.value = false;
  copyFromTermId.value = null;
  const defaults = getDefaultPositions();
  officePositions.value = defaults.filter(p => p.position_group === 'office');
  coordinatorPositions.value = defaults.filter(p => p.position_group === 'coordinator');

  const nowYear = new Date().getFullYear();
  termForm.value = {
    id: null,
    title: `${nowYear + 1} – ${nowYear + 2}`,
    starts_on: `${nowYear + 1}-09-01`,
    ends_on: `${nowYear + 2}-08-31`,
    status: 'draft'
  };
  showModal.value = true;
}

async function openEditModal(term) {
  isEditing.value = true;
  const token = authService.token.value || '';
  try {
    const res = await fetch(`./api.php?action=office_terms&id=${term.id}&token=${encodeURIComponent(token)}`, {
      headers: { 'Authorization': `Bearer ${token}` }
    });
    const data = await res.json();
    if (res.ok && data.status === 'success' && data.data) {
      termForm.value = { ...data.data.term };
      const pos = data.data.positions || [];
      officePositions.value = pos.filter(p => p.position_group === 'office');
      coordinatorPositions.value = pos.filter(p => p.position_group === 'coordinator');

      // If missing default slots, fill them
      if (officePositions.value.length === 0) {
        const def = getDefaultPositions();
        officePositions.value = def.filter(p => p.position_group === 'office');
        coordinatorPositions.value = def.filter(p => p.position_group === 'coordinator');
      }

      showModal.value = true;
      return;
    }
  } catch (e) {}

  termForm.value = { ...term };
  const def = getDefaultPositions();
  officePositions.value = def.filter(p => p.position_group === 'office');
  coordinatorPositions.value = def.filter(p => p.position_group === 'coordinator');
  showModal.value = true;
}

// Watch Office HR to auto-sync Coordinator HR (Spec v0.3.4 Rule 1.3)
function syncHrCoordinators() {
  const officeHr = officePositions.value.find(p => p.position_key === 'hr');
  const coordHr = coordinatorPositions.value.find(p => p.position_key === 'hr');
  if (officeHr && coordHr) {
    coordHr.name_ar = officeHr.name_ar;
    coordHr.name_en = officeHr.name_en;
    coordHr.photo_path = officeHr.photo_path;
    coordHr.bio_ar = officeHr.bio_ar;
    coordHr.bio_en = officeHr.bio_en;
    coordHr.linkedin_url = officeHr.linkedin_url;
    coordHr.email_public = officeHr.email_public;
    coordHr.user_id = officeHr.user_id;
  }
}

function removePhoto(pos) {
  pos.photo_path = '';
  if (pos.position_key === 'hr') {
    syncHrCoordinators();
  }
}

function cropAndCompressImage(file, maxWidth = 600, maxHeight = 600, quality = 0.85) {
  return new Promise((resolve, reject) => {
    const reader = new FileReader();
    reader.onerror = reject;
    reader.onload = (e) => {
      const img = new Image();
      img.onerror = reject;
      img.onload = () => {
        const canvas = document.createElement('canvas');
        // Center crop to 1:1 square
        const size = Math.min(img.width, img.height);
        const targetW = Math.min(size, maxWidth);
        const targetH = Math.min(size, maxHeight);
        canvas.width = targetW;
        canvas.height = targetH;

        const ctx = canvas.getContext('2d');
        const startX = (img.width - size) / 2;
        const startY = (img.height - size) / 2;

        ctx.drawImage(img, startX, startY, size, size, 0, 0, targetW, targetH);

        canvas.toBlob((blob) => {
          if (blob) {
            resolve(blob);
          } else {
            resolve(file);
          }
        }, 'image/webp', quality);
      };
      img.src = e.target.result;
    };
    reader.readAsDataURL(file);
  });
}

async function handlePhotoUpload(event, pos) {
  const file = event.target.files?.[0];
  if (!file) return;

  pos._uploading = true;
  try {
    const compressedBlob = await cropAndCompressImage(file, 600, 600, 0.85);
    const formData = new FormData();
    const fileName = `member_${pos.position_key}_${Date.now()}.webp`;
    formData.append('file', compressedBlob, fileName);

    const token = authService.token.value || '';
    const res = await fetch(`./api.php?action=upload&folder=media&token=${encodeURIComponent(token)}`, {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${token}`
      },
      body: formData
    });

    const data = await res.json();
    if (res.ok && data.status === 'success') {
      pos.photo_path = data.url;
      if (pos.position_key === 'hr') {
        syncHrCoordinators();
      }
      emit('notify', props.lang === 'ar' ? 'تم رفع واقتصاص الصورة بنجاح' : 'Photo uploaded and cropped successfully', 'success');
    } else {
      emit('notify', data.message || (props.lang === 'ar' ? 'فشل رفع الصورة' : 'Upload failed'), 'error');
    }
  } catch (err) {
    emit('notify', 'خطأ أثناء الرفع: ' + err.message, 'error');
  } finally {
    pos._uploading = false;
    event.target.value = '';
  }
}

async function saveTerm() {
  syncHrCoordinators();
  saving.value = true;
  const token = authService.token.value || '';
  const method = isEditing.value ? 'PUT' : 'POST';
  const url = isEditing.value 
    ? `./api.php?action=office_terms&id=${termForm.value.id}&token=${encodeURIComponent(token)}` 
    : `./api.php?action=office_terms&token=${encodeURIComponent(token)}`;

  const allPositionsToSave = [...officePositions.value, ...coordinatorPositions.value];

  try {
    const res = await fetch(url, {
      method,
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${token}`
      },
      body: JSON.stringify({
        ...termForm.value,
        copy_from_id: copyFromTermId.value,
        positions: allPositionsToSave
      })
    });

    const data = await res.json();
    if (res.ok && data.status === 'success') {
      emit('notify', data.message || 'Saved successfully', 'success');
      showModal.value = false;
      await loadTerms();
    } else {
      emit('notify', data.message || 'Failed to save', 'error');
    }
  } catch (e) {
    emit('notify', 'Network error: ' + e.message, 'error');
  } finally {
    saving.value = false;
  }
}

function openActivateConfirm(term) {
  activatingTerm.value = term;
}

async function confirmActivate() {
  if (!activatingTerm.value) return;
  activating.value = true;
  const token = authService.token.value || '';

  try {
    const res = await fetch(`./api.php?action=activate_term&id=${activatingTerm.value.id}&token=${encodeURIComponent(token)}`, {
      method: 'POST',
      headers: {
        'Authorization': `Bearer ${token}`
      }
    });

    const data = await res.json();
    if (res.ok && data.status === 'success') {
      emit('notify', data.message || 'Term activated successfully', 'success');
      activatingTerm.value = null;
      await loadTerms();
    } else {
      emit('notify', data.message || 'Failed to activate', 'error');
    }
  } catch (e) {
    emit('notify', 'Error: ' + e.message, 'error');
  } finally {
    activating.value = false;
  }
}

async function deleteTerm(term) {
  if (!confirm(props.lang === 'ar' ? `هل أنت متأكد من حذف الدورة "${term.title}" وأرشيفها؟` : `Delete term "${term.title}"?`)) {
    return;
  }

  const token = authService.token.value || '';
  try {
    const res = await fetch(`./api.php?action=office_terms&id=${term.id}&token=${encodeURIComponent(token)}`, {
      method: 'DELETE',
      headers: { 'Authorization': `Bearer ${token}` }
    });
    const data = await res.json();
    if (res.ok && data.status === 'success') {
      emit('notify', data.message || 'Deleted successfully', 'success');
      await loadTerms();
    } else {
      emit('notify', data.message || 'Failed to delete', 'error');
    }
  } catch (e) {}
}

onMounted(() => {
  loadTerms();
});
</script>
