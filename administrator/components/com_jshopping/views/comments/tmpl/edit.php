<?php 
/**
* @version      4.9.0 13.08.2013
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
?>
<div class="jshop_edit user-review">
	<form action="index.php?option=com_jshopping&controller=reviews" method="post" enctype="multipart/form-data" name="adminForm" id="adminForm">
		
		<?php if (!isJoomla4()) : ?>
			<ul class="nav nav-tabs">    
				<li class="active">
					<a href="#generalInfo" data-toggle="tab"><?php echo  JText::_('COM_SMARTSHOP_GENERAL');?></a>
				</li>

				<li>
					<a href="#media" data-toggle="tab"><?php echo  JText::_('COM_SMARTSHOP_ATTACHED_FILES');?></a>
				</li>
			</ul>
		<?php endif; ?>

		<div id="editdata-document" class="tab-content">
			<?php if (isJoomla4()) {
				echo HTMLHelper::_('uitab.startTabSet', 'myTab', ['active' => 'generalInfo', 'recall' => true, 'breakpoint' => 768]); 
				echo HTMLHelper::_('uitab.addTab', 'myTab', 'generalInfo', Text::_('COM_SMARTSHOP_GENERAL'));
			} ?>

				<div id="generalInfo" class="tab-pane active">
					<?php include __DIR__ . '/general_info.php'; ?>
				</div>

			<?php if (isJoomla4()) {
				echo HTMLHelper::_('uitab.endTab');
				echo HTMLHelper::_('uitab.addTab', 'myTab', 'media', Text::_('COM_SMARTSHOP_ATTACHED_FILES'));
			} ?> 

				<div id="media" class="tab-pane">
					<?php include __DIR__ . '/attached_files.php'; ?>
				</div>

			<?php if (isJoomla4()) {
				echo HTMLHelper::_('uitab.endTab');
				echo HTMLHelper::_('uitab.endTabSet');
			} ?>
		</div>

	</form>
</div>