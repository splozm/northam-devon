# Event-Venue Relationship Implementation

## Problem Solved
Previously, the code attempted to use Events Manager's EM_Location system to link events with businesses and venues. This was overly complex and didn't work properly in the admin interface - there was no visible field to select a business/venue when creating an event.

## Solution: Simple Relationship Field
Instead of using EM_Location, events now use a simple meta field `_northam_event_venue` that stores the post ID of the linked business or venue.

---

## Changes Made

### 1. New Meta Box for Events ([inc/meta-boxes.php](wordpress-theme/northam-kadence-child/inc/meta-boxes.php))

**Added:** Event Venue/Host meta box (lines ~557-650)

This meta box appears when creating/editing events and provides:
- Dropdown with two optgroups: "Businesses" and "Community Venues"
- Lists all published businesses and venues alphabetically
- Saves the selected venue ID to `_northam_event_venue`
- Saves the venue type (business/venue) to `_northam_event_venue_type`

**Key fields:**
```php
_northam_event_venue      // Post ID of the business or venue
_northam_event_venue_type // Either "business" or "venue"
```

---

### 2. Updated Event Queries

#### Business Single Page ([single-northam_business.php](wordpress-theme/northam-kadence-child/single-northam_business.php:216-230))

**Changed:** Event query to use new relationship field instead of EM_Location

**Before:**
```php
$em_location_id = get_post_meta( get_the_ID(), '_northam_em_location_id', true );
$events = EM_Events::get( array( 'location' => $em_location_id, ... ) );
```

**After:**
```php
$event_posts = get_posts( array(
    'post_type' => 'event',
    'meta_key' => '_northam_event_venue',
    'meta_value' => get_the_ID(),
    ...
) );
```

#### Helper Functions ([functions.php](wordpress-theme/northam-kadence-child/functions.php))

**Updated:**
- `northam_get_venue_events()` - Lines ~819-848
- `northam_get_business_events()` - Lines ~897-926

Both now query events by the `_northam_event_venue` meta field instead of EM_Location.

---

### 3. Removed Old Sync Functions

**Removed from [functions.php](wordpress-theme/northam-kadence-child/functions.php):**
- `northam_sync_venue_to_em()` - No longer needed
- `northam_sync_business_to_em()` - No longer needed
- Associated `save_post_*` hooks

These functions were creating EM_Location objects when businesses/venues were saved, which is no longer necessary.

---

## How It Works Now

### Creating an Event with a Venue/Business

1. Go to Events Manager → Add New Event
2. Fill in event details (title, date, time, etc.)
3. In the **"Event Venue/Host"** meta box:
   - Select a business from the "Businesses" group, OR
   - Select a venue from the "Community Venues" group
4. Click "Publish"

### Viewing Events on Business/Venue Pages

When a business or venue page loads:
1. Query all events where `_northam_event_venue = {current_post_id}`
2. Filter for future events only
3. Display in the "Upcoming Events" section

---

## Testing Checklist

- [ ] Create a new event
- [ ] Verify the "Event Venue/Host" meta box appears in the event editor
- [ ] Select a business from the dropdown
- [ ] Save the event
- [ ] Visit the business page
- [ ] Verify the event appears in the "Upcoming Events" section
- [ ] Repeat for a community venue

---

## Files Modified

| File | Lines Changed | Description |
|------|--------------|-------------|
| [inc/meta-boxes.php](wordpress-theme/northam-kadence-child/inc/meta-boxes.php) | +95 lines | Added event venue meta box and save function |
| [single-northam_business.php](wordpress-theme/northam-kadence-child/single-northam_business.php:216-230) | ~15 lines | Updated event query to use new relationship |
| [functions.php](wordpress-theme/northam-kadence-child/functions.php) | Modified 2 functions, removed 2 functions | Updated helper functions, removed sync code |

**Total:** ~110 lines added/modified

---

## Benefits of This Approach

✅ **Simple** - Just a dropdown field, no complex sync logic
✅ **Visible** - Clear UI in the event editor
✅ **Flexible** - Works with both businesses and venues
✅ **Maintainable** - No dependency on EM_Location behavior
✅ **Backward Compatible** - Event display code still works the same way

---

## Database Fields Reference

### Event Meta Fields
```
_northam_event_venue       (int)    - Post ID of business or venue
_northam_event_venue_type  (string) - "business" or "venue"
_northam_event_map_url     (string) - Google Maps URL (existing field)
```

### Legacy Fields (No Longer Used)
```
_northam_em_location_id    (int)    - Old EM_Location ID (can be cleaned up)
```

---

## Migration Notes

If you have existing events that used the old EM_Location system, they won't automatically link to businesses/venues. You'll need to:

1. Edit each existing event
2. Select the appropriate business/venue from the dropdown
3. Re-save the event

There's no automated migration because the old system may have created duplicate EM_Locations that don't cleanly map back to businesses/venues.
