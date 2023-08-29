<button type="submit" class="btn btn-outline-primary d-grid" onclick="document.querySelector('#productForm').setAttribute('action', '<?php echo SEFLink('index.php?option=com_jshopping&controller=product&task=toCheckout&productId=' . $data['productId'], 1); ?>')">
    <?php echo JText::_('COM_SMARTSHOP_CHECKOUT'); ?>
</button>