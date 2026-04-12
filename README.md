# AssetTrack — Company Inventory System

A complete web-based inventory tracking system for monitoring company asset deployments.

## Tech Stack

- **Backend:** PHP 8+ (no framework)
- **Database:** MySQL 8+
- **Frontend:** Vanilla JS + HTML5 + CSS3
- **Fonts:** Plus Jakarta Sans + Inter (Google Fonts)

---

## Folder Structure

```
inventory-system/
├── index.php                  # Landing page (User / Viewer entry)
├── database.sql               # MySQL schema + seed data
│
├── assets/
│   ├── css/
│   │   ├── landing.css        # Landing page styles
│   │   ├── auth.css           # Login page styles
│   │   └── app.css            # Dashboard design system
│   └── js/
│       ├── landing.js         # Landing page logic
│       ├── auth.js            # Login form handling
│       └── app.js             # Shared dashboard JS
│
├── includes/
│   ├── db.php                 # PDO database connection
│   ├── header.php             # HTML head + layout open
│   ├── sidebar.php            # Navigation sidebar
│   └── footer.php             # Layout close + scripts
│
├── pages/
│   ├── login.php              # User login page
│   ├── dashboard.php          # Main overview with stats & charts
│   ├── inventory.php          # Asset listing with CRUD
│   ├── history.php            # Full deployment audit log
│   ├── reports.php            # Analytics & visual reports
│   ├── users.php              # User management (admin only)
│   └── settings.php           # System settings (admin only)
│
└── api/
    └── auth.php               # Login/logout API endpoint
```

---

## Setup

### 1. Requirements
- PHP 8.0 or higher
- MySQL 8.0 or higher
- A local server: XAMPP, WAMP, Laragon, or MAMP

### 2. Database Setup
```sql
-- Import the schema via phpMyAdmin or CLI:
mysql -u root -p < database.sql
```

### 3. Configure DB Connection
Edit `includes/db.php`:
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'assettrack_db');
define('DB_USER', 'root');       // your MySQL user
define('DB_PASS', '');           // your MySQL password
```

### 4. Place Files
Copy the `inventory-system/` folder into your server's web root:
- XAMPP: `C:/xampp/htdocs/inventory-system/`
- Laragon: `C:/laragon/www/inventory-system/`

### 5. Access the App
Open your browser and go to:
```
http://localhost/inventory-system/
```

---

## Demo Credentials

| Access | Details |
|--------|---------|
| **User Login** | Username: `admin` / Password: `admin123` |
| **Viewer Passcode** | `123456` |

---

## Pages & Features

| Page | Description |
|------|-------------|
| **Landing** | Entry point with User (login) and Viewer (passcode) options |
| **Dashboard** | Stats overview, deployment bar chart, status breakdown, recent deployments |
| **Inventory** | Full asset list with search, filter by category/status/dept, add/edit assets |
| **History Log** | Audit trail of all transactions (deploy, return, maintenance) |
| **Reports** | Visual analytics: trends, category breakdown, department usage, condition report |
| **Users** | User management with role-based access (admin only) |
| **Settings** | System config, viewer passcode, categories, backup (admin only) |

---

## Role Access

| Feature | Administrator | Staff | Viewer |
|---------|:---:|:---:|:---:|
| View Dashboard | ✅ | ✅ | ✅ |
| View Inventory | ✅ | ✅ | ✅ |
| Add/Edit Assets | ✅ | ✅ | ❌ |
| History Log | ✅ | ✅ | ✅ |
| Log Transactions | ✅ | ✅ | ❌ |
| Reports | ✅ | ✅ | ✅ |
| Users Management | ✅ | ❌ | ❌ |
| Settings | ✅ | ❌ | ❌ |

---

## Design System

Built following the **UI UX Pro Max** design intelligence principles:

- **Style:** Minimalism + Soft UI Evolution
- **Primary:** `#1e40af` (Deep Blue — authority, trust)
- **Secondary:** `#0f766e` (Teal — secondary actions)
- **Accent:** `#f59e0b` (Amber — alerts)
- **Typography:** Plus Jakarta Sans (headings) + Inter (body)
- **Spacing scale:** 4 · 8 · 16 · 24 · 32 · 48 · 64px
- **WCAG AA compliant** contrast ratios throughout
