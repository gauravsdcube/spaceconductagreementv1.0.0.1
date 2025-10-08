<?php
/**
 * FILE: /protected/modules/space-conduct-agreement/migrations/m000000_000000_create_space_conduct_tables.php
 *
 * @copyright Copyright (c) 2025 D Cube Consulting
 * @author D Cube Consulting <info@dcubeconsulting.co.uk>
 */

use yii\db\Migration;

/**
 * Create tables for space conduct agreement module
 */
class m000000_000000_create_space_conduct_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';
        }

        // Create space agreements table
        $this->createTable('{{%space_conduct_agreement}}', [
            'id' => $this->primaryKey(),
            'space_id' => $this->integer()->notNull(),
            'title' => $this->string()->notNull(),
            'content' => $this->text()->notNull(),
            'is_active' => $this->boolean()->defaultValue(1),
            'created_at' => $this->dateTime(),
            'created_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
        ], $tableOptions);

        // Create user agreements table
        $this->createTable('{{%space_conduct_user_agreement}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'space_agreement_id' => $this->integer()->notNull(),
            'accepted_at' => $this->dateTime(),
            'ip_address' => $this->string(45),
        ], $tableOptions);

        // Add foreign keys if tables exist
        try {
            $this->addForeignKey(
                'fk-space_conduct_agreement-space_id',
                '{{%space_conduct_agreement}}',
                'space_id',
                '{{%space}}',
                'id',
                'CASCADE',
                'CASCADE'
            );
        } catch (Exception $e) {
            // Table might not exist in development
            echo "Warning: Could not add foreign key for space table: " . $e->getMessage() . "\n";
        }

        try {
            $this->addForeignKey(
                'fk-space_conduct_user_agreement-user_id',
                '{{%space_conduct_user_agreement}}',
                'user_id',
                '{{%user}}',
                'id',
                'CASCADE',
                'CASCADE'
            );
        } catch (Exception $e) {
            // Table might not exist in development
            echo "Warning: Could not add foreign key for user table: " . $e->getMessage() . "\n";
        }

        $this->addForeignKey(
            'fk-space_conduct_user_agreement-agreement_id',
            '{{%space_conduct_user_agreement}}',
            'space_agreement_id',
            '{{%space_conduct_agreement}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        // Add indexes for better performance
        $this->createIndex(
            'idx-space_conduct_agreement-space_id',
            '{{%space_conduct_agreement}}',
            'space_id'
        );

        $this->createIndex(
            'idx-space_conduct_agreement-is_active',
            '{{%space_conduct_agreement}}',
            'is_active'
        );

        $this->createIndex(
            'idx-space_conduct_user_agreement-user_space',
            '{{%space_conduct_user_agreement}}',
            ['user_id', 'space_agreement_id'],
            true // unique
        );

        $this->createIndex(
            'idx-space_conduct_user_agreement-accepted_at',
            '{{%space_conduct_user_agreement}}',
            'accepted_at'
        );

        echo "Space Conduct Agreement module tables created successfully.\n";
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%space_conduct_user_agreement}}');
        $this->dropTable('{{%space_conduct_agreement}}');
        
        echo "Space Conduct Agreement module tables removed.\n";
    }
} 