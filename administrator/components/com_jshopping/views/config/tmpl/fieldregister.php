<?php 
/**
* @version      4.9.0 13.08.2013
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');

JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');

JHtmlBootstrap::tooltip();
JHtmlBootstrap::modal('a.modal');

$jshopConfig = JSFactory::getConfig();
$listOrder = 'ASC';
$listDirn = 'sorting';

if (version_compare(JVERSION, '3.999.999', 'le')) JHTML::_('behavior.tooltip');

$current_fields = $this->current_fields;
$saveOrder = $listOrder == 'sorting';

    //$saveOrderingUrl = 'index.php?option=com_jshopping&controller=configfields&task=saveOrder';
   // JHtml::_('sortablelist.sortable', 'fieldList', 'adminForm', strtolower($listDirn), $saveOrderingUrl);


displaySubmenuConfigs('fieldregister', $this->canDo);
?>
    <form class="jshopfieldregister" action="index.php?option=com_jshopping&controller=config" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data">
        <?php echo $this->tmp_html_start ?? ''; ?>
        <input type="hidden" name="task" value="">
        <input type="hidden" name="tab" value="9">

		<table class="table table-striped ui-sortable" id="fieldList" style="position: relative;">
            
            <tr>
                <th width="1%" class="order nowrap center hidden-phone">
                </th>
                <th>
                    <?php echo JText::_('COM_SMARTSHOP_NAME'); ?>
                </th>
            </tr>
            
                 <?php $i = 0; foreach($current_fields as $field) : $i++; ?>
                    <tr class="ui-sortable-handle row<?php echo $i % 2; ?>" id="row_field_<?php echo $i ?>" >

                        <td class="order nowrap center hidden-phone">

                            <span class="sortable-handler">
								<span class="icon-menu" aria-hidden="true"></span>
							</span>
                            <?php //if ($canChange && $saveOrder) : ?>
                            <?php //endif; ?>
                        </td>
                        <td>
                            <a href="index.php?option=com_jshopping&controller=configfields&task=edit&field_id=<?php echo $field->id; ?>"><?php echo $field->name; ?></a>
                            <input type='hidden' name='field_id[]' value='<?php echo $field->id; ?>'>
                            <input type='hidden' id="sort_id_<?php print $field->id; ?>" name='sort_id[]' value=''>

                        </td>

                    </tr>
                    <?php endforeach; ?>
           
            </table>


        <div class="clr"></div>
        <?php echo $this->tmp_html_end ?? ''; ?>
    </form>
<script>	
	(function () {
		Sortable.create(document.querySelector('#fieldList tbody'), {
			animation: 150,
			onEnd: function (evt) {
				let fields_id = [];
				let ajaxUrl = 'index.php?option=com_jshopping&controller=configfields&task=save_order';

				let parentTable = document.querySelector('#fieldList tbody');
				if (parentTable) {
					let inputsWithProdAttrSorting = parentTable.querySelectorAll('input[name*=sort_id]');

					if (inputsWithProdAttrSorting) {
						inputsWithProdAttrSorting.forEach(function (item, indx) {
							let newSortingNumber = indx + 1;
							item.value = newSortingNumber;
						});
					}
				}

				let fieldsEls = document.querySelectorAll('input[name*=field_id]');
				if (fieldsEls) {
					fieldsEls.forEach(function (item) {
						let id = `#sort_id_${item.value}`;
						let el = document.querySelector(id);

						if (el) {
							fields_id[item.value] = el.value;
						}
					});
				}

				if (fields_id) {
					let fieldId = '&field_id[]=';
					fields_id.forEach(function (Id) {
						fieldId += '&field_id[]=' + Id;
					});
					ajaxUrl += fieldId;
				}
				
				fetch(ajaxUrl)
				.then(response => response.text())
				.then(text => {})
			}
		});
	})();
</script>