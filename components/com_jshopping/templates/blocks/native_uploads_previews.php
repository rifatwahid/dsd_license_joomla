<?php

if (!empty($this->uploadData)) {
    $uploadData = $this->uploadData;
}

if (!empty($uploadData['files'])) : ?>
    <div class="native-uploads-previews mt-3">

        <?php foreach($uploadData['files'] as $key => $fileName) :
            if (empty($fileName)) {
                continue;
            }
        ?>
            <div class="native-uploads-preview mb-4">
                <div class="row">
                    <div class="col-md-5">
                        <div class="native-uploads-preview__imgInfo">
                            <a href="<?php print JURI::base() ?>components/com_jshopping/files/files_upload/<?php echo $uploadData['files'][$key]; ?>" target="_blank" class="native-uploads-preview__link">
                                <img src="<?php print JURI::base() ?>components/com_jshopping/files/files_upload/<?php echo $uploadData['previews'][$key]; ?>" class="native-uploads-preview__img">
                            </a>
                            <div class="native-uploads-preview__description">
                                <a href="<?php print JURI::base() ?>components/com_jshopping/files/files_upload/<?php echo $uploadData['files'][$key]; ?>" class="native-uploads-preview__description-link" target="_blank">
                                    <?php echo $uploadData['files'][$key]; ?>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-7 align-self-center">
                        <div class="native-uploads-preview__qty ">
                            <input type="text" class="native-uploads-preview__qtyInput important-display--none" value="<?php echo $uploadData['qty'][$key]; ?>" disabled>
                        </div>
                    </div>
                </div>

                <?php if (!empty($uploadData['descriptions'][$key])) : ?>
                    <input type="text" class="native-uploads-preview__describeInput" value="<?php echo $uploadData['descriptions'][$key]; ?>" disabled placeholder="<?php echo JText::_('COM_SMARTSHOP_UPLOAD_DESCRIPTION'); ?>">
                <?php endif; ?>
            </div>
        <?php endforeach;?>

    </div>
<?php endif; ?>