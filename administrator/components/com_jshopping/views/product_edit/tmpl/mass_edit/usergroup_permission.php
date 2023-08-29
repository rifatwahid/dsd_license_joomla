<?php

use Joomla\CMS\Language\Text;

?>

<div class="form-group row align-items-center">
    <div class="admintable form-horizontal">
        <div class="control-group">
            <div class="control-label name font-weight-bold fw-bold text-uppercase col-form-label">
                <?php echo Text::_('COM_SMARTSHOP_BATH_PRODUCT_EDIT_ACTION'); ?>		
            </div>

            <div class="control-label">
                <?php echo $this->usergroup_permission_action; ?>
            </div>
        </div>
    </div>

    <?php require __DIR__ . '/../usergroup_permissions.php'; ?>
</div>
