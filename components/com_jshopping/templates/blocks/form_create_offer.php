<?php if ($this->config->allow_offer_in_cart) : ?>
	<form id="angebote_erstellen" name="angebote_erstellen" class="clearfix" action="<?php echo SEFLink('index.php?option=com_jshopping&controller=offer_and_order&task=create_offer', 1); ?>" method="POST">
		<div class="angebote_erstellen input-group mb-3">
			<div class="input-group-append d-flex">
				<input type="text" class="form-control" name="projectname" placeholder="<?php echo JText::_('COM_SMARTSHOP_OFFER_AND_ORDER_PROJECTNAME'); ?>" value="<?php echo $this->projectname; ?>"/>
				<input class="btn btn-outline-secondary" type="submit" value="<?php echo JText::_('COM_SMARTSHOP_OFFER_AND_ORDER_ANGEBOT_ERSTELLEN'); ?>"/>
			</div>
		</div>
	</form>
<?php endif; ?>