# Northam Devon WordPress Theme

A Kadence child theme for the Northam Devon community website featuring custom post types for businesses, venues, attractions, and community groups.

**Live Site:** https://northamdevon.co.uk  
**Theme Location:** `wordpress-theme/northam-kadence-child/`

---

## Quick Start

### Local Development Setup

1. **Local by Flywheel site** at `/Users/alexbutt/Local Sites/northam-devon/`
2. **Site URL:** `http://northam-devon.local`
3. **Theme symlink:** Edit files in `wordpress-theme/northam-kadence-child/` (this repo), changes reflect immediately

### Deploy to Production

**First time setup:**
```bash
# Copy the template and add your credentials
cp .env.example .env
# Edit .env with your actual SSH credentials
```

**Deploy:**
```bash
./deploy.sh
```

The script shows a **dry-run preview** first, highlighting files that will be deleted, then asks for confirmation before deploying.

Syncs theme files to SiteGround via rsync. Credentials are loaded from `.env` file (gitignored). 

**Before deploying:** Bump `NORTHAM_VERSION` in `functions.php` to bust CSS/JS cache.

---

## Custom Post Types

| CPT | Archive URL | Single URL | Admin Label |
|-----|-------------|------------|-------------|
| `northam_business` | `/business/` | `/business/{slug}/` | Businesses |
| `northam_venue` | `/venue/` | `/venue/{slug}/` | Community Venues |
| `northam_attraction` | `/things-to-do/` | `/things-to-do/{slug}/` | Attractions |
| `northam_group` | `/community-group/` | `/community-group/{slug}/` | Community Groups |

**Note:** `northam_directory` CPT was removed from code.

---

## Key Templates

| Template | URL | Description |
|----------|-----|-------------|
| `front-page.php` | `/` | Homepage |
| `page-events-calendar.php` | `/events-calendar/` | Events calendar with regular classes |
| `single-northam_business.php` | `/business/{slug}/` | Business detail: hero, gallery, contact sidebar, weekly events |
| `single-northam_venue.php` | `/venue/{slug}/` | Venue detail: same as business, plus facilities/capacity |
| `archive-northam_business.php` | `/business/` | Business directory grid |
| `archive-northam_group.php` | `/community-group/` | **"Our Community" page** - unified grid of groups AND venues with type badges |
| `archive-northam_attraction.php` | `/things-to-do/` | Things to do grid |

---

## Design System

### Color Palette

```css
--northam-primary: hsl(175, 65%, 40%)         /* Teal */
--northam-accent: hsl(15, 80%, 55%)           /* Orange */
--northam-background: hsl(50, 30%, 97%)       /* Warm cream */
--northam-foreground: hsl(200, 30%, 15%)      /* Dark blue-gray */
```

### Typography

- **Headings:** Cormorant Garamond (serif)
- **Body:** Source Sans 3 (sans-serif)
- Fluid sizing using `clamp()`

### Header

- White background with backdrop blur
- Dark text with teal hover states
- Custom logo: teal circle with MapPin icon + "Northam Devon" text
- Nav menu icons: Calendar, Store, Compass, Users, History

---

## File Structure

```
wordpress-theme/northam-kadence-child/
├── functions.php              # CPT registration, theme setup, helper functions
├── style.css                  # All CSS (design system + components)
├── inc/
│   ├── meta-boxes.php         # Custom meta boxes for all CPTs
│   ├── taxonomies.php         # Custom taxonomies
│   └── block-patterns.php     # Block patterns
├── assets/
│   ├── css/admin.css          # WordPress admin styling
│   └── js/
│       ├── header.js          # Header interactions
│       └── events-pagination.js  # AJAX weekly events carousel
├── single-northam_business.php
├── single-northam_venue.php
├── archive-northam_business.php
├── archive-northam_group.php
├── archive-northam_attraction.php
├── front-page.php
└── page-events-calendar.php
```

---

## Meta Fields Reference

All meta fields use `_northam_` prefix (underscore hides from custom fields UI).

### Businesses
- `_northam_address`, `_northam_phone`, `_northam_email`
- `_northam_website`, `_northam_facebook`, `_northam_instagram`, `_northam_twitter`
- `_northam_opening_hours` (JSON)
- `_northam_gallery` (comma-separated attachment IDs)
- `_northam_location` (lat,lng for map)

### Venues
- Contact: same as businesses (address, phone, email, website)
- `_northam_facilities` (textarea)
- `_northam_capacity` (int)
- `_northam_gallery`
- `_northam_regular_classes` (JSON) — scraped classes data
- No opening hours or social media

### Attractions
- `_northam_website`
- `_northam_highlights` (textarea)
- `_northam_gallery`
- `_northam_location`

### Community Groups
- `_northam_contact_name`, `_northam_contact_email`, `_northam_contact_phone`
- `_northam_meeting_time`, `_northam_meeting_location`
- `_northam_website`, `_northam_facebook`, `_northam_instagram`
- `_northam_gallery`

### Events (Events Manager integration)
- `_northam_event_venue` (int) — Post ID of linked business/venue
- `_northam_event_venue_type` (string) — "business" or "venue"

---

## Helper Functions

### Gallery
```php
northam_get_gallery( $post_id )
// Returns: [ ['url' => '...', 'alt' => '...'], ... ]
```

### Events
```php
northam_get_venue_events( $venue_id, $limit = 10 )
northam_get_business_events( $business_id, $limit = 10 )
// Both return array of EM_Event objects filtered by _northam_event_venue
```

### Regular Classes
```php
northam_get_regular_classes_for_week( $start_date, $end_date, $venue_id = null )
// Merges scraped classes into events calendar view
```

### Social Links
```php
northam_get_social_links( $post_id )
// Returns: ['website' => '...', 'facebook' => '...', 'instagram' => '...', 'twitter' => '...']
```

---

## Dependencies

- **Parent Theme:** Kadence (required)
- **Plugins:** The Events Calendar (Events Manager)
- **PHP:** 7.4+
- **WordPress:** 6.0+

---

## Development Workflow

1. **Edit locally** in `wordpress-theme/northam-kadence-child/`
2. **Test** at `http://northam-devon.local`
3. **Bump version** in `functions.php` (currently `1.0.4`)
4. **Deploy** with `./deploy.sh`
5. **Commit** changes to git

---

## Troubleshooting

### Styles not updating
- Hard refresh: `Cmd+Shift+R`
- Bump `NORTHAM_VERSION` in `functions.php`
- Check you're editing repo files, not symlink destination

### Gallery not saving
- Check browser console for JS errors
- Verify nonce in form
- Ensure `$_POST['northam_gallery_ids']` is an array

### Symlink not working
- Verify absolute path (not relative)
- Check file permissions
- Restart Local by Flywheel

---

**For critical technical gotchas and ways of working, see [CLAUDE.md](CLAUDE.md).**
