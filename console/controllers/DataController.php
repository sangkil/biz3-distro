<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;
use yii\helpers\Console;

/**
 * Description of DataController
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class DataController extends Controller
{
    public $deleteOld = false;
    public $warehouse = 1;

    public function actionConvert()
    {
        $lines = [
            '<?php',
            'return ['
        ];
        $dirname = __DIR__ . '/data/';

        $files = [
            'products1.txt',
            'products2.txt',
            'products3.txt',
            'products4.txt',
        ];

        $categories = [
            'FOOTWERE' => 1,
            'APAREL' => 2,
            'HEADWERE' => 3,
        ];
        $id = 1; // autoincrement product id
        foreach ($files as $file) {
            $file = file($dirname . $file);
            unset($file[0]); // unset header
            foreach ($file as $line) {
                $line = explode("\t", $line);
                if (empty(trim($line[3]))) {
                    continue;
                }
                $row = [$id++]; // id
                $row[] = $categories[trim($line[0])]; // categori
                $row[] = "'" . str_replace(' ', '', $line[1]) . "'"; // barcode
                $row[] = "'" . str_replace(['\\', '\''], ['\\\\', '\\\''], $line[5]) . "'"; // nama panjang

                $h = str_replace(['Rp ', ','], ['', ''], trim($line[6]));
                $row[] = $h && $h != '-' ? $h : 0; // harga jual
                $h = str_replace(['Rp ', ','], ['', ''], trim($line[7]));
                $row[] = $h && $h != '-' ? $h : 0; // harga modal
                $h = str_replace(['Rp ', ','], ['', ''], trim($line[9]));
                $row[] = $h && $h != '-' ? $h : 0; // harga net

                $row[] = $line[8]; // qty

                $lines[] = '    [' . implode(', ', $row) . '],';
            }
        }
        $lines[] = '];';
        file_put_contents($dirname . 'product.php', implode("\n", $lines));
    }

    public function actionMigrate()
    {
        if (!Console::confirm('Are you sure you want to create sample data. Old data will be lose')) {
            return self::EXIT_CODE_NORMAL;
        }

        $command = Yii::$app->db->createCommand();
        $sampleDir = __DIR__ . '/data';

        // TRUNCATE TABLE
        $command->delete('{{%product_stock}}')->execute();
        $command->delete('{{%gl_detail}}')->execute();
        $command->delete('{{%gl_header}}')->execute();
        $command->delete('{{%goods_movement_dtl}}')->execute();
        $command->delete('{{%goods_movement}}')->execute();
        $command->delete('{{%sales_dtl}}')->execute();
        $command->delete('{{%sales}}')->execute();
        $command->delete('{{%transfer_dtl}}')->execute();
        $command->delete('{{%transfer}}')->execute();
        $command->delete('{{%invoice_dtl}}')->execute();
        $command->delete('{{%invoice}}')->execute();
        $command->delete('{{%payment_dtl}}')->execute();
        $command->delete('{{%payment}}')->execute();

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

        $command->delete('{{%entri_sheet}}')->execute();
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
            $command->insert('{{%warehouse}}', $this->toAssoc($row, ['id', 'code', 'name']))->execute();
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
        $errors = [];
        foreach ($rows as $i => $line) {
            $row = [
                'id' => $line[0],
                'group_id' => 1,
                'category_id' => $line[1],
                'code' => $line[2],
                'name' => $line[3],
                'status' => 10,
            ];
            try {
                $command->insert('{{%product}}', $row)->execute();
            } catch (\Exception $e) {
                $row['msg'] = $e->getMessage();
                $errors[] = $row;
                continue;
            }

            // cogs
            $row = [
                'product_id' => $line[0],
                'cogs' => $line[5],
                'last_purchase_price' => $line[5]
            ];
            $command->insert('{{%cogs}}', $row)->execute();

            // price
            $row = [
                'product_id' => $line[0],
                'price_category_id' => 1,
                'price' => $line[4],
            ];
            $command->insert('{{%price}}', $row)->execute();

            Console::updateProgress($i + 1, $total);
        }
        $errorFile = Yii::getAlias('@runtime/data-migrate-error-' . date('His') . '.json');
        file_put_contents($errorFile, json_encode($errors));
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

        // entrisheet
        $rows = require $sampleDir . '/entri_sheet.php';
        $total = count($rows);
        echo "\ninsert table {{%entri_sheet}}\n";
        Console::startProgress(0, $total);
        foreach ($rows as $i => $row) {
            $command->insert('{{%entri_sheet}}', $this->toAssoc($row, ['id', 'code', 'name', 'd_coa_id', 'k_coa_id']))->execute();
            Console::updateProgress($i + 1, $total);
        }
        $command->resetSequence('{{%entri_sheet}}')->execute();
        Console::endProgress();

        // payment method
        $rows = require $sampleDir . '/payment_method.php';
        $total = count($rows);
        echo "\ninsert table {{%payment_method}}\n";
        Console::startProgress(0, $total);
        foreach ($rows as $i => $row) {
            $command->insert('{{%payment_method}}', $this->toAssoc($row, ['id', 'branch_id', 'method', 'coa_id', 'potongan',
                    'coa_id_potongan']))->execute();
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

    public function options($actionID)
    {
        return array_merge(parent::options($actionID), [
            'deleteOld', 'warehouse'
        ]);
    }

    public function optionAliases()
    {
        return[
            'd' => 'deleteOld',
            'w' => 'warehouse',
        ];
    }
}
