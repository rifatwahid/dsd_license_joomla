<?php

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.controller');


class JshoppingControllerFunctions extends JshoppingControllerBase
{

    function getPriceFormat(){
        $post = JFactory::getApplication()->input->post->getArray();
        print_r(formatprice($post['price']));die;
    }
    function getPricesFormat(){
        $post = JFactory::getApplication()->input->post->getArray();
        $product = json_decode($post['product']);
        $ajaxResponse = [];
        if($post['totalAjaxPrice']){
            $ajaxResponse['product_price'] = formatprice($product->preview_total_price);
        }else{
            $ajaxResponse['product_price'] = formatprice($product->product_price);
        }
        $ajaxResponse['product_old_price'] = formatprice($product->product_old_price);
        $ajaxResponse['basic_price'] = formatprice($product->basic_price_info['basic_price']);
        
        print_r(json_encode($ajaxResponse));die;
       // print_r(formatprice($post['price']));die;
    }

    function productTaxInfo(){
        $post = JFactory::getApplication()->input->post->getArray();
        print_r(productTaxInfo($post['tax']));die;
    }

    function sprintBasicPrice(){
        $post = JFactory::getApplication()->input->post->getArray();
        print_r(sprintBasicPrice($post['prod']));die;
    }
    function isUrl(){
        $post = JFactory::getApplication()->input->post->getArray();
        $jsUri = JSFactory::getJSUri();

        print_r($jsUri->isUrl(post['url']));die;
    }
    function unsereliaze_data(){
        $post = JFactory::getApplication()->input->post->getArray();

        print_r(unserialize(post['sereliaze']));die;
    }
    function sprintPreviewNativeUploadedFiles(){
        $post = JFactory::getApplication()->input->post->getArray();

        print_r(sprintPreviewNativeUploadedFiles($post['uploadData']));die;

    }
    function sprintJsTemplateForNativeUploadedFiles(){
        $post = JFactory::getApplication()->input->post->getArray();

        print_r(sprintJsTemplateForNativeUploadedFiles($post['uploadData']));die;

    }
    function printSelectQuantityCart(){
        $post = JFactory::getApplication()->input->post->getArray();

        print_r(printSelectQuantityCart($post['product_id'], $post['quantity_select'], $post['default_count_product'], $post['name'], $post['key_id']));die;

    }
    function printSelectQuantity(){
        $post = JFactory::getApplication()->input->post->getArray();
        print_r(printSelectQuantityOptions($post['product'], $post['equal_steps'], $post['quantity_select'], $post['default_count_product']));die;
       // print_r(printSelectQuantity($post['equal_steps'], $post['quantity_select'], $post['default_count_product']));die;

    }
    function formattax(){
        $post = JFactory::getApplication()->input->post->getArray();

        print_r(formattax($post['tax']));die;
    }

    function seflink(){
        $post = JFactory::getApplication()->input->post->getArray();

        print_r(SEFLink($post['link'], $post['useDefaultItemId'], $post['redirect'], $post['ssl']));die;
    }

    function patchProductImage(){
        $post = JFactory::getApplication()->input->post->getArray();

        print_r(getPatchProductImage($post['name'], $post['prefix'], $post['patchtype']));die;
    }
    function productUsergroupPermissions(){
        $post = JFactory::getApplication()->input->post->getArray();
        $product = json_decode($post['product']);
        $pr = new jshopProduct($product);
        $productUsergroupPermissions = $product->getUsergroupPermissions();
        print_r(json_value_encode($productUsergroupPermissions));die;
    }
    function file_exists_link(){
        $post = JFactory::getApplication()->input->post->getArray();

        print_r(file_exists($post['file']));die;
    }
    function generate_link(){
      //  print_r($_REQUEST);die;
        $config = JFactory::getConfig();
        $category = JSFactory::getTable('category', 'jshop');
        $manufacturer = JSFactory::getTable('manufacturer', 'jshop');
        $categories = $category->getAllCategories(1);
        $manufacturers = $manufacturer->getAllManufacturers(1);
        $links = [];
        if(!empty($categories)){
            foreach($categories as $cat){
                $links['categories'][] = SEFLink('index.php?option=com_jshopping&controller=category&task=view&category_id=' . $cat->category_id, 1);
                $links['productslinks'][] = SEFLink('index.php?option=com_jshopping&controller=category&task=view&category_id=' . $cat->category_id, 1).'/:product';
            }
        }
		
        if(!empty($manufacturers)){
            foreach($manufacturers as $val){
                $links['manufacturers'][] = SEFLink('index.php?option=com_jshopping&controller=manufacturer&task=view&manufacturer_id=' . $val->manufacturer_id);
            }
        }
        $links = $this->getShopMenuLinks($links);
       
        $post = JFactory::getApplication()->input->get('post');
        $links['cart'] = $this->generateRoute(SEFLink('index.php?option=com_jshopping&controller=cart', 1));
        $links['cart_view'] = $this->generateRoute(SEFLink('index.php?option=com_jshopping&controller=cart&task=view', 1));
        $links['category'] = $this->generateRoute(SEFLink('index.php?option=com_jshopping&controller=category', 0));
        $links['category_display'] = $this->generateRoute(SEFLink('index.php?option=com_jshopping&controller=category&task=display', 1));
        $links['category_view'] = $this->generateRoute(SEFLink('index.php?option=com_jshopping&controller=category&task=view', 1));
        $links['step2'] = $this->generateRoute(SEFLink('index.php?option=com_jshopping&controller=checkout&task=step2', 1));
        $links['step3'] = $this->generateRoute(SEFLink('index.php?option=com_jshopping&controller=checkout&task=step3', 1));
        $links['step4'] = $this->generateRoute(SEFLink('index.php?option=com_jshopping&controller=checkout&task=step4', 1));
        $links['step5'] = $this->generateRoute(SEFLink('index.php?option=com_jshopping&controller=checkout&task=step5', 1));
        $links['checkout_finish'] = $this->generateRoute(SEFLink('index.php?option=com_jshopping&controller=checkout&task=finish', 1));
        $links['manufacturer'] = $this->generateRoute(SEFLink('index.php?option=com_jshopping&controller=manufacturer&task=display', 1));
        $links['manufacturer_view'] = $this->generateRoute(SEFLink('index.php?option=com_jshopping&controller=manufacturer&task=view', 1));
        $links['offer_created'] = $this->generateRoute(SEFLink('index.php?option=com_jshopping&controller=offer_and_order&task=created', 1));
        $links['myoffer_and_order'] = $this->generateRoute(SEFLink('index.php?option=com_jshopping&controller=offer_and_order', 1));
        $links['offer_email_sent'] = $this->generateRoute(SEFLink('index.php?option=com_jshopping&controller=offer_and_order&task=offer_email_sent', 1));
        $links['product'] = $this->generateRoute(SEFLink('index.php?option=com_jshopping&controller=product&task=display', 1));
        $links['jshop'] = $this->generateRoute(SEFLink('index.php?option=com_jshopping', 1));
        $links['products'] = $this->generateRoute(SEFLink('index.php?option=com_jshopping&controller=products&task=display', 1));
        $links['tophits'] = $this->generateRoute(SEFLink('index.php?option=com_jshopping&controller=products&task=tophits', 1));
        $links['toprating'] = $this->generateRoute(SEFLink('index.php?option=com_jshopping&controller=products&task=toprating', 1));
        $links['label'] = $this->generateRoute(SEFLink('index.php?option=com_jshopping&controller=products&task=label', 1));
        $links['bestseller'] = $this->generateRoute(SEFLink('index.php?option=com_jshopping&controller=products&task=bestseller', 1));
        $links['random'] = $this->generateRoute(SEFLink('index.php?option=com_jshopping&controller=products&task=random', 1));
        $links['last'] = $this->generateRoute(SEFLink('index.php?option=com_jshopping&controller=products&task=last', 1));
        $links['custom'] = $this->generateRoute(SEFLink('index.php?option=com_jshopping&controller=products&task=custom', 1));
        $links['qcheckout'][] = $this->generateRoute(SEFLink('index.php?option=com_jshopping&controller=qcheckout&view=qcheckout', 1));
        $links['qcheckout'][] = $this->generateRoute(getCheckoutUrl(1, true));
        $links['qcheckout_finish'] = $this->generateRoute(SEFLink('index.php?option=com_jshopping&controller=qcheckout&task=finish', 1));
        $links['step6'] = $this->generateRoute(SEFLink('index.php?option=com_jshopping&controller=qcheckout&task=step6', 1));
        $links['search'] = $this->generateRoute(SEFLink('index.php?option=com_jshopping&controller=search&task=display', 1));
        $links['search_result'] = $this->generateRoute(SEFLink('index.php?option=com_jshopping&controller=search&task=result', 1));
        $links['login'] = $this->generateRoute(SEFLink('index.php?option=com_jshopping&controller=user&task=login', 1));
        $links['register'] = $this->generateRoute(SEFLink('index.php?option=com_jshopping&controller=user&task=register', 1));
        $links['addresses'] = $this->generateRoute(SEFLink('index.php?option=com_jshopping&controller=user&task=addresses', 1));
        $links['addNewAddress'] = $this->generateRoute(SEFLink('index.php?option=com_jshopping&controller=user&task=addNewAddress', 1));
        $links['editAddress'] = $this->generateRoute(SEFLink('index.php?option=com_jshopping&controller=user&task=editAddress', 1));
        $links['orders'] = $this->generateRoute(SEFLink('index.php?option=com_jshopping&controller=user&task=orders', 1));
        $links['order'] = $this->generateRoute(SEFLink('index.php?option=com_jshopping&controller=user&task=order', 1));
        $links['myaccount'] = $this->generateRoute(SEFLink('index.php?option=com_jshopping&controller=user&task=myaccount', 1));
        $links['groupsinfo'] = $this->generateRoute(SEFLink('index.php?option=com_jshopping&controller=user&task=groupsinfo', 1));
        $links['addressPopup'] = $this->generateRoute(SEFLink('index.php?option=com_jshopping&controller=user&task=addressPopup', 1));
        $links['deleteAddress'] = $this->generateRoute(SEFLink('index.php?option=com_jshopping&controller=user&task=deleteAddress', 1));
        $links['wishlistView'] = $this->generateRoute(SEFLink('index.php?option=com_jshopping&controller=wishlist&task=view', 1));
        $links['wishlist'] = $this->generateRoute(SEFLink('index.php?option=com_jshopping&controller=wishlist', 1));

        print_r(json_encode($links));die;
    }

    function generateRoute($link){
        $arr = explode('?', $link);
        $arr = explode('&', $arr[0]);
        return $arr[0];
    }

    function getShopMenuLinks($links){
        if (!class_exists('shopItemMenu')) {
			include_once __DIR__ . '/shop_item_menu.php';
		}
		$shim = shopItemMenu::getInstance();
		foreach($shim->list as $menu){ 
			if($menu->data['view'] == 'product'){ 
				$links['productslinks'][] = $this->generateRoute(SEFLink('index.php?option=com_jshopping&'.$menu->link.'&Itemid='.$menu->id, 1));
			}
		}
		return $links;
    }

}