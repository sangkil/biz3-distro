<?php
/*
 * Create By Mujib Masyhudi <mujib.masyhudi@gmail.com>
 * Create at {date('now')}
 */
?>
<table class="table table-hover" style="width: 60%;">
    <thead>
        <tr>
            <th style="width: 10%;">No</th>
            <th>Price Category</th>
            <th>Sales Price</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $row = '';
        $i = 1;
        foreach ($product_prices as $dprc) {
            $row .= '<tr>';
            $row .= '<td>' . $i . '</td>';
            $row .= '<td>' . $dprc->priceCategory->name . '</td>';
            $row .= '<td>' . $dprc->price . '</td>';
            $row .= '</tr>';
            $i++;
        }
        echo $row;
        ?> 
    </tbody>
</table>

