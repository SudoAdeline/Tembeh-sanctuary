# Of Beautiful Hope — The World of Tembeh

A creative sanctuary by **Tembeh** — music, writing, and visual art grown in solitude, shared with intention. Hosted on Hostinger with a built-in admin panel for managing content directly from the browser.

## Features

### Public Site
- **Hero** — Animated forest scene with floating particles, SVG tree silhouettes, and custom cursor
- **Music** — Track grid with a fully functional audio player (play/pause, seek, progress tracking)
- **Writing** — Expandable written pieces — click to read, click again to collapse
- **Art Gallery** — Image and video grid with hover overlays and a full-screen lightbox (keyboard navigation)
- **Video Support** — Upload and display videos alongside images in the gallery
- **Membership Tiers** — Wanderer (free) and Member with feature comparison

### Admin Panel
Access via **triple-click** on the footer logo or **Ctrl+Shift+A**.

- Upload music tracks (MP3/WAV) with cover art, title, artist, and description
- Upload images and videos to the art gallery
- Add writing pieces with title, date, and body text
- Edit and delete any content
- Files are uploaded directly to the server — no size limits from localStorage
- Password protected with bcrypt hashing (server-side)

## Project Structure

```
index.html          — The full site (HTML/CSS/JS)
admin-api.php       — PHP backend for uploads and content management
data/
  music.json        — Music track metadata
  writing.json      — Writing pieces
  art.json          — Art gallery metadata
media/
  music/            — Audio files and cover images
  art/              — Images and videos
.htaccess           — Security and upload config
.user.ini           — PHP upload limits (512MB)
```

## Deployment (Hostinger)

1. Upload all files to `public_html` on Hostinger
2. Visit your domain
3. Open the admin panel (Ctrl+Shift+A)
4. Set your password on first visit
5. Start uploading content

## Adding Content

All content is managed through the admin panel in the browser:

1. Open the admin panel on your live site
2. Choose a tab (Music, Writing, or Art)
3. Fill in the details and select your file
4. Click save — the file uploads to your server and appears on the site immediately

## Tech Stack

- **HTML/CSS/JS** — Single-page, no frameworks
- **PHP** — Backend API for file uploads and JSON management
- **Hostinger** — Shared hosting with PHP support
- Forest/botanical theme with custom cursor, scroll animations, and responsive layout

## Artist

Created by **Tembeh** — a multidisciplinary artist working across sound, word, and image.

**ofbeautifulhope.com**

## License

All rights reserved. Tembeh
