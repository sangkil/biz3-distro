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
        if (!Console::confirm('Are you sure you want to create sample data. Old data will be lose')) {
            return self::EXIT_CODE_NORMAL;
        }

        $command = Yii::$app->db->createCommand();
        $sampleDir = __DIR__ . '/samples/a4sport';

        // TRUNCATE TABLE
        $command->delete('{{%product_stock}}')->execute();

        $command->delete('{{%warehouse}}')->execute();
        $command->delete('{{%branch}}')->execute();
        $command->delete('{{%orgn}}')->execute();

        $command->delete('{{%vendor}}')->execute();

        $command->delete('{{%product_uom}}')->execute();
        $command->delete('{{%cogs}}')->execute();
        $command->delete('{{%price}}')->execute();
        $command->delete('{{%price_category}}')->execute();
        $command->delete('{{%product_child}}')->execute();
        $command->delete('{{%product}}')->execute();
        $command->delete('{{%product_group}}')->execute();
        $command->delete('{{%category}}')->execute();

        $command->delete('{{%uom}}')->execute();

        $command->delete('{{%coa}}')->execute();
        $command->delete('{{%payment_method}}')->execute();

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

        // customer
        $rows = require $sampleDir . '/vendor.php';
        $total = count($rows);
        echo "\ninsert table {{%vendor}}\n";
        Console::startProgress(0, $total);
        foreach ($rows as $i => $row) {
            $command->insert('{{%vendor}}', $this->toAssoc($row, ['id', 'type', 'code', 'name', 'contact_name',
                    'contact_number', 'status']))->execute();
            Console::updateProgress($i + 1, $total);
        }
        $command->resetSequence('{{%vendor}}')->execute();
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

        // price category
        $rows = require $sampleDir . '/price_category.php';
        $total = count($rows);
        echo "\ninsert table {{%price_category}}\n";
        Console::startProgress(0, $total);
        $pc_ids = [];
        foreach ($rows as $i => $row) {
            $pc_ids[] = $row[0];
            $command->insert('{{%price_category}}', $this->toAssoc($row, ['id', 'name']))->execute();
            Console::updateProgress($i + 1, $total);
        }
        $command->resetSequence('{{%price_category}}')->execute();
        Console::endProgress();

        // product
        $rows = require $sampleDir . '/product.php';
        $total = count($rows);
        echo "\ninsert table {{%product}}\n";
        Console::startProgress(0, $total);
        foreach ($rows as $i => $row) {
            $row = $this->toAssoc($row, ['id', 'group_id', 'category_id', 'code', 'name', 'status','stockable']);
            $command->insert('{{%product}}', $row)->execute();
            
            // barcode
            /*
             * Skip for test
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
             *
             */

            // price
            /*
             * Skip for test
            $batch = [];
            $price = mt_rand(95, 150) * 1000;
            foreach ($pc_ids as $pc_id) {
                $batch[] = [$row['id'], $pc_id, $price - $pc_id * 3000];
            }
            $command->batchInsert('{{%price}}', ['product_id', 'price_category_id', 'price'], $batch)->execute();
             */

            // cogs
            /*
             * Skip for test
            $command->insert('{{%cogs}}', [
                'product_id' => $row['id'],
                'cogs' => $price * 0.65,
                'last_purchase_price' => $price - 20000,
                'created_at' => time(),
                'created_by' => 1,
            ])->execute();            
             *
             */
            Console::updateProgress($i + 1, $total);
        }
        $command->resetSequence('{{%product}}')->execute();
        Console::endProgress();

        // price
        $rows = require $sampleDir . '/price.php';
        $total = count($rows);
        echo "\ninsert table {{%price}}\n";
        Console::startProgress(0, $total);
        foreach ($rows as $i => $row) {
            $pc_ids[] = $row[0];
            $command->insert('{{%price}}', $this->toAssoc($row, ['product_id', 'price_category_id', 'price']))->execute();
            Console::updateProgress($i + 1, $total);
        }
        Console::endProgress();

        // cogs
        $rows = require $sampleDir . '/cogs.php';
        $total = count($rows);
        echo "\ninsert table {{%cogs}}\n";
        Console::startProgress(0, $total);
        foreach ($rows as $i => $row) {
            $pc_ids[] = $row[0];
            $command->insert('{{%cogs}}', $this->toAssoc($row, ['product_id', 'cogs', 'last_purchase_price']))->execute();
            Console::updateProgress($i + 1, $total);
        }
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

        // payment method
        $rows = require $sampleDir . '/payment_method.php';
        $total = count($rows);
        echo "\ninsert table {{%payment_method}}\n";
        Console::startProgress(0, $total);
        foreach ($rows as $i => $row) {
            $command->insert('{{%payment_method}}', $this->toAssoc($row, ['id', 'branch_id',
                    'method', 'coa_id']))->execute();
            Console::updateProgress($i + 1, $total);
        }
        $command->resetSequence('{{%payment_method}}')->execute();
        Console::endProgress();
    }

    protected function toAssoc($array, $fields, $time = true)
    {
        $result = [];
        foreach ($fields as $i => $field) {
            $result[$field] = $array[$i];
        }
        if ($time) {
            return array_merge([
                'created_at' => time(),
                'created_by' => 1,
                ], $result);
        }
        return $result;
    }
}
