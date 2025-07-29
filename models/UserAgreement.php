<?php
/**
 * FILE: /protected/modules/space-conduct-agreement/models/UserAgreement.php
 *
 * @copyright Copyright (c) 2025 D Cube Consulting
 * @author D Cube Consulting <info@dcubeconsulting.co.uk>
 */

namespace humhub\modules\spaceconductagreement\models;

use Yii;
use yii\db\ActiveRecord;
use humhub\modules\user\models\User;

/**
 * UserAgreement model for tracking user agreement acceptance
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $space_agreement_id
 * @property string $accepted_at
 * @property string $ip_address
 */
class UserAgreement extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'space_conduct_user_agreement';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'space_agreement_id'], 'required'],
            [['user_id', 'space_agreement_id'], 'integer'],
            [['accepted_at'], 'safe'],
            [['ip_address'], 'string', 'max' => 45],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User',
            'space_agreement_id' => 'Agreement',
            'accepted_at' => 'Accepted At',
            'ip_address' => 'IP Address',
        ];
    }

    /**
     * Get associated user
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    /**
     * Get associated agreement
     */
    public function getSpaceAgreement()
    {
        return $this->hasOne(SpaceAgreement::class, ['id' => 'space_agreement_id']);
    }

    /**
     * Check if a user has accepted a specific agreement
     * 
     * @param int $userId
     * @param int $spaceAgreementId
     * @return bool
     */
    public static function hasUserAccepted($userId, $spaceAgreementId)
    {
        return static::find()
            ->where(['user_id' => $userId, 'space_agreement_id' => $spaceAgreementId])
            ->exists();
    }

    /**
     * Get all agreements accepted by a user for a specific space
     * 
     * @param int $userId
     * @param int $spaceId
     * @return static[]
     */
    public static function getUserAgreementsForSpace($userId, $spaceId)
    {
        return static::find()
            ->joinWith('spaceAgreement')
            ->where(['user_id' => $userId, 'space_agreement.space_id' => $spaceId])
            ->all();
    }

    /**
     * @inheritdoc
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->accepted_at = date('Y-m-d H:i:s');
                $this->ip_address = Yii::$app->request->userIP;
            }
            return true;
        }
        return false;
    }
}