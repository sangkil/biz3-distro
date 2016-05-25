<?php

use yii\db\Schema;

/**
 * 
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>  
 * @since 3.0
 */
class m160201_050050_create_table_accounting extends \yii\db\Migration
{

    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%coa}}', [
            'id' => Schema::TYPE_PK,
            'parent_id' => Schema::TYPE_INTEGER,
            'code' => Schema::TYPE_STRING . '(16) NOT NULL',
            'name' => Schema::TYPE_STRING . '(64) NOT NULL',
            'type' => Schema::TYPE_INTEGER . ' NOT NULL',
            'normal_balance' => Schema::TYPE_STRING . '(1) NOT NULL',
            // history column
            'created_at' => Schema::TYPE_INTEGER,
            'created_by' => Schema::TYPE_INTEGER,
            'updated_at' => Schema::TYPE_INTEGER,
            'updated_by' => Schema::TYPE_INTEGER,
            // constrain
            'FOREIGN KEY ([[parent_id]]) REFERENCES {{%coa}} ([[id]]) ON DELETE RESTRICT ON UPDATE CASCADE',
            ], $tableOptions);

        $this->createTable('{{%acc_periode}}', [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . '(32) NOT NULL',
            'date_from' => Schema::TYPE_DATE . ' NOT NULL',
            'date_to' => Schema::TYPE_DATE . ' NOT NULL',
            'status' => Schema::TYPE_INTEGER . ' NOT NULL',
            // history column
            'created_at' => Schema::TYPE_INTEGER,
            'created_by' => Schema::TYPE_INTEGER,
            'updated_at' => Schema::TYPE_INTEGER,
            'updated_by' => Schema::TYPE_INTEGER,
            ], $tableOptions);
//
        $this->createTable('{{%entri_sheet}}', [
            'id' => Schema::TYPE_PK,
            'code' => Schema::TYPE_STRING . '(16) NOT NULL',
            'name' => Schema::TYPE_STRING . '(64)',
            'd_coa_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'k_coa_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            // history column
            'created_at' => Schema::TYPE_INTEGER,
            'created_by' => Schema::TYPE_INTEGER,
            'updated_at' => Schema::TYPE_INTEGER,
            'updated_by' => Schema::TYPE_INTEGER,
            // constrain
            'FOREIGN KEY ([[d_coa_id]]) REFERENCES {{%coa}} ([[id]]) ON DELETE RESTRICT ON UPDATE CASCADE',
            'FOREIGN KEY ([[k_coa_id]]) REFERENCES {{%coa}} ([[id]]) ON DELETE RESTRICT ON UPDATE CASCADE',
            ], $tableOptions);

        $this->createTable('{{%gl_header}}', [
            'id' => Schema::TYPE_PK,
            'number' => Schema::TYPE_STRING . '(16) NOT NULL',
            'date' => Schema::TYPE_DATE . ' NOT NULL',
            'periode_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'branch_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'reff_type' => Schema::TYPE_INTEGER . ' NOT NULL',
            'reff_id' => Schema::TYPE_INTEGER,
            'description' => Schema::TYPE_STRING . ' NOT NULL',
            'status' => Schema::TYPE_INTEGER . ' NOT NULL',
            // history column
            'created_at' => Schema::TYPE_INTEGER,
            'created_by' => Schema::TYPE_INTEGER,
            'updated_at' => Schema::TYPE_INTEGER,
            'updated_by' => Schema::TYPE_INTEGER,
            // constrain
            'FOREIGN KEY ([[periode_id]]) REFERENCES {{%acc_periode}} ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
            ], $tableOptions);

        $this->createTable('{{%gl_detail}}', [
            'id' => Schema::TYPE_PK,
            'header_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'coa_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'amount' => Schema::TYPE_FLOAT . ' NOT NULL',
            // constrain
            'FOREIGN KEY ([[header_id]]) REFERENCES {{%gl_header}} ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
            'FOREIGN KEY ([[coa_id]]) REFERENCES {{%coa}} ([[id]]) ON DELETE RESTRICT ON UPDATE CASCADE',
            ], $tableOptions);

        $this->createTable('{{%invoice}}', [
            'id' => Schema::TYPE_PK,
            'number' => Schema::TYPE_STRING . '(16) NOT NULL',
            'date' => Schema::TYPE_DATE . ' NOT NULL',
            'due_date' => Schema::TYPE_DATE . ' NOT NULL',
            'type' => Schema::TYPE_INTEGER . ' NOT NULL',
            'vendor_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'reff_type' => Schema::TYPE_INTEGER,
            'reff_id' => Schema::TYPE_INTEGER,
            'status' => Schema::TYPE_INTEGER . ' NOT NULL',
            'description' => Schema::TYPE_STRING . '(64) NULL',
            'value' => Schema::TYPE_FLOAT . ' NOT NULL',
            'tax_type' => Schema::TYPE_STRING . '(64)',
            'tax_value' => Schema::TYPE_FLOAT,
            // history column
            'created_at' => Schema::TYPE_INTEGER,
            'created_by' => Schema::TYPE_INTEGER,
            'updated_at' => Schema::TYPE_INTEGER,
            'updated_by' => Schema::TYPE_INTEGER,
            ], $tableOptions);

        $this->createTable('{{%invoice_dtl}}', [
            'id' => Schema::TYPE_PK,
            'invoice_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'item_type' => Schema::TYPE_INTEGER,
            'item_id' => Schema::TYPE_INTEGER,
            'qty' => Schema::TYPE_FLOAT . ' NOT NULL',
            'item_value' => Schema::TYPE_FLOAT . ' NOT NULL',
            'tax_type' => Schema::TYPE_STRING . '(64)',
            'tax_value' => Schema::TYPE_FLOAT,
            // constrain
            'FOREIGN KEY ([[invoice_id]]) REFERENCES {{%invoice}} ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
            ], $tableOptions);

        $this->createTable('{{%payment}}', [
            'id' => Schema::TYPE_PK,
            'number' => Schema::TYPE_STRING . '(16) NOT NULL',
            'vendor_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'date' => Schema::TYPE_DATE . ' NOT NULL',
            'type' => Schema::TYPE_INTEGER . ' NOT NULL',
            'payment_method' => Schema::TYPE_INTEGER . ' NOT NULL',
            'status' => Schema::TYPE_INTEGER . ' NOT NULL',
            // history column
            'created_at' => Schema::TYPE_INTEGER,
            'created_by' => Schema::TYPE_INTEGER,
            'updated_at' => Schema::TYPE_INTEGER,
            'updated_by' => Schema::TYPE_INTEGER,
            ], $tableOptions);

        $this->createTable('{{%payment_dtl}}', [
            'payment_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'invoice_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'value' => Schema::TYPE_FLOAT . ' NOT NULL',
            // constrain
            'PRIMARY KEY ([[payment_id]], [[invoice_id]])',
            'FOREIGN KEY ([[payment_id]]) REFERENCES {{%payment}} ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
            'FOREIGN KEY ([[invoice_id]]) REFERENCES {{%invoice}} ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
            ], $tableOptions);

        $this->createTable('{{%payment_method}}', [
            'id' => Schema::TYPE_PK,
            'branch_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'method' => Schema::TYPE_STRING . '(32) NOT NULL',
            'coa_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'potongan' => Schema::TYPE_FLOAT . ' DEFAULT 0',
            'coa_id_potongan' => Schema::TYPE_INTEGER,
            // history column
            'created_at' => Schema::TYPE_INTEGER,
            'created_by' => Schema::TYPE_INTEGER,
            'updated_at' => Schema::TYPE_INTEGER,
            'updated_by' => Schema::TYPE_INTEGER,
            ], $tableOptions);
    }

    public function safeDown()
    {        
        $this->dropTable('{{%payment_dtl}}');
        $this->dropTable('{{%payment_method}}');
        $this->dropTable('{{%payment}}');
        $this->dropTable('{{%invoice_dtl}}');
        $this->dropTable('{{%invoice}}');
        $this->dropTable('{{%gl_detail}}');
        $this->dropTable('{{%gl_header}}');
        $this->dropTable('{{%entri_sheet}}');
        $this->dropTable('{{%acc_periode}}');
        $this->dropTable('{{%coa}}');
    }
}
