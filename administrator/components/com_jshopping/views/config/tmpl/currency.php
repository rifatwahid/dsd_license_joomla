<?php 
/**
* @version      4.9.0 13.08.2013
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');

$jshopConfig=JSFactory::getConfig();
$lists=$this->lists;
displaySubmenuConfigs('currency',$this->canDo);
?>

<form action="index.php?option=com_jshopping&controller=config" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
	<?php print $this->tmp_html_start ?? ''?>
	<input type="hidden" name="task" value="">
	<input type="hidden" name="tab" value="2">

	<legend><?php echo  JText::_('COM_SMARTSHOP_CURRENCY_PARAMETERS') ?></legend>
	<div class="striped-block jshops_edit currency_tmpl_config ">
		<div class="form-group row align-items-center">
			<label for="mainCurrency" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label col-form-label-sm">
				<?php echo  JText::_('COM_SMARTSHOP_MAIN_CURRENCY');?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				  <?php echo $lists['currencies'];?> 				   
				  <a class="btn btn-small btn-info" href="index.php?option=com_jshopping&controller=currencies"><?php print  JText::_('COM_SMARTSHOP_LIST_CURRENCY')?></a>
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="decimal_count" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label col-form-label-sm">
				<?php echo  JText::_('COM_SMARTSHOP_DECIMAL_COUNT');?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input type="text" name="decimal_count" id="decimal_count" value ="<?php echo $jshopConfig->decimal_count?>" />
				<?php echo JHTML::tooltip( JText::_('COM_SMARTSHOP_DECIMAL_COUNT_DESCRIPTION'),  JText::_('COM_SMARTSHOP_HINT'));?>
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="decimal_symbol" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label col-form-label-sm">
				<?php echo  JText::_('COM_SMARTSHOP_DECIMAL_SYMBOL');?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
			    <input type="text" name="decimal_symbol" id="decimal_symbol" value ="<?php echo $jshopConfig->decimal_symbol?>" />
			    <?php echo JHTML::tooltip( JText::_('COM_SMARTSHOP_DECIMAL_SYMBOL_DESCRIPTION'),  JText::_('COM_SMARTSHOP_HINT'));?>
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="thousand_separator" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label col-form-label-sm">
				<?php echo  JText::_('COM_SMARTSHOP_THOUSAND_SEPARATOR'); ?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input type="text" name="thousand_separator" id="thousand_separator" value ="<?php echo $jshopConfig->thousand_separator?>" />
				<?php echo JHTML::tooltip( JText::_('COM_SMARTSHOP_THOUSAND_SEPARATOR_DESCRIPTION'),  JText::_('COM_SMARTSHOP_HINT'));?>
			</div>
		</div>
		<div class="form-group row align-items-center">
			<label for="currency_format" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label col-form-label-sm">
				<?php echo  JText::_('COM_SMARTSHOP_CURRENCY_FORMAT'); ?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<?php echo $lists['format_currency']; echo " ".JHTML::tooltip( JText::_('COM_SMARTSHOP_CURRENCY_FORMAT_DESCRIPTION'),  JText::_('COM_SMARTSHOP_HINT')) ?>
			</div>
		</div>
		<?php $pkey="etemplatevar";if (isset($this->$pkey) && $this->$pkey){print $this->$pkey;}?>
	</div>

<div class="clr"></div>
<?php print $this->tmp_html_end ?? '' ?>
</form>