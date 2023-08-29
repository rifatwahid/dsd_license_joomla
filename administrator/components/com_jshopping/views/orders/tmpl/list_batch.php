<?php defined('_JEXEC') or die?>
<div class="modal fade hide" id="collapseModal">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&#215;</button>
        <h3><?php echo JText::_('COM_SMARTSHOP_STATUS')?></h3>
    </div>
    <div class="modal-body modal-batch">
        <div class="row-fluid">
            <div class="control-group span6">
                <div class="controls">
                    <label for="batch_order_status"><?php echo JText::_('COM_SMARTSHOP_ORDER_STATUS')?></label>
                    <?php echo JHTML::_("select.genericlist", $this->orderStatusOptions, "batch_order_status", "", "status_id", "name")?>
                </div>
            </div>
        </div>
        <div class="row-fluid">
            <div class="control-group span6">
                <div class="controls">
                    <label for="batch_notify_customer"><?php echo JText::_('COM_SMARTSHOP_NOTIFY_CUSTOMER')?></label>
                    <?php echo JHTML::_("select.genericlist", array(0=>JText::_("JNO"),1=>JText::_("JYES")), "batch_notify_customer", "", "status_id", "name")?>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn" type="button" onclick="document.id('batchOrderStatus').value='';document.id('batchNotifyCustomer').value='';" data-dismiss="modal"><?php echo JText::_('JCANCEL')?></button>
        <button class="btn btn-primary" type="submit" onclick="Joomla.submitbutton('orders_addon.savestatus')"><?php echo JText::_('JGLOBAL_BATCH_PROCESS')?></button>
    </div>
</div>
