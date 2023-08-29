<?php
/**
* @version      4.9.0 13.08.2013
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');

$jshopConfig=JSFactory::getConfig();
if (version_compare(JVERSION, '3.999.999', 'le')) JHTML::_('behavior.tooltip');

$freeAttributes = $this->freeAttributes;
$data_options = $this->data_options;
?>
<link rel="stylesheet" href="<?php print $jshopConfig->live_admin_path; ?>css/VueQueryBuilder.css" id="bt-theme">	
<div class="jshop_condition_edit">
<form action="index.php?option=com_jshopping&controller=conditions&task=save_condition" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data" >
	<fieldset>
	<div class="padding--15px mb-1">
		<div class = "facp_free_attr_def" id="facp_free_attr_column_1">
			<div class="row">
				<div class="col-4">
					<div class = "facp_row_label"><?php echo JText::_('COM_SMARTSHOP_FACP_WIDTH'); ?> <span class = "var_descr">($width)</span></div>
					<div class = "facp_row_input"><?php echo JHTML::_('select.genericlist', $freeAttributes, 'width_id', 'class = "facp_input form-select" size = "1"', 'id', 'name', $data_options->width_id); ?></div>
				</div>
				<div class="col-4">
					<div class = "facp_row_label"><?php echo JText::_('COM_SMARTSHOP_FACP_HEIGHT'); ?> <span class = "var_descr">($height)</span></div>
					<div class = "facp_row_input"><?php echo JHTML::_('select.genericlist', $freeAttributes, 'height_id', 'class = "facp_input form-select" size = "1"', 'id', 'name', $data_options->height_id); ?></div>
				</div>
				<div class="col-4">
					<div class = "facp_row_label"><?php echo JText::_('COM_SMARTSHOP_FACP_DEPTH'); ?> <span class = "var_descr">($depth)</span></div>
					<div class = "facp_row_input"><?php echo JHTML::_('select.genericlist', $freeAttributes, 'depth_id', 'class = "facp_input form-select" size = "1"', 'id', 'name', $data_options->depth_id); ?></div>
				</div>
			</div>
		</div>
	</div>
	</fieldset>
	<div id="app">
	
		<div class="jshops_edit condition_edit" >
			<div class="form-group row align-items-center">
				<label for="lname" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">
					<?php echo  JText::_('COM_SMARTSHOP_NAME')?>
				</label>
				<div class="col-sm-9 col-md-10 col-xl-10 col-12">
					<input type="text" class="form-control" name="name" id="lname" value="<?php print $this->condition->name?>" />					
				</div>
			</div>
					
			<div class="form-group row align-items-center">
				<label for="lordering" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label ">
					<?php echo  JText::_('COM_SMARTSHOP_ORDERING')?>
				</label>
				<div class="col-sm-9 col-md-10 col-xl-10 col-12">
					<input type="text" class="form-control" name="ordering" id="lordering" value="<?php print $this->condition->ordering?>" />					
				</div>
			</div>
		</div>
		<div class="rules">
			<vue-query-builder
					ref="query"
					v-model="query"
					:rules="[
					<?php foreach($this->types as $type=>$val){	?>
							{
							type: 'text',
							id: '<?php print $type; ?>',
							label: '<?php print $val; ?>',
							operators: ['=','!=','<','<=','>','>='],
							},
					<?php } ?>
					 {
						type: 'operation',
						id: 'operation_whd',
						inputType: 'operation',
						label: 'Operation',
						operators: ['=','!=','<','<=','>','>='],
						choices: [
							{label: '', value: ''},
							{label: '+', value: '+'},
							{label: '-', value: '-'},
							{label: '/', value: '/'},
							{label: '*', value: '*'}
						]
					},
					 {
						type: 'operation',
						id: 'operation_mmm',
						inputType: 'operation',
						label: 'Operation1',
						operators: ['=','!=','<','<=','>','>='],
						choices: [
							{label: '', value: ''},
							{label: '+', value: '+'},
							{label: '-', value: '-'},
							{label: '/', value: '/'},
							{label: '*', value: '*'}
						],
						parameterName:['min_side','median_side','max_side']
					},
					{
							type: 'text',
							id: 'formula',
							label: 'formula (ex.:($width+$height+$depth)/3*($max_side+$min_side+$median_side)/3 < 30)',
							operators: [],
							default: '($width+$height)/2 + ($max_side + $min_side)/2'
							},
					]"
				:labels='{
					"matchType": "Match Type",
					"matchTypes": [
					{"id": "and", "label": "AND"},
					{"id": "or", "label": "OR"}
					],
					"addRule": "Add Rule",
					"removeRule": "&times;",
					"addGroup": "Add Group",
					"removeGroup": "&times;",
					"textInputPlaceholder": "value",
					ruleCaption:"rule",
					groupCaption:"group",
				}'>
			</vue-query-builder>
			
			
			<input type="hidden" name="conditions_edit" v-model="conditions_edit"/>
		</div>
	</div>
	<input type="hidden" name="condition_id"  id="condition_id" value="<?php echo $this->condition->condition_id?>" />
	<input type="hidden" name="task" value="" />


	<div class="rows pt-4 pb-4">
		<?php print JText::_('COM_SMARTSHOP_ADDITIONAL_ADMIN_RULE_APPLY'); ?>
		<input type="hidden" name="rule_apply" value="0"/>
		<input type="checkbox" name="rule_apply" value="1" <?php if($this->condition->rule_apply == 1) print 'checked'; ?>/>
	</div>
	</form>
	<div class="alert alert-warning" role="alert">
		<div class="rows">	
			<b><?php print JText::_('COM_SMARTSHOP_RULE').' '.JText::_('COM_SMARTSHOP_PERIMETER'); ?></b>
			<p>$max_side+$median_side+$min_side</p>
		</div>
		<div class="rows">
			<b><?php print JText::_('COM_SMARTSHOP_RULE').' '.JText::_('COM_SMARTSHOP_AREA'); ?></b>
			<p>$max_side*$median_side</p>
		</div>
		<div class="rows">
			<b><?php print JText::_('COM_SMARTSHOP_RULE').' '.JText::_('COM_SMARTSHOP_VOLUME'); ?></b>
			<p>$max_side*$median_side*$min_side</p>
		</div>
		<div class="rows">
			<b><?php print JText::_('COM_SMARTSHOP_RULE').' '.JText::_('COM_SMARTSHOP_FORMULA'); ?></b>
			<p><?php print JText::_('COM_SMARTSHOP_FORMULA_INFO'); ?>: $price, $weight, $width, $height, $depth,  $max_side, $median_side, $min_side. <br/>Ex.:$price+$weight+($width+$height+$depth)/3*($max_side+$min_side+$median_side)/3 < 30. </p>
		</div>
	</div>

</div>

<script src="<?php print $jshopConfig->live_admin_path; ?>js/src/shipping/vue.js"></script>
<script src="<?php print $jshopConfig->live_admin_path; ?>js/src/shipping/VueQueryBuilder.umd.min.js"></script>
<script src="<?php print $jshopConfig->live_admin_path; ?>js/src/shipping/vindex.js"></script>