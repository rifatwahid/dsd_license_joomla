<?php
use Joomla\CMS\Application\SiteApplication;

defined( '_JEXEC' ) or die( 'Restricted access' );
jimport('joomla.application.component.controller');
class JshoppingControllerReturns extends JControllerLegacy
{
    
    public function __construct($config = array())
    {
        parent::__construct($config);
        JPluginHelper::importPlugin('jshoppingcheckout');
        $currentObj = $this;
        \JFactory::getApplication()->triggerEvent('onConstructJshoppingControllerReturns', [&$currentObj]);
		setSeoMetaData();
    }

    public function display($cachable = false, $urlparams = false)
    {
        $this->view();
    }

    public function start()
    {		
		JText::script('COM_SMARTSHOP_NATIVE_UPLOAD_EXIST_ZERO_QUANTITY_IN_ROW');
		JText::script('COM_SMARTSHOP_QTY');
		$jshopConfig = JSFactory::getConfig();
        checkUserLogin();
        $user = JFactory::getUser();
        $lang = JSFactory::getLang();
        $dispatcher = \JFactory::getApplication();
		$_returns = JSFactory::getModel("returns");
		$_returns->load();
		
		$order_id = JFactory::getApplication()->input->getInt('order_id');
        $order = JSFactory::getTable('order', 'jshop');
        $order->load($order_id);
		
		if ($user->id != $order->user_id) {
            throw new \Exception('Error order number. You are not the owner of this order', 500);
        }
		
		$return_products = [];
		if($_returns->order_id == $order_id){
			$return_products = $_returns->products;
		}
		
		$items = $order->getAllItems();
        $_items = $_returns->returnItemsQty($items, $return_products);
		$first = array(0 => JText::_('COM_SMARTSHOP_NO_REASON'));
		$_returnStatusList = array_merge($first, $_returns->getAllReturnStatus());
		$session = JFactory::getSession();
		$session->set('returns', '');
        
		$view = $this->getView('returns', getDocumentType(), '', [
            'template_path' => viewOverride('returns', 'start.php')
        ]);
		$layout = getLayoutName('returns', 'start');
        $view->setLayout($layout);
        $view->set('component', 'Start');
		
		$view->set('order', $order);
        $view->set('config', $jshopConfig);
        $view->set('items', $_items);
        $view->set('return_products', $return_products);
        $view->set('return_status_list', $_returnStatusList);
       
        $view->display();
    }
	
	function add(){		
        checkUserLogin();
        $orderId = JFactory::getApplication()->input->getInt('order_id');
        $products_count = JFactory::getApplication()->input->getVar('products_count');
        $reason = JFactory::getApplication()->input->getVar('reason');
        $comments = JFactory::getApplication()->input->getVar('comments');
		
		$returns = JSFactory::getModel("returns");
        $returns->load();
		
		if($returns->add($orderId, $products_count, $reason, $comments)){
			$this->setRedirect(SEFLink('index.php?option=com_jshopping&controller=returns&task=step2', 0, 1));			
		}
		
	}
	
	function step2(){
        checkUserLogin();
		$jshopConfig = JSFactory::getConfig();
		$returns = JSFactory::getModel("returns");
		$order = JSFactory::getTable('order', 'jshop');			
        $returns->load();
		
		if(!$returns->order_id){			
			$this->setRedirect(SEFLink('index.php?option=com_jshopping&controller=user&task=orders', 0, 1));
		}
		
        $order->load($returns->order_id);
		$order_products = $order->getAllItems();
		$products = $returns->loadProductData($order_products);
       
	    $view = $this->getView('returns', getDocumentType(), '', [
            'template_path' => viewOverride('returns', 'summary.php')
        ]);
		$layout = getLayoutName('returns', 'summary');
        $view->setLayout($layout);
        $view->set('component', 'Summary');

		$view->set('order_id', $returns->order_id);
        $view->set('config', $jshopConfig);
        $view->set('products', $products);
			
        $view->display();
	}
	
	function save(){
        checkUserLogin();
		$returns = JSFactory::getModel("returns");
		$returns->load();
		
		if(!$returns->order_id){			
			$this->setRedirect(SEFLink('index.php?option=com_jshopping&controller=user&task=orders', 0, 1));
		}
		$returns->saveReturns();
		$this->setRedirect(SEFLink('index.php?option=com_jshopping&controller=returns&task=finish', 0, 1));	
	}
	
	function finish(){
        checkUserLogin();
		$jshopConfig = JSFactory::getConfig();
		$vendor = JSFactory::getTable('vendor', 'jshop');
		$modelOfJsContent = JSFactory::getModel('contentFront', 'jshop');
        $vendor->loadMain();
		
		$returns = JSFactory::getModel("returns");
		$returns->load();
		if(!$returns->order_id){			
			$this->setRedirect(SEFLink('index.php?option=com_jshopping&controller=user&task=orders', 0, 1));
		}
		
        $text = $modelOfJsContent->getTextContentByContentName('return_finish_page');
		if (trim(strip_tags($text)) == '') {
            $text = '';
        }
		
		$returns->clear();
	    $view = $this->getView('returns', getDocumentType(), '', [
            'template_path' => viewOverride('returns', 'finish_return.php')
        ]);
		$address = '';
		$layout = getLayoutName('returns', 'finish_return');
        $view->setLayout($layout);
        $view->set('component', 'Finish_return');
        $view->set('address', $vendor);
        $view->set('text', $text);

        $view->set('config', $jshopConfig);
			
        $view->display();	
	}
   
   
   
}