<?php
// Version 1.0.1
if (!class_exists('Equotix')) {
	class Equotix extends Controller {
		public function generateOutput($file, $data = array()) {
			$data['license'] =  array(
				'license_key'		=> $this->config->get($this->code . '_license_license_key'),
				'order_id'			=> $this->config->get($this->code . '_license_order_id'),
				'name' 				=> $this->config->get($this->code . '_license_name'),
				'date_purchased' 	=> $this->config->get($this->code . '_license_date_purchased'),
				'date_expired' 		=> $this->config->get($this->code . '_license_date_expired'),
				'domains' 			=> $this->config->get($this->code . '_license_domains') ? $this->config->get($this->code . '_license_domains') : array()
			);

			$data['services'] = $this->config->get($this->code . '_license_services');
			
			if (version_compare(VERSION, '3.0.0.0', '>=')) {
				$folder = 'extension/module';
				
				$data['equotix_token'] = '&user_token=' . $this->session->data['user_token'];
			} elseif (version_compare(VERSION, '2.3.0.0', '>=')) {
				$folder = 'extension/module';
				
				$data['equotix_token'] = '&token=' . $this->session->data['token'];
			} else {
				$folder = 'module';
				
				$data['equotix_token'] = '&token=' . $this->session->data['token'];
			}
			
			$data['folder'] = isset($this->folder) ? $this->folder : $folder;
			$data['code'] = $this->code;
			$data['purchase_url'] = $this->purchase_url;
			$data['extension'] = $this->extension;
			$data['version'] = $this->version;
			
			$this->callbackServer($this->config->get($this->code . '_license_license_key'), false);
			
			$data['about'] = $this->getTabButton();
			$data['tab'] = $this->getTabContent($data);
			
			if (!empty($this->request->server['HTTPS'])) {
				$base = str_replace('http://', 'https://', (defined('HTTPS_CATALOG') ? HTTPS_CATALOG : HTTP_CATALOG) . 'system/library/equotix/' . $this->code . '/');
			} else {
				$base = str_replace('https://', 'http://', (defined('HTTPS_CATALOG') ? HTTPS_CATALOG : HTTP_CATALOG) . 'system/library/equotix/' . $this->code . '/');
			}
			
			$search = array(
				'view/javascript/jquery/jquery-1.7.1.min.js',
				'view/javascript/jquery/jquery-1.6.1.min.js',
				'https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js',
				'<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">'
			);
			
			$replace = array(
				$base . 'js/jquery-1.11.3.min.js',
				$base . 'js/jquery-1.11.3.min.js',
				$base . 'js/jquery-1.11.3.min.js',
				'<!DOCTYPE html>'
			);
			
			if (version_compare(VERSION, '2.0.0.0', '>=')) {
				$this->response->setOutput(str_replace($search, $replace, $this->load->view($file, $data)));
			} else {
				$this->document->addStyle($base . 'bootstrap/css/bootstrap.min.css');
				$this->document->addStyle($base . 'fontawesome/css/font-awesome.min.css');
				$this->document->addStyle($base . 'css/equotix.css');
				$this->document->addScript($base . 'bootstrap/js/bootstrap.min.js');
				$this->document->addScript($base . 'js/jquery-migrate-1.2.1.min.js');
				$this->document->addScript($base . 'js/equotix.js');
				
				$this->data = array_merge($this->data, $data);
				
				$this->template = $file;
				$this->children = array(
					'common/header',
					'common/footer'
				);

				$this->response->setOutput(str_replace($search, $replace, $this->render()));
			}
		}
		
		public function validateLicense() {
			$json = array();
			
			$license_key = isset($this->request->post['license_key']) ? $this->request->post['license_key'] : '';

			$license_info = $this->callbackServer($license_key, true);
			
			// if ($license_info['success']) {
			// 	$json['success'] = true;
			// } else {
			// 	$json['error'] = $license_info['error'];
			// }
            
            $json['success'] = true;
			
			$this->response->setOutput(json_encode($json));
		}
		
		public function checkUpdate() {
			$json = array();
			
			$post_data = array(
				'license_key'	=> $this->config->get($this->code . '_license_license_key'),
				'version'		=> $this->version
			);

			$curl = curl_init();
			
			curl_setopt($curl, CURLOPT_HEADER, false);
			curl_setopt($curl, CURLINFO_HEADER_OUT, true);
			curl_setopt($curl, CURLOPT_USERAGENT, 'OpenCart Extension Licensing System');
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($curl, CURLOPT_FORBID_REUSE, false);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_URL, 'https://license.marketinsg.com/index.php?load=common/home/checkUpdate');
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post_data));

			$response = curl_exec($curl);

			curl_close($curl);
			
			$license_info = json_decode($response, true);

			if (isset($license_info['message'])) {
				$json['type'] = $license_info['type'];
				$json['message'] = $license_info['message'];
			} else {
				$json['type'] = 'alert-danger';
				$json['message'] = 'We are unable to retrieve your license information. Please open a support ticket if you require assistance.';
			}
			
			$this->response->setOutput(json_encode($json));
		}
		
		private function saveSetting($group, $data) {
			if (version_compare(VERSION, '2.0.0.0', '>')) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "setting WHERE store_id = '0' AND `code` = '" . $this->db->escape($group) . "'");

				foreach ($data as $key => $value) {
					if (!is_array($value)) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = '" . $this->db->escape($group) . "', `key` = '" . $this->db->escape($key) . "', `value` = '" . $this->db->escape($value) . "'");
					} else {
						if (version_compare(VERSION, '2.1.0.0', '>=')) {
							$value = json_encode($value);
						} else {
							$value = serialize($value);
						}
					
						$this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `code` = '" . $this->db->escape($group) . "', `key` = '" . $this->db->escape($key) . "', `value` = '" . $this->db->escape($value) . "', serialized = '1'");
					}
				}
			} else {
				$this->db->query("DELETE FROM " . DB_PREFIX . "setting WHERE store_id = '0' AND `group` = '" . $this->db->escape($group) . "'");

				foreach ($data as $key => $value) {
					if (!is_array($value)) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `group` = '" . $this->db->escape($group) . "', `key` = '" . $this->db->escape($key) . "', `value` = '" . $this->db->escape($value) . "'");
					} else {
						$this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '0', `group` = '" . $this->db->escape($group) . "', `key` = '" . $this->db->escape($key) . "', `value` = '" . $this->db->escape(serialize($value)) . "', serialized = '1'");
					}
				}
			}
		}
		
		protected function validated() {
			$this->callbackServer($this->config->get($this->code . '_license_license_key'), false);
		
			return $this->config->get($this->code . '_license_license_key') ? true : false;
		}
		
		protected function callbackServer($license_key, $reload) {
			$domain = parse_url(HTTP_SERVER);
			$domain = $domain['host'];
			$domain = str_replace('www.', '', $domain);
			$domain = str_replace('http://', '', $domain);
			$domain = str_replace('https://', '', $domain);
			$domain = str_replace('/', '', $domain);
			$domain = strtolower($domain);
			
			if (is_array($this->config->get($this->code . '_license_domains'))) {
				$search = array();
				
				foreach ($this->config->get($this->code . '_license_domains') as $licensed_domain) {
					$search[] = $licensed_domain['domain'];
				}
				
				if (!in_array($domain, $search)) {
					$data = array(
						$this->code . '_license_license_key'	=> '',
						$this->code . '_license_order_id'		=> '',
						$this->code . '_license_name'			=> '',
						$this->code . '_license_date_purchased'	=> '',
						$this->code . '_license_date_expired'	=> '',
						$this->code . '_license_domains'		=> array(),
						$this->code . '_license_check'			=> time(),
						$this->code . '_license_services'		=> ''
					);
					
					$this->saveSetting($this->code . '_license', $data);
				}
			}
			
			if (!$reload) {
				if ($this->config->get($this->code . '_license_check') && ($this->config->get($this->code . '_license_check') + 432000) >= time()) {
					return;
				}
			}
			
			$post_data = array(
				'license_key'	=> $license_key,
				'extension_id'	=> $this->extension_id,
				'domain'		=> $domain
			);

			$curl = curl_init();
			
			curl_setopt($curl, CURLOPT_HEADER, false);
			curl_setopt($curl, CURLINFO_HEADER_OUT, true);
			curl_setopt($curl, CURLOPT_USERAGENT, 'OpenCart Extension Licensing System');
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($curl, CURLOPT_FORBID_REUSE, false);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curl, CURLOPT_URL, 'https://license.marketinsg.com/index.php?load=common/home/validatelicense');
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post_data));

			$response = curl_exec($curl);
			
			if (curl_errno($curl)) {
				$curl_error = 'Error ' . curl_errno($curl) . ': ' . curl_error($curl);
			} else {
				$curl_error = '';
			}

			curl_close($curl);
			
			$response = json_decode($response, true);

			if (isset($response['error'])) {
				$data = array(
					$this->code . '_license_license_key'	=> '',
					$this->code . '_license_order_id'		=> '',
					$this->code . '_license_name'			=> '',
					$this->code . '_license_date_purchased'	=> '',
					$this->code . '_license_date_expired'	=> '',
					$this->code . '_license_domains'		=> array(),
					$this->code . '_license_check'			=> time(),
					$this->code . '_license_services'		=> ''
				);
				
				$this->saveSetting($this->code . '_license', $data);
				
				return array(
					'error' 	=> $response['error'],
					'success' 	=> false
				);
			} elseif(isset($response['success'])) {
				$data = array(
					$this->code . '_license_license_key'	=> $response['license_key'],
					$this->code . '_license_order_id'		=> $response['order_id'],
					$this->code . '_license_name'			=> $response['name'],
					$this->code . '_license_date_purchased'	=> $response['date_purchased'],
					$this->code . '_license_date_expired'	=> $response['date_expired'],
					$this->code . '_license_domains'		=> $response['domains'],
					$this->code . '_license_check'			=> time(),
					$this->code . '_license_services'		=> $response['services']
				);
				
				$this->saveSetting($this->code . '_license', $data);
				
				return array(
					'error'				=> false,
					'success'			=> true
				);
			} else {
				$this->log->write(print_r($response, true));
				
				return array(
					'error'		=> base64_decode('VW5hYmxlIHRvIGNvbm5lY3QgdG8gdGhlIGxpY2Vuc2luZyBzZXJ2ZXIuICBQbGVhc2UgZW5zdXJlIHlvdXIgc2VydmVyIGlzIGNvbm5lY3RlZCB0byB0aGUgaW50ZXJuZXQu') . ' ' . $curl_error,
					'success'	=> false
				);
			}
		}
		
		protected function getTabButton() {
			$html = '<li><a href="#tab-about" data-toggle="tab"><i class="fa fa-question-circle"></i> About</a></li>';
		
			return $html;
		}
		
		protected function getTabContent($data) {
			foreach ($data as $key => $value) {
				${$key} = $value;
			}
			
			ob_start();
			
			require_once('equotix.tpl');
			
			return ob_get_clean();
		}
	}
}