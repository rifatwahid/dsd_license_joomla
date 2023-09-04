<?php 
defined('_JEXEC') or die('Restricted access');

$jshopConfig = $this->jshopConfig;
$lists = $this->lists;
?>

<form action="index.php?option=com_jshopping&controller=currencies" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
	<?php echo $this->tmp_html_start ?? ''; ?>

	<div class="striped-block jshops_edit">
		<div class="form-group row align-items-center">
			<label for="mainCurrency" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_MAIN_CURRENCY');?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				  <?php echo $lists['currencies'];?>
			</div>
		</div>

		<div class="form-group row align-items-center">
			<label for="decimal_count" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_DECIMAL_COUNT');?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input type="text" name="decimal_count" class="form-control" id="decimal_count" value ="<?php echo $jshopConfig->decimal_count?>" />
			</div>
		</div>

		<div class="form-group row align-items-center">
			<label for="decimal_symbol" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_DECIMAL_SYMBOL');?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
			    <input type="text" name="decimal_symbol" class="form-control" id="decimal_symbol" value ="<?php echo $jshopConfig->decimal_symbol?>" />
			</div>
		</div>

		<div class="form-group row align-items-center">
			<label for="thousand_separator" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_THOUSAND_SEPARATOR'); ?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<input type="text" name="thousand_separator" class="form-control" id="thousand_separator" value ="<?php echo $jshopConfig->thousand_separator?>" />
			</div>
		</div>

		<div class="form-group row align-items-center">
			<label for="currency_format" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_CURRENCY_FORMAT'); ?>
			</label>
			<div class="col-sm-8 col-md-9 col-xl-9 col-12">
				<?php echo $lists['format_currency']; ?>
			</div>
		</div>

		<?php 
            $pkey = 'etemplatevar';

            if (!empty($this->$pkey)) {
                echo $this->$pkey;
            }
        ?>
	</div>

    <div class="clr"></div>
    <?php echo $this->tmp_html_end ?? ''; ?>

    <input type="hidden" name="task" value="">
    <input type="hidden" name="tab" value="2">
</form>