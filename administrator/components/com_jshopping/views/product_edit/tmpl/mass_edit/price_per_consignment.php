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

<tr>
    <td class="key">
        <?php echo JText::_('COM_SMARTSHOP_PRODUCT_ADD_PRICE');?>
    </td>
    <td>
        <input type="hidden" name="product_is_add_price" value="0">
        <input type="checkbox" name="product_is_add_price" id="product_is_add_price" class="form-check-input" value="1" <?php if ($row->product_is_add_price) echo 'checked="checked"';?>  onclick="showHideAddPrice()" />
    </td>
</tr>
<tr id="tr_add_price">
    <td class="key"><?php echo JText::_('COM_SMARTSHOP_PRODUCT_ADD_PRICE');?></td>
    <td>
        <table id="table_add_price" class="table table-striped">
            <thead>
            <tr>
                <th>
                    <?php echo JText::_('COM_SMARTSHOP_PRODUCT_QUANTITY_START');?>    
                </th>
                <th>
                    <?php echo JText::_('COM_SMARTSHOP_PRODUCT_QUANTITY_FINISH');?>    
                </th>
                <th>
                    <?php echo JText::_('COM_SMARTSHOP_DISCOUNT');?>
                </th>
                <th>
                    <?php echo JText::_('COM_SMARTSHOP_PRODUCT_PRICE');?>
                </th>                    
                <?php $pkey='plugin_consignment_attr_title'; if ($row->$pkey){ print $row->$pkey;}?>
                <th>
                    <?php echo JText::_('COM_SMARTSHOP_DELETE');?>    
                </th>
            </tr>
            </thead>   
            <tbody>
            <?php 
            $add_prices = $row->product_add_prices;
            $iterationNumber = 0;

            if (!empty($add_prices)) :
            foreach($add_prices as $key => $addPrices) :
                foreach($addPrices as $key => $addPrice) :
                
                if($addPrice->discount != 0){
                    $_add_price = 0;
                }else{
                    $_add_price = $addPrice->price;
                }
                
                $_add_price = number_format($_add_price, 2, '.', '');
                ?>
                <tr id="add_price_<?php echo $iterationNumber; ?>">
                    <td>
                        <input type="text" class="small3 form-control w-50" name="quantity_start[]" id="quantity_start_<?php print $iterationNumber; ?>" value="<?php echo $addPrice->product_quantity_start;?>" />    
                    </td>
                    <td>
                        <input type="text" class="small3 form-control w-50" name="quantity_finish[]" id="quantity_finish_<?php print $iterationNumber; ?>" value="<?php echo $addPrice->product_quantity_finish;?>" />    
                    </td>
                    <td>
                        <input type="text" class="small3 form-control w-50" name="product_add_discount[]" id="product_add_discount_<?php print $iterationNumber; ?>" value="<?php echo $addPrice->discount;?>" onkeyup="productAddPriceupdateValue(<?php echo $iterationNumber; ?>)" />    
                    </td>
                    <td>
                        <input type="text" class="small3 form-control w-50" name="product_add_price[]" id="product_add_price_<?php print $iterationNumber; ?>" value="<?php echo $_add_price;?>" />
                        <input type="hidden" class="small3" name="start_discount[]" id="start_discount_<?php print $iterationNumber; ?>" value="<?php echo $addPrice->start_discount;?>" />
                    </td>
                    <?php $pkey='plugin_consignment_attr'; if ($row->$pkey[$iterationNumber]){ echo $row->$pkey[$iterationNumber];}?>
                    <td align="center">
                        <a class="btn btn-micro" href="#" onclick="delete_add_price(<?php echo $iterationNumber; ?>);return false;">
                            <i class="icon-delete"></i>
                        </a>
                    </td>
                </tr>
            <?php $iterationNumber++; endforeach; endforeach; endif; ?>    
            </tbody>
        </table>
        <table class="table table-striped">
            <tr>
                <td><?php echo $lists['add_price_units'];?> - <?php echo JText::_('COM_SMARTSHOP_UNIT_MEASURE');?></td>
                <td align="right" width="100">
                    <input class="btn button btn-primary" type="button" name="add_new_price" onclick="shopProductPrice.add();<?php $pkey='plugin_consignment_attr_button'; if ($row->$pkey){ print $row->$pkey;}?>" value="<?php echo JText::_('COM_SMARTSHOP_PRODUCT_ADD_PRICE_ADD');?>" />
                </td>
            </tr>
        </table>
        
        <script type="text/javascript">
        window.addEventListener("load", () => {
            <?php print "shopProductPrice.setNumber($i)"; ?>
        });
        </script>
        <input type="hidden" name="product_attr_id" value="<?php print $this->product_attr_id; ?>">
    </td>
</tr>
