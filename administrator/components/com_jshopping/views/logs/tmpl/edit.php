<?php 
/**
* @version      4.7.0 13.08.2013
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die();?>
<div class="jshop_log_info">
<?php print $this->tmp_html_start ?? '' ?>
<pre>
<?php print $this->data ?? '' ?>
</pre>
<?php print $this->tmp_html_end ?? '' ?>
</div>