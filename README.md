# Project Setup and Usage Instructions

## Prerequisites
Ensure you have the following installed on your system:
- PHP (8.2)
- Composer
- SQLite (for database management)

## Installation Steps

1. **Clone the Repository**
   ```sh
   git clone git@github.com:gaustinv/Nyt-Article.git
   cd Nyt-Article
   ```

2. **Install Dependencies**
   ```sh
   composer install
   ```

3. **Set Up Environment Variables**
   - Update the necessary configurations like database and API keys

4. **Run the Project**
   ```sh
   php -S localhost:8080 -t public
   ```

## Packages Used
```json
{
    "require": {
        "firebase/php-jwt": "^6.11",
        "guzzlehttp/guzzle": "^7.9",
        "vlucas/phpdotenv": "^5.6"
    }
}
```

## Database Setup

1. **Database schema creation script.**
   ```sh
   sqlite3 database.sqlite
    .read schema.sql
   ```

### SQLite Schema
Create the required tables in `database.sqlite`:
```sql
CREATE TABLE IF NOT EXISTS users (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    email TEXT UNIQUE NOT NULL,
    password TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE favorites (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER NOT NULL,
    article_id TEXT NOT NULL,
    title TEXT NOT NULL,
    url TEXT NOT NULL
);

CREATE TABLE IF NOT EXISTS logs (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER,
    action TEXT NOT NULL,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

## API Endpoints

### 1. Login
**Endpoint:** `POST /api/auth/login.php`
- **Payload:**
  ```json
  {
      "email": "admin@yopmail.com",
      "password": "password"
  }
  ```
  ### 2. Register
**Endpoint:** `POST api/auth/register.php`
- **Payload:**
  ```json
  {
      "email": "admin@yopmail.com",
      "password": "password"
  }
  ```
### 3. Favorites
**Endpoint:** `GET api/favorites/get.php?page=${page}&limit=${limit}`


### 4. Add Favorite
**Endpoint:** `POST /api/favorites/add.php`
- **Payload:**
  ```json
  {
      "user_id": 1,
      "article_id": "123",
      "title": "Example Article",
      "url": "https://example.com"
  }
  ```

### 5. Remove Favorite
**Endpoint:** `DELETE /api/favorites/remove.php`
- **Payload:**
  ```json
  {
      "user_id": 1,
      "id": "123"
  }
  ```


