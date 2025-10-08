# Space Conduct Agreement Module - Installation Instructions

## Overview
This module requires users to accept space-specific codes of conduct before joining spaces. It includes a navigation menu item for administrators to manage conduct agreements without requiring core modifications.

## Installation Steps

### 1. Module Installation
1. Upload the module files to `/protected/modules/space-conduct-agreement/`
2. Enable the module through HumHub admin panel
3. Run database migrations: `php protected/yii migrate/up`

### 2. No Core Modifications Required ✅

**This module has been updated to use the standard HumHub navigation menu system. No core template modifications are required.**

The module now adds a "Code of Conduct" menu item to the space navigation (left sidebar) for space administrators, following the same pattern as other HumHub modules.

### 3. Verification
After installation:
1. Visit a space where you are an administrator
2. Look for the "Code of Conduct" menu item in the left navigation sidebar
3. Click the menu item to access the conduct agreement management interface
4. Test creating and managing conduct agreements

## Features

### Navigation Menu Integration
- **Location**: Left-hand space navigation sidebar
- **Visibility**: Only visible to space administrators
- **Icon**: File text icon (fa-file-text-o)
- **Active State**: Properly highlights when on admin pages

### URL Routing
- **Admin Interface**: `/space-conduct-agreement/admin/index`
- **Agreement Display**: `/space-conduct-agreement/agreement/show`
- **Agreement Acceptance**: `/space-conduct-agreement/agreement/accept`

### Space Container Integration
- Uses HumHub's standard space container system
- Proper permission checking for space administrators
- Consistent with other modules like spaceJoinQuestions

## Troubleshooting

### Menu Item Not Appearing
- Verify you are logged in and have admin rights to the space
- Check that the module is enabled for the space
- Clear HumHub cache: `php protected/yii cache/flush-all`
- Check module logs: `tail -f protected/runtime/logs/app.log`

### URL Routing Issues
- Ensure URL manager rules are properly configured
- Check that the module extends SpaceController correctly
- Verify space container integration

### Database Issues
- Ensure migrations ran successfully: `php protected/yii migrate/up`
- Check database permissions for the web server user

## Files Modified
- `/protected/modules/space-conduct-agreement/` - Module files only
- **No core files are modified**

## Benefits of New Approach

### ✅ No Core Modifications
- Clean installation without touching HumHub core files
- Easy to maintain across HumHub updates
- No risk of conflicts with other modules

### ✅ Standard Integration
- Uses HumHub's standard navigation menu system
- Consistent with other modules (spaceJoinQuestions, etc.)
- Follows HumHub best practices

### ✅ Easy Maintenance
- Simple to update and extend
- No template modifications to maintain
- Clean separation of concerns

### ✅ Better User Experience
- Intuitive placement in navigation menu
- Consistent with other admin functions
- Proper active state indication

## Support
For issues or questions, please refer to the module documentation or contact the development team.

---
**Note:** This module now uses the standard HumHub navigation system and requires no core modifications. 