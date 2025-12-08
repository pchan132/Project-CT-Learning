-- สร้าง Production Database
CREATE DATABASE IF NOT EXISTS ct_learning_prod CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- สร้าง User สำหรับ Production (Change password!)
-- CREATE USER IF NOT EXISTS 'ct_learning_user'@'localhost' IDENTIFIED BY 'secure_password_hash';
-- GRANT ALL PRIVILEGES ON ct_learning_prod.* TO 'ct_learning_user'@'localhost';
-- FLUSH PRIVILEGES;

-- เพิ่ม Indexes สำหรับ Performance
-- Note: Run these AFTER migrations have created the tables
-- CREATE INDEX idx_enrollments_student_course ON enrollments(student_id, course_id);
-- CREATE INDEX idx_lesson_completions_student_lesson ON lesson_completions(student_id, lesson_id);
-- CREATE INDEX idx_quiz_attempts_user_quiz ON quiz_attempts(user_id, quiz_id);
