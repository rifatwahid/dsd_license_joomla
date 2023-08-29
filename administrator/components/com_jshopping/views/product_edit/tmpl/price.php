<?php
/**
* @version      5.2.0 08.09.2019
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');

$nativeUploadPrices = $this->nativeUploadPrices;
?>
<div id="main-price" class="tab-pane">
    <div class="jshops_edit mainprice_edit">
        <div class="form-group row align-items-center">
			<label for="product_price" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
				<?php echo JText::_('COM_SMARTSHOP_PRODUCT_PRICE');?>*
            </label>
			<div class="col-sm-9 col-md-10 col-xl-10 col-12">
                <input type="text" class="form-control" name="product_price" id="product_price" value="<?php echo $row->product_price?>" onkeyup="shopPricePerConsigment.updateAllPrices(); <?php if (!$this->withouttax){?>shopProductPrice.update(<?php print $jshopConfig->display_price_admin;?>, true)<?php }?> " /> <?php echo $this->lists['currency'];?>
            </div>
        </div>
        <?php if (!$this->withouttax) : ?>
			<div class="form-group row align-items-center attribute__nettoProductPrice">
				<label for="product_price2" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
					<?php if ($jshopConfig->display_price_admin==0) echo JText::_('COM_SMARTSHOP_PRODUCT_NETTO_PRICE'); else echo JText::_('COM_SMARTSHOP_PRODUCT_BRUTTO_PRICE');?>
				</label>
				<div class="col-sm-9 col-md-10 col-xl-10 col-12">
                    <input type="text" class="form-control" id="product_price2" value="<?php echo $row->product_price2;?>" onkeyup="shopProductPrice.update(<?php print $jshopConfig->display_price_admin;?>)" />
				</div>
			</div>
        <?php endif; ?>
        <div class="form-group row align-items-center">
			<label for="product_old_price" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
				<?php echo JText::_('COM_SMARTSHOP_OLD_PRICE');?>
            </label>
			<div class="col-sm-9 col-md-10 col-xl-10 col-12">
                <input type="text" class="form-control" name="product_old_price" id="product_old_price" value="<?php echo $row->product_old_price?>" />
            </div>
        </div>

		<!-- Price per consignment -->
		<?php 
			$add_prices = $row->product_add_prices[0] ?? [];
		    $count = count($add_prices);
		?>

		<script>
			var consigment_rows_number = <?php echo $count ?: 0; ?>;	
		</script>
		<?php require_once __DIR__ . '/elements/price_per_consignment/price_per_consignment.php'; ?>
		<!-- Price per consignment END -->
        
		<div id="tr_add_price" class="form-group row align-items-center">
			<label for="is_activated_price_per_consignment_upload" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
                <?php echo JText::_('COM_SMARTSHOP_NATIVE_PROGRESS_UPLOADS_PRICES'); ?>
            </label>
			<div class="col-sm-9 col-md-10 col-xl-10 col-12">
                <input type="hidden" name="is_activated_price_per_consignment_upload" value="0">
                <input type="checkbox" class="nativeProgressUploadsAddPrices__checker form-check-input" <?php if (!empty($this->product->is_activated_price_per_consignment_upload)) { echo 'checked'; } ?> name="is_activated_price_per_consignment_upload" id="is_activated_price_per_consignment_upload" value="1" onclick="shopHelper.showHideByChecked(this, '#nativeUploadPricesWrapper', 'block')"/>

				<div id="nativeUploadPricesWrapper" class="form-group row align-items-center <?php if (empty($this->product->is_activated_price_per_consignment_upload)) { echo 'display--none'; } ?>">
					<!-- <div class="col-sm-3 col-md-2 col-xl-2 col-12"></div> -->
					<!-- <div class="col-sm-9 col-md-10 col-xl-10 col-12"> -->
						<?php include __DIR__ . '/elements/native_progress_uploads/table_of_add_prices.php'; ?>
					<!-- </div> -->
				</div>
            </div>
        </div>
		<?php require __DIR__ . '/elements/attribute/add_price_per_upload_disable_quantity.php';?>
		<!---------------------------------------------------USERGROUP PRICES LIST---------------------------------------------------->
		<div class="usergoup_price_block">
        <?php
		if (!empty($this->usergroups_prices)) {				
		foreach ($this->usergroups_prices as $key=>$value){			
		?>
		<div id="tr_add_price" class="form-group row align-items-center">
			<div class="col-sm-12 col-md-12 col-xl-12 col-12">
               <div  id="add_usergroups_prices_add_price_<?php echo $value->group_id;?>">
					<div class="form-group row align-items-center jshops_edit">
						<div class="col-sm-12 col-md-12 col-xl-12 col-12 col-form-label">
							<hr>
						</div>
						<div class="form-group row align-items-center">
							<label class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
								<?php echo JText::_('COM_SMARTSHOP_USERGROUPS');?>*
							</label>
							<div class="col-sm-9 col-md-10 col-xl-10 col-12 table-responsive">
								<table class="admintable" width="100%">
									<tr>
										<td>
											<?php if ($value->group_id==0){echo JText::_('COM_SMARTSHOP_USERGROUPS_GUEST');}else{echo $value->usergroup_name;}?>
											<input type='hidden' name='add_usergroups_prices_usergroup_list[<?php echo $value->group_id?>]' id='add_usergroups_prices_usergroup_list[<?php echo $value->group_id?>]' value='<?php echo $value->group_id?>'>
										</td>
										<td>
											<a class="btn btn-micro" href="#" align="right" onclick="shopProductUserGroup.deletePrice(<?php echo $value->group_id;?>);return false;">
												<i class="icon-delete"></i>
											</a>
										</td>
									</tr>
								</table>
							</div>
						</div>
						<div class="form-group row align-items-center">
							<label for="add_usergroups_prices_product_price_list_<?php echo $value->group_id?>" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
								<?php echo JText::_('COM_SMARTSHOP_PRODUCT_PRICE');?>*
							</label>
							<div class="col-sm-9 col-md-10 col-xl-10 col-12">
								<input type="text" class="form-control" name="add_usergroups_prices_product_price_list[<?php echo $value->group_id?>]" id="add_usergroups_prices_product_price_list_<?php echo $value->group_id?>" value="<?php echo $value->price;?>" <?php if (!$this->withouttax){?> onkeyup="shopProductUserGroup.updateAllPrices(<?php echo $value->group_id?>);shopProductUserGroup.updatePriceList(<?php print $jshopConfig->display_price_admin;?>, <?php echo $value->group_id?>, true);" <?php }?> />
							</div>
						</div>
					<?php if (!$this->withouttax) : ?>
						<div class="form-group row align-items-center">
							<label for="add_usergroups_prices_product_price2_list_<?php echo $value->group_id?>" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
								<?php if ($jshopConfig->display_price_admin==0) echo JText::_('COM_SMARTSHOP_PRODUCT_NETTO_PRICE'); else echo JText::_('COM_SMARTSHOP_PRODUCT_BRUTTO_PRICE');?>
							</label>
							<div class="col-sm-9 col-md-10 col-xl-10 col-12">
								<input type="text" class="form-control" name="add_usergroups_prices_product_price2_list[<?php echo $value->group_id?>]"  id="add_usergroups_prices_product_price2_list_<?php echo $value->group_id?>" value="<?php echo $value->price_netto;?>" onkeyup="shopProductUserGroup.updatePriceList(<?php print $jshopConfig->display_price_admin;?>, <?php echo $value->group_id?>, false)" />
							</div>
						</div>
					<?php endif; ?>
					<div class="form-group row align-items-center">
						<label for="add_usergroups_prices_product_old_price_list_<?php echo $value->group_id?>" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
							<?php echo JText::_('COM_SMARTSHOP_OLD_PRICE');?>
						</label>
						<div class="col-sm-9 col-md-10 col-xl-10 col-12">
							<input type="text" class="form-control" name="add_usergroups_prices_product_old_price_list[<?php echo $value->group_id?>]" id="add_usergroups_prices_product_old_price_list[<?php echo $value->group_id?>]" value="<?php echo $value->old_price?>" />
						</div>
					</div>
					<div class="form-group row align-items-center">
						<label for="add_usergroups_prices_product_is_add_price_list_<?php echo $value->group_id?>" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
							<?php echo JText::_('COM_SMARTSHOP_PRODUCT_ADD_PRICE');?>
						</label>
						<div class="col-sm-9 col-md-10 col-xl-10 col-12">
							<input type="checkbox" class="form-check-input" name="add_usergroups_prices_product_is_add_price_list[<?php echo $value->group_id?>]" id="add_usergroups_prices_product_is_add_price_list[<?php echo $value->group_id?>]" value="1" <?php if ($value->product_is_add_price) echo 'checked="checked"';?>  onclick="add_usergroups_prices_showHideAddPrice_list(<?php echo $value->group_id?>)" />
						</div>
					</div>
					<div id="add_usergroups_prices_tr_add_price_<?php echo $value->group_id?>" class="form-group row align-items-center"><!--add_usergroups_prices_tr_add_price-->
						<label class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
							<?php echo JText::_('COM_SMARTSHOP_PRODUCT_ADD_PRICE');?>
						</label>
						<div class="col-sm-9 col-md-10 col-xl-10 col-12 table-responsive">
							<table id="add_usergroups_prices_table_add_price_list_<?php echo $value->group_id?>" class="table table-striped">
							<thead>
								<tr>
									<th width="20%">
										<?php echo JText::_('COM_SMARTSHOP_PRODUCT_QUANTITY_START');?>    
									</th>
									<th width="20%">
										<?php echo JText::_('COM_SMARTSHOP_PRODUCT_QUANTITY_FINISH');?>    
									</th>
									<th width="22%">
										<?php echo JText::_('COM_SMARTSHOP_DISCOUNT');?>
									</th>
									<th width="22%">
										<?php echo JText::_('COM_SMARTSHOP_PRODUCT_PRICE');?>
									</th>          
									<?php $pkey='plugin_consignment_attr_title'; if (isset($value->$pkey) && $value->$pkey){ print $value->$pkey;}?>
									<th width="16%">
										<?php echo JText::_('COM_SMARTSHOP_DELETE');?>    
									</th>
								</tr>
								</thead>  
								<tbody>              
								<?php 					
								$add_prices=$row->product_add_prices[$value->group_id] ?? [];
                                $count=count($add_prices);
								for ($i=0; $i < $count; $i++){
									if (($value->group_id!=0)OR($add_prices[$i]->usergroup_prices==1)){
									?>
									<tr id="add_usergroups_prices_add_price_list_<?php print $i?>_<?php echo $value->group_id;?>" data-group-id="<?php echo $value->group_id;?>" data-row-number="<?php print $i?>">
										<td>
											<input type="text" class="small3 form-control w-50" name="add_usergroups_prices_quantity_start_list[<?php echo $value->group_id;?>][]" id="add_usergroups_prices_quantity_start_<?php print $i?>" value="<?php echo $add_prices[$i]->product_quantity_start;?>" />    
										</td>
										<td>
											<input type="text" class="small3 form-control w-50" name="add_usergroups_prices_quantity_finish_list[<?php echo $value->group_id;?>][]" id="add_usergroups_prices_quantity_finish_<?php print $i?>" value="<?php echo $add_prices[$i]->product_quantity_finish;?>" />    
										</td>
										<td>
											<input type="text" class="small3 form-control w-50" name="add_usergroups_prices_product_add_discount_list[<?php echo $value->group_id;?>][]" id="add_usergroups_prices_product_add_discount_list_<?php echo $value->group_id;?>_<?php print $i?>" value="<?php echo $add_prices[$i]->discount;?>" onkeyup="shopPricePerConsigment.updateUserGroupDiscountList(<?php echo $value->group_id; ?>, <?php print $i; ?>)" />    
										</td>
										<td>
											<input type="text" class="small3 form-control w-50" name="add_usergroups_prices_product_add_price_list[<?php echo $value->group_id;?>][]" id="add_usergroups_prices_product_add_price_list_<?php echo $value->group_id;?>_<?php print $i?>" value="<?php echo $add_prices[$i]->price;?>"  onkeyup="shopPricePerConsigment.updateUserGroupPriceList(<?php echo $value->group_id; ?>, <?php print $i; ?>)" />
											<input type="hidden" class="small3 form-control" name="add_usergroups_prices_start_discount_list[<?php echo $value->group_id;?>][]" id="add_usergroups_prices_start_discount_list_<?php echo $value->group_id;?>_<?php print $i?>" value="<?php echo $add_prices[$i]->start_discount;?>" />
											
										</td>
										<?php $pkey='plugin_consignment_attr'; if (isset($value->$pkey[$i]) && $value->$pkey[$i]){ print $value->$pkey[$i];}?>
										<td align="center">
											<a class="btn btn-micro" href="#" onclick="shopProductUserGroup.deletePriceList(<?php print $i?>,<?php echo $value->group_id;?>);return false;">
												<i class="icon-delete"></i>
											</a>
										</td>
									</tr>
									<?php
								}}
								?>   
								</tbody>             
							</table>
							<table class="table table-striped">
							<tr>
								<td><?php echo $lists['add_usergroups_prices_add_price_units_list'][$value->group_id];?> - <?php echo JText::_('COM_SMARTSHOP_UNIT_MEASURE');?></td>
								<td align="right" width="100">
									<input class="btn btn-primary button" type="button" name="add_usergroups_prices_add_new_price" onclick="shopProductUserGroup.addPriceList(<?php echo $value->group_id;?>);<?php $pkey='plugin_consignment_attr_button'; if ($value->$pkey){ print $value->$pkey;}?>" value="<?php echo JText::_('COM_SMARTSHOP_PRODUCT_ADD_PRICE_ADD');?>" />
								</td>
							</tr>
							</table>
							<script>
							<?php 
							print "var add_usergroups_prices_add_price_num=$i;"
							?>             
							</script>
						</div>
					</div>
				</div>
			</div>
		</div>
		</div>
		<?}}?>
        </div>
        
		<?php include __DIR__ . '/elements/usergroup_prices/add_new.php'; ?>

		<div class="form-group row align-items-center">
			<label for="product_price_type" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
                <?php echo JText::_('COM_SMARTSHOP_PRICE_TYPE'); ?>
			</label>
			<div class="col-sm-9 col-md-10 col-xl-10 col-12">
                <?php echo $this->productAttrPriceTypeSelect; ?>
            </div>
        </div>
		
		<div class="qty_formula form-group row align-items-center">
			<label for="qtydiscount" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label"> </label>
			<div class="col-sm-9 col-md-10 col-xl-10 col-12">
                <div class = "rows">
                <span class="col-md-3 col-lg-3"><?php print JText::_('COM_SMARTSHOP_FACP_QTY_DISCOUNT0') ?>
                        <input type = "radio" name = "qtydiscount" class="form-check-input" id = "qtydiscount" value = "0" <?php  if($row->qtydiscount == '0') { ?> checked="checked" <?php } ?> /></span>  
                    <span class = "col-md-3 col-lg-3"><?php print JText::_('COM_SMARTSHOP_FACP_QTY_DISCOUNT') ?> 
                        <input type = "radio" name = "qtydiscount" class="form-check-input" value = "1" <?php if($row->qtydiscount == '1'){  ?> checked="checked" <?php } ?> />
                    </span>
                    <span class = "col-md-3 col-lg-3"><?php print JText::_('COM_SMARTSHOP_FACP_QTY_DISCOUNT2') ?>
                        <input type = "radio" name = "qtydiscount" class="form-check-input" value = "2" <?php if($row->qtydiscount != '0' && $row->qtydiscount != '1' ){ ?> checked="checked" <?php } ?> />
                    </span>
                </div>
            </div>
        </div>
        <?php $pkey='plugin_template_price'; if (isset($this->$pkey) && $this->$pkey){ print $this->$pkey;}?>

        <?php if ($jshopConfig->admin_show_product_bay_price) : ?>
			<div class="qty_formula form-group row align-items-center">
				<label for="product_buy_price" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
                    <?php echo JText::_('COM_SMARTSHOP_PRODUCT_BUY_PRICE');?>
				</label>
				<div class="col-sm-9 col-md-10 col-xl-10 col-12">
                    <input type="text" class="form-control" name="product_buy_price" id="product_buy_price" value="<?php echo $row->product_buy_price?>" />
                </div>
            </div>
        <?php endif; ?>

        <?php if ($jshopConfig->admin_show_product_basic_price) : ?>
			<div class="qty_formula form-group row align-items-center">
				<label class="col-sm-12 col-md-12 col-xl-12 col-12">
					<br/><?php echo JText::_('COM_SMARTSHOP_BASIC_PRICE');?>
				</label>
            </div>
			<div class="qty_formula form-group row align-items-center">
				<label for="weight_volume_units" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
                    <?php echo JText::_('COM_SMARTSHOP_WEIGHT_VOLUME_UNITS');?>
				</label>
				<div class="col-sm-9 col-md-10 col-xl-10 col-12">
                    <input type="text" class="form-control" name="weight_volume_units" id="weight_volume_units" value="<?php echo $row->weight_volume_units?>" />
                </div>
            </div>
			<div class="qty_formula form-group row align-items-center">
				<label for="basic_price_unit_id" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
                    <?php echo JText::_('COM_SMARTSHOP_UNIT_MEASURE');?>
				</label>
				<div class="col-sm-9 col-md-10 col-xl-10 col-12">
                    <?php echo $lists['basic_price_units'];?>
                </div>
            </div>
        <?php endif; ?>
        
    </div>
  </div>