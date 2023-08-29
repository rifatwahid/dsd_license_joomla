 <ul class="list-group cart-items">		
	<?php  foreach ($this->products as $key_id => $prod) : ?>
		<li class="list-group-item cart-items__item" id="cartProduct<?php echo $key_id; ?>">
			<div class="row">
				<div class="col-sm-3">
					<?php include  templateOverrideBlock('blocks', 'cart_products_image.php'); ?>				
				</div>
				
				<div class="col">
					<div class="media-body row">
					
						<div class="col-md-6 col-lg-7">						
							<?php include  templateOverrideBlock('blocks', 'cart_products_info.php'); ?>							
						</div> 
						
						<div class="col-md-3 col-lg-2 text-center">
							<div class="d-flex align-items-center flex-md-column">								
								<?php include  templateOverrideBlock('blocks', 'cart_products_quantity.php'); ?>
								<div class="cart-item-action flex-fill px-4 mt-md-2">
									<?php include  templateOverrideBlock('blocks', 'cart_products_update.php'); ?>
								    <?php include  templateOverrideBlock('blocks', 'cart_products_remove.php'); ?>
								</div>
							</div>
						</div>

						<div class="col-md-3 border-0 text-end smartshop_cart_price_tax_cell">
							<?php include  templateOverrideBlock('blocks', 'cart_products_prices.php'); ?>						
						</div> 

					</div> 
				</div>
			</div>
			
		</li>
	<?php endforeach; ?>

</ul>