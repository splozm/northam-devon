# Event Display Fix Applied - 2026-02-17

## Problem Summary

Events linked to businesses via `_northam_event_venue` meta field were NOT displaying on business pages.

**Root Cause:**
- Our code queried WordPress posts by meta field: `get_posts(post_type='event', meta_key='_northam_event_venue')`
- This returned parent event posts (Post IDs 226, 228, 236, 237)
- When loaded as EM_Event objects, these posts had NO start date (`$em_event->start = EMPTY`)
- The date filter `if ($em_event->start && $em_event->start >= current_time())` failed
- Therefore, no events displayed

**Why dates were empty:**
- Events Manager stores individual event occurrences in its own database table (`wp_em_events`)
- The WordPress post is just the template/parent
- `EM_Events::get()` returns the actual dated occurrences (85 events found)
- But our query was only finding the 4 parent posts

## Solution Applied

Changed from querying WordPress posts to using Events Manager's API:

**Before (BROKEN):**
```php
// Query WordPress posts by meta field
$event_posts = get_posts( array(
    'post_type' => 'event',
    'meta_key' => '_northam_event_venue',
    'meta_value' => get_the_ID(),
) );

// Try to load as EM_Event - NO DATES!
foreach ( $event_posts as $event_post ) {
    $em_event = new EM_Event( $event_post->ID );
    // $em_event->start is EMPTY ❌
}
```

**After (WORKING):**
```php
// Get all future events from Events Manager API
$all_events = EM_Events::get( array(
    'scope' => 'future',
    'status' => 1,
) );

// Filter for events linked to this business
foreach ( $all_events as $em_event ) {
    $venue_id = get_post_meta( $em_event->post_id, '_northam_event_venue', true );

    if ( $venue_id == get_the_ID() ) {
        $events[] = $em_event; // Has dates! ✅
    }
}
```

## Files Modified

### 1. [single-northam_business.php](wordpress-theme/northam-kadence-child/single-northam_business.php:217-240)
Lines 217-240: Changed event query to use EM_Events::get() and filter by meta field

### 2. [functions.php](wordpress-theme/northam-kadence-child/functions.php:770-799)
`northam_get_venue_events()` - Updated to use EM_Events::get()

### 3. [functions.php](wordpress-theme/northam-kadence-child/functions.php:808-837)
`northam_get_business_events()` - Updated to use EM_Events::get()

## Verification

Tested on Golden Lion business page (http://northam-devon.local/business/golden-lion/):

✅ Events section now appears
✅ Shows 3+ events:
- "New Event for Lion" - Wed, 18th Feb at 12:00 PM
- "Quiz Night - The Golden Lion" - Mon, 23rd Feb at 7:00 PM
- "Quiz Night - The Golden Lion" - Mon, 9th Mar at 7:00 PM (recurring)

## How It Works Now

1. User creates event in Events Manager with dates
2. User selects business/venue in "Event Venue/Host" meta box
3. Meta field `_northam_event_venue` saved to event post
4. Events Manager creates individual occurrences in its database
5. **NEW:** Business page calls `EM_Events::get(scope='future')` to get dated events
6. **NEW:** Filters results by checking each event's `post_id` for the meta field
7. Events display with correct dates from Events Manager data

## Performance Note

This approach queries ALL future events then filters in PHP. For sites with many events, this could be optimized by:
- Caching the business-event relationships
- Using a direct database query joining `wp_em_events` with `wp_postmeta`

However, for a village directory with ~100 events, this is perfectly acceptable.

## Key Takeaway

**When working with Events Manager:**
- EM_Events::get() returns actual event occurrences with dates ✅
- WordPress get_posts() returns parent posts without dates ❌
- Always use EM_Events::get() for displaying events
- Use meta fields on the parent post for filtering
