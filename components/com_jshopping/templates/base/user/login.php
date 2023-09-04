<?php
/**
* @version 1.0 smartSHOP BS4
*/
defined('_JEXEC') or die('Restricted access');

$cart = JModelLegacy::getInstance('cart', 'jshop');
$cart->load("cart");
?>

<div class="shop shop-login">

	<h1 class="shop-login__page-title"><?php echo JText::_('COM_SMARTSHOP_LOGIN'); ?></h1>

	<div class="row">

		<div class="col-md-6 login-form">
			<form method="post" id="loginForm" action="<?php print SEFLink('index.php?option=com_jshopping&controller=user&task=loginsave', 1,0, $this->config->use_ssl)?>" name="jlogin">
				
				<?php include templateOverrideBlock('blocks', 'login_block.php'); ?>	

				<input type="hidden" name="return" value="<?php echo $this->return; ?>" />
				<?php echo JHtml::_('form.token'); ?>
			</form>
		</div> 

		<div class="col-md-6 guest-info">
			<?php include templateOverrideBlock('blocks', 'login_register_block.php'); ?>						
		</div> 

	</div> 

</div> 