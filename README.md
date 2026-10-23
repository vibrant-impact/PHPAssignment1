# Spell Lending Library (PHP & MySQL)

An arcane archive web application built with PHP, MySQL, and PDO to catalog, track, and manage grimoire spells and magic scrolls with user authentication and isolated collections.

## Features

- **User Authentication & Session Security:**
  - Registration with secure password hashing (`password_hash()` using bcrypt).
  - Immediate automatic login upon successful account creation.
  - Centralized session authentication guard redirecting unauthenticated traffic to the login portal.
  - Brute-force protection: accounts temporarily lock for 5 minutes after 3 consecutive failed login attempts.
  - Complete session clearance and destruction on logout.
- **User-Isolated Collections:**
  - Multi-tenant architecture ensuring each registered mage can only view, edit, or delete spells in their own grimoire.
- **Relational Data Architecture:**
  - Three-table schema linking `users`, Arcane `schools`, and user-scoped `spells`.
  - Prepared statements with parameterized bindings to guard against SQL injection.
- **Dynamic Image Management:**
  - Upload spell sigils/glyphs with automated GD-based generation of 100px thumbnails and 400px detail previews (`image_util.php`).
  - Automated deletion of superseded custom image files during spell updates (preserving default placeholders).
  - Client-side live preview when selecting new sigil graphics.
- **Responsive Dark-Mode Interface:**
  - Custom CSS layout featuring showcase spell details cards, responsive tables, and clean forms.

## Database Schema

- **`users` Table:**
  - `userID` (PK, INT AUTO_INCREMENT)
  - `username` (VARCHAR(50), UNIQUE)
  - `email` (VARCHAR(100), UNIQUE)
  - `password` (VARCHAR(255))
  - `failed_attempts` (INT, default 0)
  - `lockout_time` (DATETIME, default NULL)
  - `dateCreated` (TIMESTAMP)
- **`schools` Table:**
  - `schoolID` (PK, INT AUTO_INCREMENT)
  - `schoolName` (VARCHAR(50), UNIQUE)
  - `schoolDescription` (VARCHAR(255))
- **`spells` Table:**
  - `spellID` (PK, INT AUTO_INCREMENT)
  - `schoolID` (FK referencing `schools.schoolID`)
  - `userID` (FK referencing `users.userID`)
  - `spellName` (VARCHAR(100))
  - `spellLevel` (INT)
  - `castingTime` (VARCHAR(50))
  - `suppliesNeeded` (VARCHAR(255))
  - `isAvailable` (TINYINT(1), default 1)
  - `imageFile` (VARCHAR(255), default 'placeholder.jpg')

## Project Structure

```text
spell-library/
├── css/
│   └── spell.css
├── images/
│   ├── placeholder.jpg
│   ├── placeholder_100.jpg
│   └── placeholder_400.jpg
├── sql/
│   ├── spell_library.sql
│   ├── spell_library_v2.sql
│   └── spell_library_v3.sql
├── add_error.php
├── add_spell_confirmation.php
├── add_spell_form.php
├── add_spell.php
├── database_error.php
├── database.php
├── delete_spell.php
├── footer.php
├── header.php
├── image_util.php
├── index.php
├── login_confirmation.php
├── login_form.php
├── login.php
├── logout.php
├── register_confirmation.php
├── register_user_form.php
├── register_user.php
├── spell_details.php
├── update_error.php
├── update_spell_confirmation.php
├── update_spell_form.php
└── update_spell.php
```

## Setup & Installation
1. **Clone or Move the Repository:**
Place the project folder into your local server web directory (e.g., /Applications/XAMPP/xamppfiles/htdocs/spell-library/).

2. **Configure Folder Permissions:**
Ensure the images/ directory has write permissions enabled for automated thumbnail generation:
```text
chmod 777 images
```
3. **Start Local Servers:**
Launch the XAMPP Control Panel and start Apache and MySQL.

4. **Import Database Schema:**
- Navigate to phpMyAdmin (http://localhost/phpmyadmin).
- Create or select the spell_library database.
- Run the script located in sql/spell_library_v3.sql to construct the tables, seed arcane schools, and establish the foreign key constraints.

5. **Default Test Account:**
- Username: merlin
- Password: Password123!

6. **Launch Application:**
Open your browser and navigate to:
```text
http://localhost/spell-library/
```

## License
This project is licensed under the MIT License.