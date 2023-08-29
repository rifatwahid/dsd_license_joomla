<?php
/**
* @version      4.9.0 13.08.2013
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Layout\LayoutHelper;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

$row = $this->productLabel;
?>

<form action="index.php?option=com_jshopping&controller=productlabels" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
	<?php echo $this->tmp_html_start ?? ''?>

	<?php if (!isJoomla4()) : ?>
		<ul class="nav nav-tabs">
			<?php $i=0; foreach($this->languages as $lang){ $i++;?>
			<li <?php if ($i==1){?>class="active"<?php }?>>
				<a href="#<?php print $lang->lang.'-page'?>" data-toggle="tab">
					<?php echo JText::_('COM_SMARTSHOP_DESCRIPTION')?><?php if ($this->multilang){?> (<?php print $lang->lang?>)<img class="tab_image" src="components/com_jshopping/images/flags/<?php print $lang->lang?>.gif" /><?php }?>
				</a>
			</li>
			<?php }?>
		</ul>
	<?php endif; ?>

	<div id="editdata-document" class="tab-content">
		<?php if (isJoomla4()) {
			echo HTMLHelper::_('uitab.startTabSet', 'myTab', ['recall' => true, 'breakpoint' => 768]);
		} ?>		

			<?php $i=0;
			foreach($this->languages as $lang) :
					$i++;
					$name="name_".$lang->language;
					$image="image_".$lang->language;
				?>
				<?php if (isJoomla4()) {
					$title = JText::_('COM_SMARTSHOP_DESCRIPTION') . ($this->multilang ? '(' . $lang->lang . ') <img class="tab_image" src="components/com_jshopping/images/flags/' . $lang->lang . '.gif"/>': '');
					echo HTMLHelper::_('uitab.addTab', 'myTab', 'image', $title);
				} ?>		

				<div id="<?php print $lang->lang.'-page'?>" class="tab-pane<?php if ($i==1){?> active<?php }?>">
					<div class="jshops_edit product_labels_edit">
						<div class="form-group row align-items-center">
							<label for="<?php print $name?>" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">
								<?php echo JText::_('COM_SMARTSHOP_NAME');?> <?php if ($this->multilang) print "(".$lang->lang.")";?>*
							</label>
							<div id="ordering" class="col-sm-9 col-md-10 col-xl-10 col-12">
								<input type="text" class="inputbox form-control" id="<?php print $name?>" name="<?php print $name?>" value="<?php echo $row->$name;?>" />
							</div>
						</div>
						
						<div class="form-group row align-items-center">
							<label for="btn_foto" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">
								<?php print JText::_('COM_SMARTSHOP_IMAGE')?>
							</label>
							<div id="ordering" class="col-sm-9 col-md-10 col-xl-10 col-12">
								<?php echo LayoutHelper::render('fields.media', [
									'name' => $image,
									'id' => 'img',
									'folder' => 'img_labels',
									'type' => 'smartshopimgs',
									'preview' => 'tooltip',
									'value' => $row->$image
								]); ?>
							</div>
						</div>
						<?php $pkey="etemplatevar";if ($this->$pkey){print $this->$pkey;}?>    
					</div>
				</div>

			<?php 
			if (isJoomla4()) {
				echo HTMLHelper::_('uitab.endTab');
			} 

			endforeach; 
			?>
		<?php if (isJoomla4()) {
			echo HTMLHelper::_('uitab.endTabSet');
		} ?>								
	</div>
	<div class="clr"></div>
 

	<input type="hidden" name="task" value="" />
	<input type="hidden" name="id" value="<?php echo $row->id; ?>" />
	<?php echo $this->tmp_html_end ?? ''; ?>
</form>
