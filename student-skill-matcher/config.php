<?php
// ค่าคงที่ของระบบ ใช้ร่วมกันทุกหน้า แก้ที่นี่ที่เดียว
const APP_NAME = 'Student Skill Matcher';
const APP_VERSION = '1.0.0';
const MATCH_HIGH_THRESHOLD = 80;   // % Match ระดับสูง (น้ำเงินเข้ม)
const MATCH_MID_THRESHOLD = 50;    // % Match ระดับกลาง (น้ำเงินฟ้า)
const TARGET_REPRESENTATIVES = 8;  // จำนวนตัวแทนเป้าหมายที่ต้องการส่งแข่งทั้งหมด (คน)
const MAX_SKILL_LEVEL = 5;         // ระดับคะแนนทักษะสูงสุด

date_default_timezone_set('Asia/Bangkok');
