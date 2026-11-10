# Northam Devon - Ways of Working

## Project Overview
WordPress community website for Northam, Devon. Kadence child theme with custom post types for venues, businesses, attractions, and groups.

---

## Critical Technical Gotchas

### 1. JSON Meta Field Handling (CRITICAL)

**Always use `wp_unslash()` before `json_decode()` on meta fields:**

```php
// CORRECT
$classes = json_decode( wp_unslash( $classes_json ), true );

// WRONG — fails silently if data contains apostrophes
$classes = json_decode( $classes_json, true );
```

**Why:** WordPress/MySQL stores escaped apostrophes as `\'` which is invalid JSON. `json_decode()` returns `NULL` silently without `wp_unslash()`.

**Where this applies:**
- `_northam_regular_classes` meta field
- Any JSON-encoded meta fields
- Files: `inc/meta-boxes.php`, `single-northam_venue.php`, `functions.php`

---

### 2. Events Architecture Decision

**Events are linked to businesses/venues via simple meta fields — NOT via Events Manager locations.**

```
_northam_event_venue      (int)    — Post ID of linked business/venue
_northam_event_venue_type (string) — "business" or "venue"
```

**Why:** The EM_Location sync approach was tried and removed — do not reintroduce it.

**How this works:**
- Meta box in event editor provides dropdown of all businesses/venues
- Helper functions `northam_get_venue_events()` and `northam_get_business_events()` query via `EM_Events::get()` then filter by meta field
- Weekly carousel with AJAX pagination (no page reload) on business/venue single pages

**Implementation files:**
- Meta box: `inc/meta-boxes.php`
- AJAX handler: `northam_load_weekly_events()` in `functions.php`
- JavaScript: `assets/js/events-pagination.js`
- Nonce: `northam_events_nonce`

---

### 3. User Roles & Capabilities Gotcha

Custom roles (`business_manager`, `venue_manager`, `group_admin`) have `edit_others_northam_*` set to `true`.

**Why:** WordPress adds `AND post_author = user_id` to admin list queries if this cap is false, but all posts are admin-authored. Without this cap, managers see empty lists.

**Important:** Do NOT remove `edit_others_*` caps — the admin post list will break. Actual post-level access control is handled by `northam_map_managed_post_caps()`, not these caps.

Roles registered via `northam_register_roles()` with version key `1.0.2`.

---

### 4. Regular Classes Integration

Classes are scraped from external URLs and stored as JSON in `_northam_regular_classes` post meta on venue posts.

**Two parsers in `functions.php`:**
- `northam_parse_town_council_html()` — h5/h6 structured pages (northamtowncouncil.gov.uk)
- `northam_parse_community_centre_html()` — Simple Calendar plugin pages (northamcommunitycentre.co.uk)

**Integration:** `northam_get_regular_classes_for_week()` merges regular classes into the events calendar view.

---

## Conventions

### Naming Patterns
- **All meta fields:** `_northam_` prefix (underscore hides from custom fields UI)
- **All CSS classes:** `northam-` prefix
- **Version constant:** `NORTHAM_VERSION` in `functions.php` — bump to bust CSS/JS cache

### Security
- Every meta box has its own nonce field
- Nonce naming: `northam_{type}_nonce` / `northam_{type}_meta`

### Gallery System
- Array of hidden fields `northam_gallery_ids[]`, max 10 images
- Stored as comma-separated IDs in `_northam_gallery` meta field
- Hero image: featured image first, fallback to first gallery image
- Gallery display excludes hero (no duplication)

---

## Local Development

**Environment:**
- Local by Flywheel at `/Users/alexbutt/Local Sites/northam-devon/`
- Site URL: `http://northam-devon.local`
- Theme is a **symlink** — edit files in `wordpress-theme/northam-kadence-child/`, changes are live immediately

**Cache busting:** Bump `NORTHAM_VERSION` constant in `functions.php` after CSS/JS changes.

**Deployment:** Run `./deploy.sh` to rsync theme to SiteGround (see README.md for details).
