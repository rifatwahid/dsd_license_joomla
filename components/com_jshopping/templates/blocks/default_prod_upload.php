<?php 
    $isMultiUpload = $this->isMultiUpload;

if ($this->isSupportUpload && $this->product->isShowCartSection()) : ?>

    <div class="nativeProgressUploads nativeProgressUploads--0 mb-2" data-native-uploads-block-number="0"  style="<?php if ($this->show_buttons['upload']){echo "display: none;";}?>">
        
		<div class="nativeMultiuploadProgressHeader">                
			<?php if ($isMultiUpload) : ?>
				<div class="row">
                    <div class="col-md-6 align-self-center">
                        <div class="nativeMultiuploadProgressHeader__max">
							<?php if($this->maxFilesUploads != INF): ?>
								<span class="nativeMultiuploadProgressHeader__maxText"><?php echo JText::_('COM_SMARTSHOP_FILE_MAXIMUM') ?>: </span>
								<span class="nativeMultiuploadProgressHeader__maxNumber"><?php echo $this->maxFilesUploads;?></span>
							<?php endif; ?>
                        </div>
                    </div>

                    <div class="col-md-6 align-self-center">
                        <div class="nativeMultiuploadProgressHeader__newUpload">
                            <a href="#" class="nativeMultiuploadProgressHeader__newUploadLink" onclick="uploadImage.addNewUpload('.nativeProgressUploads--0', event);">
                                <?php echo JText::_('COM_SMARTSHOP_ADD_FILE'); ?>
                            </a>
                        </div>
                    </div>
                </div>

                <?php if (!$this->product->is_upload_independ_from_qty) : ?>
                    <div class="nativeMultiuploadProgressHeader__remainingInfo">
                        <span class="nativeMultiuploadProgressHeader__remainingText"><?php echo JText::_('COM_SMARTSHOP_REMAINING_QTY'); ?>: </span>
                        <span class="nativeMultiuploadProgressHeader__remainingQty"><?php echo $this->default_count_product; ?></span>
                    </div>
                <?php endif; ?>
                
			<?php endif; ?>
			<input type="hidden" class="remainingCurrentQty" name="nativeProgressUpload[remainingCurrentQty]" value="<?php echo $this->default_count_product ; ?>">
			<input type="hidden" class="remainingTotalQty"  name="nativeProgressUpload[remainingTotalQty]" value="<?php echo $this->default_count_product; ?>">
		</div>
        
        <div class="row nativeProgressUploads__rows">
            <!-- Upload box -->
            <div class="col-md-12 mb-2" data-native-upload-row-number="0">
                <div class="nativeProgressUpload nativeProgressUpload--nouploaded" onclick="shopProductFreeAttributes.setData();uploadImage.startUpload(Joomla.getOptions('link_to_ajax_upload_files'), uploadImage.afterUpload, this, event);">
                    <a href="#" class="nativeProgressUpload__btn" style="<?php if ($this->show_buttons['upload']){echo "display: none;";}else{echo "display:block;";}?>">
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
                                    <div class="nativeProgressUpload-imageInfo__description">######</div>
                                </div>
                            </div>

                            <div class="col-md-8 align-self-center">

                                <div class="nativeProgressUpload-imageInfo__qty <?php echo (!$isMultiUpload) ? 'display--none' : ''; ?>">
                                <input type="number" class="nativeProgressUpload-imageInfo__qtyInput" name="nativeProgressUpload[qty][]" min="0" onchange="uploadImage.updateQuantity(0, this);shopProductFreeAttributes.setData();" value="<?php if (!$isMultiUpload) { echo $this->default_count_product; } ?>">
                                </div>
                                
                                <div class="nativeProgressUpload-imageInfo__removeFile">
                                    <a href="#" class="nativeProgressUpload-imageInfo__removeFileLink" onclick="shopProductFreeAttributes.setData();uploadImage.deleteUpload('.nativeProgressUploads--0', 0, event);">
                                        <?php echo JText::_('COM_SMARTSHOP_REMOVE_FILE'); ?>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <?php if ($this->upload_common_settings->upload_design == 1) : ?>
                            <input type="text" name="nativeProgressUpload[descriptions][]" class="nativeProgressUpload-imageInfo__describeInput" placeholder="<?php echo JText::_('COM_SMARTSHOP_UPLOAD_DESCRIPTION'); ?>">
                        <?php endif; ?>

                        <input type="hidden" name="nativeProgressUpload[previews][]" class="nativeProgressUpload__imageInput">
                        <input type="hidden" name="nativeProgressUpload[files][]" class="nativeProgressUpload__fileInput">
						<input type="hidden" name="nativeProgressUpload_allow_files_size" id="nativeProgressUpload_allow_files_size" value="<?php echo $this->upload_common_settings->allow_files_size;?>">
                    </div>
                </div>
            </div>
            <!-- Upload box END -->

        </div>

        <input type="hidden" class="nativeProgressUpload__isIndependFromQty isIndependFromQty" name="nativeProgressUpload[isProductIndependFromQty]" value="<?php echo $this->product->is_upload_independ_from_qty; ?>">
        <input type="hidden" class="numbOfMaxUploadsFiles" data-max-upload-files="<?php echo $this->maxFilesUploads; ?>">
    </div>

    <?php echo sprintJsTemplateForNativeUploadedFiles($isMultiUpload); ?>

<?php endif;?>