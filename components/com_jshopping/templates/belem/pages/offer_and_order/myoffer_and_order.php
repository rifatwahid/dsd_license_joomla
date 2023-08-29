<?php 
$sefLinkSearchOffers = SEFLink('index.php?option=com_jshopping&controller=offer_and_order', 1);
?>
<div class="shop offer-list">
	<div class="row-fluid row pb-2">
		<div class="col-sm-12 col-md-6 col-xl-6 col-12 ">
			<h1><?php print JText::_('COM_SMARTSHOP_OFFER_AND_ORDER_MY_OFFER'); ?></h1>
		</div>	
		<div class="col-sm-12 col-md-6 col-xl-6 col-12 ">	
			<form name="adminForm" id="adminForm" method="post" action="<?php echo $sefLinkSearchOffers;?>">
				<div class="js-stools-container-bar text_right">
					<div class="filter-search btn-group pull-left">
						<input type="text" id="text_search" name="text_search" placeholder="<?php print JText::_('COM_SMARTSHOP_SEARCH')?>" value="<?php echo htmlspecialchars(JFactory::getApplication()->input->getVar('text_search'));?>"  onkeypress="shopSearch.searchEnterKeyPress(event,this);"  />
					</div>

					<div class="btn-group pull-left hidden-phone">
						<button class="btn hasTooltip" type="submit" title="<?php print JText::_('COM_SMARTSHOP_SEARCH')?>">
							<i class="fas fa-search"></i>
						</button>
						<button class="btn hasTooltip" onclick="document.id('text_search').value='';this.form.submit();" type="button" title="<?php print JText::_('COM_SMARTSHOP_CLEAR_FILTERS')?>">
							<i class="fas fa-window-close"></i>
						</button>
					</div>
					
				</div>
			</form>
		</div>	
	</div>

	<div class="list-group">

		<div class="list-group-item d-none d-sm-block">
			<div class="row">

			<div class="col-sm-2">
				<?php echo JText::_('COM_SMARTSHOP_OFFER_AND_ORDER_NUMBER'); ?>
			</div>

			<div class="col-sm-2">
				<?php echo JText::_('COM_SMARTSHOP_OFFER_AND_ORDER_PROJECTNAME'); ?>
			</div> 
			                
			<div class="col-sm-2 text-center">
				<?php echo JText::_('COM_SMARTSHOP_OFFER_AND_ORDER_VALID_TO'); ?>
			</div>

			</div>
		</div>

		<?php foreach($this->rows as $k => $v) : ?>

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

					<div class="col-sm-3 text-sm-right">
						<a target="_blank" href="<?php echo SEFLink('index.php?option=com_jshopping&controller=offer_and_order&task=order&id=' . $v->order_id, 1); ?>"><?php echo JText::_('JACTION_EDIT'); ?></a>
					</div>

				</div>
			</li>
		<?php endforeach; ?>
	</div>
</div>