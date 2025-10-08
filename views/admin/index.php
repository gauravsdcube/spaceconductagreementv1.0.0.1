<?php
/**
 * FILE: /protected/modules/space-conduct-agreement/views/admin/index.php
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use humhub\modules\content\widgets\richtext\RichTextField;
use humhub\widgets\Button;

/* @var $this yii\web\View */
/* @var $model humhub\modules\spaceconductagreement\models\SpaceAgreement */
/* @var $space humhub\modules\space\models\Space */

$this->title = 'Manage Code of Conduct - ' . $space->name;
?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <i class="fa fa-file-text-o"></i> 
                        <?= Html::encode($this->title) ?>
                    </h3>
                </div>
                <div class="panel-body">
                    <?php $form = ActiveForm::begin(['id' => 'space-conduct-form']); ?>
                    
                    <!-- Hidden field to ensure space_id is always set -->
                    <?= $form->field($model, 'space_id')->hiddenInput()->label(false) ?>
                    <?= $form->field($model, 'is_active')->hiddenInput()->label(false) ?>
                    
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
                        <?= $form->field($model, 'content')->widget(RichTextField::class, [
                            'id' => 'space-conduct-content-' . $space->id,
                            'placeholder' => 'Enter the code of conduct that users must accept before joining this space...',
                            'pluginOptions' => ['maxHeight' => '400px'],
                            'layout' => RichTextField::LAYOUT_BLOCK,
                            'backupInterval' => 0
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
                    
                    <div class="form-group">
                        <?= Html::submitButton(
                            '<i class="fa fa-save"></i> Save Agreement', 
                            ['class' => 'btn btn-primary btn-lg']
                        ) ?>
                        <?= Html::a(
                            '<i class="fa fa-arrow-left"></i> Back to Space', 
                            $space->createUrl(), 
                            ['class' => 'btn btn-default btn-lg']
                        ) ?>
                    </div>
                    
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Page-specific styling */
.panel {
    margin-top: 20px;
}
.panel-heading {
    background-color: #f5f5f5;
    border-bottom: 1px solid #ddd;
}
.panel-title {
    font-size: 18px;
    font-weight: 600;
}
.form-group textarea {
    resize: vertical;
    min-height: 200px;
}
.help-block {
    font-size: 12px;
    margin-top: 5px;
}
.btn-lg {
    margin-right: 10px;
}
.alert {
    margin-bottom: 20px;
}
/* Rich text editor styling */
.humhub-richtext {
    min-height: 300px;
}
</style>

<script>
// Clear any existing backup data for this space to prevent content copying
$(document).ready(function() {
    const spaceId = <?= $space->id ?>;
    const backupKey = 'space-conduct-content-' + spaceId;
    
    // Clear any existing backup data
    if (typeof sessionStorage !== 'undefined') {
        sessionStorage.removeItem(backupKey);
    }
    
    // Also clear any backup data for other spaces to prevent cross-contamination
    for (let i = 1; i <= 10; i++) { // Clear backup for space IDs 1-10
        if (i !== spaceId) {
            sessionStorage.removeItem('space-conduct-content-' + i);
        }
    }
    
});
</script> 