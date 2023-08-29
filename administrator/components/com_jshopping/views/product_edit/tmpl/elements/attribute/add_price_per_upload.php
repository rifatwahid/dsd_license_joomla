<div class="form-group row align-items-center">
    <label for="attr_is_activated_price_per_consignment_upload" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label">
        <?php echo JText::_('COM_SMARTSHOP_NATIVE_PROGRESS_UPLOADS_PRICES'); ?>
    </label>

    <div class="col-sm-8 col-md-8 col-xl-8 col-12 table-responsive">
        <input type="hidden" name="attr_is_activated_price_per_consignment_upload" value="0">
        <input type="checkbox" class="attrnativeProgressUploadsAddPrices__checker" name="attr_is_activated_price_per_consignment_upload" id="attr_is_activated_price_per_consignment_upload" value="1" onclick="shopHelper.showHideByChecked(this, '#attrnativeUploadPricesWrapper', 'block')"/>

        <div id="attrnativeUploadPricesWrapper" class="form-group row align-items-center display--none">
            <div class="col-sm-3 col-md-2 col-xl-2 col-12"></div>
            <div class="table-responsive">
                <table id="attrnativeProgressUploadsAddPrices" class="table table-striped">
                    <thead class="attrnativeProgressUploadsAddPrices__head">
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

                    <tbody class="attrnativeProgressUploadsAddPrices__body">
                    </tbody>
                </table>

                <table class="table table-striped">
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td align="right" width="100">
                            <input class="btn button attrnativeProgressUploadsAddPrices__addPrice btn-primary" type="button" onclick="AdminShopNativeUploads.addPriceRow('#attrnativeProgressUploadsAddPrices .attrnativeProgressUploadsAddPrices__body', 'attr'); return false;" value="Add price">
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>