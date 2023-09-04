<?php if (!empty(trim($order->order_add_info))) : ?>
    <br>

    <div class="bottomAdditionalText">
        <?php echo trim($order->order_add_info); ?>
    </div>
<?php endif; ?>

<?php if (!empty(trim(JText::_('COM_SMARTSHOP_DN_LIEFERSCHEIN_BOTTOM_TEXT')))) : ?>
    <br>
    
    <div class="bottomAdditionalText">
        <?php echo trim(JText::_('COM_SMARTSHOP_DN_LIEFERSCHEIN_BOTTOM_TEXT')); ?>
    </div>
<?php endif; ?>