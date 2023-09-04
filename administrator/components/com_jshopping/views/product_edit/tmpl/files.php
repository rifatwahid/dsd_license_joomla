<?php
/**
* @version      4.8.0 13.08.2013
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Layout\LayoutHelper;

$productFilesTableStyle = ($this->isPageWithAdditionalValues && empty($this->product->is_use_additional_files) && !$this->isBatchEdit) ? 'display: none;' : '';
?>

<div id="product_files" class="tab-pane"> 
	<div class="col100">
		<div class="jshops_edit files_edit">
			<?php if ($this->isPageWithAdditionalValues && !$this->isBatchEdit) : ?>
				<div class="form-group row align-items-center">
					<label for="is_use_additional_files" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
						<?php echo JText::_('COM_SMARTSHOP_USE_ADDITIONAL_FILES'); ?>
					</label>
					<div class="col-sm-9 col-md-10 col-xl-10 col-12">
						<input type="hidden" name="is_use_additional_files" value="0" checked>
						<input type="checkbox" class="form-check-input" id="is_use_additional_files" name="is_use_additional_files" value="1" <?php if ($this->product->is_use_additional_files) { echo 'checked'; } ?> onclick="shopHelper.showHideByChecked(this, '#product_files .dependProductFiles');">
					</div>
				</div>
			<?php endif; ?>
		</div>

		 <div class="jshops_edit dependProductFiles" style="<?php echo $productFilesTableStyle; ?>">
			<?php
			foreach ($lists['files'] as $file) :
				JFilterOutput::objectHTMLSafe($file, ENT_QUOTES);
			?>
				<div class="jshops_edit rows_file_prod_<?php echo $file->id; ?>">
					<?php if($jshopConfig->admin_show_product_demo_files){ ?>
						<div class="form-group row align-items-center">
							<label class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label col-form-label-sm">
								<?php echo JText::_('COM_SMARTSHOP_DEMO_FILE'); ?>
							</label>
							<div id='product_demo_<?php echo $file->id; ?>' class="col-sm-9 col-md-10 col-xl-10 col-12">
							<?php if ($file->demo){?>
								<a target="_blank" href="<?php echo '/' . $file->demo?>"><?php echo $file->demo; ?></a>
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<a href="#" onclick="if (confirm('<?php echo JText::_('COM_SMARTSHOP_DELETE');?>')) shopProductCommon.deleteFile('<?php echo $file->id?>','demo');return false;"><i class="fas fa-trash-alt"></i> <?php echo JText::_('COM_SMARTSHOP_DELETE'); ?></a>
							<?php } ?>
							</div>
						</div>
						<div class="form-group row align-items-center">
							<?php
								foreach($this->languages as $lang) :

									$loopLanguage = $lang->language;
									$description = 'demo_descr_' . $loopLanguage;

								?>
								<label for="<?php echo $description; ?><?php echo $file->id; ?>" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label col-form-label-sm">
									<?php echo JText::_('COM_SMARTSHOP_DESCRIPTION_DEMO_FILE') . ' ' . $lang->lang; ?>
								</label>
								<div class="col-sm-9 col-md-10 col-xl-10 col-12">
									<input type="text" class="form-control" size="100" name="product_demo_descr[<?php echo $loopLanguage; ?>][<?php echo $file->id; ?>]" id="<?php echo $description.'_'.$file->id; ?>" value="<?php echo $file->$description; ?>"/>
								</div>
								<div class="clr"></div>
							<?php endforeach; ?>
						</div>
					<?php } ?>
					<?php if($jshopConfig->admin_show_product_sale_files){ ?>
						<div class="form-group row align-items-center">
							<label class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label col-form-label-sm">
								<?php echo JText::_('COM_SMARTSHOP_FILE_SALE'); ?>
							</label>
							<div class="col-sm-9 col-md-10 col-xl-10 col-12" id='product_file_<?php echo $file->id; ?>'>
							<?php if ($file->file){?>
								<a target="_blank" href="index.php?option=com_jshopping&controller=products&task=getfilesale&id=<?php echo $file->id; ?>">
									<?php echo $file->file; ?>
								</a>
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<a href="#" onclick="if (confirm('<?php echo JText::_('COM_SMARTSHOP_DELETE'); ?>')) shopProductCommon.deleteFile('<?php echo $file->id; ?>','file');return false;"><i class="fas fa-trash-alt"></i> <?php echo JText::_('COM_SMARTSHOP_DELETE'); ?></a>
							<?php } ?>
							</div>
						</div>
						<div class="form-group row align-items-center">
							<?php 
								foreach($this->languages as $lang) :
									$loopLanguage = $lang->language;
									$description = 'file_descr_' . $loopLanguage;
									
								?>	
								<label for="<?php echo $description; ?><?php echo $file->id; ?>" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label col-form-label-sm">
									<?php echo JText::_('COM_SMARTSHOP_DESCRIPTION_FILE_SALE'). ' ' . $lang->lang; ?>
								</label>
								<div class="col-sm-9 col-md-10 col-xl-10 col-12">
									<input type="text" class="form-control" size="100" id="<?php echo $description; ?>_<?php echo $file->id; ?>" name="product_file_descr[<?php echo $loopLanguage; ?>][<?php echo $file->id; ?>]" value="<?php echo $file->$description; ?>" />
								</div>
								<div class="clr"></div>
							<?php endforeach; ?>
						</div>
					<?php } ?>
					<div class="form-group row align-items-center">
						<label for="product_file_sort_<?php echo $file->id; ?>" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label col-form-label-sm">
							<?php echo JText::_('COM_SMARTSHOP_ORDERING'); ?>
						</label>
						<div class="col-sm-9 col-md-10 col-xl-10 col-12">
							<input type="text" size="25" class="form-control" id="product_file_sort_<?php echo $file->id; ?>" name="product_file_sort[<?php echo $file->id; ?>]" value="<?php echo $file->ordering; ?>" />
						</div>
					</div>
					<div class="form-group row align-items-center">
						<div class="col-sm-12 col-md-12 col-xl-12 col-12"><hr/></div>
					</div>
				</div>
			<?php endforeach; ?>                
			
			<?php 
			$sort = count($lists['files']);

			for ($i=0; $i < $jshopConfig->product_file_upload_count; $i++) : ?>
                <?php if($jshopConfig->admin_show_product_demo_files){ ?>
                    <div class="form-group row align-items-center">
                        <label for='product_demo_file_btn_<?php echo $i;?>' class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label col-form-label-sm">
                            <?php echo JText::_('COM_SMARTSHOP_DEMO_FILE')?>
                        </label>
                        <div class="col-sm-9 col-md-10 col-xl-10 col-12">
                            <?php 
								if ($jshopConfig->product_file_upload_via_ftp != 1) {
									echo LayoutHelper::render('fields.media', [
										'name' => 'demo_files[' . $i . '][source]',
										'id' => 'product_demo_file_' . $i,
										'folder' => 'demo_products',
										'type' => 'smartshopallfiles'
									]);
								}
							?>
                            
                            <?php if ($jshopConfig->product_file_upload_via_ftp) : ?>
                            	<div style="padding-top:3px;"><input size="34" type="text" name="product_demo_file_name_<?php echo $i;?>" title="<?php echo JText::_('COM_SMARTSHOP_UPLOAD_FILE_VIA_FTP')?>" /></div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="form-group row align-items-center">
                    <?php
                        foreach($this->languages as $lang) :

                            $loopLanguage = $lang->language;
                            $description = 'demo_files[' . $i . '][descr][' . $loopLanguage . ']';
                        ?>
                            <label for="<?php echo $description; ?>" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label col-form-label-sm">
                                <?php echo JText::_('COM_SMARTSHOP_DESCRIPTION_DEMO_FILE'). ' ' . $lang->lang; ?>
                            </label>
                            <div class="col-sm-9 col-md-10 col-xl-10 col-12">
                                <input type="text" class="form-control" size="100" name="<?php echo $description; ?>" id="<?php echo $description; ?>" value=""/>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="form-group row align-items-center"><div class="col-sm-12 col-md-12 col-xl-12 col-12">&nbsp;</div></div>
				<?php } ?>

             <?php if($jshopConfig->admin_show_product_sale_files){ ?>
				<div class="form-group row align-items-center">
					<label for="product_file_btn_<?php echo $i;?>" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label col-form-label-sm">
						<?php echo JText::_('COM_SMARTSHOP_FILE_SALE')?>
					</label>
					<div class="col-sm-9 col-md-10 col-xl-10 col-12">
						<?php if ($jshopConfig->product_file_upload_via_ftp != 1) {
							echo LayoutHelper::render('fields.media', [
								'name' => 'files[' . $i . '][source]',
								'id' => 'product_file_' . $i,
								'folder' => 'files_products',
								'type' => 'smartshopallfiles'
							]);
						}?>

						<?php if ($jshopConfig->product_file_upload_via_ftp) : ?>
							<div style="padding-top:3px;"><input size="34" type="text" name="product_file_name_<?php echo $i;?>" title="<?php echo JText::_('COM_SMARTSHOP_UPLOAD_FILE_VIA_FTP')?>" /></div>
						<?php endif; ?>
					</div>
				</div>	
				<div class="form-group row align-items-center">
					<?php 
						foreach($this->languages as $lang) :
							$loopLanguage = $lang->language;
							$description = 'files[' . $i . '][descr][' . $loopLanguage . ']';						
					?>
						<label for="<?php echo $description; ?>" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label col-form-label-sm">
							<?php echo JText::_('COM_SMARTSHOP_DESCRIPTION_FILE_SALE'). ' ' . $lang->lang;?>
						</label>
						<div class="col-sm-9 col-md-10 col-xl-10 col-12">
							<input type="text"  class="form-control" size="100" name="<?php echo $description; ?>" id="<?php echo $description; ?>" value=""/>
						</div>					
					<?php endforeach; ?>
				</div>	
            <?php } ?>
				
				<?php $sortIdent = 'files[' . $i . '][sort]'; ?>
				<div class="form-group row align-items-center">
					<label for="<?php echo $sortIdent;?>" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label col-form-label-sm">
						<?php echo JText::_('COM_SMARTSHOP_ORDERING');?>
					</label>
					<div class="col-sm-9 col-md-10 col-xl-10 col-12">
						<input type="text" class="form-control" size="25" class="form-control" name="<?php echo $sortIdent;?>" id="<?php echo $sortIdent;?>" value="<?php echo $sort + $i?>" />
					</div>
				</div>
				<div class="form-group row align-items-center">
					<div class="col-sm-12 col-md-12 col-xl-12 col-12"><hr/></div>
				</div>	
			<?php endfor; ?>

			<?php $pkey='plugin_template_files'; if ($this->$pkey){ echo $this->$pkey;}?>
		</div>
	</div>
	
    <div class="clr"></div>
    <br/>    
	<br/>
	
    <div class="helpbox">
        <div class="head">
			<i class="fas fa-info-circle"></i> <?php echo JText::_('COM_SMARTSHOP_ABOUT_UPLOAD_FILES'); ?>
		</div>

        <div class="text">
            <?php echo JText::sprintf('COM_SMARTSHOP_SIZE_FILES_INFO', ini_get('upload_max_filesize'), ini_get('post_max_size')); ?>
        </div>
    </div>
</div>