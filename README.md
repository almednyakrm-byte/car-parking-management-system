# نظام إدارة مواقف سيارات (حجز، دفع، تتبع)
==========================

### Overview & Project Purpose

نظام إدارة مواقف سيارات هو تطبيق إلكتروني يهدف إلى تسهيل عملية حجز مواقف السيارات، وتتبع الحجز، ودفع الرسوم. يهدف هذا النظام إلى توفير تجربة مريحة للمستخدمين، وتحسين كفاءة إدارة المواقف.

### Project Structure Mapping


.
├── docker-compose.yml
├── docker/
│   ├── Dockerfile
│   └── ...
├── src/
│   ├── app/
│   │   ├── controllers/
│   │   ├── models/
│   │   ├── services/
│   │   └── ...
│   ├── config/
│   │   ├── database.php
│   │   └── ...
│   ├── public/
│   │   ├── index.php
│   │   └── ...
│   └── ...
├── tests/
│   ├── unit/
│   │   ├── controllers/
│   │   └── ...
│   └── ...
├── .env
└── README.md


### Step-by-Step Instructions for Running the Environment

1. **Install Docker and Docker Compose**:
   - قم بتحميل وتثبيت Docker و Docker Compose من الموقع الرسمي.
   - تأكد من تشغيل Docker و Docker Compose.

2. **تكوين البيئة**:
   - افتح الملف `.env` و قم بتعديل قيم البيانات إلى قيمك الخاصة.

3. **تشغيل النظام**:
   - افتح ترمينال و انتقل إلى مجلد المشروع.
   - قم بتشغيل الأمر `docker-compose up -d` لتشغيل النظام في الخلفية.

4. **تأكيد تشغيل النظام**:
   - افتح ترمينال و انتقل إلى مجلد المشروع.
   - قم بتشغيل الأمر `docker-compose exec app php artisan migrate` لتنفيذ التغييرات على قاعدة البيانات.
   - قم بتشغيل الأمر `docker-compose exec app php artisan db:seed` لملء قاعدة البيانات بالبيانات المثبتة.

### Modules, Tables, and Roles

- **Modules**:
  - حجز مواقف السيارات
  - تتبع الحجز
  - دفع الرسوم

- **Tables**:
  - `parking_spaces`: تتبع مواقف السيارات
  - `bookings`: تتبع الحجز
  - `payments`: تتبع الدفع

- **Roles**:
  - `admin`: يمتلك صلاحيات إدارية كاملة
  - `user`: يمتلك صلاحيات المستخدم العادي

### Contact Developer Details

- **اسم المطور**: [اسمك]
- **بريد إلكتروني**: [بريدك الإلكتروني]
- **رقم هاتف**: [رقم هاتفك]
- **لينك لبروفايل المطور**: [لينك لبروفايلك]

---

## 📧 للتواصل (Contact)
almednyakrm@gmail.com
