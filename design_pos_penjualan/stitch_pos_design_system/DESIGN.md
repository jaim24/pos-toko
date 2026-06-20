---
name: Stitch POS Design System
colors:
  surface: '#f6fafe'
  surface-dim: '#d6dade'
  surface-bright: '#f6fafe'
  surface-container-lowest: '#ffffff'
  surface-container-low: '#f0f4f8'
  surface-container: '#eaeef2'
  surface-container-high: '#e4e9ed'
  surface-container-highest: '#dfe3e7'
  on-surface: '#171c1f'
  on-surface-variant: '#45474c'
  inverse-surface: '#2c3134'
  inverse-on-surface: '#edf1f5'
  outline: '#75777d'
  outline-variant: '#c5c6cd'
  surface-tint: '#545f73'
  primary: '#091426'
  on-primary: '#ffffff'
  primary-container: '#1e293b'
  on-primary-container: '#8590a6'
  inverse-primary: '#bcc7de'
  secondary: '#006c49'
  on-secondary: '#ffffff'
  secondary-container: '#6cf8bb'
  on-secondary-container: '#00714d'
  tertiary: '#330009'
  on-tertiary: '#ffffff'
  tertiary-container: '#590016'
  on-tertiary-container: '#ff4e69'
  error: '#ba1a1a'
  on-error: '#ffffff'
  error-container: '#ffdad6'
  on-error-container: '#93000a'
  primary-fixed: '#d8e3fb'
  primary-fixed-dim: '#bcc7de'
  on-primary-fixed: '#111c2d'
  on-primary-fixed-variant: '#3c475a'
  secondary-fixed: '#6ffbbe'
  secondary-fixed-dim: '#4edea3'
  on-secondary-fixed: '#002113'
  on-secondary-fixed-variant: '#005236'
  tertiary-fixed: '#ffdadb'
  tertiary-fixed-dim: '#ffb2b7'
  on-tertiary-fixed: '#40000d'
  on-tertiary-fixed-variant: '#92002a'
  background: '#f6fafe'
  on-background: '#171c1f'
  surface-variant: '#dfe3e7'
typography:
  page-title:
    fontFamily: Inter
    fontSize: 24px
    fontWeight: '600'
    lineHeight: 32px
  section-title:
    fontFamily: Inter
    fontSize: 18px
    fontWeight: '500'
    lineHeight: 28px
  body:
    fontFamily: Inter
    fontSize: 14px
    fontWeight: '400'
    lineHeight: 20px
  label-caps:
    fontFamily: Inter
    fontSize: 12px
    fontWeight: '500'
    lineHeight: 16px
    letterSpacing: 0.05em
  data-mono:
    fontFamily: JetBrains Mono
    fontSize: 14px
    fontWeight: '400'
    lineHeight: 20px
rounded:
  sm: 0.25rem
  DEFAULT: 0.5rem
  md: 0.75rem
  lg: 1rem
  xl: 1.5rem
  full: 9999px
spacing:
  sidebar-width: 256px
  container-padding: 24px
  gutter: 16px
  stack-sm: 8px
  stack-md: 16px
---

## Brand & Style
The design system is engineered for high-efficiency retail and inventory environments. The brand personality is **utilitarian, precise, and reliable**, focusing on reducing cognitive load for operators who interact with the system for several hours a day. 

The aesthetic follows a **Corporate Modern** style with a focus on high legibility and clear information hierarchy. It prioritizes functional clarity over decorative elements, using a structured grid and a sophisticated palette of deep slates and vibrant functional accents to guide the user's eye toward critical actions and data points.

## Colors
The color palette is strategically divided into structural and functional roles:
- **Structural (Slate):** Used for navigation (Sidebar), typography, and borders to create a stable, professional frame.
- **Action (Emerald):** Reserved strictly for primary success paths, confirmations, and active selection states.
- **Feedback (Rose/Amber):** High-visibility colors used sparingly for destructive actions and inventory alerts to ensure they stand out against the neutral background.
- **Surface:** A clean white surface is used for interactive containers (Cards/Modals) to separate content from the slate-tinted background.

## Typography
The typography system uses **Inter** for all UI elements to ensure maximum readability across various screen resolutions. 

For financial data, SKU numbers, and quantities, the design system utilizes a monospaced font (**JetBrains Mono**) to ensure vertical alignment of digits in tables and receipts, facilitating quicker price scanning and comparison. Labels use an uppercase treatment with increased tracking to differentiate metadata from editable body content.

## Layout & Spacing
The layout employs a **Fixed Sidebar** model on desktop to keep core navigation persistent and accessible. The main content area uses a fluid grid with a standard 24px (1.5rem) padding for safe areas.

**Breakpoints:**
- **Mobile (<768px):** The sidebar collapses into a bottom navigation bar or a hamburger menu. Margins reduce to 16px.
- **Tablet (768px - 1024px):** The sidebar may collapse to an icon-only rail to maximize workspace for data tables.
- **Desktop (>1024px):** Fixed 256px sidebar with expanded content area.

## Elevation & Depth
Depth is created through **Tonal Layering** and subtle shadows rather than heavy gradients.
- **Level 0 (Background):** Slate 100 serves as the canvas.
- **Level 1 (Cards):** White surfaces with a 1px border (Slate 200) and a soft, low-blur shadow to indicate interactivity.
- **Level 2 (Modals/Popovers):** Higher elevation with more diffused shadows to focus user attention on the task at hand.

Shadows should be tinted with a small amount of Slate to maintain a cohesive, cool-toned environment.

## Shapes
The shape language uses **Rounded** (0.5rem) corners for primary UI elements like buttons and inputs. Larger containers, such as dashboard cards and modals, utilize **rounded-xl** (1.5rem) to create a soft, modern enclosure for data. Badges and status indicators are fully rounded (pill-shaped) to distinguish them from interactive buttons.

## Components
- **Buttons:** 
    - *Primary:* Emerald 500 background, white text. 
    - *Secondary:* White background, Slate 200 border, Slate 700 text. 
    - *Danger:* Rose 50 background, Rose 600 text for subtle warnings; solid Rose 500 for critical destructive actions.
- **Inputs:** Use a 1px Slate 200 border. On focus, apply a 2px Emerald 400 ring with a white inner offset to ensure the active field is unmistakable.
- **Sidebar:** Dark Slate 800 background. Active items use an Emerald 500 left-accent border (4px) and a subtle ghost-white opacity change on the label.
- **Badges:** Use a light tint of the status color (e.g., Emerald 100) with deep-toned text (Emerald 700) for high contrast and accessibility.
- **Data Tables:** Use thin Slate 100 dividers between rows. The header row should use the `label-caps` typography style for clear categorization.