<tr class="freeattr_<?php print $this->id; ?>">
    <td class="key">
        
    </td>

    <td>
        <table class="table table-striped" id="upload_freeattribute_price">
            <tr id="head_ufp">
                <?php foreach($this->languages as $lang){ ?>
						<th><?php print JText::_('COM_SMARTSHOP_NAME_ATTRIBUT') ?> <?php  print "(".$lang->lang.")";?></th>
				<?php } ?>
                <th>
                    <?php print JText::_('COM_SMARTSHOP_QUANTITY') ?>
                </th>
                <th>
                    <?php print JText::_('COM_SMARTSHOP_PRICE') ?>
                </th>
                <th>
                </th>
            </tr>
            <?php for ($i = 0; $i < $this->free_count; $i++) : ?>
                <tr class="row_table_ufp">
                    <?php  foreach($this->languages as $lang){ 
						$name = "free_name_".$lang->language;
						$value = "name_".$lang->language; ?>
						<td><input type = "text" name = "<?php echo $name;?>[<?php echo $this->id; ?>][]" value = "<?php echo $this->free_list[$i]->$value;?>" /></td>
					<?php } ?>
                    <td>
                        <input type="text" name="free_count[<?php echo $this->id; ?>][]" value="<?php echo $this->free_list[$i]->quantity; ?>" size="30">
                    </td>
                    <td>
                        <input type="text" name="free_price[<?php echo $this->id; ?>][]" value="<?php echo $this->free_list[$i]->price; ?>" size="30">
                    </td>
                    <td>
                        <div class="jshop_delete btn btn-small btn-danger" onclick="delete_row_table_ufp(this);"><?php echo JText::_('ADDON_FREE_ATTRTYPE_QUANT_UFP_DELETE'); ?></div>                                
                    </td>
                </tr>
            <?php endfor; ?>
            <tr class="add_row_table_ufp">
                <td>
                </td>
                <td>
                </td>
                <td>
                </td>
                <td>
                </td>
                <td>
                    <div class="jshop_add btn btn-small btn-success" onclick="add_row_table_ufp();"><?php echo JText::_('ADDON_FREE_ATTRTYPE_QUANT_UFP_ADD'); ?></div>                                
                </td>
            </tr>
        </table>

        <?php 
	        if ( isset($this->lists['return_policy']) ) {
	        	echo $this->lists['return_policy'];
	        } elseif ( isset($lists['return_policy']) ) {
	        	echo $lists['return_policy'];
	        }
        ?>
    </td>

</tr>
