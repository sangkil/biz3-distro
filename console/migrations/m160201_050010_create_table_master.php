<?php

use yii\db\Schema;

/**
 * 
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>  
 * @since 3.0
 */
class m160201_050010_create_table_master extends \yii\db\Migration
{

    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%orgn}}', [
            'id' => Schema::TYPE_PK,
            'code' => Schema::TYPE_STRING . '(4) NOT NULL',
            'name' => Schema::TYPE_STRING . '(32) NOT NULL',
            // history column
            'created_at' => Schema::TYPE_INTEGER,
            'created_by' => Schema::TYPE_INTEGER,
            'updated_at' => Schema::TYPE_INTEGER,
            'updated_by' => Schema::TYPE_INTEGER,
            ], $tableOptions);

        $this->createTable('{{%branch}}', [
            'id' => Schema::TYPE_PK,
            'orgn_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'code' => Schema::TYPE_STRING . '(4) NOT NULL',
            'name' => Schema::TYPE_STRING . '(32) NOT NULL',
            // history column
            'created_at' => Schema::TYPE_INTEGER,
            'created_by' => Schema::TYPE_INTEGER,
            'updated_at' => Schema::TYPE_INTEGER,
            'updated_by' => Schema::TYPE_INTEGER,
            // constrain
            'FOREIGN KEY ([[orgn_id]]) REFERENCES {{%orgn}} ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
            ], $tableOptions);

        $this->createTable('{{%warehouse}}', [
            'id' => Schema::TYPE_PK,
            'code' => Schema::TYPE_STRING . '(4) NOT NULL',
            'name' => Schema::TYPE_STRING . '(32) NOT NULL',
            // history column
            'created_at' => Schema::TYPE_INTEGER,
            'created_by' => Schema::TYPE_INTEGER,
            'updated_at' => Schema::TYPE_INTEGER,
            'updated_by' => Schema::TYPE_INTEGER,
            ], $tableOptions);

        $this->createTable('{{%product_group}}', [
            'id' => Schema::TYPE_PK,
            'code' => Schema::TYPE_STRING . '(4) NOT NULL',
            'name' => Schema::TYPE_STRING . '(32) NOT NULL',
            // history column
            'created_at' => Schema::TYPE_INTEGER,
            'created_by' => Schema::TYPE_INTEGER,
            'updated_at' => Schema::TYPE_INTEGER,
            'updated_by' => Schema::TYPE_INTEGER,
            ], $tableOptions);

        $this->createTable('{{%category}}', [
            'id' => Schema::TYPE_PK,
            'code' => Schema::TYPE_STRING . '(4) NOT NULL',
            'name' => Schema::TYPE_STRING . '(32) NOT NULL',
            // history column
            'created_at' => Schema::TYPE_INTEGER,
            'created_by' => Schema::TYPE_INTEGER,
            'updated_at' => Schema::TYPE_INTEGER,
            'updated_by' => Schema::TYPE_INTEGER,
            ], $tableOptions);

        $this->createTable('{{%product}}', [
            'id' => Schema::TYPE_PK,
            'group_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'category_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'code' => Schema::TYPE_STRING . '(13) NOT NULL',
            'name' => Schema::TYPE_STRING . '(64) NOT NULL',
            'status' => Schema::TYPE_INTEGER . ' NOT NULL',
            'edition' => Schema::TYPE_DATE,
            'stockable' => Schema::TYPE_BOOLEAN . ' DEFAULT TRUE',
            // history column
            'created_at' => Schema::TYPE_INTEGER,
            'created_by' => Schema::TYPE_INTEGER,
            'updated_at' => Schema::TYPE_INTEGER,
            'updated_by' => Schema::TYPE_INTEGER,
            // constrain
            'FOREIGN KEY ([[group_id]]) REFERENCES {{%product_group}} ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
            'FOREIGN KEY ([[category_id]]) REFERENCES {{%category}} ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
            ], $tableOptions);

        $this->createTable('{{%product_child}}', [
            'barcode' => Schema::TYPE_STRING . '(13) PRIMARY KEY',
            'product_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            // history column
            'created_at' => Schema::TYPE_INTEGER,
            'created_by' => Schema::TYPE_INTEGER,
            'updated_at' => Schema::TYPE_INTEGER,
            'updated_by' => Schema::TYPE_INTEGER,
            // constrain
            'FOREIGN KEY ([[product_id]]) REFERENCES {{%product}} ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
            ], $tableOptions);

        $this->createTable('{{%uom}}', [
            'id' => Schema::TYPE_PK,
            'code' => Schema::TYPE_STRING . '(4) NOT NULL',
            'name' => Schema::TYPE_STRING . '(32) NOT NULL',
            // history column
            'created_at' => Schema::TYPE_INTEGER,
            'created_by' => Schema::TYPE_INTEGER,
            'updated_at' => Schema::TYPE_INTEGER,
            'updated_by' => Schema::TYPE_INTEGER,
            ], $tableOptions);

        $this->createTable('{{%product_uom}}', [
            'product_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'uom_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'isi' => Schema::TYPE_INTEGER . ' NOT NULL',
            // history column
            'created_at' => Schema::TYPE_INTEGER,
            'created_by' => Schema::TYPE_INTEGER,
            'updated_at' => Schema::TYPE_INTEGER,
            'updated_by' => Schema::TYPE_INTEGER,
            // constrain
            'PRIMARY KEY ([[product_id]], [[uom_id]])',
            'FOREIGN KEY ([[product_id]]) REFERENCES {{%product}} ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
            'FOREIGN KEY ([[uom_id]]) REFERENCES {{%uom}} ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
            ], $tableOptions);

        $this->createTable('{{%product_stock}}', [
            'warehouse_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'product_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'qty' => Schema::TYPE_INTEGER . ' NOT NULL',
            // history column
            'created_at' => Schema::TYPE_INTEGER,
            'created_by' => Schema::TYPE_INTEGER,
            'updated_at' => Schema::TYPE_INTEGER,
            'updated_by' => Schema::TYPE_INTEGER,
            // constrain
            'PRIMARY KEY ([[warehouse_id]], [[product_id]])',
            'FOREIGN KEY ([[warehouse_id]]) REFERENCES {{%warehouse}} ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
            'FOREIGN KEY ([[product_id]]) REFERENCES {{%product}} ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
            ], $tableOptions);

        $this->createTable('{{%vendor}}', [
            'id' => Schema::TYPE_PK,
            'type' => Schema::TYPE_INTEGER . ' NOT NULL',
            'code' => Schema::TYPE_STRING . '(8) NOT NULL',
            'name' => Schema::TYPE_STRING . '(64) NOT NULL',
            'contact_name' => Schema::TYPE_STRING . '(64)',
            'contact_number' => Schema::TYPE_STRING . '(64)',
            'status' => Schema::TYPE_INTEGER . ' NOT NULL',
            // history column
            'created_at' => Schema::TYPE_INTEGER,
            'created_by' => Schema::TYPE_INTEGER,
            'updated_at' => Schema::TYPE_INTEGER,
            'updated_by' => Schema::TYPE_INTEGER,
            ], $tableOptions);

        $this->createTable('{{%product_vendor}}', [
            'product_id' => Schema::TYPE_INTEGER,
            'vendor_id' => Schema::TYPE_INTEGER,
            // history column
            'created_at' => Schema::TYPE_INTEGER,
            'created_by' => Schema::TYPE_INTEGER,
            'updated_at' => Schema::TYPE_INTEGER,
            'updated_by' => Schema::TYPE_INTEGER,
            // constrain
            'PRIMARY KEY ([[product_id]], [[vendor_id]])',
            'FOREIGN KEY ([[product_id]]) REFERENCES {{%product}} ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
            'FOREIGN KEY ([[vendor_id]]) REFERENCES {{%vendor}} ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
            ], $tableOptions);

        $this->createTable('{{%vendor_detail}}', [
            'id' => Schema::TYPE_INTEGER,
            'distric_id' => Schema::TYPE_INTEGER,
            'addr1' => Schema::TYPE_STRING . '(128)',
            'addr2' => Schema::TYPE_STRING . '(128)',
            'latitude' => Schema::TYPE_FLOAT,
            'longtitude' => Schema::TYPE_FLOAT,
            'kab_id' => Schema::TYPE_INTEGER,
            'kec_id' => Schema::TYPE_INTEGER,
            'kel_id' => Schema::TYPE_INTEGER,
            // history column
            'created_at' => Schema::TYPE_INTEGER,
            'created_by' => Schema::TYPE_INTEGER,
            'updated_at' => Schema::TYPE_INTEGER,
            'updated_by' => Schema::TYPE_INTEGER,
            // constrain
            'PRIMARY KEY ([[id]])',
            'FOREIGN KEY ([[id]]) REFERENCES {{%vendor}} ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
            ], $tableOptions);

        $this->createTable('{{%price_category}}', [
            'id' => Schema::TYPE_PK,
            'name' => Schema::TYPE_STRING . '(64) NOT NULL',
            'formula' => Schema::TYPE_STRING . '(256)',
            // history column
            'created_at' => Schema::TYPE_INTEGER,
            'created_by' => Schema::TYPE_INTEGER,
            'updated_at' => Schema::TYPE_INTEGER,
            'updated_by' => Schema::TYPE_INTEGER,
            ], $tableOptions);

        $this->createTable('{{%price}}', [
            'product_id' => Schema::TYPE_INTEGER,
            'price_category_id' => Schema::TYPE_INTEGER,
            'price' => Schema::TYPE_FLOAT,
            // history column
            'created_at' => Schema::TYPE_INTEGER,
            'created_by' => Schema::TYPE_INTEGER,
            'updated_at' => Schema::TYPE_INTEGER,
            'updated_by' => Schema::TYPE_INTEGER,
            // constrain
            'PRIMARY KEY ([[product_id]], [[price_category_id]])',
            'FOREIGN KEY ([[product_id]]) REFERENCES {{%product}} ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
            'FOREIGN KEY ([[price_category_id]]) REFERENCES {{%price_category}} ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
            ], $tableOptions);

        $this->createTable('{{%cogs}}', [
            'product_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'cogs' => Schema::TYPE_FLOAT . ' NOT NULL',
            'last_purchase_price' => Schema::TYPE_FLOAT,
            // history column
            'created_at' => Schema::TYPE_INTEGER,
            'created_by' => Schema::TYPE_INTEGER,
            'updated_at' => Schema::TYPE_INTEGER,
            'updated_by' => Schema::TYPE_INTEGER,
            // constrain
            'PRIMARY KEY ([[product_id]])',
            'FOREIGN KEY ([[product_id]]) REFERENCES {{%product}} ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
            ], $tableOptions);

        $this->createTable('{{%user_to_branch}}', [
            'branch_id' => Schema::TYPE_INTEGER,
            'user_id' => Schema::TYPE_INTEGER,
            // history column
            'created_at' => Schema::TYPE_INTEGER,
            'created_by' => Schema::TYPE_INTEGER,
            'updated_at' => Schema::TYPE_INTEGER,
            'updated_by' => Schema::TYPE_INTEGER,
            // constrain
            'PRIMARY KEY ([[branch_id]], [[user_id]])',
            'FOREIGN KEY ([[branch_id]]) REFERENCES {{%branch}} ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
            ], $tableOptions);

        $this->createTable('{{%user_to_warehouse}}', [
            'warehouse_id' => Schema::TYPE_INTEGER,
            'user_id' => Schema::TYPE_INTEGER,
            // history column
            'created_at' => Schema::TYPE_INTEGER,
            'created_by' => Schema::TYPE_INTEGER,
            'updated_at' => Schema::TYPE_INTEGER,
            'updated_by' => Schema::TYPE_INTEGER,
            // constrain
            'PRIMARY KEY ([[warehouse_id]], [[user_id]])',
            'FOREIGN KEY ([[warehouse_id]]) REFERENCES {{%warehouse}} ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
            ], $tableOptions);
    }

    public function safeDown()
    {
        //$this->dropTable('{{%user_to_warehouse}}');
        $this->dropTable('{{%user_to_branch}}');
        $this->dropTable('{{%cogs}}');
        $this->dropTable('{{%price}}');
        $this->dropTable('{{%price_category}}');
        $this->dropTable('{{%product_vendor}}');
        $this->dropTable('{{%product_stock}}');
        $this->dropTable('{{%product_uom}}');
        $this->dropTable('{{%product_child}}');
        $this->dropTable('{{%product}}');
        $this->dropTable('{{%product_group}}');
        $this->dropTable('{{%category}}');
        $this->dropTable('{{%uom}}');
        $this->dropTable('{{%vendor_detail}}');
        $this->dropTable('{{%vendor}}');
        $this->dropTable('{{%warehouse}}');
        //$this->dropTable('{{%branch}}');
        //$this->dropTable('{{%orgn}}');
    }
}
