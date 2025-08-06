<?php
/**
 * FILE: /protected/modules/space-conduct-agreement/Events.php
 *
 * @copyright Copyright (c) 2025 D Cube Consulting
 * @author D Cube Consulting <info@dcubeconsulting.co.uk>
 */

namespace humhub\modules\spaceconductagreement;

use Yii;
use humhub\modules\space\models\Space;
use humhub\modules\space\models\Membership;
use humhub\modules\space\MemberEvent;
use humhub\modules\spaceconductagreement\models\SpaceAgreement;
use humhub\modules\spaceconductagreement\models\UserAgreement;
use humhub\modules\space\widgets\HeaderControlsMenu;
use humhub\modules\ui\menu\MenuLink;

/**
 * Event handlers for Space Conduct Agreement module
 */
class Events
{
    /**
     * Handle new space membership
     */
    public static function onMemberAdded(MemberEvent $event)
    {
        $space = $event->space;
        $user = $event->user;
        
        // Validate that we have valid user and space objects
        if (!$user || !$space) {
            return;
        }

        // Check if space has conduct agreement requirement
        $spaceAgreement = SpaceAgreement::findOne(['space_id' => $space->id, 'is_active' => 1]);
        
        if (!$spaceAgreement) {
            // No conduct agreement required for this space
            return;
        }

        // Check if user has already agreed to the CURRENT agreement
        if (UserAgreement::hasUserAccepted($user->id, $spaceAgreement->id)) {
            // User has already agreed to the current agreement, allow the membership
            return;
        }

        // Get the current membership
        $membership = Membership::findOne(['space_id' => $space->id, 'user_id' => $user->id]);
        if (!$membership) {
            return;
        }

        // For admin approvals, show a flash message
        if ($membership->status === Membership::STATUS_MEMBER) {
            Yii::$app->session->setFlash('info',
                Yii::t('SpaceConductAgreementModule.base',
                    'Welcome to {spaceName}! Please review and accept the code of conduct to fully participate.',
                    ['spaceName' => $space->name]
                )
            );
        }

        // For new join requests, keep them as APPLICANT until they accept the agreement
        if ($membership->status === Membership::STATUS_APPLICANT) {
            Yii::$app->session->setFlash('info',
                Yii::t('SpaceConductAgreementModule.base',
                    'Please accept the code of conduct for {spaceName} to complete your membership.',
                    ['spaceName' => $space->name]
                )
            );
        }
    }

    /**
     * Handle member removal - clean up agreement records
     */
    public static function onMemberRemoved(MemberEvent $event)
    {
        $space = $event->space;
        $user = $event->user;
        
        // Validate that we have valid user and space objects
        if (!$user || !$space) {
            return;
        }

        // Find all agreement records for this user and space
        $spaceAgreements = SpaceAgreement::findAll(['space_id' => $space->id]);
        
        if (empty($spaceAgreements)) {
            return;
        }

        $agreementIds = array_column($spaceAgreements, 'id');
        
        // Delete all user agreement records for this space
        UserAgreement::deleteAll([
            'user_id' => $user->id,
            'space_agreement_id' => $agreementIds
        ]);

        // Log the cleanup for debugging
        Yii::info("Cleaned up conduct agreement records for user {$user->id} in space {$space->id}", 'space-conduct-agreement');
    }

    /**
     * Check for pending agreements when user visits a space
     */
    public static function onSpaceAccess($event)
    {
        $controller = $event->sender;
        
        // Only check for space controllers
        if (!$controller instanceof \humhub\modules\space\controllers\SpaceController) {
            return;
        }
        
        // Only check for specific space actions
        $action = $controller->action->id ?? '';
        if (!in_array($action, ['index', 'home'])) {
            return;
        }
        
        $user = \Yii::$app->user->getIdentity();
        if (!$user || \Yii::$app->user->isGuest) {
            return;
        }
        
        // Get the space from the controller
        $space = $controller->getSpace();
        if (!$space) {
            return;
        }
        
        // Check if user is a member of this space
        if (!$space->isMember($user->id)) {
            return;
        }
        
        // Check if space has an active agreement
        $spaceAgreement = SpaceAgreement::findOne(['space_id' => $space->id, 'is_active' => 1]);
        if (!$spaceAgreement) {
            // No agreement required for this space
            return;
        }
        
        // Check if user has agreed to this agreement
        if (!UserAgreement::hasUserAccepted($user->id, $spaceAgreement->id)) {
            // User hasn't agreed yet, redirect to agreement page
            \Yii::$app->response->redirect(['/space-conduct-agreement/agreement/show', 'spaceId' => $space->id]);
            \Yii::$app->end();
        }
    }

    /**
     * Test method to see if module events are working
     */
    public static function onTestEvent($event)
    {
        Yii::info("=== SPACE CONDUCT AGREEMENT TEST EVENT WORKING ===", 'space-conduct-agreement');
    }

    /**
     * Add Code of Conduct menu item to space navigation
     */
    public static function onSpaceMenuInit($event)
    {
        /** @var \humhub\modules\space\widgets\Menu $menu */
        $menu = $event->sender;
        $space = $menu->space;

        if (!$space) {
            return;
        }

        // Only show for space admins
        if (!$space->isAdmin()) {
            return;
        }

        // Check if module is enabled for this space
        if (!$space->moduleManager->isEnabled('space-conduct-agreement')) {
            return;
        }

        // Add Code of Conduct menu item
        $menu->addItem([
            'label' => Yii::t('SpaceConductAgreementModule.base', 'Code of Conduct'),
            'url' => $space->createUrl('/space-conduct-agreement/admin/index'),
            'icon' => '<i class="fa fa-file-text-o"></i>',
            'isActive' => (Yii::$app->controller->module && 
                          Yii::$app->controller->module->id === 'space-conduct-agreement' && 
                          Yii::$app->controller->id === 'admin'),
            'sortOrder' => 20001,
        ]);
    }

    /**
     * Add admin button to space header controls (kept for backward compatibility)
     */
    public static function onHeaderControlsMenuInit($event)
    {
        // This method is kept for backward compatibility but is no longer used
        // The navigation menu item is now used instead
        return;
    }
}
