<?php
    use Joomla\CMS\Language\Text;
?>

<div class="jshops_edit mass_edit_attribute">
	<div class="form-group row align-items-center">
		<label class="col-sm-3 col-md-2 col-xl-2 col-12 font-weight-bold fw-bold text-uppercase col-form-label">
			<div>
				<?php echo Text::_('COM_SMARTSHOP_ATTRIBUTES'); ?>
			</div>
		</label>
		<div class="col-sm-9 col-md-10 col-xl-10 col-12">
			<?php echo $this->attrs_actions; ?>
		</div>
	</div>

    <?php include __DIR__ . '/../attribute.php'; ?>
</div>