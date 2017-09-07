<?php
class ModelExtensionModuleBestsellerPerCategory extends Model {
	public function getBestSellerProductsPerCategory($limit, $category_id = null, $manufacturer_id = null) {
		if ($this->customer->isLogged()) {
			$customer_group_id = $this->customer->getGroupId();
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}	
				
		$product_data = $this->cache->get('product.bestsellerpercategory.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id'). '.' . $customer_group_id . '.' . (int)$limit . '.' . (int)$limit . ($category_id ? (int)$category_id : '') . '.' . (int)$limit . ($manufacturer_id ? (int)$manufacturer_id : ''));

		if (!$product_data) { 
			$product_data = array();
			
			$sql = "SELECT op.product_id, COUNT(*) AS total FROM " . DB_PREFIX . "order_product op LEFT JOIN `" . DB_PREFIX . "order` o ON (op.order_id = o.order_id) LEFT JOIN `" . DB_PREFIX . "product` p ON (op.product_id = p.product_id)";
			
			foreach ($query->rows as $result) { 		
				$product_data[$result['product_id']] = $this->model_catalog_product->getProduct($result['product_id']);
			}
			$this->cache->set('product.bestsellerpercategory.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id'). '.' . $customer_group_id . '.' . (int)$limit . '.' . (int)$limit . ($category_id ? (int)$category_id : '') . '.' . (int)$limit . ($manufacturer_id ? (int)$manufacturer_id : ''), $product_data);

		}
		return $product_data;
	}
	
}
?>