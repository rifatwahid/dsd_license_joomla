<?php
defined('_JEXEC') or die('Restricted access');

$productCharacteristicsTableStyle = ($this->isPageWithAdditionalValues && empty($this->product->is_use_additional_characteristics)) ? 'display: none;' : '';
?>

<div id="product_extra_fields" class="tab-pane">
    <?php if ($this->isPageWithAdditionalValues) : ?>
		<div class="jshops_edit extrafields_edit">
			<div class="form-group row align-items-center">
				<label for="is_use_additional_characteristics" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
					<?php echo JText::_('COM_SMARTSHOP_USE_ADDITIONAL_CHARACTERISTICS'); ?>
				</label>
				<div class="col-sm-9 col-md-10 col-xl-10 col-12">
					<input type="hidden" name="is_use_additional_characteristics" id="is_use_additional_characteristics" value="0" checked>
					<input type="checkbox" name="is_use_additional_characteristics" class="form-check-input" value="1" <?php if ($this->product->is_use_additional_characteristics) { echo 'checked'; } ?> onclick="shopHelper.showHideByChecked(this, '#product_extra_fields .admintable');">
				</div>
			</div>
		</div>
	<?php else : ?>
		<input type="hidden" name="is_use_additional_characteristics" value="1" checked>
	<?php endif; ?>

    <div class="col100 admintable" id="extra_fields_space" style="<?php echo $productCharacteristicsTableStyle; ?>">
        <?php 
            echo $this->tmpl_extra_fields;
            $pkey = 'plugin_template_extrafields'; 
            
            if (!empty($this->$pkey)) { 
                echo $this->$pkey;
            }
        ?>
    </div>
    
    <div class="clr"></div>
</div>