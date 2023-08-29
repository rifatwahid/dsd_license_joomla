<?php

use Joomla\CMS\Language\Text;

$backUpisPageWithAdditionalValues = $this->isPageWithAdditionalValues;
$backupRows = $this->rows;

$this->isPageWithAdditionalValues = false;
$this->rows = $this->shippings;
?>

<div class="form-group row align-items-center">
    <label class="col-sm-3 col-md-2 col-xl-2 col-12 font-weight-bold fw-bold text-uppercase col-form-label">
        <?php echo Text::_('COM_SMARTSHOP_BATH_PRODUCT_EDIT_ACTION'); ?>
    </label>

    <div class="col-sm-9 col-md-10 col-xl-10 col-12">
        <?php echo $this->shippings_action; ?>
    </div>
</div>

<?php 

require __DIR__ . '/../product_shipping.php'; 

$this->isPageWithAdditionalValues = $backUpisPageWithAdditionalValues;
$this->rows = $backupRows;
?>
