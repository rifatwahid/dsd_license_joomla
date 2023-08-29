
    <button type="submit" class="btn btn-outline-primary d-grid" onclick="shopHelper.replaceFormActionText('form#productForm-<?php echo $data['productId']; ?>', '<?php echo SEFLink('index.php?option=com_jshopping&controller=product&task=toCheckout&productId=' . $data['productId'], 1); ?>')">
        <?php echo JText::_('COM_SMARTSHOP_CHECKOUT'); ?>
    </button>