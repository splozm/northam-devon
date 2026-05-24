# Northam Devon Project Guidelines

## Project Overview
WordPress community website for Northam, Devon. Kadence child theme with custom post types for venues, businesses, attractions, and groups.

## Local Development
- Local by Flywheel at `/Users/alexbutt/Local Sites/northam-devon/`
- Theme is a **symlink** — edit files in `wordpress-theme/northam-kadence-child/`, changes are live immediately
- Site URL: `http://northam-devon.local`
- Bump `NORTHAM_VERSION` constant in `functions.php` to bust CSS/JS cache

---

## JSON Meta Field Handling (CRITICAL)

Always use `wp_unslash()` before `json_decode()` on meta fields:

```php
// CORRECT
$classes = json_decode( wp_unslash( $classes_json ), true );

// WRONG — fails silently if data contains apostrophes
$classes = json_decode( $classes_json, true );
```

**Why:** WordPress/MySQL stores escaped apostrophes as `\'` which is invalid JSON. `json_decode()` returns `NULL` silently without `wp_unslash()`.

Applies to `_northam_regular_classes` in:
- `inc/meta-boxes.php`
- `single-northam_venue.php`
- `functions.php` — `northam_get_regular_classes_for_week()`

---

## Events Integration

### Event-Venue/Business Relationship
Events are linked to businesses or venues via a **simple meta field** — NOT via Events Manager locations.

```
_northam_event_venue      (int)    — Post ID of the linked business or venue
_northam_event_venue_type (string) — "business" or "venue"
```

In the event editor, a meta box "Event Venue/Host" (in `inc/meta-boxes.php`) provides a dropdown of all businesses and venues. The EM_Location sync approach was tried and removed — do not reintroduce it.

Helper functions in `functions.php`:
- `northam_get_venue_events( $venue_id, $limit )` — queries `EM_Events::get()` then filters in PHP by `_northam_event_venue`
- `northam_get_business_events( $business_id, $limit )` — identical logic for businesses

### AJAX Weekly Events Pagination
Business and venue single pages show events in a weekly carousel with AJAX navigation (no page reload).

- JS: `assets/js/events-pagination.js`
- AJAX handler: `northam_load_weekly_events()` in `functions.php`
- Nonce: `northam_events_nonce`
- Script localised with key `northamEvents` — `businessId` is used for both businesses and venues
- Enqueued on `is_singular('northam_business')` or `is_singular('northam_venue')`

### Regular Classes Integration
- Classes scraped from external URLs and stored as JSON in `_northam_regular_classes` post meta on venue posts
- Two parsers in `functions.php`:
  - `northam_parse_town_council_html()` — h5/h6 structured pages (northamtowncouncil.gov.uk)
  - `northam_parse_community_centre_html()` — Simple Calendar plugin pages (northamcommunitycentre.co.uk)
- `northam_get_regular_classes_for_week()` merges regular classes into the events calendar view

---

## Custom Post Types

| CPT | URL | Archive |
|-----|-----|---------|
| `northam_business` | `/business/{slug}/` | `/business/` |
| `northam_venue` | `/venue/{slug}/` | `/venue/` (also shown on Our Community page) |
| `northam_attraction` | `/things-to-do/{slug}/` | `/things-to-do/` |
| `northam_group` | `/community-group/{slug}/` | `/community-group/` |
| `northam_directory` | — | `/directory/` |

`northam_venue` labels are "Community Venues" in the admin.

---

## Key Templates

- `front-page.php` — homepage
- `page-events-calendar.php` — events calendar (URL: `/events-calendar/`)
- `single-northam_business.php` — business detail: hero, gallery, contact sidebar, weekly events carousel
- `single-northam_venue.php` — venue detail: same structure as business, plus facilities/capacity; no opening hours or social media
- `archive-northam_group.php` — **"Our Community" page** at `/community-group/`: unified grid of both `northam_group` AND `northam_venue` posts with type badges (teal = group, orange = venue)
- `archive-northam_business.php` — business directory
- `archive-northam_attraction.php` — things to do

---

## User Roles

Three custom roles registered via `northam_register_roles()` (version key: `1.0.2`):

- `business_manager` — can edit own business posts + events
- `venue_manager` — can edit own venue posts + events
- `group_admin` — can edit own group posts

**Important:** These roles have `edit_others_northam_*` capability set to `true` so WordPress does not add `AND post_author = user_id` to admin list queries (all posts are admin-authored). Actual post-level access is controlled by `northam_map_managed_post_caps()`. Do not remove `edit_others_*` caps — the admin list will break.

Admin menu items are restricted per role via `northam_restrict_admin_menu()`.

---

## Conventions

- All meta fields: `_northam_` prefix
- All CSS classes: `northam-` prefix
- Every meta box has its own nonce
- All CSS lives in `style.css` with clear section headers
- Gallery: array of hidden fields `northam_gallery_ids[]`, max 10 images, stored as comma-separated IDs in `_northam_gallery`
- Hero image: featured image first, fallback to first gallery image
