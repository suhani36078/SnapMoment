# SnapMoment – Visitor Photo Slideshow

SnapMoment is a PHP and MySQL-based visitor photo-sharing website for events. Visitors can identify themselves, upload multiple photos, and view the status of their uploads. An administrator reviews every photo before it becomes visible in the public gallery and slideshow.

## Features

### Visitor
- Enter a name to create a visitor profile.
- Receive a unique Visitor ID (for example, `VIS00001`).
- Return on the same browser using the visitor cookie, or enter the name and Visitor ID on another browser/device.
- Upload multiple JPG, PNG, or WEBP images.
- Preview and remove selected images before uploading.
- Confirm sharing consent before upload.
- View previous uploads and their moderation status after identification.

### Gallery and display
- Public gallery displays approved photos only.
- The gallery refreshes periodically and includes an interactive 3D-style image carousel.
- The separate display page shows approved photos as a slideshow.
- The slideshow advances every 6 seconds and checks for gallery updates every 10 seconds.

### Admin
- Admin login and session-based access.
- View pending, approved, rejected, or all photos.
- Approve or reject uploaded photos.
- Permanently delete rejected photos after a confirmation prompt.
- The permanent-delete action checks a session-bound CSRF token and restricts file deletion to the project's `uploads/photos` directory.

## Technology Stack

- **Frontend:** HTML5, CSS3, Vanilla JavaScript
- **Backend:** PHP
- **Database:** MySQL
- **Local development environment:** XAMPP (Apache and MySQL)
- **Database access:** PHP PDO with prepared statements

## Requirements

- XAMPP with Apache, MySQL, and PHP.
- A web browser.
- phpMyAdmin (included with XAMPP) or another way to import the SQL schema.
- MySQL configured to the port used in `config/db.php` (the supplied project configuration uses port `3307`).

## Installation on XAMPP

1. Download or clone this repository.
2. Extract the project folder into `C:\xampp\htdocs\`.
3. Confirm the project folder is named `SnapMoment_Final_Website`.
4. Start Apache and MySQL from the XAMPP Control Panel.
5. Open `config/db.php` and check the host, MySQL port, database name, username, and password. The supplied local configuration uses:
   - Host: `127.0.0.1`
   - Port: `3307`
   - Database: `snapmoment_db`
   - Username: `root`
   - Password: blank
6. Open phpMyAdmin at `http://localhost/phpmyadmin/`.
7. Import `database/schema.sql` once for a fresh installation.
8. Open `http://localhost/SnapMoment_Final_Website/`.

> If your MySQL installation uses another port or password, update `config/db.php` to match your local XAMPP configuration. Do not overwrite a working database configuration when replacing website files.

## Admin Access

Open:

`http://localhost/SnapMoment_Final_Website/admin/login.php`

On the first login, if the `admins` table is empty, the application creates this default account:

- Username: `admin`
- Password: `admin123`

This is a development/demo default. Do not expose the project publicly with the default credentials. Before public deployment, replace the default credential with a strong, privately managed admin password and review the deployment security settings.

## Upload Rules

The PHP upload handler currently accepts:
- JPG/JPEG, PNG, and WEBP images.
- Up to 20 photos per upload request.
- Maximum 8 MB per photo.
- Maximum 40 MB total per request.

The XAMPP PHP configuration must allow the request size. The supplied project notes recommend `upload_max_filesize=8M` and `post_max_size=45M` in `php.ini`. Restart Apache after changing PHP settings.

## Database Structure

The SQL schema creates the following tables:

- `visitors`: visitor code, visitor token, name, and timestamps.
- `photos`: visitor reference, stored file path, original filename, MIME type, file size, moderation status, and timestamps.
- `admins`: admin username and password hash.

Photo moderation status is stored as `pending`, `approved`, or `rejected`. Only approved photos are returned by the public gallery API.

## Important Project Paths

```text
SnapMoment_Final_Website/
├── admin/              # Admin login, moderation and rejected-photo deletion
├── api/                # Visitor, upload, photo history and gallery endpoints
├── assets/
│   ├── css/style.css   # Website styling and carousel styling
│   └── js/             # Visitor UI, gallery carousel and slideshow logic
├── config/db.php       # MySQL connection configuration
├── database/schema.sql # Database and table definitions
├── uploads/photos/     # Uploaded image files
├── display.php         # Full-screen slideshow page
└── index.php           # Visitor-facing homepage
```

## Testing Checklist

Use the following checklist when testing in a local XAMPP environment:

- [ ] Homepage opens without PHP or JavaScript errors.
- [ ] A new visitor can enter a name and receive a Visitor ID.
- [ ] The same browser recognises the returning visitor.
- [ ] A different browser/device can retrieve the profile using the name and Visitor ID.
- [ ] Multiple supported images can be selected and uploaded.
- [ ] Unsupported files, files above the per-image limit, and requests above the total limit are rejected.
- [ ] Uploads require the consent checkbox.
- [ ] Newly uploaded photos appear as pending in the admin panel.
- [ ] Pending and rejected photos do not appear in the public gallery or slideshow.
- [ ] Approved photos appear in the public gallery and display slideshow.
- [ ] The display advances photos every 6 seconds and refreshes the approved-photo list.
- [ ] The 3D gallery carousel works on desktop and touch devices.
- [ ] A rejected photo can be permanently deleted, including its stored image file.
- [ ] Two different visitors can upload at the same time without overwriting each other's files.

Record the actual date, environment, result, and any issue for each test in your testing report. Do not mark a test as passed unless it was actually performed.

## Privacy and Deployment Notes

- Visitors must confirm that the selected photos are okay to share at the event.
- Visitor IDs should be treated as private because they are used with a name to retrieve a visitor profile from another browser/device.
- Uploaded photos are stored on the server. Obtain appropriate permission before collecting or displaying photos.
- This project is currently documented for a local college/demo environment.
- Before public deployment, use HTTPS, strong admin credentials, appropriate server permissions, backups, rate limiting, and stronger visitor identity verification.
- Review applicable privacy and consent requirements for the event and hosting location.

## Current Limitations

- The supplied project uses local XAMPP database settings; production database credentials and deployment configuration must be supplied separately.
- The default admin account is intended only for first-time local setup.
- Visitor name plus Visitor ID is a simple retrieval mechanism, not strong identity verification.
- Concurrent upload behaviour should be tested in the target hosting environment, even though uploaded filenames are generated randomly and database writes use transactions.

## License

Add the license required by your course or project owner before publishing the repository publicly. Do not assume third-party images, fonts, or other assets are automatically licensed for redistribution.
