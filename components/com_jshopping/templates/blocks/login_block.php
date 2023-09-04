 <div class="form-group">
	<label for="jlusername" class="sr-only">
		<?php echo JText::_('COM_SMARTSHOP_USERNAME'); ?>
	</label>

	<input type="text" class="form-control" id="jlusername" name="username" placeholder="<?php echo JText::_('COM_SMARTSHOP_USERNAME'); ?>">
</div>

<div class="form-group">
	<label for="jlpassword" class="sr-only">
		<?php echo JText::_('COM_SMARTSHOP_PASSWORD'); ?>
	</label>

	<input type="password" class="form-control" id="jlpassword" name="passwd" placeholder="<?php echo JText::_('COM_SMARTSHOP_PASSWORD'); ?>">
</div>

<div class="row">
	<div class="col-md-6">
		<a class="small text-secondary" href="<?php echo $this->href_lost_pass; ?>">
			<?php echo JText::_('COM_SMARTSHOP_PASSWORD_FORGOTTEN'); ?>
		</a>
	</div>

	<div class="col-md-6">
		<div class="form-group">
			<button type="submit" class="btn btn-outline-primary d-grid">
				<?php echo JText::_('COM_SMARTSHOP_LOGIN'); ?>
			</button>
		</div>
	</div>
</div> <!-- .row -->