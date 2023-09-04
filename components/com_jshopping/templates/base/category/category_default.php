<?php
/**
* @version 1.0 smartSHOP BS4
*/
defined('_JEXEC') or die('Restricted access');
?>

<div class="shop category-list">

	<h1 class="category-list__page-title"><?php echo $this->category->name; ?></h1>

	<?php if ($this->category->description) {
		echo $this->category->description;
	}  
	
	if ($this->categories) : ?>
		<div class="row">
			<?php foreach($this->categories as $k => $category) : ?>
				
				<?php include  templateOverrideBlock('blocks', 'category_info.php'); ?>
				
			<?php endforeach; ?>

		</div> 
	<?php endif; ?>

</div> 

<?php 
	if (file_exists(templateOverrideBlock('blocks', 'smarteditorlink.php'))) {
		include templateOverrideBlock('blocks', 'smarteditorlink.php');
	}

	include templateOverride('category','products.php');
?>