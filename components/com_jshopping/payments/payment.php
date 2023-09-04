<?php
/**
* @version      4.3.1 13.08.2013
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/
defined('_JEXEC') or die('Restricted access');

class PaymentRoot
{    
    public $_errormessage = '';
    public $pm_method;
    
    /**
    * static
    * show form payment. Checkout Step3
    * @param array $params - entered params
    * @param array $pmconfigs - configs
    */    
    public function showPaymentForm($params, $pmconfigs)
    {
    }
    
    /**
    * check payment params. Checkout Step3save
    */
    public function checkPaymentInfo($params, $pmconfigs)
    {
        /*$this->setErrorMessage("error mgs");*/
        return 1;
    }
    
    /**
    * list display params name payment saved to order    
    */
    public function getDisplayNameParams()
    {
        return array();
    }
	
    public function getPaymentParamsData($params)
    {
		return $params;
	}
    
    /**
    * get current params
    */
    public function getParams()
    {
        return $this->_ps_params;
    }
    
    /**
    * set params
    */
    public function setParams($params)
    {
        $this->_ps_params = $params;
    }
    
    /**
    * Form parametrs. Edit params payment in administrator.
    * static
    */
    public function showAdminFormParams($pmconfigs)
    {
    }
    
    /**
    * Show form. Checkout Step6.
    */
    public function showEndForm($pmconfigs, $order)
    {        
    }
    
    public function setPmMethod($pm_method)
    {
        $this->pm_method = $pm_method;
    }
    
    public function getPmMethod()
    {
        return $this->pm_method;
    }
    
    /**
    * Check Transaction
    * @param array $pmconfigs parametns
    * @param object $order order
    * @param string $act action
    * @return array($rescode, $restext, $transaction, $transactiondata)
    */
    public function checkTransaction($pmconfigs, $order, $act)
    {
        return [
            1, 
            '', 
            '', 
            []
        ];
    }
    
    /**
    * Get status order from rescode payment
    * @param int $rescode
    * @param array $pmconfigs
    * @return int
    */
    public function getStatusFromResCode($rescode, $pmconfigs)
    {
        $status = 0;
        $types_status = [
            0 => 0, 
            1 => $pmconfigs['transaction_end_status'], 
            2 => $pmconfigs['transaction_pending_status'], 
            3 => $pmconfigs['transaction_failed_status'], 
            4 => $pmconfigs['transaction_cancel_status'], 
            5 => $pmconfigs['transaction_open_status'], 
            6 => $pmconfigs['transaction_shipping_status'], 
            7 => $pmconfigs['transaction_refunded_status'], 
            8 => $pmconfigs['transaction_confirm_status'], 
            9 => $pmconfigs['transaction_complete_status'], 
            10 => $pmconfigs['transaction_other_status'],
            40 => $pmconfigs['transaction_on_hold_status'],
            41 => $pmconfigs['transaction_accepted_status'],
            42 => $pmconfigs['transaction_rejected_status'],
            99 =>0
        ];

        if (isset($types_status[$rescode])) {
            $status = $types_status[$rescode];
        }

        return $status;
    }
    
    /**
    * get url parametr for payment. Step7
    */
    public function getUrlParams($pmconfigs)
    {
        return [];
    }
    
    /**
    * Exec after notify. Step7.
    */
    public function nofityFinish($pmconfigs, $order, $rescode)
    {
    }
    
    /**
    * exec before end. Step7.
    */
    public function finish($pmconfigs, $order, $rescode, $act)
    {
    }
    
    /**
    * exec complete. StepFinish.
    */
    public function complete($pmconfigs, $order, $payment)
    {
    }
    
    /**
	* exec before mail send
    */
    public function prepareParamsDispayMail(&$order, &$pm_method)
    {
    }
    
    /**
    * Set message error check payment
    */
    public function setErrorMessage($msg)
    {
        $this->_errormessage = $msg;
    }
    
    /**
    * Get message error check payment. Step3
    */
    public function getErrorMessage()
    {
        if ($this->_errormessage == '') {
            $this->_errormessage = JText::_('COM_SMARTSHOP_ERROR_PAYMENT_DATA');
        }

        return $this->_errormessage;
    }
	
	/**
    * Check send order email from rescode payment
    * @param int $rescode
    * @param array $pmconfigs
    * @return int
    */
    public function checkSendOrderMail($rescode, $pmconfigs)
    {
        $res = 0;
        $types_status = [
            3 => $pmconfigs['transaction_failed_status'], 
            4 => $pmconfigs['transaction_cancel_status'], 
            42 => $pmconfigs['transaction_rejected_status']
        ];

        if (in_array($rescode,$types_status)) {
            return 0;
        }

        return 1;
    }
}