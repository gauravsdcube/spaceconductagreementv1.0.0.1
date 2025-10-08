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
use humhub\modules\space\controllers\SpaceController;
use humhub\modules\spaceconductagreement\models\SpaceAgreement;

/**
 * Admin controller for managing space agreements
 */
class AdminController extends SpaceController
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
     * @inheritdoc
     */
    public function beforeAction($action)
    {
        if (!parent::beforeAction($action)) {
            return false;
        }

        // Check if user is space admin
        if (!$this->contentContainer->isAdmin()) {
            throw new NotFoundHttpException();
        }

        return true;
    }

    /**
     * Manage space agreement
     */
    public function actionIndex()
    {
        $space = $this->contentContainer;

        if (Yii::$app->request->isPost) {
            $model = new SpaceAgreement();
            
            if ($model->load(Yii::$app->request->post())) {
                $model->space_id = $space->id;
                $model->is_active = 1;
                
                SpaceAgreement::deactivateAllForSpace($space->id);
                
                if ($model->save()) {
                    Yii::$app->session->setFlash('success', 'Agreement saved successfully for ' . $space->name . '.');
                    return $this->redirect($space->createUrl());
                } else {
                    Yii::$app->session->setFlash('error', 'Failed to save agreement. Please try again.');
                }
            } else {
                Yii::$app->session->setFlash('error', 'Failed to load form data. Please try again.');
            }
        }
        
        $model = new SpaceAgreement();
        $model->space_id = $space->id;
        $model->is_active = 1;
        
        $existingAgreement = SpaceAgreement::getActiveForSpace($space->id);
        if ($existingAgreement) {
            $model = $existingAgreement;
        }

        return $this->render('index', [
            'model' => $model,
            'space' => $space
        ]);
    }
}