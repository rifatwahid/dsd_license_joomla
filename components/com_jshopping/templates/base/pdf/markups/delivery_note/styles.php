<?php

$font = $fontData['font'];
$fontI = $fontData['fontI'];
$fontB = $fontData['fontB'];

// WARNING!!!!!!!!!!!!!!! I don`t recommend to optimize the css code!!!
?>

<style>
    * {
        font-size: 7px;
        font-family: <?php echo $font; ?>;
    }

    .vendorInfo__item {
        font-size: 8px;
        color: #999999;
        text-align: right;
    }

    .subheader__vendorAddress {
        font-size: 6px;
        color: #000000;
    }

    .subheader__billText {
        font-family: <?php echo $fontB; ?>;
        font-size: 11px;
        color: #000000;
        text-align: right;
    }

    .subheader__customerItem {
        font-size: 11px;
    }

    .subheader__orderDataItem {
        font-family: <?php echo $fontI; ?>;
        font-size: 11px;
        text-align: right;
    }

    .subheader__numbersTdText {
        font-size: 7px;
    }

    .subheader__numbersTdNumber {
        font-size: 7px;
        text-align: right;
    }

    .orderListHeader__column {
        font-family: <?php echo $fontB; ?>;
        font-size: 7.5px;
    }

    .orderListBody__prodName {
        font-size: 7px;
    }
    
    .orderListBody__prodAdditionalInfo {
        padding-left: 10px;
    }

    .orderListBody__prodManufact {
        font-size: 7px;
    }

    .orderListBody__prodAttrs {
        font-size: 6px;
    }

    .orderListBody__prodDeliveryTime {
        font-size: 6px;
    }

    .orderListBody__prodCode {
        font-size: 7px;
    }

    .orderListBody__prodQty {
        font-size: 7px;
    }

    .pdf-w100{
        width: 100px;
    }

    .pdf-w150{
        width: 150px;
    }

    .pdf-w200{
        width: 200px;
    }

    .pdf-w250{
        width: 250px;
    }

    .pdf-w300{
        width: 300px;
    }

    .pdf-w350 {
        width: 350px;
    }

    .pdf-w400 {
        width: 400px;
    }

    .page {}
</style>