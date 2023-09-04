
<?php if ( $this->product->_display_price && $this->usergroup_show_action) : ?>					
	<li class="list-inline-item d-grid mx-0 mb-2 shop_editor_btn"  style="<?php if ($this->show_buttons['editor']){echo "display: none;";}?>">
		<?php echo $this->_tmp_product_html_editor_button ?? ''; ?>
	</li>
<?php endif; ?>