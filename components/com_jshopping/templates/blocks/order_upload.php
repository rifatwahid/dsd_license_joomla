<?php $_prod = $prod;
$isSupportUpload = $this->upload_common_settings->is_allow_cart_page && $_prod->is_allow_uploads && ($_prod->is_unlimited_uploads || $_prod->max_allow_uploads >= 1);

if ($isSupportUpload) :

    $isMultiUpload = $_prod->max_allow_uploads >= 2 || $_prod->is_unlimited_uploads;
    $maxFilesUploads = ($_prod->is_unlimited_uploads) ? INF : $_prod->max_allow_uploads;
    $uploadBlockNumber = $prod->order_item_id;
    $sumOfQtyUpload = !empty($_prod->uploadData['qty']) ? array_sum($_prod->uploadData['qty']) : 0;
    $remainingQtyUpload = $_prod->product_quantity - $sumOfQtyUpload;
    ?>

    <div class="nativeProgressUploads nativeProgressUploads--<?php echo $uploadBlockNumber; ?> mb-4" data-native-uploads-block-number="<?php echo $uploadBlockNumber; ?>"  style="<?php if (unserialize($_prod->buttons)['upload']){echo "display: none;";}?>">

        <?php if ($isMultiUpload) : ?>

            <div class="nativeMultiuploadProgressHeader">
                <div class="row">
                    <div class="col-md-6 align-self-center">
						<?php if($maxFilesUploads != INF): ?>
							<div class="nativeMultiuploadProgressHeader__max">
								<span class="nativeMultiuploadProgressHeader__maxText"><?php echo JText::_('COM_SMARTSHOP_FILE_MAXIMUM') ?>: </span>
								<span class="nativeMultiuploadProgressHeader__maxNumber"><?php echo $maxFilesUploads;?></span>
							</div>
						<?php endif; ?>
                    </div>

                    <div class="col-md-6 align-self-center">
                        <div class="nativeMultiuploadProgressHeader__newUpload">
                            <a href="#" class="nativeMultiuploadProgressHeader__newUploadLink" onclick="uploadImage.addNewUploadOrder('.nativeProgressUploads--<?php echo $uploadBlockNumber; ?>', event, 1);">
                                <?php echo JText::_('COM_SMARTSHOP_ADD_FILE'); ?>
                            </a>
                        </div>
                    </div>
                </div>

                <?php if (!$_prod->is_upload_independ_from_qty) : ?>
                    <div class="nativeMultiuploadProgressHeader__remainingInfo">
                        <span class="nativeMultiuploadProgressHeader__remainingText"><?php echo JText::_('COM_SMARTSHOP_REMAINING_QTY'); ?>: </span>
                        <span class="nativeMultiuploadProgressHeader__remainingQty"><?php echo $remainingQtyUpload; ?></span>
                    </div>
                <?php endif; ?>

                <input type="hidden" class="remainingCurrentQty" name="nativeProgressUpload[remainingCurrentQty]" value="<?php echo $remainingQtyUpload; ?>">
                <input type="hidden" class="remainingTotalQty" name="nativeProgressUpload[remainingTotalQty]" value="<?php echo $_prod->product_quantity; ?>">
                <input type="hidden" name="nativeProgressUpload_allow_files_size" id="nativeProgressUpload_allow_files_size" value="<?php echo $this->upload_common_settings->allow_files_size;?>">
            </div>
        <?php endif; ?>

        <div class="row nativeProgressUploads__rows">
            <?php

            if (empty($_prod->uploadData['files'])) {
                $_prod->uploadData['files'][] = '';
            } ;

            foreach($_prod->uploadData['files'] as $key => $files) :
                $uploadQtyNumb = !empty($_prod->uploadData['qty'][$key]) ? $_prod->uploadData['qty'][$key] : 0;
                $uploadPreviewFileName = !empty($_prod->uploadData['previews'][$key]) ? $_prod->uploadData['previews'][$key] : '';
                $uploadFileName = !empty($files) ? $files : '';
                $uploadDescriptionText = isset($_prod->uploadData['descriptions'][$key]) ? $_prod->uploadData['descriptions'][$key] : '';
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
                                        <input type="number" class="nativeProgressUpload-imageInfo__qtyInput" name="nativeProgressUpload[uploads][<?php echo $uploadBlockNumber; ?>][qty][]" min="0" onchange="shopCart.updateUploadImageQuantity(<?php echo $uploadBlockNumber; ?>);" value="<?php echo $uploadQtyNumb; ?>">
                                    </div>

                                    <div class="nativeProgressUpload-imageInfo__removeFile">
                                        <a href="#" class="nativeProgressUpload-imageInfo__removeFileLink" onclick="uploadImage.deleteUploadInOrder('.nativeProgressUploads--<?php echo $uploadBlockNumber; ?>', <?php echo $key; ?>, event);">
                                            <?php echo JText::_('COM_SMARTSHOP_REMOVE_FILE'); ?>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <?php if ($this->upload_common_settings->upload_design == 1) : ?>
                                <input type="text" name="nativeProgressUpload[uploads][<?php echo $uploadBlockNumber; ?>][descriptions][]" class="nativeProgressUpload-imageInfo__describeInput" onchange="ajaxUpdateDescribeUploadImageInCart(<?php echo $uploadBlockNumber; ?>);" value="<?php echo $uploadDescriptionText; ?>" placeholder="<?php echo JText::_('COM_SMARTSHOP_UPLOAD_DESCRIPTION'); ?>">
                            <?php endif; ?>

                            <input type="hidden" name="nativeProgressUpload[uploads][<?php echo $uploadBlockNumber; ?>][previews][]" value="<?php echo $uploadPreviewFileName; ?>" class="nativeProgressUpload__imageInput">
                            <input type="hidden" name="nativeProgressUpload[uploads][<?php echo $uploadBlockNumber; ?>][files][]" value="<?php echo $uploadFileName; ?>" class="nativeProgressUpload__fileInput">
                        </div>
                    </div>
                </div>

                <?php echo sprintJsTemplateForNativeUploadedOrderFiles($isMultiUpload, $uploadBlockNumber); ?>
                <!-- Upload box END -->
            <?php endforeach; ?>

        </div>

        <input type="hidden" class="nativeProgressUpload__isIndependFromQty isIndependFromQty" name="nativeProgressUpload[isProductIndependFromQty]" value="<?php echo $_prod->is_upload_independ_from_qty; ?>">
        <input type="hidden" class="numbOfMaxUploadsFiles" data-max-upload-files="<?php echo $maxFilesUploads; ?>">
    </div>


<?php elseif (!$isSupportUpload && !empty($_prod->uploadData)) : ?>
    <div class="cartUploadedeDataForNonSupportUpload">
        <?php echo sprintPreviewNativeUploadedFiles($_prod->uploadData); ?>
    </div>
<?php endif; ?>