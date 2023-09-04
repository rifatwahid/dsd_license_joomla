<?php
/**
* @version 1.0 smartSHOP BS4
*/
defined('_JEXEC') or die('Restricted access');

$jSUri = JSFactory::getJSUri();
?>

<div class="shop wishlist">

	<h1 class="wishlist__page-title"><?php echo JText::_('COM_SMARTSHOP_WISHLIST'); ?></h1>

	<div class="row">
		<?php if (!empty($this->products)) : ?>
			<?php foreach($this->products as $key_id => $prod) : 
				$product = JSFactory::getTable('product', 'jshop');
				$product->load($prod['product_id']);
			?>
			
				<div class="col-sm-6 col-md-4 col-lg-3 card-group mb-5">
					<div class="card">

						<?php include  templateOverrideBlock('blocks', 'wishlist_product_media.php'); ?>						

						<div class="card-body text-body">	
						
							<?php include  templateOverrideBlock('blocks', 'wishlist_product_info.php'); ?>
							
						</div>

						<div class="mx-auto w-100 p-3 m-3">
							<?php include  templateOverrideBlock('blocks', 'wishlist_product_buttons.php'); ?>							
						</div>

					</div>
				</div>
				
			<?php endforeach; ?>
		<?php else : ?>
		
			<p><?php echo JText::_('COM_SMARTSHOP_WISHLIST_EMPTY'); ?></p>
			
		<?php endif; ?>
	</div>
</div>