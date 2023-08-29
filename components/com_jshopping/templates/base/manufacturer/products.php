<?php
/**
* @version 1.0 smartSHOP BS4
*/
defined('_JEXEC') or die('Restricted access');
?>

<div class="shop manufacturer-products">
	<h1 class="manufacturer-products__page-title"><?php echo $this->manufacturer->name; ?></h1>

	<?php if ($this->manufacturer->description) {
		echo $this->manufacturer->description;
	} ?>


	<div class="row">
		<?php if (!empty($this->rows)) {
			include  templateOverrideBlock('blocks', 'list_products.php');
		} else {
			include  templateOverrideBlock('blocks', 'no_products.php');
		}?>
	</div>

	<?php if ($this->display_pagination) {
         include templateOverrideBlock('blocks', 'block_pagination.php');
	}?>
</div>
