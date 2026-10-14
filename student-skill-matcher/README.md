# Student Skill Matcher

> ระบบจับคู่ทักษะนักเรียนกับสายการแข่งขัน

## 📌 ภาพรวมโปรเจ็ค

**Student Skill Matcher** คือเว็บแอปพลิเคชัน PHP ที่ช่วยให้ครูและนักเรียนในวิทยาลัยอาชีวศึกษา สามารถบันทึกความถนัดด้านต่างๆ ของนักเรียน แล้วระบบจะจับคู่ (Match) กับสายการแข่งขันที่เหมาะสมที่สุด

### 🎯 ปัญหาที่ระบบนี้แก้

- ครูไม่มีข้อมูลกลางว่าใครเก่งด้านไหน ต้องนึกเอาเองเวลาคัดเลือกนักเรียนไปแข่ง
- การคัดเลือกไม่โปร่งใส ขึ้นอยู่กับความคุ้นเคยส่วนตัว ไม่ใช่ความสามารถจริง
- นักเรียนไม่รู้ว่าตัวเองเหมาะกับสายแข่งไหน และต้องพัฒนาทักษะอะไรเพิ่ม
- ข้อมูลกระจายอยู่ใน Excel / กระดาษ / Line ไม่มีที่รวมศูนย์

### ✅ ระบบทำอะไรได้

- บันทึกและประเมินทักษะด้านต่างๆ ของนักเรียน (Web Front-end, Database, Network ฯลฯ)
- จัดการรายการสายการแข่งขัน พร้อมกำหนดว่าต้องการทักษะอะไรบ้าง
- Match อัตโนมัติ: แสดงว่านักเรียนคนไหนเหมาะกับสายแข่งไหนมากที่สุด
- แยก Login ระหว่างครู (Admin) และนักเรียน

---

## 👥 ทีม

| บทบาท | ชื่อ | รับผิดชอบ |
|--------|------|-----------|
| Developer A | นายณัฐกานต์ บัวทุม | ฝั่งข้อมูลนักเรียน + ระบบ Match |
| Developer B | (ชื่อเพื่อน) | ฝั่งสายแข่ง + ระบบ Admin ครู |

### แบ่งงานตามสัปดาห์

#### Developer A รับผิดชอบ
- ฟอร์มกรอก/แก้ไขทักษะนักเรียน
- ระบบ Login ฝั่งนักเรียน
- ตาราง `students`, `skills` ใน Database
- Class `Student`, Class `Skill` (OOP)
- API endpoint สำหรับดึงข้อมูลทักษะ

#### Developer B รับผิดชอบ
- หน้า Admin สำหรับครู
- จัดการรายการสายแข่งขัน
- ระบบ Login ฝั่งครู
- ตาราง `competitions`, `requirements` ใน Database
- Class `Competition`, Class `Match` (OOP)
- Deploy ขึ้น Host จริง

#### งานทีม (ทำร่วมกัน)
- ออกแบบ Database (ER Diagram)
- ทดสอบระบบ + แก้บั๊ก
- เขียนคู่มือการใช้งาน

---

## 🛠️ Tech Stack

| หมวด | เทคโนโลยี |
|------|-----------|
| Backend | PHP 8 |
| Database | MySQL / MariaDB ผ่าน PDO |
| Frontend | HTML, CSS, JavaScript |
| Server | XAMPP (Apache) |
| Version Control | Git + GitHub |
| Editor | VS Code + PHP Intelephense |
| ทดสอบ API | Postman / Thunder Client |
| Framework (ช่วงท้าย) | Laravel |

---

## 📁 โครงสร้างโฟลเดอร์ (เป้าหมาย)

```
student-skill-matcher/
├── index.php          # หน้าแรก: แสดงข้อมูลโปรเจ็คและสมาชิก
├── hello.php          # ทดสอบ PHP ทำงาน
├── server.php         # แสดงข้อมูล Server
├── about.php          # เป้าหมายระบบ + เมนู
├── includes/          # ไฟล์ส่วนกลาง (header, footer, db)
├── student/           # ส่วนนักเรียน (Dev A)
│   ├── login.php
│   ├── profile.php
│   └── skills.php
├── admin/             # ส่วนครู Admin (Dev B)
│   ├── login.php
│   ├── competitions.php
│   └── match.php
├── api/               # REST API endpoints
│   ├── skills.php
│   └── match.php
└── assets/            # CSS, JS, รูปภาพ
```

---

## 🗃️ Database (เป้าหมาย)

```
students        — ข้อมูลนักเรียน
skills          — ทักษะและคะแนน (เชื่อมกับ students)
competitions    — รายการสายแข่งขัน
requirements    — ทักษะที่สายแข่งต้องการ (เชื่อมกับ competitions)
users           — บัญชีผู้ใช้ (ครู + นักเรียน)
```

---

## 📅 แผนการพัฒนา (15 สัปดาห์)

| สัปดาห์ | หัวข้อ | งานหลัก |
|---------|--------|---------|
| 1 | Setup + หน้าแรก | XAMPP, GitHub repo, index.php |
| 2 | ตัวแปร + ข้อมูล | แสดงข้อมูลนักเรียนจำลอง |
| 3 | Loop + Array | รายการทักษะ, กรองข้อมูล |
| 4 | Function + แยกไฟล์ | includes/, functions/ |
| 5 | Form + Validation | ฟอร์มกรอกทักษะ |
| 6 | File Upload | อัปโหลดรูปโปรไฟล์ |
| 7 | Session + Login | ระบบ Login ครู/นักเรียน |
| 8 | PDO + Database | เชื่อมต่อ MySQL จริง |
| 9 | CRUD | จัดการข้อมูลครบวงจร |
| 10 | OOP | Class Student, Skill, Competition |
| 11 | Inheritance | โครงสร้างโค้ดใหม่ |
| 12 | API + Async | fetch() + JSON endpoint |
| 13 | Laravel | ย้ายบางส่วนไป Framework |
| 14 | Deploy + คู่มือ | ขึ้น Host จริง |
| 15 | สอบปฏิบัติ | รายบุคคล ไม่ใช้ AI |

---

## 🤖 นโยบายการใช้ AI

| ระดับ | ความหมาย | สัปดาห์ |
|-------|---------|---------|
| A1 | ถามได้ แต่ห้ามให้เขียนโค้ดแทน | 1–4 |
| A2 | ใช้ช่วยโค้ดได้ **แต่ต้องบันทึก + อธิบายได้ทุกบรรทัด** | 5–14 |
| A0 | ห้ามใช้ AI เด็ดขาด | สัปดาห์ 15 (สอบ) |

> ทุกครั้งที่ใช้ AI ต้องบันทึก: ใช้ทำอะไร / prompt สำคัญ / AI ผิดตรงไหน / เราแก้เองอย่างไร

---

## 🚀 เริ่มต้นใช้งาน (Getting Started)

```bash
# 1. Clone repo
git clone https://github.com/YOUR_USERNAME/student-skill-matcher.git

# 2. วางในโฟลเดอร์ XAMPP
# C:\xampp\htdocs\student-skill-matcher\

# 3. เปิดใน Browser
# http://localhost/student-skill-matcher/
```

---

## 📋 วิชา

- รหัสวิชา: **31903-2008** การพัฒนาเว็บแอปพลิเคชันด้วย PHP
- ภาคเรียน: 2/2569
