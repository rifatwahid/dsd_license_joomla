<div class="shop offer-saved">
	<h1 class="offer-saved__page-title"><?php echo JText::_('COM_SMARTSHOP_OFFER_AND_ORDER_SAVED'); ?></h1>

	<p class="my-4"><?php echo JText::_('COM_SMARTSHOP_OFFER_AND_ORDER_SAVED_TEXT_EXPLANATION'); ?></p>

	<div class="offer_and_order_created offer-open-pdf-lightbox-container" data-order-id="<?php echo $this->order->order_id; ?>" data-user-id="<?php echo $this->order->user_id; ?>">
		<a class="btn btn-outline-secondary offer-open-pdf-lightbox" target='_blank' data-med="<?php echo $this->url; ?>" href="<?php echo $this->url; ?>" data-med-size="650x650" data-size="650x650">
			<span><?php echo JText::_('COM_SMARTSHOP_OFFER_AND_ORDER_OPEN_OFFER_AND_ORDER'); ?></span>
		</a>        
	</div>
</div>