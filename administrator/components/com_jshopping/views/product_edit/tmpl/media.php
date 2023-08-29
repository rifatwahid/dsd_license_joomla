<?php
/**
* @version      4.9.0 13.08.2013
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/

use Joomla\CMS\Layout\LayoutHelper;

defined('_JEXEC') or die('Restricted access');

JHtml::_('script', 'system/modal.js', [], true);
JHtml::_('stylesheet', 'system/modal.css', [], true);

$config = $this->jshopConfig;
$productMedia = $this->lists['media'];
$productMediaTableStyle = ($this->isPageWithAdditionalValues && empty($this->product->is_use_additional_media) && !$this->isBatchEdit) ? 'display: none;' : '';
?>

<div id="product_media_tab" class="tab-pane">

    <div class="jshops_edit product_media_tab">
        <?php if ($this->isPageWithAdditionalValues && !$this->isBatchEdit) : ?>
			<div class="form-group row align-items-center">
				<label for="is_use_additional_media" class="col-sm-3 col-md-2 col-xl-2 col-12 col-form-label">
                    <?php echo JText::_('COM_SMARTSHOP_USE_ADDITIONAL_MEDIA'); ?>
				</label>
				<div class="col-sm-9 col-md-10 col-xl-10 col-12">
                    <input type="hidden" name="is_use_additional_media" value="0" checked>
                    <input type="checkbox" name="is_use_additional_media" class="form-check-input" id="is_use_additional_media" value="1" <?php if ($this->product->is_use_additional_media) { echo 'checked'; } ?> onclick="shopHelper.showHideByChecked(this, '#product_media_tab .media');">
                </div>
            </div>
        <?php endif; ?>
    </div>

    <div class="media" style="<?php echo $productMediaTableStyle; ?>">
        <div class="media-files">
                <div class="row">
                    
                    <?php if (!empty($productMedia)) : 
                        foreach($productMedia as $media) :
                            $isVideo = ($media->media_abstract_type == 'video');
                        ?>
                            <div class="col-md-2 col-sm-4 mb-5" id="foto_product_<?php echo $media->id; ?>">
                            
                                    <div style="vertical-align: top; text-align: center;">
                                        <input class="middle mb-3" type="text" name="old_image_descr[<?php echo $media->id; ?>]" value="<?php echo htmlspecialchars($media->media_title);?>" size="22"/>

                                        <div class="pb-5">
                                        <a target="_blank" href="<?php echo $media->preparedLinkToMedia; ?>" <?php echo (!$isVideo) ? 'rel="{handler: \'image\'}"' : ''; ?>>
                                                <img src="<?php echo $media->preparedLinkToPreviewMedia ?>" class="img-fluid cursor--pointer"/>
                                            </a>
                                        </div>

                                        <?php echo JText::_('COM_SMARTSHOP_ORDERING'); ?>: <input type="text" class="small mb-3" name="old_image_ordering[<?php echo $media->id; ?>]" value="<?php echo $media->ordering; ?>"/> <br>
                                        <input type="radio" name="set_main_image" id="set_main_image_<?php echo $media->id; ?>" value="<?php echo $media->id; ?>" <?php echo !empty($media->is_main) ? 'checked="checked"': ''; ?>/> <label style="min-width: 50px;float:none;" for="set_main_image_<?php echo $media->id; ?>"><?php echo JText::_('COM_SMARTSHOP_SET_MAIN_IMAGE');?></label>

                                        <div class="link_delete_foto mt-2">
                                            <a class="btn btn-primary btn-mini" href="#" onclick="if(confirm('<?php echo JText::_('COM_SMARTSHOP_DELETE'); ?>')) {shopImage.delete('<?php echo $media->id?>', 'product'); return false;}">
                                                <i class="fas fa-trash-alt"></i> <?php echo JText::_('COM_SMARTSHOP_DELETE'); ?>
                                            </a>
                                        </div>
                                    </div>
                            </div>
                    <?php endforeach; endif; ?>
                    
                </div>
        </div>

        <div style="height:10px;"></div>
        <div class="col width-45" style="float:left">
            <fieldset class="adminform">
            <legend><?php echo JText::_('COM_SMARTSHOP_UPLOAD_IMAGE')?></legend>
            <div style="height:4px;"></div>

            <?php for($i = 0; $i < $jshopConfig->product_image_upload_count; $i++) : ?>
                <div>
                    <div class="media-block">
                        <div class="media-block__title">
                            <?php echo JText::_('COM_SMARTSHOP_TITLE')?>: 
                        </div>
                        <input type="text" class="media-block__input form-control" name="media[<?php echo $i; ?>][title]" size="35" title="<?php echo JText::_('COM_SMARTSHOP_TITLE'); ?>" />
                    </div>
                    
                    <div class="media-block">
                        <div class="media-block__title">
                            <?php echo JText::_('COM_SMARTSHOP_MEDIA_LINK') ?> :
                        </div>
                        <input type="url" class="media-block__input form-control" name="media[<?php echo $i; ?>][link]" />
                    </div>

                    <div class="media-block">
                        <div class="media-block__title">
                            <?php echo JText::_('COM_SMARTSHOP_PREVIEW') ?> :
                        </div>

                        <div>
                            <?php echo LayoutHelper::render('fields.media', [
                                'name' => 'media[' . $i . '][preview]',
                                'id' => 'product_preview_' . $i,
                                'folder' => 'img_shop_products',
                                'type' => 'smartshopimgsvideo'
                            ]); ?>
                        </div>
                    </div>
                    
                    <div class="media-block">
                        <div class="media-block__title">
                            <?php echo JText::_('COM_SMARTSHOP_IMAGE_VIDEO') ?> :
                        </div>
                        
                        <div>
                            <?php echo LayoutHelper::render('fields.media', [
                                'name' => 'media[' . $i . '][file]',
                                'id' => 'product_upload_' . $i,
                                'folder' => 'img_shop_products',
                                'type' => 'smartshopimgsvideo'
                            ]); ?>
                        </div>	
                    </div>
                    
                    <hr class="media-hr" />
                    
                </div>
                <div style="height:4px;"></div>		
            <?php endfor; ?>        
        
            </fieldset>
        </div>
        
        <div class="clr"></div>
        <?php $pkey='plugin_template_images'; if ($this->$pkey){ print $this->$pkey;}?>
        <br/>
        <div class="helpbox">
            <div class="head"><i class="fas fa-info-circle"></i> <?php echo JText::_('COM_SMARTSHOP_ABOUT_UPLOAD_FILES');?></div>
            <div class="text">
                <?php print JText::sprintf('COM_SMARTSHOP_SIZE_FILES_INFO', ini_get("upload_max_filesize"), ini_get("post_max_size"));?>
            </div>
        </div>
    </div>
</div>