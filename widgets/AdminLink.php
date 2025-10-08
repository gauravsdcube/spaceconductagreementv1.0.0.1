<?php
/**
 * FILE: /protected/modules/space-conduct-agreement/widgets/AdminLink.php
 */

namespace humhub\modules\spaceconductagreement\widgets;

use yii\helpers\Html;
use yii\helpers\Url;
use humhub\components\Widget;

/**
 * Admin link widget for space header
 */
class AdminLink extends Widget
{
    public $space;

    public function run()
    {
        if (!$this->space->isAdmin()) {
            return '';
        }

        $url = Url::to(['/space-conduct-agreement/admin/index', 'spaceId' => $this->space->id]);
        
        return Html::a(
            '<i class="fa fa-file-text-o"></i> Manage Code of Conduct',
            $url,
            [
                'class' => 'btn btn-sm btn-default',
                'data-target' => '#globalModal'
            ]
        );
    }
}