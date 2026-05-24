# Weekly Events Pagination - Implementation Summary

## Feature Added

Added weekly event filtering and pagination to business pages, allowing users to:
- View events for the current week by default
- Navigate to future weeks using "Next Week" button
- Navigate back to previous weeks using "Previous Week" button
- See clear week date ranges in the heading

## Changes Made

### 1. [single-northam_business.php](wordpress-theme/northam-kadence-child/single-northam_business.php:216-320)

**Week Calculation Logic (Lines 218-223):**
```php
$week_offset = isset( $_GET['week'] ) ? absint( $_GET['week'] ) : 0;
$current_time = current_time( 'timestamp' );
$week_start = strtotime( 'monday this week', $current_time ) + ( $week_offset * 7 * 24 * 60 * 60 );
$week_end = strtotime( 'sunday this week', $current_time ) + ( $week_offset * 7 * 24 * 60 * 60 ) + ( 24 * 60 * 60 ) - 1;
```

**Event Filtering (Lines 236-244):**
```php
// Check if event is within the current week
if ( $em_event->start >= $week_start && $em_event->start <= $week_end ) {
    $events[] = $em_event;
}
```

**Dynamic Heading (Lines 257-264):**
- Week 0: "This Week's Events"
- Week 1+: "Events: 23 Feb - 1 Mar"

**Pagination Controls (Lines 266-280):**
- Previous Week button (only shows if week > 0)
- Next Week button (always shows)
- Both link with `#events` anchor for smooth scrolling

### 2. [style.css](wordpress-theme/northam-kadence-child/style.css:2067-2119)

**New CSS Classes:**

**`.northam-events-header`** - Flexbox container for title and pagination
```css
display: flex;
justify-content: space-between;
align-items: center;
```

**`.northam-events-pagination`** - Container for navigation buttons
```css
display: flex;
gap: 0.5rem;
```

**`.northam-events-nav`** - Navigation button styling
```css
background-color: var(--northam-primary);
color: #ffffff;
border-radius: var(--northam-radius);
transition: background-color 0.2s ease, transform 0.2s ease;
```

**Hover Effect:**
```css
.northam-events-nav:hover {
  background-color: hsl(175, 55%, 35%);
  transform: translateY(-1px);
}
```

**Responsive Design (< 768px):**
- Stack header vertically
- Full-width pagination buttons
- Centered button text

## How It Works

### URL Structure

- **This Week:** `/business/golden-lion/`
- **Next Week:** `/business/golden-lion/?week=1#events`
- **Week After:** `/business/golden-lion/?week=2#events`
- **Previous:** `/business/golden-lion/?week=0#events` (redirects to current week)

### Week Calculation

1. Get current timestamp using WordPress `current_time()`
2. Calculate Monday of current week using `strtotime('monday this week')`
3. Add week offset: `$week_start + (week_offset * 7 days)`
4. Calculate Sunday end: `$week_end + 24 hours - 1 second`

### Event Filtering

Events are filtered in three stages:
1. **Business Filter:** Only events with `_northam_event_venue = business_id`
2. **Date Filter:** Only events where `start >= week_start AND start <= week_end`
3. **Future Filter:** Handled by EM_Events::get(scope='future')

### Edge Cases Handled

✅ **Week 0 navigation:** Previous button doesn't show
✅ **Empty weeks:** Section doesn't display if no events
✅ **URL anchor:** `#events` ensures page scrolls to events after pagination
✅ **Negative weeks:** `absint()` prevents negative week values
✅ **Timezone:** Uses WordPress `current_time()` respecting site timezone

## Testing Results

### Current Week (Week 0)
**URL:** `/business/golden-lion/`
- Shows: "This Week's Events"
- Events: "New Event for Lion" (Wed, 18th Feb)
- Navigation: Only "Next Week" button visible

### Next Week (Week 1)
**URL:** `/business/golden-lion/?week=1`
- Shows: "Events: 23 Feb - 1 Mar"
- Events: "Quiz Night - The Golden Lion" (Mon, 23rd Feb)
- Navigation: "Previous Week" and "Next Week" buttons

### Future Weeks (Week 2+)
**URL:** `/business/golden-lion/?week=2`
- Shows: "Events: 2 Mar - 8 Mar"
- Events: Next occurrence of Quiz Night
- Navigation: Both buttons visible

## User Experience

### Desktop
- Title and pagination side-by-side
- Hover effects on buttons with lift animation
- Clear visual feedback

### Mobile
- Stacked layout (title above pagination)
- Full-width buttons for easy tapping
- Centered content

### Accessibility
- Semantic anchor links with `#events` ID
- SVG icons with proper stroke-width
- High contrast button colors
- Keyboard navigation support

## Performance Considerations

**Current Implementation:**
- Fetches ALL future events from EM_Events::get()
- Filters in PHP for current week
- Simple and reliable

**Future Optimization (if needed):**
- Could cache events per business
- Could use direct database query with date range
- Could implement AJAX loading for pagination

For a village directory with ~100 events, current approach is optimal.

## Potential Enhancements

1. **Show event count:** "This Week's Events (3)"
2. **Week picker dropdown:** Quick jump to specific week
3. **"All Events" view:** Show all future events
4. **Month view:** Alternative to weekly view
5. **"Back to This Week" button:** When viewing future weeks

## Files Modified

| File | Lines | Description |
|------|-------|-------------|
| single-northam_business.php | ~100 lines modified | Added week filtering and pagination UI |
| style.css | +53 lines | Added pagination styling and responsive layout |

## Backwards Compatibility

✅ **Existing URLs work:** `/business/golden-lion/` shows this week
✅ **No database changes:** Uses existing event data
✅ **Graceful degradation:** Works without JavaScript
✅ **No breaking changes:** All existing functionality preserved
