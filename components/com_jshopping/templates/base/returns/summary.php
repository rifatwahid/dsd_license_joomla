<form action="<?php echo SEFLink('index.php?option=com_jshopping&controller=returns&task=save') ?>" id="updateCartForm" method="post" name="returnOrder">

	<div class="row pb-2 pt-4">
		<h3><?php print JText::_('COM_SMARTSHOP_RETURN_SUMMARY'); ?></h3>
	</div>
	<div class="row">
		<div class="col-9">
			<?php foreach($this->products as $k=>$prod) : ?>
				<?php include templateOverrideBlock('blocks', 'summary_package.php'); ?>			 
					
			<?php endforeach; ?>
			</div>
		</div>
			<div class="row">
				<div class="d-end col-md-5 col-lg-5">
					<a href="<?php echo SEFLink('index.php?option=com_jshopping&controller=returns&task=start&order_id='.$this->order_id) ?>" class="btn btn-outline-secondary w-100" ><?php echo JText::_('COM_SMARTSHOP_TO_EDIT_RETURN'); ?></a>
				</div>
			</div>
			<div class="row">
				<div class="d-end col-md-5 col-lg-5 pt-3">
					<input type="submit" class="btn btn-outline-secondary w-100" value="<?php echo JText::_('COM_SMARTSHOP_SEND_RETURN_REQUEST'); ?>" />
				</div>
			</div>
	</form>