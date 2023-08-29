<?php 
	$add_prices = $row->product_add_prices[0] ?? [];
    $count = count($add_prices);
?>

<?php
if(!empty($add_prices)){
?>
	<?php for ($i=0; $i < $count; $i++) {
		if ($add_prices[$i]->usergroup_prices == 0) {
		?>
		<tr id="add_price_<?php echo $i; ?>" data-consignment-id='<?php echo $i; ?>'>
			<td>
				<input type="text" class="small3 w-50 form-control" name="quantity_start[]" id="quantity_start_<?php echo $i; ?>" value="<?php echo $add_prices[$i]->product_quantity_start; ?>" />    
			</td>
			<td>
				<input type="text" class="small3 w-50 form-control" name="quantity_finish[]" id="quantity_finish_<?php echo $i; ?>" value="<?php echo $add_prices[$i]->product_quantity_finish; ?>" />    
			</td>
			<td>
				<input type="text" class="small3 w-50 form-control" name="product_add_discount[]" id="product_add_discount_<?php echo $i; ?>" value="<?php echo $add_prices[$i]->discount; ?>" onkeyup="shopPricePerConsigment.updateDiscount(<?php echo $i; ?>)" />    
			</td>
			<td>
				<input type="text" class="small3 w-50 form-control" name="product_add_price[]" id="product_add_price_<?php echo $i; ?>" value="<?php echo $add_prices[$i]->price; ?>"  onkeyup="shopPricePerConsigment.updatePrice(<?php echo $i; ?>)"  />
				<input type="hidden" class="small3 form-control" name="start_discount[]" id="start_discount_<?php echo $i; ?>" value="<?php echo $add_prices[$i]->start_discount; ?>" />
			</td>
			<?php $pkey='plugin_consignment_attr'; if (isset($row->$pkey[$i]) && $row->$pkey[$i]){ echo $row->$pkey[$i]; }?>
			<td align="center">
				<a class="btn btn-micro" href="#" onclick="shopProductPrice.delete(<?php echo $i; ?>);return false;">
					<i class="icon-delete"></i>
				</a>
			</td>
		</tr>
		<?php
	}}
}
?>  