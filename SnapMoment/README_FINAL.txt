SNAPMOMENT - COMPLETE WEBSITE
================================
This is the complete integrated project. You do NOT need to install phase ZIPs separately.

INSTALL ON XAMPP
1. Stop Apache/MySQL if running.
2. Rename or back up your old Visitor_Photo_Slideshow_Phase1 folder.
3. Extract this ZIP into C:\xampp\htdocs\
4. The extracted folder should be:
   C:\xampp\htdocs\SnapMoment_Final_Website\
5. Open config/db.php. It is configured for MySQL port 3307, username root, blank password.
   If your MySQL uses a different port/password, change those values there.
6. Start Apache and MySQL in XAMPP.
7. Open http://localhost/phpmyadmin/
8. Select Import and import database/schema.sql ONCE.
9. Open http://localhost/SnapMoment_Final_Website/

VISITOR
- Click Share your photos, enter name (no email required).
- Select/drag multiple JPG, PNG or WEBP photos.
- Tick consent and click Upload photos.
- Visitors are recognized on the same browser using a secure cookie. On another device/browser, enter the same name and Visitor ID to retrieve the existing profile.
- Photos are saved on server and database with pending status.

ADMIN
- Open http://localhost/SnapMoment_Final_Website/admin/login.php
- First login creates default account:
  username: admin
  password: admin123
- Change the default password before using this publicly. This demo includes login and moderation.
- Approve/reject photos. Only approved photos appear in gallery and display.

SLIDESHOW
- Open http://localhost/SnapMoment_Final_Website/display.php on the TV/computer.
- Approved photos rotate every 6 seconds. Gallery refreshes automatically every 10 seconds.

DATABASE
- Import database/schema.sql only once for a fresh setup.
- If snapmoment_db already exists and contains your existing visitors/photos tables, back it up first.
  The SQL uses CREATE TABLE IF NOT EXISTS, so it adds the admins table without deleting existing records.
- Do not drop the database when updating website files.

IMPORTANT
- PHP upload limit must permit 40 MB total. In php.ini, check upload_max_filesize=8M and post_max_size=45M.
- This is a local college demo. For public hosting, add HTTPS, stronger admin account management, rate limiting and verified visitor identity.
\nConcurrent uploads: random unique filenames and independent database transactions prevent different visitors overwriting each other. Test simultaneous uploads before submission.\n