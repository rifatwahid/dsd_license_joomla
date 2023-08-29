<?php 
    $jshopConfig = $additionalData['jshopConfig'];
?>

<table class="orderList"  border="1" cellpadding="3">
    <!--- Titles --->
    <tr class="orderListHeader__row orderList__header orderListHeader" nobr="true">
        <?php include templateOverride('pdf/markups/' . $pdf::PDF_TYPE, 'note_table_header.php'); ?>
    </tr>
    <!--- Titles END --->
    
    <!--- Order products list --->
    <tbody class="orderList__body orderListBody">
        <?php include templateOverride('pdf/markups/' . $pdf::PDF_TYPE, 'note_table_items_loop.php'); ?>
    </tbody>
    <!--- Order products list END --->
</table>

<br>
<br>