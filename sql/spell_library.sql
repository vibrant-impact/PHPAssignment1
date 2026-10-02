DROP DATABASE IF EXISTS spell_library;
CREATE DATABASE spell_library;
USE spell_library;

-- 1. Create Schools (Parent Table)
CREATE TABLE schools (
    schoolID INT AUTO_INCREMENT PRIMARY KEY,
    schoolName VARCHAR(50) NOT NULL UNIQUE,
    schoolDescription VARCHAR(255) NOT NULL
);

-- Seed Arcane Schools
INSERT INTO schools (schoolName, schoolDescription) VALUES
('Abjuration', 'Protective wards, barriers, and banishments.'),
('Conjuration', 'Summoning objects, creatures, or transporting across space.'),
('Divination', 'Unveiling hidden knowledge, past visions, and foresight.'),
('Enchantment', 'Bending the mind, charming subjects, and soothing spirits.'),
('Evocation', 'Channeling raw magical energy into fire, lightning, or healing.'),
('Illusion', 'Deceiving the senses and crafting phantasms.'),
('Necromancy', 'Manipulating life forces, souls, and undeath.'),
('Transmutation', 'Altering physical forms, matter, and the environment.');

-- 2. Create Spells (Child Table with Foreign Key)
CREATE TABLE spells (
    spellID INT AUTO_INCREMENT PRIMARY KEY,
    schoolID INT NOT NULL,
    spellName VARCHAR(100) NOT NULL,
    spellLevel INT NOT NULL,
    castingTime VARCHAR(50) NOT NULL,
    suppliesNeeded VARCHAR(255) NOT NULL,
    isAvailable TINYINT(1) DEFAULT 1,
    imageFile VARCHAR(255) DEFAULT 'placeholder.jpg',
    CONSTRAINT fk_spells_schools
        FOREIGN KEY (schoolID)
        REFERENCES schools(schoolID)
        ON UPDATE CASCADE
        ON DELETE RESTRICT
);

-- Seed Spells linked to Schools
INSERT INTO spells (schoolID, spellName, spellLevel, castingTime, suppliesNeeded, isAvailable, imageFile) VALUES
(1, 'Arcane Aegis', 1, '1 Reaction', 'A piece of cured leather', 1, 'aegis.jpg'),
(2, 'Blink Step', 2, '1 Bonus Action', 'Silver dust and a cracked prism', 1, 'step.jpg'),
(3, 'Celestial Resonance', 3, '1 Action', 'Tuning fork made of meteor iron', 0, 'resonance.jpg'),
(2, 'Dimension Doorway', 4, '1 Action', 'Key cast from brass and starlight', 1, 'doorway.jpg'),
(3, 'Echo of Chronos', 5, '10 Minutes', 'Hourglass filled with crushed pearls', 0, 'chronos.jpg'),
(5, 'Prismatic Cascade', 6, '1 Action', 'Polished quartz sphere', 1, 'cascade.jpg'),
(2, 'Interdimensional Summoner', 9, '3 Days', 'Polished quartz sphere', 1, 'summoner.jpg');