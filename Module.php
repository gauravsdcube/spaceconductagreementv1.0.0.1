<?php
/**
 * Space Conduct Agreement Module
 *
 * @copyright Copyright (c) 2025 D Cube Consulting. All rights reserved.
 * @author D Cube Consulting <info@dcubeconsulting.co.uk>
 * @version 1.0.0.1
 * @license Proprietary - All Rights Reserved
 */

namespace humhub\modules\spaceconductagreement;

use Yii;
use humhub\modules\content\components\ContentContainerModule;
use humhub\modules\space\models\Space;

/**
 * Space Conduct Agreement Module
 */
class Module extends ContentContainerModule
{
    /**
     * @inheritdoc
     */
    public $resourcesPath = 'resources';

    /**
     * @inheritdoc
     */
    public function getConfigUrl()
    {
        // This module doesn't have a global config page
        // Configuration is done per-space through the space admin interface
        return null;
    }

    /**
     * @inheritdoc
     */
    public function getContentContainerTypes()
    {
        return [
            Space::class,
        ];
    }

    /**
     * @inheritdoc
     */
    public function disable()
    {
        // Clean up when module is disabled
        parent::disable();
    }

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return Yii::t('SpaceConductAgreementModule.base', 'Space Conduct Agreement');
    }

    /**
     * @inheritdoc
     */
    public function getDescription()
    {
        return Yii::t('SpaceConductAgreementModule.base', 'Require users to accept space-specific codes of conduct before joining spaces.');
    }

    /**
     * @inheritdoc
     */
    public function getVersion()
    {
        return '1.0.0.1';
    }
}