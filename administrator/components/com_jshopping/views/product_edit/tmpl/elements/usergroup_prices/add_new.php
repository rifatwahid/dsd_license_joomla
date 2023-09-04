<div class="form-group row align-items-center">
    <div class="col-sm-12 col-md-12 col-xl-12 col-12">
        <br><br><input type='hidden' value='0' name='add_usergroup_price' id='add_usergroup_price'>
        <div class='div_hidden_add_new_usergroup_price_button' onclick="shopProductUserGroup.addUserGroupPrice();"><?php print JText::_('COM_SMARTSHOP_ADD_PRICE_FOR_USERGROUP') ?></div>
            <template id='div_hidden_add_new_usergroup_price'c style='display:none;'>
                <div class="col-sm-12 col-md-12 col-xl-12 col-12 col-form-label ">
                    <hr>
                </div>
                <div id="add_usergroups_prices_add_price_100500">
            <div class="jshops_edit usergroups_prices" >
                <div class="form-group row align-items-center">
                    <label for="add_usergroups_prices_usergroup" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">
                        <?php echo JText::_('COM_SMARTSHOP_USERGROUPS');?>*
                    </label>
                    <div class="col-sm-9 col-md-10 col-xl-10 col-12">
                        <?php echo $this->lists['usergroup_add_price'];?>
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label for="add_usergroups_prices_product_price" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">
                        <?php echo JText::_('COM_SMARTSHOP_PRODUCT_PRICE');?>*
                    </label>
                    <div class="col-sm-9 col-md-10 col-xl-10 col-12">
                        <input type="text" name="add_usergroups_prices_product_price_list[100500]" class="form-control" id="add_usergroups_prices_product_price_list_100500" value="<?php echo $row->product_price?>" <?php if (!$this->withouttax){?> onkeyup="shopProductUserGroup.updateAllPrices(100500);shopProductUserGroup.updatePriceList(<?php print $jshopConfig->display_price_admin;?>, 100500, true)" <?php }?> />
                    </div>
                </div>
                <?php if (!$this->withouttax) : ?>
                    <div class="form-group row align-items-center div_hidden_add_new_usergroup_price__nettoPrice">
                        <label for="add_usergroups_prices_product_price2" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">
                            <?php if ($jshopConfig->display_price_admin==0) echo JText::_('COM_SMARTSHOP_PRODUCT_NETTO_PRICE'); else echo JText::_('COM_SMARTSHOP_PRODUCT_BRUTTO_PRICE');?>
                    </label>
                    <div class="col-sm-9 col-md-10 col-xl-10 col-12 div_hidden_add_new_usergroup_price__nettoPrice">
                        <input type="text" id="add_usergroups_prices_product_price2_list_100500" class="form-control" name="add_usergroups_prices_product_price2_list[100500]" value="<?php echo $row->product_price2;?>" onkeyup="shopProductUserGroup.updatePriceList(<?php print $jshopConfig->display_price_admin;?>, 100500, false)" />
                    </div>
                </div>
                <?php endif; ?>
                <div class="form-group row align-items-center">
                    <label for="add_usergroups_prices_product_old_price" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">
                        <?php echo JText::_('COM_SMARTSHOP_OLD_PRICE');?>
                    </label>
                    <div class="col-sm-9 col-md-10 col-xl-10 col-12">
                        <input type="text" id="add_usergroups_prices_product_old_price" class="form-control" name="add_usergroups_prices_product_old_price_list[100500]" value="<?php echo $row->product_old_price?>" />
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label for="add_usergroups_prices_product_is_add_price" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">
                        <?php echo JText::_('COM_SMARTSHOP_PRODUCT_ADD_PRICE');?>
                    </label>
                    <div class="col-sm-9 col-md-10 col-xl-10 col-12">
                        <input type="checkbox" name="add_usergroups_prices_product_is_add_price_list[100500]" class="form-check-input" id="add_usergroups_prices_product_is_add_price" value="1" onclick="add_usergroups_prices_showHideAddPrice()" />
                    </div>
                </div>
            <div class="form-group row align-items-center">
                <div class="jshops_edit col-sm-12 col-md-12 col-xl-12 col-12 col-form-label add_usergroups_prices_tr_add_price ">
                    <div id="add_usergroups_prices_tr_add_price" class="form-group row align-items-center">
                        <label class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">
                            <?php echo JText::_('COM_SMARTSHOP_PRODUCT_ADD_PRICE');?>
                        </label>
                        <div class="table-responsive col-sm-9 col-md-10 col-xl-10 col-12">
                            <table id="add_usergroups_prices_table_add_price_list_100500" class="table table-striped">
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
                                    <?php $pkey='plugin_consignment_attr_title'; if (isset($row->$pkey) && $row->$pkey){ print $row->$pkey;}?>
                                    <th>
                                        <?php echo JText::_('COM_SMARTSHOP_DELETE');?>    
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                </tbody>
                                </table>
                                <hr />
                                <table class="table table-striped">
                                <tr>
                                    <td><?php echo $lists['add_usergroups_prices_add_price_units_add'];?> - <?php echo JText::_('COM_SMARTSHOP_UNIT_MEASURE');?></td>
                                    <td align="right" width="100" class="new_pr_but" id="but_row_100500">
                                        <input class="btn button btn-primary" type="button" name="add_usergroups_prices_add_new_price" id="add_usergroups_prices_add_new_price" onclick="shopProductUserGroup.addRowsPrice(this);<?php $pkey='plugin_consignment_attr_button'; if ($row->$pkey){ print $row->$pkey;}?>" value="<?php echo JText::_('COM_SMARTSHOP_PRODUCT_ADD_PRICE_ADD');?>" />
                                    </td>
                                </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>	
            </div>	
            </div>
        </template>
        <hr>
        </div>
        <br>           
</div>