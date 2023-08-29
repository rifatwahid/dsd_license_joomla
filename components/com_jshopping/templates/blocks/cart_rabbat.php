<?php if ($this->use_rabatt && !empty($this->products)) { ?> 
	<form id="discount cartDiscountForm" name="rabatt" method="post" action="<?php echo SEFLink('index.php?option=com_jshopping&controller=cart&task=discountsave'); ?>">
		<div class="form-row align-items-center">
			<label for="rabatt" class="h6 ps-4 pt-3 mb-3">
				<?php echo JText::_('COM_SMARTSHOP_HAVE_A_DISCOUNT_CODE'); ?>
			</label>
			<div class="d-grid gap-2 d-md-flex">
				<div class="col-md-8 mb-4">
					<input type="text" class="form-control mt-1" id="rabatt" name="rabatt" placeholder="<?php echo JText::_('COM_SMARTSHOP_CODE'); ?>">
				</div>

				<div class="col-md-4 mb-4">
					<button type="submit" class="btn btn-outline-primary mt-1 w-100">
						<?php echo JText::_('COM_SMARTSHOP_APPLY_DISCOUNT'); ?>
					</button>
				</div>
			</div>
		</div>
	</form>
<?php }else{ ?>
	<input type="hidden" name="rabatt" />
<?php } ?>