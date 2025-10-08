<?php
/**
 * FILE: /protected/modules/space-conduct-agreement/views/admin/index.php
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use humhub\widgets\ModalDialog;

/* @var $this yii\web\View */
/* @var $model humhub\modules\spaceconductagreement\models\SpaceAgreement */
/* @var $space humhub\modules\space\models\Space */

$this->title = 'Manage Code of Conduct - ' . $space->name;
?>

<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">
                <i class="fa fa-file-text-o"></i> 
                <?= Html::encode($this->title) ?>
            </h4>
        </div>
        
        <?php $form = ActiveForm::begin(['id' => 'space-conduct-form']); ?>
        
        <div class="modal-body">
            <div class="alert alert-info">
                <strong>Space:</strong> <?= Html::encode($space->name) ?><br>
                <small>Create a code of conduct that users must accept before joining this space.</small>
            </div>
            
            <div class="form-group">
                <?= $form->field($model, 'title')->textInput([
                    'maxlength' => true,
                    'placeholder' => 'e.g., "AVID Community Code of Conduct"'
                ]) ?>
            </div>
            
            <div class="form-group">
                <?= $form->field($model, 'content')->textarea([
                    'rows' => 12,
                    'placeholder' => 'Enter the code of conduct that users must accept before joining this space...',
                    'style' => 'font-family: inherit;'
                ]) ?>
                <small class="help-block">
                    <strong>Example content:</strong><br>
                    "Welcome to [Space Name]. By joining this space, you agree to:<br>
                    • Maintain professional and respectful communication<br>
                    • Share knowledge and expertise constructively<br>
                    • Respect confidentiality and privacy<br>
                    • Follow all applicable policies and guidelines"
                </small>
            </div>
            
            <div class="form-group">
                <div class="checkbox">
                    <label>
                        <?= Html::activeCheckbox($model, 'is_active') ?>
                        <strong>Require acceptance of this agreement</strong>
                    </label>
                </div>
                <p class="help-block">
                    When checked, users will be required to accept this agreement before joining the space.
                    Uncheck to disable the requirement temporarily.
                </p>
            </div>
            
            <?php if (!$model->isNewRecord): ?>
            <div class="alert alert-warning">
                <strong>Note:</strong> If you change the agreement content, existing members will need to re-accept the new terms.
            </div>
            <?php endif; ?>
        </div>
        
        <div class="modal-footer">
            <?= Html::submitButton(
                '<i class="fa fa-save"></i> Save Agreement', 
                ['class' => 'btn btn-primary']
            ) ?>
            <button type="button" class="btn btn-default" data-dismiss="modal">
                <i class="fa fa-times"></i> Cancel
            </button>
        </div>
        
        <?php ActiveForm::end(); ?>
    </div>
</div>

<style>
.modal-lg {
    width: 900px;
}
.form-group textarea {
    resize: vertical;
    min-height: 200px;
}
.help-block {
    font-size: 12px;
    margin-top: 5px;
}
/* Fix modal z-index to appear above navigation */
.modal {
    z-index: 1050 !important;
}
.modal-backdrop {
    z-index: 1040 !important;
}
/* Ensure modal content is above backdrop */
.modal-dialog {
    z-index: 1060 !important;
}
</style> 