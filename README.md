# Spell Lending Library (PHP & MySQL)

An arcane archive web application built with PHP, MySQL, and PDO to catalog, track, and manage grimoire spells and magic scrolls.

## Features
- **Full CRUD Operations:** Seamless Create, Read, Update, and Delete workflows for cataloged spells.
- **Relational Architecture:** One-to-many foreign key relationship between `schools` (Arcane Schools) and `spells`.
- **Dynamic Image Upload & Processing:** Upload spell sigils/glyphs with automated GD-based generation of 100px thumbnails and 400px previews (`image_util.php`).
- **Secure PDO Transactions:** Prepared statements with parameterized bindings to guard against SQL injection, paired with structured exception handling.
- **Separation of Concerns:** Modular component structure (`header.php`, `footer.php`, `database.php`, dedicated action controllers, and views).
- **Responsive Dark-Mode UI:** Custom CSS interface styled for an immersive archive catalog experience.

## Database Architecture
- **`schools` Table:** Primary category table containing `schoolID` (PK), `schoolName`, and `schoolDescription`.
- **`spells` Table:** Child table containing `spellID` (PK), `schoolID` (FK), `spellName`, `spellLevel`, `castingTime`, `suppliesNeeded`, `isAvailable`, and `imageFile`.

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
│   └── spell_library_v2.sql
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
- Go to the Import tab, choose sql/spell_library_v2.sql, and run the import.

5. **Launch Application:**
Open your browser and navigate to:
```text
http://localhost/spell-library/
```

## License
This project is licensed under the MIT License.