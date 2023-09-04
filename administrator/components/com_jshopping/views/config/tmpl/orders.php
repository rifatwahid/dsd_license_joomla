<?php 
/**
* @version      4.9.0 10.02.2014
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');

$jshopConfig=JSFactory::getConfig();
$lists=$this->lists;
displaySubmenuConfigs('orders',$this->canDo);
?>
<form action="index.php?option=com_jshopping&controller=config" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
	<input type="hidden" name="task" value="">
	<input type="hidden" name="tab" value="11">

    <legend><?php echo  JText::_('COM_SMARTSHOP_ORDERS') ?></legend>
	<div class="striped-block jshops_edit order_tmpl ">
		<div class="form-group row align-items-center">
			<label for="default_status_order" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
                <?php echo  JText::_('COM_SMARTSHOP_DEFAULT_ORDER_STATUS');?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
                <?php echo $lists['status']; ?>
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="next_order_number" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
                <?php echo  JText::_('COM_SMARTSHOP_NEXT_ORDER_NUMBER');?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
                <input type="text" class="form-control" name="next_order_number" id="next_order_number" value="" /> (<?php echo $jshopConfig->next_order_number?>)
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="order_suffix" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
                <?php echo  JText::_('COM_SMARTSHOP_SUFFIX'); ?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
                <input type="text" class="form-control" name="order_suffix"  id="order_suffix" value="<?php echo $jshopConfig->order_suffix; ?>"/>
			</div>
		</div>
		
		<input type="hidden" name="client_allow_cancel_order" id="client_allow_cancel_order" value="1" checked/>

		<div class="form-group row align-items-center">
			<label for="is_allowed_status_for_cancellation" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
                <?php echo  JText::_('COM_SMARTSHOP_ALLOWED_STATUS_FOR_CANCELLATION')?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">				
				<div class="allowedStatusForCancellation">
					<?php echo JHTML::_('select.genericlist', $this->namesOfOrderStatus, 'allowed_status_for_cancellation[]', 'class = "inputbox form-select" size = "10" multiple = "multiple"', 'allowed_status_for_cancellation', 'allowed_status_for_cancellation_name', $this->idsOfOrAllowedCancellationOderStatus); ?>
				</div>
			</div>
		</div>

		<?php if (isset($lists['vendor_order_message_type'])){?>
			<div class="form-group row align-items-center">
				<label for="vendor_order_message_type" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
					<?php echo  JText::_('COM_SMARTSHOP_MESSAGE_OF_ORDER_VENDOR')?>
				</label>
				<div class="col-sm-8 col-md-9 col-xl-9 col-12">        
					<?php echo $lists['vendor_order_message_type']; ?>
				</div>
			</div>
		<?php } ?>
		<div class="form-group row align-items-center">
			<label for="max_price_order" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
                <?php echo  JText::_('COM_SMARTSHOP_ERROR_MAX_SUM_ORDER');?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
                <input type="text" class="form-control" name="max_price_order" id="max_price_order" value="<?php echo $jshopConfig->max_price_order;?>" />
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="min_price_order" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
                <?php echo  JText::_('COM_SMARTSHOP_ERROR_MIN_SUM_ORDER');?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
                <input type="text" class="form-control" name="min_price_order" id="min_price_order" value="<?php echo $jshopConfig->min_price_order;?>" />
			</div>
		</div>
		<?php $pkey="etemplatevarorders";if (isset($this->$pkey) && $this->$pkey){print $this->$pkey;}?>
    </div>
    <legend><?php echo  JText::_('COM_SMARTSHOP_CONFIGURATION_DELIVERY') ?></legend>
	<div class="striped-block jshops_edit order_tmpl_deilivery_config ">
		<div class="form-group row align-items-center">
			<label for="delivery_note_suffix" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
                <?php echo  JText::_('COM_SMARTSHOP_SUFFIX'); ?> 
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
                <input type="text" class="form-control" name="delivery_note_suffix" id="delivery_note_suffix" value="<?php echo $jshopConfig->delivery_note_suffix; ?>"/>
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="delivery_order_depends_delivery_product" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
                <?php echo  JText::_('COM_SMARTSHOP_DELIVERY_ORDER_DEPENDS_DELIVERY_PRODUCT')?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input type="hidden" name="delivery_order_depends_delivery_product" value="0" />
				<input type="checkbox" name="delivery_order_depends_delivery_product" class="form-check-input" id="delivery_order_depends_delivery_product" value="1" <?php if ($jshopConfig->delivery_order_depends_delivery_product) echo 'checked="checked"';?> />
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="summ_null_shipping" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
                <?php echo  JText::_('COM_SMARTSHOP_NULL_SIHPPING');?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input type="text" class="form-control" name="summ_null_shipping"  id="summ_null_shipping" value="<?php echo $jshopConfig->summ_null_shipping ;?>" /> <?php print $this->currency_code ?? '';?>
			</div>
		</div>
		<?php $pkey="etemplatevardelivery";if (isset($this->$pkey) && $this->$pkey){print $this->$pkey;}?>
    </div>
    <legend><?php echo  JText::_('COM_SMARTSHOP_INVOICE') ?></legend>
	<div class="striped-block jshops_edit order_tmpl_invoice">
		<div class="form-group row align-items-center">
			<label for="invoice_suffix" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
                <?php echo  JText::_('COM_SMARTSHOP_SUFFIX'); ?> 
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
                <input type="text" class="form-control" name="invoice_suffix" id="invoice_suffix" value="<?php echo $jshopConfig->invoice_suffix; ?>"/>
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="next_invoice_number" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
                <?php echo  JText::_('COM_SMARTSHOP_NEXT_INVOICE_NUMBER');?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
                <input type="text" class="form-control" name="next_invoice_number" id="next_invoice_number" value="" /> (<?php echo $this->next_invoice_number?>)
			</div>
		</div>
		<?php $pkey="etemplatevarinvoice";if (isset($this->$pkey) && $this->$pkey){print $this->$pkey;}?>
    </div>
    <legend><?php echo  JText::_('COM_SMARTSHOP_REFUND') ?></legend>
	<div class="striped-block jshops_edit order_tmpl_refund">
		<div class="form-group row align-items-center">
			<label for="refund_suffix" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
                <?php echo  JText::_('COM_SMARTSHOP_SUFFIX'); ?> 
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
                <input type="text" class="form-control" name="refund_suffix" id="refund_suffix" value="<?php echo $jshopConfig->refund_suffix; ?>"/>
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="next_refund_number" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
                <?php echo  JText::_('COM_SMARTSHOP_NEXT_REFUND_NUMBER');?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
                <input type="text" class="form-control" name="next_refund_number" id="next_refund_number" value="" /> (<?php echo $this->next_refund_number?>)
			</div>
		</div>
		<?php $pkey="etemplatevarinvoice";if (isset($this->$pkey) && $this->$pkey){print $this->$pkey;}?>
    </div>
</form>