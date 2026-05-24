# Northam Devon Site Restructure Plan - REVISED

## Current Structure → New Structure

### Navigation Changes

**CURRENT:**
1. **Homepage** - Welcome/Overview
2. **Events** - All events (The Events Calendar plugin)
3. **Directory/Business** - Local businesses with detail pages
4. **Things To Do** - Attractions (card-only, no detail pages)
5. **Community** - Community Groups

**NEW (4 sections + Homepage):**
1. **Homepage** - Welcome/Overview ✅ (no changes)
2. **Who's Here** - Business directory ✅ (no changes needed)
3. **Explore Northam** - Things to do/attractions ✅ (no changes needed)
4. **What's On?** - Existing events-calendar page ✅ (just rename page title)
5. **Our Community** - NEW combined page: Community Groups + Community Venues

---

## Key Architectural Changes - SIMPLIFIED

### 1. Business-to-Event Relationship (SIMPLIFIED)
**Problem:** Currently only Venues can host events via Events Manager integration
**Solution:** Add simple event display on Business single pages

#### What needs to happen (SIMPLIFIED):
- Sync Businesses to Events Manager locations (mirror existing Venue sync code)
- When Business is saved → create EM Location → store `_northam_em_location_id`
- Display "Upcoming Events" section on Business single pages (already exists, lines 217-278)
- **No complex auto-categorization needed** - just display events at the location

**Example:** Friday Fry-up at Kingsley Pub
- Kingsley Pub = Business post
- When saved → creates EM Location "Kingsley Pub"
- Admin creates Event "Friday Fry-up" → assigns location "Kingsley Pub"
- Event automatically shows on Kingsley Pub business page

### 2. Venue CPT → "Community Venues" (RENAMED/REPURPOSED)
**Current state:**
- `northam_venue` CPT exists with EM sync (lines 767-810 in functions.php)
- Used for general event venues

**New purpose:**
- Rename CPT labels to "Community Venues" (Village Hall, etc.)
- Keep existing EM sync code (no changes)
- Display on "Our Community" page alongside Community Groups

### 3. Event Categories (USER-MANAGED)
**No auto-assignment needed!**

The existing "Community Events" category (slug: `community-events`) has been created by user.
- Events Manager already has event-categories taxonomy
- User manually assigns events to "Community Events" category when creating them
- No code needed to auto-assign categories
- **Use WordPress Tags** for additional filtering if needed

### 4. "Our Community" Page (NEW - TO BE DESIGNED BY LOVABLE)
**Single unified page showing:**
- **Community Groups** (`northam_group` CPT)
- **Community Venues** (`northam_venue` CPT - relabeled)

**Layout:** Single-column grid, not tabs
- All items in one unified grid/list
- Can use tags or badges to distinguish: "Community Group" vs "Community Venue"

---

## File Changes Required - SIMPLIFIED

### Phase 1: Business-to-Event Integration ⭐ BACKEND ONLY

#### 1. **functions.php** - Add Business sync to Events Manager
**Location:** After line 833 (after venue sync code)

**New function:** `northam_sync_business_to_em()`
- **Exact copy** of `northam_sync_venue_to_em()` but for businesses
- Triggered on `save_post_northam_business`
- Creates/updates EM Location for each business
- Stores `_northam_em_location_id` meta

**Code to add:**
```php
function northam_sync_business_to_em( $post_id, $post, $update ) {
    // Skip autosaves and revisions
    if ( wp_is_post_autosave( $post_id ) || wp_is_post_revision( $post_id ) ) return;
    if ( $post->post_status !== 'publish' ) return;
    if ( ! class_exists( 'EM_Location' ) ) return;

    // Get business meta
    $address = get_post_meta( $post_id, '_northam_address', true );
    $phone = get_post_meta( $post_id, '_northam_phone', true );

    // Check if business already has an EM location linked
    $em_location_id = get_post_meta( $post_id, '_northam_em_location_id', true );

    if ( $em_location_id ) {
        // Update existing EM location
        $location = new EM_Location( $em_location_id );
        $location->location_name = $post->post_title;
        $location->location_address = $address;
        $location->location_phone = $phone;
        $location->location_status = 1;
        $location->save();
    } else {
        // Create new EM location
        $location = new EM_Location();
        $location->location_name = $post->post_title;
        $location->location_address = $address;
        $location->location_phone = $phone;
        $location->location_status = 1;
        if ( $location->save() ) {
            update_post_meta( $post_id, '_northam_em_location_id', $location->location_id );
        }
    }
}
add_action( 'save_post_northam_business', 'northam_sync_business_to_em', 20, 3 );
```

#### 2. **single-northam_business.php** - No changes needed ✅
**Location:** Lines 217-278 (events section)

**Current code already checks for `_northam_em_location_id`** - will work automatically once businesses sync!

#### 3. **meta-boxes.php** - No changes needed ✅
Already has all necessary fields

---

### Phase 2: Rename Venue CPT Labels 📝 ADMIN LABELS ONLY

#### 1. **functions.php** - Update CPT labels
**Location:** Lines 422-445 (venue CPT registration)

**Change labels from:**
- "Venues" → "Community Venues"
- "Venue" → "Community Venue"
- Menu name: "Venues" → "Community Venues"

**No structural changes** - just label updates for clarity

---

### Phase 3: "Our Community" Page Template 🎨 TO BE DESIGNED BY LOVABLE

#### 1. **page-our-community.php** (NEW - GET DESIGN FROM LOVABLE FIRST)
**Will be created after Lovable designs the page**

**Requirements for Lovable:**
- Query both `northam_venue` and `northam_group` post types
- Single-column unified grid layout (not tabs/sections)
- Card design showing:
  - Featured image
  - Title
  - Short description
  - Badge: "Community Group" or "Community Venue"
  - Contact info / meeting details
  - Link to details or external website

**Data to display:**

**For Community Groups:**
- Title, excerpt, featured image
- Contact person, meeting time, meeting location
- Website, Facebook links

**For Community Venues:**
- Title, excerpt, featured image
- Address, phone, email
- Capacity, facilities
- Upcoming events count

---

### Phase 4: WordPress Admin Configuration Only ⚙️ NO CODE

#### 1. **Rename existing page title**
- Go to `/events/` or `/events-calendar/` page
- Update title to "What's On?" (if desired)
- No template changes needed

#### 2. **Create "Our Community" page**
- Create new page in WordPress
- Title: "Our Community"
- Assign template: `page-our-community.php` (after Lovable designs it)

#### 3. **Update Navigation Menu**
**New Primary Menu structure:**
1. Home → `/`
2. Who's Here → `/business/`
3. Explore Northam → `/things-to-do/`
4. What's On? → `/events-calendar/` (existing)
5. Our Community → `/our-community/` (new page)

#### 4. **style.css** - Update nav icons (optional)
**Location:** Lines 200-296

Update CSS selector for Community link:
```css
/* Change from: */
.menu-item a[href*="community-group"]::before

/* To: */
.menu-item a[href*="our-community"]::before
```

Keep existing Users icon for "Our Community"

---

## Implementation Order - SIMPLIFIED

### Step 1: Backend - Business Events Integration ✅ CAN DO NOW
**Estimated time:** 30 minutes

**Files to modify:**
- `functions.php` - Add one function after line 833

**What it does:**
- When a Business is saved, automatically creates an EM Location
- Events can then be assigned to that business location
- Events automatically appear on business single page (code already exists!)

**Testing:**
1. Apply code change
2. Edit any Business → click Update
3. Go to Events Manager → Locations → verify business appears as location
4. Create test event → assign to business location
5. Visit business page → verify event shows in "Upcoming Events" section

---

### Step 2: Labels - Rename Venue CPT ✅ CAN DO NOW
**Estimated time:** 10 minutes

**Files to modify:**
- `functions.php` - Lines 422-445 (venue labels)

**Change:**
- "Venues" → "Community Venues" throughout

**Impact:**
- WordPress admin menu shows "Community Venues" instead of "Venues"
- No functional changes, just clarity

---

### Step 3: Design - Send to Lovable 🎨 DO THIS NEXT
**Estimated time:** Lovable design time

**What to send to Lovable:**
"Design a page called 'Our Community' that displays both Community Groups and Community Venues in a unified single-column grid. No tabs or sections - just one flowing grid of cards.

**Community Groups data:**
- Title, description, featured image
- Contact person name/email/phone
- Meeting time & location
- Website & Facebook links
- Badge: 'Community Group'

**Community Venues data:**
- Title, description, featured image
- Address, phone, email
- Capacity, facilities list
- Upcoming events count
- Badge: 'Community Venue'

Card design should have:
- Large featured image at top
- Badge in top-right corner identifying type
- Title, short description
- Icon-based contact info grid
- CTA button ('Learn More' or 'Visit Website')

Style: Match existing Vibrant Devon theme (teal/orange/cream colors)"

**Lovable will provide:**
- React component code for the page
- We'll translate it to `page-our-community.php` WordPress template

---

### Step 4: Template - Create WordPress Template 🔧 AFTER LOVABLE
**Estimated time:** 1-2 hours

**Files to create:**
- `page-our-community.php` (based on Lovable design)

**Process:**
1. Take Lovable's React code
2. Translate to PHP WordPress template
3. Query both `northam_venue` and `northam_group` CPTs
4. Use Lovable's card design but with WordPress data
5. Test responsive design

---

### Step 5: WordPress Admin - Configure Pages & Menu ⚙️ FINAL STEP
**Estimated time:** 15 minutes

1. **Rename existing page title** (optional)
   - Events Calendar page → "What's On?"

2. **Create new page**
   - Title: "Our Community"
   - Template: "Our Community" (page-our-community.php)
   - Publish

3. **Update Primary Navigation menu**
   - Home
   - Who's Here → `/business/`
   - Explore Northam → `/things-to-do/`
   - What's On? → `/events-calendar/`
   - Our Community → `/our-community/` (new)

4. **Test all links**

---

## Database/Content Migration - MINIMAL

### No data loss - This is 100% additive ✅

**What changes:**
- Businesses get EM Location IDs (when resaved)
- Venue labels in admin change to "Community Venues"

**What stays the same:**
- All existing content unchanged
- All existing URLs unchanged
- All existing meta data intact
- Community Groups CPT unchanged

**Migration steps:**
1. Apply Phase 1 code (business sync)
2. Apply Phase 2 code (venue labels)
3. **Bulk update businesses:** Go to Businesses admin → Select all → Edit → Update (triggers sync)
4. Verify EM Locations created
5. Get Lovable design
6. Create template
7. Create page & update menu

---

## Technical Details - SIMPLIFIED

### Business-to-Events Manager Sync
**How it works for Venues (existing, lines 767-810):**
1. User saves a Venue post
2. Hook `save_post_northam_venue` fires
3. Function `northam_sync_venue_to_em()` runs
4. Creates/updates EM_Location object
5. Stores location ID in `_northam_em_location_id` meta field
6. Admin can now select this location when creating events

**How it will work for Businesses (new):**
- **Exact same process**, different hook: `save_post_northam_business`
- Same meta key: `_northam_em_location_id`
- Same EM_Location creation logic
- Copy-paste code, change "venue" to "business"

### Event Categories - USER MANAGED ✅
**No code needed!**

- User manually assigns events to "Community Events" category in Events Manager admin
- Can use WordPress **Tags** for additional filtering
- No auto-assignment logic required
- Simple and flexible

### Community Venues Identification
**Use WordPress Tags - No code needed ✅**

Option: Add tag "Community Venue" to venues like Village Hall
- User assigns tag in Venue admin
- Filter by tag on "Our Community" page template
- No meta fields or checkboxes needed

---

## Testing Plan - SIMPLIFIED

### Test 1: Business Events Sync ✅
1. Apply business sync code to functions.php
2. Create or edit a Business (e.g., "Kingsley Pub")
3. Click Update/Publish
4. Go to Events Manager → Locations
5. ✓ Verify "Kingsley Pub" appears as location
6. Create Event "Friday Fry-up" → Assign to "Kingsley Pub" location
7. Visit Kingsley Pub business page
8. ✓ Verify "Friday Fry-up" appears in Upcoming Events section

### Test 2: Venue Label Changes ✅
1. Apply venue label changes
2. Go to WordPress Admin
3. ✓ Verify menu shows "Community Venues" instead of "Venues"
4. Edit a venue
5. ✓ Verify all labels say "Community Venue"

### Test 3: Our Community Page 🎨
1. After creating template from Lovable design
2. Create "Our Community" page, assign template
3. Visit page
4. ✓ Verify both Community Groups and Community Venues display
5. ✓ Verify badges show correct type
6. ✓ Verify data displays correctly (contact info, etc.)
7. ✓ Test responsive design on mobile

### Test 4: Navigation ✅
1. Update menu to new structure
2. ✓ Verify all 5 menu items work
3. ✓ Test on desktop and mobile
4. ✓ Verify active states work
5. ✓ Verify icons display correctly

---

## Risk Assessment - MINIMAL RISK

### ZERO RISK: ✅
- Business sync code is copy-paste from existing venue code (proven working)
- Label changes are cosmetic only
- New page template is additive (doesn't affect existing pages)
- No data deletion or modification
- All existing URLs unchanged

### Rollback Plan:
- Remove sync function from functions.php → businesses lose EM locations but no data lost
- Revert venue label changes → back to "Venues"
- Delete "Our Community" page → back to separate archives
- Update menu → back to previous structure

**Total rollback time: 5 minutes**

---

## Timeline Estimate - REALISTIC

### Step 1: Business Sync Code
**30 minutes** (including testing)
- Copy venue sync function
- Change "venue" to "business"
- Test with one business
- Bulk update existing businesses

### Step 2: Venue Labels
**10 minutes**
- Find/replace "Venue" with "Community Venue" in CPT registration
- Test admin view

### Step 3: Lovable Design
**Unknown** (depends on Lovable)
- User submits design request to Lovable
- Lovable designs "Our Community" page

### Step 4: Create WordPress Template
**1-2 hours** (after receiving Lovable design)
- Translate React to PHP
- Set up dual CPT query
- Apply Lovable's card design
- Test responsive layout

### Step 5: WordPress Admin Setup
**15 minutes**
- Create page
- Update menu
- Test navigation

**TOTAL CODING TIME: 2.5-3 hours**
**+ Lovable design time (external)**

---

## Questions ANSWERED ✅

### 1. Event Category Naming
**✅ RESOLVED:** User manually assigns "Community Events" category when creating events. No auto-naming needed.

### 2. Community Venues Identification
**✅ RESOLVED:** Use WordPress Tags. Tag venues like "Village Hall" with "Community Venue" tag.

### 3. Page URLs
**✅ RESOLVED:**
- "What's On?" → Keep existing `/events-calendar/` URL
- "Our Community" → New page at `/our-community/`

### 4. Friday Fry-up Example
**✅ RESOLVED:** Kingsley Pub = Business. Gets EM location via sync. Events display on business page.

### 5. Existing Data
**✅ NO IMPACT:** All existing data preserved. Just adds EM location IDs to businesses when they're updated.

---

## Summary - FINAL SIMPLIFIED PLAN

This plan restructures the site into a clearer 4-section navigation while maintaining all existing functionality.

### The 3 Changes:

#### 1. ✅ Businesses Can Host Events (NEW CAPABILITY)
**What:** Copy-paste existing venue sync code to work for businesses
**Why:** So pubs/restaurants can show their events (Friday Fry-up example)
**Impact:** Business pages gain "Upcoming Events" section (code already exists!)
**Code:** ~40 lines in functions.php
**Time:** 30 minutes

#### 2. 🏷️ Rename "Venues" to "Community Venues" (CLARITY)
**What:** Change CPT labels in admin
**Why:** Clearer terminology - these are Village Hall, not music venues
**Impact:** WordPress admin shows "Community Venues" instead of "Venues"
**Code:** ~5 label changes in functions.php
**Time:** 10 minutes

#### 3. 🎨 Create "Our Community" Page (NEW PAGE - LOVABLE DESIGN)
**What:** Single unified page showing Community Groups + Community Venues
**Why:** Better UX - related content together
**Impact:** New page in navigation, old archives still work
**Code:** New template file (after Lovable designs it)
**Time:** 1-2 hours (after Lovable)

---

### New Navigation Structure:
1. **Home** - Welcome/Overview ✅ (no changes)
2. **Who's Here** - Business directory ✅ (no changes)
3. **Explore Northam** - Things to do/attractions ✅ (no changes)
4. **What's On?** - Events page ✅ (just rename page title)
5. **Our Community** - NEW: Community Groups + Community Venues 🆕

---

### What Does NOT Change:
- ✅ All existing URLs work
- ✅ All existing data intact
- ✅ All CPT structures preserved
- ✅ Event categories managed manually (not auto-assigned)
- ✅ No complex filtering systems
- ✅ No database migrations
- ✅ Existing templates work as-is

---

### Next Steps:

**NOW - Backend Code (can do immediately):**
1. Add business sync function to functions.php
2. Change venue labels to "Community Venues"
3. Test business events display

**AFTER LOVABLE DESIGNS - Frontend Template:**
1. Send design brief to Lovable (provided above in plan)
2. Receive React component from Lovable
3. Translate to WordPress template (`page-our-community.php`)
4. Create page and update menu

**TOTAL DEVELOPER TIME: 2.5-3 hours**
**+ Lovable design time (external)**

---

## Ready to Proceed?

**Phase 1 (Backend)** can be implemented NOW - no dependencies
**Phase 2 (Frontend)** requires Lovable design first

All changes are **100% additive and reversible** - zero risk to existing site.
