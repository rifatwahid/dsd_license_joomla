<?php 
    $view = $this->view; 
    $languages = $this->languages;
    $productNameType = $this->productNameType;
    $viewCoupon = $view->coupon;
?>

<div class="form-group row align-items-center">
	<label for="for_product_id" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">	
        <?php echo JText::_('COM_SMARTSHOP_FOR_PRODUCT_ID');?>
	</label>
	<div class="col-sm-9 col-md-10 col-xl-10 col-12">
        <input type="text" class="inputbox form-control" id="for_product_id" name="for_product_id" value="<?php echo $viewCoupon->for_product_id; ?>" size="20"/>
		</div>
	</div>     

<div class="form-group row align-items-center">
<label for="except_product_id" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">	
        <?php echo JText::_('COM_SMARTSHOP_EXCEPT_PRODUCT_ID'); ?>
	</label>
	<div class="col-sm-9 col-md-10 col-xl-10 col-12">
        <input type="text" class="inputbox form-control" id="except_product_id" name="except_product_id" value="<?php echo $viewCoupon->except_product_id; ?>" size="20"/>
	</div>
</div>     

<div class="form-group row align-items-center">
	<label for="for_category_id" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">	
        <?php echo JText::_('COM_SMARTSHOP_FOR_CATEGORY_ID'); ?>
	</label>
	<div class="col-sm-9 col-md-10 col-xl-10 col-12">
        <input type="text" class="inputbox form-control" id="for_category_id" name="for_category_id" value="<?php echo $viewCoupon->for_category_id; ?>" size="20"/>
	</div>
</div>     

<div class="form-group row align-items-center">
	<label for="except_category_id" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">	
        <?php echo JText::_('COM_SMARTSHOP_EXCEPT_CATEGORY_ID'); ?>
	</label>
	<div class="col-sm-9 col-md-10 col-xl-10 col-12">
        <input type="text" class="inputbox form-control" id="except_category_id" name="except_category_id" value="<?php echo $viewCoupon->except_category_id; ?>" size="20"/>
	</div>
</div>     

<div class="form-group row align-items-center">
	<hr>
</div>     
<div class="form-group row align-items-center">
	<label for="for_product_name_type" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">	
        <?php echo JText::_('COM_SMARTSHOP_PRODUCT_NAME_TYPE'); ?>
	</label>
	<div class="col-sm-9 col-md-10 col-xl-10 col-12">
        <?php echo JHTML::_('select.radiolist', $productNameType, 'for_product_name_type', null, 'value', 'text', $viewCoupon->for_product_name_type); ?>
	</div>
</div>     
<?php foreach($languages as $lang) : ?>
	<div class="form-group row align-items-center">
		<label for="for_product_name_<?php echo $lang->language; ?>" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">	
            <?php echo JText::_('COM_SMARTSHOP_FOR_PRODUCT_NAME') . ' (' . $lang->name . ')'; ?>
		</label>
		<div class="col-sm-9 col-md-10 col-xl-10 col-12">
			<input type="text" class="inputbox form-control" id="for_product_name_<?php echo $lang->language; ?>" name="for_product_name_<?php echo $lang->language; ?>" value="<?php echo $viewCoupon->{'for_product_name_' . $lang->language}; ?>" size="20"/>
		</div>
	</div>     
<?php endforeach; ?>

<div class="form-group row align-items-center">
	<hr>
</div>  

<div class="form-group row align-items-center">
	<label for="for_product_ean" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">	
        <?php echo JText::_('COM_SMARTSHOP_FOR_PRODUCT_EAN'); ?>
	</label>
	<div class="col-sm-9 col-md-10 col-xl-10 col-12">
        <input type="text" class="inputbox form-control" id="for_product_ean" name="for_product_ean" value="<?php echo $viewCoupon->for_product_ean; ?>" size="20"/>
	</div>
</div>  

<div class="form-group row align-items-center">
	<label for="for_editor_id" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">	
        <?php echo JText::_('COM_SMARTSHOP_FOR_EDITOR_ID'); ?>
	</label>
	<div class="col-sm-9 col-md-10 col-xl-10 col-12">
        <input type="text" class="inputbox form-control" id="for_editor_id" name="for_editor_id" value="<?php echo $viewCoupon->for_editor_id; ?>" size="20"/>
	</div>
</div>  

<div class="form-group row align-items-center">
	<label for="for_label_id" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">	
        <?php echo JText::_('COM_SMARTSHOP_FOR_LABEL_ID'); ?>
	</label>
	<div class="col-sm-9 col-md-10 col-xl-10 col-12">
        <input type="text" class="inputbox form-control" id="for_label_id" name="for_label_id" value="<?php echo $viewCoupon->for_label_id; ?>" size="20"/>
	</div>
</div>  

<div class="form-group row align-items-center">
	<label for="except_label_id" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">	
        <?php echo JText::_('COM_SMARTSHOP_EXCEPT_LABEL_ID'); ?>
	</label>
	<div class="col-sm-9 col-md-10 col-xl-10 col-12">
        <input type="text" class="inputbox form-control" id="except_label_id" name="except_label_id" value="<?php echo $viewCoupon->except_label_id; ?>" size="20"/>
	</div>
</div>  

<div class="form-group row align-items-center">
	<label for="for_manufacturer_id" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">	
        <?php echo JText::_('COM_SMARTSHOP_FOR_MANUFACTURER_ID'); ?>
	</label>
	<div class="col-sm-9 col-md-10 col-xl-10 col-12">
        <input type="text" class="inputbox form-control" id="for_manufacturer_id" name="for_manufacturer_id" value="<?php echo $viewCoupon->for_manufacturer_id; ?>" size="20"/>
	</div>
</div>  

<div class="form-group row align-items-center">
	<label for="for_vendor_id" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">	
        <?php echo JText::_('COM_SMARTSHOP_FOR_VENDOR_ID'); ?>
	</label>
	<div class="col-sm-9 col-md-10 col-xl-10 col-12">
        <input type="text" class="inputbox form-control" id="for_vendor_id" name="for_vendor_id" value="<?php echo $viewCoupon->for_vendor_id; ?>" size="20"/>
	</div>
</div>  

<div class="form-group row align-items-center">
	<label for="except_vendor_id" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">	
        <?php echo JText::_('COM_SMARTSHOP_EXCEPT_VENDOR_ID'); ?>
	</label>
	<div class="col-sm-9 col-md-10 col-xl-10 col-12">
        <input type="text" class="inputbox form-control" id="except_vendor_id" name="except_vendor_id" value="<?php echo $viewCoupon->except_vendor_id; ?>" size="20"/>
	</div>
</div>  

<div class="form-group row align-items-center">
	<label for="for_user_group_id" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">	
        <?php echo JText::_('COM_SMARTSHOP_FOR_USER_GROUP_ID'); ?>
	</label>
	<div class="col-sm-9 col-md-10 col-xl-10 col-12">
        <input type="text" class="inputbox form-control" id="for_user_group_id" name="for_user_group_id" value="<?php echo $viewCoupon->for_user_group_id; ?>" size="20"/>
	</div>
</div>  

<div class="form-group row align-items-center">
	<label for="except_user_group_id" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">	
        <?php echo JText::_('COM_SMARTSHOP_EXCEPT_USER_GROUP_ID'); ?>
	</label>
	<div class="col-sm-9 col-md-10 col-xl-10 col-12">
        <input type="text" class="inputbox form-control" id="except_user_group_id" name="except_user_group_id" value="<?php echo $viewCoupon->except_user_group_id; ?>" size="20"/>
	</div>
</div>  

<div class="form-group row align-items-center">
	<label for="once_for_each_user" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">	
        <?php echo JText::_('COM_SMARTSHOP_ONCE_FOR_EACH_USER'); ?>
	</label>
	<div class="col-sm-9 col-md-10 col-xl-10 col-12">
        <input type="hidden" class="inputbox" name="once_for_each_user" value="0" checked size="20"/>
        <input type="checkbox" class="inputbox form-check-input" id="once_for_each_user" name="once_for_each_user" value="1" <?php if (!empty($viewCoupon->once_for_each_user)) { echo 'checked'; } ?> size="20"/>
	</div>
</div>  

<div class="form-group row align-items-center">
	<label for="not_use_for_product_with_old_price" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">	
        <?php echo JText::_('COM_SMARTSHOP_NOT_USE_FOR_PRODUCT_WITH_OLD_PRICE'); ?>
	</label>
	<div class="col-sm-9 col-md-10 col-xl-10 col-12">
        <input type="hidden" class="inputbox" name="not_use_for_product_with_old_price" value="0" checked size="20"/>
        <input type="checkbox" class="inputbox form-check-input" id="not_use_for_product_with_old_price" name="not_use_for_product_with_old_price" value="1" <?php if (!empty($viewCoupon->not_use_for_product_with_old_price)) { echo 'checked'; } ?> size="20"/>
	</div>
</div>  

<div class="form-group row align-items-center">
	<label for="for_currencies" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">	
        <?php echo JText::_('COM_SMARTSHOP_FOR_CURRENCIES'); ?>
	</label>
	<div class="col-sm-9 col-md-10 col-xl-10 col-12">
        <input type="text" class="inputbox form-control" id="for_currencies" name="for_currencies" value="<?php echo $viewCoupon->for_currencies; ?>" size="20"/>
	</div>
</div>  

<div class="form-group row align-items-center">
	<label for="for_product_fields" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">	
        <?php echo JText::_('COM_SMARTSHOP_FOR_PRODUCT_FIELDS'); ?>
	</label>
	<div class="col-sm-9 col-md-10 col-xl-10 col-12">
        <input type="text" class="inputbox form-control" id="for_product_fields" name="for_product_fields" value="<?php echo $viewCoupon->for_product_fields; ?>" size="20"/>
	</div>
</div>  

<div class="form-group row align-items-center">
	<label for="for_prod_price_from" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">	
        <?php echo JText::_('COM_SMARTSHOP_FOR_PROD_PRICE'); ?>
	</label>
	<div class="col-sm-9 col-md-10 col-xl-10 col-12">
		<div class="input-group">
			<input type="text" class="inputbox form-control" id="for_prod_price_from" name="for_prod_price_from" value="<?php echo $viewCoupon->for_prod_price_from; ?>" size="20"/>
        	<input type="text" class="inputbox form-control" id="for_prod_price_to" name="for_prod_price_to" value="<?php echo $viewCoupon->for_prod_price_to; ?>" size="20"/>
		</div>
	</div>
</div>  

<div class="form-group row align-items-center">
	<label for="min_count_in_cart" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">	
        <?php echo JText::_('COM_SMARTSHOP_MIN_COUNT_PRODS_IN_CART'); ?>
	</label>
	<div class="col-sm-9 col-md-10 col-xl-10 col-12">
        <input type="text" class="inputbox form-control" id="min_count_in_cart" name="min_count_in_cart" value="<?php echo $viewCoupon->min_count_in_cart; ?>" size="20"/>
	</div>
</div>  

<div class="form-group row align-items-center">
	<label for="min_sum_for_use" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">	
        <?php echo JText::_('COM_SMARTSHOP_MIN_SUM_FOR_USE'); ?>
	</label>
	<div class="col-sm-9 col-md-10 col-xl-10 col-12">
        <input type="text" class="inputbox form-control" id="min_sum_for_use" name="min_sum_for_use" value="<?php echo $viewCoupon->min_sum_for_use; ?>" size="20"/>
	</div>
</div>  

<div class="form-group row align-items-center">
	<label for="limited_use" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">	
        <?php echo JText::_('COM_SMARTSHOP_LIMITED_USE'); ?>
	</label>
	<div class="col-sm-9 col-md-10 col-xl-10 col-12">
        <input type="hidden" class="inputbox" name="limited_use" value="0" checked size="20"/>
        <input type="checkbox" class="inputbox form-check-input" id="limited_use" name="limited_use" value="1" <?php if (!empty($viewCoupon->limited_use)) { echo 'checked'; } ?> size="20"/>
	</div>
</div>  

<div class="form-group row align-items-center">
	<label for="limited_count" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">	
        <?php echo JText::_('COM_SMARTSHOP_LIMITED_COUNT'); ?>
	</label>
	<div class="col-sm-9 col-md-10 col-xl-10 col-12">
        <input type="text" class="inputbox form-control" id="limited_count" name="limited_count" value="<?php echo $viewCoupon->limited_count; ?>" size="20"/>
	</div>
</div>  

<div class="form-group row align-items-center">
	<label for="free_shipping" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">	
        <?php echo JText::_('COM_SMARTSHOP_FREE_SHIPPING'); ?>
	</label>
	<div class="col-sm-9 col-md-10 col-xl-10 col-12">
        <input type="hidden" class="inputbox" name="free_shipping" value="0" checked size="20"/>
        <input type="checkbox" class="inputbox form-check-input" name="free_shipping" id="free_shipping" value="1" <?php if (!empty($viewCoupon->free_shipping)) { echo 'checked'; } ?> size="20"/>
	</div>
</div>  

<div class="form-group row align-items-center">
	<label for="free_payment" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">	
        <?php echo JText::_('COM_SMARTSHOP_FREE_PAYMENT'); ?>
	</label>
	<div class="col-sm-9 col-md-10 col-xl-10 col-12">
        <input type="hidden" class="inputbox" name="free_payment" value="0" checked size="20"/>
        <input type="checkbox" class="inputbox form-check-input" id="free_payment" name="free_payment" value="1" <?php if (!empty($viewCoupon->free_payment)) { echo 'checked'; } ?> size="20"/>
	</div>
</div>  

<div class="form-group row align-items-center">
	<label for="only_for_guests" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">	
        <?php echo JText::_('COM_SMARTSHOP_USE_COUPON_ONLY_FOR_GUESTS'); ?>
	</label>
	<div class="col-sm-9 col-md-10 col-xl-10 col-12">
        <input type="hidden" class="inputbox" name="only_for_guests" value="0" checked size="20"/>
        <input type="checkbox" class="inputbox form-check-input" id="only_for_guests" name="only_for_guests" value="1" <?php if (!empty($viewCoupon->only_for_guests)) { echo 'checked'; } ?> size="20"/>
	</div>
</div>  

<?php if($view->edit == 0) : ?>
	<div class="form-group row align-items-center">
		<label class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">	
            <span class="padding-top--30px display--block">
                <?php echo JText::_('COM_SMARTSHOP_AUTO_COUPONS_TITLE'); ?>
            </span>
		</label>
		<div class="col-sm-9 col-md-10 col-xl-10 col-12">
		</div>
	</div>  
	<div class="form-group row align-items-center">
		<label for="create-for-each-user" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">	
				<?php echo JText::_('COM_SMARTSHOP_CHECK_FOR_ALL_USERS'); ?>
			</label>
			<div class="col-sm-9 col-md-10 col-xl-10 col-12">
				<input type="checkbox" class="form-check-input" name="create_for_each_user" id="create-for-each-user"/>
		</div>
	</div>  
	<div class="form-group row align-items-center autoCouponsCountRow">
		<label for="auto_coupons_count" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">	
				<?php echo JText::_('COM_SMARTSHOP_AUTO_COUPONS_COUNT'); ?>
			</label>
			<div class="col-sm-9 col-md-10 col-xl-10 col-12">
				<input type="text" class="inputbox form-control" id="auto_coupons_count" name="auto_coupons_count" value="0" size="20"/>
		</div>
	</div>  
	<div class="form-group row align-items-center">
		<label for="auto_coupons_length" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">	
			<?php echo JText::_('COM_SMARTSHOP_AUTO_COUPONS_LENGTH'); ?>
		</label>
		<div class="col-sm-9 col-md-10 col-xl-10 col-12">
			<input type="text" class="inputbox form-control" id="auto_coupons_length" name="auto_coupons_length" value="5" size="20"/>
		</div>
	</div>  
	<div class="form-group row align-items-center">
		<label for="only_numbers" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">	
			<?php echo JText::_('COM_SMARTSHOP_GENERATE_ONLY_NUMBERS'); ?>
		</label>
		<div class="col-sm-9 col-md-10 col-xl-10 col-12">
			<input type="checkbox" class="inputbox form-check-input" id="only_numbers"  name="only_numbers"  value="1" <?php if (!empty($viewCoupon->only_numbers)) { echo 'checked'; } ?> size="20"/>
		</div>
	</div>  
	<div class="form-group row align-items-center">
		<label for="coupons_comment" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">	
            <?php echo JText::_('COM_SMARTSHOP_AUTO_COUPONS_COMMENT'); ?>    
		</label>
		<div class="col-sm-9 col-md-10 col-xl-10 col-12">
		</div>
	</div>  
<?php endif; ?>

<div class="form-group row align-items-center">
	<label for="coupon_desc" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">	
        <?php echo JText::_('COM_SMARTSHOP_COUPON_DESC'); ?>
	</label>
	<div class="col-sm-9 col-md-10 col-xl-10 col-12">
        <textarea rows="5" class="wide form-control" id="coupon_desc" name="coupon_desc"><?php echo $view->coupon->coupon_desc; ?></textarea>
	</div>
</div>  