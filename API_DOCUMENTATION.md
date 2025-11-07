# Mapala Pagaruyung API Documentation

Base URL: `/api/v1/mapala`

All authenticated endpoints require Bearer token in Authorization header:
```
Authorization: Bearer {your_token}
```

## Authentication

### Register
```http
POST /api/v1/mapala/auth/register
Content-Type: application/json

{
  "name": "John Doe",
  "email": "john@example.com",
  "username": "johndoe",
  "password": "password123",
  "password_confirmation": "password123",
  "phone": "08123456789"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Registration successful",
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com",
      "username": "johndoe",
      "phone": "08123456789",
      "roles": ["Member"]
    },
    "token": "1|abc123..."
  }
}
```

### Login
```http
POST /api/v1/mapala/auth/login
Content-Type: application/json

{
  "email": "john@example.com",
  "password": "password123",
  "device_name": "mobile-app"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Login successful",
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com",
      "cohort": {
        "id": 1,
        "name": "Kader XXIV",
        "year": 2024
      },
      "division": {
        "id": 1,
        "name": "Divisi Pendakian"
      },
      "roles": ["Member"],
      "is_active": true
    },
    "token": "2|xyz789..."
  }
}
```

### Logout
```http
POST /api/v1/mapala/auth/logout
Authorization: Bearer {token}
```

### Get Current User
```http
GET /api/v1/mapala/auth/me
Authorization: Bearer {token}
```

### Update Profile
```http
PUT /api/v1/mapala/auth/profile
Authorization: Bearer {token}
Content-Type: application/json

{
  "name": "John Doe Updated",
  "phone": "08129876543",
  "address": "Jl. Example No. 123",
  "bio": "Member aktif Mapala",
  "emergency_contact_name": "Jane Doe",
  "emergency_contact_phone": "08198765432",
  "emergency_contact_relation": "Sibling",
  "blood_type": "A+",
  "allergies": "Tidak ada",
  "medical_conditions": "Sehat"
}
```

### Change Password
```http
PUT /api/v1/mapala/auth/password
Authorization: Bearer {token}
Content-Type: application/json

{
  "current_password": "oldpassword",
  "password": "newpassword",
  "password_confirmation": "newpassword"
}
```

---

## Dashboard

### Get Dashboard Statistics
```http
GET /api/v1/mapala/dashboard
Authorization: Bearer {token}
```

**Response:**
```json
{
  "success": true,
  "data": {
    "statistics": {
      "expeditions": {
        "total": 15,
        "completed": 12
      },
      "training": {
        "total": 8,
        "completed": 7
      },
      "competitions": {
        "total": 5,
        "medals": 3
      },
      "equipment": {
        "total_borrowed": 20,
        "active": 2,
        "overdue": 0
      }
    },
    "recent_activities": {
      "expeditions": [...],
      "training": [...]
    },
    "active_equipment": [...]
  }
}
```

---

## Activities

### Get Available Activities
```http
GET /api/v1/mapala/activities/available
Authorization: Bearer {token}
```

Returns open registrations for expeditions, training, and competitions.

### Get Expedition History
```http
GET /api/v1/mapala/activities/expeditions?page=1
Authorization: Bearer {token}
```

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "expedition": {
        "id": 5,
        "title": "Pendakian Gunung Kerinci",
        "destination": "Gunung Kerinci, Jambi",
        "start_date": "2024-12-01T00:00:00.000000Z",
        "status": "completed"
      },
      "role": "participant",
      "status": "completed",
      "performance_rating": 4.5
    }
  ],
  "meta": {
    "current_page": 1,
    "last_page": 2,
    "per_page": 20,
    "total": 35
  }
}
```

### Get Expedition Detail
```http
GET /api/v1/mapala/activities/expeditions/{id}
Authorization: Bearer {token}
```

### Get Training History
```http
GET /api/v1/mapala/activities/training?page=1
Authorization: Bearer {token}
```

### Get Competition History
```http
GET /api/v1/mapala/activities/competitions?page=1
Authorization: Bearer {token}
```

---

## Equipment

### Get Available Equipment
```http
GET /api/v1/mapala/equipment?category_id=1&search=tenda&page=1
Authorization: Bearer {token}
```

**Query Parameters:**
- `category_id` (optional): Filter by category
- `search` (optional): Search by name or code
- `page` (optional): Page number

### Get Equipment Categories
```http
GET /api/v1/mapala/equipment/categories
Authorization: Bearer {token}
```

### Get Borrowing History
```http
GET /api/v1/mapala/equipment/borrowings?page=1
Authorization: Bearer {token}
```

### Get Active Borrowings
```http
GET /api/v1/mapala/equipment/borrowings/active
Authorization: Bearer {token}
```

### Request Equipment Borrowing
```http
POST /api/v1/mapala/equipment/borrowings
Authorization: Bearer {token}
Content-Type: application/json

{
  "equipment_id": 5,
  "borrow_date": "2024-12-15",
  "due_date": "2024-12-20",
  "quantity_borrowed": 2,
  "purpose": "expedition",
  "purpose_details": "Pendakian Gunung Singgalang"
}
```

**Purpose values:**
- `expedition`
- `training`
- `competition`
- `event`
- `personal`
- `other`

---

## Gallery

### Get Galleries
```http
GET /api/v1/mapala/gallery?category_id=1&search=kerinci&featured=1&page=1
Authorization: Bearer {token}
```

**Query Parameters:**
- `category_id` (optional): Filter by category
- `search` (optional): Search by title or description
- `featured` (optional): 1 for featured only
- `page` (optional): Page number

### Get Gallery Categories
```http
GET /api/v1/mapala/gallery/categories
Authorization: Bearer {token}
```

### Get Gallery Detail
```http
GET /api/v1/mapala/gallery/{id}
Authorization: Bearer {token}
```

---

## Error Responses

### 401 Unauthorized
```json
{
  "message": "Unauthenticated."
}
```

### 403 Forbidden
```json
{
  "success": false,
  "message": "Your account is inactive. Please contact administrator."
}
```

### 404 Not Found
```json
{
  "success": false,
  "message": "Resource not found"
}
```

### 422 Validation Error
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "email": [
      "The email field is required."
    ],
    "password": [
      "The password field is required."
    ]
  }
}
```

### 400 Bad Request
```json
{
  "success": false,
  "message": "Insufficient equipment available. Only 2 unit available."
}
```

---

## Rate Limiting

API endpoints are rate limited to prevent abuse. Default limits:
- **Public endpoints**: 60 requests per minute
- **Authenticated endpoints**: 120 requests per minute

Rate limit headers are included in responses:
```
X-RateLimit-Limit: 60
X-RateLimit-Remaining: 59
X-RateLimit-Reset: 1638360000
```

---

## Pagination

Paginated responses follow this structure:
```json
{
  "success": true,
  "data": [...],
  "meta": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 20,
    "total": 95
  }
}
```

---

## Date Formats

All dates are returned in ISO 8601 format:
```
2024-12-15T08:30:00.000000Z
```

When sending dates in requests, use `YYYY-MM-DD` format:
```
2024-12-15
```
