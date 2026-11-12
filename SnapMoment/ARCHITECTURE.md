# SnapMoment – Architecture

## Main components
- `index.php`: visitor-facing registration, upload, and photo-history interface.
- `api/`: PHP endpoints for visitor identification, photo upload, visitor history, and approved gallery data.
- `admin/`: administrator authentication and photo moderation.
- `display.php`: full-screen slideshow for approved photos.
- `assets/`: CSS and JavaScript used by the website.
- `database/schema.sql`: MySQL database and table definitions.
- `config/db.php`: local database connection file (create it by copying `config/db.example.php`; it is intentionally excluded from Git).
- `uploads/photos/`: runtime storage for visitor-uploaded images; uploaded images are intentionally excluded from Git.

## Data flow
1. Visitor identifies themselves and receives or reuses a visitor profile.
2. Uploaded images are validated and stored; each photo is recorded as `pending`.
3. An administrator approves or rejects each photo.
4. Public gallery and slideshow endpoints return approved photos only.
5. Visitors can retrieve their own upload history and moderation statuses.
