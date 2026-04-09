# Preeti Amble — Blog

> *Stories from a life lived slowly.*

A warm, Kindle-inspired personal blog for writer **Preeti Amble** — built for reading, not scrolling. The design draws from the feel of a Kindle Paperwhite: sepia tones, generous line-height, narrow content columns, and serif typography throughout.

---

## Tech Stack

![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![TailwindCSS](https://img.shields.io/badge/Tailwind_CSS-CDN-06B6D4?style=for-the-badge&logo=tailwindcss&logoColor=white)
![Alpine.js](https://img.shields.io/badge/Alpine.js-3.x-8BC0D0?style=for-the-badge&logo=alpine.js&logoColor=white)
![Apache](https://img.shields.io/badge/Apache-2.4-D22128?style=for-the-badge&logo=apache&logoColor=white)
![ImageKit](https://img.shields.io/badge/ImageKit-CDN-FF6B00?style=for-the-badge&logo=imagekit&logoColor=white)
![Google Fonts](https://img.shields.io/badge/Google_Fonts-Newsreader_·_Noto_Serif_·_Inter-4285F4?style=for-the-badge&logo=google&logoColor=white)
![cPanel](https://img.shields.io/badge/cPanel-Hosted-FF6C2C?style=for-the-badge&logo=cpanel&logoColor=white)

---

## Project Structure

```
blog/
├── index.php                   # Homepage — blog listing
├── post.php                    # Single post page
├── .htaccess                   # Apache routing (slug → post.php)
├── router.php                  # Dev server router (not deployed)
│
├── config/
│   ├── db.php                  # DB credentials (gitignored)
│   └── db.example.php          # Template — copy to db.php
│
├── includes/
│   ├── head.php                # DOCTYPE, meta, Tailwind config
│   ├── navbar.php              # Sticky navbar with mobile menu
│   ├── hero.php                # Tagline section
│   ├── category_filter.php     # Dynamic category pills
│   ├── blog_grid.php           # Paginated post grid + DB query
│   ├── pagination.php          # Prev/Next page navigation
│   └── footer.php              # Footer
│
└── docs/
    ├── schema.sql              # Full MySQL schema
    ├── seed.sql                # 6 seed posts (generated)
    ├── blog_data.json          # Original MongoDB export
    └── blog_data.md            # Post content in Markdown
```

---

## Features

- **Kindle-inspired reading experience** — sepia background, Lora/Newsreader serif fonts, 680px content width, generous line-height
- **Dynamic categories** — managed from the admin panel, only categories with published posts appear
- **Block-editor content rendering** — parses BlockNote JSON (bold, italic, headings, dividers, lists, images) into semantic HTML
- **Post recommendations** — same-category posts shown first at the end of each article
- **View counter** — increments on every post visit
- **Pagination** — 6 posts per page, category-aware
- **Mobile responsive** — hamburger nav, fluid grid, touch-friendly
- **Image CDN** — all cover images served via ImageKit with on-the-fly optimisation
- **XSS safe** — all output goes through `htmlspecialchars()`

---

## Color Palette

| Role | Name | Hex |
|---|---|---|
| Page Background | Old Paper | `#fff9ee` |
| Card Surface | Parchment | `#faf3e6` |
| Border | Warm Linen | `#DDD0BC` |
| Primary Text | Dark Walnut | `#1e1b14` |
| Secondary Text | Aged Brown | `#53433c` |
| Muted Text | Faded Ink | `#9c8878` |
| Accent | Burnt Sienna | `#8c4a24` |
| Accent Alt | Terracotta | `#aa623a` |
| Accent Light | Warm Blush | `#fadec5` |

---

## Typography

| Role | Font |
|---|---|
| Display / Headings | Newsreader (serif, italic) |
| Body / Post content | Noto Serif |
| UI / Labels / Nav | Inter |

---

## Local Development

**1. Clone the repo**
```bash
git clone https://github.com/akaashchitragar/preetiamble-blog.git
cd preetiamble-blog
```

**2. Set up the database**

- Import `docs/schema.sql` into your MySQL database via phpMyAdmin
- Import `docs/seed.sql` to load the sample posts
- Copy `config/db.example.php` → `config/db.php` and fill in your credentials

**3. Start the dev server**
```bash
# Local MySQL
php -S localhost:8000 router.php

# Remote cPanel MySQL
DB_HOST=your.server.hostname.com php -S localhost:8000 router.php
```

**4. Open** `http://localhost:8000`

---

## Deployment (cPanel)

1. Upload all files **except** `config/db.php`, `router.php`, and `docs/` to `public_html/`
2. Create `config/db.php` on the server using `db.example.php` as the template — set `DB_HOST` to `localhost`
3. Import `docs/schema.sql` in phpMyAdmin
4. The `.htaccess` handles all URL routing on Apache automatically

---

## Admin Panel

The companion admin panel lives at [`write.preetiamble.blog`](https://github.com/akaashchitragar/preetiamble-blog-admin) and handles:

- Writing & publishing posts with a rich text editor
- Auto-generating slugs from post titles
- Uploading cover images to ImageKit
- Managing categories
- Draft / publish workflow

---

*Crafted for the thoughtful reader.*
