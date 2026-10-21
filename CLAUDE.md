# CLAUDE.md — Project Guidelines & Full Context for Claude Code

> ไฟล์สรุปบริบทโปรเจ็ค คำสั่ง แนวทางการทำงาน และประวัติการตัดสินใจทั้งหมด สำหรับใช้กับ Claude Code

---

## 📌 1. ภาพรวมโปรเจ็คและวิชา (Project & Course Overview)

- **ชื่อโครงงาน:** **Student Skill Matcher (ระบบจับคู่ทักษะนักเรียนกับสายการแข่งขัน)**
- **ชื่อทีม:** Skill Match Team
- **วิชา:** 31903-2008 การพัฒนาเว็บแอปพลิเคชันด้วย PHP (ภาคเรียน 2/2569)
- **หน่วยกิต:** 1-4-3 (ทฤษฎี 1 คาบ / ปฏิบัติ 4 คาบ / รวม 3 หน่วยกิต) | รวม 15 สัปดาห์ (75 คาบ)
- **ปัญหาที่ระบบแก้:** การคัดเลือกตัวแทนนักเรียนไปแข่งขันมักเลือกจากความคุ้นเคย ข้อมูลทักษะกระจัดกระจาย ทำให้คัดเลือกคนไม่ตรงกับเกณฑ์การแข่งขันจริง และนักเรียนขาดโอกาส ระบบนี้จึงเป็นศูนย์กลางบันทึกทักษะและคำนวณ Match อัตโนมัติ

---

## 👥 2. สมาชิกในทีมและบทบาท (Team Members & Roles)

ทำงานร่วมกันเป็นคู่ (Team of 2):

| สมาชิก | บทบาท | หน้าที่รับผิดชอบหลัก | GitHub User / Branch |
|---|---|---|---|
| **นายณัฐกานต์ บัวทุม** | **Developer A** | ฝั่งข้อมูลนักเรียน + ระบบคำนวณ Matching | `NatthakanIT` / `feature/pushA01` |
| **นางสาวนัฏฐนิชา บุญมั่น** | **Developer B** | ฝั่งรายการแข่งขัน + ระบบ Admin ครู | `69319080017-collab` / `feature/pushB02` |

### ข้อตกลงทีม (Team Charter Summary)
- **การนัดหมาย:** ทุกวันเสาร์ 19:00 - 21:00 น. ผ่าน Discord / Google Meet + กลุ่ม LINE
- **กรณีขาดเรียน:** สรุปงานใน LINE ภายในวันเรียน สมาชิกที่ขาดต้อง `git pull` โค้ดล่าสุดภายใน 24 ชม.
- **การตัดสินใจ:** ยึดความต้องการระบบและเวลาเป็นหลัก หากหาข้อสรุปไม่ได้ปรึกษาอาจารย์
- **กำหนดส่งงาน:** โค้ดต้องเสร็จและ Push ขึ้น GitHub ก่อนวันส่งจริงอย่างน้อย 2 วัน
- **Code Review:** ทดสอบโค้ดในเครื่องก่อน commit เสมอ เขียน commit message ให้สื่อความหมาย

---

## 🛠️ 3. Tech Stack & Environment

- **OS / Shell:** Windows (PowerShell)
- **Web Server:** XAMPP (Apache + MariaDB/MySQL)
  - DocumentRoot: `C:\xampp\htdocs\` หรือ `C:\xamp\htdocs\`
  - URL ทดสอบ: `http://localhost/partong/`
- **Backend:** PHP 8 (ใช้ PDO เท่านั้น **ห้ามใช้** `mysql_*` หรือ `mysqli_*`)
  - ช่วงสัปดาห์ 13: นำ Composer & Laravel มาใช้บางส่วน
- **Database:** MySQL / MariaDB (เริ่มใช้สัปดาห์ที่ 8 เป็นต้นไป)
- **Frontend:** Vanilla HTML5, CSS3, JavaScript (ไม่มี JS framework หนักๆ)
- **Version Control:** Git + GitHub
  - Remote Repository: `https://github.com/69319080017-collab/partong`
  - Main Branch: `main`
  - Working Branches: `feature/pushA01` (Dev A), `feature/pushB02` (Dev B)

---

## 🤖 4. นโยบายการใช้ AI ในวิชานี้ (AI Policy by Week)

| สัปดาห์ | ระดับ | กฎเกณฑ์ที่ AI และนักเรียนต้องปฏิบัติตาม |
|---|---|---|
| **สัปดาห์ 1–4** | **A1** | **ถามและอธิบายแนวคิดได้ แต่ห้ามเขียนโค้ดสำเร็จรูปให้ทั้งหมด** เน้นให้เด็กเข้าใจโครงสร้างไวยากรณ์ |
| **สัปดาห์ 5–14** | **A2** | **ใช้ AI ช่วยเขียนโค้ดได้** แต่นักเรียน**ต้องเข้าใจและอธิบายโค้ดได้ทุกบรรทัด** + มีบันทึก AI Log (Prompt / จุดที่ AI พลาด / วิธีแก้) |
| **สัปดาห์ 15** | **A0** | ❌ **สอบปฏิบัติรายบุคคล ห้ามใช้ AI เด็ดขาด** |

---

## 🏗️ 5. โครงสร้างสถาปัตยกรรมระบบ (Domain Model & DB Schema)

### Domain Entities & Relationship
```
[User] (role: student / teacher)
  │
  ├── [Student] ─── has many ─── [StudentSkill] (skill_id, score/level 1-5)
  │                                     │
  │                                     ▼ matches with
  └── [Teacher] ─── manages ─── [Competition] ─── has many ─── [Requirement] (skill_id, min_score)
```

### ตารางฐานข้อมูลเป้าหมาย (MySQL PDO)
1. `users` — `id`, `username`, `password_hash`, `role` (admin/teacher/student), `created_at`
2. `students` — `id`, `user_id`, `student_code`, `fullname`, `department`, `year_level`, `contact`
3. `skills` — `id`, `skill_name`, `category` (Web Frontend, Backend, Database, Network, UI/UX)
4. `student_skills` — `id`, `student_id`, `skill_id`, `proficiency_level` (1-5 หรือคะแนน 0-100)
5. `competitions` — `id`, `title`, `level` (วิทยาลัย/ภาค/ชาติ), `team_size`, `event_date`
6. `requirements` — `id`, `competition_id`, `skill_id`, `min_level`
7. `matches` / `team_selections` — บันทึกผลการจับคู่และสถานะการคัดเลือก

---

## 📂 6. โครงสร้างไดเรกทอรีใน Repository

```
c:\xamp\htdocs\partong/
├── CLAUDE.md              # ไฟล์นี้ (Context สำหรับ Claude Code)
├── lab1/                  # ใบงานที่ 1 (งานส่งสัปดาห์ที่ 1)
│   ├── hello.php          # แสดงข้อความทดสอบ PHP
│   ├── info.php           # ฟังก์ชัน phpinfo()
│   ├── index.php          # หน้าแรกโครงงาน (ตัวแปร $team, $topic, $problem, $members, nav)
│   ├── server.php         # ตารางแสดงค่า PHP_VERSION, SERVER_SOFTWARE, HTTP_HOST, Timezone
│   └── about.php          # รายการ $goals (ol + count($goals) + nav)
├── learn/                 # เอกสารรายวิชา 5 ไฟล์ (.docx)
├── student-skill-matcher/ # โครงสร้างระบบหลักของโครงงาน
│   ├── README.md          # เอกสารสรุปโปรเจ็คฉบับเต็ม
│   ├── AGENTS.md          # บริบทโปรเจ็คสำหรับ Agent ทั่วไป
│   └── (modules ในสัปดาห์ถัดๆ ไป: student/, admin/, includes/, api/)
```

---

## 📅 7. แผนงาน 15 สัปดาห์ (15-Week Roadmap)

- **Week 1 (A1):** Setup Web Server (XAMPP), php.ini, Git/GitHub, หน้า PHP แรก (`lab1/`) ✅ *เสร็จแล้ว*
- **Week 2 (A1):** ตัวแปร ชนิดข้อมูล ตัวดำเนินการ, คำนวณเบื้องต้น + Spec v1
- **Week 3 (A1):** If/Else, Loop, Array ทักษะ, กรองรายการข้อมูล + Flowchart
- **Week 4 (A1):** Function, การแยกไฟล์ `include/require`, ไฟล์ส่วนกลาง `header.php`, `footer.php`
- **Week 5 (A2):** Form POST/GET, Data Validation, ฟอร์มบันทึกทักษะ
- **Week 6 (A2):** File Upload, จัดการไฟล์รูปโปรไฟล์นักเรียน + Thumbnail
- **Week 7 (A2):** Cookie, Session, Authentication (ระบบ Login แยกบทบาท ครู/นักเรียน)
- **Week 8 (A2):** เชื่อมต่อฐานข้อมูล MySQL ด้วย PDO, สร้างตารางตาม ER Diagram
- **Week 9 (A2):** CRUD ข้อมูลครบวงจร (จัดการนักเรียน / ทักษะ / สายแข่ง) + บันทึก Bug Log
- **Week 10 (A2):** OOP พื้นฐาน (Class `Student`, `Skill`, `Competition`, Constructor, Method)
- **Week 11 (A2):** OOP Inheritance, ปรับปรุงโครงสร้างโฟลเดอร์ให้เป็นระเบียบ
- **Week 12 (A2):** RESTful API JSON Endpoint + Frontend `fetch()` Async
- **Week 13 (A2):** นำ PHP Framework (Laravel) มาประยุกต์ใช้บางส่วน
- **Week 14 (A2):** Deploy ขึ้น Production Server จริง + ทดสอบระบบ (Integration Test) + คู่มือ
- **Week 15 (A0):** 🧪 สอบปฏิบัติรายบุคคล (CRUD + PDO ห้ามใช้ AI)

---

## 💻 8. คำสั่งที่ใช้บ่อย (Common Commands)

### PowerShell บน Windows
*ห้ามใช้ `&&` ในคำสั่งเดียวบน Windows PowerShell ให้รันทีละคำสั่ง หรือคั่นด้วย `;`*

```powershell
# รันเช็คสถานะ Git
git status

# สลับ Branch ทำงาน
git checkout feature/pushA01

# ดึงโค้ดล่าสุดจาก remote
git pull origin feature/pushA01

# Commit และ Push
git add .
git commit -m "คำอธิบายการแก้ไข"
git push origin feature/pushA01
```

### การตั้งค่า PHP (php.ini) ที่ยืนยันแล้ว
- `display_errors = On`
- `error_reporting = E_ALL`
- `date.timezone = Asia/Bangkok`
- `upload_max_filesize = 8M`

---

## 🎯 9. แนวทางที่ Claude Code ต้องยึดถือ (Guidelines for Claude)

1. **ตอบเป็นภาษาไทยที่เป็นมิตร ชัดเจน ตรงไปตรงมา** ให้คำแนะนำที่เหมาะสมกับระดับนักศึกษา ปวส. สาขาเทคโนโลยีสารสนเทศ
2. **รักษาความสมบูรณ์ของโค้ด:** ใช้ PHP 8 สมัยใหม่ เชื่อมต่อฐานข้อมูลด้วย **PDO พร้อม Prepared Statements** เสมอ เพื่อความปลอดภัย ป้องกัน SQL Injection
3. **คำนึงถึงระดับ AI ประจำสัปดาห์:**
   - หากผู้ใช้ถามในช่วงสัปดาห์ 1–4 ให้เน้นอธิบายวิธีคิด บรรทัดสำคัญ ชี้แนะแนวทาง
   - หากผู้ใช้ถามในช่วงสัปดาห์ 5–14 สามารถเขียนโค้ดให้ได้ แต่ต้องอธิบายการทำงานทุกส่วนอย่างละเอียด เพื่อให้ผู้ใช้ตอบอาจารย์และจดบันทึก AI Log ได้
4. **การแบ่งงาน Dev A / Dev B:** ตระหนักเสมอว่านายณัฐกานต์คือ Dev A (ฝั่งนักเรียนและระบบคำนวณ Match) หากมีงานส่วนของ Dev B ให้เขียนเชื่อมต่อกันอย่างเป็นระบบโดยไม่กระทบสิทธิ์ของเพื่อนร่วมทีม
