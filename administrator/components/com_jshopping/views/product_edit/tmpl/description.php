<?php

defined('_JEXEC') or die('Restricted access');

$editor = isJoomla4() ? \JEditor::getInstance(\JFactory::getConfig()->get('editor')) : JFactory::getEditor();
$productDescriptionStyle = ($this->isPageWithAdditionalValues && empty($this->product->is_use_additional_description) && !$this->isBatchEdit) ? 'display: none;' : '';
?>

<div id="description-page" class="tab-pane active">
	<div class="jshops_edit">
		<?php if ($this->isPageWithAdditionalValues && !$this->isBatchEdit) : ?>
			<div class="form-group row align-items-center">
				<label for="is_use_additional_description" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
					<?php echo JText::_('COM_SMARTSHOP_USE_ADDITIONAL_DESCRIPTION'); ?>
				</label>
				<div id="ordering" class="col-sm-9 col-md-10 col-xl-10 col-12">
					<input type="hidden" name="is_use_additional_description" value="0" checked>
					<input type="checkbox" name="is_use_additional_description" id="is_use_additional_description" class="form-check-input" value="1" <?php if ($this->product->is_use_additional_description) { echo 'checked'; } ?> onclick="shopHelper.showHideByChecked(this, '#description-page #descriptions');">
				</div>
			</div>
		<?php else : ?>
			<input type="hidden" name="is_use_additional_description" value="1" checked>
		<?php endif; ?>
	</div>

	<div id="descriptions" style="<?php echo $productDescriptionStyle; ?>">
		<?php $i=0;
			foreach($this->languages as $lang) :
				$i++;

				$loopLanguage = $lang->language;
				$name = 'name_' . $loopLanguage;
				$alias = 'alias_' . $loopLanguage;
				$description = 'description_' . $loopLanguage;
				$short_description = 'short_description_' . $loopLanguage;
				$meta_title = 'meta_title_' . $loopLanguage;
				$meta_keyword = 'meta_keyword_' . $loopLanguage;
				$meta_description = 'meta_description_' . $loopLanguage;   
		?>
			<div class="jshops_edit">
				<?php if (!$this->isPageWithAdditionalValues && (!isset($this->isBatchEdit) || !$this->isBatchEdit)) : ?>
				<div class="form-group row align-items-center">
					<label for="<?php echo $name; ?>" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
						<?php echo JText::_('COM_SMARTSHOP_TITLE') . ' ' . $lang->lang; ?>*
					</label>
					<div id="ordering" class="col-sm-9 col-md-10 col-xl-10 col-12">
						<input type="text" class="inputbox wide form-control" name="<?php echo $name; ?>" id="<?php echo $name; ?>" value="<?php echo $row->$name; ?>" />
					</div>
				</div>
						
				<div class="form-group row align-items-center">
					<label for="<?php echo $alias; ?>" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
						<?php echo JText::_('COM_SMARTSHOP_ALIAS') . ' ' . $lang->lang; ?>
					</label>
					<div id="ordering" class="col-sm-9 col-md-10 col-xl-10 col-12">
						<input type="text" class="inputbox wide form-control" name="<?php echo $alias; ?>" id="<?php echo $alias; ?>" value="<?php echo $row->$alias; ?>" />
					</div>
				</div>
				<?php endif; ?>
					
				<div class="form-group row align-items-center">
					<label for="<?php print $short_description; ?>" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
						<?php echo JText::_('COM_SMARTSHOP_SHORT_DESCRIPTION') . ' ' . $lang->lang; ?>
					</label>
					<div id="ordering" class="col-sm-9 col-md-10 col-xl-10 col-12">
						<?php echo $editor->display($short_description,  $row->$short_description , '100%', '350', '75', '20' ); ?>
					</div>
				</div>

				<div class="form-group row align-items-center">
					<label for="description<?php print $lang->id ?>" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
						<?php echo JText::_('COM_SMARTSHOP_DESCRIPTION') . ' ' . $lang->lang; ?>
					</label>
					<div id="ordering" class="col-sm-9 col-md-10 col-xl-10 col-12">
						<?php echo $editor->display($description,  $row->$description , '100%', '350', '75', '20' ); ?>
					</div>
				</div>		
				<?php $pkey='plugin_template_description_' . $lang->language; if ($this->$pkey) { echo $this->$pkey;} ?>
			</div>

			<div class="clr"></div>
		<?php endforeach; ?>
	</div>
</div>