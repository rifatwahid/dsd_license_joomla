<?php
/**
* @version      4.5.0 10.02.2014
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

$user = $this->user;
$lists = $this->lists;
$config_fields = $this->config_fields;
$defaultUserAddress = $this->defaultUserAddress;
?>

<div class="jshop_edit edit-user">
	<form action="index.php?option=com_jshopping&controller=users" method="post" name="adminForm" id="adminForm" autocomplete="off">
		<?php echo $this->tmp_html_start ?? ''; ?>

		<?php if (!isJoomla4()) : ?>
			<ul class="nav nav-tabs">    
				<li class="active">
					<a href="#firstpage1" data-toggle="tab"><?php echo  JText::_('COM_SMARTSHOP_GENERAL');?></a>
				</li>

				<?php if (!empty($user->user_id)) : ?>
					<li>
						<a href="#secondpage2" data-toggle="tab"><?php echo  JText::_('COM_SMARTSHOP_ADDRESSES');?></a>
					</li>
				<?php endif; ?>

				<?php echo $this->tabNavOfEditUser ?? ''; ?>
			</ul>
		<?php endif; ?>

		<div id="editdata-document" class="tab-content">
			<?php if (isJoomla4()) : ?>
				<?php 
					echo HTMLHelper::_('uitab.startTabSet', 'myTab', ['active' => 'firstpage1', 'recall' => true, 'breakpoint' => 768]); 
					echo HTMLHelper::_('uitab.addTab', 'myTab', 'firstpage1', Text::_('COM_SMARTSHOP_GENERAL'));
				?>
			<?php endif; ?>
				<div id="firstpage1" class="tab-pane active">
					<?php include __DIR__ . '/edit/general.php'; ?>
				</div>
			<?php if (isJoomla4()) : ?> 
				<?php echo HTMLHelper::_('uitab.endTab'); ?>
				<!-- Details tab -->
				<?php echo HTMLHelper::_('uitab.addTab', 'myTab', 'secondpage2', Text::_('COM_SMARTSHOP_ADDRESSES')); ?>
			<?php endif; ?>
				<?php if (!empty($user->user_id)) : ?>
					<div id="secondpage2" class="tab-pane">
						<?php include __DIR__ . '/edit/addresses.php'; ?>
					</div>
				<?php endif; ?>
			<?php if (isJoomla4()) : ?> 
				<?php echo HTMLHelper::_('uitab.endTab'); ?>
				<?php echo $this->tabContentOfEditUser ?? ''; ?>
				<?php echo HTMLHelper::_('uitab.endTabSet'); ?>
			<?php endif; ?>

		</div>

		<?php 
			$pkey = 'etemplatevarend';

			if (isset($this->$pkey) && !empty($this->$pkey)) {
				echo $this->$pkey;
			}
		?>
		<input type="hidden" name="task" value="">
		<input type="hidden" name="user_id" value="<?php echo $user->user_id; ?>">
		<input type="hidden" name="f_name" value="<?php echo $defaultUserAddress->f_name ?: ''; ?>">
		<input type="hidden" name="l_name" value="<?php echo $defaultUserAddress->l_name ?: ''; ?>">
		<?php echo $this->tmp_html_end ?? '' ; ?>
	</form>
</div>