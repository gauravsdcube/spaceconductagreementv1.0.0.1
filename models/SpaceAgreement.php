<?php
/**
 * FILE: /protected/modules/space-conduct-agreement/models/SpaceAgreement.php
 *
 * @copyright Copyright (c) 2025 D Cube Consulting
 * @author D Cube Consulting <info@dcubeconsulting.co.uk>
 */

namespace humhub\modules\spaceconductagreement\models;

use Yii;
use yii\db\ActiveRecord;
use humhub\modules\space\models\Space;

/**
 * SpaceAgreement model for storing space-specific conduct agreements
 *
 * @property integer $id
 * @property integer $space_id
 * @property string $title
 * @property string $content
 * @property integer $is_active
 * @property string $created_at
 * @property integer $created_by
 * @property string $updated_at
 * @property integer $updated_by
 */
class SpaceAgreement extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'space_conduct_agreement';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['space_id', 'title', 'content'], 'required'],
            [['space_id', 'is_active', 'created_by', 'updated_by'], 'integer'],
            [['content'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['title'], 'string', 'max' => 255],
            [['is_active'], 'boolean'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'space_id' => 'Space',
            'title' => 'Agreement Title',
            'content' => 'Agreement Content',
            'is_active' => 'Active',
            'created_at' => 'Created At',
            'created_by' => 'Created By',
            'updated_at' => 'Updated At',
            'updated_by' => 'Updated By',
        ];
    }

    /**
     * Get associated space
     */
    public function getSpace()
    {
        return $this->hasOne(Space::class, ['id' => 'space_id']);
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->created_at = date('Y-m-d H:i:s');
                $this->created_by = Yii::$app->user->id;
            }
            $this->updated_at = date('Y-m-d H:i:s');
            $this->updated_by = Yii::$app->user->id;
            return true;
        }
        return false;
    }

    /**
     * @inheritdoc
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        // No session flag logic needed here anymore
    }
}