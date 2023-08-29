<?php

defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

JHtmlBootstrap::tooltip();
$jshopConfig = $this->config;
$lists = $this->lists;
$dispatcher = \JFactory::getApplication();
$row = $this->row;
?>

<div class="jshop_edit productMassEdit">
	<form action="index.php?option=com_jshopping&controller=products" method="post" name="adminForm" id="adminForm" id="item-form">

		<?php if (!isJoomla4()) : ?>
			<ul class="nav nav-tabs">   
				<!-- Description -->
				<li class="active">
					<a href="#description" data-toggle="tab">
						<?php echo JText::_('COM_SMARTSHOP_DESCRIPTION'); ?>
					</a>
				</li>
			
				<!-- Details -->
				<li>
					<a href="#details" data-toggle="tab">
						<?php echo JText::_('COM_SMARTSHOP_INFO_PRODUCT'); ?>
					</a>
				</li>

				<!-- Price -->
				<li>
					<a href="#main-price" data-toggle="tab">
						<?php echo JText::_('COM_SMARTSHOP_PRICE'); ?>
					</a>
				</li>

				<!-- Attributes -->
				<?php if ($jshopConfig->admin_show_attributes) : ?>
					<li>
						<a href="#attribs-page" data-toggle="tab">
							<?php echo JText::_('COM_SMARTSHOP_ATTRIBUTES'); ?>
						</a>
					</li>
				<?php endif; ?>	

				<!-- Free Attrs -->
				<?php if ($jshopConfig->admin_show_freeattributes) : ?>
					<li>
						<a href="#free-attrs" data-toggle="tab">
							<?php echo JText::_('COM_SMARTSHOP_FREE_ATTRIBUTES'); ?>
						</a>
					</li>
				<?php endif; ?>

				<!-- Media -->
				<li>
					<a href="#product_media_tab" data-toggle="tab">
						<?php echo JText::_('COM_SMARTSHOP_PRODUCT_MEDIA'); ?>
					</a>
				</li>

				<!-- Related products -->
				<?php if (!empty($jshopConfig->admin_show_product_related)) : ?>
					<li>
						<a href="#product_related" data-toggle="tab">
							<?php echo JText::_('COM_SMARTSHOP_PRODUCT_RELATED'); ?>
						</a>
					</li>
				<?php endif; ?>

				<!-- Files -->
				<?php if (!empty($jshopConfig->admin_show_product_demo_files) || !empty($jshopConfig->admin_show_product_sale_files)) : ?>
					<li>
						<a href="#product_files" data-toggle="tab">
							<?php echo JText::_('COM_SMARTSHOP_FILES'); ?>
						</a>
					</li>
				<?php endif; ?>

				<!-- Characteristics -->
				<?php if ($jshopConfig->admin_show_product_extra_field) : ?>
					<li>
						<a href="#product_extra_fields" data-toggle="tab">
							<?php echo JText::_('COM_SMARTSHOP_EXTRA_FIELDS'); ?>
						</a>
					</li>
				<?php endif; ?>

				<?php if (empty($this->product->parent_id)) {
					$dispatcher->triggerEvent('onDisplayProductEditListTabsTab', [&$lists]);
				} ?>

				<!-- Shippings -->
				<li>
					<a href="#tshipping" data-toggle="tab">
						<?php echo JText::_('COM_SMARTSHOP_SHIPPINGS'); ?>
					</a>
				</li>

				<!-- Customize -->
				<li>
					<a href="#customize" data-toggle="tab">
						<?php echo JText::_('COM_SMARTSHOP_CUSTOMIZE'); ?>
					</a>
				</li>

				<!-- Permissions -->
				<li>
					<a href="#usergroup_permissions" data-toggle="tab">
						<?php echo JText::_('COM_SMARTSHOP_USERGROUP_PERMISSIONS'); ?>
					</a>
				</li>
			</ul>
		<?php endif; ?>

		<div id="editdata-document" class="tab-content jshops_edit">
			<?php
				if (isJoomla4()) {
					echo HTMLHelper::_('uitab.startTabSet', 'myTab', ['active' => 'description', 'recall' => true, 'breakpoint' => 768]);
					echo HTMLHelper::_('uitab.addTab', 'myTab', 'description', Text::_('COM_SMARTSHOP_DESCRIPTION'));
				}
			?>
				<div id="description" class="tab-pane active">
					<div class="col100">
						<div class="admintable">
							<?php require __DIR__ . '/mass_edit/description.php'; ?>
						</div>
					</div>
				</div>
			<?php
				if (isJoomla4()) {
					echo HTMLHelper::_('uitab.endTab');
					echo HTMLHelper::_('uitab.addTab', 'myTab', 'details', Text::_('COM_SMARTSHOP_INFO_PRODUCT'));
				}
			?>
				<div id="details" class="tab-pane">
					<div class="col100">
						<table class="admintable">
							
							<?php 
								require __DIR__ . '/mass_edit/details.php'; 
								
								$pkey = 'etemplatevar'; 
								if ($this->$pkey) { 
									echo $this->$pkey;
								}
							?>
						</table>
					</div>
				</div>
			<?php 
				if (isJoomla4()) {
					echo HTMLHelper::_('uitab.endTab');
					echo HTMLHelper::_('uitab.addTab', 'myTab', 'main-price', Text::_('COM_SMARTSHOP_PRICE'));
				}
			?>
				<div id="main-price" class="tab-pane">
					<div class="col100">
						<table class="admintable">
							<?php require __DIR__ . '/mass_edit/price.php'; ?>
						</table>
					</div>
				</div>
			<?php 
				if (isJoomla4()) {
					echo HTMLHelper::_('uitab.endTab');
					echo HTMLHelper::_('uitab.addTab', 'myTab', 'attribs-page', Text::_('COM_SMARTSHOP_ATTRIBUTES'));
				}
			?>
				<?php if ($jshopConfig->admin_show_attributes) : ?>
					<div id="attribs-page" class="tab-pane">
						<?php require __DIR__ . '/mass_edit/attribute.php'; ?>
					</div>
				<?php endif; ?>
			<?php 
				if (isJoomla4()) {
					echo HTMLHelper::_('uitab.endTab');
					echo HTMLHelper::_('uitab.addTab', 'myTab', 'free-attrs', Text::_('COM_SMARTSHOP_FREE_ATTRIBUTES'));
				}
			?>
				<?php if ($jshopConfig->admin_show_freeattributes) : ?>
					<div id="free-attrs" class="tab-pane">
						<?php require __DIR__ . '/mass_edit/free_attrs.php'; ?>
					</div>
				<?php endif; ?>
			<?php 
				if (isJoomla4()) {
					echo HTMLHelper::_('uitab.endTab');
					echo HTMLHelper::_('uitab.addTab', 'myTab', 'product_media_tab', Text::_('COM_SMARTSHOP_PRODUCT_MEDIA'));
				}
			?>
				<?php if ($jshopConfig->admin_show_freeattributes) : ?>
					<div id="product_media_tab" class="tab-pane">
						<?php require __DIR__ . '/mass_edit/media.php'; ?>
					</div>
				<?php endif; ?>
			<?php 
				if (isJoomla4()) {
					echo HTMLHelper::_('uitab.endTab');
					echo HTMLHelper::_('uitab.addTab', 'myTab', 'product_related', Text::_('COM_SMARTSHOP_PRODUCT_RELATED'));
				}
			?>
				<?php if ($jshopConfig->admin_show_product_related) : ?>
					<div id="product_related" class="tab-pane">
						<?php require __DIR__ . '/mass_edit/related_products.php'; ?>
					</div>
				<?php endif; ?>
			<?php 
				if (isJoomla4()) {
					echo HTMLHelper::_('uitab.endTab');
					echo HTMLHelper::_('uitab.addTab', 'myTab', 'product_related', Text::_('COM_SMARTSHOP_FILES'));
				}
			?>
				<?php if (!empty($jshopConfig->admin_show_product_demo_files) || !empty($jshopConfig->admin_show_product_sale_files)) : ?>
					<div id="product_files" class="tab-pane">
						<?php require __DIR__ . '/mass_edit/files.php'; ?>
					</div>
				<?php endif; ?>
			<?php 
				if (isJoomla4()) {
					echo HTMLHelper::_('uitab.endTab');
					echo HTMLHelper::_('uitab.addTab', 'myTab', 'product_extra_fields', Text::_('COM_SMARTSHOP_EXTRA_FIELDS'));
				}
			?>
				<?php if ($jshopConfig->admin_show_product_extra_field) : ?>
					<div id="product_extra_fields" class="tab-pane">
						<?php include __DIR__  . '/mass_edit/characteristics.php'; ?>
					</div>
				<?php endif; 
					$dispatcher->triggerEvent('onDisplayProductEditListTabsEndTab', [&$lists]);
				?>
			<?php 
				if (isJoomla4()) {
					echo HTMLHelper::_('uitab.endTab');
					echo HTMLHelper::_('uitab.addTab', 'myTab', 'tshipping', Text::_('COM_SMARTSHOP_SHIPPINGS'));
				}
			?>
				<div id="tshipping" class="tab-pane">
					<?php include __DIR__  . '/mass_edit/shippings.php'; ?>
				</div>
			<?php 
				if (isJoomla4()) {
					echo HTMLHelper::_('uitab.endTab');
					echo HTMLHelper::_('uitab.addTab', 'myTab', 'customize', Text::_('COM_SMARTSHOP_CUSTOMIZE'));
				}
			?>
				<div id="customize" class="tab-pane">
					<?php include __DIR__  . '/mass_edit/customize.php'; ?>
				</div>
			<?php 
				if (isJoomla4()) {
					echo HTMLHelper::_('uitab.endTab');
					echo HTMLHelper::_('uitab.addTab', 'myTab', 'usergroup_permissions', Text::_('COM_SMARTSHOP_USERGROUP_PERMISSIONS'));
				}
			?>
				<div id="usergroup_permissions" class="tab-pane">
					<?php include __DIR__  . '/mass_edit/usergroup_permission.php'; ?>
				</div>
			<?php 
				if (isJoomla4()) {
					echo HTMLHelper::_('uitab.endTab');
					echo HTMLHelper::_('uitab.endTabSet');
				}
			?>
		</div>

		<input type="hidden" name="task">
		<?php foreach($this->cid as $cid) : ?>
			<input type="hidden" name="cid[]" value="<?php echo $cid; ?>">
		<?php endforeach; ?>
	</form>
</div>

<script>
	function shfoldprice(checked) {
		let foldPriceEl = document.querySelector('#foldprice');

		if (foldPriceEl) {
			if (checked) {
				foldPriceEl.style.display = 'none';
			} else {
				foldPriceEl.style.display = 'block';
			}
		}
	}

	document.querySelector('#adminForm').addEventListener('change', function (e) {
		let target = e.target;

		if (target.name == 'characteristics_action') {
			let isMultipleMode = (target.value == 1) ? true : false;;
			shopProductCharacteristics.switchSelectToMultButDontTouchAlreadyMult(isMultipleMode);
		}
	});
</script>