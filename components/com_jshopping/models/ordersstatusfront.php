<?php 

defined( '_JEXEC' ) or die( 'Restricted access' );

class JshoppingModelOrdersStatusFront extends jshopBase
{
    public const TABLE_NAME = '#__jshopping_order_status';

    public function getStatusNameByStatusId(int $statusId): string
    {
        $lang = JSFactory::getLang();
        return $this->select(['`' . $lang->get('name') . '` as name'], ['status_id = \'' . $statusId . '\''], '', false)->name ?: '';
    }

    public function getListOrdersStatusWithCertainCancellation(int $certainCancellation = 1)
	{
        JModelLegacy::addIncludePath(JPATH_JOOMSHOPPING_ADMIN . '/models');
        $adminOrderStatusModel = JSFactory::getModel('Orderstatus');

		$result = [];
        $statuses = $adminOrderStatusModel->getListOrderStatus();
        
		if (!empty($statuses)) {
			foreach ($statuses as $status) {
				if ($status->is_allowed_status_for_cancellation == $certainCancellation) {
					$result[$status->status_id] = $status;
				}
			}
		}

		return $result;
	}

	public function sendEmailWhenChangeOrderStatus($order, $subject, $msgTextForClient, $msgTextForAdmin, $isNotifyCustomer = true, $isNotifyAdmin = true)
	{
		$mainframe = JFactory::getApplication();
		$jshopConfig = JSFactory::getConfig();
		$dispatcher = \JFactory::getApplication();

        $vendor_send_order_admin = (($jshopConfig->vendor_order_message_type == 2 && $order->vendor_type == 0 && $order->vendor_id) || $jshopConfig->vendor_order_message_type == 3);
		$admin_send_order = ($jshopConfig->admin_not_send_email_order_vendor_order && $vendor_send_order_admin && !empty($listVendors)) ? 0 : 1;

		if (!$isNotifyAdmin) {
			$admin_send_order = false;
		}

		$manuallysend = false;
		$pdfsend = true;        
        $mailfrom = $mainframe->getCfg('mailfrom');
        $fromname = $mainframe->getCfg('fromname');

        $modelOfOrdersStatusFront = JSFactory::getModel('OrdersStatusFront');
        $generatedPdfsInfo = $modelOfOrdersStatusFront->generatePdfsIfOrderStatusSupportIt($order);
		
        $invoiceFileName = JText::_('COM_SMARTSHOP_INVOICE') . '_' . $order->order_number. '.pdf';
        $deliveryNoteFileName = JText::_('COM_SMARTSHOP_DELIVERY_NOTE') . '_' . $order->order_number. '.pdf';

		$isAccessSendOrderEmail = true;
		$dispatcher = \JFactory::getApplication();
		$dispatcher->triggerEvent('onAfterGeneratePdfsWhenChangeOrderStatus', [&$order, &$admin_send_order, &$isAccessSendOrderEmail]);

		if (!$isAccessSendOrderEmail) {
            return;
        }

       //send mail client
	    $app = JFactory::getApplication();
		if (!$app->get('mailonline', 1)){
			\JFactory::getApplication()->enqueueMessage(JText::_('COM_SMARTSHOP_ERROR_SENDING_MAIL'),'error');
			return;
		}
		if ($order->email && ($isNotifyCustomer || $generatedPdfsInfo->supportsPdfOfStatuses->isSupportSendInvoiceToCustomer || $generatedPdfsInfo->supportsPdfOfStatuses->isSupportSendDeliveryNoteToCustomer || $generatedPdfsInfo->supportsPdfOfStatuses->isSupportSendRefundToCustomer)) {
			$mailer = JFactory::getMailer();
			$mailer->setSender([$mailfrom, $fromname]);
			$mailer->addRecipient($order->email);
			$mailer->setSubject($subject);
			
			$dataForTemplate = array('emailSubject'=>$subject, 'emailBod'=>$msgTextForClient);
			$bodyEmailText = renderTemplateEmail('default', $dataForTemplate, 'emails');
			
            $mailer->setBody($bodyEmailText);
			if (!empty($generatedPdfsInfo->invoiceToCustomer)) {
				$mailer->addAttachment($generatedPdfsInfo->invoiceToCustomer, $invoiceFileName);
            }
            
            if (!empty($generatedPdfsInfo->deliveryNoteToCustomer)) {
                $mailer->addAttachment($generatedPdfsInfo->deliveryNoteToCustomer, $deliveryNoteFileName);
            }
            if (!empty($generatedPdfsInfo->refundToCustomer)) {
				foreach($generatedPdfsInfo->refundToCustomer as $k=>$file){					
					$refundFileName = JText::_('COM_SMARTSHOP_REFUND') .'_'.$k. '_' . $order->order_number. '.pdf';
					$mailer->addAttachment($file, $refundFileName);
				}
            }
            
			$mailer->isHTML(true);			
            $dispatcher->triggerEvent('onBeforeSendOrderEmailClient', [&$mailer, &$order, &$manuallysend, &$pdfsend]);
			$mailer->Send();
        }


        //send mail admin
        if (($admin_send_order || $generatedPdfsInfo->supportsPdfOfStatuses->isSupportSendInvoiceToAdmin || $generatedPdfsInfo->supportsPdfOfStatuses->isSupportSendDeliveryNoteToAdmin)) {
            $mailer = JFactory::getMailer();
            $mailer->setSender([$mailfrom, $fromname]);
            $mailer->addRecipient(explode(',', $jshopConfig->contact_email));
            $mailer->setSubject($subject);
			
			$dataForTemplate = array('emailSubject'=>$subject, 'emailBod'=>$msgTextForAdmin);
			$bodyEmailText = renderTemplateEmail('default', $dataForTemplate, 'emails');
			
            $mailer->setBody($bodyEmailText);
            
            if (!empty($generatedPdfsInfo->invoiceToAdministrator)) {
                $mailer->addAttachment($generatedPdfsInfo->invoiceToAdministrator, $invoiceFileName);
            }

            if (!empty($generatedPdfsInfo->deliveryNoteToAdministrator)) {
                $mailer->addAttachment($generatedPdfsInfo->deliveryNoteToAdministrator, $deliveryNoteFileName);
            }

            if (!empty($generatedPdfsInfo->refundToAdministrator)) {
				foreach($generatedPdfsInfo->refundToAdministrator as $k=>$file){					
					$refundFileName[$k] = JText::_('COM_SMARTSHOP_REFUND') .'_'.$k. '_' . $order->order_number. '.pdf';
					$mailer->addAttachment($file, $refundFileName[$k]);
				}
            }

            $mailer->isHTML(true);
            $dispatcher->triggerEvent('onBeforeSendOrderEmailAdmin', [&$mailer, &$order, &$manuallysend, &$pdfsend]);
            $mailer->Send();
            
            $sendInvoiceAlsoForAdditionalAdministrator = (!empty($jshopConfig->additional_admin_invoice_email) && filter_var($jshopConfig->additional_admin_invoice_email, FILTER_VALIDATE_EMAIL) && $generatedPdfsInfo->invoiceToAdministrator);
			$sendDeliveryNoteAlsoForAdditionalAdministrator = (!empty($jshopConfig->additional_admin_delivery_note_email) && filter_var($jshopConfig->additional_admin_delivery_note_email, FILTER_VALIDATE_EMAIL) && $generatedPdfsInfo->deliveryNoteToAdministrator);
			$sendRefundAlsoForAdditionalAdministrator = (!empty($jshopConfig->additional_admin_refund_email) && filter_var($jshopConfig->additional_admin_refund_email, FILTER_VALIDATE_EMAIL) && $generatedPdfsInfo->refundToAdministrator);
            if (($sendInvoiceAlsoForAdditionalAdministrator || $sendDeliveryNoteAlsoForAdditionalAdministrator || $sendRefundAlsoForAdditionalAdministrator) && (!empty($generatedPdfsInfo->invoiceToAdministrator) || !empty($generatedPdfsInfo->deliveryNoteToAdministrator) || !empty($generatedPdfsInfo->refundToAdministrator))) {
                $additionalData = [];
                $key = (($jshopConfig->additional_admin_invoice_email == $jshopConfig->additional_admin_delivery_note_email) && $sendInvoiceAlsoForAdditionalAdministrator && $sendDeliveryNoteAlsoForAdditionalAdministrator) ? 0 : 1;
                $rkey = (($jshopConfig->additional_admin_invoice_email == $jshopConfig->additional_admin_refund_email) && $sendInvoiceAlsoForAdditionalAdministrator && $sendRefundAlsoForAdditionalAdministrator) ? 0 : 2;

                if ($sendInvoiceAlsoForAdditionalAdministrator) {
                    $additionalData['0']['recipient'][] = $jshopConfig->additional_admin_invoice_email;
                    $additionalData['0']['urlToPdf'][] = $generatedPdfsInfo->invoiceToAdministrator;
                    $additionalData['0']['fileName'][] = $invoiceFileName;
                }

                if ($sendDeliveryNoteAlsoForAdditionalAdministrator) {
                    $additionalData[$key]['recipient'][] = $jshopConfig->additional_admin_delivery_note_email;
                    $additionalData[$key]['urlToPdf'][] = $generatedPdfsInfo->deliveryNoteToAdministrator;
                    $additionalData[$key]['fileName'][] = $deliveryNoteFileName;
                }

                if ($sendRefundAlsoForAdditionalAdministrator) {
                    $additionalData[$rkey]['recipient'][] = $jshopConfig->additional_admin_refund_email;
					foreach($generatedPdfsInfo->refundToAdministrator as $k=>$file){						
						$additionalData[$rkey]['urlToPdf'][] = $file;
						$additionalData[$rkey]['fileName'][] = $refundFileName[$k];
					}
                }

                foreach ($additionalData as $data) {
                    $mailer = JFactory::getMailer();
                    $mailer->setSender([$mailfrom, $fromname]);
                    $mailer->addRecipient($data['recipient']);
                    $mailer->setSubject($subject);
                    $mailer->setBody('&nbsp;');
                    $mailer->addAttachment($data['urlToPdf'], $data['fileName']);
                    $mailer->isHTML(true);

                    $mailer->Send();
                }
            }
		}
	}

	public function generatePdfsIfOrderStatusSupportIt($order)
	{
		$result = [
			'invoiceToCustomer' => null,
			'deliveryNoteToCustomer' => null,
			'refundToCustomer' => null,
			'invoiceToAdministrator' => null,
			'deliveryNoteToAdministrator' => null,
			'refundToAdministrator' => null,
			'supportsPdfOfStatuses' => null
		];
		$temp = [];

		$jshopConfig = JSFactory::getConfig();
		$supportsPdfOfStatuses = $this->getSupportsPdfOfStatuses($order);
		
		$file_generete_pdf_order = $jshopConfig->file_generete_pdf_order;

		if ($supportsPdfOfStatuses->isSupportGenerateInvoice || $supportsPdfOfStatuses->isSupportGenerateDeliveryNote) {
            include_once($file_generete_pdf_order);

            if ($supportsPdfOfStatuses->isSupportGenerateDeliveryNote) {
			    $order->setInvoiceDate();
            }

            $order->pdf_file = generatePdf($order, $supportsPdfOfStatuses->isSupportGenerateDeliveryNote, $supportsPdfOfStatuses->isSupportGenerateInvoice);
            $order->insertPDF();
		}
		if ($supportsPdfOfStatuses->isSupportGenerateRefund) {
			$refunds = JSFactory::getModel("refund");
			$refund_list = $refunds->getList($order->order_id);
			$refund_files = [];
			if(!empty($refund_list)){
				include_once($file_generete_pdf_order);
				$folder = $jshopConfig->pdf_orders_path . '/refunds/'. $order->order_id .'/';
				$files = glob($folder . '/*');
				foreach($files as $file){
					if(is_file($file)){
						unlink($file);
					}
				}
				//print_r($refund_list);die;
				foreach($refund_list as $refund){
					if(!$refund->refund_number){
						//$jshopConfig = JSFactory::getConfig();
						$refund->refund_number = $jshopConfig->next_refund_number ?: count(JSFactory::getModel('Refund')->getList($order->order_id)) + 1;
						$jshopConfig->next_refund_number = $refund->refund_number + 1;
						$jshopConfig->updateNextRefundNumber($refund->refund_number + 1);
						$refund->refund_number = $jshopConfig->refund_suffix . $refund->refund_number;
						$refund->refund_date = !empty((int)$refund->refund_date) ? $refund->refund_date : date('Y-m-d');		
						JSFactory::getModel('OrdersFront')->setRefundNumber($refund->refund_id ,$jshopConfig->refund_suffix.$refund->refund_number);
						
					}
//print_r($refund);die;					
					$pdf_file = generatePdfRefund($order, $supportsPdfOfStatuses->isSupportGenerateRefund, $refund);
					JSFactory::getModel('OrdersFront')->setRefundFile($refund->refund_id, $pdf_file);
					$refund_files[] = $pdf_file;
				}
			}
		}

		if ($supportsPdfOfStatuses->isSupportGenerateInvoice || !empty($order->pdf_file)) {
			$temp['invoice'] = $jshopConfig->pdf_orders_path . '/' . $order->pdf_file;
		}

		if ($supportsPdfOfStatuses->isSupportSendDeliveryNoteToCustomer || !empty($order->pdf_file)) {
			$temp['deliveryNote'] = $jshopConfig->pdf_orders_path . '/delivery/' . $order->pdf_file;
		}

		if ($supportsPdfOfStatuses->isSupportSendRefundToCustomer) {
			if(!empty($refund_files)){
				foreach($refund_files as $file){
					$temp['refunds'][] = $jshopConfig->pdf_orders_path . '/refunds/'.$order->order_id. '/' . $file;
				}
			}
		}

		if (!empty($temp['invoice'])) {

			if ($supportsPdfOfStatuses->isSupportSendInvoiceToCustomer) {
				$result['invoiceToCustomer'] = $temp['invoice'];
			}

			if ($supportsPdfOfStatuses->isSupportSendInvoiceToAdmin) {
				$result['invoiceToAdministrator'] = $temp['invoice'];
			}
		}
		
		if (!empty($temp['deliveryNote'])) {

			if ($supportsPdfOfStatuses->isSupportSendDeliveryNoteToCustomer) {
				$result['deliveryNoteToCustomer'] = $temp['deliveryNote'];
			}

			if ($supportsPdfOfStatuses->isSupportSendDeliveryNoteToAdmin) {
				$result['deliveryNoteToAdministrator'] = $temp['deliveryNote'];
			}
		}
		
		if (!empty($temp['refunds'])) {

			if ($supportsPdfOfStatuses->isSupportSendRefundToCustomer) {
				$result['refundToCustomer'] = $temp['refunds'];
			}

			if ($supportsPdfOfStatuses->isSupportSendDeliveryNoteToAdmin) {
				$result['refundToAdministrator'] = $temp['refunds'];
			}
		}

		$result['supportsPdfOfStatuses'] = $supportsPdfOfStatuses;

		return (object)$result;
	}

	public function getSupportsPdfOfStatuses($orderStatus)
	{
		$modelOfOrderStatus = JSFactory::getModel('Orderstatus');
        $orderStatusesWithSupportGenerateInvoice = $modelOfOrderStatus->getAllWitchSupport('is_generate_invoice');
        $orderStatusesWithSupportSendInvoiceToCustomer = $modelOfOrderStatus->getAllWitchSupport('is_send_invoice_to_customer');
        $orderStatusesWithSupportSendInvoiceToAdmin = $modelOfOrderStatus->getAllWitchSupport('is_send_invoice_to_admin');

        $orderStatusesWithSupportGenerateDeliveryNote = $modelOfOrderStatus->getAllWitchSupport('is_generate_delivery_note');
        $orderStatusesWithSupportSendDeliveryNoteToCustomer = $modelOfOrderStatus->getAllWitchSupport('is_send_delivery_note_to_customer');
		$orderStatusesWithSupportSendDeliveryNoteToAdmin = $modelOfOrderStatus->getAllWitchSupport('is_send_delivery_note_to_admin');

        $orderStatusesWithSupportGenerateRefund = $modelOfOrderStatus->getAllWitchSupport('is_generate_refund_note');
        $orderStatusesWithSupportSendRefundToCustomer = $modelOfOrderStatus->getAllWitchSupport('is_send_refund_note_to_customer');
		$orderStatusesWithSupportSendRefundToAdmin = $modelOfOrderStatus->getAllWitchSupport('is_send_refund_note_to_admin');
		
		$result = new StdClass();
		$result->isSupportGenerateInvoice = array_key_exists($orderStatus->order_status, $orderStatusesWithSupportGenerateInvoice);
        $result->isSupportSendInvoiceToCustomer = array_key_exists($orderStatus->order_status, $orderStatusesWithSupportSendInvoiceToCustomer);
        $result->isSupportSendInvoiceToAdmin = array_key_exists($orderStatus->order_status, $orderStatusesWithSupportSendInvoiceToAdmin);

        $result->isSupportGenerateDeliveryNote = array_key_exists($orderStatus->order_status, $orderStatusesWithSupportGenerateDeliveryNote);
        $result->isSupportSendDeliveryNoteToCustomer = array_key_exists($orderStatus->order_status, $orderStatusesWithSupportSendDeliveryNoteToCustomer);
		$result->isSupportSendDeliveryNoteToAdmin = array_key_exists($orderStatus->order_status, $orderStatusesWithSupportSendDeliveryNoteToAdmin);

        $result->isSupportGenerateRefund = array_key_exists($orderStatus->order_status, $orderStatusesWithSupportGenerateRefund);
        $result->isSupportSendRefundToCustomer = array_key_exists($orderStatus->order_status, $orderStatusesWithSupportSendRefundToCustomer);
		$result->isSupportSendRefundToAdmin = array_key_exists($orderStatus->order_status, $orderStatusesWithSupportSendRefundToAdmin);

		return $result;
	}

	public function changeStatus($orderId, int $newStatusId)
	{
		$dispatcher = \JFactory::getApplication();

		$orderTable = JSFactory::getTable('Order');
		$orderTable->load($orderId);
		$oldOrderStatus = $orderTable->order_status;

		$dispatcher->triggerEvent('onBeforeModelChangeOrderStatus', [&$orderId, &$newStatusId, &$oldOrderStatus]);  
		$orderTable->order_status = $newStatusId;
        $isSuccessOrderStore = $orderTable->store();

		$dispatcher->triggerEvent('onAfterModelChangeOrderStatus', [&$orderId, &$newStatusId, &$isSuccessOrderStore, &$oldOrderStatus]);    

        return $isSuccessOrderStore;
	}
}