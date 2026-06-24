# Host The Outlet with XAMPP (Windows)

## 1. Install XAMPP

1. Download from [https://www.apachefriends.org/](https://www.apachefriends.org/)
2. Run the installer (default location: `C:\xampp`)
3. In the XAMPP Control Panel, start **Apache** and **MySQL**

## 2. Copy the project into htdocs

Copy your entire `labelloom` folder to:

```
C:\xampp\htdocs\labelloom
```

Your project is currently at:

```
C:\Users\emmam\CascadeProjects\labelloom
```

**Option A — Manual:** Copy/paste the folder in File Explorer.

**Option B — Script:** From the project folder, run:

```powershell
powershell -ExecutionPolicy Bypass -File scripts\setup_xampp.ps1
```

## 3. Create the database

1. Open [http://localhost/phpmyadmin](http://localhost/phpmyadmin)
2. Click **New** in the left sidebar
3. Database name: `labelloom` → **Create**
4. Select the `labelloom` database
5. Click **Import** → **Choose File**
6. Select `C:\xampp\htdocs\labelloom\database.sql`
7. Click **Go**

## 4. Check config.php

`config.php` is already set for XAMPP defaults:

| Setting    | Value        |
|-----------|--------------|
| Host      | `localhost`  |
| Username  | `root`       |
| Password  | *(empty)*    |
| Database  | `labelloom`  |
| Base URL  | `http://localhost/labelloom` |

If you renamed the folder in `htdocs`, update `$base_url` in `config.php` to match.

## 5. Open the site

| Page              | URL |
|-------------------|-----|
| Homepage          | [http://localhost/labelloom/](http://localhost/labelloom/) |
| Design diagrams   | [http://localhost/labelloom/docs/diagrams/](http://localhost/labelloom/docs/diagrams/) |
| PDF (all diagrams)| [http://localhost/labelloom/docs/diagrams/The Outlet_Design_Diagrams.pdf](http://localhost/labelloom/docs/diagrams/The Outlet_Design_Diagrams.pdf) |
| phpMyAdmin        | [http://localhost/phpmyadmin](http://localhost/phpmyadmin) |

## 6. Test login (sample accounts)

Password for all test accounts: **admin123**

| Role      | Email                    |
|-----------|--------------------------|
| Admin     | admin@labelloom.com      |
| Buyer     | buyer@example.com        |
| Seller    | seller@example.com       |
| Moderator | moderator@example.com    |

Admin panel: [http://localhost/labelloom/admin/](http://localhost/labelloom/admin/)

## Troubleshooting

### Apache won't start (port 80 in use)

- Stop Skype or other apps using port 80, **or**
- In XAMPP Control Panel → Apache **Config** → `httpd.conf` → change `Listen 80` to `Listen 8080`
- Then use: `http://localhost:8080/labelloom/`

### MySQL won't start (port 3306 in use)

- Stop other MySQL services, or change MySQL port in XAMPP config

### "Connection failed"

- Ensure MySQL is running in XAMPP
- Confirm database `labelloom` exists and `database.sql` was imported
- Check `config.php` username/password

### Images won't upload

- Ensure `C:\xampp\htdocs\labelloom\uploads` exists and is writable

### Page redirects to wrong URL

- Set `$base_url` in `config.php` to match your actual URL (including port if not 80)
