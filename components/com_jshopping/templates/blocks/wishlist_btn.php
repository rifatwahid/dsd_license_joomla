<?php if ($this->enable_wishlist && ((isset($productUsergroupPermissions->is_usergroup_show_buy) && $productUsergroupPermissions->is_usergroup_show_buy) || (isset($this->productUsergroupPermissions->is_usergroup_show_buy) && $this->productUsergroupPermissions->is_usergroup_show_buy)) && (!$this->jshopConfig->user_as_catalog || !$jshopConfig->user_as_catalog)) { ?>
	<button type="submit" class="btn btn-outline-secondary d-grid" onclick="document.getElementById('to').value = 'wishlist';">
		<?php echo JText::_('COM_SMARTSHOP_ADD_TO_WISHLIST'); ?>
	</button>
<?php } ?>