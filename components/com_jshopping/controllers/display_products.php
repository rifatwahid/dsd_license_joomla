<?php

/**
 * @version
 * @author       
 * @package     smartSHOP
 * @copyright    Copyright (C) 2010. All rights reserved.
 * @license      GNU/GPL
 */
defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controller');

JModelLegacy::addIncludePath(JPATH_SITE . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_expresseditor' . DIRECTORY_SEPARATOR . 'models');
include_once JPATH_SITE . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_expresseditor' . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR .  'epptableauto.php';
JTable::addIncludePath(JPATH_SITE . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_expresseditor' . DIRECTORY_SEPARATOR . 'tables');
class JshoppingControllerDisplay_products extends JshoppingControllerBase {

    function display($cachable = false, $urlparams = []) {
        $mainframe = JFactory::getApplication();
        $db = \JFactory::getDBO();
        $ajax = JFactory::getApplication()->input->getInt('ajax');
        $jshopConfig = JSFactory::getConfig();
        $user = JFactory::getUser();

        $product_id = JFactory::getApplication()->input->getInt('product_id');
        $category_id = JFactory::getApplication()->input->getInt('category_id');
        $quantity = JFactory::getApplication()->input->getInt('quantity', 1);

        if ($jshopConfig->use_decimal_qty) {
            $quantity = floatval(str_replace(',', '.', JFactory::getApplication()->input->getVar('quantity', 1)));
            $quantity = round($quantity, $jshopConfig->cart_decimal_qty_precision);
        }

        $to = JFactory::getApplication()->input->getVar('to', 'cart');
        if ($to != 'cart' && $to != 'wishlist') {
            $to = 'cart';
        }
            
        $jshop_attr_id = JFactory::getApplication()->input->getVar('jshop_attr_id');
        $freeattribut = JFactory::getApplication()->input->getVar("freeattribut");
        $sna = (array) JFactory::getApplication()->input->getVar("sna");

        $lang = JSFactory::getLang();
        $query = "SELECT editor_id FROM `#__ee_editors_to_products` WHERE `product_id` = '{$product_id}' AND `enable` = 1 AND `open_type` = 2";
        $db->setQuery($query);
        $editor_id = $db->loadResult();
        $query = "SELECT epp_id FROM `#__ee_editors_to_products` WHERE `product_id` = '{$product_id}' AND `enable` = 1 AND `open_type` = 2";
        $db->setQuery($query);
        $epp_id = $db->loadResult();


        $view_name = "category";
        $view_config = array("template_path" => viewOverride($view_name, 'products_upload.php'));
        $view = &$this->getView($view_name, getDocumentType(), '', $view_config);
        // echo JFactory::getApplication()->input->getInt('show_to_step');die;
        if (JFactory::getApplication()->input->getInt('show_to_step') == 0) {
            $html_ = '<form enctype="multipart/form-data" action="' . SEFLink('index.php?option=com_jshopping&controller=cart&task=add') . '" method="post" name="product" id="select_pr">
                  <input class="button" type="submit" onclick="document.getElementById(\'to\').value = \'cart\';" value="" style="width:0; display:none;text-align:left;height:0; ">
                  <input id="quantity" type="hidden" value="' . $quantity . '" name="quantity">
                  <input id="product_id" type="hidden" value="' . $product_id . '" name="product_id">
                  <input id="category_id" type="hidden" value="' . $category_id . '" name="category_id">';
            if ($jshop_attr_id)
                foreach ($jshop_attr_id as $key => $att) {
                    $html_ .= '<input type="hidden" value="' . $att . '" name="jshop_attr_id[' . $key . ']">';
                }
            if ($freeattribut)
                foreach ($freeattribut as $key => $fatt) {
                    $html_ .= '<input type="hidden" value="' . $fatt . '" name="freeattribut[' . $key . ']">';
                }
            $html_ .= '<script type="text/javascript">document.getElementById(\'select_pr\').submit();</script>
                </form>';
            echo $html_;
            die();
        }

        if ($product->product_template == '') {
            $product->product_template = 'default';
        }
            
        $view->setLayout('products_upload');

        $view->set('config', $jshopConfig);
        $view->set('category_id', $category_id);
        $view->set('product_id', $product_id);
        $view->set('editor_id', $editor_id);
        $view->set('epp_id', $epp_id);
        $view->set('quantity', $quantity);
        $view->set('attributes', $jshop_attr_id);
        $view->set('freeattribut', $freeattribut);
        $view->set('sna', $sna);
        $view->set('params', JFactory::getApplication()->input->getVar('params'));
        $view->display();
    }

    function addtocart2() 
    {
        $mainframe = JFactory::getApplication();
        $db = \JFactory::getDBO();
        $post = JFactory::getApplication()->input->post->getArray();

        $query = "SELECT  id  FROM `#__jshopping_free_attr` WHERE type_for_editor = '3'";
        $db->setQuery($query);
        $attr_file_id = $db->loadResult();
        echo $attr_file_id;
        die();
    }

    function addtocart() 
    {
		JPluginHelper::importPlugin('jshoppingproducts');
		$dispatcher = \JFactory::getApplication();
        $mainframe = JFactory::getApplication();
        $db = \JFactory::getDBO();
        $post = JFactory::getApplication()->input->post->getArray();
        $sna = (array) JFactory::getApplication()->input->getVar("sna");
        $price = (double) JFactory::getApplication()->input->getVar("price");
        $xmlid = JFactory::getApplication()->input->getVar("id");
        $aftereditor = JFactory::getApplication()->input->getInt('aftereditor');
        $priceFromFrontendplusImages = JFactory::getApplication()->input->getInt('priceFromFrontendplusImages');
        if (!class_exists('SEConfig')) {
            if (defined('JPATH_CONFIGURATION')) {
                require_once JPATH_CONFIGURATION . '/smarteditor_configuration.php';
            } else {
                require_once JPATH_SITE . '/smarteditor_configuration.php';
            }
        }
        $seconfig = new SEConfig;
        $smarteditor = JModelLegacy::getInstance('smarteditor', 'jshop');

        $attr_file_id = $smarteditor->getFileEditorFreeAttrId();
        $post = json_decode($post['parrams']);
        //$post->xmlname = 'smarteditor|' . $post->ftpName; //TASK 2453 Konfigurator in HTML 5. 27/04/2017
        if ($aftereditor) {
            //NEW
            $jshopConfig = JSFactory::getConfig();
            //$lng = getLangShortCode();        		
            $xm = $seconfig->editor_saved_xml . "showXML/" . $xmlid . ".xml"; //GOOD for Api//$xm
            $pxml = $seconfig->editor_saved_xml . "priceXML/" . $xmlid . ".xml"; //GOOD for Api
            $smarteditor = JModelLegacy::getInstance('smarteditor', 'jshop');
            $modelXml = JModelLegacy::getInstance('editor_xml', 'ExpresseditorModel'); //GOOD for Api
            $xml = $modelXml->load($xm);
            $editor_id = $modelXml->getEditor_id();
            $options_save = array('fotoliaPictures' => 1, 'rf123Pictures' => 1, 'pattern2Pictures' => 1, 'colourboxPictures' => 1, 'adobe_stockPictures' => 1, 'depositphotosPictures'=>1); //GOOD for Api       
            $product_id = $modelXml->createEditorProduct($template_title, $lng, $price_description, $user, $options_save);
            $product = JModelLegacy::getInstance('Editor_products', 'ExpresseditorModel')
                    ->getData($product_id, array('enable' => 0));
            $post->xml = $product->xml_id;
            $post->product_type = $product->type;
            $post->pr_id = $product_id;
            //NEW
            $product_id_for_order = $smarteditor->copyProductToNew($post->product_id, array('params' => $post, 'xmlid' => $xmlid), $price);
			$dispatcher->triggerEvent('onAfterCopyProductToNew', array(&$product_id_for_order, &$post->product_id));
            /**/
            $this->setListImage($product_id_for_order, $xml);
        } else {
            $product_id_for_order = 0;
        }

        $attr = '';
        if ($post->jshop_attr_id) {
            foreach ($post->jshop_attr_id as $key => $attributes) {
                if(is_object($attributes)){
                    foreach($attributes as $key2 => $attribute){
                        $attr .= '<input type="hidden" name="jshop_attr_id[' . $key . ']['. $key2 .']"  value="' . $attribute . '" />';
                    }
                }else{
                    $attr .= '<input type="hidden" name="jshop_attr_id[' . $key . ']"  value="' . $attributes . '" />';
                }
            }
        }

        $price9999 = 0;
        $price9998 = 0;
        if ($priceFromFrontendplusImages > 0) {
            list($price9999, $price9998, $attr) = $this->priceAsAttribute($seconfig, $xmlid, $product_id_for_order, $attr);
            $this->setListImageByPriceXml($product_id_for_order, $xmlid);
        }
        /////Add atribute priceFromFrontendplusImages
//die($db->insertid());
        $data_form = $this->get_form_data($post, $sna, $product_id_for_order, $priceFromFrontendplusImages, $seconfig->editor_saved_xml, $xmlid, $price9999, $price9998, $sna_editor);
        $this->redirect_width_post($data_form);
        die;
    }

    protected function getListImage($product)
    {
		$images_price = [
            'list' => [],
            'total_price' => 0
        ];
		
		if(!empty($product->temp_data)) {
            $images_price = json_decode($product->temp_data, true);
        }	
        	
		return $images_price;
	}
	
    function setListImageByPriceXml($product_id_for_order, $xmlid)
    {
		$product = JTable::getInstance('product', 'jshop');
		$product->load($product_id_for_order);	
		
        $priceFromFrontendplusImages = JFactory::getApplication()->input->getInt('priceFromFrontendplusImages');
		if($priceFromFrontendplusImages < 1){
		if($product->product_price < $product->min_price){
			$product->product_price = $product->min_price;
		}
		}
		
		$images_price = $this->getListImage($product);	
		
		$pxml = "{$seconfig->editor_saved_xml}priceXML/{$xmlid}.xml";
		$xml = (array) simplexml_load_file($pxml);	   
        $total_price = 0;
        $types = [
            'photo',
            'svgPattern',
            'fotolia',
            'rf123',
            'svg'
        ];		

        foreach ($pricexml['details'] as $prices) {
            foreach ((array) $prices as $p) {
                $price = 0;
                
                if ( in_array($p['key'], $types) ) {
                    $price = (double) $p['priceProduct'];
                }
                
				$total_price += $price;							
				$images_price['list'][] = [
                    'price' => $price,
					'name' => (string)$p['key'],
					'quantity' => 1,
					'opr' => '+'
                ];
            }
        }
		
        $images_price['total_price'] += $total_price;	
		$product->temp_data = (string)json_encode($images_price);
		$product->store();
	}
	
    function setListImage($product_id_for_order, $xml)
    {
		$product = JTable::getInstance('product', 'jshop');
		$product->load($product_id_for_order);	
		$images_price = $this->getListImage($product);	
		
		$total_price = 0;
        if(isset ($xml->sna_editor)) {
            foreach (get_object_vars($xml->sna_editor) as $value) {
                $total_price += (double)$value['price'];

                $images_price['list'][] = [
                    'price' => (double)$value['price'],
                    'name' => (string)$value['name']['0'],
                    'quantity' => 1,
                    'opr' => '+'
                ];
            }
        }
		
		$images_price['total_price'] += $total_price;	
		$product->temp_data = (string)json_encode($images_price);		
		$product->store();
    }

    function get_form_data(&$post, $sna, $product_id_for_order, $priceFromFrontendplusImages, $editor_saved_xml, $xmlid, $price = 0, $price9998 = 0, $sna_editor = []) 
    {
        $data = [];

        if ($post->jshop_attr_id) {
            foreach ($post->jshop_attr_id as $key => $attributes) {
                $data["jshop_attr_id[" . $key . "]"] = $attributes;
            }
        }
        if ($price > 0) {
            $data["jshop_attr_id[9999]"] = "9999";
        }

        if ($price9998 > 0) {
            $data["jshop_attr_id[9998]"] = "9998";
        }

        if ($post->freeattribut) {
            foreach ($post->freeattribut as $key => $freeattribut) {
                $data["freeattribut[" . $key . "]"] = $freeattribut;
            }
        }
        if ($sna) {
            foreach ($sna as $k => $v) {
                $data["sna[" . $k . "]"] = $v;
            }
        }
        if ($post->sna) {
            foreach ($post->sna as $k => $v) {
                $data["sna[" . $k . "]"] = $v;
            }
        }
        /*NEW*/
        if (!empty($sna_editor)) {
            foreach ($sna_editor as $v) {
                $data["sna[" . $v['sna_id'] . "]"] = $v['sna_product_id'];
            }
        }
        $data['product_id'] = $post->product_id;
		
        if (($product_id_for_order > 0)AND ( $priceFromFrontendplusImages > 0)) {
            $data['product_id'] = $product_id_for_order;
        }

        $data['quantity'] = $post->quantity;
        $data['category_id'] = $post->category_id;
        $data['product_id_for_order'] = $product_id_for_order;
        $data['priceFromFrontendplusImages'] = $priceFromFrontendplusImages;

        if ($priceFromFrontendplusImages) {
            $file_price_xml = "{$editor_saved_xml}priceXML/{$xmlid}.xml";
            $data['adt_file_price_xml'] = $file_price_xml;
        }

        return $data;
    }

    function redirect_width_post($data) 
    {
        $string = http_build_query($data);
        //echo 'Location: '.SEFLink('index.php?option=com_jshopping&controller=cart&task=add&'.$string, 0, 1); die;
        // If validation has passed, redirect to the URL rewritten search page
        header('Location: ' . SEFLink('index.php?option=com_jshopping&controller=cart&task=add&' . $string, 0, 1));
    }

    function ajax_get_product() 
    {
        $db = \JFactory::getDBO();
        $lang = JSFactory::getLang();
        $jshopConfig = JSFactory::getConfig();

        $product_id = JFactory::getApplication()->input->getInt('product_id');
        $change_attr = JFactory::getApplication()->input->getInt('change_attr');
        if ($jshopConfig->use_decimal_qty) {
            $qty = floatval(str_replace(",", ".", JFactory::getApplication()->input->getVar('qty', 1)));
        } else {
            $qty = JFactory::getApplication()->input->getInt('qty', 1);
        }
        if ($qty < 0)
            $qty = 0;
        $attribs = JFactory::getApplication()->input->getVar('attr');
        if (!is_array($attribs))
            $attribs = array();
        $freeattr = JFactory::getApplication()->input->getVar('freeattr');
        if (!is_array($freeattr))
            $freeattr = array();

        JPluginHelper::importPlugin('jshoppingproducts');
        $dispatcher = \JFactory::getApplication();
        $dispatcher->triggerEvent('onBeforeLoadDisplayAjaxAttrib', array(&$product_id, &$change_attr, &$qty, &$attribs));

        $product = JTable::getInstance('product', 'jshop');

        $product->load($product_id);

        $product->getExtendsData();

        $attributesDatas = $product->getAttributesDatas($attribs, JSFactory::getUser()->usergroup_id);
        $product->setAttributeActive($attributesDatas['attributeActive']);
        $attributeValues = $attributesDatas['attributeValues'];
        $product->setFreeAttributeActive($freeattr);

        $attributes = $product->getBuildSelectAttributes($attributeValues, $attributesDatas['attributeSelected']);


        // ---------------
        $default_count_product = 1;
        if ($jshopConfig->min_count_order_one_product > 1) {
            $default_count_product = $jshopConfig->min_count_order_one_product;
        }

        // ------
        $rows = array();
        $attrib = '';
        if ($attributes)
            foreach ($attributes as $k => $v) {
                $v->selects = str_replace(array("\n", "\r", "\t"), "", $v->selects);
                $rows[] = '"name_' . $k . '":"' . str_replace('"', '\"', $v->attr_name) . '"';
                $rows[] = '"id_' . $k . '":"' . str_replace('"', '\"', $v->selects) . '"';
                $attrib .= $k . ",";
            }
        $rows[] = '"attrib":"' . substr($attrib, 0, (strlen($attrib) - 1)) . '"';


        if ($jshopConfig->admin_show_freeattributes) {

            //$product->getListFreeAttributes();

            $query = "SELECT FA.id, FA.required, FA.`" . $lang->get("name") . "` as name, FA.type, FA.type_for_editor FROM `#__jshopping_products_free_attr` as PFA left join `#__jshopping_free_attr` as FA on FA.id=PFA.attr_id
                      WHERE PFA.product_id = '" . $db->escape($product->product_id) . "' order by FA.ordering";
            $db->setQuery($query);
            $product->freeattributes = $db->loadObjectList();

            foreach ($product->freeattributes as $k => $v) {
                if ($v->required == 1)
                    $required = 'required';
                else
                    $required = '';
                $value1 = '';
                $dispatcher->triggerEvent('onBeforeDisplayProductAjax', array(&$v->id, &$value1));
                $product->freeattributes[$k]->input_field = '<input onkeyup="free_attributte_recalcule();" onchange="free_attributte_recalcule();" id="freeattribut_' . $v->id . '" type="text" class="inputbox freeattr ' . $required . '" size="40" name="freeattribut[' . $v->id . ']" value="' . $value1 . '" />';
            }
            $attrrequire = $product->getRequireFreeAttribute();
            $product->freeattribrequire = count($attrrequire);
        }else {
            $product->freeattributes = null;
            $product->freeattribrequire = 0;
        }


        $freeattrib = '';

        if ($product->freeattributes)
            foreach ($product->freeattributes as $kkey => $freeattribut) {
                if ($freeattribut->type_for_editor != 3) { // type file not use
                    $rows[] = '"free_name_' . $kkey . '":"' . str_replace('"', '\"', $freeattribut->name) . '"';
                    $rows[] = '"input_field_' . $kkey . '":"' . str_replace('"', '\"', $freeattribut->input_field) . '"';
                    $rows[] = '"free_required_' . $kkey . '":"' . $freeattribut->required . '"';
                    $freeattrib .= $kkey . ",";
                }
                if ($freeattribut->type_for_editor == 1)
                    $rows[] = '"free_width":"' . $freeattribut->id . '"';
                if ($freeattribut->type_for_editor == 2)
                    $rows[] = '"free_height":"' . $freeattribut->id . '"';
                if ($freeattribut->type_for_editor == 3)
                    $rows[] = '"free_file":"' . $freeattribut->id . '"';
            }


        if ($product->default_width > 0) {
            $rows[] = '"free_width":"' . $product->default_width . '"';
        }


        if ($product->default_height > 0) {
            $rows[] = '"free_height":"' . $product->default_height . '"';
        }


        $rows[] = '"freeattrib":"' . substr($freeattrib, 0, (strlen($freeattrib) - 1)) . '"';
        //print_r ($product->show_two_step);die;
        $pricefloat = $product->getPrice($qty, 1, 1, 1);
        $price = formatprice($pricefloat);
        $available = intval($product->getQty() > 0);
        $ean = $product->getEan();
        $weight = formatweight($product->getWeight());
        $weight_volume_units = $product->getWeight_volume_units();

        $rows[] = '"product_basic_price_volume":"' . $product->weight_volume_units . '"';
        $str_tax = productTaxInfo($product->product_tax);

        $rows[] = '"tax":"' . substr($str_tax, 0, (strlen($str_tax) - strlen(strrchr($str_tax, ' ')))) . ' <a style=\'color:#fff;text-decoration:underline\' href=' . JRoute::_('index.php?option=com_content&view=article&id=30&Itemid=524', 1) . '>' . strrchr($str_tax, ' ') . '</a>' . '"';
        $rows[] = '"price":"' . $price . '"';
        $rows[] = '"pricefloat":"' . $pricefloat . '"';
        $rows[] = '"available":"' . $available . '"';
        $rows[] = '"ean":"' . $ean . '"';


        if ($jshopConfig->admin_show_product_basic_price) {
            $rows[] = '"wvu":"' . $weight_volume_units . '"';
        }
        if ($jshopConfig->product_show_weight) {
            $rows[] = '"weight":"' . $weight . '"';
        }
        if ($jshopConfig->product_list_show_price_default && $product->product_price_default > 0) {
            $rows[] = '"pricedefault":"' . formatprice($product->product_price_default) . '"';
        }
        if ($jshopConfig->product_show_qty_stock) {
            $qty_in_stock = getDataProductQtyInStock($product);
            $rows[] = '"qty":"' . sprintQtyInStock($qty_in_stock) . '"';
        }

        $product->updateOtherPricesIncludeAllFactors();

        if (is_array($product->product_add_prices)) {
            foreach ($product->product_add_prices as $k => $v) {
                $rows[] = '"pq_' . $v->product_quantity_start . '":"' . str_replace('"', '\"', formatprice($v->price)) . '"';
            }
        }
        if ($product->product_old_price) {
            $old_price = formatprice($product->product_old_price);
            $rows[] = '"oldprice":"' . $old_price . '"';
        }

        if ($jshopConfig->use_extend_attribute_data) {
            $images = $product->getImages();
            $videos = $product->getVideos();
            $tmp = array();
            foreach ($images as $img) {
                $tmp[] = '"' . $img->image_name . '"';
            }
            $displayimgthumb = intval((count($images) > 1) || (count($videos) && count($images)));
            $rows[] = '"images":[' . implode(",", $tmp) . '],"displayimgthumb":"' . $displayimgthumb . '"';
        }
        $short_d = str_replace('"', "'", $product->short_description);
        $short_dd = str_replace("\r\n", ' ', $short_d);
        //print_r ($product->show_two_step);die;
        $product->getExtendsData();
        $rows[] = '"show_two_step":"' . $product->show_two_step . '"';
        $rows[] = '"short_description":"' . $short_dd . '"';
        $rows[] = '"default_count_product":"' . $default_count_product . '"';
        print '{' . implode(",", $rows) . '}';
        die();
    }

    function quickorder() 
    {
        $post = JFactory::getApplication()->input->post->getArray();
        $xmlid = JFactory::getApplication()->input->getVar('id');
        $aftereditor = JFactory::getApplication()->input->getInt('aftereditor');
        set_include_path(implode(PATH_SEPARATOR, array(JPATH_SITE . '/components/com_expresseditor/', get_include_path())));

        $model = JModelLegacy::getInstance('smarteditorquickorder', 'jshop');
        $model->setData($post, $xmlid, $aftereditor);
        $order = $model->save();

        $prms = $post['parrams'];

        $res = [
            'product_url' => SEFLink("index.php?option=com_jshopping&controller=product&task=view&category_id={$prms['category_id']}&product_id={$prms['product_id']}", 1),
            'order_id' => $order->order_id
        ];

        print json_encode($res);
        die();
    }

    function priceAsAttribute($seconfig, $xmlid, $product_id_for_order, $attr) 
    {
        $pxml = $seconfig->editor_saved_xml . "priceXML/" . $xmlid . ".xml";
        $pricexml = (array) simplexml_load_file($pxml);
        /////Add atribute priceFromFrontendplusImages
        $price9999 = 0;
        $price9998 = 0;
        foreach ($pricexml['details'] as $prices) {
            foreach ((array) $prices as $p) {
                if ($p['key'] == "photo") {
                    $price9999 += (double) $p['priceProduct'];
                }
                if ($p['key'] == "svgPattern") {
                    $price9998 = (double) $p['priceProduct'];
                }
                if ($p['key'] == "fotolia") {
                    $price9999 += (double) $p['priceProduct'];
                }
                if ($p['key'] == "rf123") {
                    $price9999 += (double) $p['priceProduct'];
                }
                if ($p['key'] == "svg") {
                    $price9999 += (double) $p['priceProduct'];
                }
               /* if ($p['key'] == "adobe_stock") {
                    $price9999 += (double) $p['priceProduct'];
                }*/
            }
        }
        //$attr = $this->imageAsAttribute($price9999, $product_id_for_order, $attr);
        //$this->svgPatternAsAttribute($price9998, $product_id_for_order);
        return array($price9999, $price9998, $attr);
    }

    function insertImageAttribute($attr_id, $name) 
    {
        $db = \JFactory::getDBO();
        $query = "SELECT count(attr_id) as count_id FROM `#__jshopping_attr` WHERE attr_id=" . $attr_id;
        $db->setQuery($query);
        $count_id = $db->loadResult();
        if ($count_id == 0) {
            $query = "INSERT INTO `#__jshopping_attr` (`attr_id`, `attr_ordering`, `attr_type`, `independent`, `name_de-DE`, `name_en-GB`) VALUES (" . $attr_id . ",0,2,1,'" . $name . "','" . $name . "')";
            $db->setQuery($query);
            $db->execute();
        }
    }

    function insertImageValueAttribute($value_id, $attr_id, $name) 
    {
        $db = \JFactory::getDBO();
        $query = "SELECT count(value_id) as count_id FROM `#__jshopping_attr_values` WHERE value_id=" . $value_id;
        $db->setQuery($query);
        $count_id = $db->loadResult();
        if ($count_id == 0) {
            $query = "INSERT INTO `#__jshopping_attr_values` (`value_id`, `attr_id`, `value_ordering`, `image`, `name_de-DE`, `name_en-GB`) VALUES(" . $value_id . ", " . $attr_id . ", 0, '', '" . $name . "', '" . $name . "');";
            $db->setQuery($query);
            $db->execute();
        }
        $listfields = $db->getTableColumns("#__jshopping_products_attr");
        if (!isset($listfields["attr_" . $value_id])) {
            $query = "ALTER TABLE `#__jshopping_products_attr` ADD `attr_" . $value_id . "` INT NOT NULL";
            $db->setQuery($query);
            $db->execute();
        }
    }

   /* function imageAsAttribute($price9999, $product_id_for_order, $attr)
    {
        if ($price9999 > 0) {
            $this->insertImageAttribute("9999", "Photo");
            $this->insertImageValueAttribute("9999", "9999", "Photo");
            $db = \JFactory::getDBO();
            $query = 'INSERT INTO #__jshopping_products_attr2 (`product_id`,`attr_id`,`attr_value_id`,`price_mod`,`addprice`) VALUES (' . $product_id_for_order . ',9999,9999,"+",' . (double) $price9999 . ')';
            $db->setQuery($query);
            $db->execute();
            $attr .= '<input type="hidden" name="jshop_attr_id[9999]"  value="9999" />'; //$db->insertid()
        }
        return $attr;
    }

    function svgPatternAsAttribute($price9998, $product_id_for_order) 
    {
        if ($price9998 > 0) {
            $this->insertImageAttribute("9998", "svgPattern");
            $this->insertImageValueAttribute("9998", "9998", "svgPattern");
            $db = \JFactory::getDBO();
            $query = 'INSERT INTO #__jshopping_products_attr2 (`product_id`,`attr_id`,`attr_value_id`,`price_mod`,`addprice`) VALUES (' . $product_id_for_order . ',9998,9998,"+",' . (double) $price9998 . ')';
            $db->setQuery($query);
            $db->execute();
        }
    }*/

}
