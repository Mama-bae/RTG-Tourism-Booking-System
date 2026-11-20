RTG TOURISM BOOKING SYSTEM – STUDENT PROTOTYPE
================================================

IMPORTANT
This is an independent academic prototype for the Rainbow Tourism Group case-study context. It is not an official RTG product and is not connected to RTG's internal systems. It uses sample service information. It does not check live availability, guarantee reservations or process payments.

REQUIREMENTS
- XAMPP with Apache, PHP and MySQL/MariaDB
- PHP extensions: PDO MySQL and mbstring
- A web browser

INSTALLATION (FRESH DATABASE)
1. Extract this folder into C:\xampp\htdocs\
2. Rename the extracted folder to RTG_Tourism_Booking_System if needed.
3. Start Apache and MySQL from the XAMPP Control Panel.
4. Open http://localhost/phpmyadmin
5. Import schema.sql. It creates/uses the rtg_tourism database and inserts sample services.
6. Check db.php. The default XAMPP settings are host 127.0.0.1, database rtg_tourism, user root, blank password. Change these if your setup differs.
7. Open http://localhost/RTG_Tourism_Booking_System/setup_admin.php
8. Create the first administrator using your own name, email and a password of at least 10 characters.
9. Sign in at http://localhost/RTG_Tourism_Booking_System/login.php
10. DELETE setup_admin.php after the first administrator is created.

IF YOU ALREADY IMPORTED THE EARLIER PROJECT DATABASE
Do NOT import schema.sql again. First make a backup in phpMyAdmin (Export).
Then open the SQL tab for rtg_tourism and run upgrade_existing_database.sql ONCE. This adds the booking reference field required by the updated booking form.
The earlier schema may already contain sample services. Do not repeatedly run seed statements.

FEATURES
- Responsive public home page and sample service catalogue.
- Service details, location and category.
- Booking request form with server-side validation.
- Unique booking reference and initial Pending status.
- Administrator login with password hashing and session regeneration.
- Admin dashboard for reviewing requests and updating status.
- Admin service management: add, edit, activate and deactivate sample experiences.
- PDO prepared statements, output escaping, CSRF tokens and server-side admin checks.

TEST PLAN
1. Open the home page and confirm the sample services appear.
2. Submit an incomplete form; it should be rejected by browser/server validation.
3. Submit a valid request with a future check-in date and a later check-out date.
4. Confirm a booking reference appears and the status is Pending.
5. Sign in as administrator and confirm the request appears in the dashboard.
6. Change its status to Confirmed and save; confirm the new status is displayed.
7. Add or edit a sample service and confirm it appears on the public page.
8. Sign out and confirm the admin dashboard redirects to login.

TROUBLESHOOTING
- Database connection error: check MySQL is running and verify db.php credentials.
- Experiences do not load: check Apache/MySQL, then open services.php directly to inspect the error.
- Setup page says an administrator exists: use that account to log in; do not create a second initial admin through the setup page.
- If an existing database is used, run the upgrade SQL once before testing bookings.
- Remote sample images and Google Fonts require an internet connection; the system's core functions run locally.

SECURITY / SUBMISSION NOTES
- Use fictional sample customer details in demonstrations.
- Do not use a real customer's personal information.
- Delete setup_admin.php after initial setup.
- Change the database credentials before any deployment beyond a local school demonstration.
- This local prototype has not been penetration-tested or independently reviewed for production use.
