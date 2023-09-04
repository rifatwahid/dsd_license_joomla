<?php
/**
* @version 1.0 smartSHOP BS4
*/
defined('_JEXEC') or die('Restricted access');

$jsUri = JSFactory::getJSUri();
?>

<div class="shop shop-cart" id="comjshop">
	<h1 class="hidden"><?php echo JText::_('COM_SMARTSHOP_CART'); ?></h1>
    <?php if (!empty($this->products)) : ?>
		<form action="<?php echo SEFLink('index.php?option=com_jshopping&controller=cart&task=refresh') ?>" id="updateCartForm" method="post" name="updateCart">

			<?php include  templateOverrideBlock('blocks', 'cart_products.php'); ?>
			
			<?php include  templateOverrideBlock('blocks', 'fast_admin_links.php'); ?>
		</form>


		<div class="row my-4">
			<div class="col-md-6 col-lg-7">
				<?php include templateOverrideBlock('blocks', 'cart_rabbat.php');  ?>
			</div> 
			<div class="col cart-calculation-block">
				<?php include templateOverrideBlock('blocks', 'cart_calculation.php');  ?>				
			</div>
		</div> 

		<?php include  templateOverrideBlock('blocks', 'form_create_offer.php'); ?>
		
		<?php include  templateOverrideBlock('blocks', 'cart_checkout.php'); ?>

		<?php print $this->_tmp_ext_html_before_discount ?? ''; ?>
		
  	<?php else : ?>
    	<p>
			<?php echo JText::_('COM_SMARTSHOP_CART_IS_EMPTY'); ?>
		</p>
  	<?php endif; ?>

</div> <!-- shop-cart -->