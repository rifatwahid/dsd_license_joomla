<?php 

defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

$row = $this->category;
$lists = $this->lists;
$jshopConfig = JSFactory::getConfig();
?>

<div class="jshop_edit category_edit">
	<form action="index.php?option=com_jshopping&controller=categories" method="post" enctype="multipart/form-data" name="adminForm" id="adminForm">
		<?php echo $this->tmp_html_start ?? ''; ?>

		<?php if (!isJoomla4()) : ?>
			<ul class="nav nav-tabs">
				<li class="active">
					<a href="#description-page" data-toggle="tab">
						<?php echo JText::_('COM_SMARTSHOP_DESCRIPTION'); ?>
					</a>
				</li>        
				<li>
					<a href="#main-page" data-toggle="tab"><?php echo JText::_('COM_SMARTSHOP_MAIN_PARAMETERS');?></a>
				</li>
				<li>
					<a href="#image" data-toggle="tab"><?php echo JText::_('COM_SMARTSHOP_IMAGE');?></a>
				</li>
			</ul>
		<?php endif; ?>

		<div id="editdata-document" class="tab-content">
			<?php if (isJoomla4()) {
				echo HTMLHelper::_('uitab.startTabSet', 'myTab', ['active' => 'description-page', 'recall' => true, 'breakpoint' => 768]); 
				echo HTMLHelper::_('uitab.addTab', 'myTab', 'description-page', Text::_('COM_SMARTSHOP_DESCRIPTION'));
			} ?>
					<div id="description-page" class="tab-pane active">
						<?php $i = 0;
						foreach($this->languages as $lang) :
							$i++;
							$name="name_".$lang->language;
							$alias="alias_".$lang->language;
							$description="description_".$lang->language;
							$short_description="short_description_".$lang->language;
							$meta_title="meta_title_".$lang->language;
							$meta_keyword="meta_keyword_".$lang->language;
							$meta_description="meta_description_".$lang->language;
						?>
							<div class="jshops_edit category_title_description_edit">
								<div class="form-group row align-items-center">
									<label for="<?php print $name?>" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
										<?php echo JText::_('COM_SMARTSHOP_TITLE'). ' ' . $lang->lang; ?>*
									</label>
									<div class="col-sm-9 col-md-10 col-xl-10 col-12">
										<input type="text" class="inputbox wide form-control" name="<?php print $name?>" id="<?php print $name?>" value="<?php print $row->$name?>" />
									</div>
								</div>

								<div class="form-group row align-items-center">
									<label for="<?php print $alias?>" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
										<?php echo JText::_('COM_SMARTSHOP_ALIAS'). ' ' . $lang->lang; ?>*
									</label>
									<div class="col-sm-9 col-md-10 col-xl-10 col-12">
										<input type="text" class="inputbox wide form-control" id="<?php print $alias?>" name="<?php print $alias?>" value="<?php print $row->$alias?>" />
									</div>
								</div>

								<div class="form-group row align-items-center">
									<label for="<?php print $short_description ?>" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
										<?php echo JText::_('COM_SMARTSHOP_SHORT_DESCRIPTION'). ' ' . $lang->lang; ?>
									</label>
									<div class="col-sm-9 col-md-10 col-xl-10 col-12">
										<?php
											$editor = \JEditor::getInstance(\JFactory::getConfig()->get('editor'));                
											echo $editor->display($short_description,  $row->$short_description , '100%', '350', '75', '20' );
										?>
									</div>
								</div>

								<div class="form-group row align-items-center">
									<label for="<?php print 'description'.$lang->id; ?>" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
										<?php echo JText::_('COM_SMARTSHOP_DESCRIPTION'). ' ' . $lang->lang; ?>
									</label>
									<div class="col-sm-9 col-md-10 col-xl-10 col-12">
										<?php
											$editor = \JEditor::getInstance(\JFactory::getConfig()->get('editor'));
											echo $editor->display('description'.$lang->id, $row->$description , '100%', '350', '75', '20' ) ;              
										?>
									</div>
								</div>

								<?php $pkey = 'plugin_template_description_'.$lang->language;  print $this->$pkey ?? ''; ?>
							</div>
							<div class="clr"></div>
						<?php endforeach; ?>
					</div>   

				<?php if (isJoomla4()) {
					echo HTMLHelper::_('uitab.endTab');
					echo HTMLHelper::_('uitab.addTab', 'myTab', 'main-page', Text::_('COM_SMARTSHOP_MAIN_PARAMETERS'));
				} ?> 

					<div id="main-page" class="tab-pane">
						<div class="jshops_edit category_details_edit">
						<div class="form-group row align-items-center">
							<label for="category_publish" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
								<?php echo JText::_('COM_SMARTSHOP_PUBLISH');?>
							</label>
							<div class="col-sm-9 col-md-10 col-xl-10 col-12">
								<input type="checkbox" name="category_publish" class="form-check-input" id="category_publish" value="1" <?php if ($row->category_publish) echo 'checked="checked"'?> />
							</div>
						</div>

						<div class="form-group row align-items-center">
							<label for="access" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
								<?php echo JText::_('COM_SMARTSHOP_ACCESS');?>*
							</label>
							<div class="col-sm-9 col-md-10 col-xl-10 col-12">
								<?php echo $this->lists['access']; ?>
							</div>
						</div>

						<div class="form-group row align-items-center">
							<label for="ordering" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
								<?php echo JText::_('COM_SMARTSHOP_ORDERING_CATEGORY');?>
							</label>
							<div class="col-sm-9 col-md-10 col-xl-10 col-12">
								<?php echo $lists['onelevel']; ?>
							</div>
						</div>
								
						<?php if ($jshopConfig->use_different_templates_cat_prod) { ?>
						<div class="form-group row align-items-center">
							<label for="category_template" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
								<?php echo JText::_('COM_SMARTSHOP_TEMPLATE_CATEGORY');?>
							</label>
							<div  class="col-sm-9 col-md-10 col-xl-10 col-12">
								<?php echo $lists['templates']?>
							</div>
						</div>
						<?php } ?>

						<div class="form-group row align-items-center">
							<label for="products_page" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
								<?php echo JText::_('COM_SMARTSHOP_COUNT_PRODUCTS_PAGE');?>*
							</label>
							<div class="col-sm-9 col-md-10 col-xl-10 col-12">
								<input type="text" class="inputbox form-control" id="products_page" name="products_page" value="<?php echo $count_product_page=($row->category_id) ? ($row->products_page) : ($jshopConfig->count_products_to_page);?>" />
							</div>
						</div>

						<div class="form-group row align-items-center">
							<label for="category_parent_id" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
								<?php echo JText::_('COM_SMARTSHOP_PARENT_CATEGORY');?>*
							</label>
							<div class="col-sm-9 col-md-10 col-xl-10 col-12">
								<?php echo $lists['treecategories'];?>
							</div>
						</div>
						<?php $pkey="etemplatevar";if ($this->$pkey){print $this->$pkey;}?>       
						</div>
						<div class="clr"></div>
					</div> 

				<?php if (isJoomla4()) {
					echo HTMLHelper::_('uitab.endTab');
					echo HTMLHelper::_('uitab.addTab', 'myTab', 'image', Text::_('COM_SMARTSHOP_IMAGE'));
				} ?> 

					<div id="image" class="tab-pane">
						<div class="jshops_edit category_image_edit">
						<div class="form-group row align-items-center">
							<label for="btn_category_image" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
								<?php echo JText::_('COM_SMARTSHOP_IMAGE_SELECT');?>
							</label>
							<div class="col-sm-9 col-md-10 col-xl-10 col-12">
								<?php echo LayoutHelper::render('fields.media', [
									'name' => 'category_image',
									'id' => 'category_image',
									'folder' => 'img_categories',
									'preview' => 'tooltip',
									'value' => $row->category_image
								]); ?>
							</div>
						</div>
						<?php $pkey = 'plugin_template_img_'.$lang->language; print $this->$pkey ?? ''; ?>
						</div>

						<div class="clr"></div>
					</div>
					<?php if (isJoomla4()) {
						echo HTMLHelper::_('uitab.endTab');
						echo HTMLHelper::_('uitab.endTabSet');
					} ?>
		</div>
	
	<input type="hidden" id="category_width_image" name="category_width_image" value="<?php echo $jshopConfig->image_category_width; ?>" disabled="disabled" />
	<input type="hidden" id="category_height_image" name="category_height_image" value="<?php echo $jshopConfig->image_category_height; ?>" disabled="disabled" />           
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="category_id" value="<?php echo $row->category_id; ?>" />
	<input type="hidden" name="old_image" value="<?php echo $row->category_image;?>" />
	<script>
		window.addEventListener("load", () => {
			Joomla.submitbutton = function(task) {
				if (task == 'save' || task == 'apply') {
				if (!parseInt(shopHelper.getValue('products_page'))){
					alert ('<?php echo  JText::_('COM_SMARTSHOP_WRITE_PRODUCTS_PAGE')?>');
					return 0;
				} else if (shopHelper.isEmpty(shopHelper.getValue('category_width_image')) && shopHelper.isEmpty(shopHelper.getValue('category_height_image'))){
					alert ('<?php echo  JText::_('COM_SMARTSHOP_WRITE_SIZE_BAD')?>');
					return 0;
				}
				}

				Joomla.submitform(task, document.getElementById('adminForm'));
			}
		});
	</script>
	<?php echo $this->tmp_html_end ?? ''; ?>
	</form>
</div>