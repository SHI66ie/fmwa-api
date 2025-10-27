# 🗄️ Database Structure Diagram

## Entity Relationship Overview

```
┌─────────────────┐
│     USERS       │
│─────────────────│
│ id (PK)         │
│ username        │
│ email           │
│ password        │
│ full_name       │
│ role            │
│ status          │
│ avatar          │
│ last_login      │
└─────────────────┘
        │
        │ (author_id)
        ├──────────────────────────┐
        │                          │
        ▼                          ▼
┌─────────────────┐        ┌─────────────────┐
│     POSTS       │        │     MEDIA       │
│─────────────────│        │─────────────────│
│ id (PK)         │        │ id (PK)         │
│ title           │        │ filename        │
│ slug            │        │ file_path       │
│ content         │        │ file_type       │
│ excerpt         │        │ file_size       │
│ featured_image  │        │ title           │
│ author_id (FK)  │        │ uploaded_by(FK) │
│ status          │        │ created_at      │
│ post_type       │        └─────────────────┘
│ views           │
│ published_at    │
└─────────────────┘
        │
        ├─────────────┐
        │             │
        ▼             ▼
┌─────────────────┐  ┌─────────────────┐
│  POST_META      │  │   COMMENTS      │
│─────────────────│  │─────────────────│
│ id (PK)         │  │ id (PK)         │
│ post_id (FK)    │  │ post_id (FK)    │
│ meta_key        │  │ user_id (FK)    │
│ meta_value      │  │ author_name     │
└─────────────────┘  │ content         │
                     │ status          │
        ┌────────────│ parent_id (FK)  │
        │            └─────────────────┘
        │                    │
        │                    │ (self-reference)
        │                    └─────┐
        │                          │
        ▼                          ▼
┌─────────────────┐        ┌─────────────────┐
│  CATEGORIES     │        │   COMMENTS      │
│─────────────────│        │  (nested)       │
│ id (PK)         │        └─────────────────┘
│ name            │
│ slug            │
│ description     │
│ parent_id (FK)  │◄───┐
│ display_order   │    │ (self-reference)
│ status          │    │
└─────────────────┘    │
        │              │
        │              └────────────┐
        ▼                           │
┌─────────────────┐                │
│POST_CATEGORIES  │                │
│─────────────────│                │
│ post_id (FK)    │◄───────────────┘
│ category_id(FK) │
└─────────────────┘
        ▲
        │
        │
┌─────────────────┐
│     PAGES       │
│─────────────────│
│ id (PK)         │
│ title           │
│ slug            │
│ content         │
│ template        │
│ parent_id (FK)  │◄───┐
│ status          │    │ (self-reference)
│ meta_title      │    │
└─────────────────┘    │
                       └────────────┘

┌─────────────────┐
│   SETTINGS      │
│─────────────────│
│ id (PK)         │
│ setting_key     │
│ setting_value   │
│ setting_type    │
│ is_autoload     │
└─────────────────┘

┌─────────────────┐
│ VISITOR_STATS   │
│─────────────────│
│ id (PK)         │
│ page_url        │
│ visitor_ip      │
│ user_agent      │
│ device_type     │
│ browser         │
│ visit_date      │
└─────────────────┘

┌─────────────────┐
│ ACTIVITY_LOG    │
│─────────────────│
│ id (PK)         │
│ user_id (FK)    │
│ action          │
│ entity_type     │
│ entity_id       │
│ description     │
│ ip_address      │
└─────────────────┘
```

## Relationships

### One-to-Many
- **users** → **posts** (author_id)
- **users** → **media** (uploaded_by)
- **users** → **comments** (user_id)
- **users** → **activity_log** (user_id)
- **posts** → **post_meta** (post_id)
- **posts** → **comments** (post_id)

### Many-to-Many
- **posts** ↔ **categories** (via post_categories)

### Self-Referencing
- **categories** → **categories** (parent_id)
- **pages** → **pages** (parent_id)
- **comments** → **comments** (parent_id)

## Table Details

### Core Content Tables

#### USERS
```
Purpose: User authentication and management
Records: 1+ (default admin)
Key Fields: username, email, password, role
Indexes: username, email, status
```

#### POSTS
```
Purpose: Main content management
Records: Variable
Key Fields: title, slug, content, status, post_type
Indexes: slug, author_id, status, post_type, published_at
Full-Text: title, content, excerpt
```

#### CATEGORIES
```
Purpose: Content organization
Records: 6+ (default categories)
Key Fields: name, slug, parent_id
Indexes: slug, parent_id, status
```

### Supporting Tables

#### POST_CATEGORIES
```
Purpose: Link posts to categories
Type: Junction table
Keys: post_id, category_id (composite primary key)
```

#### POST_META
```
Purpose: Flexible metadata storage
Type: Key-value store
Keys: post_id, meta_key (unique together)
```

#### COMMENTS
```
Purpose: User comments and discussions
Features: Nested comments, moderation
Keys: post_id, user_id, parent_id
```

#### MEDIA
```
Purpose: File management
Supports: Images, videos, documents
Keys: filename, uploaded_by
```

#### PAGES
```
Purpose: Static pages
Features: Templates, SEO, hierarchy
Keys: slug, parent_id
```

### System Tables

#### SETTINGS
```
Purpose: Site configuration
Type: Key-value store
Features: Type-safe, autoload
```

#### VISITOR_STATS
```
Purpose: Analytics and tracking
Features: Device detection, geolocation
Keys: page_url, visitor_ip, visit_date
```

#### ACTIVITY_LOG
```
Purpose: Audit trail
Features: User actions, system events
Keys: user_id, action, entity_type
```

## Indexes Strategy

### Primary Indexes
- All tables have auto-increment `id` as PRIMARY KEY

### Unique Indexes
- users: username, email
- posts: slug
- categories: slug
- pages: slug
- settings: setting_key

### Foreign Key Indexes
- Automatic indexes on all FK columns
- Ensures referential integrity
- Optimizes JOIN operations

### Search Indexes
- Full-text index on posts (title, content, excerpt)
- Regular indexes on frequently queried columns

### Performance Indexes
- posts: status, post_type, published_at
- visitor_stats: page_url, visit_date
- activity_log: created_at

## Data Types

### String Types
- VARCHAR(50-500): Usernames, emails, URLs
- TEXT: Descriptions, excerpts
- LONGTEXT: Content, metadata

### Numeric Types
- INT: IDs, counters, sizes
- ENUM: Status fields, roles, types

### Date/Time Types
- DATETIME: Specific timestamps (published_at, last_login)
- TIMESTAMP: Auto-updating (created_at, updated_at)
- DATE: Date only (visit_date)
- TIME: Time only (visit_time)

### Boolean Types
- BOOLEAN: Flags (is_featured, allow_comments)

## Character Set
- **Charset:** utf8mb4
- **Collation:** utf8mb4_unicode_ci
- **Supports:** Full Unicode including emojis

## Storage Engine
- **Engine:** InnoDB
- **Features:** 
  - ACID compliance
  - Foreign key support
  - Row-level locking
  - Crash recovery

## Views

### vw_published_posts
```sql
SELECT posts with author info and categories
WHERE status = 'published'
ORDER BY published_at DESC
```

### vw_visitor_summary
```sql
SELECT visit_date, total_visits, unique_visitors, device_breakdown
GROUP BY visit_date
ORDER BY visit_date DESC
```

## Constraints

### Foreign Keys
- ON DELETE CASCADE: posts, comments, media
- ON DELETE SET NULL: categories (parent), users (optional)

### Check Constraints
- ENUM values enforce valid options
- NOT NULL on required fields

### Unique Constraints
- Prevent duplicate usernames, emails, slugs
- Composite unique on post_meta (post_id, meta_key)

## Triggers (Future Enhancement)
```sql
-- Auto-update timestamps
-- Maintain view counts
-- Log changes to activity_log
-- Clean up orphaned records
```

## Stored Procedures (Future Enhancement)
```sql
-- Bulk operations
-- Complex queries
-- Data migrations
-- Maintenance tasks
```

---

**Schema Version:** 1.0.0  
**Total Tables:** 11  
**Total Views:** 2  
**Character Set:** utf8mb4  
**Engine:** InnoDB
