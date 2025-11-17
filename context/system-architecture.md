# System Architecture - LMS Platform

## ภาพรวมสถาปัตยกรรมระบบ

```mermaid
graph TB
    subgraph "Frontend Layer"
        A[Student Dashboard] --> B[Course Catalog]
        A --> C[Learning Interface]
        A --> D[Quiz Interface]
        E[Teacher Dashboard] --> F[Course Management]
        E --> G[Content Upload]
        E --> H[Quiz Builder]
        I[Admin Dashboard] --> J[User Management]
        I --> K[System Statistics]
    end
    
    subgraph "Application Layer (Laravel)"
        L[Authentication Middleware] --> M[Role-based Access Control]
        N[Route Controllers] --> O[Business Logic]
        P[Request Validation] --> Q[Data Processing]
    end
    
    subgraph "Data Layer"
        R[(MySQL Database)]
        S[File Storage]
        T[Cache System]
    end
    
    subgraph "External Services"
        U[YouTube API]
        V[Vimeo API]
        W[Email Service]
    end
    
    A --> L
    E --> L
    I --> L
    O --> R
    G --> S
    H --> R
    T --> R
    G --> U
    G --> V
    O --> W
```

## โครงสร้างไฟล์และโฟลเดอร์

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Auth/
│   │   │   ├── AuthenticatedSessionController.php
│   │   │   ├── RegisteredUserController.php
│   │   │   └── EmailVerificationNotificationController.php
│   │   ├── Student/
│   │   │   ├── DashboardController.php
│   │   │   ├── CourseController.php
│   │   │   ├── LessonController.php
│   │   │   └── QuizController.php
│   │   ├── Teacher/
│   │   │   ├── DashboardController.php
│   │   │   ├── CourseManagementController.php
│   │   │   ├── ContentController.php
│   │   │   └── QuizBuilderController.php
│   │   ├── Admin/
│   │   │   ├── DashboardController.php
│   │   │   ├── UserController.php
│   │   │   └── StatisticsController.php
│   │   └── API/
│   │       ├── CourseAPIController.php
│   │       ├── QuizAPIController.php
│   │       └── ProgressAPIController.php
│   ├── Middleware/
│   │   ├── RoleMiddleware.php
│   │   ├── TeacherMiddleware.php
│   │   └── AdminMiddleware.php
│   └── Requests/
│       ├── CourseRequest.php
│       ├── QuizRequest.php
│       └── UserRequest.php
├── Models/
│   ├── User.php
│   ├── Course.php
│   ├── Module.php
│   ├── Lesson.php
│   ├── Quiz.php
│   ├── Question.php
│   ├── Choice.php
│   ├── CourseUser.php
│   ├── LessonProgress.php
│   ├── QuizAttempt.php
│   └── QuizAnswer.php
└── Services/
    ├── CertificateService.php
    ├── FileUploadService.php
    ├── QuizGradingService.php
    └── ProgressTrackingService.php

resources/
├── views/
│   ├── layouts/
│   │   ├── app.blade.php
│   │   ├── guest.blade.php
│   │   └── components/
│   ├── auth/
│   ├── student/
│   │   ├── dashboard.blade.php
│   │   ├── courses/
│   │   ├── lessons/
│   │   └── quizzes/
│   ├── teacher/
│   │   ├── dashboard.blade.php
│   │   ├── courses/
│   │   ├── modules/
│   │   └── quizzes/
│   ├── admin/
│   │   ├── dashboard.blade.php
│   │   ├── users/
│   │   └── statistics/
│   └── components/
│       ├── navbar.blade.php
│       ├── sidebar.blade.php
│       └── cards/
├── css/
│   └── app.css
└── js/
    ├── app.js
    ├── quiz.js
    ├── progress.js
    └── file-upload.js

routes/
├── web.php
├── api.php
└── auth.php
```

## การทำงานของระบบ (System Flow)

### 1. Student Flow
```mermaid
sequenceDiagram
    participant S as Student
    participant A as Auth
    participant D as Dashboard
    participant C as Course
    participant L as Lesson
    participant Q as Quiz
    
    S->>A: Login/Register
    A->>D: Redirect to Dashboard
    D->>C: Browse Courses
    C->>S: Show Course Details
    S->>C: Enroll in Course
    C->>L: Access Lessons
    L->>L: Mark Progress
    L->>Q: Access Quiz
    Q->>Q: Submit Answers
    Q->>S: Show Results
    Q->>S: Generate Certificate
```

### 2. Teacher Flow
```mermaid
sequenceDiagram
    participant T as Teacher
    participant A as Auth
    participant D as Dashboard
    participant C as Course
    participant M as Module
    participant L as Lesson
    participant Q as Quiz
    
    T->>A: Login
    A->>D: Redirect to Dashboard
    D->>C: Create Course
    C->>M: Add Modules
    M->>L: Add Lessons
    L->>L: Upload Content
    M->>Q: Create Quiz
    Q->>Q: Add Questions
    D->>D: View Student Progress
```

### 3. Admin Flow
```mermaid
sequenceDiagram
    participant A as Admin
    participant AU as Auth
    participant D as Dashboard
    participant U as User Management
    participant C as Course Management
    participant S as Statistics
    
    A->>AU: Login
    AU->>D: Redirect to Dashboard
    D->>U: Manage Users
    U->>U: Approve Teachers
    D->>C: Manage All Courses
    C->>C: Edit/Delete Courses
    D->>S: View System Statistics
    S->>S: Generate Reports
```

## เทคโนโลยีและ Tools

### Backend Stack
- **Framework**: Laravel 10.x
- **Database**: MySQL 8.0
- **Authentication**: Laravel Breeze
- **File Management**: Spatie Media Library
- **PDF Generation**: DomPDF
- **Cache**: Redis (optional)

### Frontend Stack
- **Template Engine**: Blade
- **CSS Framework**: Tailwind CSS
- **JavaScript**: Vanilla JS + Alpine.js
- **File Upload**: Dropzone.js
- **Video Player**: Video.js

### Development Tools
- **Package Manager**: Composer
- **Asset Building**: Vite
- **Version Control**: Git
- **Testing**: PHPUnit
- **Code Style**: Laravel Pint

## Security Considerations

### Authentication & Authorization
- Role-based Access Control (RBAC)
- Email Verification
- Password Hashing
- Session Management
- API Token Authentication (Sanctum)

### Data Protection
- Input Validation
- SQL Injection Prevention
- XSS Protection
- CSRF Protection
- File Upload Security

### Performance Optimization
- Database Indexing
- Query Optimization
- Caching Strategy
- Image Optimization
- Lazy Loading

## Scalability Considerations

### Database Design
- Normalized Tables
- Proper Indexing
- Foreign Key Constraints
- Soft Deletes

### Application Design
- Service Layer Pattern
- Repository Pattern (optional)
- Event-Driven Architecture
- Queue System for Heavy Tasks

### Deployment Strategy
- Environment Configuration
- Asset Optimization
- Database Migration
- Backup Strategy
- Monitoring Setup