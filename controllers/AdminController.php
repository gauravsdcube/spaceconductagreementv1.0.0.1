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
        
        // Debug: Log the current space ID
        Yii::info("AdminController: Processing for Space ID: " . $space->id . " (" . $space->name . ")", 'spaceconductagreement');
        
        // Debug: Show all existing agreements for this space
        $allAgreements = SpaceAgreement::getAllForSpace($space->id);
        foreach ($allAgreements as $agreement) {
            Yii::info("AdminController: Existing agreement ID " . $agreement->id . " for Space " . $space->id . " - Active: " . $agreement->is_active . " - Title: " . $agreement->title, 'spaceconductagreement');
        }

        // Check if this is a POST request (form submission)
        if (Yii::$app->request->isPost) {
            // AGGRESSIVE APPROACH: Always create a completely new agreement
            $model = new SpaceAgreement();
            $model->space_id = $space->id;
            $model->is_active = 1;
            
            if ($model->load(Yii::$app->request->post())) {
                // Debug: Log what was loaded
                Yii::info("AdminController POST: Loaded model with space_id: " . $model->space_id . ", title: " . $model->title, 'spaceconductagreement');
                
                // FORCE space_id to be correct for this space
                $model->space_id = $space->id;
                $model->is_active = 1;
                
                // Deactivate ALL previous agreements for THIS SPACE ONLY
                SpaceAgreement::deactivateAllForSpace($space->id);
                
                // Save the new agreement for THIS SPACE
                if ($model->save()) {
                    Yii::info("AdminController POST: Successfully saved agreement for Space " . $space->id, 'spaceconductagreement');
                    Yii::$app->session->setFlash('success', 'Agreement saved successfully for ' . $space->name . '.');
                    return $this->redirect($space->createUrl());
                } else {
                    Yii::error("AdminController POST: Failed to save agreement. Errors: " . json_encode($model->errors), 'spaceconductagreement');
                    Yii::$app->session->setFlash('error', 'Failed to save agreement. Please try again.');
                }
            }
        } else {
            // GET request - ALWAYS create a fresh model, never load existing
            // This prevents any possibility of content copying
            Yii::info("AdminController: Creating fresh model for Space " . $space->id, 'spaceconductagreement');
            
            $model = new SpaceAgreement();
            $model->space_id = $space->id;
            $model->is_active = 1;
            
            // Check if there's an existing agreement to show as reference
            $existingAgreement = SpaceAgreement::getActiveForSpace($space->id);
            if ($existingAgreement) {
                Yii::info("AdminController: Found existing agreement for Space " . $space->id . " - ID: " . $existingAgreement->id, 'spaceconductagreement');
                // Copy content to new model for editing, but keep it as a NEW record
                $model->title = $existingAgreement->title;
                $model->content = $existingAgreement->content;
                // Don't copy the ID - this ensures it's treated as a new record
            } else {
                Yii::info("AdminController: No existing agreement for Space " . $space->id . " - creating completely new", 'spaceconductagreement');
            }
        }

        return $this->render('index', [
            'model' => $model,
            'space' => $space
        ]);
    }
}