# Threefold Artists - CLAUDE.md

## Project Overview
Nonprofit performing arts website for Threefold Artists - "Keeping Theatre Alive".
Brings live performing arts (theatre, music, dance) to underserved communities.

## Tech Stack
- **Framework:** Laravel 12 + PHP 8.4
- **Admin:** Filament 3
- **Frontend:** Tailwind CSS v4 + Alpine.js + Blade
- **Database:** SQLite (MVP), planned migration to MySQL
- **Media:** Spatie Media Library
- **Payments:** Stripe Checkout (placeholder keys for now)

## Brand
- Colors: Curtain Red (#8B1A2B), Stage Gold (#C9A84C), Theatre Black (#1A1A1A), Linen (#F5F0E8), Spotlight Amber (#D4943A)
- Fonts: Playfair Display (headings), Source Sans 3 (body)
- Logo: public/images/logo.png

## Key Patterns
- All PHP files use `declare(strict_types=1)`
- PSR-12 coding standard
- Form Request classes for validation
- Named routes throughout
- Blade component layout: `<x-layouts.app>`
- Filament admin at /admin

## Admin Access
- URL: /admin
- Default: admin@threefoldartists.org / threefold2026!

## Models
Page, Artist, PerformanceRequest, Event, BlogPost, Testimonial, GalleryItem, Donation, ArtistApplication, ContactMessage, ImpactMetric

## Environment Notes
- Forge-provisioned server
- Nginx serves from /public
- SQLite database at database/database.sqlite
- Stripe keys are placeholders (sk_test_placeholder)
- Mail is configured but SMTP not yet set up

## Commands
```bash
php artisan migrate          # Run migrations
php artisan db:seed          # Seed initial data
npm run build                # Build frontend assets
php artisan optimize:clear   # Clear all caches
```
