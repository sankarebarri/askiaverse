# Askiaverse Design System

## 🎨 Color Palette

### Primary Colors
```css
--primary-color: #2A4D69    /* Deep Blue - Main brand color */
--secondary-color: #4B8691  /* Teal Blue - Secondary actions */
--success-color: #63B7AF    /* Mint Green - Success states, XP bar */
```

### Background Colors
```css
--dark-bg: #1E2B3A         /* Dark Navy - Main background */
--surface-bg: #253549      /* Lighter Navy - Cards, modals */
```

### Text Colors
```css
--text-color: #EAF0F1      /* Light Gray - Primary text */
--text-muted: rgba(234, 240, 241, 0.7) /* Muted text */
```

### Utility Colors
```css
--overlay: rgba(0, 0, 0, 0.2)           /* Dark overlays */
--overlay-light: rgba(255, 255, 255, 0.1) /* Light overlays */
--glass-bg: rgba(23, 31, 42, 0.7)       /* Glass morphism */
```

## 📝 Typography

### Font Families
- **Headings**: Poppins (600, 700 weights)
- **Body**: Lato (400, 700 weights)

### Font Sizes
```css
/* Desktop */
h1: 3.5em (56px)
h2: 2.5em (40px)
h3: 1.8em (28.8px)
body: 1em (16px)

/* Mobile */
h1: 2em (32px)
h2: 1.5em (24px)
h3: 1.2em (19.2px)
body: 0.9em (14.4px)
```

## 🏗️ Layout System

### Container Widths
```css
--container-max: 1100px    /* Main content */
--quiz-max: 800px         /* Quiz interface */
--dashboard-max: 1400px   /* Dashboard layout */
```

### Spacing Scale
```css
--spacing-xs: 4px
--spacing-sm: 8px
--spacing-md: 16px
--spacing-lg: 24px
--spacing-xl: 32px
--spacing-2xl: 48px
```

### Border Radius
```css
--radius-sm: 6px    /* Small elements */
--radius-md: 8px    /* Buttons, cards */
--radius-lg: 12px   /* HUD elements */
--radius-xl: 16px   /* Modals, containers */
--radius-2xl: 20px  /* Large containers */
```

## 🎮 Component Library

### Buttons

#### Primary Button
```html
<button class="btn btn-primary">
  Primary Action
</button>
```

#### Secondary Button
```html
<button class="btn btn-secondary">
  Secondary Action
</button>
```

#### Large CTA Button
```html
<button class="btn btn-primary btn-large">
  🚀 Call to Action
</button>
```

### HUD Components

#### Level Display
```html
<div class="hud-item" title="Niveau">
  LVL <span>5</span>
</div>
```

#### XP Progress Bar
```html
<div class="hud-item xp-bar-container" title="Expérience">
  <div class="xp-bar" style="width: 75%"></div>
</div>
```

#### Orbs Counter
```html
<div class="hud-item" title="Askia Orbs">
  🪙 <span>150</span>
</div>
```

#### Focus Tokens
```html
<div class="hud-item" title="Jetons Focus">
  🧘 <span>3</span>
</div>
```

### Navigation

#### Main Navigation
```html
<nav class="main-nav">
  <a href="#" class="nav-link active">📚 Apprendre</a>
  <a href="#" class="nav-link">🏆 Compétition</a>
  <a href="#" class="nav-link">👥 Communauté</a>
</nav>
```

#### User Menu
```html
<div class="user-menu">
  <button class="user-menu-btn">
    <div class="avatar-placeholder"></div>
    <span>Username</span>
    <span class="arrow-down">▼</span>
  </button>
  <div class="user-dropdown hidden">
    <!-- Dropdown items -->
  </div>
</div>
```

### Cards

#### Subject Card
```html
<div class="subject-card">
  <div class="icon">📐</div>
  <h3 class="subject-title">Mathématiques</h3>
  <p>Arithmétique et géométrie</p>
</div>
```

#### Grade Button
```html
<button class="grade-btn selected">
  <span class="grade-number">6</span>
  <span class="grade-label">6ème</span>
</button>
```

### Modals

#### Basic Modal
```html
<div class="modal-overlay">
  <div class="modal-content">
    <button class="close-modal-btn">&times;</button>
    <h2>Modal Title</h2>
    <div class="modal-body">
      <!-- Content -->
    </div>
  </div>
</div>
```

#### Topic Selection Modal
```html
<div class="topic-modal-overlay">
  <div class="topic-modal-content">
    <h2>Choisir un Thème</h2>
    <div class="topic-buttons-container">
      <button class="topic-btn">Algèbre</button>
      <button class="topic-btn">Géométrie</button>
      <button class="topic-btn random-mix">Mélange Aléatoire</button>
    </div>
  </div>
</div>
```

## 📱 Responsive Breakpoints

### Desktop First Approach
```css
/* Desktop: 900px+ */
/* Tablet: 600px - 900px */
/* Mobile: < 600px */
```

### Mobile Adaptations
- **Navigation**: Stack vertically on mobile
- **Buttons**: Full width for touch targets
- **Grids**: Single column layout
- **Typography**: Reduced font sizes
- **Spacing**: Tighter margins and padding

## 🎭 Animations

### Transitions
```css
--transition-fast: 0.2s ease-in-out
--transition-normal: 0.3s ease-in-out
--transition-slow: 0.5s ease-in-out
```

### Keyframe Animations
- **fadeIn**: Modal overlays
- **slideUp**: Modal content
- **pulse-glow**: Focus token crystal
- **xp-fill**: XP bar progress

## 🎨 Visual Effects

### Glass Morphism
```css
.glass-header {
  background: rgba(23, 31, 42, 0.7);
  backdrop-filter: blur(15px);
  border: 1px solid rgba(255, 255, 255, 0.1);
}
```

### Shadows
```css
--shadow-glass: 0 10px 30px rgba(0,0,0,0.2)
--shadow-hud: 0 4px 12px rgba(0,0,0,0.3)
--shadow-modal: 0 20px 40px rgba(0,0,0,0.4)
```

### Gradients
```css
--gradient-radial: radial-gradient(ellipse at bottom, #1B2735 0%, #090A0F 100%)
--gradient-primary: linear-gradient(90deg, #2A4D69, #4B8691)
--gradient-success: linear-gradient(90deg, #4B8691, #63B7AF)
```

## 🎯 Usage Guidelines

### Color Usage
- **Primary**: Main actions, important elements
- **Secondary**: Secondary actions, highlights
- **Success**: Positive feedback, progress indicators
- **Dark backgrounds**: Create depth and focus
- **Text colors**: Ensure sufficient contrast

### Typography Hierarchy
- **H1**: Page titles, hero text
- **H2**: Section headers
- **H3**: Subsection headers
- **Body**: Main content, descriptions

### Component Spacing
- **Cards**: 16px padding, 24px margin between
- **Buttons**: 12px vertical, 16px horizontal padding
- **Modals**: 30px padding, 20px margin
- **Navigation**: 8px gap between items

### Accessibility
- **Color contrast**: Minimum 4.5:1 ratio
- **Touch targets**: Minimum 44px for mobile
- **Focus states**: Visible focus indicators
- **Screen readers**: Proper ARIA labels 