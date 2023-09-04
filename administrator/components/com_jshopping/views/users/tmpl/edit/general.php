<div class="jshops_edit general_edits">
		<?php echo $this->tmp_html_row_before_username ?? ''; ?>
        <div class="form-group row align-items-center">
			<label for="u_name" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">
				<?php echo JText::_('COM_SMARTSHOP_NAME');?>*
			</label>
			<div class="col-sm-9 col-md-10 col-xl-10 col-12">
				<input type="text" class="inputbox form-control" id="u_name" name="u_name" readonly value="<?php echo $user->u_name; ?>" />
			</div>
		</div>

        <div class="form-group row align-items-center">
			<label for="email" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">
				<?php echo JText::_('COM_SMARTSHOP_EMAIL')?>*
			</label>
			<div class="col-sm-9 col-md-10 col-xl-10 col-12">
				<input type="text" class="inputbox form-control" id="email" name="email" readonly value="<?php echo $user->email; ?>" />
			</div>
		</div>

        <div class="form-group row align-items-center">
			<label for="number" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">
				<?php echo JText::_('COM_SMARTSHOP_NUMBER')?>
			</label>
			<div class="col-sm-9 col-md-10 col-xl-10 col-12">
				<input type="text" class="inputbox form-control" name="number" id="number" value="<?php echo $user->number; ?>" />
			</div>
		</div>

		<?php if (JFactory::getUser()->authorise('core.admin', 'com_jshopping')) : ?>
        <div class="form-group row align-items-center">
			<label for="block0" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_BLOCK_USER'); ?>
			</label>
			<div  class="col-sm-9 col-md-10 col-xl-10 col-12">
					<?php echo $this->lists['block']; ?>
			</div>
		</div>
		<?php endif; ?>

        <div class="form-group row align-items-center">
			<label for="usergroup_id" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_USERGROUP_NAME'); ?>*
			</label>
			<div  class="col-sm-9 col-md-10 col-xl-10 col-12">
				<?php echo $lists['usergroups']; ?>
			</div>
		</div>
        <div class="form-group row align-items-center">
			<label for="credit_limit" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">
				<?php echo  JText::_('COM_SMARTSHOP_CREDIT_LIMIT'); ?>
			</label>
			<div  class="col-sm-9 col-md-10 col-xl-10 col-12">
				<input type="text" class="inputbox form-control" id="credit_limit" name="credit_limit" value="<?php echo $user->credit_limit; ?>" />
			</div>
		</div>
        <div class="form-group row align-items-center">
			<label for="open_amount" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">
                    <?php echo  JText::_('COM_SMARTSHOP_OPEN_AMOUNT'); ?>
			</label>
			<div  class="col-sm-9 col-md-10 col-xl-10 col-12">
				<input type="text" class="inputbox form-control" id="open_amount"name="open_amount" value="<?php echo $user->open_amount; ?>" />
			</div>
		</div>
<?php 
    $pkey = 'etemplatevar1';
    if (isset($this->$pkey) && $this->$pkey) {
        echo $this->$pkey;
    }
?>
</div>

<div class="clr"></div>

<?php 
    $pkey = 'etemplatevar0';

    if (isset($this->$pkey) && $this->$pkey) {
        echo $this->$pkey;
    }
?>