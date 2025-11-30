# 🚀 CT Learning - Development Guide

## 📋 บทนำ

คู่มือนี้คือแนวทางการพัฒนาระบบ CT Learning ครอบคลุมตั้งแต่การติดตั้งสภาพแวดล้อม การเขียนโค้ด การทดสอบ ไปจนถึงการ Deploy ออกสู่ Production

---

## 📋 สารบัญ

1. [Prerequisites](#prerequisites)
2. [Environment Setup](#environment-setup)
3. [Installation Guide](#installation-guide)
4. [Development Workflow](#development-workflow)
5. [Code Standards](#code-standards)
6. [Testing Strategy](#testing-strategy)
7. [Debugging Guide](#debugging-guide)
8. [Performance Optimization](#performance-optimization)
9. [Security Best Practices](#security-best-practices)
10. [Deployment Guide](#deployment-guide)
11. [Maintenance](#maintenance)
12. [Troubleshooting](#troubleshooting)

---

## 🔧 Prerequisites

### System Requirements

#### Minimum Requirements
- **Operating System**: Windows 10+, macOS 10.15+, Ubuntu 18.04+
- **PHP**: 8.1 or higher
- **MySQL**: 8.0 or higher (or PostgreSQL 12+)
- **Node.js**: 16.0 or higher
- **NPM**: 8.0 or higher
- **Composer**: 2.0 or higher
- **Git**: 2.30 or higher
- **RAM**: 4GB minimum (8GB recommended)
- **Storage**: 10GB free space

#### Recommended Development Environment
- **IDE**: VS Code, PhpStorm, Sublime Text
- **Browser**: Chrome/Firefox with Developer Tools
- **Database Tool**: phpMyAdmin, DBeaver, TablePlus
- **API Testing**: Postman, Insomnia
- **Version Control**: Git + GitHub/GitLab

### Required Software Installation

#### Install PHP
```bash
# Ubuntu/Debian
sudo apt update
sudo apt install php8.1 php8.1-cli php8.1-fpm php8.1-mysql php8.1-xml php8.1-mbstring php8.1-curl php8.1-zip php8.1-gd php8.1-intl

# macOS (using Homebrew)
brew install php@8.1

# Windows (using XAMPP/WAMP)
# Download and install XAMPP from https://www.apachefriends.org/
```

#### Install Composer
```bash
# Linux/macOS
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Windows
# Download Composer-Setup.exe from https://getcomposer.org/download/
```

#### Install Node.js & NPM
```bash
# Using NVM (recommended)
curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.0/install.sh | bash
nvm install 18
nvm use 18

# Direct installation
# Download from https://nodejs.org/
```

#### Install MySQL
```bash
# Ubuntu/Debian
sudo apt install mysql-server
sudo mysql_secure_installation

# macOS
brew install mysql
brew services start mysql

# Windows
# Download from https://dev.mysql.com/downloads/mysql/
```

---

## 🌍 Environment Setup

### Development Environment Options

#### Option 1: Local Development (Recommended)
```bash
# Clone repository
git clone https://github.com/pchan132/Project-CT-Learning.git
cd ct-learning

# Install dependencies
composer install
npm install

# Setup environment
cp .env.example .env
php artisan key:generate

# Setup database
mysql -u root -p
CREATE DATABASE ct_learning CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

# Update .env with database credentials
# DB_DATABASE=ct_learning
# DB_USERNAME=root
# DB_PASSWORD=your_password

# Run migrations
php artisan migrate
php artisan db:seed

# Setup storage
php artisan storage:link

# Compile assets
npm run dev

# Start development server
php artisan serve
```

#### Option 2: Docker Development
```bash
# Using Docker Compose
docker-compose up -d

# Access application
# Web: http://localhost:8000
# Database: localhost:3306
# phpMyAdmin: http://localhost:8080
```

#### Option 3: Virtual Machine (Laravel Homestead)
```bash
# Install Homestead
composer global require laravel/homestead

# Initialize Homestead
homestead init

# Configure Homestead.yaml
---
ip: "192.168.10.10"
memory: 2048
cpus: 2
provider: virtualbox
authorize: ~/.ssh/id_rsa.pub
keys:
    - ~/.ssh/id_rsa
folders:
    - map: /path/to/ct-learning
      to: /home/vagrant/ct-learning
sites:
    - map: ct-learning.test
      to: /home/vagrant/ct-learning/public
databases:
    - ct_learning

# Start Homestead
homestead up
```

### IDE Configuration

#### VS Code Setup
```json
// .vscode/settings.json
{
    "php.validate.executablePath": "/usr/bin/php",
    "php.suggest.basic": false,
    "emmet.includeLanguages": {
        "blade": "html"
    },
    "files.associations": {
        "*.blade.php": "blade"
    },
    "editor.formatOnSave": true,
    "editor.codeActionsOnSave": {
        "source.fixAll": true
    }
}
```

#### Recommended VS Code Extensions
```json
// .vscode/extensions.json
{
    "recommendations": [
        "bmewburn.vscode-intelephense-client",
        "onecentlin.laravel-blade",
        "formulahendry.auto-rename-tag",
        "bradlc.vscode-tailwindcss",
        "esbenp.prettier-vscode",
        "ms-vscode.vscode-json",
        "redhat.vscode-yaml",
        "ms-vscode-remote.remote-containers"
    ]
}
```

---

## 📦 Installation Guide

### Step-by-Step Installation

#### 1. Clone Repository
```bash
git clone https://github.com/pchan132/Project-CT-Learning.git
cd ct-learning
```

#### 2. Install PHP Dependencies
```bash
# Install production dependencies
composer install --no-dev --optimize-autoloader

# Or install with development dependencies
composer install
```

#### 3. Install JavaScript Dependencies
```bash
# Install dependencies
npm install

# Or using Yarn
yarn install
```

#### 4. Environment Configuration
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Edit .env file
nano .env
```

#### 5. Database Setup
```bash
# Create database
mysql -u root -p
CREATE DATABASE ct_learning CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
GRANT ALL PRIVILEGES ON ct_learning.* TO 'ct_user'@'localhost' IDENTIFIED BY 'secure_password';
FLUSH PRIVILEGES;
EXIT;

# Update .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ct_learning
DB_USERNAME=ct_user
DB_PASSWORD=secure_password
```

#### 6. Run Database Migrations
```bash
# Run migrations
php artisan migrate

# Run seeders (optional)
php artisan db:seed

# Or migrate with seed in one command
php artisan migrate:fresh --seed
```

#### 7. Setup Storage
```bash
# Create symbolic link
php artisan storage:link

# Set proper permissions
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

#### 8. Compile Frontend Assets
```bash
# Development build
npm run dev

# Production build
npm run build

# Watch for changes
npm run watch
```

#### 9. Start Development Server
```bash
# Start Laravel development server
php artisan serve

# Or specify host and port
php artisan serve --host=0.0.0.0 --port=8000
```

### Verification
```bash
# Check Laravel installation
php artisan --version

# Check database connection
php artisan tinker
>>> DB::connection()->getPdo();

# Check application
curl http://localhost:8000
```

---

## 🔄 Development Workflow

### Git Workflow

#### Branching Strategy
```
main                 # Production branch
├── develop          # Development branch
├── feature/auth     # Feature branches
├── feature/courses
├── feature/quiz
└── hotfix/bug-fix   # Hotfix branches
```

#### Daily Workflow
```bash
# 1. Sync with latest changes
git checkout develop
git pull origin develop

# 2. Create feature branch
git checkout -b feature/new-feature

# 3. Make changes
# ... code changes ...

# 4. Commit changes
git add .
git commit -m "feat: add new feature description"

# 5. Push to remote
git push origin feature/new-feature

# 6. Create Pull Request
# Go to GitHub and create PR from feature/new-feature to develop

# 7. After merge, update develop
git checkout develop
git pull origin develop

# 8. Delete feature branch
git branch -d feature/new-feature
git push origin --delete feature/new-feature
```

### Commit Message Convention
```bash
# Format: <type>(<scope>): <description>

# Types
feat:     New feature
fix:      Bug fix
docs:     Documentation changes
style:    Code formatting (no logic change)
refactor: Code refactoring
test:     Adding tests
chore:    Maintenance tasks

# Examples
feat(auth): add social login integration
fix(quiz): resolve timer issue in quiz system
docs(api): update authentication endpoints
style(lesson): fix code formatting in lesson controller
refactor(course): optimize course query performance
test(user): add unit tests for user model
chore(deps): update laravel to version 10.15
```

### Code Review Process

#### Pull Request Checklist
- [ ] Code follows coding standards
- [ ] Tests are written and passing
- [ ] Documentation is updated
- [ ] No sensitive data is committed
- [ ] Performance impact is considered
- [ ] Security implications are reviewed
- [ ] Browser compatibility is tested
- [ ] Mobile responsiveness is verified

#### Review Guidelines
1. **Code Quality**: Readability, maintainability, performance
2. **Security**: Check for vulnerabilities, input validation
3. **Testing**: Test coverage, edge cases
4. **Documentation**: Comments, API docs, user guides
5. **User Experience**: UI/UX improvements, accessibility

---

## 📝 Code Standards

### PHP Standards (PSR-12)

#### Naming Conventions
```php
// Classes: PascalCase
class CourseController
{
    // Properties: camelCase
    private $courseRepository;
    
    // Methods: camelCase
    public function getCourses()
    {
        // Variables: camelCase
        $courseList = $this->courseRepository->getAll();
        
        // Constants: UPPER_SNAKE_CASE
        const MAX_COURSES_PER_USER = 10;
        
        return $courseList;
    }
}

// Interfaces: PascalCase with 'Interface' suffix
interface CourseRepositoryInterface
{
    public function findById(int $id): ?Course;
}

// Traits: PascalCase with 'Trait' suffix
trait CacheableTrait
{
    // Implementation
}
```

#### File Organization
```php
<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Http\Requests\StoreCourseRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

/**
 * Course Controller
 * 
 * Handles course-related operations including CRUD,
 * enrollment management, and progress tracking.
 * 
 * @package App\Http\Controllers
 * @author CT Learning Team
 * @version 1.0.0
 */
class CourseController extends Controller
{
    /**
     * Course repository instance.
     *
     * @var CourseRepository
     */
    protected $courseRepository;

    /**
     * Constructor.
     *
     * @param CourseRepository $courseRepository
     */
    public function __construct(CourseRepository $courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }

    /**
     * Display a listing of courses.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        // Implementation
    }
}
```

#### Error Handling
```php
try {
    $course = $this->courseRepository->create($data);
    
    return response()->json([
        'success' => true,
        'data' => $course,
        'message' => 'Course created successfully'
    ], 201);
    
} catch (ValidationException $e) {
    return response()->json([
        'success' => false,
        'error' => [
            'code' => 'VALIDATION_ERROR',
            'message' => 'The given data was invalid.',
            'details' => $e->errors()
        ]
    ], 422);
    
} catch (Exception $e) {
    Log::error('Course creation failed', [
        'error' => $e->getMessage(),
        'data' => $data,
        'user_id' => auth()->id()
    ]);
    
    return response()->json([
        'success' => false,
        'error' => [
            'code' => 'SERVER_ERROR',
            'message' => 'An error occurred while creating the course.'
        ]
    ], 500);
}
```

### JavaScript Standards

#### ES6+ Standards
```javascript
// Use const/let instead of var
const API_BASE_URL = 'http://localhost:8000/api';
let currentUser = null;

// Arrow functions
const getUserCourses = async (userId) => {
    try {
        const response = await fetch(`${API_BASE_URL}/users/${userId}/courses`);
        const data = await response.json();
        return data;
    } catch (error) {
        console.error('Error fetching user courses:', error);
        throw error;
    }
};

// Template literals
const welcomeMessage = `Welcome, ${user.name}! You have ${user.courseCount} courses.`;

// Destructuring
const { id, name, email } = user;
const [firstCourse, secondCourse] = courses;

// Spread operator
const updatedUser = { ...user, lastLogin: new Date() };
const allCourses = [...activeCourses, ...archivedCourses];

// Async/await
const completeLesson = async (lessonId) => {
    const token = localStorage.getItem('authToken');
    
    try {
        const response = await fetch(`${API_BASE_URL}/lessons/${lessonId}/complete`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            updateProgress(data.data.courseProgress);
            showNotification('Lesson completed!', 'success');
        }
        
        return data;
    } catch (error) {
        showNotification('Failed to complete lesson', 'error');
        throw error;
    }
};
```

#### Alpine.js Components
```javascript
// Course progress tracker
document.addEventListener('alpine:init', () => {
    Alpine.data('courseProgress', () => ({
        courseId: null,
        progress: 0,
        loading: false,
        lessons: [],
        
        init(courseId) {
            this.courseId = courseId;
            this.loadProgress();
            this.startAutoRefresh();
        },
        
        async loadProgress() {
            this.loading = true;
            
            try {
                const response = await fetch(`/api/courses/${this.courseId}/progress`);
                const data = await response.json();
                
                this.progress = data.data.progress;
                this.lessons = data.data.lessons;
            } catch (error) {
                console.error('Failed to load progress:', error);
            } finally {
                this.loading = false;
            }
        },
        
        async completeLesson(lessonId) {
            try {
                const response = await fetch(`/api/lessons/${lessonId}/complete`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    this.progress = data.data.courseProgress;
                    this.updateLessonStatus(lessonId, 'completed');
                }
            } catch (error) {
                console.error('Failed to complete lesson:', error);
            }
        },
        
        updateLessonStatus(lessonId, status) {
            const lesson = this.lessons.find(l => l.id === lessonId);
            if (lesson) {
                lesson.status = status;
            }
        },
        
        startAutoRefresh() {
            // Refresh progress every 30 seconds
            setInterval(() => {
                this.loadProgress();
            }, 30000);
        }
    }));
});
```

### CSS/Tailwind Standards

#### Component Organization
```html
<!-- Course Card Component -->
<div class="course-card group relative bg-white rounded-lg shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden">
    <!-- Cover Image -->
    <div class="relative h-48 overflow-hidden">
        <img 
            src="{{ $course->cover_image_url }}" 
            alt="{{ $course->title }}"
            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
        >
        
        <!-- Progress Overlay -->
        @if($enrolled)
        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent flex items-end p-4">
            <div class="w-full">
                <div class="flex justify-between text-white text-sm mb-1">
                    <span>Progress</span>
                    <span>{{ $progress }}%</span>
                </div>
                <div class="w-full bg-white/20 rounded-full h-2">
                    <div 
                        class="bg-green-500 h-2 rounded-full transition-all duration-500"
                        style="width: {{ $progress }}%"
                    ></div>
                </div>
            </div>
        </div>
        @endif
    </div>
    
    <!-- Course Info -->
    <div class="p-6">
        <h3 class="text-xl font-semibold text-gray-900 mb-2 line-clamp-2">
            {{ $course->title }}
        </h3>
        
        <p class="text-gray-600 text-sm mb-4 line-clamp-3">
            {{ $course->description }}
        </p>
        
        <!-- Meta Info -->
        <div class="flex items-center justify-between text-sm text-gray-500">
            <div class="flex items-center space-x-4">
                <span class="flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                    </svg>
                    {{ $course->enrollments_count }} students
                </span>
                
                <span class="flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.55 1 1 0 01-.17-1.075L3.31 9.397zm5.69 3.303a7.952 7.952 0 014.5 1.278v-4.102l-4.5-1.928v4.752zm7.5.438a7.952 7.952 0 014.5-1.278v-4.752l-4.5 1.928v4.102z"/>
                    </svg>
                    {{ $course->modules_count }} modules
                </span>
            </div>
            
            @if($course->teacher)
            <span class="flex items-center">
                <img 
                    src="{{ $course->teacher->avatar_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($course->teacher->name) }}"
                    alt="{{ $course->teacher->name }}"
                    class="w-6 h-6 rounded-full mr-1"
                >
                {{ $course->teacher->name }}
            </span>
            @endif
        </div>
        
        <!-- Action Button -->
        <div class="mt-4">
            @if($enrolled)
                <a 
                    href="{{ route('student.courses.learn', $course->id) }}"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200 flex items-center justify-center"
                >
                    @if($progress == 100)
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Completed
                    @else
                        Continue Learning
                    @endif
                </a>
            @else
                <button 
                    onclick="enrollCourse({{ $course->id }})"
                    class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg transition-colors duration-200"
                >
                    Enroll Now
                </button>
            @endif
        </div>
    </div>
</div>
```

---

## 🧪 Testing Strategy

### Testing Pyramid

```
    E2E Tests (10%)
   ┌─────────────────┐
  │  User Journeys  │
 └─────────────────┘
        ↑
   Integration Tests (20%)
  ┌─────────────────┐
 │  API Endpoints  │
 │  Database Tests │
 └─────────────────┘
        ↑
    Unit Tests (70%)
   ┌─────────────────┐
  │  Model Tests    │
  │  Service Tests  │
  │  Utility Tests  │
 └─────────────────┘
```

### Unit Testing

#### Model Tests
```php
// tests/Unit/CourseTest.php
namespace Tests\Unit;

use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourseTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_course()
    {
        $teacher = User::factory()->create(['role' => 'teacher']);
        
        $course = Course::factory()->create([
            'teacher_id' => $teacher->id,
            'title' => 'Test Course',
            'description' => 'Test Description'
        ]);

        $this->assertEquals('Test Course', $course->title);
        $this->assertEquals('Test Description', $course->description);
        $this->assertEquals($teacher->id, $course->teacher_id);
    }

    /** @test */
    public function it_can_calculate_progress_for_student()
    {
        $course = Course::factory()
            ->has(Module::factory()->count(2)
                ->has(Lesson::factory()->count(3))
            )
            ->create();

        $student = User::factory()->create(['role' => 'student']);
        
        // Complete 3 out of 6 lessons
        $course->lessons->take(3)->each(function ($lesson) use ($student) {
            $lesson->completions()->create(['user_id' => $student->id]);
        });

        $progress = $course->getProgressForStudent($student->id);
        
        $this->assertEquals(50.0, $progress);
    }

    /** @test */
    public function it_can_check_if_student_is_enrolled()
    {
        $course = Course::factory()->create();
        $student = User::factory()->create(['role' => 'student']);
        
        // Initially not enrolled
        $this->assertFalse($course->isEnrolledByStudent($student->id));
        
        // Enroll student
        $course->enrollments()->create(['user_id' => $student->id]);
        
        $this->assertTrue($course->isEnrolledByStudent($student->id));
    }
}
```

#### Service Tests
```php
// tests/Unit/CourseServiceTest.php
namespace Tests\Unit;

use App\Services\CourseService;
use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourseServiceTest extends TestCase
{
    use RefreshDatabase;

    private $courseService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->courseService = app(CourseService::class);
    }

    /** @test */
    public function it_can_create_course_with_valid_data()
    {
        $teacher = User::factory()->create(['role' => 'teacher']);
        $courseData = [
            'title' => 'New Course',
            'description' => 'Course Description',
            'teacher_id' => $teacher->id
        ];

        $course = $this->courseService->createCourse($courseData);

        $this->assertInstanceOf(Course::class, $course);
        $this->assertEquals('New Course', $course->title);
        $this->assertEquals($teacher->id, $course->teacher_id);
    }

    /** @test */
    public function it_throws_exception_for_invalid_course_data()
    {
        $this->expectException(\InvalidArgumentException::class);
        
        $invalidData = [
            'title' => '', // Empty title
            'description' => 'Description'
        ];

        $this->courseService->createCourse($invalidData);
    }
}
```

### Integration Testing

#### API Endpoint Tests
```php
// tests/Feature/CourseApiTest.php
namespace Tests\Feature;

use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CourseApiTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_list_courses_publicly()
    {
        Course::factory()->count(5)->create();

        $response = $this->getJson('/api/courses');

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'success',
                    'data' => [
                        '*' => [
                            'id',
                            'title',
                            'description',
                            'teacher',
                            'modules_count'
                        ]
                    ]
                ]);

        $this->assertEquals(5, count($response->json('data')));
    }

    /** @test */
    public function it_can_enroll_in_course_as_student()
    {
        $student = User::factory()->create(['role' => 'student']);
        $course = Course::factory()->create();

        Sanctum::actingAs($student);

        $response = $this->postJson("/api/courses/{$course->id}/enroll");

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'message' => 'Successfully enrolled in course'
                ]);

        $this->assertDatabaseHas('enrollments', [
            'user_id' => $student->id,
            'course_id' => $course->id
        ]);
    }

    /** @test */
    public function it_prevents_unauthorized_course_creation()
    {
        $student = User::factory()->create(['role' => 'student']);
        $courseData = [
            'title' => 'Unauthorized Course',
            'description' => 'Description'
        ];

        Sanctum::actingAs($student);

        $response = $this->postJson('/api/courses', $courseData);

        $response->assertStatus(403)
                ->assertJson([
                    'success' => false,
                    'error' => [
                        'code' => 'FORBIDDEN',
                        'message' => 'Access denied. Teacher role required.'
                    ]
                ]);
    }
}
```

### End-to-End Testing

#### Browser Tests (Dusk)
```php
// tests/Browser/CourseLearningTest.php
namespace Tests\Browser;

use App\Models\User;
use App\Models\Course;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class CourseLearningTest extends DuskTestCase
{
    /** @test */
    public function it_allows_student_to_learn_and_complete_lesson()
    {
        $student = User::factory()->create(['role' => 'student']);
        $course = Course::factory()
            ->has(Module::factory()->has(Lesson::factory()->count(2)))
            ->create();

        // Enroll student
        $course->enrollments()->create(['user_id' => $student->id]);

        $this->browse(function (Browser $browser) use ($student, $course) {
            $browser->loginAs($student)
                    ->visit("/student/courses/{$course->id}/learn")
                    ->assertSee($course->title)
                    ->clickLink('Start Learning')
                    ->waitForText('Mark as Complete', 5)
                    ->assertSee('Mark as Complete')
                    ->click('@mark-complete-button')
                    ->waitForText('✅ Completed', 5)
                    ->assertSee('✅ Completed');
        });
    }
}
```

### Running Tests

#### Command Line
```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test tests/Unit/CourseTest.php

# Run specific test method
php artisan test --filter it_can_create_a_course

# Run with coverage
php artisan test --coverage

# Run tests in parallel
php artisan test --parallel

# Generate coverage report
php artisan test --coverage --min=80
```

#### PHPUnit Configuration
```xml
<!-- phpunit.xml -->
<?xml version="1.0" encoding="UTF-8"?>
<phpunit xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
         xsi:noNamespaceSchemaLocation="https://schema.phpunit.de/10.0/phpunit.xsd"
         bootstrap="vendor/autoload.php"
         colors="true"
         cacheDirectory=".phpunit.cache"
         executionOrder="depends,defects"
         requireCoverageMetadata="true"
         beStrictAboutCoverageMetadata="true"
         beStrictAboutOutputDuringTests="true"
         failOnRisky="true"
         failOnWarning="true">
    <testsuites>
        <testsuite name="Unit">
            <directory>tests/Unit</directory>
        </testsuite>
        <testsuite name="Feature">
            <directory>tests/Feature</directory>
        </testsuite>
    </testsuites>
    <source>
        <include>
            <directory>app</directory>
        </include>
    </source>
    <php>
        <env name="APP_ENV" value="testing"/>
        <env name="BCRYPT_ROUNDS" value="4"/>
        <env name="CACHE_DRIVER" value="array"/>
        <env name="DB_CONNECTION" value="sqlite"/>
        <env name="DB_DATABASE" value=":memory:"/>
        <env name="MAIL_MAILER" value="array"/>
        <env name="QUEUE_CONNECTION" value="sync"/>
        <env name="SESSION_DRIVER" value="array"/>
        <env name="TELESCOPE_ENABLED" value="false"/>
    </php>
</phpunit>
```

---

## 🐛 Debugging Guide

### Laravel Debugging Tools

#### Built-in Debugging
```php
// Enable debug mode in .env
APP_DEBUG=true
APP_LEVEL=debug

// Use dump() for debugging
dump($variable); // Outputs variable and continues execution
dd($variable); // Outputs variable and stops execution

// Use Laravel Log
Log::info('User logged in', ['user_id' => $user->id]);
Log::error('Database error', ['exception' => $e->getMessage()]);

// Use debugbar (install barryvdh/laravel-debugbar)
// Automatically adds debug toolbar in development
```

#### Telescope for Debugging
```php
// Install Telescope
composer require laravel/telescope --dev
php artisan telescope:install
php artisan migrate

// Monitor requests, exceptions, queries, cache, etc.
// Access at /telescope
```

### Frontend Debugging

#### JavaScript Debugging
```javascript
// Use console.log for debugging
console.log('Variable value:', variable);
console.table(usersArray);
console.error('Error occurred:', error);

// Use debugger statement
function calculateProgress(course) {
    debugger; // Pauses execution if dev tools open
    // ... calculation logic
}

// Use browser dev tools
// Network tab: Monitor API calls
// Console tab: Check JavaScript errors
// Elements tab: Inspect DOM and CSS
```

#### Alpine.js Debugging
```javascript
// Enable Alpine.js debugging
Alpine.start(); // Add to console to inspect Alpine data

// Check component data
Alpine.$data(element) // Get data from element

// Debug reactivity
Alpine.effect(() => {
    console.log('Reactive dependency changed');
});
```

### Database Debugging

#### Query Logging
```php
// Enable query logging in .env
DB_CONNECTION=mysql
DB_LOG=true

// Or enable programmatically
DB::enableQueryLog();
// ... run queries
$queries = DB::getQueryLog();
dd($queries);

// Use Laravel Debugbar to see queries
// Install: composer require barryvdh/laravel-debugbar
```

#### Database Testing
```php
// Use database transactions for testing
DB::beginTransaction();
try {
    // Test operations
    DB::rollBack();
} catch (Exception $e) {
    DB::rollBack();
    throw $e;
}

// Use factory states for testing
$course = Course::factory()->published()->create();
$user = User::factory()->withProfile()->create();
```

---

## 📈 Performance Optimization

### Database Optimization

#### Query Optimization
```php
// ❌ Bad: N+1 Query Problem
$courses = Course::all();
foreach ($courses as $course) {
    echo $course->teacher->name; // Query for each course
}

// ✅ Good: Eager Loading
$courses = Course::with('teacher')->get();
foreach ($courses as $course) {
    echo $course->teacher->name; // No additional queries
}

// ✅ Better: Selective Loading
$courses = Course::with(['teacher:id,name', 'modules:id,title,course_id'])
    ->select(['id', 'title', 'teacher_id'])
    ->get();

// ✅ Best: Pagination + Eager Loading
$courses = Course::with(['teacher', 'modules'])
    ->paginate(15);
```

#### Indexing Strategy
```php
// Add indexes in migrations
Schema::table('courses', function (Blueprint $table) {
    $table->index('teacher_id');           // Foreign key
    $table->index('title');               // Search
    $table->index(['teacher_id', 'created_at']); // Composite
});

// Use database indexes for performance
// Monitor slow queries
// Use EXPLAIN to analyze queries
```

### Frontend Optimization

#### Asset Optimization
```javascript
// vite.config.js
export default defineConfig({
    build: {
        rollupOptions: {
            output: {
                manualChunks: {
                    vendor: ['alpinejs'],
                    styles: ['tailwindcss']
                }
            }
        },
        minify: 'terser',
        sourcemap: false
    }
});

// Lazy load components
const LazyComponent = () => import('./LazyComponent.vue');
```

#### Caching Strategy
```php
// Route caching
php artisan route:cache

// Configuration caching
php artisan config:cache

// View caching
php artisan view:cache

// Application caching
$popularCourses = Cache::remember('popular.courses', 3600, function () {
    return Course::withCount('enrollments')
        ->orderBy('enrollments_count', 'desc')
        ->limit(10)
        ->get();
});
```

---

## 🔒 Security Best Practices

### Input Validation
```php
// Use Form Request Validation
class StoreCourseRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z0-9\s\-]+$/'],
            'description' => ['nullable', 'string', 'max:2000'],
            'cover_image' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,gif',
                'max:2048' // 2MB
            ]
        ];
    }

    public function sanitize()
    {
        $input = $this->all();
        
        // Sanitize HTML content
        if (isset($input['description'])) {
            $input['description'] = strip_tags($input['description'], '<p><br><strong><em>');
        }
        
        return $input;
    }
}
```

### Authorization
```php
// Use Policies for authorization
class CoursePolicy
{
    public function update(User $user, Course $course): bool
    {
        return $user->id === $course->teacher_id;
    }

    public function delete(User $user, Course $course): bool
    {
        return $this->update($user, $course);
    }
}

// Apply policy in controller
public function update(UpdateCourseRequest $request, Course $course)
{
    $this->authorize('update', $course);
    
    // Update logic
}
```

### CSRF Protection
```html
<!-- Include CSRF token in all forms -->
<form method="POST" action="/courses">
    @csrf
    <!-- form fields -->
</form>

<!-- Include in AJAX requests -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
fetch('/api/courses', {
    method: 'POST',
    headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'Content-Type': 'application/json'
    },
    body: JSON.stringify(courseData)
});
</script>
```

---

## 🚀 Deployment Guide

### Production Setup

#### Server Requirements
- **Server**: Ubuntu 20.04+ / CentOS 8+ / Amazon Linux 2
- **Web Server**: Nginx or Apache
- **PHP**: 8.1+ with required extensions
- **Database**: MySQL 8.0+ or PostgreSQL 12+
- **SSL**: Valid SSL certificate
- **Domain**: Configured domain name

#### Deployment Steps

##### 1. Server Setup
```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install Nginx
sudo apt install nginx -y
sudo systemctl start nginx
sudo systemctl enable nginx

# Install PHP and extensions
sudo apt install php8.1-fpm php8.1-cli php8.1-mysql php8.1-xml php8.1-mbstring php8.1-curl php8.1-zip php8.1-gd php8.1-intl -y

# Install MySQL
sudo apt install mysql-server -y
sudo mysql_secure_installation

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install Node.js
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt-get install -y nodejs
```

##### 2. Application Setup
```bash
# Clone repository
cd /var/www
sudo git clone https://github.com/pchan132/Project-CT-Learning.git ct-learning
sudo chown -R $USER:$USER /var/www/ct-learning
cd ct-learning

# Install dependencies
composer install --optimize-autoloader --no-dev
npm install && npm run build

# Setup environment
cp .env.example .env
php artisan key:generate

# Configure .env for production
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ct_learning
DB_USERNAME=ct_user
DB_PASSWORD=secure_password

# Run migrations
php artisan migrate --force

# Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Setup storage
php artisan storage:link
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

##### 3. Nginx Configuration
```nginx
# /etc/nginx/sites-available/ct-learning
server {
    listen 80;
    server_name yourdomain.com www.yourdomain.com;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name yourdomain.com www.yourdomain.com;
    root /var/www/ct-learning/public;
    index index.php index.html;

    # SSL Configuration
    ssl_certificate /path/to/ssl/certificate.crt;
    ssl_certificate_key /path/to/ssl/private.key;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers ECDHE-RSA-AES256-GCM-SHA512:DHE-RSA-AES256-GCM-SHA512:ECDHE-RSA-AES256-GCM-SHA384:DHE-RSA-AES256-GCM-SHA384;
    ssl_prefer_server_ciphers off;

    # Security Headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header Referrer-Policy "no-referrer-when-downgrade" always;
    add_header Content-Security-Policy "default-src 'self' http: https: data: blob: 'unsafe-inline'" always;

    # Gzip Compression
    gzip on;
    gzip_vary on;
    gzip_proxied any;
    gzip_comp_level 6;
    gzip_types text/plain text/css text/xml text/javascript application/json application/javascript application/xml+rss application/rss+xml application/atom+xml image/svg+xml;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    # Block access to sensitive files
    location ~ /\. {
        deny all;
    }

    location ~ ^/(composer\.json|composer\.lock|\.env|\.git) {
        deny all;
    }

    # Static file caching
    location ~* \.(jpg|jpeg|png|gif|ico|css|js)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }
}
```

##### 4. SSL Setup (Let's Encrypt)
```bash
# Install Certbot
sudo apt install certbot python3-certbot-nginx -y

# Obtain SSL certificate
sudo certbot --nginx -d yourdomain.com -d www.yourdomain.com

# Setup auto-renewal
sudo crontab -e
# Add: 0 12 * * * /usr/bin/certbot renew --quiet
```

##### 5. Process Management
```bash
# Install Supervisor
sudo apt install supervisor -y

# Create supervisor config
sudo nano /etc/supervisor/conf.d/ct-learning-worker.conf
```

```ini
[program:ct-learning-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/ct-learning/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=4
redirect_stderr=true
stdout_logfile=/var/www/ct-learning/storage/logs/worker.log
stopwaitsecs=3600
```

```bash
# Start supervisor
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start ct-learning-worker:*
```

### Monitoring & Maintenance

#### Log Monitoring
```bash
# Application logs
tail -f /var/www/ct-learning/storage/logs/laravel.log

# Nginx logs
tail -f /var/log/nginx/access.log
tail -f /var/log/nginx/error.log

# PHP-FPM logs
tail -f /var/log/php8.1-fpm.log
```

#### Backup Strategy
```bash
# Create backup script
#!/bin/bash
# backup.sh

DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/var/backups/ct-learning"

# Create backup directory
mkdir -p $BACKUP_DIR

# Backup database
mysqldump -u ct_user -p ct_learning > $BACKUP_DIR/database_$DATE.sql

# Backup files
tar -czf $BACKUP_DIR/files_$DATE.tar.gz /var/www/ct-learning

# Remove old backups (keep 7 days)
find $BACKUP_DIR -name "*.sql" -mtime +7 -delete
find $BACKUP_DIR -name "*.tar.gz" -mtime +7 -delete

echo "Backup completed: $DATE"
```

```bash
# Setup cron job
sudo crontab -e
# Add: 0 2 * * * /path/to/backup.sh
```

---

## 🔧 Maintenance

### Regular Tasks

#### Daily
- Monitor application logs
- Check system resources
- Verify backups are running

#### Weekly
- Update dependencies
- Review security patches
- Analyze performance metrics

#### Monthly
- Database maintenance
- SSL certificate renewal check
- Security audit

### Update Process

#### Laravel Updates
```bash
# Check current version
php artisan --version

# Update Laravel
composer update laravel/framework

# Run update commands
php artisan route:clear
php artisan config:clear
php artisan view:clear
php artisan cache:clear

# Test application thoroughly
```

#### Dependency Updates
```bash
# Update PHP dependencies
composer update

# Update JavaScript dependencies
npm update

# Test after updates
php artisan test
npm run test
```

---

## 🆘 Troubleshooting

### Common Issues

#### 1. White Screen of Death
```bash
# Check PHP errors
tail -f /var/log/php8.1-fpm.log

# Enable error reporting
# In .env: APP_DEBUG=true
# In index.php: error_reporting(E_ALL);
```

#### 2. Database Connection Issues
```bash
# Test database connection
mysql -u username -p -h localhost

# Check credentials in .env
# Verify database exists
# Check MySQL service status
sudo systemctl status mysql
```

#### 3. Permission Issues
```bash
# Fix file permissions
sudo chown -R www-data:www-data /var/www/ct-learning
sudo chmod -R 775 storage bootstrap/cache

# Fix storage link
php artisan storage:link
```

#### 4. Asset Loading Issues
```bash
# Clear and rebuild assets
npm run build
php artisan view:clear
php artisan config:clear

# Check Nginx configuration
sudo nginx -t
sudo systemctl reload nginx
```

### Performance Issues

#### Slow Database Queries
```bash
# Enable slow query log
# In my.cnf:
slow_query_log = 1
slow_query_log_file = /var/log/mysql/slow.log
long_query_time = 2

# Analyze with EXPLAIN
EXPLAIN SELECT * FROM courses WHERE teacher_id = 1;
```

#### High Memory Usage
```bash
# Monitor memory usage
free -h
top

# Check PHP-FPM settings
# In php.ini:
memory_limit = 256M
max_execution_time = 300

# In www.conf:
pm.max_children = 50
pm.start_servers = 5
pm.min_spare_servers = 5
pm.max_spare_servers = 35
```

---

**อัพเดทล่าสุด**: 29 พฤศจิกายน 2025  
**เวอร์ชัน**: v2.0  
**ผู้เขียน**: CT Learning Development Team  
**สถานะ**: ✅ Complete & Maintained  

---

<p align="center">
  <strong>🚀 CT Learning - Development Guide</strong><br>
  <em>Complete guide for developing, testing, and deploying CT Learning</em>
</p>