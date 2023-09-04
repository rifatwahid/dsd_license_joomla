 <li class="list-group-item list-group-item-action">
		<div class="row">

			<div class="col-sm-2">
				<span class="d-sm-none"><?php echo JText::_('COM_SMARTSHOP_OFFER_AND_ORDER_NUMBER'); ?>:</span>
				<?php echo $v->order_number; ?>
			</div>

			<div class="col-sm-2">
				<span class="d-sm-none"><?php echo JText::_('COM_SMARTSHOP_OFFER_AND_ORDER_PROJECTNAME'); ?>:</span>
				<?php echo $v->projectname; ?>
			</div>

			<div class="col-sm-2 text-sm-center">
				<span class="d-sm-none"><?php echo JText::_('COM_SMARTSHOP_OFFER_AND_ORDER_VALID_TO'); ?>:</span>
				<?php echo strftime($this->config->store_date_format, strtotime($v->valid_to)); ?>
			</div>

			<div class="col-sm-3 text-sm-center offer-open-pdf-lightbox-container" data-order-id="<?php echo $v->order_id; ?>" data-user-id="<?php echo $v->user_id; ?>">
				<a class="offer-open-pdf-lightbox" data-med="<?php echo $this->config->pdf_orders_live_path . '/' . $v->pdf_file; ?>" data-med-size="0x0" data-size="0x0" href="<?php echo $this->config->pdf_orders_live_path . '/' . $v->pdf_file; ?>">
					<span><?php echo JText::_('COM_SMARTSHOP_OFFER_AND_ORDER_OPEN_OFFER_AND_ORDER'); ?></span>
				</a>
			</div>

			<div class="col-sm-3 text-sm-end">
				<a target="_blank" href="<?php echo SEFLink('index.php?option=com_jshopping&controller=offer_and_order&task=order&id=' . $v->order_id, 1); ?>"><?php echo JText::_('JACTION_EDIT'); ?></a>
			</div>

		</div>
	</li>