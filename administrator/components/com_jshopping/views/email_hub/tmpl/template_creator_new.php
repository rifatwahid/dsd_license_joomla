<?php 
defined('_JEXEC') or die('Restricted access');
$jshopConfig = JSFactory::getConfig();
$lists = $this->lists;
displaySubmenuConfigs('email_hub',$this->canDo);
displaySubSubmenuConfigs('template_creator');
?>


<input type='hidden' id='selected_row' value=''>
<input type='hidden' id='selected_block' value=''>


<div class='container'>
	<div class='tools_container'>
	
	
		<div class='tools_block'>		
			<div class='row_line tool_title'>
				<?php echo JText::_('COM_SMARTSHOP_TEMPLATE_CREATOR_TEMPLATE_SIZE');?>: 
			</div>
			<div class='row_line'>
				<div class='tools_element_title'>
					<?php echo JText::_('COM_SMARTSHOP_TEMPLATE_CREATOR_TEMPLATE_WIDTH');?>:
				</div>
				<div class='tools_element_field'>
					<button class='emailhub_minus' onClick="shopEmailHub.widthPlus(-1)">-</button><input type='text' value='800' id='template_width' class="emailhub_width" onKeyUp="shopEmailHub.changeWidth(this)"><button class='emailhub_pluss' onClick="shopEmailHub.widthPlus(1)">+</button>
				</div>	
			</div>
			<div class='row_line'>
				<div class='tools_element_title'>
					<?php echo JText::_('COM_SMARTSHOP_TEMPLATE_CREATOR_TEMPLATE_PADDING');?>: 
				</div>
				<div class='tools_element_field'>
					<button class='emailhub_minus' onClick="shopEmailHub.paddingPlus(-1)">-</button><input type='text' value='10' id='template_padding' class="emailhub_width" onKeyUp="shopEmailHub.changePadding(this)"><button class='emailhub_pluss' onClick="shopEmailHub.paddingPlus(1)">+</button>
				</div>
			</div>
		</div>	
		
		<div class='tools_block'>		
			<div class='row_line tool_title'>
				<?php echo JText::_('COM_SMARTSHOP_TEMPLATE_CREATOR_TEMPLATE_ADD_BLOCKS');?>: 
			</div>
			<div class='row_line'>
				<div class='tools_element_title'>
					<?php echo JText::_('COM_SMARTSHOP_TEMPLATE_CREATOR_TEMPLATE_BLOCKS');?>: 
				</div>
				<div class='tools_element_field'>
					<button class='emailhub_minus' onClick="shopEmailHub.plusRow(-1)">-</button><input type='text' class='emailhub_width' value='3' id='blocks'><button class='emailhub_pluss' onClick="shopEmailHub.plusRow(1)">+</button>			
				</div>	
			</div>
			<div class='row_line'>
				<div class='tools_element_title'>
					<?php echo JText::_('COM_SMARTSHOP_TEMPLATE_CREATOR_TEMPLATE_BLOCKS_HEIGHT');?>: 
				</div>
				<div class='tools_element_field'>
					<input type='text' class='emailhub_width' value='50' id='blocks_height' >			
				</div>
			</div>
			<div class='row_line'>
				<div>
					<button class='add_row' onClick="shopEmailHub.addBlock(document.querySelector('#blocks').value, document.querySelector('#blocks_height').value)"><?php echo JText::_('COM_SMARTSHOP_TEMPLATE_CREATOR_ADD_ROW');?></button>
				</div>
			</div>			
		</div>

		<div class='tools_block'>		
			<div class='row_line tool_title'>
				<?php echo JText::_('COM_SMARTSHOP_TEMPLATE_CREATOR_IMPORT_EXPORT');?>: 
			</div>
			<div class='row_line'>
				<div>
					<button class='add_row' onClick="open_import_window()"><?php echo JText::_('COM_SMARTSHOP_TEMPLATE_CREATOR_IMPORT');?></button>
				</div>
			</div>			
			<div class='row_line'>
				<div>
					<button class='add_row' onClick="open_export_window()"><?php echo JText::_('COM_SMARTSHOP_TEMPLATE_CREATOR_EXPORT');?></button>
				</div>
			</div>			
		</div>	
		
		
	</div>	
	<div class='preview_container'>
		
		<div id='preview' class='preview'>
			<div id='preview_padding' class='preview_padding conteiner'>
			</div>
		</div>
		
	</div>
</div>