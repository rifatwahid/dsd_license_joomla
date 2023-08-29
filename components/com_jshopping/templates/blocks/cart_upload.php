<?php

$isSupportUpload = $this->upload_common_settings->is_allow_cart_page && $prod['is_allow_uploads'] && ($prod['is_unlimited_uploads'] || $prod['max_allow_uploads'] >= 1);
//$isSupportUpload = 1;
if ($isSupportUpload) : 

    $isMultiUpload = $prod['max_allow_uploads'] >= 2 || $prod['is_unlimited_uploads'];
    $maxFilesUploads = ($prod['is_unlimited_uploads']) ? INF : $prod['max_allow_uploads'];
    $uploadBlockNumber = $key_id;
    $sumOfQtyUpload = !empty($prod['uploadData']['qty']) ? array_sum($prod['uploadData']['qty']) : 0;
    $remainingQtyUpload = $prod['quantity'] - $sumOfQtyUpload;
?>
 
    <div class="nativeProgressUploads nativeProgressUploads--<?php echo $uploadBlockNumber; ?> mb-4" data-native-uploads-block-number="<?php echo $uploadBlockNumber; ?>"  style="<?php if (unserialize($prod['buttons'])['upload']){echo "display: none;";}?>">
    
        <?php if ($isMultiUpload) : ?>

            <div class="nativeMultiuploadProgressHeader">
                <div class="row">
                    <div class="col-md-6 align-self-center">
                        <div class="nativeMultiuploadProgressHeader__max">
							<?php if($maxFilesUploads != INF): ?>
								<span class="nativeMultiuploadProgressHeader__maxText"><?php echo JText::_('COM_SMARTSHOP_FILE_MAXIMUM') ?>: </span>
								<span class="nativeMultiuploadProgressHeader__maxNumber"><?php echo $maxFilesUploads;?></span>
							<?php endif; ?>
						</div>
                    </div>

                    <div class="col-md-6 align-self-center">
                        <div class="nativeMultiuploadProgressHeader__newUpload">
                            <a href="#" class="nativeMultiuploadProgressHeader__newUploadLink" onclick="uploadImage.addNewUpload('.nativeProgressUploads--<?php echo $uploadBlockNumber; ?>', event);">
                                <?php echo JText::_('COM_SMARTSHOP_ADD_FILE'); ?>
                            </a>
                        </div>
                    </div>
                </div>

                <?php if (!$prod['is_upload_independ_from_qty']) : ?>
                <div class="nativeMultiuploadProgressHeader__remainingInfo">
                    <span class="nativeMultiuploadProgressHeader__remainingText"><?php echo JText::_('COM_SMARTSHOP_REMAINING_QTY'); ?>: </span>
                    <span class="nativeMultiuploadProgressHeader__remainingQty"><?php echo $remainingQtyUpload; ?></span>
                </div>
                <?php endif; ?>
                
                <input type="hidden" class="remainingCurrentQty" name="nativeProgressUpload[remainingCurrentQty]" value="<?php echo $remainingQtyUpload; ?>">
                <input type="hidden" class="remainingTotalQty" name="nativeProgressUpload[remainingTotalQty]" value="<?php echo $prod['quantity']; ?>">
				<input type="hidden" name="nativeProgressUpload_allow_files_size" id="nativeProgressUpload_allow_files_size" value="<?php echo $this->upload_common_settings->allow_files_size;?>">
            </div>
        <?php endif; ?>
        
        <div class="row nativeProgressUploads__rows">
            <?php

            if (empty($prod['uploadData']['files'])) { 
                $prod['uploadData']['files'][] = ''; 
            } ;
            
            foreach($prod['uploadData']['files'] as $key => $files) :
                $uploadQtyNumb = !empty($prod['uploadData']['qty'][$key]) ? $prod['uploadData']['qty'][$key] : 0;
                $uploadPreviewFileName = !empty($prod['uploadData']['previews'][$key]) ? $prod['uploadData']['previews'][$key] : '';
                $uploadFileName = !empty($files) ? $files : '';
                $uploadDescriptionText = isset($prod['uploadData']['descriptions'][$key]) ? $prod['uploadData']['descriptions'][$key] : '';
                $hideClassForImageInfo = empty($uploadFileName) ? 'display--none' : '';
            ?>
                <!-- Upload box -->
                <div class="col-md-12 mb-4" data-native-upload-row-number="<?php echo $key; ?>">
                    <div class="nativeProgressUpload nativeProgressUpload--nouploaded" onclick="uploadImage.startUpload(Joomla.getOptions('link_to_ajax_upload_files'), uploadImage.afterUpload, this, event);">
                        <a href="#" class="nativeProgressUpload__btn">
                            <?php echo JText::_('COM_SMARTSHOP_MOD_UPLOAD'); ?>
                        </a>

                        <div class="nativeProgressUpload__progress"></div>
                        <div class="nativeProgressUpload-imageInfo <?php echo $hideClassForImageInfo; ?>">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="nativeProgressUpload-imageInfo__wrapper">
                                        <a href="/components/com_jshopping/files/files_upload/<?php echo $uploadFileName; ?>" target="_blank" class="nativeProgressUpload-imageInfo__link">
                                            <img src="/components/com_jshopping/files/files_upload/<?php echo $uploadPreviewFileName; ?>" alt="" class="nativeProgressUpload-imageInfo__img">
                                        </a>
                                        <div class="nativeProgressUpload-imageInfo__description">
                                            <a href="/components/com_jshopping/files/files_upload/<?php echo $uploadFileName; ?>" class="nativeProgressUpload-imageInfo__description-link" target="_blank"><?php echo $uploadFileName; ?></a>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-8 align-self-center">
                                    
                                    <div class="nativeProgressUpload-imageInfo__qty <?php echo (!$isMultiUpload) ? 'display--none' : ''; ?>">
                                        <input type="number" class="nativeProgressUpload-imageInfo__qtyInput" name="nativeProgressUpload[qty][]" min="0" onchange="shopCart.updateUploadImageQuantity(<?php echo $uploadBlockNumber; ?>);" value="<?php echo $uploadQtyNumb; ?>">
                                    </div>

                                    <div class="nativeProgressUpload-imageInfo__removeFile">
                                        <a href="#" class="nativeProgressUpload-imageInfo__removeFileLink" onclick="uploadImage.deleteUploadInCart('.nativeProgressUploads--<?php echo $uploadBlockNumber; ?>', <?php echo $key; ?>, event);">
                                            <?php echo JText::_('COM_SMARTSHOP_REMOVE_FILE'); ?>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <?php if ($this->upload_common_settings->upload_design == 1) : ?>
                                <input type="text" name="nativeProgressUpload[descriptions][]" class="nativeProgressUpload-imageInfo__describeInput" onchange="ajaxUpdateDescribeUploadImageInCart(<?php echo $uploadBlockNumber; ?>);" value="<?php echo $uploadDescriptionText; ?>" placeholder="<?php echo JText::_('COM_SMARTSHOP_UPLOAD_DESCRIPTION'); ?>">
                            <?php endif; ?>

                            <input type="hidden" name="nativeProgressUpload[previews][]" value="<?php echo $uploadPreviewFileName; ?>" class="nativeProgressUpload__imageInput">
                            <input type="hidden" name="nativeProgressUpload[files][]" value="<?php echo $uploadFileName; ?>" class="nativeProgressUpload__fileInput <?php IF ($prod['is_required_upload']){?>nativeProgressUpload__fileInput_is_required_upload<?}?>">
                        </div>
                    </div>
                </div>
                <!-- Upload box END -->
            <?php endforeach; ?>

        </div>

        <input type="hidden" class="nativeProgressUpload__isIndependFromQty isIndependFromQty" name="nativeProgressUpload[isProductIndependFromQty]" value="<?php echo $prod['is_upload_independ_from_qty']; ?>">
        <input type="hidden" class="numbOfMaxUploadsFiles" data-max-upload-files="<?php echo $maxFilesUploads; ?>">
    </div>

    <?php echo sprintJsTemplateForNativeUploadedFiles($isMultiUpload); ?>

<?php elseif (!$isSupportUpload && !empty($prod['uploadData'])) : ?>
	<div class="cartUploadedeDataForNonSupportUpload">
		<?php echo sprintPreviewNativeUploadedFiles($prod['uploadData']); ?>
	</div>
<?php endif; ?>