# Lovable Design Brief: "Our Community" Page

## Project Context
We're building a community page for Northam Devon website that combines two types of content:
1. **Community Groups** (clubs, societies, organizations)
2. **Community Venues** (Village Hall, community spaces)

## Page Requirements

### URL & Template
- **Page will be accessed at:** `/community-group/` (existing URL, repurposing)
- **React component name:** `Community.tsx` or `OurCommunity.tsx`
- **Design should match:** Vibrant Devon theme (teal primary, orange accent, warm cream background)

---

## Design Specifications

### Layout
- **Single-column unified grid** - NOT tabs or separate sections
- All items (groups + venues) flow together in one cohesive grid
- Responsive: 3 columns desktop, 2 columns tablet, 1 column mobile
- Hero section at top with page title and subtitle

### Hero Section
```
Title: "Our Community"
Subtitle: "Join local groups, discover community spaces, and get involved in village life"
Background: Subtle teal gradient or image with overlay
```

---

## Card Design - Unified Style for Both Types

Each card should display:

### Visual Elements
1. **Featured Image** - Large, top of card, aspect ratio 16:9
2. **Badge** - Top-right corner overlay on image
   - "Community Group" (teal badge) OR "Community Venue" (orange badge)
3. **Card Body** - White background, subtle shadow, rounded corners

### Content Structure

```
┌─────────────────────────────────┐
│                                 │
│     [Featured Image]            │
│              [BADGE]            │
│                                 │
├─────────────────────────────────┤
│  Title (h3)                     │
│  Short description (2-3 lines)  │
│                                 │
│  📍 [Location/Meeting info]     │
│  👤 [Contact info]              │
│  🌐 [Website if available]      │
│                                 │
│  [Learn More Button →]          │
└─────────────────────────────────┘
```

---

## Sample Data Structure

### Community Group Example
```json
{
  "type": "group",
  "title": "Northam Women's Institute",
  "excerpt": "Friendly local group meeting monthly for talks, crafts, and community events. All welcome!",
  "image": "/images/wi-group.jpg",
  "contactPerson": "Jane Smith",
  "contactEmail": "jane@example.com",
  "contactPhone": "01237 123456",
  "meetingTime": "First Tuesday of each month, 7:30 PM",
  "meetingLocation": "Village Hall, Northam",
  "website": "https://northamwi.org.uk",
  "facebook": "https://facebook.com/northamwi"
}
```

### Community Venue Example
```json
{
  "type": "venue",
  "title": "Northam Village Hall",
  "excerpt": "Modern community space available for hire. Perfect for events, meetings, and celebrations.",
  "image": "/images/village-hall.jpg",
  "address": "Church Street, Northam, Devon EX39 1HN",
  "phone": "01237 123789",
  "email": "bookings@northamhall.org",
  "capacity": "120 people",
  "facilities": ["Kitchen", "Stage", "Parking", "Accessible"],
  "upcomingEventsCount": 5,
  "website": "https://northamhall.org"
}
```

---

## Component Behavior

### Display Logic
- Fetch both groups and venues from API
- Merge into single array
- Sort alphabetically by title
- Display in unified grid with appropriate badge

### Card Interactions
- **Hover state:** Subtle lift effect, shadow increases
- **Click:** Navigate to detail page OR external website
- **Contact icons:** Clickable (tel: for phone, mailto: for email)

### Filtering (Optional Nice-to-Have)
- Filter buttons at top: "All" | "Community Groups" | "Community Venues"
- Filters items in place without page reload

---

## Icon Set
Use Lucide React icons:
- 📍 MapPin (location/meeting location)
- 👤 User (contact person)
- 📞 Phone (phone number)
- ✉️ Mail (email)
- 🌐 Globe (website)
- 📅 Calendar (meeting time)
- 👥 Users (capacity)
- 🏢 Building (facilities)

---

## Typography & Colors

### Colors (Match Existing Theme)
```css
Primary (Teal):      hsl(175, 65%, 40%)    /* #2b9b9b */
Accent (Orange):     hsl(15, 80%, 55%)     /* #e86d3f */
Background (Cream):  hsl(50, 30%, 97%)     /* #faf8f3 */
Foreground (Dark):   hsl(200, 30%, 15%)    /* #1a3a4a */
Muted:               hsl(50, 20%, 90%)     /* Light gray-beige */
```

### Typography
- **Headings:** Cormorant Garamond (serif) - elegant, traditional
- **Body:** Source Sans 3 (sans-serif) - clean, readable
- **Card title:** 1.5rem (24px), bold
- **Description:** 1rem (16px), regular
- **Meta info:** 0.875rem (14px), medium weight

---

## Spacing & Layout
- **Container max-width:** 1200px, centered
- **Grid gap:** 2rem (32px)
- **Card padding:** 1.5rem (24px)
- **Card border-radius:** 12px
- **Hero padding:** 4rem vertical, 2rem horizontal

---

## Responsive Breakpoints
```css
Mobile:  < 768px   (1 column)
Tablet:  768-1024px (2 columns)
Desktop: > 1024px  (3 columns)
```

---

## Example Card HTML Structure
```jsx
<div className="community-card">
  <div className="card-image">
    <img src={item.image} alt={item.title} />
    <span className="badge badge-group">Community Group</span>
  </div>
  <div className="card-body">
    <h3>{item.title}</h3>
    <p className="description">{item.excerpt}</p>

    <div className="meta-info">
      <div className="meta-item">
        <MapPin size={16} />
        <span>{item.meetingLocation}</span>
      </div>
      <div className="meta-item">
        <User size={16} />
        <span>{item.contactPerson}</span>
      </div>
      {item.website && (
        <div className="meta-item">
          <Globe size={16} />
          <a href={item.website}>Visit Website</a>
        </div>
      )}
    </div>

    <button className="btn-primary">
      Learn More →
    </button>
  </div>
</div>
```

---

## Accessibility Requirements
- All images have alt text
- Links have clear focus states
- Color contrast meets WCAG AA standards
- Semantic HTML (headings hierarchy, landmarks)
- Keyboard navigable

---

## Sample Content (Use in Design)

### Community Groups (3-4 examples)
1. **Northam Women's Institute** - Monthly meetings, crafts, talks
2. **Northam Cricket Club** - Local cricket team, all ages welcome
3. **St Margaret's Church Community** - Services, events, support groups
4. **Northam History Society** - Exploring local heritage and history

### Community Venues (2-3 examples)
1. **Northam Village Hall** - Main community space, 120 capacity
2. **St Margaret's Church Hall** - Smaller venue, meetings and events
3. **Northam Youth Centre** - Activities and programs for young people

---

## Technical Notes for Translation to WordPress
- React component will be translated to PHP WordPress template
- Data will come from WordPress custom post types (not API calls)
- Keep component structure simple for easy PHP translation
- Avoid complex state management or React-specific patterns

---

## Deliverables Needed from Lovable

1. **React component** (`Community.tsx`) with full functionality
2. **CSS/Tailwind** styling (inline or separate file)
3. **Sample data** hardcoded in component for demonstration
4. **Responsive design** tested on mobile, tablet, desktop
5. **Interactive elements** (hover states, click handlers)

---

## Timeline
Please prioritize clear, maintainable code over complex features. The design should be:
- ✅ Clean and professional
- ✅ Easy to read and scan
- ✅ Consistent with existing site design
- ✅ Simple to translate to WordPress PHP

---

## Reference URLs
- **Existing site style:** Review existing Northam Devon pages for color/typography reference
- **Similar examples:** Community pages with mixed content types in unified grids

---

## Questions?
If any requirements are unclear or you need additional context about:
- Specific data fields
- Interaction behaviors
- Design preferences

Please ask before starting development!

---

**Key Point:** This is a **unified community hub** - not separate sections. Groups and venues should feel like equal members of one community family, distinguished only by their badges and the specific info they display.
