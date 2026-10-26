# مواصفات نموذج التطوع النهائي
### IEEE AAU Student Branch — Volunteer Application Form Specification

> **الإصدار:** 0.3.3 — **يحل محل v0.3.2** (بعد اعتماد الهيكل الجديد للنموذج)
> **الغرض:** نموذج تطوع ديناميكي: صفحتان ثابتتان لكل المتقدمين، ثم اختيار الفريق، ثم أسئلة الفريق المختار فقط.

---

## 1. ما الذي تغيّر عن v0.3.2؟

| التغيير | التفصيل |
|---|---|
| هيكل جديد معتمد | صفحة بيانات شخصية + صفحة أسئلة عامة + اختيار الفريق + أسئلة الفريق |
| **8 فرق** | Media · Technical · Magazine · Program · PR · **HR (جديد)** · Presenters · Speaker |
| سؤال ثابت لكل الفرق | «لماذا نختارك من بين كل المتقدمين؟» |
| أسئلة البحث العلمي | أُلحقت بفريق **Magazine** |
| بيانات شخصية موسَّعة | الاسم الرباعي، الجامعة، الكلية، التخصص، الدفعة، الواتساب |
| إزالة التكرار | حُذفت أسئلة الفرق التي صارت مغطاة في الصفحة العامة (القسم 8) |
| أنواع أسئلة Technical | حُسمت بقوائم مستويات (القسم 6.2) |

---

## 2. مسار التقديم

```
1. الشروط والأحكام (إلزامية)
2. الصفحة الأولى: البيانات الشخصية
3. الصفحة الثانية: الأسئلة العامة
4. الصفحة الثالثة: اختيار الفريق  (+ التخصص إن كان Media)
5. أسئلة الفريق المختار  (+ السؤال الثابت لكل الفرق)
6. المراجعة والإرسال
```

- يظهر شريط تقدم أعلى الشاشة، وتُحفظ الإجابات **تلقائياً كمسودة**.
- تغيير الفريق قبل الإرسال يستبدل أسئلة الفريق السابق مع تنبيه بحذف إجاباتها.
- يعمل بالكامل على الهاتف (Mobile-First)، وبالعربية والإنجليزية.

---

## 3. الصفحة الأولى — البيانات الشخصية

| # | الحقل | التسمية الإنجليزية | النوع | إلزامي | ملاحظات |
|---|---|---|---|:---:|---|
| 1 | الاسم الرباعي | Full Name (4 parts) | نص | ✅ | التحقق من وجود 4 كلمات على الأقل |
| 2 | الجامعة | University | قائمة + «أخرى» | ✅ | القيمة الافتراضية: جامعة الزعيم الأزهري، والقائمة تُدار من لوحة التحكم |
| 3 | الكلية | College | قائمة تابعة للجامعة | ✅ | |
| 4 | التخصص | Major / Department | قائمة تابعة للكلية | ✅ | |
| 5 | الدفعة | Batch | قائمة | ✅ | تُدار من لوحة التحكم (مثل 25، 26، 27 ...) |
| 6 | البريد الإلكتروني | Email | بريد | ✅ | يُملأ من الحساب، وقابل للتعديل |
| 7 | رقم الهاتف | Phone Number | هاتف | ✅ | صيغة دولية (+249 ...) |
| 8 | رقم الواتساب | WhatsApp Number | هاتف | ✅ | خيار «نفس رقم الهاتف» لتوفير الإدخال |

---

## 4. الصفحة الثانية — الأسئلة العامة

| # | السؤال (English) | العربية | النوع | إلزامي |
|---|---|---|---|:---:|
| 1 | What do you know about IEEE? | ماذا تعرف عن IEEE؟ | نص طويل | ✅ |
| 2 | Have you attended any event of our student branch IEEE-AAU? If yes, mention the event. | هل سبق لك حضور أي حدث لفرعنا الطلابي IEEE-AAU؟ إن كانت الإجابة نعم فاذكر الحدث. | Yes/No + نص يظهر عند «Yes» | ✅ |
| 3 | Why do you want to join us? | لماذا تريد الانضمام إلينا؟ | نص طويل | ✅ |
| 4 | Rate your commitment to meetings from 1 to 10 | قيّم مدى التزامك بالاجتماعات من 1 إلى 10 | تقييم 1–10 | ✅ |
| 5 | Do you prefer online or offline meetings? | هل تفضّل الاجتماعات أونلاين أم أوفلاين؟ | اختيار: Online / Offline / **كلاهما** | ✅ |
| 6 | Have you volunteered in a team at any organization? If yes, mention the organization and your role. | هل سبق لك التطوع ضمن فريق في جهة ما؟ إن كانت الإجابة نعم فاذكر الجهة ودورك. | Yes/No + نص يظهر عند «Yes» | ✅ |
| 7 | Rate your English level | قيّم مستواك في اللغة الإنجليزية | اختيار: **Beginner · Intermediate · Advanced · Fluent** (مبتدئ، متوسط، متقدم، متقن) | ✅ |

> 💡 أضفنا خيار «كلاهما» في سؤال 5 لأن بعض المتقدمين لا يفضّلون أحدهما، ويمكن حذفه إن رغبتم باختيار حاد.

---

## 5. الصفحة الثالثة — اختيار الفريق

**Which team would you like to join?** — في أي فريق ترغب بالانضمام؟ (اختيار واحد، إلزامي)

| # | الفريق | ملاحظة |
|---|---|---|
| 1 | **Media** | يتبعه اختيار تخصص (انظر القسم 7.1) |
| 2 | **Technical** | |
| 3 | **Magazine** | |
| 4 | **Program** | |
| 5 | **PR** (Public Relations) | |
| 6 | **HR** | جديد |
| 7 | **Presenters** | |
| 8 | **Speaker** | |

- تظهر بجانب كل فريق **بطاقة مختصرة بمهامه** وزر «تفاصيل الفريق» (من صفحة «الفرق» في الموقع)، ويُلغى الاعتماد على ملف PDF خارجي.

### السؤال الثابت لكل الفرق (يظهر في نهاية أسئلة كل فريق)

| السؤال | العربية | النوع | إلزامي |
|---|---|---|:---:|
| **Why should we choose you among all applicants?** | لماذا نختارك من بين كل المتقدمين؟ | نص طويل | ✅ |

> يُخزَّن كسؤال واحد على مستوى كل الفرق (`scope = all_teams`)، فتعديله من لوحة التحكم يُطبَّق على الفرق كلها دفعة واحدة.

---

## 6. أسئلة الفرق

**مفاتيح:** «Yes/No + نص» = يظهر حقل التفاصيل عند اختيار Yes فقط.
**مقياس التقييم 1–10** = أزرار أرقام كبيرة قابلة للمس (أو نجوم) على الهاتف.
كل فريق ينتهي بالسؤال الثابت (القسم 5).

### 6.1 فريق Program

| # | السؤال | العربية | النوع |
|---|---|---|---|
| 1 | Have you had experience working in this team? | هل عملت سابقاً في هذا الفريق؟ | Yes/No + نص |
| 2 | Why did you choose this team? | لماذا اخترت هذا الفريق؟ | نص طويل |
| 3 | If you encounter a problem and want to solve it, what steps will you follow? | إذا واجهتك مشكلة وأردت حلّها، فما الخطوات التي ستتبعها؟ | نص طويل |
| 4 | What steps will you follow to organize an event or conference? | ما الخطوات التي ستتبعها لتنظيم فعالية أو مؤتمر؟ | نص طويل |
| 5 | Rate your teamwork | قيّم عملك ضمن فريق | تقييم 1–10 |
| 6 | Rate your communication skills | قيّم مهارات التواصل لديك | تقييم 1–10 |

### 6.2 فريق Technical

| # | السؤال | العربية | النوع (القرار المعتمد) |
|---|---|---|---|
| 1 | Have you had experience working in this team? | هل عملت سابقاً في هذا الفريق؟ | Yes/No + نص |
| 2 | Why did you choose this team? | لماذا اخترت هذا الفريق؟ | نص طويل |
| 3 | Can you use technical equipment such as a projector, microphone, and sound system? | هل تستطيع استخدام الأجهزة التقنية كالبروجكتر والميكروفون ونظام الصوت؟ | اختيار: **Yes / Partially / No** + نص اختياري يصف خبرته |
| 4 | Your knowledge of Microsoft Word | مستوى معرفتك بـ Microsoft Word | مستوى: Beginner · Intermediate · Advanced · Fluent |
| 5 | Your knowledge of Microsoft PowerPoint | مستوى معرفتك بـ Microsoft PowerPoint | مستوى: Beginner · Intermediate · Advanced · Fluent |
| 6 | Rate your teamwork | قيّم عملك ضمن فريق | تقييم 1–10 |
| 7 | Rate your communication skills | قيّم مهارات التواصل لديك | تقييم 1–10 |
| 8 | Rate your work under pressure | قيّم عملك تحت الضغط | تقييم 1–10 |

> **سبب اختيار القوائم بدل النص الحر:** تسهّل الفرز والتصفية («أظهر الأعلى مستوى في PowerPoint») وتُظهر نفس المقياس المستخدم في مستوى الإنجليزية، فتبقى التجربة متسقة.

### 6.3 فريق Speaker

| # | السؤال | العربية | النوع |
|---|---|---|---|
| 1 | Have you had experience working in this team? | هل عملت سابقاً في هذا الفريق؟ | Yes/No + نص |
| 2 | Why did you choose this team? | لماذا اخترت هذا الفريق؟ | نص طويل |
| 3 | How can you search for the right speaker and communicate with him/her? | كيف تبحث عن المتحدث المناسب وتتواصل معه؟ | نص طويل |
| 4 | Rate your teamwork | قيّم عملك ضمن فريق | تقييم 1–10 |
| 5 | Rate your communication skills | قيّم مهارات التواصل لديك | تقييم 1–10 |

### 6.4 فريق Presenters

| # | السؤال | العربية | النوع |
|---|---|---|---|
| 1 | Have you had experience working in this team? | هل عملت سابقاً في هذا الفريق؟ | Yes/No + نص |
| 2 | Why did you choose this team? | لماذا اخترت هذا الفريق؟ | نص طويل |
| 3 | Rate your teamwork | قيّم عملك ضمن فريق | تقييم 1–10 |
| 4 | Rate your communication skills | قيّم مهارات التواصل لديك | تقييم 1–10 |

### 6.5 فريق PR (Public Relations)

| # | السؤال | العربية | النوع |
|---|---|---|---|
| 1 | Do you have any previous experience in public relations? If yes, mention it. | هل لديك خبرة سابقة في العلاقات العامة؟ إن وجدت فاذكرها. | Yes/No + نص |
| 2 | In your opinion, what are the most important characteristics that the public relations team should have? | برأيك، ما أهم الصفات التي يجب توافرها في فريق العلاقات العامة؟ | نص طويل |

### 6.6 فريق Magazine (المجلة)

| # | السؤال | العربية | النوع |
|---|---|---|---|
| 1 | From 1–10, assess your scientific writing | قيّم كتابتك العلمية من 1 إلى 10 | تقييم 1–10 |
| 2 | In less than 100 words, write your vision for the chapter's magazine | في أقل من 100 كلمة، اكتب رؤيتك لمجلة الفرع | نص بحد أقصى **100 كلمة** (عدّاد كلمات مباشر) |
| 3 | If you were given the opportunity to research any topic of your choice, what would it be, and how would you approach it? | لو أُتيحت لك فرصة بحث أي موضوع تختاره، فما هو وكيف ستتناوله؟ | نص طويل |
| 4 | What recent scientific breakthrough or technological advancement has fascinated you the most, and why? | ما أحدث إنجاز علمي أو تقني أثار إعجابك أكثر ولماذا؟ | نص طويل |
| 5 | Have you participated in any research projects or scientific competitions before? | هل شاركت سابقاً في مشاريع بحثية أو مسابقات علمية؟ | Yes/No + نص |

> 💡 اقتراح (غير مطلوب): سؤال اختياري برفع **عيّنة من كتاباتك** أو رابط مقال سابق، لأنه أصدق دليل على مستوى الكتابة العلمية.

### 6.7 فريق HR — ⚠️ أسئلة مقترحة (للاعتماد)

لم تُرسل أسئلة HR، وهذه مسودة مقترحة تناسب طبيعة عمل الفريق:

| # | السؤال | العربية | النوع |
|---|---|---|---|
| 1 | Have you had experience working in this team or in organizing/managing people? | هل لديك خبرة سابقة في هذا الفريق أو في تنظيم الأفراد وإدارتهم؟ | Yes/No + نص |
| 2 | Why did you choose this team? | لماذا اخترت هذا الفريق؟ | نص طويل |
| 3 | If two team members had a conflict, how would you handle it? | لو حدث خلاف بين عضوين في الفريق، فكيف ستتعامل معه؟ | نص طويل |
| 4 | How would you motivate a volunteer whose commitment has dropped? | كيف ستحفّز متطوعاً انخفض التزامه؟ | نص طويل |
| 5 | Rate your organizational skills | قيّم مهاراتك التنظيمية | تقييم 1–10 |
| 6 | Rate your communication skills | قيّم مهارات التواصل لديك | تقييم 1–10 |

---

## 7. فريق Media — التخصصات الفرعية

### 7.1 اختيار التخصص (إلزامي)
**Choose your specialty** — اختر تخصصك:
`Graphic Designer` · `Photographer` · `Content Writer` · `Video Editor`

ثم يعرض النموذج أسئلة التخصص المختار، وبعدها السؤال الثابت لكل الفرق.

### 7.2 Graphic Designer

| # | السؤال | العربية | النوع |
|---|---|---|---|
| 1 | Your knowledge of the program (Illustrator or Photoshop) | مدى معرفتك بالبرنامج (Illustrator أو Photoshop) | اختيار متعدد: Photoshop · Illustrator · أخرى (نص) |
| 2 | Upload your work | ارفع أعمالك | رفع ملفات **أو رابط** (Behance/Drive/Instagram) |
| 3 | Which device do you use for design: phone or laptop? | ما الجهاز الذي تصمم عليه: هاتف أم لابتوب؟ | اختيار: Phone / Laptop / كلاهما |
| 4 | How long have you been designing? | منذ متى تصمم؟ | نص قصير |
| 5 | Rate yourself in designing | قيّم نفسك في التصميم | تقييم 1–10 |

### 7.3 Content Writer

| # | السؤال | العربية | النوع |
|---|---|---|---|
| 1 | Upload your work — give an example of your writing | ارفع أعمالك — اذكر مثالاً من كتاباتك | رفع ملف أو لصق نص أو رابط |
| 2 | How long have you been writing content? | منذ متى تكتب المحتوى؟ | نص قصير |
| 3 | Rate yourself in content writing | قيّم نفسك في كتابة المحتوى | تقييم 1–10 |

### 7.4 Video Editor

| # | السؤال | العربية | النوع |
|---|---|---|---|
| 1 | How long have you been a video editor? | منذ متى تعمل في المونتاج؟ | نص قصير |
| 2 | Prepare videos using Premiere or After Effects and give us examples of your work | حضّر فيديوهات باستخدام Premiere أو After Effects وأرفق أمثلة من أعمالك | **رابط** (YouTube/Drive) أو رفع ملف |
| 3 | Rate yourself in video editing | قيّم نفسك في المونتاج | تقييم 1–10 |

### 7.5 Photographer

| # | السؤال | العربية | النوع |
|---|---|---|---|
| 1 | Upload your work (examples) | ارفع أمثلة من أعمالك | رفع صور أو رابط |
| 2 | How long have you been photographing? | منذ متى تمارس التصوير؟ | نص قصير |
| 3 | Rate yourself in photographing | قيّم نفسك في التصوير | تقييم 1–10 |

---

## 8. الأسئلة المحذوفة لإزالة التكرار (قابلة للاسترجاع)

| السؤال القديم في الفورم | سبب الحذف |
|---|---|
| Rate your commitment (Program, Speaker, Technical, Presenters) | مغطى بسؤال «الالتزام بالاجتماعات» في الصفحة العامة (رقم 4) |
| Rate your English language (Speaker, Presenters) | مغطى بسؤال مستوى الإنجليزية (رقم 7) |
| Your Batch (Technical) | صار حقلاً عاماً في الصفحة الأولى |
| Why do you want to join us? (PR) | مغطى بسؤال رقم 3 في الصفحة العامة |
| Why should we choose you? (PR) | صار سؤالاً ثابتاً لكل الفرق |
| Would you attend all the meetings? Yes/No/As possible | استُبدل بسؤال الالتزام 1–10 وتفضيل نوع الاجتماعات |

> إن أردتم الإبقاء على تقييم اللغة الإنجليزية بمقياس 1–10 لفريقي Speaker وPresenters تحديداً، يمكن إضافته كسؤال خاص بهما دون تغيير الهيكل.

---

## 9. التصميم التقني (Laravel)

### 9.1 الجداول

| الجدول | الأعمدة الأساسية |
|---|---|
| `recruitment_campaigns` | id, title, opens_at, closes_at, status, terms_version_id |
| `teams` | id, name_en, name_ar, description, tasks, is_active, order |
| `team_specialties` | id, team_id, name_en, name_ar |
| `questions` | id, **scope** (`personal` / `general` / `team` / `specialty` / `all_teams`), team_id?, specialty_id?, label_en, label_ar, type, required, options (JSON), conditional (JSON), validation (JSON), order, is_active |
| `applications` | id, user_id, campaign_id, team_id, specialty_id?, status, terms_version_id, terms_accepted_at, submitted_at |
| `application_answers` | id, application_id, question_id, **question_label_snapshot**, value (JSON), file_path? |
| `interviews` | id, application_id, scheduled_at, interviewer_id, notes, result |
| `universities` / `colleges` / `majors` / `batches` | قوائم الصفحة الأولى، تُدار من لوحة التحكم |

### 9.2 أنواع الأسئلة
`short_text` · `long_text` · `single_choice` · `multi_choice` · `yes_no_detail` · `rating_1_10` · `level_4` (مبتدئ → متقن) · `file_or_link` · `email` · `phone` · `limited_words` (حد أقصى للكلمات)

### 9.3 القواعد
- تظهر أسئلة الفريق المختار + أسئلة `all_teams` فقط، ويتحقق الـ Back-end من الإلزامية.
- طلب واحد لكل مستخدم في كل حملة، ويمكن تعديله قبل الإغلاق.
- حفظ نص السؤال وقت التقديم (`question_label_snapshot`) حتى لا تتأثر الطلبات القديمة بأي تعديل.
- الأسئلة المعطّلة لا تظهر في الجديد وتبقى في القديم.
- الحملة تُغلق تلقائياً في `closes_at` وتظهر رسالة «التقديم مغلق».

### 9.4 الصلاحيات

| الدور | يدير |
|---|---|
| أعضاء المكتب | كل الأسئلة والفرق والحملات |
| HR | الصفحتان 1 و2، الحملات، مراجعة الطلبات والمعاينات، وأسئلة فريق HR |
| منسق كل فريق | أسئلة فريقه + عرض طلبات فريقه فقط |
| منسق الإعلام | أسئلة Media وتخصصاتها |
| منسق المجلة | أسئلة Magazine |

> السؤال الثابت `all_teams` يعدّله **أعضاء المكتب وHR** فقط.

---

## 10. مراجعة الطلبات والتصدير

- صفحة طلب واحدة: بيانات شخصية + أسئلة عامة + إجابات الفريق + الملفات والروابط.
- تصفية: الفريق، التخصص، الجامعة، الكلية، الدفعة، الحالة، مستوى الإنجليزية، تقييم الالتزام.
- فرز بالتقييمات الذاتية، مع درجة المراجع وملاحظاته الداخلية (لا تظهر للمتقدم).
- **حالات الطلب:** `جديد ← قيد الفرز ← مرشح للمعاينة ← معاينة مجدولة ← مقبول / مرفوض`.
- تصدير Excel لكل فريق بأعمدة أسئلته، وتصدير شامل بالأسئلة العامة.
- عند القبول يُنشأ سجل **عضو** تلقائياً مرتبط بالفريق (حصر العضويات عند HR).

## 11. رفع الملفات

- صور (JPG/PNG/WebP) وPDF ونصوص، بحد أقصى 10 MB للملف و5 ملفات.
- **رابط بديل** للأعمال الكبيرة (فيديو) بسبب ضعف الإنترنت.
- فحص أمني للملفات وتخزينها خارج المجلد العام.

## 12. معايير القبول

- [ ] اختيار فريق ما يعرض أسئلته مع السؤال الثابت فقط.
- [ ] Media يطلب التخصص أولاً ثم يعرض أسئلة التخصص.
- [ ] حقول التفاصيل (عند Yes) تظهر وتُلزم عند الاختيار فقط.
- [ ] لا يمكن الإرسال مع نقص أي سؤال إلزامي.
- [ ] نص رؤية المجلة يرفض ما يزيد عن 100 كلمة.
- [ ] تعديل السؤال الثابت يظهر في الفرق الثمانية معاً.
- [ ] تعديل أي سؤال لا يغيّر الطلبات القديمة.
- [ ] منسق الفريق يرى طلبات فريقه فقط.
- [ ] الصفحات كلها سليمة على عرض 320px وفي الوضع الليلي وبالعربية RTL.

## 13. افتراضات وقرارات للمراجعة

| # | الافتراض | يمكن تغييره؟ |
|---|---|---|
| 1 | اختيار **فريق واحد** فقط (كما في الفورم الحالي) | نعم، يمكن إضافة «خيار ثانٍ اختياري» بدون أسئلة إضافية |
| 2 | أسئلة **HR** مسودة مقترحة منا | تحتاج اعتمادكم أو استبدالها بأسئلتكم |
| 3 | حذف الأسئلة المكررة (القسم 8) | قابل للاسترجاع |
| 4 | خيار «كلاهما» في تفضيل الاجتماعات | يمكن حذفه |
| 5 | قوائم المستويات الأربعة لأسئلة Technical | محسوم، ويمكن تحويلها لنص حر |
| 6 | طلب الانضمام يتطلب **حساباً** (v0.3.1) | موصى به |

---

**IEEE AAU Student Branch — Volunteer Form Specification v0.3.3**
