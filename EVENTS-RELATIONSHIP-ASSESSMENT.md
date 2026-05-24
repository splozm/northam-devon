# Business & Venue Events Relationship - System Assessment

## Date: 2026-02-20

## Overview

This document assesses the current state of the events relationship system that connects Events Manager events to businesses and community venues in the Northam Devon theme.

## Current Implementation Status

### ✅ What's Working

#### 1. **Event Admin Interface** ([inc/meta-boxes.php:555-684](wordpress-theme/northam-kadence-child/inc/meta-boxes.php#L555-L684))

**Meta Box: "Event Venue/Host"**
- Located in the event edit screen (post type: `event`)
- Dropdown selector with two optgroups:
  - **Businesses** (post_type: `northam_business`)
  - **Community Venues** (post_type: `northam_venue`)
- Saves two meta fields:
  - `_northam_event_venue` - The post ID of the business/venue
  - `_northam_event_venue_type` - Either "business" or "venue"
- Uses jQuery to auto-populate venue type based on selection

**Functionality:**
```php
// Meta fields saved on event posts:
_northam_event_venue = 86 (Golden Lion business ID)
_northam_event_venue_type = "business"
```

#### 2. **Event Querying System** (PHP Functions)

**Helper Functions in functions.php:**

**`northam_get_business_events($business_id, $limit)`** (Lines 808-838)
- Gets all future events from Events Manager API
- Filters by `_northam_event_venue` meta field matching business ID
- Returns array of EM_Event objects
- Has limit parameter (default 5)

**`northam_get_venue_events($venue_id, $limit)`** (Lines 770-800)
- Identical logic to business events
- Filters by venue ID instead

**Key Implementation Detail:**
```php
// Uses EM_Events::get() NOT get_posts()
$all_events = EM_Events::get( array(
    'scope' => 'future',
    'status' => 1,
) );

// Then filters in PHP by meta field
foreach ( $all_events as $em_event ) {
    $venue_id = get_post_meta( $em_event->post_id, '_northam_event_venue', true );
    if ( $venue_id == $business_id ) {
        $events[] = $em_event;
    }
}
```

#### 3. **Business Page Event Display** ([single-northam_business.php:216-322](wordpress-theme/northam-kadence-child/single-northam_business.php#L216-L322))

**Features:**
- ✅ Weekly pagination (AJAX-based carousel navigation)
- ✅ Smooth horizontal slide transitions
- ✅ "This Week" / "Next Week" / "Previous Week" navigation
- ✅ Event cards with thumbnail, date, time, map link
- ✅ Only shows events for current week by default
- ✅ URL parameter support: `?week=1` for future weeks
- ✅ Section only displays if events exist

**AJAX Implementation:**
- JavaScript: [assets/js/events-pagination.js](wordpress-theme/northam-kadence-child/assets/js/events-pagination.js)
- AJAX Handler: `northam_load_weekly_events()` in functions.php
- No page reloads, smooth transitions

#### 4. **Data Flow (Currently Working)**

```
[Event Created in WP Admin]
         ↓
[User selects "Golden Lion" from dropdown]
         ↓
[Meta fields saved: _northam_event_venue=86, _northam_event_venue_type="business"]
         ↓
[Events Manager creates recurring occurrences in wp_em_events table]
         ↓
[Business page loads]
         ↓
[EM_Events::get() fetches all future event occurrences]
         ↓
[PHP filters events by _northam_event_venue meta on parent post]
         ↓
[Events display in weekly carousel]
```

## ❌ What's Missing/Broken

### 1. **No Community Venue Single Page Template**

**Issue:**
- Community venues (post_type: `northam_venue`) don't have a dedicated single page template
- File doesn't exist: `single-northam_venue.php`
- When users visit a venue page, it likely falls back to the default Kadence template
- **No events display on venue pages**

**Impact:**
- Events can be linked to community venues in the admin
- The `northam_get_venue_events()` helper function exists
- But there's no frontend display for venue events
- Users can't see which events are happening at a venue

### 2. **Inconsistent Event Display**

**Current State:**
- ✅ Business pages: Full weekly pagination system with AJAX
- ❌ Venue pages: No event display at all
- ❌ Event archive/calendar: Unknown if it shows venue/business info

**Should Be:**
- Both businesses AND venues should show events
- Both should have the same weekly pagination experience

### 3. **Reverse Relationship Not Displayed**

**Issue:**
- Events link TO businesses/venues via meta field
- But business/venue pages need to query BACK to find their events
- This is currently working for businesses, but:
  - No visual indicator in admin of how many events are linked
  - No way to see all events for a venue/business from the admin

### 4. **Archive Page Gaps**

Let me check if there's an events archive that shows the business/venue relationship:

**Unknown:**
- Does the events calendar show which business/venue hosts each event?
- Can users filter events by business or venue?

## 🔍 Technical Details

### Database Structure

**WordPress Posts:**
- Post Type: `event` (individual events & recurring templates)
- Meta Field: `_northam_event_venue` → stores ID of business or venue post
- Meta Field: `_northam_event_venue_type` → stores "business" or "venue"

**Events Manager Tables:**
- `wp_em_events` - Stores individual event occurrences with dates
- Each occurrence has a `post_id` that links back to the WordPress event post
- The meta fields live on the WordPress post, not in EM tables

### Why the Original Approach Failed

**Problem (from DIAGNOSTIC-RESULTS.md):**
```php
// This DIDN'T work:
$event_posts = get_posts(array(
    'post_type' => 'event',
    'meta_key' => '_northam_event_venue',
    'meta_value' => 86,
));
$em_event = new EM_Event($event_post->ID);
// $em_event->start was EMPTY ❌
```

**Reason:**
- `get_posts()` returns parent/template posts
- Parent posts don't have `start` dates populated
- Individual occurrences are in `wp_em_events` table, not WordPress posts

**Solution (currently working):**
```php
// This DOES work:
$all_events = EM_Events::get(array('scope' => 'future'));
foreach ($all_events as $em_event) {
    $venue_id = get_post_meta($em_event->post_id, '_northam_event_venue', true);
    if ($venue_id == 86) {
        // $em_event->start is populated ✅
    }
}
```

## 📊 Current Capabilities Matrix

| Feature | Businesses | Community Venues | Status |
|---------|------------|------------------|--------|
| Link events in admin | ✅ Yes | ✅ Yes | Working |
| Helper function exists | ✅ `northam_get_business_events()` | ✅ `northam_get_venue_events()` | Working |
| Single page template | ✅ `single-northam_business.php` | ❌ Missing | **BROKEN** |
| Events section on page | ✅ Yes | ❌ No | **BROKEN** |
| Weekly pagination | ✅ Yes (AJAX) | ❌ No | **BROKEN** |
| AJAX carousel | ✅ Yes | ❌ No | **BROKEN** |
| Archive listing | ✅ Yes | ✅ Yes | Working |
| Show event count in admin | ❌ No | ❌ No | Missing |

## 🚨 Priority Issues

### HIGH PRIORITY

1. **Create venue single page template**
   - Copy `single-northam_business.php` → `single-northam_venue.php`
   - Adapt for venue-specific fields (capacity, facilities, etc.)
   - Include same events section with weekly pagination
   - Ensure AJAX script loads on venue pages too

2. **Update AJAX handler to support venues**
   - Currently only enqueues on `is_singular('northam_business')`
   - Needs to also enqueue on `is_singular('northam_venue')`

### MEDIUM PRIORITY

3. **Show event count in admin**
   - Add column to business/venue admin list showing "5 upcoming events"
   - Quick visual feedback for content managers

4. **Verify event archive displays business/venue info**
   - Check if event cards show "Hosted by Golden Lion"
   - Add if missing

### LOW PRIORITY

5. **Admin UX improvements**
   - Show list of linked events on business/venue edit screen
   - Quick links to edit those events
   - Warning if unlinking an event that has occurrences

## ✅ What's Working Well

1. **Clean data model** - Single source of truth via meta fields
2. **Flexible relationship** - Same system works for businesses AND venues
3. **Future-proof** - Uses Events Manager API correctly
4. **Performance** - Efficient filtering (acceptable for ~100 events)
5. **User experience** - AJAX pagination is smooth and modern
6. **Security** - Proper nonce verification on AJAX requests
7. **No database modifications** - Uses existing WP/EM structures

## 🎯 Recommended Next Steps

### Step 1: Create Venue Single Template
**Priority:** HIGH
**Estimated effort:** 1-2 hours
**Files to create/modify:**
- Create `single-northam_venue.php`
- Modify `functions.php` to enqueue AJAX script on venues
- Test with existing events linked to venues

### Step 2: Ensure Feature Parity
**Priority:** HIGH
**Estimated effort:** 30 minutes
**Tasks:**
- Verify venues have same event display as businesses
- Test weekly pagination on venue pages
- Ensure AJAX carousel works

### Step 3: Admin Improvements
**Priority:** MEDIUM
**Estimated effort:** 1 hour
**Tasks:**
- Add "Events" column to business/venue admin lists
- Show count of upcoming events
- Make count clickable to filter events

### Step 4: Documentation
**Priority:** LOW
**Estimated effort:** 30 minutes
**Tasks:**
- Update user guide for content editors
- Document how to link events to businesses/venues
- Add troubleshooting section

## 📝 Summary

**Overall Status:** 🟡 Partially Working

**What works:**
- Event-to-business linking in admin ✅
- Event display on business pages ✅
- AJAX weekly pagination ✅
- Data model and querying ✅

**What's broken:**
- No event display on venue pages ❌
- Inconsistent feature support between businesses and venues ❌

**Root cause:** Missing venue single template

**Fix complexity:** Low - mostly copy/paste from business template

**User impact:** Medium - venues can't showcase their events

**Recommended action:** Create venue single template to achieve feature parity with businesses
