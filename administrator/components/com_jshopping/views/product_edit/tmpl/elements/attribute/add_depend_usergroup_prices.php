<div class="form-group row align-items-center attr_usergroup_price">
    <div class="col-sm-12 col-md-12 col-xl-12 col-12">
        <br><br><input type='hidden' value='0' name='attr_add_usergroup_price' id='attr_add_usergroup_price'>
        <div class='div_hidden_add_new_usergroup_price_button' onclick="shopProductAttribute.addUserGroupPrice(this)"><?php print JText::_('COM_SMARTSHOP_ADD_PRICE_FOR_USERGROUP') ?></div>
        <div id='attr_div_hidden_add_new_usergroup_price' class='attr_div_hidden_add_new_usergroup_price'> </div>
        <template id='attr_div_hidden_add_new_usergroup_price_temp' class='attr_div_hidden_add_new_usergroup_price_temp' style='display:none;'>
            <div class="jshops_edit usergroup_price row_groups_100500">
				<div class="form-group row align-items-center">                    
                    <div class="col-sm-12 col-md-12 col-xl-12 col-12">
                        <button type="button" onclick="shopProductAttribute.removeGroupRow(100500);" class="bg-transparent text-danger font-weight-bold border p-1 pt-0 pb-0 border-danger cursor-pointer">X</button>
                    </div>
                </div>			
                <div class="form-group row align-items-center">
                    <label for="attr_add_usergroups_prices_usergroup" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
                        <?php echo JText::_('COM_SMARTSHOP_USERGROUPS');?>*
                    </label>
                    <div class="col-sm-9 col-md-10 col-xl-10 col-12">
                        <?php echo $this->lists['attr_depend_usergroups'];?>
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label for="attr_add_usergroups_prices_product_price" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
                        <?php echo JText::_('COM_SMARTSHOP_PRODUCT_PRICE');?>*
                    </label>
                    <div class="col-sm-9 col-md-10 col-xl-10 col-12">
                        <input type="text" name="attr_add_usergroups_prices_product_price_list[100500]" id="attr_add_usergroups_prices_product_price_list_100500" class="form-control" value="<?php echo $row->product_price?>" <?php if (!$this->withouttax){?> onkeyup="shopProductUserGroup.updatePriceList(<?php print $jshopConfig->display_price_admin;?>, 100500, true, 'attr_')" <?php }?> />
                    </div>
                </div>
                <?php if (!$this->withouttax) : ?>
                    <div class="form-group row align-items-center">
                        <label for="attr_add_usergroups_prices_product_price2" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
                            <?php if ($jshopConfig->display_price_admin==0) echo JText::_('COM_SMARTSHOP_PRODUCT_NETTO_PRICE'); else echo JText::_('COM_SMARTSHOP_PRODUCT_BRUTTO_PRICE');?>
                        </label>
                        <div class="col-sm-9 col-md-10 col-xl-10 col-12">
                            <input type="text" name="attr_add_usergroups_prices_product_price2_list[100500]" id="attr_add_usergroups_prices_product_price2_list_100500" class="form-control" value="<?php echo $row->product_price2;?>" onkeyup="shopProductUserGroup.updatePriceList(<?php print $jshopConfig->display_price_admin;?>, 100500, false, 'attr_')" />
                        </div>
                    </div>
                <?php endif; ?>
                <div class="form-group row align-items-center">
                    <label for="attr_add_usergroups_prices_product_old_price" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
                        <?php echo JText::_('COM_SMARTSHOP_OLD_PRICE');?>
                    </label>
                    <div class="col-sm-9 col-md-10 col-xl-10 col-12">
                        <input type="text" name="attr_add_usergroups_prices_product_old_price[100500]" id="attr_add_usergroups_prices_product_old_price" class="form-control" value="<?php echo $row->product_old_price?>" />
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label for="attr_add_usergroups_prices_product_is_add_price" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
                        <?php echo JText::_('COM_SMARTSHOP_PRODUCT_ADD_PRICE');?>
                    </label>
                    <div class="col-sm-9 col-md-10 col-xl-10 col-12">
                        <input type="checkbox" name="attr_add_usergroups_prices_product_is_add_price[100500]" id="attr_add_usergroups_prices_product_is_add_price_100500" class="form-check-input" value="1" onclick="add_usergroups_prices_showHideAddPrice('#attr_add_usergroups_prices_product_is_add_price_100500', '#attr_add_usergroups_prices_tr_add_price_100500')" />
                    </div>
                </div>

                <div class="form-group row align-items-center">
                    <div class="jshops_edit col-sm-12 col-md-12 col-xl-12 col-12 col-form-label add_price_edit">
                        <div id="attr_add_usergroups_prices_tr_add_price_100500" class="form-group row align-items-center" style="display:none;">
                            <label class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
                                <?php echo JText::_('COM_SMARTSHOP_PRODUCT_ADD_PRICE');?>
                            </label>
                            <div class="table-responsive col-sm-9 col-md-10 col-xl-10 col-12">
                                <table id="attr_add_usergroups_prices_table_add_price_100500" class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>
                                                <?php echo JText::_('COM_SMARTSHOP_PRODUCT_QUANTITY_START');?>    
                                            </th>
                                            <th>
                                                <?php echo JText::_('COM_SMARTSHOP_PRODUCT_QUANTITY_FINISH');?>    
                                            </th>
                                            <th>
                                                <?php echo JText::_('COM_SMARTSHOP_DISCOUNT');?>
                                            </th>
                                            <th>
                                                <?php echo JText::_('COM_SMARTSHOP_PRODUCT_PRICE');?>
                                            </th>          

                                            <th>
                                                <?php echo JText::_('COM_SMARTSHOP_DELETE');?>    
                                            </th>
                                        </tr>
                                    </thead>   
                                    <tbody></tbody>                         
                                </table>
                                <hr />
                                <table class="table table-striped">
                                    <tr>
                                        <td><?php echo $lists['attr_add_usergroups_prices_add_price_units'];?> - <?php echo JText::_('COM_SMARTSHOP_UNIT_MEASURE');?></td>
                                        <td align="right" width="100">
                                            <input class="btn button btn-primary" type="button" onclick="shopProductUserGroup.addPrice('#attr_add_usergroups_prices_table_add_price_100500 tbody', 'attr_');" value="<?php echo JText::_('COM_SMARTSHOP_PRODUCT_ADD_PRICE_ADD');?>" />
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>        
</div>