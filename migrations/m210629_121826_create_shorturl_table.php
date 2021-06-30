<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%shorturl}}`.
 */
class m210629_121826_create_shorturl_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%shorturl}}', [
            'id' => $this->primaryKey(),
            'url'=> $this->text()->notNull(),
            'shorturl'=>$this->text()->notNull(),
            'creating_date_time'=>$this->timestamp()->notNull(), //default 'CURRENT_TIMESTAMP' have some trouble
            'counter'=>$this->integer()->notNull()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%shorturl}}');
    }
}
