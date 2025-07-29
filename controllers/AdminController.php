<?php
/**
 * FILE: /protected/modules/space-conduct-agreement/controllers/AdminController.php
 *
 * @copyright Copyright (c) 2025 D Cube Consulting
 * @author D Cube Consulting <info@dcubeconsulting.co.uk>
 */

namespace humhub\modules\spaceconductagreement\controllers;

use Yii;
use yii\web\NotFoundHttpException;
use humhub\components\Controller;
use humhub\modules\space\models\Space;
use humhub\modules\spaceconductagreement\models\SpaceAgreement;

/**
 * Admin controller for managing space agreements
 */
class AdminController extends Controller
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
     * Manage space agreement
     */
    public function actionIndex($spaceId)
    {
        $space = Space::findOne($spaceId);
        if (!$space || !$space->isAdmin()) {
            throw new NotFoundHttpException();
        }

        // Always create a new model for the form
        $model = new SpaceAgreement();
        $model->space_id = $spaceId;
        $model->is_active = 1;

        if ($model->load(Yii::$app->request->post())) {
            // Deactivate all previous agreements for this space
            SpaceAgreement::updateAll(['is_active' => 0], ['space_id' => $spaceId]);
            // Save the new agreement
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Agreement saved successfully.');
                return $this->redirect($space->createUrl());
            }
        } else {
            // If GET, prefill with the latest active agreement if it exists
            $latest = SpaceAgreement::findOne(['space_id' => $spaceId, 'is_active' => 1]);
            if ($latest) {
                $model->title = $latest->title;
                $model->content = $latest->content;
            }
        }

        return $this->render('index', [
            'model' => $model,
            'space' => $space
        ]);
    }
}