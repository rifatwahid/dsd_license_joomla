<?php

defined('_JEXEC') or die('Restricted access');

require_once __DIR__ . '/../BackMambot.php';
require_once  __DIR__ . '/jshEAFAhelper.php';

class AdminExcludeAttributeForAttribute extends BackMambot 
{
    protected static $instance;

    public function onBeforeEditAtributesValues(&$view)
    {
        jshEAFAhelper::addSelectsAdminView($view);
    }

    public function onBeforeSaveAttributValue(&$post)
    {
        jshEAFAhelper::addPostAttrValues($post);
    }
}