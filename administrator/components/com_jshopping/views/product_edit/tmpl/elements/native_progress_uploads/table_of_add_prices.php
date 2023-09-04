<div class="table-responsive">
<table id="nativeProgressUploadsAddPrices" class="table table-striped">
    <thead class="nativeProgressUploadsAddPrices__head">
        <tr>
            <th width="20%">
                <?php echo JText::_('COM_SMARTSHOP_PRODUCT_QUANTITY_START'); ?>
            </th>
            <th  width="20%">
                <?php echo JText::_('COM_SMARTSHOP_PRODUCT_QUANTITY_FINISH'); ?>
            </th>
            <th  width="22%">
                <?php echo JText::_('COM_SMARTSHOP_DISCOUNT');?>
            </th>
            <th  width="22%">
                <?php echo JText::_('COM_SMARTSHOP_PRODUCT_PRICE'); ?>
            </th>
            <th  width="16%">
                <?php echo JText::_('COM_SMARTSHOP_DELETE'); ?>
            </th>
        </tr>
    </thead>

    <tbody class="nativeProgressUploadsAddPrices__body">
        <?php if (!empty($nativeUploadPrices)) :
                $rowNumber = 1; 
                foreach($nativeUploadPrices as $uploadPrice) :
            ?>

                <tr class="nativeProgressUploadsAddPrices__row" data-row-id="<?php echo $rowNumber; ?>">
                    <td class="nativeProgressUploadsAddPrices__row-from">
                        <input type="text" name="nativeProgressUploads[prices][updates][<?php echo $rowNumber; ?>][from_item]" value="<?php echo $uploadPrice->from_item ?? 0; ?>" class="small3 w-50 form-control nativeProgressUploadsAddPrices__row-item nativeProgressUploadsAddPrices__from-el">
                    </td>
                    <td class="nativeProgressUploadsAddPrices__row-to">
                        <input type="text" name="nativeProgressUploads[prices][updates][<?php echo $rowNumber; ?>][to_item]" value="<?php echo $uploadPrice->to_item ?? 0; ?>" class="small3 w-50 form-control nativeProgressUploadsAddPrices__row-item nativeProgressUploadsAddPrices__to-el">
                    </td>
                    <td class="nativeProgressUploadsAddPrices__row-percent">
                        <input type="text" name="nativeProgressUploads[prices][updates][<?php echo $rowNumber; ?>][percent]" value="<?php echo $uploadPrice->percent ?? 0; ?>" class="small3 w-50 form-control nativeProgressUploadsAddPrices__row-item nativeProgressUploadsAddPrices__percent-el" onchange="AdminShopNativeUploads.clearPriceOrPercent(<?php echo $rowNumber; ?>, true); AdminShopNativeUploads.updateCalculatedPrice(<?php echo $rowNumber; ?>, this.value);" value="0">
                    </td>
                    <td class="nativeProgressUploadsAddPrices__row-price">
                        <input type="text" name="nativeProgressUploads[prices][updates][<?php echo $rowNumber; ?>][price]" value="<?php echo $uploadPrice->price ?? 0; ?>" class="small3 w-50 form-control nativeProgressUploadsAddPrices__row-item nativeProgressUploadsAddPrices__price-el"  onchange="AdminShopNativeUploads.clearPriceOrPercent(<?php echo $rowNumber; ?>, false); AdminShopNativeUploads.updateCalculatedPrice(<?php echo $rowNumber; ?>, this.value);" value="0">
                    </td>
                    <td class="nativeProgressUploadsAddPrices__row-calculated-price display--none">
                        <input type="text" name="nativeProgressUploads[prices][updates][<?php echo $rowNumber; ?>][calculated_price]" value="<?php echo $uploadPrice->calculated_price ?: 0; ?>" class="small3 form-control nativeProgressUploadsAddPrices__row-item nativeProgressUploadsAddPrices__calculated-price-el">
                    </td>
                    <td class="nativeProgressUploadsAddPrices__row-delete">
                        <a href="#" class="btn btn-micro" onclick="AdminShopNativeUploads.deletePriceRow(<?php echo $rowNumber; ?>); return false;">
                            <i class="icon-delete"></i>
                        </a>
                    </td>
                </tr>
            
            <?php $rowNumber++; endforeach; endif; ?>
    </tbody>
</table>
    <table class="table table-striped">
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td align="right" width="100">
                <input class="btn button nativeProgressUploadsAddPrices__addPrice btn-primary" type="button" name="" onclick="AdminShopNativeUploads.addPriceRow(); return false;" value="Add price">
            </td>
        </tr>
</table>
</div>
