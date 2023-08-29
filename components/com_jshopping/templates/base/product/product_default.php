<?php
/**
* @version 1.0 smartSHOP BS4
*/
defined('_JEXEC') or die('Restricted access');

$product = $this->product;
$smart_link = $this->smartLink;
$jshopConfig = JSFactory::getConfig();

$productWithActivePricesPerCons = $product->getEssenceWithActivePricesPerCons();
$productUsergroupPermissions = $product->getUsergroupPermissions();
$this->productUsergroupPermissions = $productUsergroupPermissions;
?>

<div class="shop product-details">
    <?php if($jshopConfig->display_preloader){ ?>
        <div id="dsd-spinner_loading_block">
            <div id="spinner_loading" class="loading">
                <i class="fas fa-spinner fa-spin fa-4x"></i>
            </div>
        </div>
    <?php } ?>

    <div class="row">

		<div class="col-md-6 col-lg-7" id="image-video-block">
			<?php include templateOverrideBlock('blocks','media_product_block.php'); ?>
		</div> 

		<div class="col-md-6 col-lg-5 ps-lg-4">
  			<?php include templateOverrideBlock('blocks', 'product_info.php'); ?>	

            <form name="product" id="productForm" method="post" action="<?php echo $this->action; ?>" enctype="multipart/form-data" autocomplete="off" onchange="shopProductForm.formHandler(this, event)">
            	<?php echo $this->_corrugated_transfer_diecut_to_editor ?? ''; ?>

				<div class="free-attributes"  id="shop_upload_btn">
                 	<?php include_once(templateOverrideBlock('blocks', 'free_attribute.php')); ?>
				</div>

				<?php include templateOverrideBlock('blocks', 'attributes.php'); ?>

                <div class="form-group product__quantity">
					<?php include templateOverrideBlock('blocks', 'product_quantity.php'); ?>	
				</div>				
				
				<?php echo $this->_tmp_product_html_before_buttons ?? ''; ?>

                <ul class="list-inline flex-wrap my-4">
                    <li class="list-inline-item flex-fill d-grid mb-2" id="product-details__uploads">
                        <?php
                        if (!$this->jshopConfig->user_as_catalog) {
                            include templateOverrideBlock('blocks', 'default_prod_upload.php');
                        }
                        ?>
                    </li>

					<li class="list-inline-item flex-fill d-grid mb-2">
						<?php include templateOverrideBlock('blocks', 'smart_link_btn.php'); ?>
					</li>	
					
					<?php 
						if (file_exists(templateOverrideBlock('blocks', 'editor_button_product.php'))) {
							include templateOverrideBlock('blocks', 'editor_button_product.php');
						}
					?>

					<li class="list-inline-item flex-fill d-grid mb-2" id="product-details__wishlist">
						<?php 
							if (!$this->jshopConfig->user_as_catalog) {
								include templateOverrideBlock('blocks', 'wishlist_btn.php'); 
							}
						?>
					</li>

					<li class="list-inline-item flex-fill mb-2 d-grid shop_cart_btn" id="product-details__cart">
						<?php
							if (!$this->jshopConfig->user_as_catalog) {
								include  templateOverrideBlock('blocks', 'cart_product.php');
							}
						?>
					</li>

					<li class="list-inline-item flex-fill mb-2 d-grid tmpProductHtmlAfterAddToCart">
						<?php include templateOverrideBlock('blocks', 'after_add_to_cart_product.php'); ?>
					</li>

					<?php 
						if (!$this->jshopConfig->user_as_catalog) {
							include templateOverrideBlock('blocks', 'checkout_button_product.php'); 
						}
					?>	
					
				</ul>

				<?php include templateOverrideBlock('blocks', 'offer_and_order_prooduct.php');  ?> 
                <?php include templateOverrideBlock('blocks', 'availability_delivery_info.php'); ?>	

                <div id="productBulkPrices" class="list-unstyled text-muted">
					<?php include templateOverrideBlock('blocks', 'bulk_prices.php'); ?>
				</div>

				<?php print $this->_tmp_product_html_after_buttons ?? ''; ?>

                <input type="hidden" name="to" id='to' value="cart" />
                <input type="hidden" name="product_id" id="product_id" value="<?php echo $this->product->product_id; ?>" />
                <input type="hidden" name="category_id" id="category_id" value="<?php echo $this->category_id; ?>" />
          	</form>

        </div>
    </div> 

   <?php include templateOverrideBlock('blocks', 'default_prod_tablist.php'); ?>
	
	<div id="product-details__demofiles">
		<?php include templateOverrideBlock('blocks', 'demofiles.php'); ?>
	</div>
	
	<?php echo $this->_tmp_product_html_before_related ?? ''; ?>

	<div id="product__relateds">
		<?php include templateOverrideBlock('blocks', 'related.php'); ?>
	</div>
	
	<div id="product__reviews">
		<?php include templateOverrideBlock('blocks', 'review.php'); ?>
	</div>

</div>
