<?php 
/**
* @version      4.3.1 13.08.2013
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');
$menu=getItemsOptionPanelMenu($canDo); 
?>
<div class="jssubmenu">
    <div class="m">
        <ul id="submenu">
        <?php        
		foreach($menu as $key=>$el){
            if (!$el[3]) continue;
            $class="";
            if ($active){
                if ($key==$active) $class="class='active'";
            }else{
                if ($el[1] == basename($_SERVER['REQUEST_URI'])) $class="class='active'";
            }
        ?>
            <li>
                <a <?php print $class;?> href="<?php print $el[1]?>"><?php print $el[0]?></a>
            </li>
        <?php }?>        
        </ul>    
        <div class="clr"></div>
    </div>
</div>