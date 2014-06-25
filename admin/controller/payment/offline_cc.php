<?php 
class ControllerPaymentofflinecc extends Controller {
	private $error = array(); 

	public function index() {
		$this->load->language('payment/offline_cc');
		
		$this->load->model('payment/offline_cc');
		$this->model_payment_offline_cc->check_cc_field();

		$this->document->setTitle($this->language->get('heading_title'));
		
		$this->load->model('setting/setting');
			
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('offline_cc', $this->request->post);				
			
			$this->session->data['success'] = $this->language->get('text_success');

			$this->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$this->data['heading_title'] = $this->language->get('heading_title');

		$this->data['text_enabled'] = $this->language->get('text_enabled');
		$this->data['text_disabled'] = $this->language->get('text_disabled');
		$this->data['text_all_zones'] = $this->language->get('text_all_zones');
		$this->data['text_authorization'] = $this->language->get('text_authorization');
		$this->data['text_capture'] = $this->language->get('text_capture');		
		
	
		$this->data['entry_total'] = $this->language->get('entry_total');	
		$this->data['entry_order_status'] = $this->language->get('entry_order_status');		
		$this->data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$this->data['entry_status'] = $this->language->get('entry_status');
		$this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$this->data['entry_email'] = $this->language->get('entry_email');
		$this->data['entry_encryption'] = $this->language->get('entry_encryption');
		
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');

		$this->data['tab_general'] = $this->language->get('tab_general');

 		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		
  		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => false
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_payment'),
			'href'      => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('heading_title'),
			'href'      => $this->url->link('payment/offline_cc', 'token=' . $this->session->data['token'], 'SSL'),
      		'separator' => ' :: '
   		);
				
		$this->data['action'] = $this->url->link('payment/offline_cc', 'token=' . $this->session->data['token'], 'SSL');
		
		$this->data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL');
		
		if (isset($this->request->post['offline_cc_total'])) {
			$this->data['offline_cc_total'] = $this->request->post['offline_cc_total'];
		} else {
			$this->data['offline_cc_total'] = $this->config->get('offline_cc_total'); 
		} 
				
		if (isset($this->request->post['offline_cc_order_status_id'])) {
			$this->data['offline_cc_order_status_id'] = $this->request->post['offline_cc_order_status_id'];
		} else {
			$this->data['offline_cc_order_status_id'] = $this->config->get('offline_cc_order_status_id'); 
		} 

		$this->load->model('localisation/order_status');
		
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		if (isset($this->request->post['offline_cc_geo_zone_id'])) {
			$this->data['offline_cc_geo_zone_id'] = $this->request->post['offline_cc_geo_zone_id'];
		} else {
			$this->data['offline_cc_geo_zone_id'] = $this->config->get('offline_cc_geo_zone_id'); 
		} 
		
		$this->load->model('localisation/geo_zone');
										
		$this->data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();
		
		if (isset($this->request->post['offline_cc_status'])) {
			$this->data['offline_cc_status'] = $this->request->post['offline_cc_status'];
		} else {
			$this->data['offline_cc_status'] = $this->config->get('offline_cc_status');
		}
		
		if (isset($this->request->post['offline_cc_sort_order'])) {
			$this->data['offline_cc_sort_order'] = $this->request->post['offline_cc_sort_order'];
		} else {
			$this->data['offline_cc_sort_order'] = $this->config->get('offline_cc_sort_order');
		}
		
		if (isset($this->request->post['offline_email'])) {
			$this->data['offline_email'] = $this->request->post['offline_email'];
		} else {
			$this->data['offline_email'] = $this->config->get('offline_email'); 
		}
		
		if (isset($this->request->post['offline_encryption'])) {
			$this->data['offline_encryption'] = $this->request->post['offline_encryption'];
		} else {
			$this->data['offline_encryption'] = $this->config->get('offline_encryption'); 
		}
		
		
		if (isset($this->request->post['entry_use_cc_name'])) {
			$this->data['entry_use_cc_name'] = $this->request->post['entry_use_cc_name'];
		} else {
			$this->data['entry_use_cc_name'] = $this->config->get('entry_use_cc_name'); 
		}
		
		if (isset($this->request->post['entry_use_cc_type'])) {
			$this->data['entry_use_cc_type'] = $this->request->post['entry_use_cc_type'];
		} else {
			$this->data['entry_use_cc_type'] = $this->config->get('entry_use_cc_type'); 
		}
		
		if (isset($this->request->post['entry_accept_visa'])) {
			$this->data['entry_accept_visa'] = $this->request->post['entry_accept_visa'];
		} else {
			$this->data['entry_accept_visa'] = $this->config->get('entry_accept_visa'); 
		}
		
		if (isset($this->request->post['entry_accept_master'])) {
			$this->data['entry_accept_master'] = $this->request->post['entry_accept_master'];
		} else {
			$this->data['entry_accept_master'] = $this->config->get('entry_accept_master'); 
		}
		
		if (isset($this->request->post['entry_accept_ae'])) {
			$this->data['entry_accept_ae'] = $this->request->post['entry_accept_ae'];
		} else {
			$this->data['entry_accept_ae'] = $this->config->get('entry_accept_ae'); 
		}
		
		if (isset($this->request->post['entry_accept_cu'])) {
			$this->data['entry_accept_cu'] = $this->request->post['entry_accept_cu'];
		} else {
			$this->data['entry_accept_cu'] = $this->config->get('entry_accept_cu'); 
		}
		
		if (isset($this->request->post['entry_accept_jcb'])) {
			$this->data['entry_accept_jcb'] = $this->request->post['entry_accept_jcb'];
		} else {
			$this->data['entry_accept_jcb'] = $this->config->get('entry_accept_jcb'); 
		}
		 

		$this->template = 'payment/offline_cc.tpl';
		$this->children = array(
			'common/header',
			'common/footer',
		);
				
		$this->response->setOutput($this->render());
	}

	private function validate() {
		if (!$this->user->hasPermission('modify', 'payment/offline_cc')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}
		
		
		if (!$this->error) {
			return true;
		} else {
			return false;
		}	
	}
}
?>