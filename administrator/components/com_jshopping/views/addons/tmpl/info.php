<?php 
/**
* @version      4.6.1 10.08.2014
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');
$params = $this->params;
?>
<div class="jshop_edit addons_info">
<form action="index.php?option=com_jshopping&controller=addons" method="post" enctype="multipart/form-data" name="adminForm" id='adminForm'>
<?php if (isset($this->tmp_html_start)) print $this->tmp_html_start?>
<input type="hidden" name="id" value="<?php print $this->row->id?>">
<?php if ($this->file_exist){
    include($this->file_patch);
}?>
<input type="hidden" name="task" value="" />
<input type="hidden" name="hidemainmenu" value="0" />
<input type="hidden" name="boxchecked" value="0" />
<?php if (isset($this->tmp_html_end)) print $this->tmp_html_end?>
</form>
</div>