<?php
class ModelPaymentofflinecc extends Model {	
	
	public function check_cc_field() {
		$result = $this->db->query("
			SHOW columns from `".DB_PREFIX."order` where field='payment_cc'"
		);
		
		if(!$result->num_rows) {
			$this->db->query("
				ALTER TABLE `".DB_PREFIX."order` ADD `payment_cc` VARCHAR( 100 ) NOT NULL ;
			");
			
		}
		
		$result = $this->db->query("
			SHOW columns from `".DB_PREFIX."order` where field='payment_name'"
		);
		
		if(!$result->num_rows) {
			
			$this->db->query("
				ALTER TABLE `".DB_PREFIX."order` ADD `payment_name` VARCHAR( 100 ) NOT NULL ;
			");
		}
		
		$result = $this->db->query("
			SHOW columns from `".DB_PREFIX."order` where field='payment_card_type'"
		);
		
		if(!$result->num_rows) {
			
			$this->db->query("
				ALTER TABLE `".DB_PREFIX."order` ADD `payment_card_type` VARCHAR( 100 ) NOT NULL ;
			");
		}
		
	}
}

?>