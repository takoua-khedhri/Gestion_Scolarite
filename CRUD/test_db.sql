
CREATE TABLE leçon (
    id INT AUTO_INCREMENT PRIMARY KEY,       -- Identifiant unique pour chaque cours
    nom VARCHAR(255) NOT NULL,               -- Nom du cours
    description TEXT,                        -- Description détaillée du cours
    enseignant VARCHAR(255) NOT NULL,        -- Nom de l'enseignant responsable du cours
    email_enseignant VARCHAR(255) NOT NULL,  -- Email de l'enseignant
    date_debut DATE,                         -- Date de début du cours
    date_fin DATE,                           -- Date de fin du cours
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP -- Date de création de l'enregistrement
);

CREATE TABLE examens (
    id INT AUTO_INCREMENT PRIMARY KEY,            -- Identifiant unique de l'examen
    titre VARCHAR(255) NOT NULL,                 -- Titre de l'examen (ex. "Examen final - Programmation")
    date_exam DATE NOT NULL,                     -- Date de l'examen
    heure_exam TIME NOT NULL,                    -- Heure de l'examen
    duree INT NOT NULL,                          -- Durée de l'examen en minutes
    enseignant VARCHAR(255) NOT NULL,            -- Nom de l'enseignant responsable
    salle VARCHAR(50),                           -- Salle ou numéro d'amphithéâtre pour l'examen
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP -- Date de création de l'enregistrement
);

CREATE TABLE G_matiere (
    id INT AUTO_INCREMENT PRIMARY KEY, -- Identifiant unique pour chaque matière
    nom_matiere VARCHAR(255) NOT NULL, -- Nom de la matière
    filiere VARCHAR(255) NOT NULL, -- Filière associée à la matière
    annee_etude INT NOT NULL -- Année d'étude correspondante
);



CREATE TABLE Apprenant (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    date_naissance DATE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    telephone VARCHAR(15),
    adresse VARCHAR(255),
    password VARCHAR(255) NOT NULL,
    code_postal VARCHAR(10),
    genre ENUM('Homme', 'Femme', 'Autre'),
    nationalite VARCHAR(50),
    filiere VARCHAR(50),
    annee_etude VARCHAR(20),
    matricule VARCHAR(20) UNIQUE
);

CREATE TABLE Educateur(
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    date_naissance DATE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    telephone VARCHAR(15),
    adresse VARCHAR(255),
    ville VARCHAR(50),
    code_postal VARCHAR(10),
    genre ENUM('Homme', 'Femme', 'Autre'),
    nationalite VARCHAR(50),
    matiere_enseignee VARCHAR(100),
    password VARCHAR(255) NOT NULL,
    departement VARCHAR(100),

);
CREATE TABLE IF NOT EXISTS notes_apprenant (
    id INT AUTO_INCREMENT PRIMARY KEY,
    apprenant_id INT NOT NULL,
    matiere_id INT NOT NULL,
    note_CC1 DECIMAL(5,2) DEFAULT NULL,
    note_CC2 DECIMAL(5,2) DEFAULT NULL,
    date_modification TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (apprenant_id) REFERENCES Apprenant(id),
    FOREIGN KEY (matiere_id) REFERENCES G_matiere(id),
    UNIQUE KEY (apprenant_id, matiere_id)
);



