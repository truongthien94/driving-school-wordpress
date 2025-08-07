# SBS Portal CSS Structure

## Overview
CSS files have been separated to avoid conflicts between different page types and improve maintainability.

## File Structure

### `main.css`
- **Purpose**: Core styles for the main portal page and general theme elements
- **Contains**: 
  - Reset and base styles
  - Portal page specific styles (hero, services, FAQ, footer)
  - Float buttons and popup styles
  - Responsive design for main portal
  - General theme utilities

### `blog-list.css`
- **Purpose**: Dedicated styles for blog list page only
- **Contains**:
  - Blog list container and layout
  - Banner carousel section (copied from main.css)
  - Header section with Figma-accurate design
  - Blog cards and pagination
  - Blog-specific floating elements
  - Breadcrumbs and navigation
  - Responsive design for blog list

## CSS Class Naming Convention

### Blog List Specific Classes
To avoid conflicts with main CSS, blog list uses prefixed classes:

- `.blog-list-*` - Main container and layout
- `.blog-header-*` - Header section elements
- `.blog-card-large-*` - Blog card components
- `.blog-list-float-*` - Floating elements
- `.blog-list-back-to-top` - Back to top button
- `.blog-breadcrumbs` - Breadcrumb navigation

### Main Portal Classes
- `.sbs-portal` - Main portal container
- `.sbs-hero-section` - Hero section
- `.portal-box` - Service boxes
- `.float-button` - General float buttons
- `.back-to-top` - General back to top

## Import Strategy

### Conditional Loading
CSS files are loaded conditionally based on page type:

```php
// In functions.php
if (is_page_template('page-blog.php') || 
    (get_query_var('sbs_page') === 'blog-list') || 
    is_post_type_archive('blog') ||
    (is_page() && get_page_template_slug() === 'page-blog.php')) {
    wp_enqueue_style('sbs-blog-list-style', 
        get_stylesheet_directory_uri() . '/assets/css/blog-list.css', 
        array('sbs-style'), '1.0.0');
}
```

### Dependencies
- `blog-list.css` depends on `main.css` for base styles
- Both files are loaded on blog list pages
- Only `main.css` is loaded on portal pages

## Benefits

1. **No CSS Conflicts**: Blog list styles are isolated
2. **Better Performance**: Only load necessary CSS per page
3. **Easier Maintenance**: Separate concerns and files
4. **Figma Accuracy**: Blog list styles match design exactly
5. **Responsive Design**: Each file handles its own responsive needs

## Development Guidelines

### Adding New Styles
- **Portal pages**: Add to `main.css`
- **Blog list page**: Add to `blog-list.css`
- **Shared utilities**: Add to `main.css`

### Class Naming
- Use descriptive, prefixed class names
- Avoid generic names that might conflict
- Follow BEM methodology when possible

### Responsive Design
- Each file handles its own media queries
- Use consistent breakpoints across files
- Test on both page types when making changes

## File Locations
```
wp-content/themes/sbs-portal/assets/css/
├── main.css          # Main portal styles
├── blog-list.css     # Blog list specific styles
└── README.md         # This documentation
``` 