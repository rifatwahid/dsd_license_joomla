<?php
/**
* @version 1.0 CA Smartshop BS4
*/
defined('_JEXEC') or die('Restricted access');
?>

<?php if ($this->jshopConfig->show_comment_box) :?>
	<div class="form-group mt-4">
		<label for="order_add_info" class="ml-4">
			<?php echo JText::_('COM_SMARTSHOP_ORDER_COMMENT'); ?>
		</label>

		<textarea class="form-control" id="order_add_info" name="order_add_info" rows="3"></textarea>
	</div>
<?php endif; ?>

<?php if ($this->no_return || $this->products_return) : ?>
	<div class="form-check d-flex">
		<div class="mr-1">
			<input class="form-check-input" type="checkbox" name="no_return" id="no_return" />
		</div>

		<div class="mb-3">
			<label class="form-check-label d-block row_no_return" for="no_return">
				<?php echo JText::_('COM_SMARTSHOP_NO_RETURN_DESCRIPTION'); ?>
			</label>
		</div>
	</div>
<?php endif; ?>

<?php if ($this->jshopConfig->display_agb) : ?>
	<div class="form-check d-flex row_agb">
		<div class="mr-1">
			<input class="form-check-input" type="checkbox" name="agb" id="agb" />
		</div>

		<div class="mb-3">
			<label class="form-check-label d-block" for="agb">
				<?php echo JText::_('COM_SMARTSHOP_AGB_AND_RETURN_POLICY'); ?>
			</label>
		</div>
	</div>
<?php endif; ?>

<?php print $this->_tmpl_address_html ?? ''; ?>

<input type="submit" name="save" id="submitCheckout" value="<?php echo $this->submitOrderText ?? JText::_('COM_SMARTSHOP_SUBMIT_ORDER'); ?>" class="btn btn-outline-primary d-grid col-md-6 float-end mt-2" />
