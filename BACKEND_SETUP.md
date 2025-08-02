# 🚀 Backend Setup - Askiaverse

## 📋 Prérequis

- **PHP 7.4+** avec les extensions suivantes :
  - `pdo_mysql`
  - `json`
  - `mbstring`
- **MySQL 5.7+** ou **MariaDB 10.2+**
- **Composer** (pour les dépendances)

## 🗄️ Base de Données

### 1. Créer la base de données

```sql
CREATE DATABASE askiaverse CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 2. Structure des tables

Assurez-vous que votre base de données contient les tables suivantes :

- `users` - Utilisateurs
- `subjects` - Matières
- `themes` - Thèmes par matière
- `questions` - Questions de quiz
- `quiz_attempts` - Tentatives de quiz

## ⚙️ Configuration

### 1. Installation automatique

Exécutez le script de configuration :

```bash
php setup-backend.php
```

Ce script va :
- ✅ Vérifier les dépendances PHP
- ✅ Créer le fichier `.env`
- ✅ Tester la connexion à la base de données
- ✅ Vérifier les tables
- ✅ Populer avec les données de test

### 2. Configuration manuelle

Si vous préférez configurer manuellement :

1. **Créer le fichier `.env`** :
```bash
cp .env.example .env
```

2. **Modifier les paramètres de base de données** dans `.env` :
```env
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=askiaverse
DB_USERNAME=root
DB_PASSWORD=votre_mot_de_passe
```

3. **Installer les dépendances** :
```bash
composer install
```

4. **Populer la base de données** :
```bash
php database/seeds/TestDataSeeder.php
```

## 👤 Utilisateur de Test

Un utilisateur de test est créé automatiquement :

- **Nom d'utilisateur** : `hamza`
- **Mot de passe** : `123456`
- **École** : `LYMG`
- **État** : `Gao`

## 🔗 API Endpoints

### Authentification

- `POST ?api=auth&action=login` - Connexion
- `POST ?api=auth&action=register` - Inscription
- `GET ?api=auth&action=logout` - Déconnexion

### Quiz

- `GET ?api=quiz&action=themes&subject_id=1` - Obtenir les thèmes
- `GET ?api=quiz&action=questions&subject_id=1&theme_id=1` - Obtenir les questions
- `POST ?api=quiz&action=submit` - Soumettre un résultat
- `GET ?api=quiz&action=attempts` - Historique des tentatives
- `GET ?api=quiz&action=stats` - Statistiques utilisateur
- `GET ?api=quiz&action=leaderboard` - Classement
- `GET ?api=quiz&action=daily-stats` - Statistiques quotidiennes

## 🧪 Test des Fonctionnalités

### 1. Test de connexion

1. Accédez à `http://askiaverse.local/?page=login`
2. Connectez-vous avec `hamza` / `123456`
3. Vous devriez être redirigé vers le dashboard

### 2. Test d'inscription

1. Accédez à `http://askiaverse.local/?page=register`
2. Créez un nouveau compte
3. Connectez-vous avec le nouveau compte

### 3. Test des quiz

1. Connectez-vous
2. Allez sur le dashboard
3. Cliquez sur "Mathématiques"
4. Sélectionnez "Arithmétique Simple"
5. Choisissez "Quiz Rapide"
6. Répondez aux questions

## 🔧 Dépannage

### Erreur de connexion à la base de données

1. Vérifiez que MySQL est démarré
2. Vérifiez les paramètres dans `.env`
3. Vérifiez que la base de données existe

### Erreur "Class not found"

1. Vérifiez que Composer est installé
2. Exécutez `composer install`
3. Vérifiez l'autoloader

### Erreur de session

1. Vérifiez que les sessions PHP sont activées
2. Vérifiez les permissions du dossier de sessions

## 📁 Structure des Fichiers

```
src/
├── Controllers/          # Contrôleurs
│   ├── AuthController.php
│   └── QuizController.php
├── Models/              # Modèles
│   ├── User.php
│   ├── Subject.php
│   └── Quiz.php
└── Shared/              # Classes partagées
    ├── Database.php
    ├── Config.php
    └── BaseController.php

database/
└── seeds/
    └── TestDataSeeder.php

config/
└── config.php          # Configuration principale
```

## 🚀 Prochaines Étapes

1. **Testez toutes les fonctionnalités**
2. **Ajoutez plus de questions** dans le seeder
3. **Implémentez les fonctionnalités manquantes** (profil, paramètres)
4. **Ajoutez la validation côté serveur**
5. **Implémentez la sécurité** (CSRF, rate limiting)
6. **Ajoutez les tests unitaires**

## 📞 Support

Si vous rencontrez des problèmes :

1. Vérifiez les logs PHP
2. Vérifiez les logs MySQL
3. Activez le mode debug dans `.env`
4. Consultez la documentation PHP/MySQL 