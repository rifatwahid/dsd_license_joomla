<?php
/**
* @version      4.1.0 13.02.2011
* @author       
* @package     smartSHOP
* @copyright    Copyright (C) 2010. All rights reserved.
* @license      GNU/GPL
*/

defined('_JEXEC') or die('Restricted access');
jimport( 'joomla.application.component.model');

class JshoppingModelReviews extends JModelLegacy
{
    const TABLE_NAME = '#__jshopping_products_reviews';
    
    public function getAllReviews($category_id = null, $product_id = null, $limitstart = null, $limit = null, $text_search = null, $result = "list", $order = null, $orderDir = null) {
        $dispatcher = \JFactory::getApplication();        	        		
        $lang = JSFactory::getLang();
        $db = \JFactory::getDBO(); 
        $where = "";
        if ($product_id) $where .= " AND pr_rew.product_id='".$db->escape($product_id)."' ";
		$dispatcher->triggerEvent('onBeforeModelGetAllProductsAfterWhereSet', array(&$where));		
        
        if($limit > 0) {
            $limit = " LIMIT " . $limitstart . " , " . $limit;
        }
        $where .= ($text_search) ? ( " AND CONCAT_WS('|',pr.`".$lang->get('name')."`,pr.`".$lang->get('short_description')."`,pr.`".$lang->get('description')."`,pr_rew.review, pr_rew.user_name, pr_rew.user_email ) LIKE '%".$db->escape($text_search)."%' " ) : ('');
        $ordering = 'pr_rew.review_id desc';
        
        if ($order && $orderDir){
            $ordering = $order." ".$orderDir;
        }
        
        if($category_id) {   
            $query = "select pr.`".$lang->get('name')."` as name,pr_rew.* , DATE_FORMAT(pr_rew.`time`,'%d.%m.%Y') as dateadd 
            from  #__jshopping_products_reviews as pr_rew
            LEFT JOIN #__jshopping_products  as pr USING (product_id)
            LEFT JOIN `#__jshopping_products_to_categories` AS pr_cat USING (product_id)
            WHERE pr_cat.category_id = '" . $db->escape($category_id) . "' ".$where." ORDER BY ". $ordering ." ". $limit;
        }else {
            $query = "select pr.`".$lang->get('name')."` as name,pr_rew.*, DATE_FORMAT(pr_rew.`time`,'%d.%m.%Y') as dateadd 
            from  #__jshopping_products_reviews as pr_rew
            LEFT JOIN #__jshopping_products  as pr USING (product_id)            
            WHERE 1 ".$where." ORDER BY ". $ordering ." ". $limit;
        }
        extract(js_add_trigger(get_defined_vars(), "before"));
        $db->setQuery($query);
        if ($result=="list"){
            return $db->loadObjectList();
        }else{
            $db->execute();
            return $db->getNumRows();    
        }
    }
    
    public function getReview($id){
        $db = \JFactory::getDBO();
        $lang = JSFactory::getLang();   
        $query = "select pr_rew.*, pr.`".$lang->get('name')."` as name from #__jshopping_products_reviews as pr_rew LEFT JOIN #__jshopping_products  as pr USING (product_id)  where pr_rew.review_id = '$id'";
        $db->setQuery($query); 
        return $db->loadObject(); 
    }
    
    public function getProdNameById($id){
        $db = \JFactory::getDBO();
        $lang = JSFactory::getLang();   
        $query = "select pr.`".$lang->get('name')."` as name from #__jshopping_products  as pr where pr.product_id = '$id' LIMIT 1";
        $db->setQuery($query); 
        return $db->loadResult(); 
    }
    
    public function deleteReview($id){
		$jshopConfig = JSFactory::getConfig();
        $db = \JFactory::getDBO(); 
		$query = "select * from #__jshopping_products_reviews where review_id = '$id'";
        $db->setQuery($query); 
        $review=$db->loadObject(); 
		unlink($jshopConfig->files_product_review_path .'/'.$review->reviewfile);
		unlink($jshopConfig->files_product_review_path .'/thumb_'.$review->reviewfile);
		unlink($jshopConfig->files_product_review_path .'/full_'.$review->reviewfile);
        $query = "delete from #__jshopping_products_reviews where review_id = '$id'";
        $db->setQuery($query);
        return $db->execute();
    }

    public function selectReviewStars($selected)
    {
        $options = [
            JHtml::_('select.option', '0', JText::_('COM_JSHOPPING_CONFIGURATION_RATING_STARPARTS_HALF'), 'id', 'name'),
            JHtml::_('select.option', '1', JText::_('COM_JSHOPPING_CONFIGURATION_RATING_STARPARTS_FULL'), 'id', 'name')
        ];
        
        return JHtml::_('select.genericlist', $options, 'rating_starparts', 'class="inputbox form-select"', 'id', 'name', $selected);
    }
	public function setPublishById($flag,$id){
		$db = \JFactory::getDBO();
		$query = "UPDATE `#__jshopping_products_reviews` SET `publish` = '".$db->escape($flag)."' WHERE `review_id` = '".$db->escape($id)."'";
		$db->setQuery($query);
		$db->execute();
    }
    
    public function deleteAttachedFile($reviewId, $name): bool
    {
        $isDelete = false;

        if (!empty($reviewId) && !empty($name)) {
            
            try {
                $reviewTable = JSFactory::getTable('review');
                $reviewTable->load($reviewId);

                if (!empty($reviewTable->reviewfile)) {
                    $reviewFiles = explode('|', $reviewTable->reviewfile) ?: [];

                    if (in_array($name, $reviewFiles)) {
                        $key = array_search($name, $reviewFiles);

                        if ( $key !== false) {
                            unset($reviewFiles[$key]);

                            $reviewTable->bind([
                                'reviewfile' => implode('|', $reviewFiles)
                            ]);

                            $isDelete = $reviewTable->store();

                            if ($isDelete) {
                                $config = JSFactory::getConfig();
                                $pathToFile = $config->files_product_review_path . '/';

                                JFile::delete($pathToFile . $name);
                                JFile::delete($pathToFile . 'full_' . $name);
                                JFile::delete($pathToFile . 'thumb_' . $name);
                            }
                        }
                    }
                }
            } catch(\Exception $e) {
                $isDelete = false;
            }

        }

        return $isDelete;
    }

    public function getAllByProdId($productId, $columns = ['*'])
    {
        $prodReviews = [];

        if (!empty($productId) && !empty($columns)) {
            $db = \JFactory::getDBO();
            $selectColumns = implode(', ', $columns);
            $selectProdReviews = "SELECT {$selectColumns} FROM {$db->qn(self::TABLE_NAME)} WHERE `product_id` = {$productId}";

            $db->setQuery($selectProdReviews);
            $prodReviews = $db->loadObjectList() ?: [];
        }

        return $prodReviews;
    }

    public function deleteByProductId($productId)
    {
        $result = true;

        if (!empty($productId)) {
            $db = \JFactory::getDBO();
            $sql = "DELETE FROM {$db->qn(self::TABLE_NAME)} WHERE `product_id` = {$productId}";

            $db->setQuery($sql);
            $result = $db->execute();
        }

        return $result;
    }

    public function deleteFilesByProdId($productId)
    {
        $result = true;

        if (!empty($productId)) {
            $prodReviews = $this->getAllByProdId($productId, ['reviewfile']);

            if (!empty($prodReviews)) {
                $jshopConfig = JSFactory::getConfig();

                foreach ($prodReviews as $review) {
                    if (!empty($review->reviewfile)) {
                        $reviewFiles = explode('|', $review->reviewfile);

                        if (!empty($reviewFiles)) {
                            foreach ($reviewFiles as $fileName) {
                                unlink("{$jshopConfig->files_product_review_path}/{$fileName}");
                                unlink("{$jshopConfig->files_product_review_path}/thumb_{$fileName}");
                                unlink("{$jshopConfig->files_product_review_path}/full_{$fileName}");
                            }
                        }
                    }
                }
            }
        }

        return $result;
    }
}
