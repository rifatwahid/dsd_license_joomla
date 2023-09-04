<?php
JFactory::getDocument()->addStyleSheet(JUri::base() . '/components/com_jshopping/css/free_attribute_calcule_price.css', 'text/css');

$_freeAttributes = JTable::getInstance('freeattribut', 'jshop');

$language =& JFactory::getLanguage();
$language->load('free_attribute_calcule_price' , JPATH_ROOT, $language->getTag(), true);

$nullFreeAttr = array();
$nullFreeAttr[0] = new stdClass();
$nullFreeAttr[0]->id = 0;
$nullFreeAttr[0]->name = ' - - - - ';
$key = 0;
$freeAttributes = array_merge($nullFreeAttr, $_freeAttributes->getAll());

$modelFreeAtrrCalcPrice = JSFactory::getModel('FreeAttrCalcPrice');
$count_variables = (count($modelFreeAtrrCalcPrice->getParametersVariables()) >= 2 ) ? count($modelFreeAtrrCalcPrice->getParametersVariables()) : 1;
$addonParams = $modelFreeAtrrCalcPrice->getAddonParameters();
$example = JText::_('COM_SMARTSHOP_FACP_FORMULA_DESCR').' 1: $width*$height*$depth<br />'.JText::_('COM_SMARTSHOP_FACP_FORMULA_DESCR').' 2: ($var1+$var2+$var3)/2<br />'.JText::_('COM_SMARTSHOP_FACP_FORMULA_DESCR').' 3($width+$var1)*($height+$var2)';

displaySubmenuOptions("",$this->canDo);
?>
<form action="index.php?option=com_jshopping&controller=formula_calculation" method="post" enctype="multipart/form-data" name="adminForm" id="adminForm">
<fieldset>
    <legend class="addon-params" data-variable-default-name="<?php echo JText::_('COM_SMARTSHOP_FACP_VARIABLE'); ?>"><?php echo JText::_('COM_SMARTSHOP_FACP_BASIC_PARAMETERS'); ?></legend>
    <div class="padding--15px">
        
        <!-- =========================================== -->
        <!-- Column 1 -->
        <div class = "facp_free_attr_def width--280px" id="facp_free_attr_column_1">
            <div>
                <div class = "facp_row_label"><?php echo JText::_('COM_SMARTSHOP_FACP_WIDTH'); ?> <span class = "var_descr">($width)</span></div>
                <div class = "facp_row_input"><?php echo JHTML::_('select.genericlist', $freeAttributes, 'params[variables][width_id]', 'class = "facp_input form-select" size = "1"', 'id', 'name', $addonParams['variables']['width_id']); ?></div>
            </div>
            <div>
                <div class = "facp_row_label"><?php echo JText::_('COM_SMARTSHOP_FACP_HEIGHT'); ?> <span class = "var_descr">($height)</span></div>
                <div class = "facp_row_input"><?php echo JHTML::_('select.genericlist', $freeAttributes, 'params[variables][height_id]', 'class = "facp_input form-select" size = "1"', 'id', 'name', $addonParams['variables']['height_id']); ?></div>
            </div>
            <div>
                <div class = "facp_row_label"><?php echo JText::_('COM_SMARTSHOP_FACP_DEPTH'); ?> <span class = "var_descr">($depth)</span></div>
                <div class = "facp_row_input"><?php echo JHTML::_('select.genericlist', $freeAttributes, 'params[variables][depth_id]', 'class = "facp_input form-select" size = "1"', 'id', 'name', $addonParams['variables']['depth_id']); ?></div>
            </div>

            <?php for ($i = 1; $i <= $count_variables; $i++) { 

                $variableName = JText::_('COM_SMARTSHOP_FACP_VARIABLE') . $i;

                if ( isset($addonParams['variablesNames']['var_' . $i]) ) {
                    $variableName = trim($addonParams['variablesNames']['var_' . $i]);
                } 
            ?>

                <div class="facp_free_attr_row-<?php echo $i; ?>">
                    <div class = "facp_row_label facp_row_label__variable"> 
                        <div class="facp-variableName-edit">
                            <input type="text" class="facp-variableName-edit__input form-control" name="params[variablesNames][var_<?php echo $i; ?>]" value="<?php echo $variableName; ?>" onfocusout="shopProductFreeAttribute.showText(this);">
                        </div>

                        <div class="fa-solid fa-xmark" onclick="shopProductFreeAttribute.removeRow(<?php echo $i; ?>);">X</div>
                        <div class="icon-pencil" onclick="shopProductFreeAttribute.hideText(this);"></div>

                        <span class="facp_row_label__variable-name">
                              <?php echo $variableName; ?>
                        </span>  
                    
                        <span class = "var_descr">
                            (<span class="var_descr__name">$var<?php echo $i; ?></span>)
                        </span>
                    
                    </div>
                    <div class = "facp_row_input">
                        <?php echo JHTML::_('select.genericlist', $freeAttributes, 'params[variables][var_' . $i . ']', 'class = "facp_input form-select" size = "1"', 'id', 'name', $addonParams['variables']['var_' . $i]); ?>
                    </div>
                </div>
            <?php } ?>
        
        </div>
        
        <!-- Column 2 -->
        <div class = "facp_free_attr_def" id="facp_free_attr_column_2">
            <div>
                <div class = "facp_row_label"><?php echo JText::_('COM_SMARTSHOP_FACP_WIDTH_DEFAULT'); ?></div>
                <div class = "facp_row_input"><input type = "text" class = "facp_input form-control" name = "params[width_def]" value = "<?php echo $addonParams['width_def'] ?>" /></div>
            </div>
            <div>
                <div class = "facp_row_label"><?php echo JText::_('COM_SMARTSHOP_FACP_HEIGHT_DEFAULT'); ?></div>
                <div class = "facp_row_input"><input type = "text" class = "facp_input form-control" name = "params[height_def]" value = "<?php echo $addonParams['height_def'] ?>" /></div>
            </div>
            <div>
                <div class = "facp_row_label"><?php echo JText::_('COM_SMARTSHOP_FACP_DEPTH_DEFAULT'); ?></div>
                <div class = "facp_row_input"><input type = "text" class = "facp_input form-control" name = "params[depth_def]" value = "<?php echo $addonParams['depth_def'] ?>" /></div>
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
                    <div class = "facp_row_input"><input type = "text" class = "facp_input form-control" name = "params[var_<?php echo $i; ?>_def]" value = "<?php echo $addonParams['var_' . $i . '_def'] ?>" /></div>
                </div>
            <?php } ?>
            
        </div>
    
        <!-- Column 3 -->
        <div class = "facp_free_attr_def" id="facp_free_attr_column_3">
            <div>
                <div class = "facp_row_label"><?php echo JText::_('COM_SMARTSHOP_FACP_WIDTH_MINIMUM'); ?></div>
                <div class = "facp_row_input"><input type = "text" class = "facp_input form-control" name = "params[width_min]" value = "<?php echo $addonParams['width_min'] ?>" /></div>
            </div>
            <div>
                <div class = "facp_row_label"><?php echo JText::_('COM_SMARTSHOP_FACP_HEIGHT_MINIMUM'); ?></div>
                <div class = "facp_row_input"><input type = "text" class = "facp_input form-control" name = "params[height_min]" value = "<?php echo $addonParams['height_min'] ?>" /></div>
            </div>
            <div>
                <div class = "facp_row_label"><?php echo JText::_('COM_SMARTSHOP_FACP_DEPTH_MINIMUM'); ?></div>
                <div class = "facp_row_input"><input type = "text" class = "facp_input form-control" name = "params[depth_min]" value = "<?php echo $addonParams['depth_min'] ?>" /></div>
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
                    <div class = "facp_row_input"><input type = "text" class = "facp_input form-control" name = "params[var_<?php echo $i; ?>_min]" value = "<?php echo $addonParams['var_' . $i . '_min'] ?>" /></div>
                </div>
            <?php } ?>
        </div>

        <!-- Column 4 -->
        <div class = "facp_free_attr_def" id="facp_free_attr_column_4">
            <div>
                <div class = "facp_row_label"><?php echo JText::_('COM_SMARTSHOP_FACP_WIDTH_MAXIMUM'); ?></div>
                <div class = "facp_row_input"><input type = "text" class = "facp_input form-control" name = "params[width_max]" value = "<?php echo $addonParams['width_max'] ?>" /></div>
            </div>
            <div>
                <div class = "facp_row_label"><?php echo JText::_('COM_SMARTSHOP_FACP_HEIGHT_MAXIMUM'); ?></div>
                <div class = "facp_row_input"><input type = "text" class = "facp_input form-control" name = "params[height_max]" value = "<?php echo $addonParams['height_max'] ?>" /></div>
            </div>
            <div>
                <div class = "facp_row_label"><?php echo JText::_('COM_SMARTSHOP_FACP_DEPTH_MAXIMUM'); ?></div>
                <div class = "facp_row_input"><input type = "text" class = "facp_input form-control" name = "params[depth_max]" value = "<?php echo $addonParams['depth_max'] ?>" /></div>
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

                    <div class = "facp_row_input"><input type = "text" class = "facp_input form-control" name = "params[var_<?php echo $i; ?>_max]" value = "<?php echo $addonParams['var_' . $i . '_max'] ?>" /></div>
                </div>
            <?php } ?>
        </div>
        
        <!-- Column 5 -->
        <div class = "facp_free_attr_def_small" id="facp_free_attr_column_5">
            <div class = "facp_row_input"><input type = "text" class = "facp_input form-control" name = "params[width_step]" placeholder = "<?php echo JText::_('COM_SMARTSHOP_FACP_WIDTH_STEP'); ?>" value = "<?php echo $addonParams['width_step'] ?>" /></div>
            <div class = "facp_row_input"><input type = "text" class = "facp_input form-control" name = "params[height_step]" placeholder = "<?php echo JText::_('COM_SMARTSHOP_FACP_HEIGHT_STEP'); ?>" value = "<?php echo $addonParams['height_step'] ?>" /></div>
            <div class = "facp_row_input"><input type = "text" class = "facp_input form-control" name = "params[depth_step]" placeholder = "<?php echo JText::_('COM_SMARTSHOP_FACP_DEPTH_STEP'); ?>" value = "<?php echo $addonParams['depth_step'] ?>" /></div>

            <?php for ($i = 1; $i <= $count_variables; $i++) { 
                $placeholder = sprintf(JText::_('COM_SMARTSHOP_FACP_VARIABLE_STEP'), $i);

                if ( isset($addonParams['variablesNames']['var_' . $i]) ) {
                    $placeholder = $addonParams['variablesNames']['var_' . $i] . ' ' . JText::_('COM_SMARTSHOP_FACP_STEP');
                }
            ?>
                <div class = "facp_row_input variable_<?php echo $i;?>_step">
                    <input type = "text" class = "facp_input form-control" name = "params[var_<?php echo $i; ?>_step]" placeholder = "<?php echo $placeholder; ?>" value = "<?php echo $addonParams['var_' . $i . '_step'] ?>" />
                </div>
            <?php } ?>
        </div>
        <!-- =========================================== -->
        <div class = "clr"></div>
        <div class="mgb--20px mgt--10px">
            <span class="facp_option_type facp_active facp_free_attr_add_new_row" id="facp_free_attr_add_new_row" onclick="shopProductFreeAttribute.addLastRow(this);">+</span>
        </div>        


<fieldset>
    <?php $oneTimeCostArrKey = 100500; ?>
    <legend><?php echo JText::_('COM_SMARTSHOP_PRICE_TYPES'); ?></legend>
    
    <div class="formula_name_empty_block display--none">
        <div class = "facp_free_attr_def">
            <div class = "facp_row_label">
                <?php echo JText::_('COM_SMARTSHOP_FACP_FORMULA'); ?> 
            </div>
            <div class = "facp_row_input"><input type = "text" class = "facp_input form-control" name = "params[pricetypes_formula][]"  alt="Formula. Example 2*($width+$height+$depth)" placeholder="2*($width+$height+$depth)" value = "" /></div>
        </div>
        <div class = "facp_free_attr_def">
            <div class = "facp_row_label">
                <?php echo JText::_('COM_SMARTSHOP_FACP_FORMULA_NAME'); ?> 
            </div>
            <div class = "facp_row_input"><input type = "text" class = "facp_input form-control" name = "params[pricetypes_formula_name][]"  alt="Formulaname" placeholder="Formulaname" value = "" /></div>
        </div>
        <div class = "clr"></div>
    </div>
    
    <?php if (isset($addonParams['pricetypes_formula']) && count($addonParams['pricetypes_formula'])) : ?>
        <?php foreach ($addonParams['pricetypes_formula'] as $key => $pricetypes_formula) : 
        if ($pricetypes_formula == ''  && $key != $oneTimeCostArrKey) continue;
        ?>
        <div <?php if ($key == $oneTimeCostArrKey) { echo 'class="hide hidden oneTimeCostPriceType"'; }?>>
            <div class = "facp_free_attr_def">
                <div class = "facp_row_label">
                    <?php echo JText::_('COM_SMARTSHOP_FACP_FORMULA'); ?> 
                </div>
                <div class = "facp_row_input"><input type = "text" class = "facp_input form-control" name = "params[pricetypes_formula][<?php echo $key;?>]"  alt="Formula. Example 2*($width+$height+$depth)" placeholder="2*($width+$height+$depth)" value = "<?php echo $pricetypes_formula ?>" /></div>
            </div>
            <div class = "facp_free_attr_def">
                <div class = "facp_row_label">
                    <?php echo JText::_('COM_SMARTSHOP_FACP_FORMULA_NAME'); ?> 
                </div>
                <div class = "facp_row_input"><input type = "text" class = "facp_input form-control" name = "params[pricetypes_formula_name][<?php echo $key;?>]"  alt="Formulaname" placeholder="Formulaname" value = "<?php echo $addonParams['pricetypes_formula_name'][$key] ?>" /></div>
            </div>
            <div class = "clr"></div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>

    
    <div class="buttonAdd width--1230px text-align--right margin--top-10px"><input type = "button" value = "<?php echo JText::_('COM_SMARTSHOP_FACP_NEW_OPTION'); ?>" class = "facp_option_type facp_active" onclick = "shopProductFreeAttribute.addOption()"></div>
</fieldset>
<input type="hidden" name="task" value="" />
<div class = "clr margin--top-10px"></div>
</form>
