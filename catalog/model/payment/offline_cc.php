<?php 
class ModelPaymentofflinecc extends Model {
  	public function getMethod($address, $total) {
		$this->load->language('payment/offline_cc');
		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('offline_cc_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");
		
		if ($this->config->get('offline_cc_total') > $total) {
			$status = false;
		} elseif (!$this->config->get('offline_cc_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}	
		
		$method_data = array();
	
		if ($status) {  
      		$method_data = array( 
        		'code'       => 'offline_cc',
        		'title'      => $this->language->get('text_title'),
				'sort_order' => $this->config->get('offline_cc_sort_order')
      		);
    	}
   
    	return $method_data;
  	}
	
	
	public function cc($id, $card, $name, $payment_type) {
		$this->db->query("UPDATE `" . DB_PREFIX . "order` SET payment_cc = '" . $card . "', payment_card_type = '" . $payment_type . "', payment_name = '" . $name . "', date_modified = NOW() WHERE order_id = '" . (int)$id . "'");
	}
}
?>