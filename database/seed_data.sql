-- Seed Data for Askiaverse Database
-- This file populates the database with initial educational content

-- Insert subjects
INSERT INTO `subjects` (`name`, `description`, `icon`, `color`) VALUES
('Mathématiques', 'Apprentissage des concepts mathématiques fondamentaux', '🔢', '#3B82F6'),
('Français', 'Maîtrise de la langue française et de la littérature', '📚', '#EF4444'),
('Sciences', 'Découverte des sciences naturelles et expérimentales', '🔬', '#10B981'),
('Histoire', 'Exploration de l\'histoire du Mali et du monde', '🏛️', '#F59E0B'),
('Géographie', 'Découverte du Mali, de l\'Afrique et du monde', '🌍', '#8B5CF6'),
('Anglais', 'Apprentissage de la langue anglaise', '🇬🇧', '#06B6D4');

-- Insert themes for Mathématiques
INSERT INTO `themes` (`name`, `description`, `subject_id`, `difficulty`) VALUES
('Arithmétique Simple', 'Addition, soustraction, multiplication et division de base', 1, 'facile'),
('Géométrie de Base', 'Formes géométriques, périmètre et aire', 1, 'facile'),
('Fractions', 'Introduction aux fractions et aux nombres décimaux', 1, 'moyen'),
('Problèmes', 'Résolution de problèmes mathématiques', 1, 'moyen');

-- Insert themes for Français
INSERT INTO `themes` (`name`, `description`, `subject_id`, `difficulty`) VALUES
('Grammaire', 'Règles grammaticales de base', 2, 'facile'),
('Conjugaison', 'Conjugaison des verbes au présent, passé et futur', 2, 'facile'),
('Vocabulaire', 'Enrichissement du vocabulaire', 2, 'facile'),
('Compréhension', 'Lecture et compréhension de textes', 2, 'moyen');

-- Insert themes for Sciences
INSERT INTO `themes` (`name`, `description`, `subject_id`, `difficulty`) VALUES
('Sciences de la Vie', 'Le corps humain et les êtres vivants', 3, 'facile'),
('Sciences de la Terre', 'La planète Terre et l\'environnement', 3, 'facile'),
('Physique Simple', 'Forces, énergie et mouvement', 3, 'moyen'),
('Chimie de Base', 'Matière et transformations', 3, 'moyen');

-- Insert themes for Histoire
INSERT INTO `themes` (`name`, `description`, `subject_id`, `difficulty`) VALUES
('Histoire du Mali', 'Empires et royaumes du Mali', 4, 'facile'),
('Histoire de l\'Afrique', 'Civilisations africaines anciennes', 4, 'facile'),
('Histoire Moderne', 'Événements historiques récents', 4, 'moyen'),
('Personnages Historiques', 'Figures importantes de l\'histoire', 4, 'facile');

-- Insert themes for Géographie
INSERT INTO `themes` (`name`, `description`, `subject_id`, `difficulty`) VALUES
('Géographie du Mali', 'Régions, villes et reliefs du Mali', 5, 'facile'),
('Géographie de l\'Afrique', 'Pays, capitales et caractéristiques de l\'Afrique', 5, 'facile'),
('Géographie Mondiale', 'Continents, océans et pays du monde', 5, 'moyen'),
('Climat et Environnement', 'Types de climats et protection de l\'environnement', 5, 'moyen');

-- Insert themes for Anglais
INSERT INTO `themes` (`name`, `description`, `subject_id`, `difficulty`) VALUES
('Vocabulaire de Base', 'Mots et expressions courantes en anglais', 6, 'facile'),
('Grammaire Simple', 'Structure des phrases et temps verbaux', 6, 'facile'),
('Conversation', 'Dialogues et expressions de la vie quotidienne', 6, 'moyen'),
('Compréhension Orale', 'Écoute et compréhension de l\'anglais parlé', 6, 'moyen');

-- Insert sample questions for Mathématiques - Arithmétique Simple
INSERT INTO `questions` (`question`, `options`, `correct_answer`, `explanation`, `theme_id`, `difficulty`, `points`) VALUES
('Combien font 7 + 8 ?', '["13", "14", "15", "16"]', 2, '7 + 8 = 15. C\'est une addition simple.', 1, 'facile', 10),
('Quel est le résultat de 12 - 5 ?', '["5", "6", "7", "8"]', 2, '12 - 5 = 7. C\'est une soustraction simple.', 1, 'facile', 10),
('Combien font 6 × 4 ?', '["20", "22", "24", "26"]', 2, '6 × 4 = 24. C\'est une multiplication simple.', 1, 'facile', 10),
('Quel est le résultat de 20 ÷ 4 ?', '["3", "4", "5", "6"]', 2, '20 ÷ 4 = 5. C\'est une division simple.', 1, 'facile', 10);

-- Insert sample questions for Français - Grammaire
INSERT INTO `questions` (`question`, `options`, `correct_answer`, `explanation`, `theme_id`, `difficulty`, `points`) VALUES
('Quel est le genre du mot "livre" ?', '["Masculin", "Féminin", "Les deux", "Aucun"]', 0, 'Le mot "livre" est masculin. On dit "un livre".', 5, 'facile', 10),
('Quel est le pluriel de "cheval" ?', '["Chevals", "Chevaux", "Chevales", "Chevauxes"]', 1, 'Le pluriel de "cheval" est "chevaux".', 5, 'facile', 10),
('Quelle est la bonne orthographe ?', '["Je mange", "Je mangent", "Je manges", "Je mangé"]', 0, 'La bonne orthographe est "Je mange" (1ère personne du singulier).', 5, 'facile', 10);

-- Insert sample questions for Sciences - Sciences de la Vie
INSERT INTO `questions` (`question`, `options`, `correct_answer`, `explanation`, `theme_id`, `difficulty`, `points`) VALUES
('Combien d\'os y a-t-il dans le corps humain ?', '["156", "206", "256", "306"]', 1, 'Le corps humain adulte compte 206 os.', 9, 'facile', 10),
('Quel organe pompe le sang dans le corps ?', '["Le cerveau", "Le cœur", "Les poumons", "Le foie"]', 1, 'Le cœur est l\'organe qui pompe le sang dans tout le corps.', 9, 'facile', 10),
('Quel est le plus grand organe du corps humain ?', '["Le cerveau", "Le cœur", "La peau", "Les poumons"]', 2, 'La peau est le plus grand organe du corps humain.', 9, 'facile', 10);

-- Insert sample questions for Histoire - Histoire du Mali
INSERT INTO `questions` (`question`, `options`, `correct_answer`, `explanation`, `theme_id`, `difficulty`, `points`) VALUES
('Quel était le nom de l\'empire médiéval du Mali ?', '["Empire du Ghana", "Empire du Mali", "Empire Songhaï", "Empire du Kanem"]', 1, 'L\'empire médiéval du Mali était appelé "Empire du Mali".', 13, 'facile', 10),
('Qui était le fondateur de l\'empire du Mali ?', '["Soundiata Keita", "Mansa Moussa", "Askia Mohammed", "Sundiata Keita"]', 3, 'Sundiata Keita était le fondateur de l\'empire du Mali.', 13, 'facile', 10),
('Quelle était la capitale de l\'empire du Mali ?', '["Tombouctou", "Gao", "Niani", "Djenné"]', 2, 'Niani était la capitale de l\'empire du Mali.', 13, 'facile', 10);

-- Insert sample questions for Géographie - Géographie du Mali
INSERT INTO `questions` (`question`, `options`, `correct_answer`, `explanation`, `theme_id`, `difficulty`, `points`) VALUES
('Combien de régions y a-t-il au Mali ?', '["6", "8", "10", "12"]', 1, 'Le Mali compte 8 régions administratives.', 17, 'facile', 10),
('Quelle est la capitale du Mali ?', '["Gao", "Tombouctou", "Bamako", "Sikasso"]', 2, 'Bamako est la capitale du Mali.', 17, 'facile', 10),
('Quel fleuve traverse le Mali ?', '["Le Niger", "Le Sénégal", "Le Congo", "Le Nil"]', 0, 'Le fleuve Niger traverse le Mali.', 17, 'facile', 10);

-- Insert sample questions for Anglais - Vocabulaire de Base
INSERT INTO `questions` (`question`, `options`, `correct_answer`, `explanation`, `theme_id`, `difficulty`, `points`) VALUES
('Comment dit-on "bonjour" en anglais ?', '["Goodbye", "Hello", "Thank you", "Please"]', 1, '"Hello" signifie "bonjour" en anglais.', 21, 'facile', 10),
('Comment dit-on "merci" en anglais ?', '["Please", "Thank you", "Sorry", "Goodbye"]', 1, '"Thank you" signifie "merci" en anglais.', 21, 'facile', 10),
('Comment dit-on "au revoir" en anglais ?', '["Hello", "Please", "Thank you", "Goodbye"]', 3, '"Goodbye" signifie "au revoir" en anglais.', 21, 'facile', 10);

-- Insert sample users
INSERT INTO `users` (`username`, `email`, `password`, `school`, `city`, `class_level`) VALUES
('hamza', 'hamza@askiaverse.ml', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'LYMG', 'Gao', '6ème'),
('fatou', 'fatou@askiaverse.ml', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Lycée Moderne', 'Bamako', '5ème'),
('moussa', 'moussa@askiaverse.ml', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'École Fondamentale', 'Sikasso', '4ème');

-- Insert sample achievements
INSERT INTO `achievements` (`name`, `description`, `icon`, `points`) VALUES
('Premier Pas', 'Réussir votre premier quiz', '🎯', 50),
('Mathématicien', 'Réussir 10 questions de mathématiques', '🔢', 100),
('Linguiste', 'Réussir 10 questions de français', '📚', 100),
('Scientifique', 'Réussir 10 questions de sciences', '🔬', 100),
('Historien', 'Réussir 10 questions d\'histoire', '🏛️', 100),
('Géographe', 'Réussir 10 questions de géographie', '🌍', 100),
('Polyglotte', 'Réussir 10 questions d\'anglais', '🇬🇧', 100);

-- Insert sample quiz attempts
INSERT INTO `quiz_attempts` (`user_id`, `subject_id`, `theme_id`, `score`, `total_questions`, `correct_answers`, `time_spent`, `mode`) VALUES
(1, 1, 1, 80.00, 4, 3, 120, 'quick_quiz'),
(1, 2, 5, 100.00, 3, 3, 90, 'quick_quiz'),
(2, 1, 1, 75.00, 4, 3, 150, 'quick_quiz'),
(3, 3, 9, 90.00, 3, 3, 110, 'quick_quiz');

-- Insert sample user progress
INSERT INTO `user_progress` (`user_id`, `subject_id`, `theme_id`, `level`, `experience`, `completed_lessons`) VALUES
(1, 1, 1, 2, 150, 8),
(1, 2, 5, 1, 100, 6),
(2, 1, 1, 1, 75, 4),
(3, 3, 9, 1, 90, 6);
