<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;
use yii\helpers\Console;

/**
 * Description of SampleDataController
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class SampleDataController extends Controller
{
    /**
     * @var string the default command action.
     */
    public $defaultAction = 'create';
    
    /**
     * Create sample data
     */
    public function actionCreate()
    {
        if(!Console::confirm('Are you sure you want to create sample data. Old data will be lose')){
            return self::EXIT_CODE_NORMAL;
        }
        
        $tableOptions = null;
        if (Yii::$app->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $command = Yii::$app->db->createCommand();
        $sampleDir = Yii::getAlias('@console/migrations/samples');

        // TRUNCATE TABLE
        $command->delete('{{%product_stock}}')->execute();

        $command->delete('{{%warehouse}}')->execute();
        $command->delete('{{%branch}}')->execute();
        $command->delete('{{%orgn}}')->execute();

        $command->delete('{{%supplier}}')->execute();
        $command->delete('{{%customer}}')->execute();

        $command->delete('{{%product_uom}}')->execute();
        $command->delete('{{%product_child}}')->execute();
        $command->delete('{{%product}}')->execute();
        $command->delete('{{%product_group}}')->execute();
        $command->delete('{{%category}}')->execute();

        $command->delete('{{%uom}}')->execute();

        $command->delete('{{%coa}}')->execute();

        // orgn
        $rows = require $sampleDir . '/orgn.php';
        $total = count($rows);
        echo "\ninsert table {{%orgn}}\n";
        Console::startProgress(0, $total);
        foreach ($rows as $i => $row) {
            $command->insert('{{%orgn}}', $this->toAssoc($row, ['id', 'code', 'name']))->execute();
            Console::updateProgress($i + 1, $total);
        }
        $command->resetSequence('{{%orgn}}')->execute();
        Console::endProgress();

        // branch
        $rows = require $sampleDir . '/branch.php';
        $total = count($rows);
        echo "\ninsert table {{%branch}}\n";
        Console::startProgress(0, $total);
        foreach ($rows as $i => $row) {
            $command->insert('{{%branch}}', $this->toAssoc($row, ['id', 'orgn_id', 'code', 'name']))->execute();
            Console::updateProgress($i + 1, $total);
        }
        $command->resetSequence('{{%branch}}')->execute();
        Console::endProgress();

        // warehouse
        $rows = require $sampleDir . '/warehouse.php';
        $total = count($rows);
        echo "\ninsert table {{%warehouse}}\n";
        Console::startProgress(0, $total);
        foreach ($rows as $i => $row) {
            $command->insert('{{%warehouse}}', $this->toAssoc($row, ['id', 'branch_id', 'code', 'name']))->execute();
            Console::updateProgress($i + 1, $total);
        }
        $command->resetSequence('{{%warehouse}}')->execute();
        Console::endProgress();

        // supplier
        $rows = require $sampleDir . '/supplier.php';
        $total = count($rows);
        echo "\ninsert table {{%supplier}}\n";
        Console::startProgress(0, $total);
        foreach ($rows as $i => $row) {
            $command->insert('{{%supplier}}', $this->toAssoc($row, ['id', 'code', 'name']))->execute();
            Console::updateProgress($i + 1, $total);
        }
        $command->resetSequence('{{%supplier}}')->execute();
        Console::endProgress();

        // customer
        $rows = require $sampleDir . '/customer.php';
        $total = count($rows);
        echo "\ninsert table {{%customer}}\n";
        Console::startProgress(0, $total);
        foreach ($rows as $i => $row) {
            $command->insert('{{%customer}}', $this->toAssoc($row, ['id', 'code', 'name', 'contact_name',
                    'contact_number', 'status']))->execute();
            Console::updateProgress($i + 1, $total);
        }
        $command->resetSequence('{{%customer}}')->execute();
        Console::endProgress();

        // product category
        $rows = require $sampleDir . '/category.php';
        $total = count($rows);
        echo "\ninsert table {{%category}}\n";
        Console::startProgress(0, $total);
        foreach ($rows as $i => $row) {
            $command->insert('{{%category}}', $this->toAssoc($row, ['id', 'code', 'name']))->execute();
            Console::updateProgress($i + 1, $total);
        }
        $command->resetSequence('{{%category}}')->execute();
        Console::endProgress();

        // product group
        $rows = require $sampleDir . '/product_group.php';
        $total = count($rows);
        echo "\ninsert table {{%product_group}}\n";
        Console::startProgress(0, $total);
        foreach ($rows as $i => $row) {
            $command->insert('{{%product_group}}', $this->toAssoc($row, ['id', 'code', 'name']))->execute();
            Console::updateProgress($i + 1, $total);
        }
        $command->resetSequence('{{%product_group}}')->execute();
        Console::endProgress();

        // product
        $rows = require $sampleDir . '/product.php';
        $total = count($rows);
        echo "\ninsert table {{%product}}\n";
        Console::startProgress(0, $total);
        foreach ($rows as $i => $row) {
            $row = $this->toAssoc($row, ['id', 'group_id', 'category_id', 'code', 'name', 'status']);
            $command->insert('{{%product}}', $row)->execute();
            // barcode
            $batch = [];
            for ($j = 0; $j < 3; $j++) {
                $rand = mt_rand(1000000, 9999999) . mt_rand(100000, 999999);
                $batch[] = [$rand, $row['id']];
            }
            try {
                $command->batchInsert('{{%product_child}}', ['barcode', 'product_id'], $batch)->execute();
            } catch (Exception $exc) {
                echo 'Error: ' . $exc->getMessage() . "\n";
            }
            Console::updateProgress($i + 1, $total);
        }
        $command->resetSequence('{{%product}}')->execute();
        Console::endProgress();

        // uom
        $rows = require $sampleDir . '/uom.php';
        $total = count($rows);
        echo "\ninsert table {{%uom}}\n";
        Console::startProgress(0, $total);
        foreach ($rows as $i => $row) {
            $command->insert('{{%uom}}', $this->toAssoc($row, ['id', 'code', 'name']))->execute();

            // product uom
            $sql = "insert into {{%product_uom}}([[product_id]],[[uom_id]],[[isi]])\n"
                . "select [[id]],{$row[0]},{$row[3]} from {{%product}}";
            $command->setSql($sql)->execute();
            Console::updateProgress($i + 1, $total);
        }
        $command->resetSequence('{{%uom}}')->execute();
        Console::endProgress();

        // coa
        $rows = require $sampleDir . '/coa.php';
        $total = count($rows);
        echo "\ninsert table {{%coa}}\n";
        Console::startProgress(0, $total);
        foreach ($rows as $i => $row) {
            $command->insert('{{%coa}}', $this->toAssoc($row, ['id', 'parent_id', 'code',
                    'name', 'type', 'normal_balance']))->execute();
            Console::updateProgress($i + 1, $total);
        }
        $command->resetSequence('{{%coa}}')->execute();
        Console::endProgress();
    }

    protected function toAssoc($array, $fields)
    {
        $result = [];
        foreach ($fields as $i => $field) {
            $result[$field] = $array[$i];
        }
        return $result;
    }

    
}
