<?php

use yii\db\Migration;

/**
 * Handles the creation for table `stock_opname_check_table`.
 */
class m161115_045011_create_stock_opname_check_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('stock_opname_check', [
            'opname_id' => $this->integer(),
            'date' => $this->date(),
            'product_id' => $this->integer(),
            'uom_id' => $this->integer(),
            'qty' => $this->double(),
            // history column
            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
            // constrain
            'PRIMARY KEY ([[opname_id]], [[product_id]], [[date]])',
            'FOREIGN KEY ([[opname_id]]) REFERENCES {{stock_opname}} ([[id]]) ON DELETE RESTRICT ON UPDATE CASCADE',

        ]);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('stock_opname_check');
    }
}
