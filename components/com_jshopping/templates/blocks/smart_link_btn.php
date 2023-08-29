<?php if ($smart_link && $productUsergroupPermissions->is_usergroup_show_buy) : ?>
	<li class="list-inline-item d-grid mx-0 mb-2">
		<a class="btn btn-outline-secondary shop_editor_btn d-grid" href="<?php echo $smart_link; ?>" style="<?php if ($this->show_buttons['editor']){echo "display: none;";}?>">
			<?php echo JText::_('COM_SMARTSHOP_EDIT_TEMPLATE'); ?>
		</a>
	</li>
<?php endif; ?>