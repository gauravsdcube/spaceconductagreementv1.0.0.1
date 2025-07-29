# Space Conduct Agreement Module - Installation Instructions

## Overview
This module requires users to accept space-specific codes of conduct before joining spaces. It includes a button in the space header for administrators to manage conduct agreements.

## Installation Steps

### 1. Module Installation
1. Upload the module files to `/protected/modules/space-conduct-agreement/`
2. Enable the module through HumHub admin panel
3. Run database migrations: `php protected/yii migrate/up`

### 2. Required Template Modification ⚠️ IMPORTANT

**This module requires a modification to the HumHub core template file to display the admin button in the space header.**

#### File to Modify:
`/protected/humhub/modules/space/widgets/views/profileHeaderControls.php`

#### Required Changes:
Add the following code block **between** the `HeaderControls::widget()` and `HeaderControlsMenu::widget()` calls:

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

#### Complete Modified Section:
```php
<div class="controls controls-header pull-right">
    <?= HeaderControls::widget(['widgets' => [
        [InviteButton::class, ['space' => $container], ['sortOrder' => 10]],
        [MembershipButton::class, [
            'space' => $container,
            'options' => [
                'becomeMember' => ['mode' => 'link'],
                'acceptInvite' => ['mode' => 'link']
            ],
        ], ['sortOrder' => 20]],
        [FollowButton::class, [
            'space' => $container,
            'followOptions' => ['class' => 'btn btn-primary'],
            'unfollowOptions' => ['class' => 'btn btn-primary active']
        ], ['sortOrder' => 30]]
    ]]); ?>
    
    <!-- SPACE CONDUCT AGREEMENT BUTTON - ADD THIS SECTION -->
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
    <!-- END SPACE CONDUCT AGREEMENT BUTTON -->
    
    <?= HeaderControlsMenu::widget(['space' => $container]); ?>
</div>
```

#### Required Imports:
Make sure the following imports are present at the top of the file:
```php
use yii\helpers\Url;
use yii\helpers\Html;
```

### 3. Verification
After installation and template modification:
1. Visit a space where you are an administrator
2. Look for the "Code of Conduct" button in the space header (between "Invite" button and "Settings" gear)
3. Click the button to verify the modal opens correctly
4. Test creating and managing conduct agreements

## Troubleshooting

### Button Not Appearing
- Verify the template modification was applied correctly
- Check that you are logged in and have admin rights to the space
- Clear HumHub cache: `php protected/yii cache/flush-all`

### Modal Appearing Behind Navigation
- The module includes z-index fixes in the admin modal view
- If issues persist, check for conflicting CSS in your theme

### Database Issues
- Ensure migrations ran successfully: `php protected/yii migrate/up`
- Check database permissions for the web server user

## Files Modified
- `/protected/modules/space-conduct-agreement/` - Module files
- `/protected/humhub/modules/space/widgets/views/profileHeaderControls.php` - Template modification

## Support
For issues or questions, please refer to the module documentation or contact the development team.

---
**Note:** This template modification is required for the module to function properly. The button will not appear without this modification. 