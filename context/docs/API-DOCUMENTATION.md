# 📚 CT Learning - API Documentation

## 🎯 บทนำ

เอกสารนี้คือคู่มือ API สำหรับระบบ CT Learning อธิบาย endpoints ทั้งหมด พร้อมตัวอย่างการใช้งาน สำหรับนักพัฒนาที่ต้องการเชื่อมต่อกับระบบ

---

## 📋 สารบัญ

1. [Authentication](#authentication)
2. [Base URL & Headers](#base-url--headers)
3. [Response Format](#response-format)
4. [Error Handling](#error-handling)
5. [User APIs](#user-apis)
6. [Course APIs](#course-apis)
7. [Module APIs](#module-apis)
8. [Lesson APIs](#lesson-apis)
9. [Quiz APIs](#quiz-apis)
10. [Certificate APIs](#certificate-apis)
11. [Rate Limiting](#rate-limiting)
12. [Code Examples](#code-examples)

---

## 🔐 Authentication

### Login
```http
POST /login
Content-Type: application/json

{
    "email": "user@example.com",
    "password": "password123"
}
```

**Response:**
```json
{
    "success": true,
    "data": {
        "user": {
            "id": 1,
            "name": "John Doe",
            "email": "john@example.com",
            "role": "student"
        },
        "token": "1|abc123def456..."
    },
    "message": "Login successful"
}
```

### Logout
```http
POST /logout
Authorization: Bearer {token}
```

**Response:**
```json
{
    "success": true,
    "message": "Logged out successfully"
}
```

### Registration
```http
POST /register/student
Content-Type: application/json

{
    "name": "Jane Doe",
    "email": "jane@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

**Response:**
```json
{
    "success": true,
    "data": {
        "user": {
            "id": 2,
            "name": "Jane Doe",
            "email": "jane@example.com",
            "role": "student"
        }
    },
    "message": "Registration successful"
}
```

---

## 🌐 Base URL & Headers

### Base URLs
- **Development**: `http://localhost:8000/api`
- **Production**: `https://ct-learning.com/api`

### Required Headers
```http
Content-Type: application/json
Authorization: Bearer {token}
X-Requested-With: XMLHttpRequest
```

### CSRF Protection
สำหรับ web requests ต้องรวม CSRF token:
```http
X-CSRF-TOKEN: {csrf_token}
```

---

## 📤 Response Format

### Success Response
```json
{
    "success": true,
    "data": {
        // Response data here
    },
    "message": "Operation completed successfully",
    "meta": {
        "pagination": {
            "current_page": 1,
            "last_page": 10,
            "per_page": 15,
            "total": 150
        }
    }
}
```

### Error Response
```json
{
    "success": false,
    "error": {
        "code": "VALIDATION_ERROR",
        "message": "The given data was invalid.",
        "details": {
            "title": ["The title field is required."],
            "email": ["The email must be a valid email address."]
        }
    }
}
```

---

## 🚨 Error Handling

### HTTP Status Codes
- `200` - Success
- `201` - Created
- `400` - Bad Request
- `401` - Unauthorized
- `403` - Forbidden
- `404` - Not Found
- `422` - Validation Error
- `429` - Too Many Requests
- `500` - Internal Server Error

### Error Codes
| Code | Description |
|------|-------------|
| `VALIDATION_ERROR` | Input validation failed |
| `UNAUTHORIZED` | Authentication required |
| `FORBIDDEN` | Insufficient permissions |
| `NOT_FOUND` | Resource not found |
| `RATE_LIMIT_EXCEEDED` | Too many requests |
| `SERVER_ERROR` | Internal server error |

---

## 👤 User APIs

### Get Current User
```http
GET /api/user
Authorization: Bearer {token}
```

**Response:**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "name": "John Doe",
        "email": "john@example.com",
        "role": "student",
        "created_at": "2025-01-01T00:00:00.000000Z",
        "updated_at": "2025-01-01T00:00:00.000000Z"
    }
}
```

### Update Profile
```http
PUT /api/user/profile
Authorization: Bearer {token}
Content-Type: application/json

{
    "name": "John Smith",
    "email": "johnsmith@example.com"
}
```

**Response:**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "name": "John Smith",
        "email": "johnsmith@example.com",
        "role": "student"
    },
    "message": "Profile updated successfully"
}
```

### Change Password
```http
PUT /api/user/password
Authorization: Bearer {token}
Content-Type: application/json

{
    "current_password": "oldpassword123",
    "password": "newpassword123",
    "password_confirmation": "newpassword123"
}
```

---

## 📚 Course APIs

### List Courses (Public)
```http
GET /api/courses?page=1&per_page=15&search=programming
```

**Response:**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "title": "Introduction to Programming",
            "description": "Learn programming basics",
            "cover_image_url": "http://localhost/storage/courses/cover1.jpg",
            "teacher": {
                "id": 10,
                "name": "Dr. Smith"
            },
            "modules_count": 5,
            "lessons_count": 25,
            "enrollments_count": 150,
            "created_at": "2025-01-01T00:00:00.000000Z"
        }
    ],
    "meta": {
        "pagination": {
            "current_page": 1,
            "last_page": 5,
            "per_page": 15,
            "total": 75
        }
    }
}
```

### Get Course Details
```http
GET /api/courses/{id}
Authorization: Bearer {token}
```

**Response:**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "title": "Introduction to Programming",
        "description": "Learn programming basics",
        "cover_image_url": "http://localhost/storage/courses/cover1.jpg",
        "teacher": {
            "id": 10,
            "name": "Dr. Smith",
            "email": "smith@example.com"
        },
        "modules": [
            {
                "id": 1,
                "title": "Getting Started",
                "description": "Introduction to programming",
                "order": 1,
                "lessons_count": 5
            }
        ],
        "is_enrolled": false,
        "progress": 0,
        "created_at": "2025-01-01T00:00:00.000000Z"
    }
}
```

### Enroll in Course (Student)
```http
POST /api/courses/{id}/enroll
Authorization: Bearer {token}
```

**Response:**
```json
{
    "success": true,
    "data": {
        "enrollment_id": 123,
        "course_id": 1,
        "student_id": 1,
        "enrolled_at": "2025-01-15T10:30:00.000000Z"
    },
    "message": "Successfully enrolled in course"
}
```

### Create Course (Teacher)
```http
POST /api/courses
Authorization: Bearer {token}
Content-Type: application/json

{
    "title": "Advanced Web Development",
    "description": "Learn modern web development",
    "cover_image": "base64_encoded_image_or_file_upload"
}
```

**Response:**
```json
{
    "success": true,
    "data": {
        "id": 25,
        "title": "Advanced Web Development",
        "description": "Learn modern web development",
        "teacher_id": 10,
        "cover_image_url": "http://localhost/storage/courses/cover25.jpg",
        "created_at": "2025-01-20T14:30:00.000000Z"
    },
    "message": "Course created successfully"
}
```

### Update Course (Teacher)
```http
PUT /api/courses/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
    "title": "Updated Course Title",
    "description": "Updated description"
}
```

### Delete Course (Teacher)
```http
DELETE /api/courses/{id}
Authorization: Bearer {token}
```

**Response:**
```json
{
    "success": true,
    "message": "Course deleted successfully"
}
```

---

## 📖 Module APIs

### List Course Modules
```http
GET /api/courses/{course_id}/modules
Authorization: Bearer {token}
```

**Response:**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "title": "Getting Started",
            "description": "Introduction to programming",
            "order": 1,
            "lessons_count": 5,
            "completed_lessons": 2,
            "progress": 40
        }
    ]
}
```

### Create Module (Teacher)
```http
POST /api/courses/{course_id}/modules
Authorization: Bearer {token}
Content-Type: application/json

{
    "title": "New Module",
    "description": "Module description",
    "order": 6
}
```

### Get Module Details
```http
GET /api/modules/{id}
Authorization: Bearer {token}
```

**Response:**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "title": "Getting Started",
        "description": "Introduction to programming",
        "order": 1,
        "course": {
            "id": 1,
            "title": "Introduction to Programming"
        },
        "lessons": [
            {
                "id": 1,
                "title": "What is Programming?",
                "content_type": "VIDEO",
                "order": 1,
                "is_completed": false
            }
        ],
        "quiz": {
            "id": 1,
            "title": "Module 1 Quiz",
            "passing_score": 70,
            "time_limit": 30
        }
    }
}
```

### Update Module (Teacher)
```http
PUT /api/modules/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
    "title": "Updated Module Title",
    "description": "Updated description"
}
```

### Delete Module (Teacher)
```http
DELETE /api/modules/{id}
Authorization: Bearer {token}
```

---

## 📝 Lesson APIs

### List Module Lessons
```http
GET /api/modules/{module_id}/lessons
Authorization: Bearer {token}
```

**Response:**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "title": "What is Programming?",
            "content_type": "VIDEO",
            "order": 1,
            "duration": 1200,
            "is_completed": false
        }
    ]
}
```

### Create Lesson (Teacher)
```http
POST /api/modules/{module_id}/lessons
Authorization: Bearer {token}
Content-Type: application/json

{
    "title": "New Lesson",
    "content_type": "VIDEO",
    "content_url": "https://www.youtube.com/watch?v=abc123",
    "order": 6
}
```

### Get Lesson Details
```http
GET /api/lessons/{id}
Authorization: Bearer {token}
```

**Response:**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "title": "What is Programming?",
        "content_type": "VIDEO",
        "content_url": "https://www.youtube.com/embed/abc123",
        "content_text": null,
        "order": 1,
        "module": {
            "id": 1,
            "title": "Getting Started"
        },
        "course": {
            "id": 1,
            "title": "Introduction to Programming"
        },
        "is_completed": false,
        "next_lesson": {
            "id": 2,
            "title": "Setting Up Environment"
        },
        "previous_lesson": null
    }
}
```

### Mark Lesson Complete (Student)
```http
POST /api/lessons/{id}/complete
Authorization: Bearer {token}
```

**Response:**
```json
{
    "success": true,
    "data": {
        "lesson_id": 1,
        "user_id": 1,
        "completed_at": "2025-01-20T15:30:00.000000Z",
        "course_progress": 20.5
    },
    "message": "Lesson marked as complete"
}
```

### Update Lesson (Teacher)
```http
PUT /api/lessons/{id}
Authorization: Bearer {token}
Content-Type: application/json

{
    "title": "Updated Lesson Title",
    "content_type": "PDF",
    "content_url": "path/to/new/file.pdf"
}
```

### Delete Lesson (Teacher)
```http
DELETE /api/lessons/{id}
Authorization: Bearer {token}
```

---

## 📝 Quiz APIs

### Get Quiz Details
```http
GET /api/quizzes/{id}
Authorization: Bearer {token}
```

**Response:**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "title": "Module 1 Quiz",
        "description": "Test your knowledge",
        "passing_score": 70,
        "time_limit": 30,
        "module": {
            "id": 1,
            "title": "Getting Started"
        },
        "questions": [
            {
                "id": 1,
                "question_text": "What is programming?",
                "order": 1,
                "answers": [
                    {
                        "id": 1,
                        "answer_text": "Writing code",
                        "is_correct": true
                    },
                    {
                        "id": 2,
                        "answer_text": "Drawing pictures",
                        "is_correct": false
                    }
                ]
            }
        ],
        "attempts_left": 3,
        "best_score": null
    }
}
```

### Start Quiz (Student)
```http
POST /api/quizzes/{id}/start
Authorization: Bearer {token}
```

**Response:**
```json
{
    "success": true,
    "data": {
        "attempt_id": 123,
        "quiz_id": 1,
        "student_id": 1,
        "started_at": "2025-01-20T16:00:00.000000Z",
        "time_remaining": 1800,
        "questions": [
            {
                "id": 1,
                "question_text": "What is programming?",
                "answers": [
                    {
                        "id": 1,
                        "answer_text": "Writing code"
                    },
                    {
                        "id": 2,
                        "answer_text": "Drawing pictures"
                    }
                ]
            }
        ]
    },
    "message": "Quiz started"
}
```

### Submit Quiz (Student)
```http
POST /api/quizzes/{id}/submit
Authorization: Bearer {token}
Content-Type: application/json

{
    "attempt_id": 123,
    "answers": [
        {
            "question_id": 1,
            "answer_id": 1
        },
        {
            "question_id": 2,
            "answer_id": 5
        }
    ]
}
```

**Response:**
```json
{
    "success": true,
    "data": {
        "attempt_id": 123,
        "score": 85.5,
        "passed": true,
        "correct_answers": 17,
        "total_questions": 20,
        "completed_at": "2025-01-20T16:25:00.000000Z",
        "time_taken": 1500
    },
    "message": "Quiz submitted successfully"
}
```

### Get Quiz Results
```http
GET /api/quizzes/{id}/results
Authorization: Bearer {token}
```

**Response:**
```json
{
    "success": true,
    "data": {
        "attempts": [
            {
                "id": 123,
                "score": 85.5,
                "passed": true,
                "correct_answers": 17,
                "total_questions": 20,
                "completed_at": "2025-01-20T16:25:00.000000Z",
                "time_taken": 1500
            }
        ],
        "best_score": 85.5,
        "attempts_count": 1,
        "passed_attempts": 1
    }
}
```

### Create Quiz (Teacher)
```http
POST /api/modules/{module_id}/quizzes
Authorization: Bearer {token}
Content-Type: application/json

{
    "title": "Module Quiz",
    "description": "Test your knowledge",
    "passing_score": 70,
    "time_limit": 30
}
```

---

## 🎓 Certificate APIs

### Get User Certificates
```http
GET /api/certificates
Authorization: Bearer {token}
```

**Response:**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "certificate_number": "CT-2025-ABC123",
            "course": {
                "id": 1,
                "title": "Introduction to Programming"
            },
            "issued_at": "2025-01-20T17:00:00.000000Z",
            "pdf_url": "http://localhost/storage/certificates/CT-2025-ABC123.pdf"
        }
    ]
}
```

### Download Certificate
```http
GET /api/certificates/{id}/download
Authorization: Bearer {token}
```

**Response:** PDF file download

### Verify Certificate
```http
GET /api/certificates/verify/{certificate_number}
```

**Response:**
```json
{
    "success": true,
    "data": {
        "certificate_number": "CT-2025-ABC123",
        "student": {
            "name": "John Doe"
        },
        "course": {
            "title": "Introduction to Programming"
        },
        "issued_at": "2025-01-20T17:00:00.000000Z",
        "is_valid": true
    }
}
```

---

## ⏱️ Rate Limiting

### Rate Limits
- **Guest Users**: 60 requests per hour
- **Authenticated Users**: 1000 requests per hour
- **Premium Users**: 5000 requests per hour

### Rate Limit Headers
```http
X-RateLimit-Limit: 1000
X-RateLimit-Remaining: 999
X-RateLimit-Reset: 1642694400
```

### Rate Limit Exceeded Response
```json
{
    "success": false,
    "error": {
        "code": "RATE_LIMIT_EXCEEDED",
        "message": "Too many requests. Please try again later.",
        "retry_after": 3600
    }
}
```

---

## 💻 Code Examples

### JavaScript (Fetch API)
```javascript
// Login
const login = async (email, password) => {
    try {
        const response = await fetch('/api/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ email, password })
        });

        const data = await response.json();
        
        if (data.success) {
            localStorage.setItem('token', data.data.token);
            return data.data.user;
        } else {
            throw new Error(data.error.message);
        }
    } catch (error) {
        console.error('Login error:', error);
        throw error;
    }
};

// Get courses
const getCourses = async (page = 1) => {
    const token = localStorage.getItem('token');
    
    try {
        const response = await fetch(`/api/courses?page=${page}`, {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json'
            }
        });

        const data = await response.json();
        return data;
    } catch (error) {
        console.error('Get courses error:', error);
        throw error;
    }
};

// Complete lesson
const completeLesson = async (lessonId) => {
    const token = localStorage.getItem('token');
    
    try {
        const response = await fetch(`/api/lessons/${lessonId}/complete`, {
            method: 'POST',
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });

        const data = await response.json();
        
        if (data.success) {
            // Update progress UI
            updateProgress(data.data.course_progress);
        }
        
        return data;
    } catch (error) {
        console.error('Complete lesson error:', error);
        throw error;
    }
};
```

### PHP (Guzzle)
```php
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class CTLearningAPI
{
    private $client;
    private $token;

    public function __construct($baseUri, $token = null)
    {
        $this->client = new Client([
            'base_uri' => $baseUri,
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ]
        ]);
        
        $this->token = $token;
    }

    public function login($email, $password)
    {
        try {
            $response = $this->client->post('/api/login', [
                'json' => [
                    'email' => $email,
                    'password' => $password
                ]
            ]);

            $data = json_decode($response->getBody(), true);
            
            if ($data['success']) {
                $this->token = $data['data']['token'];
                return $data['data']['user'];
            }
            
            throw new Exception($data['error']['message']);
        } catch (RequestException $e) {
            throw new Exception('Login failed: ' . $e->getMessage());
        }
    }

    public function getCourses($page = 1)
    {
        try {
            $response = $this->client->get('/api/courses', [
                'query' => ['page' => $page],
                'headers' => [
                    'Authorization' => "Bearer {$this->token}"
                ]
            ]);

            return json_decode($response->getBody(), true);
        } catch (RequestException $e) {
            throw new Exception('Get courses failed: ' . $e->getMessage());
        }
    }

    public function completeLesson($lessonId)
    {
        try {
            $response = $this->client->post("/api/lessons/{$lessonId}/complete", [
                'headers' => [
                    'Authorization' => "Bearer {$this->token}"
                ]
            ]);

            return json_decode($response->getBody(), true);
        } catch (RequestException $e) {
            throw new Exception('Complete lesson failed: ' . $e->getMessage());
        }
    }
}
```

### Python (Requests)
```python
import requests
import json

class CTLearningAPI:
    def __init__(self, base_url, token=None):
        self.base_url = base_url
        self.token = token
        self.session = requests.Session()
        self.session.headers.update({
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        })

    def login(self, email, password):
        try:
            response = self.session.post(
                f'{self.base_url}/api/login',
                json={'email': email, 'password': password}
            )
            
            data = response.json()
            
            if data['success']:
                self.token = data['data']['token']
                self.session.headers.update({
                    'Authorization': f'Bearer {self.token}'
                })
                return data['data']['user']
            else:
                raise Exception(data['error']['message'])
        except requests.RequestException as e:
            raise Exception(f'Login failed: {str(e)}')

    def get_courses(self, page=1):
        try:
            response = self.session.get(
                f'{self.base_url}/api/courses',
                params={'page': page}
            )
            return response.json()
        except requests.RequestException as e:
            raise Exception(f'Get courses failed: {str(e)}')

    def complete_lesson(self, lesson_id):
        try:
            response = self.session.post(
                f'{self.base_url}/api/lessons/{lesson_id}/complete'
            )
            return response.json()
        except requests.RequestException as e:
            raise Exception(f'Complete lesson failed: {str(e)}')

# Usage
api = CTLearningAPI('http://localhost:8000')
user = api.login('student@example.com', 'password123')
courses = api.get_courses()
result = api.complete_lesson(1)
```

---

## 🔄 Webhooks

### Lesson Completed Webhook
```http
POST {webhook_url}
Content-Type: application/json
X-CT-Learning-Signature: {signature}

{
    "event": "lesson.completed",
    "data": {
        "user_id": 1,
        "lesson_id": 123,
        "course_id": 45,
        "completed_at": "2025-01-20T15:30:00.000000Z",
        "course_progress": 25.5
    },
    "timestamp": "2025-01-20T15:30:00.000000Z"
}
```

### Course Completed Webhook
```http
POST {webhook_url}
Content-Type: application/json
X-CT-Learning-Signature: {signature}

{
    "event": "course.completed",
    "data": {
        "user_id": 1,
        "course_id": 45,
        "completed_at": "2025-01-20T16:00:00.000000Z",
        "certificate_issued": true
    },
    "timestamp": "2025-01-20T16:00:00.000000Z"
}
```

---

## 📝 Testing API

### Using Postman
1. Import collection from `postman_collection.json`
2. Set environment variables:
   - `base_url`: `http://localhost:8000/api`
   - `token`: `{your_auth_token}`
3. Use Pre-request script for auth:
   ```javascript
   if (pm.environment.get("token")) {
       pm.request.headers.add({
           key: 'Authorization',
           value: 'Bearer ' + pm.environment.get("token")
       });
   }
   ```

### Using cURL
```bash
# Login
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -d '{"email":"student@example.com","password":"password123"}'

# Get courses
curl -X GET http://localhost:8000/api/courses \
  -H "Authorization: Bearer {token}"

# Complete lesson
curl -X POST http://localhost:8000/api/lessons/1/complete \
  -H "Authorization: Bearer {token}"
```

---

## 📚 Additional Resources

### SDKs & Libraries
- **JavaScript**: `@ct-learning/api-client` (Coming soon)
- **PHP**: `ct-learning/laravel-sdk` (Coming soon)
- **Python**: `ct-learning-python` (Coming soon)
- **Node.js**: `ct-learning-node` (Coming soon)

### API Versioning
- Current version: `v1`
- Version in URL: `/api/v1/...`
- Backward compatibility maintained for 6 months

### Changelog
- **v1.2.0** - Added certificate verification endpoint
- **v1.1.0** - Added quiz analytics
- **v1.0.0** - Initial API release

---

## 🆘 Support

### API Support
- **Email**: api-support@ct.ac.th
- **Documentation**: https://docs.ct-learning.com/api
- **Status Page**: https://status.ct-learning.com

### Reporting Issues
1. Check existing issues on GitHub
2. Create new issue with:
   - API endpoint
   - Request/Response details
   - Error messages
   - Expected vs actual behavior

---

**อัพเดทล่าสุด**: 29 พฤศจิกายน 2025  
**เวอร์ชัน API**: v1.2.0  
**ผู้เขียน**: CT Learning API Team  
**สถานะ**: ✅ Complete & Maintained  

---

<p align="center">
  <strong>📚 CT Learning - API Documentation</strong><br>
  <em>Complete REST API documentation for developers</em>
</p>