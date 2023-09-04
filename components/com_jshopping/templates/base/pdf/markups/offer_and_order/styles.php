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

    .orderListBody__prodUploads {
        font-size: 7px;
    }

    .orderListBody__prodCode {
        font-size: 7px;
    }

    .orderListBody__prodQty {
        font-size: 7px;
    }

    .orderListBodyProdPrices__itemPrice {
        font-size: 7px;
    }

    .orderListBodyProdPrices__extPrice {
        font-size: 7px;
    }

    .orderListBodyProdPrices__taxPrice {
        font-size: 6px;
    }

    .orderListBodyProdPrices__basicPrice {
        font-size: 6px;
    }

    .orderListBodyProdTotalPrice__totalPrice {
        font-size: 7px;
    }

    .orderListBodyProdTotalPrice__extTotalPrice {
        font-size: 7px;
    }

    .orderListBodyProdTotalPrice__taxTotalPrice {
        font-size: 6px;
    }

    .orderListFooter__columnText {
        font-size: 10px;
        text-align: right;
    }

    .orderListFooter__columnPrice {
        font-size: 10px;
        text-align: right;
    }

    .orderListFooter__total {
        font-family: <?php echo $fontB; ?>;
        font-size: 10px;
    }

    .orderInfoPaymentInfo__text {
        font-family: <?php echo $fontB; ?>;
        font-size: 7px;
    }

    .orderInfoShippingInfo__text {
        font-family: <?php echo $fontB; ?>;
        font-size: 7px;
    }

    .subheaderOrderNowLink {
        color: #ff0000;
        font-size: 9px;
    }

    .subheaderCustomerValidTo {
        font-size: 9px;
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