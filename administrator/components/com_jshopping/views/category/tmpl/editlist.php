<?php 

defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

$row = $this->category;
$lists = $this->lists;
$jshopConfig = JSFactory::getConfig();
?>

<div class="jshop_edit category_edit_list">
	<form action="index.php?option=com_jshopping&controller=categories" method="post" enctype="multipart/form-data" name="adminForm" id="adminForm">
		<?php echo $this->tmp_html_start; ?>

		<?php if (!isJoomla4()) : ?>
			<ul class="nav nav-tabs">
				<li><a href="#main-page" data-toggle="tab"><?php echo JText::_('COM_SMARTSHOP_MAIN_PARAMETERS'); ?></a></li>
			</ul>
		<?php endif; ?>

		<div id="editdata-document" class="tab-content">
			<?php if (isJoomla4()) : ?>
				<?php echo HTMLHelper::_('uitab.startTabSet', 'myTab', ['active' => 'main-page', 'recall' => true, 'breakpoint' => 768]); ?>
				<!-- Details tab -->
				<?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'description-page', Text::_('COM_SMARTSHOP_MAIN_PARAMETERS')); ?>
			<?php endif; ?>
				<div id="main-page" class="tab-pane active">
					<div class="jshops_edit category_config_edit">
						<div class="form-group row align-items-center">
							<label for="category_publish" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
								<?php echo JText::_('COM_SMARTSHOP_PUBLISH'); ?>
							</label>
							<div class="col-sm-9 col-md-10 col-xl-10 col-12">
								<?php echo $lists['category_publish']; ?>
							</div>
						</div>

						<div class="form-group row align-items-center">
							<label for="access" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
								<?php echo JText::_('COM_SMARTSHOP_ACCESS'); ?>*
							</label>

							<div class="col-sm-9 col-md-10 col-xl-10 col-12">
								<?php print $this->lists['access']; ?>
							</div>
						</div>

						<?php if ($jshopConfig->use_different_templates_cat_prod) : ?>
							<div class="form-group row align-items-center">
								<label for="category_template" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
									<?php echo JText::_('COM_SMARTSHOP_TEMPLATE_CATEGORY');?>
								</label>

								<div class="col-sm-9 col-md-10 col-xl-10 col-12">
									<?php echo $lists['templates']; ?>
								</div>
							</div>
						<?php endif; ?>

						<div class="form-group row align-items-center">
							<label for="products_page" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
								<?php echo JText::_('COM_SMARTSHOP_COUNT_PRODUCTS_PAGE');?>*
							</label>
							<div class="col-sm-9 col-md-10 col-xl-10 col-12">
								<input type="text" class="inputbox form-control" id="products_page" name="products_page" value="" />
							</div>
						</div>

						<div class="form-group row align-items-center">
							<label for="category_parent_id" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
								<?php echo JText::_('COM_SMARTSHOP_PARENT_CATEGORY'); ?>*
							</label>
							<div class="col-sm-9 col-md-10 col-xl-10 col-12">
								<?php echo $lists['treecategories']; ?>
							</div>
						</div>

						<?php $pkey="etemplatevar";if ($this->$pkey){echo $this->$pkey;}?>       
					</div>
					<div class="clr"></div>
				</div> 
			<?php if (isJoomla4()) {
				echo HTMLHelper::_('uitab.endTab');
				echo HTMLHelper::_('uitab.endTabSet');
			} ?>
		</div>

		<input type="hidden" name="task" value="" />
		<?php foreach($this->cid as $cid) : ?>
			<input type="hidden" name="cid[]" value="<?php echo $cid; ?>">
		<?php endforeach; ?>

		<script>
			window.addEventListener("load", () => {
				Joomla.submitbutton = function(task){
					if (task == 'save' || task == 'apply') {
						if (!parseInt(shopHelper.getValue('products_page'))) {
							alert ('<?php echo  JText::_('COM_SMARTSHOP_WRITE_PRODUCTS_PAGE')?>');
							return 0;
						}else if (shopHelper.isEmpty(shopHelper.getValue('category_width_image')) && shopHelper.isEmpty(shopHelper.getValue('category_height_image'))){
							alert ('<?php echo  JText::_('COM_SMARTSHOP_WRITE_SIZE_BAD')?>');
							return 0;
						}
					}

					Joomla.submitform(task, document.getElementById('adminForm'));
				}
			});
		</script>
		<?php echo $this->tmp_html_end; ?>
	</form>
</div>