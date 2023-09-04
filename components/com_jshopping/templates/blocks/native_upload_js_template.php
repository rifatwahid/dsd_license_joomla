<?php
    if (isset($this->isMultiUpload)) {
        $isMultiUpload = $this->isMultiUpload;
    }

    if (isset($this->uploadCommonSettings)) {
        $uploadCommonSettings = $this->uploadCommonSettings;
    }
?>

<!-- Full native upload row template for JS -->
<template id="nativeProgressUploadRow">

    <div class="col-md-12 mb-2" data-native-upload-row-number="#">
        <div class="nativeProgressUpload nativeProgressUpload--nouploaded" onclick="shopProductFreeAttributes.setData();uploadImage.startUpload(Joomla.getOptions('link_to_ajax_upload_files'), uploadImage.afterUpload, this, event);">
            <a href="#" class="nativeProgressUpload__btn d-grid" style="<?php if ($this->show_buttons['upload']){echo "display: none;";}?>">
                <?php echo JText::_('COM_SMARTSHOP_MOD_UPLOAD'); ?>
            </a>

            <div class="nativeProgressUpload__progress"></div>
            <div class="nativeProgressUpload-imageInfo display--none">
                <div class="row">
                    <div class="col-md-4">
                        <div class="nativeProgressUpload-imageInfo__wrapper">
                            <a href="#" target="_blank" class="nativeProgressUpload-imageInfo__link">
                                <img src="/components/com_jshopping/files/img_shop_products/noimage.gif" alt="" class="nativeProgressUpload-imageInfo__img">
                            </a>
                            <div class="nativeProgressUpload-imageInfo__description">####</div>
                        </div>
                    </div>

                    <div class="col-md-8 align-self-center">

                        <div class="nativeProgressUpload-imageInfo__qty <?php echo (!$isMultiUpload) ? 'display--none' : ''; ?>">
                        <input type="number" class="nativeProgressUpload-imageInfo__qtyInput" name="nativeProgressUpload[qty][]" min="0" value="1" onchange="uploadImage.updateQuantity(1, this);shopProductFreeAttributes.setData();">
                        </div>

                        <div class="nativeProgressUpload-imageInfo__removeFile">
                            <a href="#" class="nativeProgressUpload-imageInfo__removeFileLink">
                                <?php echo JText::_('COM_SMARTSHOP_REMOVE_FILE'); ?>
                            </a>
                        </div>
                    </div>
                </div>

                <?php if ($uploadCommonSettings->upload_design == 1) : ?>
                    <input type="text" name="nativeProgressUpload[descriptions][]" class="nativeProgressUpload-imageInfo__describeInput" placeholder="<?php echo JText::_('COM_SMARTSHOP_UPLOAD_DESCRIPTION'); ?>">
                <?php endif; ?>

                <input type="hidden" name="nativeProgressUpload[previews][]" class="nativeProgressUpload__imageInput">
                <input type="hidden" name="nativeProgressUpload[files][]" class="nativeProgressUpload__fileInput">
				<input type="hidden" name="nativeProgressUpload_allow_files_size" id="nativeProgressUpload_allow_files_size" value="<?php echo $this->uploadCommonSettings->allow_files_size;?>">
            </div>
        </div>
    </div>

</template>
<!-- Full native upload row template for JS END -->