<?php
/**
* @version      4.9.0 13.08.2013
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');
?>

<div id="attribs-page" class="tab-pane">
	<?php if (!empty($lists['all_independent_attributes']) || !empty($lists['all_attributes'])) : ?>
		<script>
			var lang_error_attribute = "<?php echo JText::_('COM_SMARTSHOP_ERROR_ADD_ATTRIBUTE'); ?>";
			var lang_attribute_exist = "<?php echo JText::_('COM_SMARTSHOP_ATTRIBUTE_EXIST'); ?>";
			var folder_image_attrib = "<?php echo $jshopConfig->image_attributes_live_path; ?>";
			var use_basic_price = "<?php echo $jshopConfig->admin_show_product_basic_price; ?>";
			var use_bay_price = "<?php echo $jshopConfig->admin_show_product_bay_price; ?>";
			var use_stock = parseInt("<?php echo intval($jshopConfig->stock); ?>");
			var attrib_images = new Object();

			<?php foreach($lists['attribs_values'] as $k => $v) : ?>
			attrib_images[<?php echo $v->value_id?>]="<?php echo $v->image; ?>";
			<?php endforeach; ?>

			document.addEventListener('DOMContentLoaded', function () {
				document.querySelector('.product_edit #adminForm').addEventListener('submit', function(ev){
					ev.preventDefault();
					shopProductAttribute.separatePost(document.querySelector('.product_edit #adminForm'));						
				});
				
				
				function changeNumberArrForLowStockAttrNotifyInputs(table) {
					let tableRows = table.querySelectorAll('tr');

					if (tableRows) {
						tableRows.forEach(function(el, indx1) {
							let inputsLowStockAttrNotify = el.querySelectorAll('input[name*="low_stock_attr_notify_"]');;
							let tableRowNumbFromZero = indx1 - 1;

							if (inputsLowStockAttrNotify) {
								inputsLowStockAttrNotify.forEach(function(el, indx2) {
									let oldInputName = el.getAttribute('name');
									let newInputName = oldInputName.replace(/\[\d*\]/, '[' + tableRowNumbFromZero + ']');

									el.setAttribute('name', newInputName);
								});
							}
						});  
					}          
				}

				function onEnd(tableName, inputName) {
					let parentTable = document.querySelector(tableName);

					if (parentTable) {
						let inputsWithProdAttrSorting = parentTable.querySelectorAll(inputName);
						changeNumberArrForLowStockAttrNotifyInputs(parentTable);
						
						if (inputsWithProdAttrSorting) {
							inputsWithProdAttrSorting.forEach(function (el, indx) {
								let newSortingNumber = indx + 1;
								el.value = newSortingNumber;
							});
						}
					}
				}

				let attrValueBody = document.querySelector('#list_attr_value tbody');
				if (attrValueBody) {
					Sortable.create(attrValueBody, {
					animation: 150,
					onEnd: function (evt) {
						onEnd('#list_attr_value', 'input[name*=product_attr_sorting]');
					}
				});
				}

				let listAttrValueIndBody = document.querySelectorAll('[id*=list_attr_value_ind_] tbody');				
				if (listAttrValueIndBody) {
					listAttrValueIndBody.forEach(function(el){
						var id = el.parentElement.id;
						Sortable.create(el, {
							animation: 150,
							onEnd: function (evt) {
								onEnd('#'+id, '#'+id+' input[name*=product_independ_attr_sorting]');
							}
						});	
					});
					
				}
			});
		</script>
	<?php endif; ?>

	<?php 
		if (!empty($lists['all_attributes'])) {
			require_once __DIR__ . '/elements/attribute/list_depend.php';
			echo '<br/>';
			require_once __DIR__ . '/elements/attribute/add_depend.php'; 
			echo '<div class="clr"></div><br/>';
		} 

		if (!empty($lists['all_independent_attributes'])) {
			require_once __DIR__ . '/elements/attribute/independ.php';
			echo '<br/><br/>';
		}
	?>

	<?php $pkey='plugin_template_attribute'; if ($this->$pkey) { echo $this->$pkey; } ?>
	<a href="index.php?option=com_jshopping&controller=attributes" target="_blank">
		<i class="fas fa-align-justify"></i><?php echo JText::_('COM_SMARTSHOP_LIST_ATTRIBUTES'); ?>
	</a>
</div>
<script src="<?php print $jshopConfig->live_admin_path; ?>js/src/product/form-data-json.min.js"></script>
