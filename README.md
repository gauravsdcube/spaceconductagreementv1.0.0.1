# Space Conduct Agreement Module

A production-ready HumHub module that requires users to accept space-specific codes of conduct before joining spaces, with comprehensive admin management and user tracking.

**Copyright © 2025 D Cube Consulting Ltd. All rights reserved.**

## 🚀 Version: 1.0.0 (Production Ready)

### Overview

This module enhances HumHub's space functionality by adding a mandatory conduct agreement system. Space administrators can create custom codes of conduct that users must accept before joining spaces, ensuring all members understand and agree to community guidelines.

## ✨ Features

### Core Functionality
- **Space-specific conduct agreements**: Each space can have its own unique code of conduct
- **Admin management interface**: Space administrators can create, edit, and manage conduct agreements
- **User acceptance tracking**: System tracks when users accept agreements with timestamps
- **Automatic enforcement**: Users must accept agreements before gaining full space access
- **Modal interface**: Clean, user-friendly interface for agreement management
- **Flash notifications**: Informative messages guide users through the process

### Admin Features
- **Agreement Management**: Create, edit, and activate/deactivate conduct agreements
- **Rich Text Editor**: Use HumHub's rich text editor for formatting agreements
- **Version Tracking**: System tracks agreement versions and user acceptance
- **Admin Dashboard**: Centralized view of agreement status and user acceptance

### User Experience
- **Seamless Integration**: Agreements appear automatically when joining spaces
- **Clear Interface**: User-friendly modal dialogs for agreement acceptance
- **Status Tracking**: Users can see their agreement acceptance status
- **Automatic Redirects**: Users are guided through the acceptance process

## 📋 Requirements

- **HumHub Version**: 1.17.3 or higher
- **PHP Version**: 8.1 or higher
- **Database**: MySQL 5.7+ or MariaDB 10.2+
- **Permissions**: Admin access to install and configure the module

## 🛠️ Installation

### Method 1: Manual Installation (Recommended)

1. **Download the Module**
   ```bash
   # Navigate to your HumHub installation
   cd /path/to/humhub
   
   # Create the modules directory if it doesn't exist
   mkdir -p protected/modules
   
   # Copy the module files
   cp -r space-conduct-agreement protected/modules/
   ```

2. **Set Proper Permissions**
   ```bash
   # Set correct ownership and permissions
   chown -R www-data:www-data protected/modules/space-conduct-agreement
   chmod -R 755 protected/modules/space-conduct-agreement
   ```

3. **Enable the Module**
   ```bash
   # Via CLI (if available)
   php protected/yii module/enable space-conduct-agreement
   
   # Or via web interface
   # Go to Administration > Modules > Space Conduct Agreement > Enable
   ```

4. **Run Database Migrations**
   ```bash
   php protected/yii migrate/up --migrationPath=@spaceconductagreement/migrations
   ```

### Method 2: Web Interface Installation

1. **Upload Module Files**
   - Upload the `space-conduct-agreement` folder to `protected/modules/`
   - Ensure proper file permissions (755 for directories, 644 for files)

2. **Enable via Admin Panel**
   - Log in as administrator
   - Go to **Administration** → **Modules**
   - Find **Space Conduct Agreement** and click **Enable**

3. **Apply Core Template Modification** (See Critical Section Below)

## ⚠️ **CRITICAL: Core HumHub Template Modification**

This module requires a modification to the HumHub core template file to display the admin button in the space header.

### File to Modify
**Path:** `/protected/humhub/modules/space/widgets/views/profileHeaderControls.php`

### Required Changes

1. **Add the following code block** between `HeaderControls::widget()` and `HeaderControlsMenu::widget()`:

```php
<?php if (!Yii::$app->user->isGuest && $container->isAdmin()): ?>
    <?= Html::a(
        '<i class="fa fa-file-text-o"></i> ' . Yii::t('SpaceConductAgreementModule.base', 'Code of Conduct'),
        Url::to(['/space-conduct-agreement/admin/index', 'spaceId' => $container->id]),
        [
            'class' => 'btn btn-default',
            'data-target' => '#globalModal',
            'style' => 'margin-left:5px;'
        ]
    ) ?>
<?php endif; ?>
```

2. **Ensure these imports are present** at the top of the file:

```php
use yii\helpers\Url;
use yii\helpers\Html;
```

### Complete Modified File Example

```php
<?php

use humhub\modules\space\widgets\HeaderControls;
use humhub\modules\space\widgets\HeaderControlsMenu;
use yii\helpers\Url;
use yii\helpers\Html;

/* @var $this \humhub\modules\ui\view\components\View */
/* @var $container \humhub\modules\content\components\ContentContainerActiveRecord */

?>

<div class="controls">
    <?= HeaderControls::widget() ?>
    
    <?php if (!Yii::$app->user->isGuest && $container->isAdmin()): ?>
        <?= Html::a(
            '<i class="fa fa-file-text-o"></i> ' . Yii::t('SpaceConductAgreementModule.base', 'Code of Conduct'),
            Url::to(['/space-conduct-agreement/admin/index', 'spaceId' => $container->id]),
            [
                'class' => 'btn btn-default',
                'data-target' => '#globalModal',
                'style' => 'margin-left:5px;'
            ]
        ) ?>
    <?php endif; ?>
    
    <?= HeaderControlsMenu::widget() ?>
</div>
```

### Backup Recommendation
```bash
cp protected/humhub/modules/space/widgets/views/profileHeaderControls.php protected/humhub/modules/space/widgets/views/profileHeaderControls.php.backup
```

## ⚙️ Configuration

### Module Settings
- No global configuration required
- All settings are managed per-space through the space admin interface

### Database Tables
The module creates the following tables:
- `space_agreement`: Stores conduct agreements for each space
- `user_agreement`: Tracks user acceptance of agreements with timestamps

## 📖 Usage

### For Space Administrators

1. **Create a Conduct Agreement**
   - Visit a space where you are an administrator
   - Click the "Code of Conduct" button in the space header
   - Fill in the title and content of your conduct agreement
   - Use the rich text editor for formatting
   - Check "Require acceptance of this agreement" to enable it
   - Click "Save Agreement"

2. **Manage Existing Agreements**
   - Click the "Code of Conduct" button to edit existing agreements
   - Modify the content as needed
   - Existing members will need to re-accept if you change the content
   - View user acceptance statistics

### For Users

1. **Joining a Space with Conduct Agreement**
   - When joining a space that requires a conduct agreement
   - You'll be automatically redirected to the agreement page
   - Read the agreement and click "I Accept" to join the space
   - Or click "I Decline" to cancel the join request

2. **Viewing Agreement Status**
   - Check your agreement acceptance status in the space
   - Re-accept agreements if they have been updated

## 🔧 Core HumHub Changes

### Modified Files

#### 1. `protected/humhub/modules/space/widgets/views/profileHeaderControls.php`
**Purpose**: Add the Code of Conduct admin button to space headers

**Changes Made**:
- Added conditional button for space administrators
- Integrated with modal system for admin interface
- Maintained existing header controls layout

**Backup Recommendation**: 
```bash
cp protected/humhub/modules/space/widgets/views/profileHeaderControls.php protected/humhub/modules/space/widgets/views/profileHeaderControls.php.backup
```

### Database Changes

The module creates the following database tables:

- `space_agreement`: Stores conduct agreements for each space
- `user_agreement`: Stores user acceptance records with timestamps

## 🔍 Troubleshooting

### Common Issues

#### 1. Code of Conduct Button Not Appearing
**Symptoms**: Admin button not visible in space header
**Solutions**:
- Verify the template modification was applied correctly
- Check that you are logged in and have admin rights to the space
- Clear HumHub cache: `php protected/yii cache/flush-all`
- Check file permissions on the modified template file

#### 2. Modal Appearing Behind Navigation
**Symptoms**: Admin modal appears behind other elements
**Solutions**:
- The module includes z-index fixes in the admin modal view
- If issues persist, check for conflicting CSS in your theme
- Clear browser cache and reload page

#### 3. Database Migration Errors
**Symptoms**: Error during module installation
**Solutions**:
- Check database permissions
- Verify MySQL/MariaDB version compatibility
- Run migrations manually: `php protected/yii migrate/up --migrationPath=@spaceconductagreement/migrations`

#### 4. Agreement Not Enforcing
**Symptoms**: Users can join spaces without accepting agreements
**Solutions**:
- Verify the agreement is marked as "active" in admin interface
- Check that the agreement content is not empty
- Clear HumHub cache and test again

### Debug Mode

Enable debug mode to see detailed error messages:

```php
// In protected/config/common.php
'components' => [
    'log' => [
        'traceLevel' => YII_DEBUG ? 3 : 0,
        'targets' => [
            [
                'class' => 'yii\log\FileTarget',
                'levels' => ['error', 'warning', 'info'],
                'logVars' => [],
            ],
        ],
    ],
],
```

## 🔄 Upgrading

### From Previous Versions

1. **Backup Current Installation**
   ```bash
   # Backup database
   mysqldump -u username -p database_name > backup_$(date +%Y%m%d).sql
   
   # Backup files
   tar -czf humhub_backup_$(date +%Y%m%d).tar.gz .
   ```

2. **Update Module Files**
   ```bash
   # Replace module directory
   rm -rf protected/modules/space-conduct-agreement
   cp -r new_space-conduct-agreement protected/modules/
   ```

3. **Run Migrations**
   ```bash
   php protected/yii migrate/up --migrationPath=@spaceconductagreement/migrations
   ```

4. **Clear Cache**
   ```bash
   php protected/yii cache/flush-all
   ```

## 🧪 Testing

### Manual Testing Checklist

- [ ] Module installs without errors
- [ ] Code of Conduct button appears for space admins
- [ ] Admin can create and edit conduct agreements
- [ ] Rich text editor works for agreement content
- [ ] Agreement activation/deactivation works
- [ ] Users are prompted to accept agreements when joining
- [ ] Agreement acceptance is tracked in database
- [ ] Users can decline agreements and cancel join request
- [ ] Flash messages appear correctly
- [ ] Modal interface works properly
- [ ] Agreement status is displayed correctly

### Automated Testing

Run the module's test suite:
```bash
php protected/vendor/bin/codecept run --config protected/tests/codeception.yml unit SpaceConductAgreement
```

## 📝 Changelog

### Version 1.0.0 (Production Ready)
- ✅ **Added**: Space-specific conduct agreement system
- ✅ **Added**: Admin management interface with rich text editor
- ✅ **Added**: User acceptance tracking with timestamps
- ✅ **Added**: Automatic enforcement for new space members
- ✅ **Added**: Modal interface for agreement management
- ✅ **Added**: Flash notifications and user guidance
- ✅ **Added**: Comprehensive documentation
- ✅ **Tested**: PHP 8.1+ compatibility

## 🤝 Support

### Getting Help

1. **Check Documentation**: Review this README and inline code comments
2. **Review Logs**: Check `protected/runtime/logs/` for error messages
3. **Community Support**: Post issues on HumHub community forums
4. **Debug Mode**: Enable debug mode for detailed error information

### Reporting Issues

When reporting issues, please include:
- HumHub version
- PHP version
- Module version
- Error messages from logs
- Steps to reproduce the issue
- Screenshots if applicable
- Template modification status

## 📄 License

This module is released under a proprietary license.

**Copyright © 2025 D Cube Consulting Ltd. All rights reserved.**

## 🙏 Acknowledgments

- HumHub development team for the excellent framework
- Community contributors for testing and feedback
- All users who provided feedback during development

---

**Last Updated**: July 2025  
**Compatible with**: HumHub 1.17.3+  
**PHP Version**: 8.1+  
**Status**: Production Ready ✅ 