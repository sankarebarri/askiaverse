# Askiaverse Games

**Apprentissage ludique pour les élèves francophones du Mali**
A Progressive Web App (PWA) built with vanilla PHP, Tailwind CSS, Vite, Axios, and Alpine.js. It delivers interactive quizzes, offline support, and a native-like experience without requiring an app store install.

---

## Table of Contents

1. [Overview](#overview)
2. [Features](#features)
3. [Student Journey](#student-journey)
4. [Community (Guild Hall)](#community-guild-hall)
5. [Competition](#competition)
6. [Why It Works](#why-it-works)
7. [Teacher & Admin Tools](#teacher--admin-tools)
8. [Technical Highlights](#technical-highlights)
9. [Installation & Setup](#installation--setup)
10. [Directory Structure](#directory-structure)
11. [Contributing](#contributing)
12. [License](#license)

---

## Overview

Askiaverse Games is a mobile-first, installable web platform that transforms learning into an epic adventure. Designed for Francophone learners in Mali and beyond, it blends interactive quizzes, meaningful rewards, and real-time collaboration to make education engaging, inclusive, and effective.

---

## Features

* **Interactive Quizzes** in Math, French, English, and Mali History & Geography
* **Gamification**: Experience Points (XP), Askia Orbs, Levels
* **Progressive Web App**: installable, offline-first via Service Worker
* **Responsive Design**: mobile-first and works on any device
* **Communities & Competitions**: Guild Halls and tournaments

---

## Student Journey

1. **Instant Access**

   * Visit the site, install to home screen—no app store required.
   * Work offline; progress syncs when reconnected.

2. **Personal Dashboard**

   * Displays student’s Level, XP, and Askia Orbs.
   * Progress bar and clear goals for motivation.

3. **Quest-Style Quizzes**

   * Subjects as worlds, chapters as quests.
   * Instant feedback: correct answers get cheers, incorrect answers offer hints.
   * Perfect scores trigger confetti and fanfare.

4. **Rewards & Progress**

   * Earn XP and convert every 100 XP into 1 Askia Orb.
   * Orbs unlock rewards or special challenges.

---

## Community (Guild Hall)

When students click **Communauté**, they enter their **Guild Hall**, a collaborative space with:

### 1. My Guild Dashboard

* **Guild Name & Crest** (e.g., "The Timbuktu Scholars")
* **Member List**: username, city, and school.
* **Guild Message Board**: post encouragement, tips, and announcements.
* **Weekly Quest Progress**: visual bar tracking the guild’s collective target.

### 2. Guild Leaderboards

* Ranks all guilds by total weekly contribution (XP or questions answered).
* Top guilds earn badges and spotlight messages.

### 3. Joining a Guild

* First visit assigns or creates a guild with a welcome animation and confetti.

### 4. Guild Mechanics

* **Weekly Guild Quests**: collective goals (e.g., 20,000 XP as a team).
* **Co-op Puzzles (Future)**: collaborative challenges requiring communication via the message board.

---

## Competition

* **Head-to-Head Matches**: quiz duels with friends.
* **Inter-School Tournaments**: represent your school on national leaderboards.
* All results build a student’s legacy in Askiaverse.

---

## Why It Works

* **Gamification** turns study into play.
* **Immediate Feedback** reinforces learning.
* **Meaningful Rewards** boost motivation.
* **Offline-First PWA** ensures access even with flaky internet.

---

## Teacher & Admin Tools

* **Class Setup**: create classes, invite students, assign quizzes (coming soon).
* **Progress Dashboards**: monitor class and individual performance.
* **Safe Spaces**: moderated guilds and tournaments.

---

## Technical Highlights

* **PWA**: installable, offline-capable, secure.
* **Responsive**: works on phones, tablets, desktops.
* **Vanilla PHP** backend with PDO (MySQL).
* **Tailwind CSS**, **Vite** for fast builds.
* **Axios** for API calls, **Alpine.js** for UI interactivity.

---

## Installation & Setup

1. **Clone** into XAMPP `htdocs`:

   ```bash
   cd C:\xampp\htdocs
   git clone https://github.com/YourUser/askiaverse.git
   ```
2. **Install PHP deps**:

   ```bash
   cd askiaverse
   composer install
   ```
3. **Install Node & build frontend**:

   ```bash
   cd resources
   npm ci
   npm run build
   ```
4. **Configure .env** (copy `.env.example`), then start Apache & MySQL.
5. Visit `http://askiaverse.local/` or `http://localhost/askiaverse/`.

---

## Directory Structure

```text
askiaverse/
├── app/
├── config/
├── database/
├── public/
│   └── assets/
├── resources/
├── routes/
├── src/
├── tests/
├── vendor/
├── .env
├── composer.json
├── composer.lock
├── package.json
├── tailwind.config.js
├── vite.config.js
├── .gitignore
└── .github/workflows/ci-cd.yml
```

---

## Contributing

1. Fork and branch.
2. Add tests for new features.
3. Submit pull requests with clear descriptions.

---

## License

This project is licensed under the MIT License. See [LICENSE](LICENSE) for details.

---

> *"Learn, play, and grow every day!"*
