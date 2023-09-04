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
	<?php include  templateOverrideBlock('blocks', 'offer_list_data.php'); ?>	
<?php endforeach; ?>