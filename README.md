# The Vault — A Living Archive

A self-contained, single-page creative archive for music, writing, and visual art. Built as a pure HTML/CSS/JS experience with no frameworks or dependencies.

## Features

### Public-Facing Sections
- **Hero** — Animated forest scene with floating particles, SVG tree silhouettes, and custom cursor
- **Music** — Track grid with a fully functional audio player (play/pause, seek, progress tracking)
- **Writing** — Expandable list of written pieces — click to read, click again to collapse
- **Art Gallery** — Image grid with hover overlays and a full-screen lightbox (keyboard navigation with arrow keys and Escape)
- **Membership Tiers** — Wanderer (free) and Member ($8/month) with feature comparison
- **Audio Teaser** — Animated waveform preview player

### Admin Panel (Password-Protected)
Only the site owner can manage content. Access via:
- **Triple-click** the footer logo ("the vault")
- **Ctrl+Shift+A** keyboard shortcut

Admin capabilities:
- **Music** — Add tracks with audio files (MP3/WAV), cover images, title, artist, and description
- **Writing** — Add pieces with title, date, and full body text
- **Art** — Upload images with title and description
- Full **edit** and **delete** support for all content types
- Password is hashed (SHA-256) and stored securely in localStorage

### Design
- Forest/botanical theme with CSS custom properties
- Custom cursor with trailing ring
- Scroll-triggered reveal animations
- Responsive layout (mobile-friendly)
- Google Fonts: Cormorant Garamond + Karla

## Tech Stack

- **HTML5** — Single file, no build step
- **CSS3** — Custom properties, grid, animations, blend modes
- **Vanilla JS** — Web Audio API, IntersectionObserver, localStorage, Web Crypto API
- **No dependencies** — Fully self-contained

## Storage

All content is stored in the browser's `localStorage` as base64 data URLs. Keep in mind:
- ~5–10 MB storage limit depending on the browser
- Content persists per-browser — clearing browser data removes it
- Best with compressed audio and optimized images

## Getting Started

1. Open `vault (1).html` in any modern browser
2. Triple-click the footer logo or press `Ctrl+Shift+A` to open the admin panel
3. Set your password on first visit
4. Start adding your music, writing, and art

## License

All rights reserved.
