<?php

$rows = $this->rows;
$pageNav = $this->pageNav;

if (!isJoomla4()) {
    if(class_exists('JHtmlSidebar') && count(JHtmlSidebar::getEntries())) {
        $sidebar = JHtmlSidebar::render();
    }

    $classMain = '';
    if(isset($sidebar) && $sidebar):
        $classMain = ' class="span10"'; ?>
        <div id="j-sidebar-container" class="span2">
            <?php echo $sidebar; ?>
        </div>
    <?php endif;
}
?>

<div id="j-main-container"<?php echo $classMain ?? '';?>>
    <?php displaySubmenuOptions("",$this->canDo); ?>

    <form action = "index.php?option=com_jshopping&controller=states" method = "post" id="adminForm" name = "adminForm">


    <div class="input-group mb-3">
        <?php 
            echo $this->filter['countries']; 
            echo $this->filter['publish'];
        ?>
    </div>

    <table class="table table-striped">
        <thead>
            <tr>
                <th class = "title" width  = "10">
                    #
                </th>
                <th width = "20">
                    <input type="checkbox" class="form-check-input" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
                </th>
                <th align = "left">
                    <?php echo JText::_('COM_SMARTSHOP_STATES'); ?>
                </th>
                <th align = "left">
                    <?php echo JText::_('COM_SMARTSHOP_COUNTRY'); ?>
                </th>
                <th colspan = "3" width = "40">
                    <?php echo JText::_('COM_SMARTSHOP_ORDERING'); ?>
                    <button onClick="shopHelper.saveorder(<?php echo count($rows); ?>, 'saveorder', event)" title="Save Order" class="saveorder btn btn-sm btn-secondary float-end float-right"><span class="icon-menu-2"></span></button>
                </th>
                <th width = "50">
                    <?php echo JText::_('COM_SMARTSHOP_PUBLISH'); ?>
                </th>
                <th width="50">
                    <?php print JText::_('COM_SMARTSHOP_EDIT'); ?>
                </th>
                <th width = "50">
                    <?php print JText::_('COM_SMARTSHOP_ID');?>
                </th>
            </tr>
        </thead>
        <?php
            $i = 0;
            $count = count($rows);
            foreach($rows as $row) :
        ?>
        <tr class = "row<?php echo $i % 2;?>">
            <td>
                <?php echo $pageNav->getRowOffset($i);?>
            </td>
            <td>
                <?php echo JHtml::_('grid.id', $i, $row->state_id);?>
            </td>
            <td style="text-align: left;">
                <a href = "index.php?option=com_jshopping&controller=states&task=edit&state_id=<?php echo $row->state_id; ?>"><?php echo $row->name;?></a>
            </td>
            <td style="text-align: left;">
                <?php echo $row->country;?>
            </td>
            <td align = "right" width = "20">
                <?php
                    if ($i != 0) {
                        echo '
                            <a  class="btn btn-micro" href = "index.php?option=com_jshopping&controller=states&task=order&id=' . $row->state_id . '&order=up&number=' . $row->ordering . '">
                                <i class="icon-uparrow"></i>
                            </a>
                        ';
                    }
                ?>
            </td>
            <td align = "left" width = "20">
                <?php
                    if ($i != $count - 1) {
                        echo '
                            <a class="btn btn-micro" href = "index.php?option=com_jshopping&controller=states&task=order&id=' . $row->state_id . '&order=down&number=' . $row->ordering . '">
                                <i class="icon-downarrow"></i>
                            </a>
                        ';
                    }
                ?>
            </td>
            <td align = "center" width = "10">
                <input type="text" name="order[]" id = "ord<?php echo $row->state_id;?>" size="5" value="<?php echo $row->ordering; ?>" <?php echo $disabled ?? '' ?> class="text_area" style="text-align: center; width: 50px;" />    
            </td>
            <td align="center">
                <?php
                    if ($row->state_publish) {
                        echo '
                            <a  class="btn btn-micro" href = "javascript:void(0)" onclick = "return Joomla.listItemTask(\'cb' . $i . '\', \'unpublish\')">
                                <span class="icon-publish" aria-hidden="true"></span>
                            </a>
                        ';
                    } else {
                        echo '
                            <a  class="btn btn-micro" href = "javascript:void(0)" onclick = "return Joomla.listItemTask(\'cb' . $i . '\', \'publish\')">
                                <span class="icon-unpublish" aria-hidden="true"></span>
                            </a>
                        ';
                    }
                ?>
            </td>
            <td align="center">
                <a class="btn btn-micro" href='index.php?option=com_jshopping&controller=states&task=edit&state_id=<?php print $row->state_id;?>'>
					<i class="icon-edit"></i>
				</a>
            </td>
            <td align="center">
                <?php echo $row->state_id;?>
            </td>
        </tr>
        <?php
            $i++;
            endforeach;
        ?>
        <tfoot>
            <tr>
                <td colspan="11">
                    <?php echo $pageNav->getListFooter();?>
                </td>
            </tr>
        </tfoot>
    </table>

    <input type="hidden" name="task" value="" />
    <input type="hidden" name="hidemainmenu" value="0" />
    <input type="hidden" name="boxchecked" value="0" />
</form>