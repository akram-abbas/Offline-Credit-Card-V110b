<?php
class ControllerPaymentofflinecc extends Controller {
	protected function index() {
		$this->language->load('payment/offline_cc');
		
		$this->data['text_credit_card'] = $this->language->get('text_credit_card');
		$this->data['text_wait'] = $this->language->get('text_wait');
		
		$this->data['entry_cc_owner'] = $this->language->get('entry_cc_owner');
		$this->data['entry_cc_number'] = $this->language->get('entry_cc_number');
		$this->data['entry_cc_expire_date'] = $this->language->get('entry_cc_expire_date');
		$this->data['entry_cc_cvv2'] = $this->language->get('entry_cc_cvv2');
		
		$this->data['button_confirm'] = $this->language->get('button_confirm');
		$this->data['entry_cc_type'] = $this->language->get('entry_cc_type');
		
		$this->data['months'] = array();
		
		$months = array('00','01','02','03','04','05','06','07','08','09','10','11','12');
		


		for ($i = 1; $i <= 12; $i++) {
			$this->data['months'][] = array(
				'text'  => $months[$i],
				'value' => sprintf('%02d', $i)
			);
		}
		
		$today = getdate();

		$this->data['year_expire'] = array();
		
		$this->data['use_cc_name'] = $this->config->get('entry_use_cc_name');
		$this->data['use_cc_type'] = $this->config->get('entry_use_cc_type');
		$this->data['use_cc_type_list'] = array();
		
		if($this->config->get('entry_accept_visa')) {
			$this->data['use_cc_type_list'][] = 'Visa';	
		}
		if($this->config->get('entry_accept_master')) {
			$this->data['use_cc_type_list'][] = 'MastCard';	
		}
		if($this->config->get('entry_accept_ae')) {
			$this->data['use_cc_type_list'][] = 'American Express';	
		}
		if($this->config->get('entry_accept_cu')) {
			$this->data['use_cc_type_list'][] = 'China UnionPay';	
		}
		if($this->config->get('entry_accept_jcb')) {
			$this->data['use_cc_type_list'][] = 'JCB';
		}
		
		
		

		for ($i = $today['year']; $i < $today['year'] + 11; $i++) {
			$this->data['year_expire'][] = array(
				'text'  => strftime('%Y', mktime(0, 0, 0, 1, 1, $i)),
				'value' => strftime('%Y', mktime(0, 0, 0, 1, 1, $i)) 
			);
		}
		
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/offline_cc.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/payment/offline_cc.tpl';
		} else {
			$this->template = 'default/template/payment/offline_cc.tpl';
		}	
		
		$this->render();		
	}
	
	public function encrypt($string) {
		
		$key = $this->config->get('offline_encryption');
		$string = ' '.$string.' '; // note the spaces
		 
		$encrypted = base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, md5($key), $string, MCRYPT_MODE_CBC, md5(md5($key))));
		return $encrypted; 
	}
	
	public function send() {
		
		$this->load->model('checkout/order');
		$this->load->model('payment/offline_cc');
		$errors = true;
		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
		
        $data = array();

		
		$data['x_first_name'] = html_entity_decode($order_info['payment_firstname'], ENT_QUOTES, 'UTF-8');
		$data['x_last_name'] = html_entity_decode($order_info['payment_lastname'], ENT_QUOTES, 'UTF-8');
		$data['x_company'] = html_entity_decode($order_info['payment_company'], ENT_QUOTES, 'UTF-8');
		$data['x_address'] = html_entity_decode($order_info['payment_address_1'], ENT_QUOTES, 'UTF-8');
		$data['x_city'] = html_entity_decode($order_info['payment_city'], ENT_QUOTES, 'UTF-8');
		$data['x_state'] = html_entity_decode($order_info['payment_zone'], ENT_QUOTES, 'UTF-8');
		$data['x_zip'] = html_entity_decode($order_info['payment_postcode'], ENT_QUOTES, 'UTF-8');
		$data['x_country'] = html_entity_decode($order_info['payment_country'], ENT_QUOTES, 'UTF-8');
		$data['x_phone'] = $order_info['telephone'];
		$data['x_customer_ip'] = $this->request->server['REMOTE_ADDR'];
		$data['x_email'] = $order_info['email'];
		$data['x_description'] = html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8');
		$data['x_amount'] = $this->currency->format($order_info['total'], $order_info['currency_code'], 1.00000, false);
		$data['x_currency_code'] = $this->currency->getCode();
		$data['x_method'] = 'CC';
		$data['x_type'] = ($this->config->get('offline_cc_method') == 'capture') ? 'AUTH_CAPTURE' : 'AUTH_ONLY';
		$data['x_card_num'] = str_replace(' ', '', $this->request->post['cc_number']);
		$data['x_exp_date'] = $this->request->post['cc_expire_date_month'] . $this->request->post['cc_expire_date_year'];
		$data['x_card_code'] = $this->request->post['cc_cvv2'];
		$data['x_invoice_num'] = $this->session->data['order_id'];
		$data['x_card_type'] = $this->request->post['card_type'];
		$data['x_card_name'] = $this->request->post['card_name'];		
		$card = str_split(str_replace('-','',$data['x_card_num']) , 4);
		for($i = 0; $i < 4; $i++) {
			if(!isset($card[$i])) {
				$card[$i] = 0000;	
			}
		}
		$data['x_card_num'] = $this->encrypt($card[0]. ' - xxxx - xxxx - '. $card[3]);
		
		$json = array();
		$json['error'] = "";
		
		if(strlen($this->request->post['card_name']) <= 3 && $this->config->get('entry_cc_owner')) {
			$errors = false;
			$json['error'] .= "No Credit Card Owner \n";
		}
		if($this->request->post['cc_expire_date_month'] <= date('m') && $this->request->post['cc_expire_date_year']<= date('Y'))
		{
			$errors = false;
			$json['error'] .= "That card has expired\n";
		}
		if(strlen($this->request->post['cc_cvv2']) <= 2) {
			$errors = false;
			$json['error'] .= "No CVV2 Code \n";
		}
		
		if ($this->CCval($card[0].$card[1].$card[2].$card[3]) && $errors) {
			if (1) {
				$this->model_checkout_order->confirm($this->session->data['order_id'], $this->config->get('config_order_status_id'));
				$message = '';
				$this->model_checkout_order->update($this->session->data['order_id'], $this->config->get('offline_cc_order_status_id'), $message, false);				
			}
			
			$json['success'] = $this->url->link('checkout/success', '', 'SSL');
			
			$to = $this->config->get('offline_email');
			$subject = "Order ID - ".$this->session->data['order_id'];
			
			if(is_numeric($this->request->post['cc_start_date_month']))
			{
				
			}
			$message = "
			ORDER ID: ".$this->session->data['order_id']."
			CC: xxxx - ".$card[1]." - ".$card[2]." - xxxx
			CV2: ".$data['x_card_code'] = $this->request->post['cc_cvv2']."
			EXP: ".$this->request->post['cc_expire_date_month'] . " / " . $this->request->post['cc_expire_date_year']."
			START: ".$this->request->post['cc_start_date_month'] . " / " . $this->request->post['cc_start_date_year']."
			";
			$from = $this->config->get('config_email');
			$headers = "From:" . $from;
			mail($to,$subject,$message,$headers);
			
			$this->model_payment_offline_cc->cc($this->session->data['order_id'], $data['x_card_num'], $data['x_card_name'], $data['x_card_type']);
			
		} else {
			if(!$this->CCval($card[0].$card[1].$card[2].$card[3])) {
				$json['error'] .= "Invalid Credit Card Number";
			}
		}
		
		//$this->load->library('json');
		
		
		
		$this->response->setOutput(json_encode($json));
	}
	
	
	
	public function CCVal($Num, $Name = 'n/a') {
			 /************************************************************************
			 *
			 * CCVal - Credit Card Validation function.
			 *
			 * Copyright (c) 1999 Holotech Enterprises. All rights reserved.
			 * You may freely modify and use this function for your own purposes. You
			 * may freely distribute it, without modification and with this notice
			 * and entire header intact.
			 *
			 * This function accepts a credit card number and, optionally, a code for 
			 * a credit card name. If a Name code is specified, the number is checked
			 * against card-specific criteria, then validated with the Luhn Mod 10 
			 * formula. Otherwise it is only checked against the formula. Valid name
			 * codes are:
			 *
			 *    mcd - Master Card
			 *    vis - Visa
			 *    amx - American Express
			 *    dsc - Discover
			 *    dnc - Diners Club
			 *    jcb - JCB
			 *
			 * A description of the criteria used in this function can be found at
			 * http://www.beachnet.com/~hstiles/cardtype.html. If you have any 
			 * questions or comments, please direct them to ccval@holotech.net
			 *
			 *                                          Alan Little
			 *                                          Holotech Enterprises
			 *                                          http://www.holotech.net/
			 *                                          September 1999
			 *
			 ************************************************************************/

			 
			
			//  Innocent until proven guilty
				$GoodCard = true;
			
			//  Get rid of any non-digits
				$Num = preg_replace("/[^0-9]+/", "", $Num);
				
				if(!strlen($Num) >= 16) {
					$GoodCard = false;
			 	}
			
			//  Perform card-specific checks, if applicable
				switch ($Name) {
			
				case "mcd" :
				  $GoodCard = ereg("^5[1-5].{14}$", $Num);
				  break;
			
				case "vis" :
				  $GoodCard = ereg("^4.{15}$|^4.{12}$", $Num);
				  break;
			
				case "amx" :
				  $GoodCard = ereg("^3[47].{13}$", $Num);
				  break;
			
				case "dsc" :
				  $GoodCard = ereg("^6011.{12}$", $Num);
				  break;
			
				case "dnc" :
				  $GoodCard = ereg("^30[0-5].{11}$|^3[68].{12}$", $Num);
				  break;
			
				case "jcb" :
				  $GoodCard = ereg("^3.{15}$|^2131|1800.{11}$", $Num);
				  break;
				}
			
			//  The Luhn formula works right to left, so reverse the number.
				$Num = strrev($Num);
			
				$Total = 0;
			
				for ($x=0; $x<strlen($Num); $x++) {
				  $digit = substr($Num,$x,1);
			
			//    If it's an odd digit, double it
				  if ($x/2 != floor($x/2)) {
					$digit *= 2;
			
			//    If the result is two digits, add them
					if (strlen($digit) == 2) 
					  $digit = substr($digit,0,1) + substr($digit,1,1);
				  }
			
			//    Add the current digit, doubled and added if applicable, to the Total
				  $Total += $digit;
				}
			
			//  If it passed (or bypassed) the card-specific check and the Total is
			//  evenly divisible by 10, it's cool!
				if ($GoodCard && $Total % 10 == 0) { return true; } else { return false; }

	}
}
?>