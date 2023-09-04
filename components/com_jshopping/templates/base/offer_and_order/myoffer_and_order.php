<?php 
$sefLinkSearchOffers = SEFLink('index.php?option=com_jshopping&controller=offer_and_order', 1);
?>
<div class="shop offer-list">
	<div class="row-fluid row pb-2">
		<div class="col-sm-12 col-md-6 col-xl-6 col-12 ">
			<h1><?php print JText::_('COM_SMARTSHOP_OFFER_AND_ORDER_MY_OFFER'); ?></h1>
		</div>	
		<div class="col-sm-12 col-md-6 col-xl-6 col-12 ">	
			<?php include  templateOverrideBlock('blocks', 'offer_search_form.php'); ?>			
		</div>	
	</div>

	<div class="list-group">
		<?php include  templateOverrideBlock('blocks', 'offer_list.php'); ?>		
	</div>
</div>