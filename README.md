# The Everglade — tembeh.world

Beyond the reach of the city static lies a sanctuary of ancient light and living resonance. This is the digital home of **Tembeh** — musician, artist, creator — built as a single-page experience that feels like stepping into a forest.

**[tembeh.world](https://tembeh.world)**

## The Site

- **Landing** — Full-screen hero with floating particles, custom cursor, and the harp image
- **Gatherings** — Upcoming live events, Song Slams, and intimate concerts
- **The Lore** — Chapter-style storytelling about the Everglade and the Cantora
- **The Glade** — Newsletter signup, contact, and the gateway to The Hidden Thicket
- **The Hidden Thicket** — Coming soon page for the inner sanctuary (superfan space)
- **The Vault** — Music player, writing pieces, and art gallery with lightbox

### Admin Panel
Access via **triple-click** on the footer logo or **Ctrl+Shift+A**.

- Upload music (MP3/WAV) with cover art, title, and description
- Add writing pieces and art/video to the gallery
- Edit and delete content
- Password protected with bcrypt hashing

## Project Structure

```
index.html              — The full site (HTML/CSS/JS)
thicket/index.html      — The Hidden Thicket coming soon page
admin-api.php           — PHP backend for uploads and content management
datenschutz/index.html  — Privacy policy (DSGVO)
impressum/index.html    — Legal notice
data/
  music.json            — Music track metadata
  writing.json          — Writing pieces
  art.json              — Art gallery metadata
media/
  music/                — Audio files and cover images
  art/                  — Images and videos
```

## Tech Stack

- **HTML/CSS/JS** — Single-page, no frameworks, no build step
- **PHP** — Backend API for file uploads and JSON storage
- **localStorage** — Browser-side persistence as fallback
- **Hostinger** — Shared hosting with PHP support
- Fonts: Cormorant Garamond + Karla
- Custom cursor, scroll-reveal animations, floating particles, responsive design

## Deployment

1. Upload all files to `public_html` on Hostinger
2. Visit your domain
3. Open the admin panel (Ctrl+Shift+A)
4. Set your password on first visit
5. Start uploading content

## Artist

Created by **Tembeh** — a multidisciplinary artist working across sound, word, and image. Based in Germany, rooted in nature, building from the quiet.

## License

All rights reserved. Tembeh
