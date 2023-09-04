<?php
/**
* @version      4.14.0 25.05.2016
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die();

define('JPATH_JOOMSHOPPING', JPATH_ROOT . '/components/com_jshopping');
define('JPATH_JOOMSHOPPING_ADMIN', JPATH_ADMINISTRATOR . '/components/com_jshopping');

JTable::addIncludePath(JPATH_JOOMSHOPPING . '/tables');
JLoader::discover('JshopHelpers', JPATH_JOOMSHOPPING . '/helpers');

require_once JPATH_JOOMSHOPPING . '/lib/shopfiles.php';
require_once JPATH_JOOMSHOPPING . '/lib/jtableauto.php'; //Must always(!!!!) be first for tables
require_once JPATH_JOOMSHOPPING . '/lib/flashdata.php';
require_once JPATH_JOOMSHOPPING . '/tables/shopbase.php';
require_once JPATH_JOOMSHOPPING . '/lib/multilangfield.php';
require_once JPATH_JOOMSHOPPING . '/tables/config.php';
require_once JPATH_JOOMSHOPPING . '/lib/functions.php';
require_once JPATH_JOOMSHOPPING . '/lib/shop_item_menu.php';
require_once JPATH_JOOMSHOPPING . '/lib/jsuri.php';
require_once JPATH_JOOMSHOPPING . '/models/base.php';
require_once JPATH_JOOMSHOPPING . '/payments/payment.php';
require_once JPATH_JOOMSHOPPING . '/shippingform/shippingform.php';
require_once JPATH_JOOMSHOPPING . '/lib/formatconverter/convertertoimg.php';
require_once JPATH_JOOMSHOPPING . '/tables/userbase.php';