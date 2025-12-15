# REST API Documentation

## Base URL
```
/api/v1
```

## Authentication

The API uses Laravel Sanctum for token-based authentication. Most endpoints require authentication.

### Getting an Access Token

**POST** `/api/v1/auth/login`

**Request Body:**
```json
{
  "email": "user@example.com",
  "password": "password123"
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
      "email": "user@example.com",
      "role": "Administrator"
    },
    "token": "1|xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx"
  }
}
```

### Using the Token

Include the token in the `Authorization` header:
```
Authorization: Bearer {token}
```

---

## Endpoints

### Authentication

#### Login
**POST** `/api/v1/auth/login`

**Request Body:**
```json
{
  "email": "string (required)",
  "password": "string (required)"
}
```

**Response:** `200 OK`
```json
{
  "success": true,
  "message": "Login successful",
  "data": {
    "user": {
      "id": 1,
      "name": "John Doe",
      "email": "user@example.com",
      "role": "Administrator"
    },
    "token": "1|xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx"
  }
}
```

#### Logout
**POST** `/api/v1/auth/logout`

**Headers:** `Authorization: Bearer {token}`

**Response:** `200 OK`
```json
{
  "success": true,
  "message": "Logged out successfully"
}
```

#### Get Current User
**GET** `/api/v1/auth/me`

**Headers:** `Authorization: Bearer {token}`

**Response:** `200 OK`
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "John Doe",
    "email": "user@example.com",
    "phone_number": "+1234567890",
    "role": "Administrator",
    "status": "active"
  }
}
```

---

### Posts

#### List Posts (Public)
**GET** `/api/v1/posts`

**Query Parameters:**
- `search` (string, optional) - Search in title, excerpt, content
- `category` (string, optional) - Filter by category slug
- `sort` (string, optional) - Sort order: `latest` (default) or `oldest`
- `per_page` (integer, optional) - Items per page (default: 9)

**Example:** `/api/v1/posts?search=laravel&category=tutorials&sort=latest&per_page=10`

**Response:** `200 OK`
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "title": "Post Title",
      "slug": "post-title",
      "excerpt": "Post excerpt",
      "content": "Post content",
      "featured_image": "path/to/image.jpg",
      "type": "post",
      "status": "published",
      "published_at": "2025-01-01T00:00:00.000000Z",
      "author": {
        "id": 1,
        "name": "Author Name",
        "email": "author@example.com"
      },
      "categories": [
        {
          "id": 1,
          "name": "Category Name",
          "slug": "category-slug"
        }
      ]
    }
  ],
  "meta": {
    "current_page": 1,
    "last_page": 5,
    "per_page": 9,
    "total": 45
  }
}
```

#### Get Post (Public)
**GET** `/api/v1/posts/{slug}`

**Response:** `200 OK`
```json
{
  "success": true,
  "data": {
    "id": 1,
    "title": "Post Title",
    "slug": "post-title",
    "excerpt": "Post excerpt",
    "content": "Post content",
    "featured_image": "path/to/image.jpg",
    "type": "post",
    "status": "published",
    "published_at": "2025-01-01T00:00:00.000000Z",
    "author": {
      "id": 1,
      "name": "Author Name",
      "email": "author@example.com"
    },
    "categories": [...],
    "comments": [...]
  }
}
```

#### Create Post
**POST** `/api/v1/posts`

**Headers:** `Authorization: Bearer {token}`

**Request Body:**
```json
{
  "title": "string (required, max:255)",
  "slug": "string (required, max:255, unique)",
  "excerpt": "string (optional)",
  "content": "string (optional)",
  "featured_image": "string (optional)",
  "type": "string (required, enum: post|page)",
  "status": "string (required, enum: draft|published)",
  "published_at": "datetime (optional, format: Y-m-d H:i:s)",
  "categories": "array (optional)",
  "categories.*": "integer (exists:categories,id)"
}
```

**Response:** `201 Created`
```json
{
  "success": true,
  "message": "Post created successfully",
  "data": { ... }
}
```

#### Update Post
**PUT/PATCH** `/api/v1/posts/{slug}`

**Headers:** `Authorization: Bearer {token}`

**Request Body:** (All fields optional for PATCH, required for PUT)
```json
{
  "title": "string (optional, max:255)",
  "slug": "string (optional, max:255, unique)",
  "excerpt": "string (optional)",
  "content": "string (optional)",
  "featured_image": "string (optional)",
  "type": "string (optional, enum: post|page)",
  "status": "string (optional, enum: draft|published)",
  "published_at": "datetime (optional)",
  "categories": "array (optional)",
  "categories.*": "integer (exists:categories,id)"
}
```

**Response:** `200 OK`
```json
{
  "success": true,
  "message": "Post updated successfully",
  "data": { ... }
}
```

#### Delete Post
**DELETE** `/api/v1/posts/{slug}`

**Headers:** `Authorization: Bearer {token}`

**Response:** `200 OK`
```json
{
  "success": true,
  "message": "Post deleted successfully"
}
```

---

### Products

#### List Products (Public)
**GET** `/api/v1/products`

**Query Parameters:**
- `search` (string, optional) - Search by product name
- `category` (integer, optional) - Filter by category ID
- `min_price` (decimal, optional) - Minimum price filter
- `max_price` (decimal, optional) - Maximum price filter
- `sort` (string, optional) - Sort by: `created_at` (default), `price`, or `name`
- `per_page` (integer, optional) - Items per page (default: 12)

**Example:** `/api/v1/products?search=laptop&min_price=100&max_price=1000&sort=price&per_page=20`

**Response:** `200 OK`
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Product Name",
      "slug": "product-name",
      "description": "Product description",
      "price": "99.99",
      "featured_image": "path/to/image.jpg",
      "is_active": true,
      "categories": [...]
    }
  ],
  "meta": {
    "current_page": 1,
    "last_page": 3,
    "per_page": 12,
    "total": 30
  }
}
```

#### Get Product (Public)
**GET** `/api/v1/products/{slug}`

**Response:** `200 OK`
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "Product Name",
    "slug": "product-name",
    "description": "Product description",
    "price": "99.99",
    "featured_image": "path/to/image.jpg",
    "is_active": true,
    "categories": [...]
  }
}
```

#### Create Product
**POST** `/api/v1/products`

**Headers:** `Authorization: Bearer {token}`

**Request Body:**
```json
{
  "name": "string (required, max:255)",
  "slug": "string (required, max:255, unique)",
  "description": "string (optional)",
  "price": "decimal (required, min:0)",
  "featured_image": "string (optional)",
  "is_active": "boolean (optional, default: true)",
  "categories": "array (optional)",
  "categories.*": "integer (exists:categories,id)"
}
```

**Response:** `201 Created`
```json
{
  "success": true,
  "message": "Product created successfully",
  "data": { ... }
}
```

#### Update Product
**PUT/PATCH** `/api/v1/products/{slug}`

**Headers:** `Authorization: Bearer {token}`

**Request Body:** (All fields optional for PATCH, required for PUT)
```json
{
  "name": "string (optional, max:255)",
  "slug": "string (optional, max:255, unique)",
  "description": "string (optional)",
  "price": "decimal (optional, min:0)",
  "featured_image": "string (optional)",
  "is_active": "boolean (optional)",
  "categories": "array (optional)",
  "categories.*": "integer (exists:categories,id)"
}
```

**Response:** `200 OK`
```json
{
  "success": true,
  "message": "Product updated successfully",
  "data": { ... }
}
```

#### Delete Product
**DELETE** `/api/v1/products/{slug}`

**Headers:** `Authorization: Bearer {token}`

**Response:** `200 OK`
```json
{
  "success": true,
  "message": "Product deleted successfully"
}
```

---

### Categories

#### List Categories (Public)
**GET** `/api/v1/categories`

**Query Parameters:**
- `type` (string, optional) - Filter by type: `product` or `post`
- `is_active` (boolean, optional) - Filter by active status

**Example:** `/api/v1/categories?type=product&is_active=true`

**Response:** `200 OK`
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Category Name",
      "slug": "category-slug",
      "type": "product",
      "image": "path/to/image.jpg",
      "is_active": true
    }
  ]
}
```

#### List Categories by Type (Public)
**GET** `/api/v1/categories/{type}`

**Response:** `200 OK`
```json
{
  "success": true,
  "data": [...]
}
```

#### Create Category
**POST** `/api/v1/categories`

**Headers:** `Authorization: Bearer {token}`

**Request Body:**
```json
{
  "name": "string (required, max:255)",
  "type": "string (required, enum: product|post)",
  "slug": "string (optional, max:255, auto-generated if not provided)",
  "image": "string (optional)",
  "is_active": "boolean (optional, default: true)"
}
```

**Response:** `201 Created`
```json
{
  "success": true,
  "message": "Category created successfully",
  "data": { ... }
}
```

#### Update Category
**PUT/PATCH** `/api/v1/categories/{id}`

**Headers:** `Authorization: Bearer {token}`

**Request Body:** (All fields optional for PATCH, required for PUT)
```json
{
  "name": "string (optional, max:255)",
  "type": "string (optional, enum: product|post)",
  "slug": "string (optional, max:255)",
  "image": "string (optional)",
  "is_active": "boolean (optional)"
}
```

**Response:** `200 OK`
```json
{
  "success": true,
  "message": "Category updated successfully",
  "data": { ... }
}
```

#### Delete Category
**DELETE** `/api/v1/categories/{id}`

**Headers:** `Authorization: Bearer {token}`

**Response:** `200 OK`
```json
{
  "success": true,
  "message": "Category deleted successfully"
}
```

---

### Comments

#### Create Comment (Public)
**POST** `/api/v1/posts/{slug}/comments`

**Request Body:**
```json
{
  "author_name": "string (required, max:255, regex: /^[a-zA-Z0-9\\s\\-\\.]+$/)",
  "author_email": "string (required, email, max:255)",
  "content": "string (required, min:5, max:1000)"
}
```

**Response:** `201 Created`
```json
{
  "success": true,
  "message": "Comment submitted successfully",
  "data": {
    "id": 1,
    "post_id": 1,
    "author_name": "John Doe",
    "author_email": "john@example.com",
    "content": "Great post!",
    "is_approved": true,
    "spam_status": "clean",
    "created_at": "2025-01-01T00:00:00.000000Z"
  }
}
```

#### List Comments
**GET** `/api/v1/comments`

**Headers:** `Authorization: Bearer {token}` (Admin only)

**Query Parameters:**
- `status` (string, optional) - Filter by status: `approved` or `pending`
- `post_id` (integer, optional) - Filter by post ID
- `per_page` (integer, optional) - Items per page (default: 20)

**Example:** `/api/v1/comments?status=pending&post_id=1&per_page=10`

**Response:** `200 OK`
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "post_id": 1,
      "author_name": "John Doe",
      "author_email": "john@example.com",
      "content": "Great post!",
      "is_approved": true,
      "spam_status": "clean",
      "post": { ... },
      "user": { ... }
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

#### Approve Comment
**POST** `/api/v1/comments/{comment}/approve`

**Headers:** `Authorization: Bearer {token}` (Admin only)

**Response:** `200 OK`
```json
{
  "success": true,
  "message": "Comment approved successfully",
  "data": { ... }
}
```

#### Reject Comment
**POST** `/api/v1/comments/{comment}/reject`

**Headers:** `Authorization: Bearer {token}` (Admin only)

**Response:** `200 OK`
```json
{
  "success": true,
  "message": "Comment rejected successfully",
  "data": { ... }
}
```

#### Flag Comment
**POST** `/api/v1/comments/{comment}/flag`

**Headers:** `Authorization: Bearer {token}` (Admin only)

**Request Body:**
```json
{
  "flag_reason": "string (required, max:255)"
}
```

**Response:** `200 OK`
```json
{
  "success": true,
  "message": "Comment flagged successfully",
  "data": { ... }
}
```

#### Delete Comment
**DELETE** `/api/v1/comments/{comment}`

**Headers:** `Authorization: Bearer {token}` (Admin only)

**Response:** `200 OK`
```json
{
  "success": true,
  "message": "Comment deleted successfully"
}
```

---

### Media

#### List Media
**GET** `/api/v1/media`

**Headers:** `Authorization: Bearer {token}`

**Query Parameters:**
- `user_id` (integer, optional) - Filter by user ID
- `search` (string, optional) - Search by filename or alt_text
- `per_page` (integer, optional) - Items per page (default: 20)

**Example:** `/api/v1/media?user_id=1&search=image&per_page=10`

**Response:** `200 OK`
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "filename": "image.jpg",
      "path": "media/image.jpg",
      "mime_type": "image/jpeg",
      "size": 102400,
      "alt_text": "Alt text",
      "description": "Description",
      "user_id": 1,
      "created_at": "2025-01-01T00:00:00.000000Z"
    }
  ],
  "meta": {
    "current_page": 1,
    "last_page": 3,
    "per_page": 20,
    "total": 50
  }
}
```

#### Get Media
**GET** `/api/v1/media/{id}`

**Headers:** `Authorization: Bearer {token}`

**Response:** `200 OK`
```json
{
  "id": 1,
  "filename": "image.jpg",
  "path": "media/image.jpg",
  "mime_type": "image/jpeg",
  "size": 102400,
  "alt_text": "Alt text",
  "description": "Description",
  "user_id": 1,
  "created_at": "2025-01-01T00:00:00.000000Z"
}
```

#### Upload Media
**POST** `/api/v1/media`

**Headers:** `Authorization: Bearer {token}`

**Request Body:** (multipart/form-data)
- `media` or `file` (file, required) - Image file (jpeg, png, jpg, gif, webp, max: 5120KB)
- `alt_text` (string, optional, max:255)
- `description` (string, optional)

**Response:** `200 OK`
```json
{
  "success": true,
  "message": "File uploaded successfully",
  "media": {
    "id": 1,
    "filename": "image.jpg",
    "path": "media/image.jpg",
    ...
  },
  "url": "http://example.com/storage/media/image.jpg"
}
```

#### Update Media
**PUT/PATCH** `/api/v1/media/{id}`

**Headers:** `Authorization: Bearer {token}`

**Request Body:** (All fields optional for PATCH, required for PUT)
```json
{
  "alt_text": "string (optional, max:255)",
  "description": "string (optional)"
}
```

**Response:** `200 OK`
```json
{
  "success": true,
  "message": "Media updated successfully",
  "media": { ... }
}
```

#### Delete Media
**DELETE** `/api/v1/media/{id}`

**Headers:** `Authorization: Bearer {token}`

**Response:** `200 OK`
```json
{
  "success": true,
  "message": "Media deleted successfully"
}
```

---

### Users (Admin Only)

#### List Users
**GET** `/api/v1/users`

**Headers:** `Authorization: Bearer {token}` (Administrator only)

**Response:** `200 OK`
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "John Doe",
      "email": "john@example.com",
      "phone_number": "+1234567890",
      "role": "Administrator",
      "status": "active",
      "created_at": "2025-01-01T00:00:00.000000Z"
    }
  ]
}
```

#### Get User
**GET** `/api/v1/users/{id}`

**Headers:** `Authorization: Bearer {token}` (Administrator only)

**Response:** `200 OK`
```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com",
    "phone_number": "+1234567890",
    "role": "Administrator",
    "role_id": 1,
    "status": "active",
    "created_at": "2025-01-01T00:00:00.000000Z",
    "updated_at": "2025-01-01T00:00:00.000000Z"
  }
}
```

#### Create User
**POST** `/api/v1/users`

**Headers:** `Authorization: Bearer {token}` (Administrator only)

**Request Body:**
```json
{
  "name": "string (required, max:255)",
  "email": "string (required, email, unique)",
  "phone_number": "string (optional, max:20)",
  "password": "string (required, min:8)",
  "role_id": "integer (required, exists:roles,id)",
  "status": "string (required, enum: active|inactive)"
}
```

**Response:** `201 Created`
```json
{
  "success": true,
  "message": "User created successfully",
  "data": {
    "id": 2,
    "name": "Jane Doe",
    "email": "jane@example.com",
    "phone_number": "+1234567890",
    "role": "Editor",
    "status": "active"
  }
}
```

#### Update User
**PUT/PATCH** `/api/v1/users/{id}`

**Headers:** `Authorization: Bearer {token}` (Administrator only)

**Request Body:** (All fields optional for PATCH, required for PUT)
```json
{
  "name": "string (optional, max:255)",
  "email": "string (optional, email, unique)",
  "phone_number": "string (optional, max:20)",
  "password": "string (optional, min:8)",
  "role_id": "integer (optional, exists:roles,id)",
  "status": "string (optional, enum: active|inactive)"
}
```

**Response:** `200 OK`
```json
{
  "success": true,
  "message": "User updated successfully",
  "data": { ... }
}
```

#### Delete User
**DELETE** `/api/v1/users/{id}`

**Headers:** `Authorization: Bearer {token}` (Administrator only)

**Response:** `200 OK`
```json
{
  "success": true,
  "message": "User deleted successfully"
}
```

---

## Error Responses

All endpoints return errors in the following format:

**400 Bad Request**
```json
{
  "success": false,
  "message": "Validation error",
  "errors": {
    "field_name": ["Error message"]
  }
}
```

**401 Unauthorized**
```json
{
  "success": false,
  "message": "Unauthenticated"
}
```

**403 Forbidden**
```json
{
  "success": false,
  "message": "Unauthorized"
}
```

**404 Not Found**
```json
{
  "success": false,
  "message": "Resource not found"
}
```

**422 Unprocessable Entity**
```json
{
  "success": false,
  "message": "Validation error",
  "errors": {
    "field_name": ["Error message"]
  }
}
```

**500 Internal Server Error**
```json
{
  "success": false,
  "message": "Server error"
}
```

---

## Notes

1. **Authentication**: Most endpoints require authentication via Bearer token. Public endpoints are clearly marked.

2. **Pagination**: List endpoints support pagination via `per_page` query parameter. Response includes `meta` object with pagination info.

3. **Filtering**: Most list endpoints support filtering via query parameters.

4. **Search**: Posts and Products support search functionality via `search` query parameter.

5. **Roles**: Some endpoints require specific roles (Administrator, Editor). Check endpoint documentation for requirements.

6. **Sanitization**: Content fields (post content, product description) are automatically sanitized using HTMLSanitizer.

7. **Spam Protection**: Comments are automatically checked for spam. Comments with high spam scores require admin approval.

8. **File Uploads**: Media uploads accept multipart/form-data format.

9. **Soft Deletes**: Some resources use soft deletes and may not appear in listings after deletion.

10. **Date Formats**: All dates are returned in ISO 8601 format (e.g., `2025-01-01T00:00:00.000000Z`).

