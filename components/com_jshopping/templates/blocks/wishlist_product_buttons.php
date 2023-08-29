<a class="btn btn-outline-danger d-grid mb-3" href="<?php echo $prod['href_delete']?>" onclick="return confirm('<?php echo JText::_('COM_SMARTSHOP_MODAL_REMOVE_ITEM_FROM_WISHLIST'); ?>')"><?php echo JText::_('COM_SMARTSHOP_REMOVE'); ?></a>
<?php if ((!isset($product->hide_buy) || !$product->hide_buy) && $product->isShowCartSection() && (!$this->config->user_as_catalog || !$this->config->user_as_catalog)) :  ?>
	<a class="btn btn-outline-primary d-grid" href ="<?php echo $prod['remove_to_cart'] ?>"><?php echo JText::_('COM_SMARTSHOP_ADD_TO_CART'); ?></a>
<?php endif; ?>						