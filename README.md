# Meshes & Models CMS

A Content Management System dedicated to allowing 3D artists and creators to share their creations.

## Features

- **User accounts** – Register, log in, and manage a personal profile
- **Post creation** – Upload 3D model images with a title and rich-text description (powered by TinyMCE)
- **Image resizing** – Uploaded images are automatically saved at full, medium (400 px), and small (250 px) widths
- **Post management** – Edit and delete your own posts from a personal dashboard
- **Categories** – Browse posts filtered by category; admins can create new categories and assign posts to them
- **Search** – Full-text keyword search across all posts
- **Admin panel** – Dedicated page for managing categories and post–category relationships
- **Azure deployment** – Ready-to-deploy Azure Resource Manager (ARM) template included

## Tech Stack

| Layer | Technology |
|---|---|
| Language | PHP 8 |
| Database | MySQL (local dev) / Azure Database for MySQL (production) |
| WYSIWYG editor | [TinyMCE](https://www.tiny.cloud/) |
| HTML sanitisation | [HTMLPurifier](http://htmlpurifier.org/) |
| Image resizing | [gumlet/php-image-resize](https://github.com/gumlet/php-image-resize) |
| HTTP client | [rmccue/requests](https://github.com/WordPress/Requests) |
| Dependency manager | [Composer](https://getcomposer.org/) |
| Cloud hosting | Azure App Service |

## Prerequisites

- PHP 8.0 or later (with the `pdo_mysql` and `gd` extensions enabled)
- MySQL 8.0 or later
- [Composer](https://getcomposer.org/) 2.x

## Installation

1. **Clone the repository**

   ```bash
   git clone https://github.com/ryansteffan/Meshes_Models_CMS.git
   cd Meshes_Models_CMS
   ```

2. **Install PHP dependencies**

   ```bash
   composer install
   ```

3. **Create the database**

   Create a MySQL database called `meshes_and_models`. The application expects at least the following tables: `users`, `posts`, `categories`, and `posts_categories`. No schema migration file is currently included in the repository, so you will need to create these tables manually based on the queries found in the PHP source files.

4. **Create the uploads directory**

   ```bash
   mkdir uploads
   ```

   Ensure the web server has write permissions to this directory.

5. **Configure the database connection** (see [Configuration](#configuration) below).

6. **Point your web server** at the repository root and make sure URL rewriting is enabled. An `.htaccess` file is included for Apache. Nginx users will need to add an equivalent `try_files` rewrite rule to their server block manually.

## Configuration

Database credentials are set in `utilities/connect.php`.

### Local development

The file ships with placeholder credentials for a local MySQL instance:

```php
define('DB_DSN',  'mysql:host=localhost;port=3306;dbname=meshes_and_models;charset=utf8');
define('DB_USER', 'serveruser');
define('DB_PASS', 'your_password');
```

Update these values to match your local MySQL setup.

### Production (Azure)

When the following environment variables are present the application automatically switches to the Azure MySQL connection – no code changes required:

| Environment variable | Description |
|---|---|
| `AZURE_MYSQL_DBNAME` | Database name |
| `AZURE_MYSQL_HOST` | Server hostname |
| `AZURE_MYSQL_PASSWORD` | Password |
| `AZURE_MYSQL_PORT` | Port (usually `3306`) |
| `AZURE_MYSQL_USERNAME` | Username |

Set these in your Azure App Service **Application settings**.

## Project Structure

```
Meshes_Models_CMS/
├── ExportedTemplate-meshesandmodelscms_group/  # Azure ARM deployment template
├── images/                                      # Static site images
├── pages/                                       # Application pages
│   ├── admin.php                                # Admin panel (category management)
│   ├── create_account.php                       # New user registration
│   ├── create_post.php                          # Create a new post
│   ├── modify_post.php                          # Edit an existing post
│   ├── delete_post.php                          # Delete a post
│   ├── my_posts.php                             # Personal post dashboard
│   ├── login.php / logout.php                   # Authentication
│   ├── view_post.php                            # Single post view
│   ├── view_category.php                        # Posts filtered by category
│   └── categories.php                           # Category listing
├── templates/                                   # Shared HTML partials
│   ├── header.php
│   └── footer.php
├── utilities/                                   # Core PHP helpers
│   ├── auth.php                                 # Session & user auth functions
│   ├── categories.php                           # Category query helpers
│   ├── connect.php                              # Database connection
│   └── image_upload.php                         # Image upload & resize helpers
├── uploads/                                     # User-uploaded images (gitignored)
├── composer.json                                # PHP dependency manifest
├── index.php                                    # Home page / search results
└── index.css                                    # Global stylesheet
```

## Deployment to Azure

An ARM template is provided in `ExportedTemplate-meshesandmodelscms_group/`. To deploy:

1. Open the [Azure Portal](https://portal.azure.com) and navigate to **Deploy a custom template**.
2. Upload `template.json` and supply the required parameter values from `parameters.json`.
3. After deployment, configure the [environment variables](#production-azure) in your App Service application settings.
4. Deploy the application code via your preferred method (GitHub Actions, FTP, Azure CLI, etc.).

## Author

**Ryan Steffan** – [ryansteffan.biz@gmail.com](mailto:ryansteffan.biz@gmail.com)
