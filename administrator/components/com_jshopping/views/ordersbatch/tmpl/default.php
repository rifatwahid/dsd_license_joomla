<?php defined("_JEXEC") or die?>
<script type="text/javascript">
    function batchSubmit(){
        var statusElement = document.getElementById("batchOrderStatus");
        var parentStatusElement = window.parent.document.getElementById("batchOrderStatus")
        var notifyElement = document.getElementById("batchNotifyCustomer");
        var parentNotifyElement = window.parent.document.getElementById("batchNotifyCustomer")
        var parentController = window.parent.document.getElementById("controller")
        if(typeof statusElement != "undefined" && typeof parentStatusElement != "undefined"){
            parentStatusElement.value = statusElement.value;
        }        
        if(typeof notifyElement != "undefined" && typeof parentNotifyElement != "undefined"){
            parentNotifyElement.value = notifyElement.value;
        }
        window.parent.Joomla.submitform("orders_addon.savestatus");
    }
</script>
<form action="<?php echo JRoute::_("index.php?option=com_jshopping&controller=orders_addon")?>" method="post" name="adminForm">
    <fieldset>
        <div class="fltrt">
            <button type="button" onclick="batchSubmit()"><?php echo JText::_("JSAVE")?></button>
            <button type="button" onclick="window.parent.SqueezeBox.close()"><?php echo JText::_("JCANCEL")?></button>
        </div>
        <div class="configuration" ><?php echo JText::_('COM_SMARTSHOP_STATUS')?></div>
    </fieldset>
    <?php echo JHtml::_("tabs.start", "orders-batch-tabs-options", array("useCookie" => 1))?>
    <?php echo JHtml::_("tabs.panel", JText::_('COM_SMARTSHOP_OPTIONS'), "publishing-details")?>
    <ul class="config-option-list">
        <li>
            <label for="batchOrderStatus"><?php echo JText::_('COM_SMARTSHOP_ORDER_STATUS')?></label>
            <?php echo JHTML::_("select.genericlist", $this->orderStatusOptions, "batchOrderStatus", "", "status_id", "name")?>
        </li>
        <li>
            <label for="batchNotifyCustomer"><?php echo JText::_('COM_SMARTSHOP_NOTIFY_CUSTOMER')?></label>
            <input type="checkbox" id="batchNotifyCustomer" value="1" />
        </li>
    </ul>
    <div class="clr"></div>
    <?php echo JHtml::_("tabs.end")?>
</form>
