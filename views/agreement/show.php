<?php
/**
 * FILE: /protected/modules/space-conduct-agreement/views/agreement/show.php
 */

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $space humhub\modules\space\models\Space */
/* @var $agreement humhub\modules\spaceconductagreement\models\SpaceAgreement */

$this->title = $agreement->title . ' - ' . $space->name;
$this->params['breadcrumbs'][] = ['label' => 'Spaces', 'url' => ['/space/spaces']];
$this->params['breadcrumbs'][] = ['label' => $space->name, 'url' => $space->createUrl()];
$this->params['breadcrumbs'][] = $agreement->title;
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default agreement-panel">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <i class="fa fa-file-text-o text-primary"></i> 
                        <?= Html::encode($agreement->title) ?>
                    </h3>
                </div>
                
                <div class="panel-body">
                    <div class="alert alert-info">
                        <div class="row">
                            <div class="col-md-6">
                                <strong><i class="fa fa-users"></i> Space:</strong> 
                                <?= Html::encode($space->name) ?>
                            </div>
                            <div class="col-md-6">
                                <strong><i class="fa fa-exclamation-triangle"></i> Required:</strong> 
                                You must accept this agreement to join the space
                            </div>
                        </div>
                    </div>
                    
                    <div class="agreement-content">
                        <?= nl2br(Html::encode($agreement->content)) ?>
                    </div>
                    
                    <hr>
                    
                    <div class="agreement-footer">
                        <div class="text-center">
                            <h4 class="text-primary">
                                <i class="fa fa-question-circle"></i> 
                                Do you agree to abide by this code of conduct?
                            </h4>
                            <p class="text-muted">
                                By clicking "I Accept", you confirm that you have read, understood, 
                                and agree to follow the terms outlined above.
                            </p>
                        </div>
                        
                        <div class="text-center" style="margin-top: 30px;">
                            <div class="btn-group btn-group-lg" role="group">
                                <?= Html::a(
                                    '<i class="fa fa-check"></i> I Accept', 
                                    Url::to(['/space-conduct-agreement/agreement/accept', 'spaceId' => $space->id]), 
                                    [
                                        'class' => 'btn btn-success btn-lg',
                                        'data-method' => 'post',
                                        'data-loading-text' => 'Processing...',
                                        'title' => 'Accept the code of conduct and join the space'
                                    ]
                                ) ?>
                                
                                <?= Html::a(
                                    '<i class="fa fa-times"></i> I Decline', 
                                    Url::to(['/space-conduct-agreement/agreement/decline', 'spaceId' => $space->id]), 
                                    [
                                        'class' => 'btn btn-danger btn-lg',
                                        'data-method' => 'post',
                                        'data-confirm' => 'Are you sure you want to decline? You will not be able to join this space.',
                                        'title' => 'Decline and do not join the space'
                                    ]
                                ) ?>
                            </div>
                        </div>
                        
                        <div class="text-center" style="margin-top: 20px;">
                            <small class="text-muted">
                                <i class="fa fa-shield"></i> 
                                Your acceptance will be recorded with a timestamp for compliance purposes.
                            </small>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="text-center">
                <?= Html::a(
                    '<i class="fa fa-arrow-left"></i> Back to Dashboard', 
                    Url::to(['/dashboard/dashboard']), 
                    ['class' => 'btn btn-default']
                ) ?>
            </div>
        </div>
    </div>
</div>

<style>
.agreement-panel {
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    border: none;
}

.agreement-content {
    max-height: 400px;
    overflow-y: auto;
    padding: 20px;
    background-color: #f9f9f9;
    border: 1px solid #e3e3e3;
    border-radius: 6px;
    margin: 20px 0;
    line-height: 1.8;
    font-size: 14px;
}

.agreement-content::-webkit-scrollbar {
    width: 8px;
}

.agreement-content::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

.agreement-content::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 4px;
}

.agreement-content::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

.agreement-footer {
    background-color: #fafafa;
    padding: 20px;
    border-radius: 6px;
    border: 1px solid #e8e8e8;
}

.btn-group-lg .btn {
    padding: 12px 30px;
    font-size: 16px;
    margin: 0 10px;
}

.panel-title {
    font-size: 18px;
    font-weight: 600;
}

@media (max-width: 768px) {
    .btn-group-lg .btn {
        display: block;
        width: 100%;
        margin: 5px 0;
    }
    
    .agreement-content {
        max-height: 300px;
        padding: 15px;
    }
}
</style>