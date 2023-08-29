<?php
/**
* @version      4.9.0 13.08.2013
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');

$row = $template->sh_method_price;
?>
<tr><td>&nbsp;</td></tr>
<tr>
  <td colspan="2" style = "text-align:right; vertical-align:top">
    <table class="adminlist table table-striped" id="table_shipping_weight_price">
    <thead>
       <tr>
         <th>
           <?php echo JText::_('COM_SMARTSHOP_CONDITION');?>
         </th>
         <th>
           <?php echo JText::_('COM_SMARTSHOP_PRICE');?> (<?php echo $template->currency->currency_code; ?>)
         </th>
         <th>
           <?php echo JText::_('COM_SMARTSHOP_PACKAGE_PRICE');?> (<?php echo $template->currency->currency_code; ?>)
         </th>         
         <th>
           <?php echo JText::_('COM_SMARTSHOP_DELETE');?>
         </th>
       </tr>                   
       </thead>
       <tbody>
       <?php
       $key = 0;
       foreach ($row->prices as $key=>$value){?>
       <tr id='shipping_weight_price_row_<?php print $key?>'>
         <td>
           <?php print JHTML::_('select.genericlist', $template->conditions, 'condition[]', 'class="inputbox form-select" ', 'condition_id', 'name', $value->condition_id); ?>
         </td>
         <td>
           <input type = "text" class = "inputbox form-control" name = "shipping_price[]" value = "<?php echo $value->shipping_price;?>" />
         </td>
         <td>
           <input type = "text" class = "inputbox form-control" name = "shipping_package_price[]" value = "<?php echo $value->shipping_package_price;?>" />
         </td>         
         <td style="text-align:center">
            <a class="btn btn-micro" href="#" onclick="shopShipping.deletePrice(<?php print $key?>);return false;">
                <i class="icon-delete"></i>
            </a>
         </td>
       </tr>
       <?php }?>    
       </tbody>
    </table>
    <table class="adminlist"> 
    <tr>
        <td style="padding-top:5px;" align="right">
            <input type="button" class="btn btn-primary" value="<?php echo JText::_('COM_SMARTSHOP_ADD_VALUE')?>" onclick = "shopShipping.addPrice();">
        </td>
    </tr>
    </table>
    <script> 
        document.addEventListener('DOMContentLoaded', function () {
          <?php echo "shopShipping.setNumber($key);"?>
        });
    </script>
</td>
</tr>