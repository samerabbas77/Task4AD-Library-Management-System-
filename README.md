+المهمة: بناء نظام إدارة مكتبة مع استعارة الكتب (Library Management System)
المتطلبات:
إعداد المشروع:
قم بإنشاء مشروع جديد باستخدام Laravel.
قم بإعداد قاعدة بيانات جديدة وربطها بالمشروع.
جداول قاعدة البيانات:
إنشاء الجداول التالية:
books:
id (مفتاح رئيسي)
title (اسم الكتاب)
author (اسم المؤلف)
description (وصف الكتاب)
published_at (تاريخ النشر)
created_at و updated_at (تلقائيين)
users:
id (مفتاح رئيسي)
name (اسم المستخدم)
email (البريد الإلكتروني)
password (كلمة المرور)
created_at و updated_at (تلقائيين)
borrow_:records:
id (مفتاح رئيسي)
book_id (معرّف الكتاب، مفتاح)
user_id (معرّف المستخدم، مفتاح)
borrowed_at (تاريخ الاستعارة)
due_date (تاريخ الإعادة)
returned_at (تاريخ الإرجاع)
created_at و updated_at (تلقائيين)
نظام الـ Auth:
قم بإعداد نظام ِAuth  باستخدام API tokens باستخدام حزمة JWT بحيث يتمكن المستخدمون من تسجيل الدخول وتسجيل الخروج باستخدام API.
قم بإنشاء صلاحيات بحيث يمكن للمستخدمين المسجلين فقط استعارة الكتب.
إنشاء CRUD:
Books CRUD: قم بإنشاء جميع عمليات الـ CRUD للكتب (إنشاء، عرض، تحديث، حذف).
Users CRUD: قم بإنشاء عمليات الـ CRUD للمستخدمين مع صلاحيات إدارية.
Borrow Records CRUD: قم بإنشاء CRUD لسجل الاستعارة بحيث يتمكن المستخدمون من استعارة وإرجاع الكتب.
تحدي الاستعارة:
 يحق للمستخدمين المسجلين فقط استعارة الكتب.
تحقق من توفر الكتاب قبل السماح بالاستعارة (لا يمكن استعارة الكتاب مرتين في نفس الوقت).
عند استعارة الكتاب، يجب تحديد تاريخ الإرجاع (تلقائيًا بعد 14 يومًا من تاريخ الاستعارة).
التأكد من صحة البيانات باستخدام Form Requests:
إنشاء Form Request لكل من الـ Books و Borrow Records:
BookFormRequest: يجب أن يحتوي على قواعد التحقق (Validation rules) المناسبة عند إضافة أو تحديث كتاب. على سبيل المثال، يجب التحقق من أن عنوان الكتاب title غير فارغ ومؤلف الكتاب author يجب أن يحتوي على أكثر من 3 حروف.
BorrowRecordFormRequest: يجب أن يحتوي على قواعد التحقق مثل التحقق من أن تاريخ الإعادة due_date ليس قبل تاريخ الاستعارة borrowed_at.
إدارة الأخطاء باستخدام failedValidation:
قم بتخصيص طريقة failedValidation في الـ Form Requests بحيث ترسل رسالة خطأ مخصصة إذا فشلت عملية التحقق. هذه الرسالة يجب أن تكون واضحة للمستخدم وتصف المشكلة بالتحديد.
تنفيذ عمليات بعد النجاح باستخدام passedValidation:
إذا نجحت عملية التحقق، استخدم طريقة passedValidation لتنفيذ عمليات معينة بعد التحقق. على سبيل المثال، يمكن استخدام passedValidation في BorrowRecordFormRequest لتسجيل وقت إضافي إذا كانت عملية التحقق ناجحة أو إرسال تنبيه. 
تخصيص أسماء الحقول باستخدام attributes:
استخدم خاصية attributes في الـ Form Request لتخصيص أسماء الحقول عند عرض رسائل الخطأ. على سبيل المثال، بدلاً من عرض "The title field is required"، يمكن تخصيصها لتظهر "اسم الكتاب مطلوب".
تخصيص رسائل التحقق باستخدام messages:
قم بتخصيص رسائل التحقق باستخدام خاصية messages في الـ Form Requests لتقديم رسائل مفهومة أكثر للمستخدم. على سبيل المثال، إذا كان هناك حقل معين يجب أن يحتوي على قيمة معينة، تأكد من أن رسالة الخطأ توضح هذا الشرط بشكل واضح.
التوثيق و التعليقات:
يجب توثيق كل الكود باستخدام التعليقات المناسبة (DocBlocks) لضمان سهولة فهم الكود لاحقًا.
التحدي الإضافي:
نظام تصفية الكتب:
قم بإنشاء واجهة API لفلترة الكتب بحيث يمكن للمستخدمين البحث عن كتب بناءً على أحد أو جميع العوامل التالية:
المؤلف: عرض الكتب لمؤلف معين.
التصنيف: إضافة نظام تصنيف للكتب (مثل: رواية، تقنية، تعليمية) ثم فلترة الكتب حسب التصنيف.
توفر الكتاب: فلترة الكتب المتاحة للاستعارة فقط.
نظام التقييم (Rating):
أضف نظام تقييم للكتب بحيث يمكن للمستخدمين تقييم الكتب التي قاموا باستعارتها.
إنشاء CRUD للتقييمات بحيث يمكن للمستخدمين إضافة، عرض، وتحديث تقييماتهم.
توضيحات إضافية:
يجب أن تحتوي كل واجهة API على ردود واضحة تشمل رسائل خطأ مناسبة عند الفشل.
يجب أن تكون كل العمليات مؤمنة بحيث لا يمكن تنفيذها إلا من قبل المستخدمين المصرح لهم.
*********************************************************************************************************************************************************
### README: Library Management System

**Objective**: Build a Library Management System with book borrowing functionality.

### Project Setup:
1. **Configure project:
     git clone https://github.com/samerabbas77/Task4AD-Library-Management-System-.git
    composer install
    cp .env.example .env
    php artisan key:generate
2.**configure Database:
   php artisan migrate --seed    

### Database Tables:
- **Books**: `id`, `title`, `author`, `description`, `published_at`, `created_at`, `updated_at`.
- **Users**: `id`, `name`, `email`, `password`, `created_at`, `updated_at`.
- **Borrow Records**: `id`, `book_id`, `user_id`, `borrowed_at`, `due_date`, `returned_at`, `created_at`, `updated_at`.

### Features:
- **Auth**: JWT-based authentication for login/logout.
- **Permissions**: Only registered users can borrow books.
- **CRUD Operations**:
  - **Books**: Create, Read, Update, Delete.
  - **Users**: Admin manages CRUD for users.
  - **Borrow Records**: Users can borrow/return books.

### Borrowing System:
- Only registered users can borrow.
- Ensure book availability.
- Set return date (default 14 days).

### Validation:
- **Form Requests**:
  - `BookFormRequest`: Validate book fields (e.g., `title` required, `author` length > 3).
  - `BorrowRecordFormRequest`: Validate borrow records (e.g., `due_date` > `borrowed_at`).
  
- **Custom Error Handling**:
  - `failedValidation`: Custom error messages.
  - `passedValidation`: Actions after successful validation.

### Additional Challenges:
- **Book Filtering**: Filter by author, category, or availability.
- **Rating System**: Users rate borrowed books, with CRUD operations for ratings.

### Documentation:
https://documenter.getpostman.com/view/34411360/2sAXjNXqdB

---

This setup ensures a secure, functional library management system with user permissions, book borrowing, and data validation.


