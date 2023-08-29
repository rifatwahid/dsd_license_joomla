<?php 
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

$row = $this->manufacturer;
$edit = $this->edit;
$jshopConfig = JSFactory::getConfig();
?>

<form action="index.php?option=com_jshopping&controller=manufacturers" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
	<?php echo $this->tmp_html_start ?? ''; ?>

	<?php if (!isJoomla4()) : ?>
		<ul class="nav nav-tabs">
			<li class="active">
				<a href="#lang-page" data-toggle="tab">
					<?php echo JText::_('COM_SMARTSHOP_DESCRIPTION'); ?>
				</a>
			</li>
			<li><a href="#main-page" data-toggle="tab"><?php echo JText::_('COM_SMARTSHOP_MAIN_PARAMETERS');?></a></li>
			<li><a href="#image" data-toggle="tab"><?php echo JText::_('COM_SMARTSHOP_IMAGE');?></a></li>
		</ul>
	<?php endif; ?>

	<div id="editdata-document" class="tab-content">

		<?php if (isJoomla4()) : ?>
			<?php echo HTMLHelper::_('uitab.startTabSet', 'myTab', ['active' => 'lang-page', 'recall' => true, 'breakpoint' => 768]); ?>
			<!-- Description tab -->
			<?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'lang-page', Text::_('COM_SMARTSHOP_DESCRIPTION')); ?>
		<?php endif; ?>
			<!-- Description -->
			<div id="lang-page" class="tab-pane active">
				<div class="jshops_edit manufacturer_edit">
					<?php
					$i = 0;   

					foreach($this->languages as $lang) {
						$i++;
						$name="name_".$lang->language;
						$alias="alias_".$lang->language;
						$description="description_".$lang->language;
						$short_description="short_description_".$lang->language;       
					?>
						
						<div class="form-group row align-items-center">
							<label for="title" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">
								<?php echo JText::_('COM_SMARTSHOP_TITLE'); ?>
								<img class="tab_image" src="components/com_jshopping/images/flags/<?php print $lang->lang?>.gif" />
							</label>
							<div class="col-sm-9 col-md-10 col-xl-10 col-12">
								<input type="text" id="title" class="inputbox wide form-control" name="<?php print $name?>" value="<?php print $row->$name?>" />
							</div>
						</div>
						
						<div class="form-group row align-items-center">
							<label for="alias" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">
								<?php echo JText::_('COM_SMARTSHOP_ALIAS'); ?>
								<img class="tab_image" src="components/com_jshopping/images/flags/<?php print $lang->lang?>.gif" />
							</label>
							<div class="col-sm-9 col-md-10 col-xl-10 col-12">
							<input type="text" class="inputbox wide form-control" id="alias" name="<?php print $alias?>" value="<?php print $row->$alias?>" />
							</div>
						</div>
						<div class="form-group row align-items-center">
							<label for="short_description_<?php print $lang->id ?>" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">
								<?php echo JText::_('COM_SMARTSHOP_SHORT_DESCRIPTION'); ?>
								<img class="tab_image" src="components/com_jshopping/images/flags/<?php print $lang->lang?>.gif" />
							</label>
							<div class="col-sm-9 col-md-10 col-xl-10 col-12">
							<?php
								$editor = \JEditor::getInstance(\JFactory::getConfig()->get('editor'));
								print $editor->display('short_description_'.$lang->id, $row->$short_description , '100%', '350', '75', '20' ) ;              
							?>
							</div>
						</div>
						
						<div class="form-group row align-items-center">
							<label for="description<?php print $lang->id ?>" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">
								<?php echo JText::_('COM_SMARTSHOP_DESCRIPTION'); ?>
								<img class="tab_image" src="components/com_jshopping/images/flags/<?php print $lang->lang?>.gif" />
							</label>
							<div class="col-sm-9 col-md-10 col-xl-10 col-12">
							<?php
								$editor = \JEditor::getInstance(\JFactory::getConfig()->get('editor'));
								print $editor->display('description'.$lang->id, $row->$description , '100%', '350', '75', '20' ) ;              
							?>
							</div>
						</div>
						<div class="clr"></div>
					<?php }?>
				</div>
			</div>

		<?php if (isJoomla4()) : ?> 
			<?php echo HTMLHelper::_('uitab.endTab'); ?>
			<!-- Details tab -->
			<?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'main-page', Text::_('COM_SMARTSHOP_MAIN_PARAMETERS')); ?>
		<?php endif; ?>
	
			<!-- Details -->
			<div id="main-page" class="tab-pane">
				<div class="jshops_edit manufacturer_edit_config">
					<div class="form-group row align-items-center">
						<label for="manufacturer_publish" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">
							<?php echo JText::_('COM_SMARTSHOP_PUBLISH');?>
						</label>
						<div class="col-sm-9 col-md-10 col-xl-10 col-12">
							<input type="checkbox" class="inputbox form-check-input" id="manufacturer_publish" name="manufacturer_publish" value="1" value="1" <?php if ($row->manufacturer_publish) echo 'checked="checked"'?>  />
						</div>
					</div>
				
					<div class="form-group row align-items-center">
						<label for="products_page" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">
							<?php echo JText::_('COM_SMARTSHOP_COUNT_PRODUCTS_PAGE');?>*
						</label>
						<div class="col-sm-9 col-md-10 col-xl-10 col-12">
							<input type="text" class="inputbox form-control" id="products_page" name="products_page" value="<?php echo $count_product_page=($row->manufacturer_id) ? ($row->products_page) : ($jshopConfig->count_products_to_page);?>" />
						</div>
					</div>
					<?php $pkey="etemplatevar";if ($this->$pkey){print $this->$pkey;}?>
				</div>
			</div> 

		<?php if (isJoomla4()) : ?> 
			<?php echo HTMLHelper::_('uitab.endTab'); ?>
			<!-- Images tab -->
			<?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'image', Text::_('COM_SMARTSHOP_IMAGE')); ?>
		<?php endif; ?>
   
		<!-- Image -->
		<div id="image" class="tab-pane">
			<div class="jshops_edit manufacturer_image_edit">
				<div class="form-group row align-items-center">
					<label for="manufacturer_logo_button" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">							
						<?php echo JText::_('COM_SMARTSHOP_IMAGE_SELECT');?>
					</label>
					<div class="col-sm-9 col-md-10 col-xl-10 col-12">
						<?php echo LayoutHelper::render('fields.media', [
							'name' => 'manufacturer_logo',
							'id' => 'manufacturer_logo',
							'folder' => 'img_manufs',
							'type' => 'smartshopimgs',
							'preview' => 'tooltip',
							'value' => $row->manufacturer_logo
						]); ?>
					</div>
				</div>     
			</div>
		</div>
		<?php if (isJoomla4()) {
			echo HTMLHelper::_('uitab.endTab');
			echo HTMLHelper::_('uitab.endTabSet');
		} ?>
	</div>

   <script>
   window.addEventListener("load", () => {
		Joomla.submitbutton = function(task) {
			if (task == 'save' || task == 'apply') {
				if (!parseInt(shopHelper.getValue('products_page'))) {
					alert ('<?php echo JText::_('COM_SMARTSHOP_WRITE_PRODUCTS_PAGE'); ?>');
					return 0;
				} else if (shopHelper.isEmpty(shopHelper.getValue('category_width_image')) && shopHelper.isEmpty(shopHelper.getValue('category_height_image'))){
					alert ('<?php echo JText::_('COM_SMARTSHOP_WRITE_SIZE_BAD')?>');
					return 0;
				}
			}

			Joomla.submitform(task, document.getElementById('adminForm'));
		}
    });
   </script>

	<input type="hidden" id="category_width_image" name="category_width_image" value="<?php echo $jshopConfig->image_category_width?>" disabled="disabled" />
	<input type="hidden" id="category_height_image" name="category_height_image" value="<?php echo $jshopConfig->image_category_height?>" disabled="disabled" />           
	<input type="hidden" name="old_image" value="<?php echo $row->manufacturer_logo?>" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="edit" value="<?php echo $edit;?>" />
	<input type="hidden" name="manufacturer_id" value="<?php echo $row->manufacturer_id?>" />
	<?php echo $this->tmp_html_end ?? ''; ?>
</form>