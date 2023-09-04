<?php

defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

$row = $this->product;
$lists = $this->lists;
$tax_value = $this->tax_value;
$jshopConfig = JSFactory::getConfig();
$currency = $this->currency ?? '';

JHtmlBootstrap::tooltip();
JHtmlBootstrap::modal('a.modal');

$dispatcher = \JFactory::getApplication();
?>

<div class="jshop_edit product_edit">

    <form action="index.php?option=com_jshopping&controller=products" method="post" class="form-inline" enctype="multipart/form-data" name="adminForm" id="adminForm" class="<?php echo ($this->isPageWithAdditionalValues ? 'additionalValuesForm' : ''); ?>">
		<div id="spinner_loading_block">
			<div id="spinner_loading" class="loading">
				<img src="<?php print $jshopConfig->live_admin_path; ?>images/loading.gif" />
			</div>
		</div>
		<?php if (!isJoomla4()) : ?>
			<ul class="nav nav-tabs">
				<li class="active">
					<a href="#description-page" data-toggle="tab">
						<?php echo JText::_('COM_SMARTSHOP_DESCRIPTION'); ?>
					</a>
				</li>        

				<li>
					<a href="#main-page" data-toggle="tab">
						<?php echo JText::_('COM_SMARTSHOP_INFO_PRODUCT'); ?>
					</a>
				</li>

				<li>
					<a href="#main-price" data-toggle="tab">
						<?php echo JText::_('COM_SMARTSHOP_PRICE'); ?>
					</a>
				</li>

				<?php if (empty($this->product->parent_id)) {
					$dispatcher->triggerEvent('onDisplayProductEditTabsTab', [&$row, &$lists, &$tax_value]);
				} ?>

				<?php if ($jshopConfig->admin_show_attributes && empty($this->product->parent_id)) : ?>
					<li>
						<a href="#attribs-page" data-toggle="tab">
							<?php echo JText::_('COM_SMARTSHOP_ATTRIBUTES'); ?>
						</a>
					</li>
				<?php endif; ?>

				<?php if (!empty($jshopConfig->admin_show_freeattributes)) : ?>
					<li>
						<a href="#product_freeattribute" data-toggle="tab">
							<?php echo JText::_('COM_SMARTSHOP_FREE_ATTRIBUTES'); ?>
						</a>
					</li>
				<?php endif;?>

				<li>
					<a href="#product_media_tab" data-toggle="tab">
						<?php echo JText::_('COM_SMARTSHOP_PRODUCT_MEDIA'); ?>
					</a>
				</li>

				<?php if (!empty($jshopConfig->admin_show_product_related)) : ?>
					<li>
						<a href="#product_related" data-toggle="tab">
							<?php echo JText::_('COM_SMARTSHOP_PRODUCT_RELATED'); ?>
						</a>
					</li>
				<?php endif; ?>

				<?php if (!empty($jshopConfig->admin_show_product_demo_files) || !empty($jshopConfig->admin_show_product_sale_files)) : ?>
					<li>
						<a href="#product_files" data-toggle="tab">
							<?php echo JText::_('COM_SMARTSHOP_FILES'); ?>
						</a>
					</li>
				<?php endif; ?>

				<?php if ($jshopConfig->admin_show_product_extra_field) : ?>
					<li>
						<a href="#product_extra_fields" data-toggle="tab">
							<?php echo JText::_('COM_SMARTSHOP_EXTRA_FIELDS'); ?>
						</a>
					</li>
				<?php endif; ?>

				<li>
					<a href="#tshipping" data-toggle="tab">
						<?php echo JText::_('COM_SMARTSHOP_SHIPPINGS'); ?>
					</a>
				</li>

				<?php if (empty($this->product->parent_id)) {
					$dispatcher->triggerEvent('onDisplayProductEditTabsEndTab', [&$row, &$lists, &$tax_value]);	   
				} ?>

				<li>
					<a href="#customize" data-toggle="tab">
						<?php echo JText::_('COM_SMARTSHOP_CUSTOMIZE'); ?>
					</a>
				</li>

				<li>
					<a href="#usergroup_permissions" data-toggle="tab">
						<?php echo JText::_('COM_SMARTSHOP_USERGROUP_PERMISSIONS'); ?>
					</a>
				</li>
			</ul>
		<?php endif; ?>

        <div id="editdata-document" class="tab-content">
            <?php
				if (isJoomla4()) {
					echo HTMLHelper::_('uitab.startTabSet', 'myTab', ['active' => 'description-page', 'recall' => true, 'breakpoint' => 768]);
					echo HTMLHelper::_('uitab.addTab', 'myTab', 'description-page', Text::_('COM_SMARTSHOP_DESCRIPTION'));
				}

                include __DIR__ . '/description.php';

				if (isJoomla4()) {
					echo HTMLHelper::_('uitab.endTab');
					echo HTMLHelper::_('uitab.addTab', 'myTab', 'main-page', Text::_('COM_SMARTSHOP_INFO_PRODUCT'));
				}

                include __DIR__ . '/info.php';

				if (isJoomla4()) {
					echo HTMLHelper::_('uitab.endTab');
					echo HTMLHelper::_('uitab.addTab', 'myTab', 'main-price', Text::_('COM_SMARTSHOP_PRICE'));
				}

                include __DIR__ . '/price.php';

				if (isJoomla4()) {
					echo HTMLHelper::_('uitab.endTab');
				}
                
                if (empty($this->product->parent_id)) {
                    $pane = null;
                    $dispatcher->triggerEvent('onDisplayProductEditTabs', [&$pane, &$row, &$lists, &$tax_value, &$currency]);
                }

                if ($jshopConfig->admin_show_attributes && empty($this->product->parent_id)) {
					if (isJoomla4()) {
						echo HTMLHelper::_('uitab.addTab', 'myTab', 'attribs-page', Text::_('COM_SMARTSHOP_ATTRIBUTES'));
					}

                    include __DIR__ . '/attribute.php';

					if (isJoomla4()) {
						echo HTMLHelper::_('uitab.endTab');
					}
                }

                if (!empty($jshopConfig->admin_show_freeattributes)) {
					if (isJoomla4()) {
						echo HTMLHelper::_('uitab.addTab', 'myTab', 'product_freeattribute', Text::_('COM_SMARTSHOP_FREE_ATTRIBUTES'));
					}

                    include __DIR__ .  '/freeattribute.php';

					if (isJoomla4()) {
						echo HTMLHelper::_('uitab.endTab');
					}
                }

				if (isJoomla4()) {
					echo HTMLHelper::_('uitab.addTab', 'myTab', 'product_media_tab', Text::_('COM_SMARTSHOP_PRODUCT_MEDIA'));
				}

                include __DIR__ . '/media.php';

				if (isJoomla4()) {
					echo HTMLHelper::_('uitab.endTab');
				}

                if (!empty($jshopConfig->admin_show_product_related)) {
					if (isJoomla4()) {
						echo HTMLHelper::_('uitab.addTab', 'myTab', 'product_related', Text::_('COM_SMARTSHOP_PRODUCT_RELATED'));
					}

                    include __DIR__ . '/related.php';

					if (isJoomla4()) {
						echo HTMLHelper::_('uitab.endTab');
					}
                }

                if (!empty($jshopConfig->admin_show_product_demo_files) || !empty($jshopConfig->admin_show_product_sale_files)) {
					if (isJoomla4()) {
						echo HTMLHelper::_('uitab.addTab', 'myTab', 'product_files', Text::_('COM_SMARTSHOP_FILES'));
					}

                    include __DIR__ . '/files.php';

					if (isJoomla4()) {
						echo HTMLHelper::_('uitab.endTab');
					}
                }

                if (!empty($jshopConfig->admin_show_product_extra_field)) {
					if (isJoomla4()) {
						echo HTMLHelper::_('uitab.addTab', 'myTab', 'product_extra_fields', Text::_('COM_SMARTSHOP_EXTRA_FIELDS'));
					}

                    include __DIR__ . '/extrafields.php';

					if (isJoomla4()) {
						echo HTMLHelper::_('uitab.endTab');
					}
                }

				if (isJoomla4()) {
					echo HTMLHelper::_('uitab.addTab', 'myTab', 'tshipping', Text::_('COM_SMARTSHOP_SHIPPINGS'));
				}

                include __DIR__ . '/product_shipping.php';
				
				if (isJoomla4()) {
					echo HTMLHelper::_('uitab.endTab');
				}
                
				if (empty($this->product->parent_id) && isJoomla4()) {
					$dispatcher->triggerEvent('onDisplayProductEditTabsEndTab', [&$row, &$lists, &$tax_value]);	   
				}
				
                if (!($this->product->parent_id) ) {       
                    $pane = null;
                    $dispatcher->triggerEvent('onDisplayProductEditTabsEnd', [&$pane, &$row, &$lists, &$tax_value, &$currency]);
                }
				
				if (isJoomla4()) {
					echo HTMLHelper::_('uitab.endTab');
				}
				if (isJoomla4()) {
					echo HTMLHelper::_('uitab.addTab', 'myTab', 'customize', Text::_('COM_SMARTSHOP_CUSTOMIZE'));
				}

                include __DIR__ . '/customize.php';

				if (isJoomla4()) {
					echo HTMLHelper::_('uitab.endTab');
				}

				if (isJoomla4()) {
					echo HTMLHelper::_('uitab.addTab', 'myTab', 'usergroup_permissions', Text::_('COM_SMARTSHOP_USERGROUP_PERMISSIONS'));
				}

                include __DIR__ . '/usergroup_permissions.php';

				if (isJoomla4()) {
					echo HTMLHelper::_('uitab.endTab');
					echo HTMLHelper::_('uitab.endTabSet');
				}
            ?>
        </div>

        <input type="hidden" name="task" value="" />
        <input type="hidden" name="current_cat" value="<?php echo JFactory::getApplication()->input->getVar('current_cat', 0); ?>" />
        <input type="hidden" name="product_id" value="<?php echo $row->product_id; ?>" />
        <input type="hidden" name="parent_id" value="<?php echo $row->parent_id; ?>" />
        
        <?php if (!empty($this->product->parent_id)) : ?>
            <input type="hidden" name="product_attr_id" value="<?php echo $this->product_attr_id ?: 0; ?>">
        <?php endif; ?>
    </form>
</div>

<script>
	window.addEventListener("load",() => {
		shopProductRelated.setLang("<?php echo JText::_('COM_SMARTSHOP_DELETE'); ?>");
	});

    <?php include __DIR__ . '/default_scripts.php'; ?>
</script>