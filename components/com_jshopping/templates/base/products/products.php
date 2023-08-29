<?php
/**
* @version 1.0 smartSHOP BS4
*/
defined('_JEXEC') or die('Restricted access');
?>

<div class="shop list-products">
	<h1 class="list-products__page-title"><?php echo JFactory::getDocument()->getTitle(); ?></h1>

	<div class="row">		
		<?php if (!empty($this->rows)) {
			include templateOverrideBlock('blocks', 'list_products.php');
		} else {
			include templateOverrideBlock('blocks', 'no_products.php');
		}?>
    </div>

    <?php if ($this->display_pagination) : ?>
		<div class="list-products__pagination">
			<?php include templateOverrideBlock('blocks', 'block_pagination.php');?>
		</div>
	<?php endif; ?>
</div>

