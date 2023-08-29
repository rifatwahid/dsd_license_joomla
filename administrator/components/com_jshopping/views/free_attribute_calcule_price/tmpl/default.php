<?php
defined('_JEXEC') or die;

$modelFreeAtrrCalcPrice = JSFactory::getModel('FreeAttrCalcPrice');
$addonParams = $modelFreeAtrrCalcPrice->getAddonParameters();
$count_variables = (count($modelFreeAtrrCalcPrice->getParametersVariables()) >= 2 ) ? count($modelFreeAtrrCalcPrice->getParametersVariables()) : 1;
?>
<div class = "facp_adv_options" id = "adv_options_<?php echo $this->key; ?>">
    <img src = "<?php echo JUri::root(); ?>administrator/components/com_jshopping/images/free_attribute_calcule_price/delete.png" class="float--right cursor--pointer margin--bottom-4px"onclick = "shopProductFreeAttribute.deleteOption('<?php echo $this->key ?>')" />

    <div class = "clr"></div>

    <div class="float--left margin--right-13px width--264px">

        <input type = "hidden" id = "facp_adv_option_type_<?php echo $this->key; ?>" name = "params[adv_options][<?php echo $this->key; ?>][option_type]" value = "1">

        <div class="width--264px height--25px">
            <input type = "button" class = "facp_option_type facp_active float--left" id = "facp_adv_option_type_cat_<?php echo $this->key; ?>" value = "<?php echo JText::_('COM_SMARTSHOP_CATEGORIES'); ?>" onclick = "
                    document.querySelector('#facp_adv_option_type_<?php echo $this->key; ?>').value = 1;
                    this.classList.add('facp_active');
                    document.querySelector('#facp_adv_option_type_prod_<?php echo $this->key; ?>').classList.remove('facp_active');
                    document.querySelector('#facp_option_categories_block_<?php echo $this->key; ?>').style.display = 'block';
                    document.querySelector('#facp_option_products_block_<?php echo $this->key; ?>').style.display = 'none';">
            <input type = "button" class = "facp_option_type float--right" id = "facp_adv_option_type_prod_<?php echo $this->key; ?>" value = "<?php echo JText::_('COM_SMARTSHOP_PRODUCTS'); ?>" onclick = "
                    document.querySelector('#facp_adv_option_type_<?php echo $this->key; ?>').value = 2
                    this.classList.add('facp_active');
                    document.querySelector('#facp_adv_option_type_cat_<?php echo $this->key; ?>').classList.remove('facp_active');
                    document.querySelector('#facp_option_categories_block_<?php echo $this->key; ?>').style.display = 'none';
                    document.querySelector('#facp_option_products_block_<?php echo $this->key; ?>').style.display = 'block';">
        </div>

        <div id = "facp_option_categories_block_<?php echo $this->key; ?>">
            <?php echo JHTML::_('select.genericlist', buildTreeCategory(0), 'params[adv_options][' . $this->key . '][categories][]', 'class="facp_input width--264px form-select" multiple="multiple" size="8"', 'category_id', 'name', array()); ?>
        </div>

        <div class = "clr"></div>

        <div id = "facp_option_products_block_<?php echo $this->key; ?>" class="display--none">
            <div class = "facp_prod_id_descr"><?php echo JText::_('COM_SMARTSHOP_FACP_ENTER_PRODUCTS_IDS'); ?></div>
            <input type = "text" name = "params[adv_options][<?php echo $this->key; ?>][products]" class="facp_input width--250px" value = "" />
        </div>

    </div>

    <div class = "facp_separator"></div>

    <div class = "facp_free_attr_def">
        <div>
            <div class = "facp_row_label"><?php echo JText::_('COM_SMARTSHOP_FACP_WIDTH_DEFAULT'); ?></div>
            <div class = "facp_row_input"><input type = "text" class = "facp_input" name = "params[adv_options][<?php echo $this->key; ?>][width_def]" value = "" /></div>
        </div>
        <div>
            <div class = "facp_row_label"><?php echo JText::_('COM_SMARTSHOP_FACP_HEIGHT_DEFAULT'); ?></div>
            <div class = "facp_row_input"><input type = "text" class = "facp_input" name = "params[adv_options][<?php echo $this->key; ?>][height_def]" value = "" /></div>
        </div>
        <div>
            <div class = "facp_row_label"><?php echo JText::_('COM_SMARTSHOP_FACP_DEPTH_DEFAULT'); ?></div>
            <div class = "facp_row_input"><input type = "text" class = "facp_input" name = "params[adv_options][<?php echo $this->key; ?>][depth_def]" value = "" /></div>
        </div>
        <?php for ($i = 1; $i <= $count_variables; $i++) { 
                $variableName = sprintf(JText::_('COM_SMARTSHOP_FACP_VARIABLE_DEFAULT'), $i);
                $variableNamePrefix = '';

                if ( isset($addonParams['variablesNames']['var_' . $i]) ) {
                    $variableName = trim($addonParams['variablesNames']['var_' . $i]);
                    $variableNamePrefix = JText::_('COM_SMARTSHOP_FACP_DEFAULT');
                }            
        ?>
            <div>
                <div class = "facp_row_label">
                    <span class="variable_<?php echo $i;?>_default"> <?php echo $variableName; ?> </span>
                    <span class="variable_<?php echo $i;?>_default--prefix-name"> <?php echo $variableNamePrefix; ?></span>
                </div>                

                <div class = "facp_row_input">
                    <input type = "text" class = "facp_input" name = "params[adv_options][<?php echo $this->key; ?>][var_<?php echo $i; ?>_def]" value = "" />
                </div>
            </div>
        <?php } ?>
    </div>

    <div class = "facp_free_attr_def">
        <div>
            <div class = "facp_row_label"><?php echo JText::_('COM_SMARTSHOP_FACP_WIDTH_MINIMUM'); ?></div>
            <div class = "facp_row_input"><input type = "text" class = "facp_input" name = "params[adv_options][<?php echo $this->key; ?>][width_min]" value = "" /></div>
        </div>
        <div>
            <div class = "facp_row_label"><?php echo JText::_('COM_SMARTSHOP_FACP_HEIGHT_MINIMUM'); ?></div>
            <div class = "facp_row_input"><input type = "text" class = "facp_input" name = "params[adv_options][<?php echo $this->key; ?>][height_min]]" value = "" /></div>
        </div>
        <div>
            <div class = "facp_row_label"><?php echo JText::_('COM_SMARTSHOP_FACP_DEPTH_MINIMUM'); ?></div>
            <div class = "facp_row_input"><input type = "text" class = "facp_input" name = "params[adv_options][<?php echo $this->key; ?>][depth_min]" value = "" /></div>
        </div>
        <?php for ($i = 1; $i <= $count_variables; $i++) { 
                $variableName = sprintf(JText::_('COM_SMARTSHOP_FACP_VARIABLE_MINIMUM'), $i);
                $variableNamePrefix = '';

                if ( isset($addonParams['variablesNames']['var_' . $i]) ) {
                    $variableName = trim($addonParams['variablesNames']['var_' . $i]);
                    $variableNamePrefix = JText::_('COM_SMARTSHOP_FACP_MIN');
                }            
        ?>
            <div>
                <div class = "facp_row_label">
                    <span class="variable_<?php echo $i;?>_minimum"> <?php echo $variableName; ?> </span>
                    <span class="variable_<?php echo $i;?>_minimum--prefix-name"> <?php echo $variableNamePrefix; ?></span>
                </div>                

                <div class = "facp_row_input"><input type = "text" class = "facp_input" name = "params[adv_options][<?php echo $this->key; ?>][var_<?php echo $i; ?>_min]" value = "" /></div>
            </div>
        <?php } ?>
    </div>

    <div class = "facp_free_attr_def">
        <div>
            <div class = "facp_row_label"><?php echo JText::_('COM_SMARTSHOP_FACP_WIDTH_MAXIMUM'); ?></div>
            <div class = "facp_row_input"><input type = "text" class = "facp_input" name = "params[adv_options][<?php echo $this->key; ?>][width_max]" value = "" /></div>
        </div>
        <div>
            <div class = "facp_row_label"><?php echo JText::_('COM_SMARTSHOP_FACP_HEIGHT_MAXIMUM'); ?></div>
            <div class = "facp_row_input"><input type = "text" class = "facp_input" name = "params[adv_options][<?php echo $this->key; ?>][height_max]" value = "" /></div>
        </div>
        <div>
            <div class = "facp_row_label"><?php echo JText::_('COM_SMARTSHOP_FACP_DEPTH_MAXIMUM'); ?></div>
            <div class = "facp_row_input"><input type = "text" class = "facp_input" name = "params[adv_options][<?php echo $this->key; ?>][depth_max]" value = "" /></div>
        </div>
        <?php for ($i = 1; $i <= $count_variables; $i++) { 
                $variableName = sprintf(JText::_('COM_SMARTSHOP_FACP_VARIABLE_MAXIMUM'), $i);
                $variableNamePrefix = '';

                if ( isset($addonParams['variablesNames']['var_' . $i]) ) {
                    $variableName = trim($addonParams['variablesNames']['var_' . $i]);
                    $variableNamePrefix = JText::_('COM_SMARTSHOP_FACP_MAX');
                }            
        ?>
            <div>
                <div class = "facp_row_label">
                    <span class="variable_<?php echo $i;?>_maximum"> <?php echo $variableName; ?> </span>
                    <span class="variable_<?php echo $i;?>_maximum--prefix-name"> <?php echo $variableNamePrefix; ?></span>
                </div>                   

                <div class = "facp_row_input"><input type = "text" class = "facp_input" name = "params[adv_options][<?php echo $this->key; ?>][var_<?php echo $i; ?>_max]" value = "" /></div>
            </div>
        <?php } ?>
    </div>

    <div class = "facp_free_attr_def_small">
        <div class = "facp_row_input"><input type = "text" class = "facp_input" name = "params[adv_options][<?php echo $this->key; ?>][width_step]" placeholder = "<?php echo JText::_('COM_SMARTSHOP_FACP_WIDTH_STEP'); ?>" value = "" /></div>
        <div class = "facp_row_input"><input type = "text" class = "facp_input" name = "params[adv_options][<?php echo $this->key; ?>][height_step]" placeholder = "<?php echo JText::_('COM_SMARTSHOP_FACP_HEIGHT_STEP'); ?>" value = "" /></div>
        <div class = "facp_row_input"><input type = "text" class = "facp_input" name = "params[adv_options][<?php echo $this->key; ?>][depth_step]" placeholder = "<?php echo JText::_('COM_SMARTSHOP_FACP_DEPTH_STEP'); ?>" value = "" /></div>
        <?php for ($i = 1; $i <= $count_variables; $i++) { 
                $placeholder = sprintf(JText::_('COM_SMARTSHOP_FACP_VARIABLE_STEP'), $i);

                if ( isset($addonParams['variablesNames']['var_' . $i]) ) {
                    $placeholder = $addonParams['variablesNames']['var_' . $i] . ' ' . JText::_('COM_SMARTSHOP_FACP_STEP');
                }            
        ?>
            <div class = "facp_row_input">
                <input type = "text" class = "facp_input" name = "params[adv_options][<?php echo $this->key; ?>][var_<?php echo $i; ?>_step]" placeholder = "<?php echo $placeholder; ?>" value = "" />
            </div>
        <?php } ?>
    </div>

    <div class = "clr"></div>
    <div class = "facp_free_attr_def width--600px">
        <div class = "facp_row_label"><?php echo JText::_('COM_SMARTSHOP_FACP_FORMULA'); ?></div>
        <div class = "facp_row_input"><input type = "text" class = "facp_input width--300px" name = "params[adv_options][<?php echo $this->key; ?>][formula]" value = "" /></div>
    </div>
    <div class = "facp_free_attr_def">
        <div class = "facp_row_label"><?php echo JText::_('COM_SMARTSHOP_FACP_RESULT_LABEL'); ?></div>
        <div class = "facp_row_input"><input type = "text" class = "facp_input" name = "params[adv_options][<?php echo $this->key; ?>][label]" value = "" /></div>
    </div>
    <div class = "facp_free_attr_def">
        <div class = "facp_row_label"><?php echo JText::_('COM_SMARTSHOP_FACP_RESULT_SUFFIX'); ?></div>
        <div class = "facp_row_input"><input type = "text" class = "facp_input" name = "params[adv_options][<?php echo $this->key; ?>][suffix]" value = "" /></div>
    </div>

    <div class = "clr"></div>
    <div class = "facp_free_attr_def width--35px float--left">
        <div class = "facp_row_label width--280px"><?php echo JText::_('COM_SMARTSHOP_FACP_CALC_BASIC_PRICE_FOR_FREEATTR'); ?></div>
        <div class = "facp_row_input"><input type = "checkbox" name = "params[adv_options][<?php echo $this->key; ?>][calc_basic_price_for_freeattr]" value = "1" /></div>
    </div>
    <div class = "facp_free_attr_def width--280px float--left">
        <div class = "facp_row_label width--120px"><?php echo JText::_('COM_SMARTSHOP_FACP_QTY_DISCOUNT0'); ?></div>
        <div class = "facp_row_input"><input type = "radio" name = "params[adv_options][<?php echo $this->key; ?>][qtydiscount]" value = "0" /></div>
    </div>
    <div class = "facp_free_attr_def width--200px float--left">
        <div class = "facp_row_label width--120px"><?php echo JText::_('COM_SMARTSHOP_FACP_QTY_DISCOUNT'); ?></div>
        <div class = "facp_row_input"><input type = "radio" name = "params[adv_options][<?php echo $this->key; ?>][qtydiscount]" value = "1" /></div>
    </div>
    <div class = "facp_free_attr_def width--200px float--left">
        <div class = "facp_row_label width--120px"><?php echo JText::_('COM_SMARTSHOP_FACP_QTY_DISCOUNT2'); ?></div>
        <div class = "facp_row_input"><input type = "radio" name = "params[adv_options][<?php echo $this->key; ?>][qtydiscount]" value = "2" /></div>
    </div>
    <div class = "facp_free_attr_def width--200px float--left">
        <div class = "facp_row_label width--120px"><?php echo JText::_('COM_SMARTSHOP_FACP_BERECHNUNG_DER_ANZAHL'); ?></div>
        <div class = "facp_row_input"><input type = "checkbox" name = "params[adv_options][<?php echo $this->key; ?>][berechnungderanzahl]" value = "1" /></div>
    </div>
    <div class = "clr"></div>

    <div class = "facp_free_attr_def width--350px float--left">
        <div class = "facp_row_label width--280px"><?php echo JText::_('COM_SMARTSHOP_FACP_SHOW_MINMAXVAL'); ?></div>
        <div class = "facp_row_input"><input type = "checkbox" name = "params[adv_options][<?php echo $this->key; ?>][show_min_max_val]" value = "1" /></div>
    </div>
    <div class = "facp_free_attr_def">
        <div class = "facp_row_label"><?php echo JText::_('COM_SMARTSHOP_UNIT_MEASURE'); ?></div>
        <div class = "facp_row_input"><input type = "text" class = "facp_input" name = "params[adv_options][<?php echo $this->key; ?>][unitmeasure]" value = "" /></div>
    </div>
    <div class = "facp_free_attr_def">
        <div class = "facp_row_label"><?php echo JText::_('COM_SMARTSHOP_FUNC_DISPLAY_NAME_IN_MSG'); ?></div>
        <div class = "facp_row_input"><input type = "text" class = "facp_input" name = "params[adv_options][<?php echo $this->key; ?>][func_disp_name_in_msg]" value = "" /></div>
    </div>
    <div class = "facp_free_attr_def width--200px float--left">
        <div class = "facp_row_label width--120px"><?php echo JText::_('COM_SMARTSHOP_FACP_BERECHNUNG_SQUARE_METER'); ?></div>
        <div class = "facp_row_input"><input type = "checkbox" name = "params[adv_options][<?php echo $this->key; ?>][berechnungquadratmeterwert]" value = "1" /></div>
    </div>
    <div class = "clr"></div>
</div>