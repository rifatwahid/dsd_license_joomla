<?php
/**
* @version 1.0 smartSHOP BS4
*/
defined('_JEXEC') or die('Restricted access');
?>
<?php if (!empty($this->related_prod)) { ?>
	<section class="my-4">
		<div class="row">
			<?php foreach($this->related_prod as $k => $product) {
				include templateOverride('list_products', 'product.php');
			} ?>
		</div>
	</section>
<?php } ?>
