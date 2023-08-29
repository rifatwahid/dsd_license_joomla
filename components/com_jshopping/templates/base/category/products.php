<?php
/**
* @version 1.0 smartSHOP BS4
*/
defined('_JEXEC') or die('Restricted access');
?>

<div class="shop category-products">

	<div class="row">
		<?php if (!empty($this->rows)) {
			include  templateOverrideBlock('blocks', 'list_products.php');
		} else {
			include  templateOverrideBlock('blocks', 'no_products.php');
		} ?>
	</div>

  <?php if ($this->display_pagination) {
      include templateOverrideBlock('blocks', 'block_pagination.php');
  } ?>
</div>
