<?php echo $this->tmp_html_start ?? '' ; ?>

     <div class="jshops_edit general_info">
        <?php if (isset($this->review->review_id) && $this->review->review_id) : ?>
		<div class="form-group row align-items-center">
			<label for="product_id" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">
                    <?php echo  JText::_('COM_SMARTSHOP_NAME_PRODUCT'); ?>
			</label>
			<div id="ordering" class="col-sm-9 col-md-10 col-xl-10 col-12">
                    <?php echo $this->review->name; ?>     
                    <input type="hidden" id="product_id" name="product_id" value="<?php echo $this->review->product_id; ?>">
			</div>
		</div>
        <?php else : ?>
		<div class="form-group row align-items-center">
			<label for="product_id" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">
                    <?php echo  JText::_('COM_SMARTSHOP_PRODUCT_ID'); ?>*
			</label>
			<div id="ordering" class="col-sm-9 col-md-10 col-xl-10 col-12">
                    <input type="text" class="form-control" id="product_id" name="product_id" value="">    
			</div>
		</div>   
        <?php endif; ?>

		<div class="form-group row align-items-center">
			<label for="user_name" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">
                <?php echo  JText::_('COM_SMARTSHOP_USER'); ?>*
			</label>
			<div id="ordering" class="col-sm-9 col-md-10 col-xl-10 col-12">
                <input type="text" class="inputbox form-control" size="50" id="user_name" name="user_name" value="<?php echo $this->review->user_name ?? ''; ?>" />
 			</div>
		</div>

		<div class="form-group row align-items-center">
			<label for="user_email" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">
                <?php echo  JText::_('COM_SMARTSHOP_EMAIL'); ?>*
			</label>
			<div id="ordering" class="col-sm-9 col-md-10 col-xl-10 col-12">
                <input type="text" class="inputbox form-control" size="50" id="user_email" name="user_email" value="<?php echo $this->review->user_email ?? ''; ?>" />
			</div>
		</div>      
		<div class="form-group row align-items-center">
			<label for="review" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">
                <?php echo  JText::_('COM_SMARTSHOP_PRODUCT_REVIEW'); ?>*
			</label>
			<div id="ordering" class="col-sm-9 col-md-10 col-xl-10 col-12">
                <textarea id="review" class="form-control" name="review" cols="35"><?php echo $this->review->review ?? ''; ?></textarea>
			</div>
		</div>

		<div class="form-group row align-items-center">
			<label for="mark" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">
                <?php echo  JText::_('COM_SMARTSHOP_REVIEW_MARK'); ?> 
			</label>
			<div id="ordering" class="col-sm-9 col-md-10 col-xl-10 col-12">
                <?php echo $this->mark; ?>
			</div>
		</div>

        <?php 
            $pkey = 'etemplatevar';

            if ($this->$pkey) {
                echo $this->$pkey;
            }
        ?>
	</div>

<div class="clr"></div>
<input type="hidden" name="review_id" value="<?php echo $this->review->review_id ?? ''; ?>">
<input type="hidden" name="task" value="<?php echo JFactory::getApplication()->input->getVar('task', 0); ?>" />
<?php echo $this->tmp_html_end ?? ''; ?>