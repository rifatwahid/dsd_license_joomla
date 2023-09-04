<?php 
/**
* @version      4.9.0 13.08.2013
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');
?>
<div id="j-main-container">
<table class="table table-striped" id="articleList">
	<thead>
		<tr>
			<th width="1%" class="nowrap center">
				<?php echo  JText::_('COM_SMARTSHOP_SELECT_CONTENT_STATUS');?>
			</th>
			<th style="min-width:100px" class="nowrap">
				<?php echo  JText::_('COM_SMARTSHOP_SELECT_CONTENT_TITLE');?>
			</th>
			<th width="10%" class="nowrap hidden-phone">
				<?php echo  JText::_('COM_SMARTSHOP_SELECT_CONTENT_ACCESS');?>
			</th>
			<th width="10%" class="nowrap hidden-phone">
				<?php echo JHtml::_('searchtools.sort', 'JGRID_HEADING_LANGUAGE', 'language', $listDirn ?? '', $listOrder ?? ''); ?>
			</th>
			<th width="1%" class="nowrap hidden-phone">
				<?php echo JHtml::_('searchtools.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn ?? '', $listOrder ?? ''); ?>
			</th>
		</tr>		
	</thead>
	<tfoot>
		<tr>
			<td colspan="5">
			</td>
		</tr>
	</tfoot>
	<tbody>	
	<?php 	
	foreach($this->contents as $i=>$content){?>
		<tr class="row<?php echo $i % 2; ?>">
			<td class="order nowrap center hidden-phone">
				<?php echo JHtml::_('jgrid.published', $content->state, $i, 'articles.', 0, 'cb', $content->publish_up, $content->publish_down); ?>
			</td>
			<td class="has-context">
				<div class="selected_title" onClick='shopConfig.selectContent(<?php echo $content->id;?>,"<?php echo $content->title;?>")' data-dismiss="modal" data-bs-dismiss="modal"><?php echo $content->title;?></div>
			</td>	
			<td class="hidden-phone">				
				<div><?php echo $content->access_title;?></div>					
			</td>
			<td class="small hidden-phone">
				<div><?php echo $content->language;?></div>
			</td>		
			<td class="hidden-phone">
				<?php echo (int) $content->id; ?>
			</td>
		</tr>
	<?php } ?>
	</tbody>
</table>

	<div class="j-main-container-footer pl-3 ps-3 pr-3 pe-3">
		<?php 
			if (!empty($this->pages)) {
				echo JText::_('COM_SMARTSHOP_SELECT_CONTENT_PAGES');

				for ($i=1;$i<=$this->pages;$i++){
					?> <button class=" <?php if ($this->current_page==($i-1)){echo "btn-primary";}?>" onClick="shopConfig.reloadPage(<?php echo ($i-1);?>)"><?php echo $i;?></button><?
				}
			}
		?>
	</div>

</div>
<br>