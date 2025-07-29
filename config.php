<?php
/**
 * FILE: /protected/modules/space-conduct-agreement/config.php
 */

use humhub\modules\space\models\Membership;
use humhub\modules\spaceconductagreement\Events;
use humhub\components\Controller;
use humhub\modules\space\widgets\HeaderControlsMenu;
use humhub\widgets\BaseMenu;

return [
    'id' => 'space-conduct-agreement',
    'class' => 'humhub\\modules\\spaceconductagreement\\Module',
    'namespace' => 'humhub\\modules\\spaceconductagreement',
    'version' => '1.0.0',
    'events' => [
        // Test event to see if module is working
        [
            'class' => Controller::class,
            'event' => Controller::EVENT_BEFORE_ACTION,
            'callback' => [Events::class, 'onTestEvent']
        ],
        // Intercept space join requests
        [
            'class' => Membership::class,
            'event' => Membership::EVENT_MEMBER_ADDED,
            'callback' => [Events::class, 'onMemberAdded']
        ],
        // Clean up agreement records when members leave
        [
            'class' => Membership::class,
            'event' => Membership::EVENT_MEMBER_REMOVED,
            'callback' => [Events::class, 'onMemberRemoved']
        ],
        // Register admin button in space header
        [
            "class" => HeaderControlsMenu::class,
            "event" => BaseMenu::EVENT_INIT,
            "callback" => [Events::class, "onHeaderControlsMenuInit"]
        ],
        // Check for pending agreements when accessing spaces
        [
            'class' => Controller::class,
            'event' => Controller::EVENT_BEFORE_ACTION,
            'callback' => [Events::class, 'onSpaceAccess']
        ],
    ]
]; 