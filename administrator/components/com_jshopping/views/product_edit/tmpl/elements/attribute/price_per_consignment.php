<?php 

$pathToFile = __DIR__ . '/../price_per_consignment/price_per_consignment.php';
$data = new stdClass();
$attrConsignmentPrefix = 'attr_consignment_';

echo getContentOfFile($pathToFile, [
    'row' => $data,
    'consignmentPrefix' => $attrConsignmentPrefix,
    'attr_price_per_consignment_basic_price_unit_id' => $lists['attr_price_per_consignment_basic_price_unit_id'] ?? '',
    'productIsAddPriceLabelClass' => 'col-sm-4 col-md-4 col-xl-4 col-12 col-form-label',
    'productIsAddPriceCheckboxRowClass' => 'col-sm-8 col-md-8 col-xl-8 col-12',
    'classNameOfLabelRowAttrAddprice' => 'col-sm-4 col-md-4 col-xl-4 col-12 col-form-label',
    'classNameOfTableRowAttrAddprice' => 'col-sm-8 col-md-8 col-xl-8 col-12 table-responsive'
]);
