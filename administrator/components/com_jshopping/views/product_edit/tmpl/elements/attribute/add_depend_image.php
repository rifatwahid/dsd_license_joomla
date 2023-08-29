<?php
use Joomla\CMS\Layout\LayoutHelper;
JHtml::_('script', 'system/modal.js', [], true);
JHtml::_('stylesheet', 'system/modal.css', [], true); ?>

	<div class="form-group row align-items-center bg-light">
		<label for="attr_min_count_product" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label">
			<?php echo JText::_('COM_SMARTSHOP_TITLE')?>: 
		</label>

		<div id="ordering" class="col-sm-8 col-md-8 col-xl-8 col-12">					
			<input type="text" class="media-block__input form-control" id="attr_media_title" title="<?php echo JText::_('COM_SMARTSHOP_TITLE'); ?>" />
	   </div>
	</div>
						
	<div class="form-group row align-items-center bg-light">
		<label for="attr_min_count_product" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label">
			<?php echo JText::_('COM_SMARTSHOP_MEDIA_LINK') ?> :
		</label>

		<div id="ordering" class="col-sm-8 col-md-8 col-xl-8 col-12">
			<input type="url" class="media-block__input form-control" id="attr_media_link" />
	   </div>
	</div>

	<div class="form-group row align-items-center bg-light">
		<label for="attr_min_count_product" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label">
			<?php echo JText::_('COM_SMARTSHOP_PREVIEW') ?> :
		</label>

		<div id="ordering" class="col-sm-8 col-md-8 col-xl-8 col-12">
			<?php echo LayoutHelper::render('fields.media', [
				'id' => 'attr_media_preview',
				'folder' => 'img_shop_products',
				'type' => 'smartshopimgsvideo'
			]); ?>
	   </div>
	</div>
						
						
	<div class="form-group row align-items-center bg-light">
		<label for="attr_min_count_product" class="col-sm-4 col-md-4 col-xl-4 col-12 col-form-label">
			<?php echo JText::_('COM_SMARTSHOP_IMAGE_VIDEO') ?> :
		</label>

		<div id="ordering" class="col-sm-8 col-md-8 col-xl-8 col-12">
				<?php echo LayoutHelper::render('fields.media', [
					'id' => 'attr_media_file',
					'folder' => 'img_shop_products',
					'type' => 'smartshopimgsvideo'
				]); ?>
	   </div>
	</div>





