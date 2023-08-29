<?php 

defined('_JEXEC') or die('Restricted access');

JHtmlBootstrap::tooltip();
JHtmlBootstrap::modal('a.modal');

displaySubmenuConfigs('content',$this->canDo);
?>

<div class="modal fade" id="selectContent" aria-hidden="true" role="dialog">
    <div class="modal-dialog modal-xl">
		<div class="modal-content">
        	<div class="pl-5 ps-5 pt-2 pb-2">
				

				<div class="row text-right text-end">
					<div class="col-6">
						<h2 class="modal-title">
							<?php echo  JText::_('COM_SMARTSHOP_CONTENT');?>
						</h2>
					</div>
					<div class="col-2 offset-3 mt-2">
						<select id="content_language" class="form-select form-select-sm" onChange="shopConfig.reloadLanguage(this.value)">
								<option value=''></option>
								<?php foreach ($this->languages as $lang){
									?><option value='<?php echo $lang->language;?>'><?php echo $lang->name;?></option><?php
								}?>
						</select>
					</div>
				</div>
				
					
				
	        </div>

			<input type='hidden' id='content_link_id' value=''>
			<input type='hidden' id='content_page' value='0'>

	        <div class="modal-body" id='result'>
	        </div>

	        <div class="modal-footer">			
	          <button type="button" class="btn btn-default btn-primary" data-dismiss="modal" data-bs-dismiss="modal"><?php echo  JText::_('COM_SMARTSHOP_CLOSE');?></button>
	        </div>
	    </div>
	 </div>
</div>

<form action="index.php?option=com_jshopping&controller=config" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
	<input type="hidden" name="task" value="contentsave">
	<input type="hidden" name="id" value="">

	<h1>
		<?php echo  JText::_('COM_SMARTSHOP_PRIVACY_POLICY'); ?>
	</h1>
	<div class="striped-block jshops_edit listconent_tmpl_config">
		<?php foreach($this->languages as $lang) : ?>
				<div class='form-group row align-items-center <?php print $this->pbclass; ?>'>
					<input type='hidden' >				
					<label for="privacy_statement_<?php print $lang->lang;?>_type" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label "><?php echo  JText::_('COM_SMARTSHOP_CONTENT_TYPE'); ?> </label>	
					<div class='col-sm-8 col-md-9 col-xl-9 col-12' id='label_privacy_statement_<?php print $lang->lang;?>_type'>
						<select name='privacy_statement_<?php print $lang->lang;?>_type' class="form-select" id='privacy_statement_<?php print $lang->lang;?>_type' onChange="shopConfig.restartContent();">
							<option value="1" <?php if($this->rows['privacy_statement'][$lang->lang]['type'] == 1) print 'selected';?>><?php echo  JText::_('COM_SMARTSHOP_JOOMLA_ARTICLES'); ?></option>
							<option value="2" <?php if($this->rows['privacy_statement'][$lang->lang]['type'] == 2) print 'selected';?>><?php echo  JText::_('COM_SMARTSHOP_PAGEBUILDER_PAGES'); ?></option>
						</select>
					</div>
				</div>		
				<div class='form-group row'>			
					<input type='hidden' name='privacy_statement_<?php print $lang->lang;?>' id='privacy_statement_<?php print $lang->lang;?>' value='<?php print $this->rows['privacy_statement'][$lang->lang]['link'];?>'>
					<label for="btn_privacy_statement_<?php print $lang->lang;?>" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label "><?php echo $lang->name;?></label>
					<div class='col-sm-2 col-md-3 col-xl-1 col-12'><button type="button" class="btn btn-primary" id="btn_privacy_statement_<?php print $lang->lang;?>" data-toggle="modal" data-bs-toggle="modal" data-target="#selectContent" data-bs-target="#selectContent" onClick="document.getElementById('content_link_id').value='privacy_statement_<?php print $lang->lang;?>'; shopConfig.restartContent();"><?php echo  JText::_('COM_SMARTSHOP_CONTENT_SELECT'); ?></button></div>
					<div class='col-sm-2 col-md-3 col-xl-2 col-12' id='label_privacy_statement_<?php print $lang->lang ?? '';?>'><?php print $this->rows['privacy_statement'][$lang->lang]['title'] ?? '';?></div>
					<?php $class = ''; if(!isset($this->rows['privacy_statement'][$lang->lang]['title']) || !$this->rows['privacy_statement'][$lang->lang]['title']){ $class = "hidden"; }?>
					<div class='col-sm-4 col-md-3 col-xl-3 col-12' id="remove_privacy_statement_<?php print $lang->lang;?>"><i onclick="shopConfig.removeContent('privacy_statement', '<?php print $lang->lang;?>');" class="icon-remove <?php print $class; ?>"></i></div>
				</div>
			
		<?php endforeach; ?>
	</div>
	
	<h1>
		<?php echo  JText::_('COM_SMARTSHOP_TERMS_OF_SERVICE'); ?>
	</h1>
	<div class="striped-block jshops_edit listcontent_config_lang">
		<?php foreach($this->languages as $lang) : ?>
			<div class='form-group row align-items-center <?php print $this->pbclass; ?>'>
				<input type='hidden' >
				<label for="agb_<?php print $lang->lang;?>_type" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label "><?php echo  JText::_('COM_SMARTSHOP_CONTENT_TYPE'); ?> </label>	
				<div class='col-sm-8 col-md-9 col-xl-9 col-12' id='label_agb_<?php print $lang->lang;?>_type'>
					<select name='agb_<?php print $lang->lang;?>_type' class="form-select" id='agb_<?php print $lang->lang;?>_type' onChange="shopConfig.restartContent();">
						<option value="1" <?php if($this->rows['agb'][$lang->lang]['type'] == 1) print 'selected';?>><?php echo  JText::_('COM_SMARTSHOP_JOOMLA_ARTICLES'); ?></option>
						<option value="2" <?php if($this->rows['agb'][$lang->lang]['type'] == 2) print 'selected';?>><?php echo  JText::_('COM_SMARTSHOP_PAGEBUILDER_PAGES'); ?></option>
					</select>
				</div>
			</div>		
			<div class='form-group row'>
				<input type='hidden' name='agb_<?php print $lang->lang;?>' id='agb_<?php print $lang->lang;?>' value='<?php print $this->rows['agb'][$lang->lang]['link'];?>'>
				<label for="btn_agb_<?php print $lang->lang;?>" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label "><?php echo $lang->name;?></label>
				<div class='col-sm-2 col-md-3 col-xl-1 col-12'><button type="button" class="btn btn-primary" data-toggle="modal" data-bs-toggle="modal" id="btn_agb_<?php print $lang->lang;?>"  data-target="#selectContent" data-bs-target="#selectContent" onClick="document.getElementById('content_link_id').value='agb_<?php print $lang->lang;?>';shopConfig.restartContent();"><?php echo  JText::_('COM_SMARTSHOP_CONTENT_SELECT'); ?></button></div>
				<div class='col-sm-2 col-md-3 col-xl-2 col-12' id='label_agb_<?php print $lang->lang ?? '';?>'><?php print $this->rows['agb'][$lang->lang]['title'] ?? '';?></div>
				<?php $class = ''; if(!isset($this->rows['agb'][$lang->lang]['title']) || !$this->rows['agb'][$lang->lang]['title']){ $class = "hidden"; }?>
				<div class='col-sm-4 col-md-3 col-xl-3 col-12' id="remove_agb_<?php print $lang->lang ?? '';?>"><i onclick="shopConfig.removeContent('agb', '<?php print $lang->lang ?? '';?>');" class="icon-remove <?php print $class ?? ''; ?>"></i></div>
			</div>			
		<?php endforeach; ?>
	</div> 
	
	<h1>
		<?php echo  JText::_('COM_SMARTSHOP_RETURN_POLICY'); ?>
	</h1>
	<div class="striped-block jshops_edit list_content_return_policy">
		<?php foreach($this->languages as $lang) : ?>	
			<div class='form-group row align-items-center <?php print $this->pbclass; ?>'>
				<input type='hidden' >
				<label for="return_policy_<?php print $lang->lang;?>_type" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label "><?php echo  JText::_('COM_SMARTSHOP_CONTENT_TYPE'); ?> </label>	
				<div class='col-sm-8 col-md-9 col-xl-9 col-12' id='label_return_policy_<?php print $lang->lang;?>_type'>
					<select name='return_policy_<?php print $lang->lang;?>_type' class="form-select" id='return_policy_<?php print $lang->lang;?>_type' onChange="shopConfig.restartContent();">
						<option value="1" <?php if($this->rows['return_policy'][$lang->lang]['type'] == 1) print 'selected';?>><?php echo  JText::_('COM_SMARTSHOP_JOOMLA_ARTICLES'); ?></option>
						<option value="2" <?php if($this->rows['return_policy'][$lang->lang]['type'] == 2) print 'selected';?>><?php echo  JText::_('COM_SMARTSHOP_PAGEBUILDER_PAGES'); ?></option>
					</select>
				</div>
			</div>	
			<div class='form-group row'>
				<input type='hidden' name='return_policy_<?php print $lang->lang;?>' id='return_policy_<?php print $lang->lang;?>' value='<?php print $this->rows['return_policy'][$lang->lang]['link'];?>'>
				<label for="label_return_policy_<?php print $lang->lang;?>_btn" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label "><?php echo $lang->name;?></label>
				<div class='col-sm-2 col-md-3 col-xl-1 col-12'><button type="button" class="btn btn-primary" id="label_return_policy_<?php print $lang->lang;?>_btn" data-toggle="modal" data-bs-toggle="modal" data-target="#selectContent" data-bs-target="#selectContent" onClick="document.getElementById('content_link_id').value='return_policy_<?php print $lang->lang;?>';shopConfig.restartContent();"><?php echo  JText::_('COM_SMARTSHOP_CONTENT_SELECT'); ?></button></div>
				<div class='col-sm-2 col-md-3 col-xl-2 col-12' id='label_return_policy_<?php print $lang->lang ?? '';?>'><?php print $this->rows['return_policy'][$lang->lang]['title'] ?? '';?></div>
				<?php $class = ''; if(!isset($this->rows['return_policy'][$lang->lang]['title']) || !$this->rows['return_policy'][$lang->lang]['title']){ $class = "hidden"; }?>
				<div class='col-sm-4 col-md-3 col-xl-3 col-12' id="remove_return_policy_<?php print $lang->lang;?>"><i onclick="shopConfig.removeContent('return_policy', '<?php print $lang->lang;?>');" class="icon-remove <?php print $class; ?>"></i></div>
			</div>
		<?php endforeach; ?>
	</div>
	
	<h1>
		<?php echo  JText::_('COM_SMARTSHOP_ORDER_SUCCESS_PAGE'); ?>
	</h1>
	<div class="striped-block jshops_edit listconten_success_page">
		<?php foreach($this->languages as $lang) : ?>	
			<div class='form-group row align-items-center <?php print $this->pbclass; ?>'>
				<input type='hidden' >
				<label for="order_success_page_<?php print $lang->lang;?>_type" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label "><?php echo  JText::_('COM_SMARTSHOP_CONTENT_TYPE'); ?> </label>	
				<div class='col-sm-8 col-md-9 col-xl-9 col-12' id='label_order_success_page_<?php print $lang->lang;?>_type'>
					<select name='order_success_page_<?php print $lang->lang;?>_type' class="form-select" id='order_success_page_<?php print $lang->lang;?>_type' onChange="shopConfig.restartContent();">
						<option value="1" <?php if($this->rows['order_success_page'][$lang->lang]['type'] == 1) print 'selected';?>><?php echo  JText::_('COM_SMARTSHOP_JOOMLA_ARTICLES'); ?></option>
						<option value="2" <?php if($this->rows['order_success_page'][$lang->lang]['type'] == 2) print 'selected';?>><?php echo  JText::_('COM_SMARTSHOP_PAGEBUILDER_PAGES'); ?></option>
					</select>
				</div>
			</div>
			<div class='form-group row'>
				<input type='hidden' name='order_success_page_<?php print $lang->lang;?>' id='order_success_page_<?php print $lang->lang;?>' value='<?php print $this->rows['order_success_page'][$lang->lang]['link'];?>'>
				<label for="label_order_success_page_<?php print $lang->lang;?>_btn" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label "><?php echo $lang->name;?></label>
				<div class='col-sm-2 col-md-3 col-xl-1 col-12'><button type="button" class="btn btn-primary" id="label_order_success_page_<?php print $lang->lang;?>_btn" data-toggle="modal" data-bs-toggle="modal" data-target="#selectContent" data-bs-target="#selectContent" onClick="document.getElementById('content_link_id').value='order_success_page_<?php print $lang->lang;?>';shopConfig.restartContent();"><?php echo  JText::_('COM_SMARTSHOP_CONTENT_SELECT'); ?></button></div>
				<div class='col-sm-2 col-md-3 col-xl-2 col-12' id='label_order_success_page_<?php print $lang->lang ?? '';?>'><?php print $this->rows['order_success_page'][$lang->lang]['title'] ?? '';?></div>
				<?php $class = ''; if(!isset($this->rows['order_success_page'][$lang->lang]['title']) || !$this->rows['order_success_page'][$lang->lang]['title']){ $class = "hidden"; }?>
				<div class='col-sm-4 col-md-3 col-xl-3 col-12' id="remove_order_success_page_<?php print $lang->lang;?>"><i onclick="shopConfig.removeContent('order_success_page', '<?php print $lang->lang;?>');" class="icon-remove <?php print $class; ?>"></i></div>
			</div>
		<?php endforeach; ?>
	</div>
	
	<h1>
		<?php echo JText::_('COM_SMARTSHOP_SHIPPING_INFORMATION'); ?>
	</h1>
	<div class="striped-block jshops_edit listcontent_shipping_info">
		<?php foreach($this->languages as $lang) : ?>	
			<div class='form-group row align-items-center <?php print $this->pbclass; ?>'>
				<input type='hidden' >
				<label for="shipping_<?php print $lang->lang;?>_type" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label "><?php echo  JText::_('COM_SMARTSHOP_CONTENT_TYPE'); ?> </label>	
				<div class='col-sm-8 col-md-9 col-xl-9 col-12' id='label_shipping_<?php print $lang->lang;?>_type'>
					<select name='shipping_<?php print $lang->lang;?>_type' class="form-select" id='shipping_<?php print $lang->lang;?>_type' onChange="shopConfig.restartContent();">
						<option value="1" <?php if($this->rows['shipping'][$lang->lang]['type'] == 1) print 'selected';?>><?php echo  JText::_('COM_SMARTSHOP_JOOMLA_ARTICLES'); ?></option>
						<option value="2" <?php if($this->rows['shipping'][$lang->lang]['type'] == 2) print 'selected';?>><?php echo  JText::_('COM_SMARTSHOP_PAGEBUILDER_PAGES'); ?></option>
					</select>
				</div>
			</div>	
			<div class='form-group row'>
				<input type='hidden' name='shipping_<?php print $lang->lang;?>' id='shipping_<?php print $lang->lang;?>' value='<?php print $this->rows['shipping'][$lang->lang]['link'];?>'>
				<label for="label_shipping_<?php print $lang->lang;?>_btn" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label "><?php echo $lang->name;?></label>
				<div class='col-sm-2 col-md-3 col-xl-1 col-12'><button type="button" class="btn btn-primary" id="label_shipping_<?php print $lang->lang;?>_btn" data-toggle="modal" data-bs-toggle="modal" data-target="#selectContent" data-bs-target="#selectContent" onClick="document.getElementById('content_link_id').value='shipping_<?php print $lang->lang;?>';shopConfig.restartContent();"><?php echo  JText::_('COM_SMARTSHOP_CONTENT_SELECT'); ?></button></div>
				<div class='col-sm-2 col-md-3 col-xl-2 col-12' id='label_shipping_<?php print $lang->lang ?? '';?>'><?php print $this->rows['shipping'][$lang->lang]['title'] ?? '';?></div>
				<?php $class = ''; if(!isset($this->rows['shipping'][$lang->lang]['title']) || !$this->rows['shipping'][$lang->lang]['title']){ $class = "hidden"; }?>
				<div class='col-sm-4 col-md-3 col-xl-3 col-12' id="remove_shipping_<?php print $lang->lang;?>"><i onclick="shopConfig.removeContent('shipping', '<?php print $lang->lang;?>');" class="icon-remove <?php print $class; ?>"></i></div>
			</div>
		<?php endforeach; ?>
	</div>

	<h1>
		<?php echo JText::_('COM_SMARTSHOP_RETURN_FINISH_PAGE'); ?>
	</h1>
	<div class="striped-block jshops_edit listcontent_finish">
		<?php foreach($this->languages as $lang) : ?>	
			<div class='form-group row align-items-center <?php print $this->pbclass; ?>'>
				<input type='hidden' >
				<label for="return_finish_page_<?php print $lang->lang;?>_type" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label "><?php echo  JText::_('COM_SMARTSHOP_CONTENT_TYPE'); ?> </label>	
				<div class='col-sm-8 col-md-9 col-xl-9 col-12' id='label_return_finish_page_<?php print $lang->lang;?>_type'>
					<select name='return_finish_page_<?php print $lang->lang;?>_type' class="form-select" id='return_finish_page_<?php print $lang->lang;?>_type' onChange="shopConfig.restartContent();">
						<option value="1" <?php if($this->rows['return_finish_page'][$lang->lang]['type'] == 1) print 'selected';?>><?php echo  JText::_('COM_SMARTSHOP_JOOMLA_ARTICLES'); ?></option>
						<option value="2" <?php if($this->rows['return_finish_page'][$lang->lang]['type'] == 2) print 'selected';?>><?php echo  JText::_('COM_SMARTSHOP_PAGEBUILDER_PAGES'); ?></option>
					</select>
				</div>
			</div>	
			<div class='form-group row'>
				<input type='hidden' name='return_finish_page_<?php print $lang->lang;?>' id='return_finish_page_<?php print $lang->lang;?>' value='<?php print $this->rows['return_finish_page'][$lang->lang]['link'];?>'>
				<label for="label_return_<?php print $lang->lang;?>_btn" class="col-sm-4 col-md-3 col-xl-3 col-12 col-form-label "><?php echo $lang->name;?></label>
				<div class='col-sm-2 col-md-3 col-xl-1 col-12'><button type="button" class="btn btn-primary" id="label_return_finish_page_<?php print $lang->lang;?>_btn" data-toggle="modal" data-bs-toggle="modal" data-target="#selectContent" data-bs-target="#selectContent" onClick="document.getElementById('content_link_id').value='return_finish_page_<?php print $lang->lang;?>';shopConfig.restartContent();"><?php echo  JText::_('COM_SMARTSHOP_CONTENT_SELECT'); ?></button></div>
				<div class='col-sm-2 col-md-3 col-xl-2 col-12' id='label_return_finish_page_<?php print $lang->lang ?? '';?>'><?php print $this->rows['return_finish_page'][$lang->lang]['title'] ?? '';?></div>
				<?php $class = ''; if(!isset($this->rows['return_finish_page'][$lang->lang]['title']) || !$this->rows['return_finish_page'][$lang->lang]['title']){ $class = "hidden"; }?>
				<div class='col-sm-4 col-md-3 col-xl-3 col-12' id="remove_return_finish_page_<?php print $lang->lang;?>"><i onclick="shopConfig.removeContent('return_finish_page', '<?php print $lang->lang;?>');" class="icon-remove <?php print $class; ?>"></i></div>
			</div>
		<?php endforeach; ?>
	</div>
		<div class="clr"></div>
	</form>

<script>
	document.addEventListener('DOMContentLoaded', function () {
		shopConfig.restartContent();
	});
</script>