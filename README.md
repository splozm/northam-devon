# Northam Devon WordPress Theme - Project Guide

## Project Overview

This is a Kadence child theme for the Northam Devon community website. It features a coastal-inspired design with custom post types for businesses, venues, attractions, and community groups.

**Theme Location:** `/wordpress-theme/northam-kadence-child/`

## Development Setup

### Local Development with Symlinks

The WordPress theme is developed using **symlinks** to avoid duplicating files. The actual theme files live in this repository, and we create symbolic links in the WordPress installation.

**Important:** When working with this theme, you must:
1. Edit files in `/wordpress-theme/northam-kadence-child/` (this repo)
2. The symlink at your WordPress installation will reflect changes immediately
3. Never edit files directly in your `wp-content/themes/` directory

### Creating the Symlink

```bash
# From your WordPress wp-content/themes directory:
ln -s /path/to/repo/wordpress-theme/northam-kadence-child northam-kadence-child

# Example:
ln -s /Users/alexbutt/Desktop/Cursor-projects/NorthamDevon/northam-devon/wordpress-theme/northam-kadence-child northam-kadence-child
```

### Verifying the Symlink

```bash
# In wp-content/themes/, run:
ls -la

# You should see something like:
# northam-kadence-child -> /Users/alexbutt/Desktop/.../northam-kadence-child
```

## Theme Structure

```
wordpress-theme/northam-kadence-child/
├── functions.php           # Main theme functions, CPT registration
├── style.css              # All CSS (design system + components)
├── inc/
│   ├── meta-boxes.php     # Custom meta boxes for all CPTs
│   ├── taxonomies.php     # Custom taxonomies
│   ├── block-patterns.php # Block patterns for page builder
│   └── sample-data.php    # Sample data generator
├── assets/
│   ├── css/
│   │   └── admin.css      # WordPress admin styling
│   └── js/
│       └── header.js      # Header interactions
├── single-northam_business.php    # Business single page template
├── archive-northam_business.php   # Business archive template
└── (other templates...)
```

## Key Files

### 1. `functions.php`
- Custom post type registration (businesses, venues, attractions, groups, directory)
- Theme setup and enqueues
- Helper functions for getting meta data
- Custom logo implementation with SVG icon
- Navigation menu icon integration

### 2. `style.css`
Contains the entire design system:
- CSS custom properties (colors, typography, spacing)
- Component styles (cards, buttons, badges)
- Custom header styling (white background, dark text, teal accents)
- Responsive layouts
- Photo gallery mosaic styles
- Archive and single page layouts

### 3. `inc/meta-boxes.php`
- Meta boxes for all custom post types
- **Gallery meta box** with WordPress media uploader (max 10 images)
- Contact information fields
- Opening hours fields
- Social media fields
- Save functions for all meta data

### 4. Single Page Templates
- `single-northam_business.php` - Business detail pages
- Hero section with featured image or first gallery image
- Contact sidebar with hours, phone, email, address
- Photo gallery section (mosaic style)
- Events section
- Instagram feed integration

## Custom Post Types

### 1. Businesses (`northam_business`)
- URL: `/business/{slug}/`
- Archive: `/business/`
- Taxonomies: Business Categories
- Features: Full contact info, gallery, events, Instagram
- Meta fields: address, phone, email, website, social media, hours, location

### 2. Venues (`northam_venue`)
- URL: `/venue/{slug}/`
- Archive: `/venue/`
- Features: Capacity, facilities, contact info, gallery
- Meta fields: address, phone, email, website, facilities, capacity, location

### 3. Attractions (`northam_attraction`)
- URL: `/things-to-do/{slug}/`
- Archive: `/things-to-do/`
- Features: Highlights, website, gallery
- Meta fields: website, highlights, location, gallery

### 4. Community Groups (`northam_group`)
- URL: `/community-group/{slug}/`
- Archive: `/community-group/`
- Features: Meeting details, contact person, gallery
- Meta fields: contact name/email/phone, meeting time/location, website, social media

### 5. Directory Listings (`northam_directory`)
- Archive only: `/directory/`
- No single pages
- Simple contact information display
- Meta fields: address, phone, opening hours

## Photo Gallery System

### How It Works

1. **Gallery Meta Box**: Uses WordPress media uploader with array of hidden fields
   - Located in `inc/meta-boxes.php`
   - Function: `northam_gallery_meta_box_callback()`
   - Max 10 images per post
   - Stores as comma-separated attachment IDs in `_northam_gallery` meta field

2. **Gallery Display**: Mosaic-style layout
   - Desktop: 4-column artistic mosaic with varied sizes
   - Mobile: 2-column stacked mosaic with smaller images
   - CSS classes: `.northam-gallery-section`, `.northam-gallery-grid`, `.northam-gallery-item`

3. **Hero Image Logic**:
   - If featured image exists → use featured image as hero
   - If no featured image → use first gallery image as hero
   - Gallery display excludes the hero image (no duplication)

### Gallery Helper Function

```php
northam_get_gallery( $post_id )
```
Returns array of images with URL and alt text.

## Design System

### Color Palette (Vibrant Devon Theme)

```css
--northam-primary: hsl(175, 65%, 40%)         /* Teal */
--northam-accent: hsl(15, 80%, 55%)           /* Orange */
--northam-background: hsl(50, 30%, 97%)       /* Warm cream */
--northam-foreground: hsl(200, 30%, 15%)      /* Dark blue-gray */
```

### Typography

- **Headings**: Cormorant Garamond (serif)
- **Body**: Source Sans 3 (sans-serif)
- Fluid typography using `clamp()`

### Header Design

- **Background**: White with backdrop blur and subtle border
- **Navigation**: Dark text with teal hover/active states
- **Logo**: Custom teal circle with MapPin icon + "Northam Devon" text
- **Menu Icons**: Each nav item has an icon (Calendar, Store, Compass, Users, History)

## Common Tasks

### Adding a New Business

1. Go to WordPress Admin → Businesses → Add New
2. Enter title, description, category
3. Add featured image (for hero)
4. Add gallery images (max 10)
5. Fill in contact information meta box
6. Fill in opening hours
7. Add Instagram handle (optional)
8. Publish

### Adding Gallery Images

1. In the post editor, scroll to "Gallery" meta box
2. Click "Add Images" button
3. Select up to 10 images from media library
4. Images can be reordered by dragging
5. Click X button to remove an image
6. Click "Update" to save

### Modifying Gallery Layout

Gallery styles are in `style.css` starting at line ~1994:
- `.northam-gallery-grid` - Grid container
- `.northam-gallery-item` - Individual image wrapper
- Mobile breakpoint: `@media (min-width: 768px)`
- Desktop mosaic pattern uses `nth-child()` selectors

## Important Conventions

### File Editing

- ✅ **DO**: Edit files in `/wordpress-theme/northam-kadence-child/`
- ❌ **DON'T**: Edit files in `wp-content/themes/northam-kadence-child/`
- The latter is a symlink and changes may not persist

### Meta Field Naming

All meta fields use underscore prefix to hide from custom fields UI:
- `_northam_address`
- `_northam_phone`
- `_northam_email`
- `_northam_gallery`
- etc.

### CSS Class Naming

All custom classes use `northam-` prefix:
- `.northam-card`
- `.northam-btn`
- `.northam-gallery-grid`
- `.northam-business-hero`

### Nonce Security

Every meta box has its own nonce field:
- `northam_business_nonce` / `northam_business_meta`
- `northam_gallery_nonce` / `northam_gallery_save`
- `northam_venue_nonce` / `northam_venue_meta`
- etc.

## Debugging

### WordPress Debug Mode

Add to `wp-config.php`:
```php
define('WP_DEBUG', true);
define('WP_DEBUG_LOG', true);
define('WP_DEBUG_DISPLAY', false);
```

Debug log location: `wp-content/debug.log`

### Common Issues

**Gallery images not saving:**
- Check browser console for JavaScript errors
- Verify nonce is being passed correctly
- Check `$_POST['northam_gallery_ids']` is an array
- Ensure save function is hooked to correct post type

**Symlink not working:**
- Verify symlink path is absolute, not relative
- Check file permissions
- Restart local server if needed

**Styles not updating:**
- Clear browser cache (hard refresh: Cmd+Shift+R)
- Check if Kadence is caching CSS
- Verify you're editing the correct file (repo, not symlink destination)

## Helper Functions

### Get Gallery Images
```php
$images = northam_get_gallery( $post_id );
// Returns: [ ['url' => '...', 'alt' => '...'], ... ]
```

### Get Contact Information
```php
$phone = get_post_meta( $post_id, '_northam_phone', true );
$email = get_post_meta( $post_id, '_northam_email', true );
$address = get_post_meta( $post_id, '_northam_address', true );
```

### Get Opening Hours
```php
$hours_json = get_post_meta( $post_id, '_northam_opening_hours', true );
$hours_array = json_decode( $hours_json, true );
```

### Get Social Media
```php
$socials = northam_get_social_links( $post_id );
// Returns: ['website' => '...', 'facebook' => '...', 'instagram' => '...', 'twitter' => '...']
```

## Deployment

When deploying to production:

1. **DO NOT** deploy the symlink setup
2. Copy the actual theme files from `/wordpress-theme/northam-kadence-child/` to production
3. Upload to `wp-content/themes/northam-kadence-child/`
4. Activate the theme in WordPress admin
5. Ensure parent theme (Kadence) is also installed

## Additional Notes

- Theme is a **Kadence child theme** (requires Kadence parent theme)
- Uses **The Events Calendar** plugin for events
- Custom post types are set to `'show_in_rest' => false` (Classic Editor only)
- All custom content is self-contained in the child theme
- No database migrations needed - everything is registered on theme activation

## Working with AI Assistants

### Context Preservation

This project includes a comprehensive **PROJECT-GUIDE.md** file (this document) that provides context for AI assistants across different chat sessions. When starting a new chat or conversation:

1. **Always reference this guide first** - It contains all the key patterns, conventions, and setup details
2. **The guide is self-updating** - Major changes should be documented here for future reference
3. **Location**: `/PROJECT-GUIDE.md` in the repository root

### What to Include in New Chat Sessions

When opening a new AI chat context, provide:

```
Please read PROJECT-GUIDE.md for full context on this WordPress theme project.

Key points:
- This is a Kadence child theme with symlink setup
- Theme files are in /wordpress-theme/northam-kadence-child/
- We use a custom gallery system (max 10 images, mosaic layout)
- All classes use 'northam-' prefix
- Meta fields use '_northam_' prefix with underscores
```

### Important Patterns to Communicate

When asking AI for help, mention these established patterns:

- **Symlink workflow**: Always edit files in the repo, not in `wp-content/themes/`
- **Gallery system**: Array of hidden fields (`northam_gallery_ids[]`), not single field
- **Hero images**: Featured image first, then fallback to first gallery image
- **CSS organization**: Everything in `style.css` with clear section headers
- **Nonce fields**: Every meta box has its own nonce for security

## Support & References

- [Kadence Theme Documentation](https://www.kadencewp.com/documentation/)
- [WordPress Child Themes](https://developer.wordpress.org/themes/advanced-topics/child-themes/)
- [Custom Post Types](https://developer.wordpress.org/plugins/post-types/)
- [WordPress Meta Boxes](https://developer.wordpress.org/plugins/metadata/custom-meta-boxes/)

---

**Last Updated:** 2026-01-22

For questions or issues, refer to the commit history or check the inline documentation in each file.

**Note:** This guide should be referenced at the start of any new AI chat session to maintain context and consistency across the project.
