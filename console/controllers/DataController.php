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

    public function actionConvertQty()
    {
        $lines = [
            '<?php',
            'return ['
        ];
        $dirname = __DIR__ . '/data/';
        $file = $sampleDir.'/qty-awal.txt';
        $artikel_map = json_decode(file_get_contents($dirname . 'artikel_map.json'), true);

        $whs = [
            'BKT' => 4,
            'BUKITTINGGI' => 4,
            'A4SPORT' => 3,
            'LAKUAK' => 3,
            'PERMINDO' => 2,
        ];
        $contents = file($file);
        $i = 0;
        $errors = [];
        unset($contents[0]);
        foreach ($contents as $line) {
            echo $i++, "\t";
            $line = explode("\t", trim($line));

            $artikel = $line[1];
            if (isset($artikel_map[$artikel])) {
                $id = $artikel_map[$artikel];
            } else {
                $errors[$i] = $line;
                continue;
            }

            $row = [];
            $row[] = $whs[strtoupper($line[0])];
            $row[] = $id;
            $row[] = isset($line[2]) ? $line[2] : 0;

            $lines[] = '    [' . implode(', ', $row) . '],';
        }
        file_put_contents(Yii::getAlias('@runtime/qty-convert-err-' . date('Ymd-His') . '.json'), json_encode($errors, JSON_PRETTY_PRINT));
        $lines[] = '];';
        file_put_contents($dirname . 'qty_awal.php', implode("\n", $lines));
        echo "\n";
    }

    public function actionConvert()
    {
        $lines = [
            '<?php',
            'return ['
        ];
        $dirname = __DIR__ . '/data/';
        $file = '/home/mdmunir/Unduhan/master-barang-with-artikelsize.txt';

        $categories = [
            'FOOTWEAR' => 1,
            'FOOTWERE' => 1,
            'APPAREL' => 2,
            'HARDWARE' => 3,
            'HARDWEAR' => 3,
            'HEADWERE' => 3,
        ];
        /*
         * [1, "0001", "Adidas"],
          [2, "0002", "Nike"],
          [3, "0003", "Puma"],
          [4, "0004", "Specs"],
          [5, "0005", "Joma"],
         */
        $groups = [
            'adidas' => 1,
            'nike' => 2,
            'puma' => 3,
            'specs' => 4,
            'joma' => 5
        ];
        $id = 1; // autoincrement product id

        $contents = file($file);
        unset($contents[0]); // unset header
        $artikel = [];
        foreach ($contents as $line) {
            echo "$id \t";
            $line = explode("\t", trim($line));
            $row = [$id]; // id
            $row[] = $categories[strtoupper(trim($line[2]))]; // categori
            $row[] = "'" . str_replace([' ', '-'], ['', ''], $line[4]) . "'"; // barcode
            $row[] = json_encode($line[5] . ';' . $line[10]); // nama panjang

            $row[] = $line[8]; // harga jual
            $row[] = $line[9]; // harga modal
            //$row[] = $line[7]; // qty
            $row[] = $groups[strtolower($line[1])]; //

            $lines[] = '    [' . implode(', ', $row) . '],';
            $artikel[$line[10]] = $id;
            $id++;
        }

        $lines[] = '];';
        file_put_contents($dirname . 'product.php', implode("\n", $lines));
        file_put_contents($dirname . 'artikel_map.json', json_encode($artikel, JSON_PRETTY_PRINT));
    }

    public function actionBarcodeMap()
    {
        $dirname = __DIR__ . '/data/';
        $file = '/home/mdmunir/Unduhan/master-barang-with-artikelsize.txt';
        $artikel_map = json_decode(file_get_contents($dirname . 'artikel_map.json'), true);
        $contents = file($file);
        unset($contents[0]); // unset header
        $barcode_map = [];
        $i = 1;
        foreach ($contents as $line) {
            echo $i++, "\t";
            $line = explode("\t", trim($line));
            $id = $artikel_map[$line[10]];
            $barcode_map[$id] = $line[3];
        }
        file_put_contents($dirname . 'barcode_map.json', json_encode($barcode_map, JSON_PRETTY_PRINT));
    }

    public function actionUpdateBarcode()
    {
        $dirname = __DIR__ . '/data/';
        $barcode_map = json_decode(file_get_contents($dirname . 'barcode_map.json'), true);
        $cmd = Yii::$app->db->createCommand();
        foreach ($barcode_map as $id => $barcode) {
            if(strlen($barcode) > 13){
                $barcode = substr($barcode, 0, -2);
            }
            $cmd->update('{{%product}}', ['code' => $barcode], ['id' => $id])->execute();
            echo $id, "\t";
        }
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
            $code = strlen($line[2]) <= 13 ? $line[2] : substr($line[2], 0, -2);
            $row = [
                'id' => $line[0],
                'group_id' => $line[6],
                'category_id' => $line[1],
                'code' => $code,
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

    public function actionMigrateQty()
    {
        $command = Yii::$app->db->createCommand();
        $sampleDir = __DIR__ . '/data';
        $command->delete('{{%product_stock}}')->execute();
//        $queryCheck = (new \yii\db\Query())
//            ->select(['qty'])
//            ->from('{{%product_stock}}')
//            ->where('warehouse_id=:wid and product_id=:pid');
        $rows = require $sampleDir . '/qty_awal.php';
        $total = count($rows);
        echo "\ninsert table {{%product_stock}}\n";
        Console::startProgress(0, $total);
        foreach ($rows as $i => $row) {
//            if ($queryCheck->params([':wid' => $row[0], ':pid' => $row[1]])->one()) {
//                $command->update('{{%product_stock}}', ['qty' => $row[2]], [
//                    'warehouse_id' => $row[0],
//                    'product_id' => $row[1],
//                ])->execute();
//            } else {
            $command->insert('{{%product_stock}}', $this->toAssoc($row, ['warehouse_id', 'product_id', 'qty',]))->execute();
//            }
            Console::updateProgress($i + 1, $total);
        }
        Console::endProgress();
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
