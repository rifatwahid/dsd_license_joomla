<?php
/**
* @version 1.0 smartSHOP BS4
*/
defined('_JEXEC') or die('Restricted access');
$smart_link = getSmartLinkForListProducts($product->product_id);
$jshopConfig = JSFactory::getConfig();

$sefLinkToCartAdd = SEFLink('index.php?option=com_jshopping&controller=cart&task=add&to=cart', 1);
$sefLinkToWishlistAdd = SEFLink('index.php?option=com_jshopping&controller=cart&task=add&to=wishlist', 1);
$productUsergroupPermissions = $product->getUsergroupPermissions();
if(isset($product->attribute_active_data->ext_data->image) && $product->attribute_active_data->ext_data->image){
	$image = $product->attribute_active_data->ext_data->image;
}elseif($product->image){
	$image = $product->image;
}else{
	$image = 'noimage.gif';
}

?>

<div class="col-sm-6 col-md-4 col-lg-3 card-group mb-5">	
	<div class="card product-<?php echo $product->product_id; ?> cart-product" data-product-id="<?php echo $product->product_id; ?>">		
		
		<?php include  templateOverrideBlock('blocks', 'list_product_media.php'); ?>
		
		<div class="card-body text-body">
			<form name="product" id="productForm-<?php echo $product->product_id; ?>" onchange="shopProductForm.formHandler(this, event)" class="cart-product__form" method="post" action="<?php echo $this->action; ?>" enctype="multipart/form-data" autocomplete="off">
				<?php include  templateOverrideBlock('blocks', 'list_product_info.php'); ?>
				
				<div class="cart-product__prices">
					<?php if ($this->display_price && $productUsergroupPermissions->is_usergroup_show_price) {
						include templateOverrideBlock('blocks', 'prices_product.php');
					} ?> 
				</div>
				
				<?php echo $product->_tmp_individual_product_list_html_before_weight ?? ''; ?>
				
				<?php if ($jshopConfig->product_list_show_weight && (!empty($product->preview_calculated_weight) || $product->getWeight() > 0)) : ?>
					<?php include  templateOverrideBlock('blocks', 'list_product_weight.php'); ?>
				<?php endif; ?>

                <?php if ($jshopConfig->product_list_show_product_code && (!empty($product->product_ean))) : ?>
					<?php include  templateOverrideBlock('blocks', 'list_product_code.php'); ?>					
				<?php endif; ?>

				<?php if ($jshopConfig->stock && $jshopConfig->product_list_show_qty_stock) : ?>				
					<?php include  templateOverrideBlock('blocks', 'list_product_quantity.php'); ?>					
				<?php endif; ?>

				<?php if ($this->allow_review && $product->reviews_count > 0) {
					echo showMarkStar($product->average_rating);
				} ?>
			
				<?php if($jshopConfig->productlist_allow_buying) : ?>
					<?php $this->attributes = $product->attributes; ?>
					<?php if (!empty($this->attributes)) : ?>
						<?php include templateOverrideBlock('blocks', 'attributes.php'); ?>
					<?php endif; ?>				
					
					<?php print isset($product->tmp_product_inlist_html_after_attr) ? $product->tmp_product_inlist_html_after_attr : ''; ?>
					
					<?php $this->product=$product;	?>
					<div class="free-attributes"  id="shop_upload_btn">
						<?php include templateOverrideBlock('blocks', 'free_attribute.php'); ?>
					</div>
					
                    <?php if($jshopConfig->productlist_allow_buying == 2) : ?>
                        <div class="product_quantity_list" >
                            <?php include templateOverrideBlock('blocks', 'product_list_quantity.php'); ?>
                        </div>
                    <?php endif; ?>
					
                    <li class="flex-fill mb-2 shop_cart_btn d-grid" id="cart-product__cart">						
						<?php if ($productUsergroupPermissions->is_usergroup_show_price && $productUsergroupPermissions->is_usergroup_show_buy && $product->isShowCartSection() && !$jshopConfig->user_as_catalog) : ?>
							<?php if (!$jshopConfig->not_redirect_in_cart_after_buy) { ?>
								<button type="submit" class="btn btn-outline-primary d-grid" onclick="shopHelper.replaceFormActionText('form#productForm-<?php echo $product->product_id; ?>', '<?php echo $sefLinkToCartAdd; ?>')">
									<?php echo JText::_('COM_SMARTSHOP_ADD_TO_CART'); ?>
								</button>
							<?php } else { ?>
								<button type="button" class="btn btn-outline-primary d-grid" onclick="shopHelper.sendAjax2('POST', '<?php echo $sefLinkToCartAdd; ?>&isAjaxRequest=1', shopHelper.gatherFormData(document.getElementById('productForm-<?php echo $product->product_id; ?>')),{
    beforeSend: function() {        
    },
    success: function(html) {
		if (html.html!=''){
			var smartshopCartLink = document.querySelector('.smartshop-cart__link.smartshop-cart_animate');
			var cardBodyElement = smartshopCartLink.closest('.card-body');
			cardBodyElement.innerHTML=html.html;
		}
    },
    error: function(error) {        
    },
    complete: function() {
    }
}



 )">
    <?php echo JText::_('COM_SMARTSHOP_ADD_TO_CART'); ?>
</button>
							<?php } ?>

						<?php endif; ?>
					</li>	

					<li class="flex-fill mb-2 d-grid tmpProductListHtmlAfterAddToCart">	
						<?php if ($productUsergroupPermissions->is_usergroup_show_price && $productUsergroupPermissions->is_usergroup_show_buy && $product->isShowCartSection()) : ?>
							<div class="tmpProductListHtmlAfterAddToCart__wrapper">		
								<?php echo isset($product->_tmp_individual_product_list_html_after_add_to_cart) ? $product->_tmp_individual_product_list_html_after_add_to_cart : ''; ?>
							</div>
						<?php endif; ?>
					</li>
				<?php endif; ?>

				<?php 
					if (!$jshopConfig->user_as_catalog) {
						include templateOverrideBlock('blocks', 'checkout_button_product_list.php'); 
					}
				?>
				
				<li class="mb-2 d-grid cart-product__wishlist">	
					<?php if ($productUsergroupPermissions->is_usergroup_show_price && $productUsergroupPermissions->is_usergroup_show_buy && $this->enable_wishlist && $this->show_wishlist_button && !$jshopConfig->user_as_catalog) {
						include templateOverrideBlock('blocks', 'products_wishlist_btn.php');
					} ?>
				</li>

				<input type="hidden" name="product_id" value="<?php echo $product->product_id; ?>" />
			</form>
		</div>
	</div>
</div>
