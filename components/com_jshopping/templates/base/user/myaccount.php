<?php
/**
* @version 1.0 smartSHOP BS4
*/
defined('_JEXEC') or die('Restricted access');

$config_fields = $this->config_fields;
?>

<div class="shop shop-account">

    <h1 class="shop-account__page-title mb-4"><?php echo JText::_('COM_SMARTSHOP_MY_ACCOUNT'); ?></h1>

	<div class="myAccountCards">
		<div class="row">
			<?php include templateOverrideBlock('blocks', 'myaccount_address_btn.php'); ?>				
			
			<?php echo $this->tmpl_after_address ?? ''; ?>
			
			<?php include templateOverrideBlock('blocks', 'myaccount_profile_btn.php'); ?>

			<?php echo $this->tmpl_after_authentication ?? ''; ?>

			<?php include templateOverrideBlock('blocks', 'myaccount_orders_btn.php'); ?>				

			<?php echo $this->tmpl_after_my_orders ?? ''; ?>

			<?php if ($this->config->allow_offer_on_product_details_page || $this->config->allow_offer_in_cart) : ?>
				<?php include templateOverrideBlock('blocks', 'myaccount_offer_btn.php'); ?>
			<?php endif; ?>

			<?php echo $this->tmpl_after_offer_and_order_my_offer ?? ''; ?>

			<?php if ($this->config->enable_wishlist) : ?>
				<?php include templateOverrideBlock('blocks', 'myaccount_wishlist_btn.php'); ?>				
			<?php endif; ?>

			<?php echo $this->tmpl_after_wishlist ?? ''; ?>

			<?php if (isSmartEditorEnabled()) : ?>
				<?php include templateOverrideBlock('blocks', 'myaccount_design_btn.php'); ?>
			<?php endif; ?>

			<?php echo $this->tmpl_after_saved_designs ?? ''; ?>
		</div>
		<div class="row">
			<a href="<?php echo SEFLink('index.php?option=com_jshopping&view=user&task=logout', 1); ?>" class="col-md-12">
				<?php echo JText::_('COM_SMARTSHOP_LOGOUT'); ?>
			</a>
		</div>
	</div>

</div>
