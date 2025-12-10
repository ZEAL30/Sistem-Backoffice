# Database Schema Documentation

## Overview
Minimal Laravel e-commerce schema with RBAC, product catalog, and blog. Orders via WhatsApp.

*Database:* MySQL 8.0+  
*Framework:* Laravel 10+  
*Tables:* 9 core tables only

---

## Tables Structure

### 1. *users*
All user accounts

*Fields:*
- id - BIGINT UNSIGNED (PK)
- role_id - BIGINT UNSIGNED (FK → roles.id)
- name - VARCHAR(255)
- email - VARCHAR(255) UNIQUE
- email_verified_at - TIMESTAMP NULL
- phone_number - VARCHAR(20) NULL
- password - VARCHAR(255)
- avatar - VARCHAR(255) NULL
- remember_token - VARCHAR(100) NULL
- created_at, updated_at, deleted_at

---

### 2. *roles*
User roles (Admin, Editor, Customer)

*Fields:*
- id - BIGINT UNSIGNED (PK)
- name - VARCHAR(50) UNIQUE
- slug - VARCHAR(50) UNIQUE
- permissions - JSON (store all permissions here)
- created_at, updated_at

*Example Data:*
json
{
  "slug": "admin",
  "permissions": ["products.*", "posts.*", "users.*"]
}
{
  "slug": "editor", 
  "permissions": ["products.view", "products.edit", "posts.*"]
}
{
  "slug": "customer",
  "permissions": []
}


*Note:* Permissions stored as JSON array - no need for separate permissions table!

---

### 3. *categories*
Categories for both products AND posts

*Fields:*
- id - BIGINT UNSIGNED (PK)
- parent_id - BIGINT UNSIGNED NULL (FK → categories.id)
- name - VARCHAR(255)
- slug - VARCHAR(255) UNIQUE
- type - ENUM('product', 'post')
- image - VARCHAR(255) NULL
- is_active - BOOLEAN DEFAULT TRUE
- created_at, updated_at

*Usage:*
- Same table for both products and posts
- Use type field to differentiate
- Hierarchical support via parent_id

---

### 4. *categorizables*
Polymorphic pivot: Links categories to products/posts

*Fields:*
- id - BIGINT UNSIGNED (PK)
- category_id - BIGINT UNSIGNED (FK → categories.id)
- categorizable_id - BIGINT UNSIGNED
- categorizable_type - VARCHAR(50) ('App\Models\Product' or 'App\Models\Post')

*Replaces:* product_categories + post_categories tables  
*Benefit:* One table handles both!

---

### 5. *products*
Product catalog

*Fields:*
- id - BIGINT UNSIGNED (PK)
- sku - VARCHAR(100) UNIQUE
- name - VARCHAR(255)
- slug - VARCHAR(255) UNIQUE
- description - TEXT NULL
- price - DECIMAL(12, 2)
- sale_price - DECIMAL(12, 2) NULL
- stock - INT DEFAULT 0
- images - JSON (array of image paths)
- status - ENUM('draft', 'published') DEFAULT 'draft'
- is_featured - BOOLEAN DEFAULT FALSE
- created_by - BIGINT UNSIGNED (FK → users.id)
- created_at, updated_at, deleted_at

*Images as JSON:*
json
{
  "images": [
    "/uploads/product-1.jpg",
    "/uploads/product-2.jpg"
  ]
}


*Note:* No separate product_images table needed!

---

### 6. *posts*
Blog posts and pages

*Fields:*
- id - BIGINT UNSIGNED (PK)
- author_id - BIGINT UNSIGNED (FK → users.id)
- title - VARCHAR(255)
- slug - VARCHAR(255) UNIQUE
- excerpt - TEXT NULL
- content - LONGTEXT NULL
- featured_image - VARCHAR(255) NULL
- type - ENUM('post', 'page') DEFAULT 'post'
- status - ENUM('draft', 'published') DEFAULT 'draft'
- published_at - TIMESTAMP NULL
- created_at, updated_at, deleted_at

---

### 7. *tags*
Tags for products and posts

*Fields:*
- id - BIGINT UNSIGNED (PK)
- name - VARCHAR(100)
- slug - VARCHAR(100) UNIQUE
- created_at, updated_at

---

### 8. *taggables*
Polymorphic pivot: Links tags to products/posts

*Fields:*
- id - BIGINT UNSIGNED (PK)
- tag_id - BIGINT UNSIGNED (FK → tags.id)
- taggable_id - BIGINT UNSIGNED
- taggable_type - VARCHAR(50)

---

### 9. *settings*
Site configuration (key-value store)

*Fields:*
- id - BIGINT UNSIGNED (PK)
- key - VARCHAR(100) UNIQUE
- value - LONGTEXT NULL
- type - ENUM('text', 'textarea', 'image', 'json') DEFAULT 'text'
- group - VARCHAR(50) NULL
- created_at, updated_at

*Example Data:*

key: site_name, value: "My Store"
key: about_company, value: "This company..."
key: contact_email, value: "hello@example.com"
key: whatsapp_number, value: "+6281234567890"
key: social_media, value: {"facebook": "...", "instagram": "..."}


---

## Database Relationships


users (N) ───> (1) roles
  │
  ├──> (N) products [created_by]
  │
  └──> (N) posts [author_id]

categories (1) ───> (N) categories [parent_id] (self-reference)
  │
  └──> (N) categorizables (polymorphic)
            ├──> products
            └──> posts

tags (1) ───> (N) taggables (polymorphic)
            ├──> products
            └──> posts


---

## Simplified Architecture Benefits

| What We Removed | Why |
|-----------------|-----|
| ❌ permissions table | Stored as JSON in roles |
| ❌ role_permission pivot | Not needed with JSON |
| ❌ product_categories | Use polymorphic categorizables |
| ❌ post_categories | Use polymorphic categorizables |
| ❌ product_images | Store as JSON array in products |
| ❌ file_metadata | Store paths directly (or add later if needed) |

*Result:* 9 tables instead of 14+ tables!

---

## Laravel Model Relationships

### User.php
php
public function role() {
    return $this->belongsTo(Role::class);
}

public function products() {
    return $this->hasMany(Product::class, 'created_by');
}

public function posts() {
    return $this->hasMany(Post::class, 'author_id');
}


### Product.php
php
public function creator() {
    return $this->belongsTo(User::class, 'created_by');
}

public function categories() {
    return $this->morphToMany(Category::class, 'categorizable');
}

public function tags() {
    return $this->morphToMany(Tag::class, 'taggable');
}

// Handle images JSON
protected $casts = ['images' => 'array'];


### Post.php
php
public function author() {
    return $this->belongsTo(User::class, 'author_id');
}

public function categories() {
    return $this->morphToMany(Category::class, 'categorizable');
}

public function tags() {
    return $this->morphToMany(Tag::class, 'taggable');
}


### Category.php
php
public function parent() {
    return $this->belongsTo(Category::class, 'parent_id');
}

public function children() {
    return $this->hasMany(Category::class, 'parent_id');
}

public function products() {
    return $this->morphedByMany(Product::class, 'categorizable');
}

public function posts() {
    return $this->morphedByMany(Post::class, 'categorizable');
}


---

## Permission Check Example

php
// In Role model
public function hasPermission($permission) {
    $permissions = $this->permissions ?? [];
    
    // Check exact match
    if (in_array($permission, $permissions)) {
        return true;
    }
    
    // Check wildcard (e.g., "products.*")
    foreach ($permissions as $p) {
        if (str_ends_with($p, '.*')) {
            $prefix = str_replace('.*', '', $p);
            if (str_starts_with($permission, $prefix)) {
                return true;
            }
        }
    }
    
    return false;
}

// Usage
if (auth()->user()->role->hasPermission('products.edit')) {
    // Allow edit
}


---

## Migration Notes

*JSON Fields:*
- roles.permissions - Store permissions array
- products.images - Store image paths array
- settings.value - When type is 'json'

*Cast in Models:*
php
protected $casts = [
    'permissions' => 'array', // roles
    'images' => 'array',      // products
];


*Polymorphic Relations:*
- categorizables - category_id, categorizable_id, categorizable_type
- taggables - tag_id, taggable_id, taggable_type

---

## Quick Start SQL

*Total: 9 tables only*
1. users
2. roles
3. categories
4. categorizables (polymorphic pivot)
5. products
6. posts
7. tags
8. taggables (polymorphic pivot)
9. settings

*WhatsApp Integration:*
Store WhatsApp number in settings table, then link from product pages.

*File Storage:*
Store image paths as strings (products.featured_image) or JSON arrays (products.images). Use Laravel's Storage facade for file handling.
