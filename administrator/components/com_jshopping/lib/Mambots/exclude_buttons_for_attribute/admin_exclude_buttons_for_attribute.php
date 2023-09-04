<?php

defined('_JEXEC') or die('Restricted access');

require_once __DIR__ . '/../BackMambot.php';
require_once  __DIR__ . '/jshEAFAhelperbuttons.php';

class AdminExcludeButtonsForAttribute extends BackMambot 
{
    protected static $instance;

    public function onBeforeEditAtributesValues(&$view)
    {
        jshEAFAhelperbuttons::addSelectsAdminView($view);
    }

    public function onBeforeSaveAttributValue(&$post)
    {
        jshEAFAhelperbuttons::addPostAttrValues($post);
    }
}