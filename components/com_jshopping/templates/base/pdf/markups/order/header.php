<div class="header">
    <?php if ($isHeaderImgExists) : ?>
        <div class="header__image">
            <img src="<?php echo $urlToImg; ?>" width="<?php echo $imgWidth . $imgUnit; ?>" height="<?php echo $imgHeight . $imgUnit; ?>"/>
        </div>
    <?php else : ?>
        <br>
        <br>
        <!-- Vendor info -->
        <div class="vendorInfo">
            <?php if (!empty($vendorInfo->company_name)) : ?>
                <div class="vendorInfo__item vendorInfo__companyName">
                    <?php echo $vendorInfo->company_name; ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($vendorInfo->adress)) : ?>
                <div class="vendorInfo__item vendorInfo__address">
                    <?php echo $vendorInfo->adress; ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($vendorInfo->zip) || !empty($vendorInfo->city)) : ?>
                <div class="vendorInfo__item vendorInfo__ZipCity">
                    <?php echo $vendorInfo->zip  . ' ' . $vendorInfo->city; ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($vendorInfo->phone)) : ?>
                <div class="vendorInfo__item vendorInfo__phone">
                    <?php echo JText::_('COM_SMARTSHOP_CONTACT_PHONE') . ': ' . $vendorInfo->phone; ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($vendorInfo->fax)) : ?>
                <div class="vendorInfo__item vendorInfo__fax">
                    <?php echo JText::_('COM_SMARTSHOP_CONTACT_FAX') . ': ' . $vendorInfo->fax; ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($vendorInfo->email)) : ?>
                <div class="vendorInfo__item vendorInfo__email">
                    <?php echo JText::_('COM_SMARTSHOP_EMAIL') . ': ' . $vendorInfo->email; ?>
                </div>
            <?php endif; ?>
        </div>
            
    <?php endif; ?>
</div>
