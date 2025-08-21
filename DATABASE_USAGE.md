# Askiaverse Database & Frontend Usage

## 🗄️ Database Setup

The database has been successfully created and populated with educational content for Askiaverse.

### Database Structure
- **6 subjects**: Mathématiques, Français, Sciences, Histoire, Géographie, Anglais
- **24 themes**: 4 themes per subject with varying difficulty levels
- **19 sample questions**: Covering different subjects and themes
- **3 sample users**: For testing purposes
- **Sample quiz attempts and progress data**

### Database Tables
- `subjects` - Main educational subjects
- `themes` - Specific topics within each subject
- `questions` - Quiz questions with multiple choice answers
- `users` - User accounts
- `quiz_attempts` - User quiz performance tracking
- `user_progress` - Learning progress tracking
- `achievements` - Gamification system
- `user_achievements` - User achievement tracking

## 🌐 Frontend Pages

### 1. Homepage (`/`)
- Landing page with hero section
- Call-to-action buttons
- Link to subjects exploration

### 2. Subjects Overview (`/subjects.php`)
- Displays all 6 subjects in a grid
- Shows theme count for each subject
- Beautiful cards with subject icons and colors
- Links to individual subject pages

### 3. Individual Subject (`/subject.php?id=X`)
- Detailed view of a specific subject
- Lists all themes with difficulty levels
- Shows sample questions with correct answers
- Interactive question display

### 4. Admin Dashboard (`/admin-dashboard.php`)
- Overview of database statistics
- Recent quiz attempts
- Quick navigation to other pages
- Data visualization

## 🚀 How to Test

### Start the Development Server
```bash
cd public
php -S localhost:8000
```

### Access the Pages
1. **Homepage**: http://localhost:8000/
2. **Subjects**: http://localhost:8000/subjects.php
3. **Mathématiques**: http://localhost:8000/subject.php?id=1
4. **Admin Dashboard**: http://localhost:8000/admin-dashboard.php

### Test Different Subjects
- Mathématiques: `id=1`
- Français: `id=2`
- Sciences: `id=3`
- Histoire: `id=4`
- Géographie: `id=5`
- Anglais: `id=6`

## 📊 Sample Data

### Sample Users (Password: 123456)
- `hamza` - hamza@askiaverse.ml
- `fatou` - fatou@askiaverse.ml
- `moussa` - moussa@askiaverse.ml

### Sample Questions
- **Mathématiques**: Basic arithmetic operations
- **Français**: Grammar and vocabulary
- **Sciences**: Human body and biology
- **Histoire**: Mali and African history
- **Géographie**: Mali geography and world geography
- **Anglais**: Basic English vocabulary

## 🎨 Features

### Visual Design
- Modern, responsive design using Tailwind CSS
- Color-coded subjects with icons
- Difficulty level indicators (facile, moyen, difficile)
- Interactive hover effects and transitions

### Content Organization
- Hierarchical structure: Subject → Theme → Questions
- Difficulty progression system
- Sample questions with explanations
- Progress tracking capabilities

### User Experience
- Intuitive navigation between subjects
- Clear visual hierarchy
- Mobile-responsive design
- French language interface (as requested)

## 🔧 Technical Details

### Database Connection
- Uses XAMPP MySQL/MariaDB
- Connection through `src/bootstrap.php`
- PDO for database operations

### File Structure
- `database/schema.sql` - Database structure
- `database/seed_data.sql` - Sample data
- `resources/views/subjects/` - Subject views
- `public/*.php` - Public access pages

### Security Features
- SQL injection prevention with prepared statements
- XSS protection with `htmlspecialchars()`
- Input validation and sanitization

## 📈 Next Steps

### Potential Enhancements
1. **User Authentication System**
   - Login/logout functionality
   - User registration
   - Session management

2. **Quiz Engine**
   - Interactive quiz taking
   - Score calculation
   - Progress tracking

3. **Admin Panel**
   - Content management
   - User management
   - Analytics dashboard

4. **Gamification**
   - Achievement system
   - Experience points
   - Leaderboards

## 🐛 Troubleshooting

### Common Issues
1. **Database Connection Error**
   - Ensure XAMPP is running
   - Check database credentials in `.env`
   - Verify database exists

2. **Page Not Found**
   - Check if PHP server is running
   - Verify file paths
   - Check for syntax errors

3. **Data Not Displaying**
   - Verify database tables exist
   - Check if seed data was loaded
   - Review SQL queries

### Database Reset
If you need to reset the database:
```bash
/opt/lampp/bin/mysql -u root -e "DROP DATABASE askiaverse; CREATE DATABASE askiaverse CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
/opt/lampp/bin/mysql -u root askiaverse < database/simple_schema.sql
/opt/lampp/bin/mysql -u root askiaverse < database/seed_data.sql
```

## 📞 Support

The system is now ready for testing and development. All database content is displayed on the frontend with a beautiful, responsive design that follows the Askiaverse brand guidelines.
