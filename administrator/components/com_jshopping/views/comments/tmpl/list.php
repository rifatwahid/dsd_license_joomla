<?php 
/**
* @version      4.9.0 13.08.2013
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');

use Joomla\CMS\Layout\LayoutHelper;

displaySubmenuOptions("",$this->canDo);
$count = count($this->reviews);
$i = 0;

if (!isJoomla4()) {
	JHtml::_('formbehavior.chosen', 'select');
}
?>
<form action="index.php?option=com_jshopping&controller=reviews" method="post" name="adminForm" id="adminForm">

<?php print $this->tmp_html_start ?? ''?>

<div class="js-stools clearfix jshop_block_filter">
    
    <div class="js-stools-container-bar">
        <?php if (isJoomla4()) : ?> 
        <div id="filter-bar" class="btn-toolbar mb-3">
          <?php 
            echo LayoutHelper::render('smartshop.helpers.search_j4', [
              'searchText' => $text_search ?? ''
            ], JPATH_ROOT . '/components/com_jshopping/layouts'); 
          ?>
        </div>
        <?php else : ?>
          <div class="filter-search btn-group pull-left">
            <input type="text" id="text_search" name="text_search" placeholder="<?php print  JText::_('COM_SMARTSHOP_SEARCH')?>" value="<?php echo htmlspecialchars($this->text_search);?>"  onkeypress="shopSearch.searchEnterKeyPress(event,this);" />
          </div>

          <div class="btn-group pull-left hidden-phone">
            <button class="btn hasTooltip" type="submit" title="<?php print  JText::_('COM_SMARTSHOP_SEARCH')?>">
              <i class="icon-search"></i>
            </button>
            <button class="btn hasTooltip" onclick="document.id('text_search').value='';this.form.submit();" type="button" title="<?php print  JText::_('COM_SMARTSHOP_CLEAR')?>">
              <i class="icon-remove"></i>
            </button>
          </div>
        <?php endif; ?>
    </div>
        
    <div class="js-stools-container-filters">
        <?php print $this->tmp_html_filter ?? ''?>
        
        <div class="js-stools-field-filter">
            <?php echo $this->categories;?>
        </div>
        <div class="js-stools-field-filter">
            <?php echo $this->products_select;?>
        </div>  
    </div>
    
</div>
<div class="table-responsive">
<table class="table table-striped" >
<thead> 
  <tr>
    <th scope="col" class="title" width ="10">
      #
    </th>
    <th scope="col" width="20">
      <input type="checkbox" class="form-check-input" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
    </th>
    <th scope="col" width = "200" align = "left">
        <?php echo JHTML::_('grid.sort',  JText::_('COM_SMARTSHOP_NAME_PRODUCT'), 'name', $this->filter_order_Dir, $this->filter_order); ?>
    </th>
    <th scope="col" >
        <?php echo JHTML::_('grid.sort',  JText::_('COM_SMARTSHOP_USER'), 'pr_rew.user_name', $this->filter_order_Dir, $this->filter_order); ?>
    </th>        
    <th scope="col" >
        <?php echo JHTML::_('grid.sort',  JText::_('COM_SMARTSHOP_EMAIL'), 'pr_rew.user_email', $this->filter_order_Dir, $this->filter_order); ?>
    </th>
    <th scope="col" align = "left">
        <?php echo JHTML::_('grid.sort',  JText::_('COM_SMARTSHOP_PRODUCT_REVIEW'), 'pr_rew.review', $this->filter_order_Dir, $this->filter_order); ?>
    </th>
    <th scope="col" >
        <?php echo JHTML::_('grid.sort',  JText::_('COM_SMARTSHOP_REVIEW_MARK'), 'pr_rew.mark', $this->filter_order_Dir, $this->filter_order); ?>
    </th> 
    <th scope="col" >
        <?php echo JHTML::_('grid.sort',  JText::_('COM_SMARTSHOP_DATE'), 'pr_rew.time', $this->filter_order_Dir, $this->filter_order); ?> 
    </th>
    <th scope="col" >
        <?php echo JHTML::_('grid.sort', 'IP', 'pr_rew.ip', $this->filter_order_Dir, $this->filter_order); ?>
    </th>
    <th scope="col" width="50" class="center">
        <?php echo  JText::_('COM_SMARTSHOP_PUBLISH');?>       
    </th>
    <th scope="col" width="50" class="center">
        <?php echo  JText::_('COM_SMARTSHOP_EDIT'); ?>
    </th>
    <th scope="col" width="50" class="center">
        <?php echo  JText::_('COM_SMARTSHOP_DELETE'); ?>
    </th>
    <th scope="col" width="40" class="center">
        <?php echo JHTML::_('grid.sort',  JText::_('COM_SMARTSHOP_ID'), 'pr_rew.review_id', $this->filter_order_Dir, $this->filter_order); ?>
    </th>
  </tr>
</thead> 
<?php foreach ($this->reviews as $row){?>
<tr class="row<?php echo $i % 2;?>">
   <td>
     <?php echo $this->pagination->getRowOffset($i);?>             
   </td>
   <td>         
     <?php echo JHtml::_('grid.id', $i, $row->review_id);?>
   </td>
   <td>
     <?php echo $row->name;?>
   </td>
   <td>
     <?php echo $row->user_name;?>
   </td> 
   <td>
     <?php echo $row->user_email;?>
   </td>     
   <td>
     <?php echo $row->review;?>
   </td> 
   <td>
     <?php echo $row->mark;?>
   </td> 
   <td>
     <?php echo $row->dateadd;?>
   </td>
   <td>
     <?php echo $row->ip;?>
   </td>
   <td class="center">
     <?php echo JHtml::_('jgrid.published', $row->publish, $i);?>
   </td> 
   <td class="center">
    <a class="btn btn-micro" href='index.php?option=com_jshopping&controller=reviews&task=edit&cid[]=<?php print $row->review_id?>'>
        <i class="icon-edit"></i>
    </a>
   </td>
   <td class="center">
    <a class="btn btn-micro" href='index.php?option=com_jshopping&controller=reviews&task=remove&cid[]=<?php print $row->review_id?>' onclick="return confirm('<?php print  JText::_('COM_SMARTSHOP_DELETE')?>')">
        <i class="icon-delete"></i>
    </a>
   </td>
   <td class="center">
    <?php print $row->review_id;?>
   </td>
</tr>
<?php
$i++;
}
?>
 <tfoot>
 <tr>
    <td colspan="13">
		<div class = "jshop_list_footer"><?php echo $this->pagination->getListFooter(); ?></div>
        <div class = "jshop_limit_box"><?php echo $this->pagination->getLimitBox(); ?></div>
	</td>
 </tr>
 </tfoot>   
 </table>
 </div>

<input type="hidden" name="filter_order" value="<?php echo $this->filter_order?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->filter_order_Dir?>" />      
<input type="hidden" name="task" value="" />
<input type="hidden" name="hidemainmenu" value="0" />
<input type="hidden" name="boxchecked" value="0" />
<?php print $this->tmp_html_end ?? ''?>
</form>