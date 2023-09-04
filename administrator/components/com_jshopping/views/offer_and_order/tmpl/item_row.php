<?php 
Joomla\CMS\HTML\HTMLHelper::addIncludePath(JPATH_COMPONENT_SITE . '/helpers/html/');
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
$i = $this->id; ?>
<tr valign="top" id="order_item_row_<?php echo $i?>">
 <td>
   <input type="text" name="product_name[<?php echo $i?>]" class="form-control mb-2" value="" size="44" title="<?php print JText::_('COM_SMARTSHOP_TITLE')?>" />
	<?php echo HTMLHelper::_('smartshopmodal.renderButton', '', 'product_list_selectable_'.$i, '', JText::_('COM_SMARTSHOP_LOAD')); ?>
	<?php echo HTMLHelper::_('smartshopmodal.renderWindow', 'product_list_selectable_'.$i, '', '<iframe src="index.php?option=com_jshopping&controller=product_list_selectable&tmpl=component&e_name='.$i.'" id="product_list_selectable" frameborder="0" width="758" height="540"></iframe>'); ?>
  <br />
   <?php if ($this->config->admin_show_attributes){?>
   <textarea rows="2" cols="24" class="form-control" name="product_attributes[<?php echo $i?>]" title="<?php print JText::_('COM_SMARTSHOP_ATTRIBUTES')?>"></textarea><br />
   <?php }?>
   <?php if ($this->config->admin_show_freeattributes){?>
   <textarea rows="2" cols="24" class="form-control" name="product_freeattributes[<?php echo $i?>]" title="<?php print JText::_('COM_SMARTSHOP_FREE_ATTRIBUTES')?>"></textarea>
   <?php }?>   
   <input type="hidden" name="product_id[<?php echo $i?>]" value="" />
   <input type="hidden" name="delivery_times_id[<?php echo $i?>]" value="" />
   <input type="hidden" name="thumb_image[<?php echo $i?>]" value="" />
   <?php if ($this->config->admin_order_edit_more){?>
   <div>
   <?php echo JText::_('COM_SMARTSHOP_PRODUCT_WEIGHT')?> <input type="text" class="form-control" name="weight[<?php echo $i?>]" value="" />
   </div>
   <div>   
   <?php echo JText::_('COM_SMARTSHOP_VENDOR')?> ID <input type="text" class="form-control" name="vendor_id[<?php echo $i?>]" value="" />
   </div>
   <?php }else{?>
   <input type="hidden" name="weight[<?php echo $i?>]" value="" />
   <input type="hidden" name="vendor_id[<?php echo $i?>]" value="" />
   <?php }?>
 </td>
 <td>
   <input type="text" class="form-control" name="product_ean[<?php echo $i?>]" class="middle" value="" />
 </td>
 <td>
   <input type="text" class="form-control" name="product_quantity[<?php echo $i?>]" class="small3" value="" onkeyup="shopOrderAndOffer.updateOrderSubtotal();shopOrderAndOffer.calculateTax();"/>   
 </td>
 <td>
   <div class="price"><?php print JText::_('COM_SMARTSHOP_PRICE')?>: <input class="small3 form-control" type="text" name="product_item_price[<?php echo $i?>]" value="" onkeyup="shopOrderAndOffer.updateOrderSubtotal();shopOrderAndOffer.calculateTax();"/></div>
   <div class="price"><?php print JText::_('COM_SMARTSHOP_PRODUCT_ADD_PRICE_ADD')?>: <input class="small3 form-control" type="text" name="product_one_time_price[<?php echo $i?>]" value="" onkeyup="shopOrderAndOffer.updateOrderSubtotal();"/></div>
   <?php if (!$this->config->hide_tax){?>
   <div class="tax"><?php print JText::_('COM_SMARTSHOP_TAX')?>: <input class="small3 form-control" type="text" name="product_tax[<?php echo $i?>]" value="" /> %</div>
   <?php }?>
   <input type="hidden" name="order_item_id[<?php echo $i?>]" value="" />
 </td>
 <td>
    <a class="btn btn-micro" href='#' onclick="document.querySelector('#order_item_row_<?php echo $i?>').remove();shopOrderAndOffer.updateOrderSubtotal();shopOrderAndOffer.calculateTax();return false;">
        <i class="icon-delete"></i>
    </a>
 </td>
</tr>