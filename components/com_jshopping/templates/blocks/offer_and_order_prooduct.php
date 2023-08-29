
<?php if ($this->config->allow_offer_on_product_details_page && $this->product->_display_price && $this->usergroup_show_action) : ?>
	<div class="angebote_erstellen input-group mb-3">
		<div class="d-flex w-100">
			<input type="text" class="form-control" name="projectname" id="project-name-input" placeholder="<?php echo JText::_('COM_SMARTSHOP_OFFER_AND_ORDER_PROJECTNAME'); ?>" value="<?php echo isset($this->projectname) ? $this->projectname : ''; ?>"/>
			<input class="btn btn-outline-secondary" type="button" value="<?php echo JText::_('COM_SMARTSHOP_OFFER_AND_ORDER_ANGEBOT_ERSTELLEN')?>" onclick="shopProductForm.changeAction('form[name=product]', '/index.php?option=com_jshopping&controller=offer_and_order&task=createOfferFromProduct'); addProjectNameToProductForm();document.querySelector('form[name=product]').submit();" /> 
		</div>
	</div>
<?php endif; ?>