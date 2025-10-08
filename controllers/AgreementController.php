<?php
/**
 * FILE: /protected/modules/space-conduct-agreement/controllers/AgreementController.php
 *
 * @copyright Copyright (c) 2025 D Cube Consulting
 * @author D Cube Consulting <info@dcubeconsulting.co.uk>
 */

namespace humhub\modules\spaceconductagreement\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use humhub\components\Controller;
use humhub\modules\space\models\Space;
use humhub\modules\space\models\Membership;
use humhub\modules\spaceconductagreement\models\SpaceAgreement;
use humhub\modules\spaceconductagreement\models\UserAgreement;

/**
 * Agreement controller for user acceptance
 */
class AgreementController extends Controller
{
    /**
     * @inheritdoc
     */
    public function getAccessRules()
    {
        return [
            ['login']
        ];
    }

    /**
     * Show agreement for user acceptance
     */
    public function actionShow($spaceId)
    {
        $space = Space::findOne($spaceId);
        if (!$space) {
            throw new NotFoundHttpException();
        }

        $spaceAgreement = SpaceAgreement::findOne(['space_id' => $spaceId, 'is_active' => 1]);
        if (!$spaceAgreement) {
            // No agreement required, redirect to space
            return $this->redirect($space->createUrl());
        }

        $user = Yii::$app->user->getIdentity();
        
        // Check if already accepted
        if (UserAgreement::hasUserAccepted($user->id, $spaceAgreement->id)) {
            // Already accepted, redirect to space
            return $this->redirect($space->createUrl());
        }

        return $this->render('show', [
            'space' => $space,
            'agreement' => $spaceAgreement
        ]);
    }

    /**
     * Accept agreement
     */
    public function actionAccept($spaceId)
    {
        $space = Space::findOne($spaceId);
        if (!$space) {
            throw new NotFoundHttpException();
        }

        $spaceAgreement = SpaceAgreement::findOne(['space_id' => $spaceId, 'is_active' => 1]);
        if (!$spaceAgreement) {
            throw new NotFoundHttpException();
        }

        $user = Yii::$app->user->getIdentity();

        // Create user agreement record
        $userAgreement = new UserAgreement();
        $userAgreement->user_id = $user->id;
        $userAgreement->space_agreement_id = $spaceAgreement->id;
        
        if ($userAgreement->save()) {
            // Update membership status to active
            $membership = Membership::findOne(['space_id' => $spaceId, 'user_id' => $user->id]);
            if ($membership && $membership->status == Membership::STATUS_APPLICANT) {
                $membership->status = Membership::STATUS_MEMBER;
                $membership->save();
            }

            // Remove any pending agreement session flags
            Yii::$app->session->remove('pending_agreement_space_' . $space->id);

            Yii::$app->session->setFlash('success', 
                Yii::t('SpaceConductAgreementModule.base', 
                    'Thank you for accepting the code of conduct. Welcome to {spaceName}!',
                    ['spaceName' => $space->name]
                )
            );

            return $this->redirect($space->createUrl());
        }

        Yii::$app->session->setFlash('error', 'Error accepting agreement. Please try again.');
        return $this->actionShow($spaceId);
    }

    /**
     * Decline agreement
     */
    public function actionDecline($spaceId)
    {
        // Only allow POST requests for security
        if (!Yii::$app->request->isPost) {
            throw new NotFoundHttpException('Invalid request method.');
        }

        $space = Space::findOne($spaceId);
        if (!$space) {
            throw new NotFoundHttpException('Space not found.');
        }

        $user = Yii::$app->user->getIdentity();
        if (!$user) {
            throw new NotFoundHttpException('User not authenticated.');
        }

        // Remove membership
        $membership = Membership::findOne(['space_id' => $spaceId, 'user_id' => $user->id]);
        if ($membership) {
            $membership->delete();
        }

        // Remove any pending agreement session flags
        Yii::$app->session->remove('pending_agreement_space_' . $space->id);

        Yii::$app->session->setFlash('info', 
            Yii::t('SpaceConductAgreementModule.base', 
                'You have declined to join {spaceName}.',
                ['spaceName' => $space->name]
            )
        );

        return $this->redirect(['/dashboard/dashboard']);
    }
}