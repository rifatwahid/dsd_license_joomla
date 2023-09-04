<?php 

Joomla\CMS\HTML\HTMLHelper::addIncludePath(JPATH_COMPONENT_SITE . '/helpers/html/');
use Joomla\CMS\HTML\HTMLHelper;
$category_id = $this->product->category_id ?? 0;

?>
<?php if ((!isset($this->show_buttons['cart']) || !$this->show_buttons['cart']) && (!isset($this->hide_buy) || !$this->hide_buy) && $this->product->isShowCartSection() && (!$this->jshopConfig->user_as_catalog || !$jshopConfig->user_as_catalog)) :  ?>
	<button type="submit" class="btn btn-outline-primary d-grid mb-2 btn-add-product-to-cart <?php if($this->product->product_template == 'stone'){ ?>btn btn-outline-primary btn-primary<?php } ?>" onclick="document.getElementById('to').value = 'cart';">
		<?php echo JText::_('COM_SMARTSHOP_ADD_TO_CART'); ?>
	</button>

    <?php if ($this->product->one_click_buy && $this->user->id && $this->page_type != 'product_list') :  ?>
        <?php echo HTMLHelper::_('smartshopmodal.renderButton', 'btn btn-outline-primary d-grid btn-one-click-checkout', 'one_click_buy_window', 'onclick="document.getElementById(\'to\').value = \'one_click_buy\';shopOneClickCheckout.add('.$this->product->product_id.','.$category_id.');"', JText::_('COM_SMARTSHOP_ONE_CLICK_BUY')); ?>
    <?php endif; ?>
	 
<?php endif; ?>
<?php 
    echo HTMLHelper::_('smartshopmodal.renderModalWindow', [
        'modalId' => 'one_click_buy_window',
        'modalTitle' => JText::_('COM_SMARTSHOP_BUY_NOW') . ': ' . $this->product->name,
        'modalBody' => '<iframe data-src="/index.php?option=com_jshopping&controller=one_click_checkout&task=display&tmpl=component" id="one_click_buy_window_iframe" frameborder="0" width="758" height="340"></iframe>',
    ]);
?>