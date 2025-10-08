# Changelog

**Author:** D Cube Consulting (info@dcubeconsulting.co.uk)

## [1.0.2] - 2025-01-XX
### Fixed
- **Content Copying Issue**: Fixed critical bug where rich text content was being copied across different spaces
- **RichTextField Widget**: Resolved sessionStorage backup mechanism causing cross-space content contamination
- **Form Submission**: Fixed save button functionality in admin interface
- **Space Isolation**: Ensured each space maintains its own distinct code of conduct

### Enhanced
- **User Interface**: Converted modal interface to full-page layout for better user experience
- **Form Structure**: Improved form nesting and HTML structure for better reliability
- **Code Quality**: Removed debugging code and cleaned up redundant comments
- **Performance**: Optimized form submission and data handling

### Technical
- **Unique Widget IDs**: Added space-specific IDs to prevent RichTextField backup conflicts
- **Backup Mechanism**: Disabled automatic content backup to prevent cross-space contamination
- **JavaScript Cleanup**: Added sessionStorage cleanup to prevent data persistence issues
- **Form Validation**: Enhanced form validation and error handling

## [1.0.1] - 2025-01-XX
### Added
- Rich text editor for agreement content creation and editing
- Enhanced formatting capabilities for conduct agreements
- Improved user experience with professional text editing tools
- Better content display with rich text rendering

### Enhanced
- Admin interface now uses HumHub's RichTextField widget
- Agreement display now properly renders rich text content
- Enhanced CSS styling for rich text content display
- Improved typography and formatting options

### Technical
- Updated admin form to use RichTextField widget
- Modified agreement display to use RichText::output()
- Added proper rich text styling and formatting
- Maintained backward compatibility with existing plain text content

## [1.0.0] - 2025-01-XX
### Added
- Initial stable release of Space Conduct Agreement module
- Require users to accept space-specific codes of conduct before joining spaces
- Per-space configuration for agreement text
- Integration with space membership workflow
- Agreement acceptance tracking and validation
- Cleanup of agreement records when users leave spaces
- Event handling for member addition and removal

### Features
- Space admins can configure custom conduct agreements
- Users must accept agreements before joining spaces
- Proper tracking of agreement acceptance
- Automatic cleanup when users leave spaces
- Integration with existing space membership system

### Technical
- Built for HumHub 1.15.0+
- Follows HumHub module development standards
- Proper event handling and integration
- Clean separation of concerns 