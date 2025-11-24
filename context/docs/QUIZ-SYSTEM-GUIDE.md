# 📝 Quiz System - Developer Guide

## ระบบแบบทดสอบ (Quiz System)

เอกสารนี้อธิบายการทำงานของระบบ Quiz แบบละเอียด

---

## 🗂️ โครงสร้างฐานข้อมูล

### 1. Table: `quizzes`
```sql
- id (PK)
- module_id (FK -> modules.id)
- title (ชื่อแบบทดสอบ)
- description (รายละเอียด)
- passing_score (คะแนนผ่าน, default: 80)
- time_limit (เวลาจำกัด นาที, nullable)
- timestamps
```

### 2. Table: `questions`
```sql
- id (PK)
- quiz_id (FK -> quizzes.id)
- question_text (คำถาม)
- order (ลำดับ)
- timestamps
```

### 3. Table: `answers`
```sql
- id (PK)
- question_id (FK -> questions.id)
- answer_text (ข้อความตัวเลือก)
- is_correct (ถูกหรือผิด, boolean)
- timestamps
```

### 4. Table: `quiz_attempts`
```sql
- id (PK)
- quiz_id (FK -> quizzes.id)
- student_id (FK -> users.id)
- score (คะแนนที่ได้ %)
- passed (ผ่านหรือไม่)
- answers (JSON - บันทึกคำตอบทั้งหมด)
- started_at (เวลาเริ่มทำ)
- completed_at (เวลาเสร็จ)
- timestamps
```

---

## 📋 Models & Relationships

### Quiz Model (`app/Models/Quiz.php`)

```php
// Relationships
- belongsTo(Module)
- hasMany(Question)
- hasMany(QuizAttempt)

// Methods
- hasPassedByStudent($studentId)  // ตรวจสอบว่า student ผ่านหรือไม่
- getBestAttemptForStudent($studentId)  // ดึงครั้งที่ทำได้คะแนนสูงสุด
```

### Question Model (`app/Models/Question.php`)

```php
// Relationships
- belongsTo(Quiz)
- hasMany(Answer)

// Methods
- getCorrectAnswerAttribute()  // ดึงคำตอบที่ถูก
- isCorrectAnswer($answerId)  // ตรวจสอบว่าคำตอบนั้นถูกหรือไม่
```

### Answer Model (`app/Models/Answer.php`)

```php
// Relationships
- belongsTo(Question)

// Fields
- is_correct (boolean)
```

### QuizAttempt Model (`app/Models/QuizAttempt.php`)

```php
// Relationships
- belongsTo(Quiz)
- belongsTo(User, 'student_id')

// Methods
- getDurationAttribute()  // คำนวณระยะเวลาทำ
- getFormattedScoreAttribute()  // แสดงคะแนนเป็น %
```

---

## 🎯 Controllers

### Teacher/QuizController.php

#### 1. index(Module $module)
แสดงรายการ Quiz ทั้งหมดใน Module

#### 2. create(Module $module)
แสดงฟอร์มสร้าง Quiz

#### 3. store(Request $request, Module $module)
บันทึก Quiz ใหม่
```php
Validation:
- title: required|string|max:255
- description: nullable|string
- passing_score: required|integer|min:0|max:100
- time_limit: nullable|integer|min:1
```

#### 4. show(Module $module, Quiz $quiz)
แสดง Quiz พร้อมคำถามและผลลัพธ์ของนักเรียน

#### 5. edit(Module $module, Quiz $quiz)
แสดงฟอร์มแก้ไข Quiz

#### 6. update(Request $request, Module $module, Quiz $quiz)
อัพเดท Quiz

#### 7. destroy(Module $module, Quiz $quiz)
ลบ Quiz

---

### Teacher/QuestionController.php

#### 1. create($courseId, $moduleId, Quiz $quiz)
แสดงหน้าเพิ่มคำถาม (สามารถเพิ่มหลายคำถามในหน้าเดียว)

#### 2. store(Request $request, $courseId, $moduleId, Quiz $quiz)
บันทึกคำถามและคำตอบ
```php
Validation:
- question_text: required|string
- answers: required|array|min:2  // ต้องมีอย่างน้อย 2 ตัวเลือก
- answers.*.text: required|string
- correct_answer: required|integer|min:0  // index ของคำตอบที่ถูก
```

**Logic:**
1. สร้าง Question
2. Loop สร้าง Answers โดยตั้ง is_correct = true เฉพาะตัวที่เลือก

#### 3. edit($courseId, $moduleId, Quiz $quiz, Question $question)
แสดงฟอร์มแก้ไขคำถาม

#### 4. update(Request $request, $courseId, $moduleId, Quiz $quiz, Question $question)
อัพเดทคำถาม
- ลบ answers เดิมทั้งหมด
- สร้าง answers ใหม่

#### 5. destroy($courseId, $moduleId, Quiz $quiz, Question $question)
ลบคำถาม

---

### Student/QuizController.php

#### 1. show(Quiz $quiz)
แสดงข้อมูล Quiz ให้ student ดู
- ตรวจสอบว่าผ่านแล้วหรือไม่
- แสดงคะแนนดีที่สุด

#### 2. start(Quiz $quiz)
เริ่มทำ Quiz (แสดงคำถามทั้งหมด)

#### 3. submit(Request $request, Quiz $quiz)
ส่งคำตอบ
```php
Validation:
- answers: required|array  // [question_id => answer_id]
- started_at: required|date
```

**Logic การคำนวณคะแนน:**
```php
$correctAnswers = 0;
$totalQuestions = $quiz->questions->count();

foreach ($quiz->questions as $question) {
    $submittedAnswerId = $answers[$question->id] ?? null;
    
    if ($submittedAnswerId && $question->isCorrectAnswer($submittedAnswerId)) {
        $correctAnswers++;
    }
}

$score = round(($correctAnswers / $totalQuestions) * 100);
$passed = $score >= $quiz->passing_score;
```

#### 4. result(QuizAttempt $attempt)
แสดงผลคะแนน
- แสดงคำตอบที่เลือก vs คำตอบที่ถูก
- แสดงสถิติ (ใช้เวลา, คะแนน, ผ่าน/ไม่ผ่าน)

---

## 🛣️ Routes

### Teacher Routes:
```php
Route::prefix('teacher/courses/{course}/modules/{module}/quizzes')->group(function () {
    Route::get('/', 'QuizController@index')->name('teacher.courses.modules.quizzes.index');
    Route::get('/create', 'QuizController@create')->name('teacher.courses.modules.quizzes.create');
    Route::post('/', 'QuizController@store')->name('teacher.courses.modules.quizzes.store');
    Route::get('/{quiz}', 'QuizController@show')->name('teacher.courses.modules.quizzes.show');
    Route::get('/{quiz}/edit', 'QuizController@edit')->name('teacher.courses.modules.quizzes.edit');
    Route::put('/{quiz}', 'QuizController@update')->name('teacher.courses.modules.quizzes.update');
    Route::delete('/{quiz}', 'QuizController@destroy')->name('teacher.courses.modules.quizzes.destroy');
    
    // Questions
    Route::prefix('/{quiz}/questions')->group(function () {
        Route::get('/create', 'QuestionController@create')->name('teacher.courses.modules.quizzes.questions.create');
        Route::post('/', 'QuestionController@store')->name('teacher.courses.modules.quizzes.questions.store');
        Route::get('/{question}/edit', 'QuestionController@edit')->name('teacher.courses.modules.quizzes.questions.edit');
        Route::put('/{question}', 'QuestionController@update')->name('teacher.courses.modules.quizzes.questions.update');
        Route::delete('/{question}', 'QuestionController@destroy')->name('teacher.courses.modules.quizzes.questions.destroy');
    });
});
```

### Student Routes:
```php
Route::middleware('student')->prefix('student')->group(function () {
    Route::get('/quizzes/{quiz}', 'Student\QuizController@show')->name('student.quizzes.show');
    Route::get('/quizzes/{quiz}/start', 'Student\QuizController@start')->name('student.quizzes.start');
    Route::post('/quizzes/{quiz}/submit', 'Student\QuizController@submit')->name('student.quizzes.submit');
    Route::get('/quiz-attempts/{attempt}/result', 'Student\QuizController@result')->name('student.quizzes.result');
});
```

---

## 🎨 Views Structure (ต้องสร้าง)

### Teacher Views:
```
resources/views/teacher/quizzes/
├── index.blade.php       # รายการ Quiz ทั้งหมด
├── create.blade.php      # ฟอร์มสร้าง Quiz
├── edit.blade.php        # ฟอร์มแก้ไข Quiz
└── show.blade.php        # แสดง Quiz + Questions + Student Results

resources/views/teacher/questions/
├── create.blade.php      # ฟอร์มเพิ่มคำถาม (สามารถเพิ่มหลายข้อ)
└── edit.blade.php        # ฟอร์มแก้ไขคำถาม
```

### Student Views:
```
resources/views/student/quizzes/
├── show.blade.php        # หน้าแสดงข้อมูล Quiz ก่อนทำ
├── take.blade.php        # หน้าทำ Quiz (แสดงคำถามทั้งหมด)
└── result.blade.php      # หน้าแสดงผลคะแนน
```

---

## 💡 การปรับแต่งระบบ

### 1. เพิ่มเวลาจำกัดในการทำ Quiz

**Backend:**
แก้ `Student/QuizController.php` method `submit()`:
```php
$timeLimit = $quiz->time_limit; // นาที
$startTime = Carbon::parse($validated['started_at']);
$endTime = now();

$usedMinutes = $startTime->diffInMinutes($endTime);

if ($timeLimit && $usedMinutes > $timeLimit) {
    return back()->with('error', 'หมดเวลาในการทำแบบทดสอบ');
}
```

**Frontend:**
เพิ่ม JavaScript Timer ใน `take.blade.php`:
```javascript
let timeLimit = {{ $quiz->time_limit ?? 0 }};
if (timeLimit > 0) {
    // Countdown timer
    // Auto-submit เมื่อหมดเวลา
}
```

---

### 2. จำกัดจำนวนครั้งการทำ Quiz

แก้ `Student/QuizController.php` method `start()`:
```php
$maxAttempts = 3; // กำหนดจำนวนครั้ง
$attemptCount = QuizAttempt::where('quiz_id', $quiz->id)
    ->where('student_id', auth()->id())
    ->count();

if ($attemptCount >= $maxAttempts) {
    return redirect()
        ->route('student.quizzes.show', $quiz)
        ->with('error', "คุณทำแบบทดสอบนี้ครบ {$maxAttempts} ครั้งแล้ว");
}
```

---

### 3. เพิ่มประเภทคำถาม True/False

**1. Migration:**
```bash
php artisan make:migration add_question_type_to_questions_table
```

```php
$table->string('question_type')->default('multiple_choice'); // multiple_choice, true_false, essay
```

**2. Model:**
แก้ `Question.php`:
```php
public function isTrueFalse() {
    return $this->question_type === 'true_false';
}
```

**3. Controller:**
แก้ `QuestionController::store()`:
```php
$validated = $request->validate([
    'question_type' => 'required|in:multiple_choice,true_false',
    // ...
]);
```

**4. Views:**
สร้าง component แยกสำหรับ True/False questions

---

### 4. Randomize คำถาม

แก้ `Student/QuizController.php` method `start()`:
```php
$quiz->load(['questions' => function($query) {
    $query->inRandomOrder(); // สุ่มลำดับคำถาม
}, 'questions.answers' => function($query) {
    $query->inRandomOrder(); // สุ่มลำดับตัวเลือก
}]);
```

---

### 5. แสดง Explanation เมื่อตอบผิด

**1. Migration:**
```php
$table->text('explanation')->nullable(); // ใน answers table
```

**2. แก้ View `result.blade.php`:**
```blade
@if($userAnswer->id !== $correctAnswer->id)
    <p class="text-red-600">{{ $correctAnswer->explanation }}</p>
@endif
```

---

## 🔒 Security Considerations

### 1. ป้องกัน Cheating:
- บันทึก `started_at` และ `completed_at` เพื่อตรวจสอบเวลา
- Validate ว่า answer_id ต้องเป็นของ question นั้นจริง
- ตรวจสอบว่า student ลงทะเบียนเรียนคอร์สนั้นหรือไม่

### 2. Authorization:
```php
// ตรวจสอบว่า Teacher เป็นเจ้าของ Course
if ($quiz->module->course->teacher_id !== auth()->id()) {
    abort(403);
}

// ตรวจสอบว่า Student enroll แล้ว
if (!$quiz->module->course->isEnrolledByStudent(auth()->id())) {
    abort(403);
}
```

---

## 📊 Analytics & Reporting

### ดึงสถิติ Quiz:

```php
// Average score
$avgScore = QuizAttempt::where('quiz_id', $quiz->id)
    ->avg('score');

// Pass rate
$totalAttempts = QuizAttempt::where('quiz_id', $quiz->id)->count();
$passedAttempts = QuizAttempt::where('quiz_id', $quiz->id)
    ->where('passed', true)
    ->count();
$passRate = ($passedAttempts / $totalAttempts) * 100;

// Question difficulty (% ที่ตอบถูก)
foreach ($quiz->questions as $question) {
    $correctCount = 0;
    $totalCount = 0;
    
    foreach (QuizAttempt::where('quiz_id', $quiz->id)->get() as $attempt) {
        $totalCount++;
        $answeredId = $attempt->answers[$question->id] ?? null;
        if ($question->isCorrectAnswer($answeredId)) {
            $correctCount++;
        }
    }
    
    $difficulty = ($correctCount / $totalCount) * 100;
}
```

---

## 🐛 Common Issues & Solutions

### ปัญหา: คะแนนไม่ถูกต้อง
**สาเหตุ:** Logic คำนวณผิด หรือ is_correct ไม่ถูกต้อง  
**แก้ไข:** ตรวจสอบใน `QuizController::submit()`

### ปัญหา: หน้า Quiz ไม่แสดงคำถาม
**สาเหตุ:** ไม่มี eager loading  
**แก้ไข:** ใช้ `$quiz->load('questions.answers')`

### ปัญหา: Student เข้าทำ Quiz ซ้ำได้เรื่อยๆ
**สาเหตุ:** ไม่มีการตรวจสอบ attempt  
**แก้ไข:** เพิ่มเงื่อนไขจำกัดครั้ง

---

## 📞 Support

หากมีปัญหา ดูเอกสารเพิ่มเติม:
- [LMS-COMPLETE-GUIDE.md](./LMS-COMPLETE-GUIDE.md)
- [ARCHITECTURE.md](./ARCHITECTURE.md)

---

**Last Updated:** 24 พฤศจิกายน 2025
