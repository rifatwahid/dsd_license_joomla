<div class="modal jviewport-width30 fade hide" id="change_status">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" data-bs-dismiss="modal">&#215;</button>
        <h3><?php echo JText::_('COM_SMARTSHOP_ORDER_STATUS_CHANGE') ?></h3>
    </div>

    <div class="modal-body mb-change-status">
        <form id="form-change-status" class="form-horizontal" action="<?php print JRoute::_('index.php?option=com_jshopping&controller=orders&task=changestatus') ?>" method="POST">
            <div class="control-group">
                <label class="control-label" for="batch_order_status"><?php echo JText::_('COM_SMARTSHOP_ORDER_STATUS') ?></label>
                <div class="controls">
                    <?php echo JHTML::_('select.genericlist', $lists['status_orders'], 'change_status_status_id', 'class="inputbox form-select"', 'status_id', 'name') ?>
                </div>
            </div>
            <div class="control-group">
                
                <label class="control-label" for="change_status_notify_customer"><?php echo JText::_('COM_SMARTSHOP_NOTIFY_CUSTOMER') ?></label>
                <div class="controls">
                    <?php echo JHTML::_("select.genericlist", array(0=>JText::_("JNO"),1=>JText::_("JYES")), "change_status_notify_customer", "", "status_id", "name")?>
                </div>
            </div>
            <input id="change-status-ids" type="hidden" name="cid" value="" />
        </form>
    </div>

    <div class="modal-footer">
        <button class="btn" type="button" data-dismiss="modal" data-bs-dismiss="modal"><?php echo JText::_('JCANCEL')?></button>
        <button class="btn btn-success" type="submit" onclick="changeStatus()"><?php echo JText::_('JSAVE')?></button>
    </div>
</div>
<button type="button" style="display:none;" class="change_status_btn_call" data-bs-toggle="modal" data-toggle="modal" data-bs-target="#change_status" data-target="#change_status"></button>

<script>
    Joomla.submitbutton = function(task) {
        
        if (task == 'change_status') {
            document.querySelector('.change_status_btn_call').click();
            return;
        }

        Joomla.submitform(task);
    }

    function changeStatus() {
        let ids = [];
        let checkedElems = document.querySelectorAll("form[name='adminForm'] input[name='cid[]']:checked");
        let changeStatusIdEl = document.querySelector('#change-status-ids');

        if (checkedElems) {
            checkedElems.forEach(function (item) {
                ids.push(item.value);
            });
        }

        if (changeStatusIdEl) {
            changeStatusIdEl.value = ids.join(',');
        }
                
        document.querySelector('#form-change-status').submit();
    }
</script>