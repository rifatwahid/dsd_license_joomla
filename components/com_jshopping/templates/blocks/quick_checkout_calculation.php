 <ul class="list-group">
	<?php //if (!$this->hide_subtotal) : ?>
		<li class="list-group-item price_products">
			<?php echo JText::_('COM_SMARTSHOP_SUBTOTAL'); ?>: <span class="float-end"><?php echo formatprice($this->summ); ?></span>
		</li>
	<?php //endif; ?>

	<?php if ($this->discount > 0) : ?>
		<li class="list-group-item">
			<?php echo JText::_('COM_SMARTSHOP_DISCOUNT'); ?>: <span class="float-end"><?php echo formatprice(-$this->discount);?></span>
		</li>
	<?php endif; ?>

	<?php if ($this->free_discount > 0) : ?>
		<li class="list-group-item">
			<?php echo JText::_('COM_SMARTSHOP_DISCOUNT'); ?>: <span class="float-end"><?php echo formatprice($this->free_discount); ?></span>
		</li>
	<?php endif; ?>

		<li class="list-group-item summ_delivery">
			<?php echo JText::_('COM_SMARTSHOP_SHIPPING_COSTS'); ?>: <span class="float-end"><?php echo formatprice($this->summ_delivery); ?></span>
		</li>
	<?php if ($this->summ_package>0){?>
	<li class="list-group-item summ_package">
		<?php echo JText::_('COM_SMARTSHOP_PACKAGE_PRICE'); ?>: <span class="float-end"><?php echo formatprice($this->summ_package ?? 0); ?></span>
	</li>
	<?php } ?>
	<?php if ($this->payment_name!=""){?>
	<li class="list-group-item summ_payment">
		<span id="active_payment_name"><?php echo $this->payment_name; ?></span>: <span class="float-end summ_pay"><?php echo formatprice($this->summ_payment); ?></span>
	</li>
	<?php } ?>
	
	<?php foreach($this->tax_list as $percent=>$value) : ?>
		
			<?php if ($value>0){
				?><li class="list-group-item tax_list_value"><?php
					if ((double)$percent==0) {
						$tmp=explode('_',substr($percent,15,strlen($percent)));
						echo displayTotalCartTax().JSFactory::getTable('taxextadditional', 'jshop')->getAllAdditionalTaxes((double)$tmp[0])[0]->name." ";
						$percent=$tmp[1];						
						if ($this->show_percent_tax) echo formattax($percent) . '%'; ?>: <span class="float-end"><?php echo formatprice($value); ?></span>
					<?php } else {
						echo displayTotalCartTaxName(); ?> <?php if ($this->show_percent_tax) echo formattax($percent) . '%'; ?>: <span class="float-end"><?php echo formatprice($value); ?></span>
					<?php } ?>
				</li>
			<?php } ?>		
		
	<?php endforeach; ?>
	
	<?php print $this->_tmp_ext_html_after_show_total_tax ?? ''; ?>
	
	<li class="list-group-item fullsumm">
		<?php echo JText::_('COM_SMARTSHOP_ORDER_TOTAL'); ?>: <span class="float-end"><?php echo formatprice($this->fullsumm); ?></span>
	</li>

</ul>