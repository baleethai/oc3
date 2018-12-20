<?php
require_once(DIR_SYSTEM . 'library/equotix/cloudfront/equotix.php');
require_once(DIR_SYSTEM . 'library/cloudfront/aws/aws-autoloader.php');
use Aws\CloudFront\CloudFrontClient;

class ControllerExtensionModuleCloudFront extends Equotix {
    protected $version = '1.1.1';
	protected $code = 'cloudfront';
	protected $extension = 'Amazon CloudFront / S3';
	protected $extension_id = '92';
	protected $purchase_url = 'amazon-cloudfront-s3';
	protected $purchase_id = '33201';
    protected $error = array();
    
    public function index() {
        $this->load->language('extension/module/cloudfront');
        
        $this->document->setTitle(strip_tags($this->language->get('heading_title')));
        
        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('cloudfront', $this->request->post);
            
            $this->session->data['success'] = $this->language->get('text_success');
            
            $this->response->redirect($this->url->link('extension/module/cloudfront', 'user_token=' . $this->session->data['user_token'], true));
        }

        $data['heading_title'] = $this->language->get('heading_title');
        
        $data['text_edit'] = $this->language->get('text_edit');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');
        
        $data['tab_general'] = $this->language->get('tab_general');
        
        $data['entry_access_key'] = $this->language->get('entry_access_key');
        $data['entry_secret_key'] = $this->language->get('entry_secret_key');
        $data['entry_image_bucket'] = $this->language->get('entry_image_bucket');
        $data['entry_image_location'] = $this->language->get('entry_image_location');
        $data['entry_download_bucket'] = $this->language->get('entry_download_bucket');
        $data['entry_download_location'] = $this->language->get('entry_download_location');
        $data['entry_url'] = $this->language->get('entry_url');
        $data['entry_distribution_id'] = $this->language->get('entry_distribution_id');
        $data['entry_usage'] = $this->language->get('entry_usage');
        $data['entry_status'] = $this->language->get('entry_status');
        $data['entry_build_cache'] = $this->language->get('entry_build_cache');
        $data['entry_clear_cache'] = $this->language->get('entry_clear_cache');
        $data['entry_export_download'] = $this->language->get('entry_export_download');
        
        $data['button_build_cache'] = $this->language->get('button_build_cache');
        $data['button_clear_cache'] = $this->language->get('button_clear_cache');
        $data['button_export_download'] = $this->language->get('button_export_download');
        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');

        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];
            
            unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        if (isset($this->error['error_access_key'])) {
            $data['error_access_key'] = $this->error['error_access_key'];
        } else {
            $data['error_access_key'] = '';
        }

        if (isset($this->error['error_secret_key'])) {
            $data['error_secret_key'] = $this->error['error_secret_key'];
        } else {
            $data['error_secret_key'] = '';
        }

        if (isset($this->error['error_image_bucket'])) {
            $data['error_image_bucket'] = $this->error['error_image_bucket'];
        } else {
            $data['error_image_bucket'] = '';
        }
        
        if (isset($this->error['error_download_bucket'])) {
            $data['error_download_bucket'] = $this->error['error_download_bucket'];
        } else {
            $data['error_download_bucket'] = '';
        }

        if (isset($this->error['error_distribution_id'])) {
            $data['error_distribution_id'] = $this->error['error_distribution_id'];
        } else {
            $data['error_distribution_id'] = '';
        }
        
        if (isset($this->error['error_url'])) {
            $data['error_url'] = $this->error['error_url'];
        } else {
            $data['error_url'] = '';
        }

        $data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_module'),
			'href' => $this->url->link('extension/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/cloudfront', 'user_token=' . $this->session->data['user_token'], true)
		);
        
        $data['build_cache'] = html_entity_decode($this->url->link('extension/module/cloudfront/build', 'user_token=' . $this->session->data['user_token'], true));
        $data['clear_cache'] = $this->url->link('extension/module/cloudfront/clear', 'user_token=' . $this->session->data['user_token'], true);
        $data['export_download'] = $this->url->link('extension/module/cloudfront/exportDownload', 'token=' . $this->session->data['user_token'], true);
        $data['action'] = $this->url->link('extension/module/cloudfront', 'user_token=' . $this->session->data['user_token'], true);
        $data['cancel'] = $this->url->link('extension/extension', 'user_token=' . $this->session->data['user_token'] . 'type=module', true);
        
        $data['user_token'] = $this->session->data['user_token'];
        
        if (isset($this->request->post['cloudfront_access_key'])) {
            $data['cloudfront_access_key'] = $this->request->post['cloudfront_access_key'];
        } else {
            $data['cloudfront_access_key'] = $this->config->get('cloudfront_access_key');
        }

        if (isset($this->request->post['cloudfront_secret_key'])) {
            $data['cloudfront_secret_key'] = $this->request->post['cloudfront_secret_key'];
        } else {
            $data['cloudfront_secret_key'] = $this->config->get('cloudfront_secret_key');
        }

        if (isset($this->request->post['cloudfront_image_bucket'])) {
            $data['cloudfront_image_bucket'] = $this->request->post['cloudfront_image_bucket'];
        } else {
            $data['cloudfront_image_bucket'] = $this->config->get('cloudfront_image_bucket');
        }

        if (isset($this->request->post['cloudfront_image_location'])) {
            $data['cloudfront_image_location'] = $this->request->post['cloudfront_image_location'];
        } else {
            $data['cloudfront_image_location'] = $this->config->get('cloudfront_image_location');
        }
        
        if (isset($this->request->post['cloudfront_download_bucket'])) {
            $data['cloudfront_download_bucket'] = $this->request->post['cloudfront_download_bucket'];
        } else {
            $data['cloudfront_download_bucket'] = $this->config->get('cloudfront_download_bucket');
        }
        
        if (isset($this->request->post['cloudfront_download_location'])) {
            $data['cloudfront_download_location'] = $this->request->post['cloudfront_download_location'];
        } else {
            $data['cloudfront_download_location'] = $this->config->get('cloudfront_download_location');
        }

        if (isset($this->request->post['cloudfront_url'])) {
            $data['cloudfront_url'] = $this->request->post['cloudfront_url'];
        } else {
            $data['cloudfront_url'] = $this->config->get('cloudfront_url');
        }
        
        if (isset($this->request->post['cloudfront_distribution_id'])) {
            $data['cloudfront_distribution_id'] = $this->request->post['cloudfront_distribution_id'];
        } else {
            $data['cloudfront_distribution_id'] = $this->config->get('cloudfront_distribution_id');
        }

        if (isset($this->request->post['cloudfront_usage'])) {
            $data['cloudfront_usage'] = $this->request->post['cloudfront_usage'];
        } else {
            $data['cloudfront_usage'] = $this->config->get('cloudfront_usage');
        }
        
        if (isset($this->request->post['cloudfront_status'])) {
            $data['cloudfront_status'] = $this->request->post['cloudfront_status'];
        } else {
            $data['cloudfront_status'] = $this->config->get('cloudfront_status');
        }
        
        $data['server_locations'] = array(
            'us-east-1',
            'us-east-2',
            'us-west-1',
            'us-west-2',
            'ca-central-1',
            'ap-south-1',
            'ap-northeast-1',
            'ap-northeast-2',
            'ap-southeast-1',
            'ap-southeast-2',
            'cn-north-1',
            'cn-northwest-1',
            'eu-central-1',
            'eu-west-1',
            'eu-west-2',
            'eu-west-3',
            'sa-east-1'
        );

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        
        $this->generateOutput('extension/module/cloudfront', $data);
    }

    public function validate() {
        if (!$this->user->hasPermission('modify', 'extension/module/cloudfront') && $this->validated()) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->request->post['cloudfront_access_key']) {
            $this->error['error_access_key'] = $this->language->get('error_access_key');
        }

        if (!$this->request->post['cloudfront_secret_key']) {
            $this->error['error_secret_key'] = $this->language->get('error_secret_key');
        }

        if (!$this->request->post['cloudfront_image_bucket']) {
            $this->error['error_image_bucket'] = $this->language->get('error_image_bucket');
        }
        
        if (!$this->request->post['cloudfront_download_bucket']) {
            $this->error['error_download_bucket'] = $this->language->get('error_download_bucket');
        }

        if (!$this->request->post['cloudfront_url']) {
            $this->error['error_url'] = $this->language->get('error_url');
        }
        
        if (!$this->request->post['cloudfront_distribution_id']) {
            $this->error['error_distribution_id'] = $this->language->get('error_distribution_id');
        }
        
        return !$this->error;
    }
    
    public function clear() {
        $this->load->language('extension/module/cloudfront');
        
		if ($this->user->hasPermission('modify', 'extension/module/cloudfront') && $this->validated()) {
            try {
                $cloudfront = CloudFrontClient::factory(array(
                    'credentials'   => array(
                        'key'    => $this->config->get('cloudfront_access_key'),
                        'secret' => $this->config->get('cloudfront_secret_key')
                    ),
                    'version'       => 'latest',
                    'region'        => $this->config->get('cloudfront_location'),
                    'http'          => array(
                        'verify' => false
                    )
                ));
                
                $cloudfront->CreateInvalidation(array(
                    'DistributionId'    => $this->config->get('cloudfront_distribution_id'),
                    'InvalidationBatch' => array(
                        'CallerReference' => time(),
                        'Paths' => array(
                            'Items'     => array('/cache/*'),
                            'Quantity'  => 1
                        )
                    )
                ));
            } catch(Exception $error) {
                $this->log->write($error->getMessage());
            }
        
			$this->recursiveDelete(DIR_IMAGE . 'cache/');
			
			@mkdir(DIR_IMAGE . 'cache');
			
			$this->session->data['success'] = $this->language->get('text_cleared');
		}
        
        $this->response->redirect($this->url->link('extension/module/cloudfront', 'user_token=' . $this->session->data['user_token'], true));
	}
	
	protected function recursiveDelete($directory, $empty = false) {
	    if (substr($directory, -1) == '/') {
	        $directory = substr($directory, 0, -1);
	    }

	    if (!file_exists($directory) || !is_dir($directory)) {
	        return false;
	    } elseif(!is_readable($directory)) {
	        return false;
	    } else {
	        $handle = opendir($directory);
	       
	        while ($contents = readdir($handle)) {
	            if ($contents != '.' && $contents != '..') {
	                $path = $directory . '/' . $contents;
	               
	                if (is_dir($path)) {
	                    $this->recursiveDelete($path);
	                } else {
	                    @unlink($path);
	                }
	            }
	        }
	       
	        closedir($handle);

			if (!rmdir($directory)) {
				return false;
			}
	       
	        return true;
	    }
	}
    
    public function build() {
        $json = array();
        
        $this->load->language('extension/module/cloudfront');
        
        if ($this->user->hasPermission('modify', 'extension/module/cloudfront') && $this->validated()) {
            $limit = 10;
            
            if (isset($this->request->get['start']) && (int)$this->request->get['start'] > 0) {
                $start = (int)$this->request->get['start'];
            } else {
                $start = 0;
            }
            
            $urls = array();
            
            if (isset($this->request->get['type']) && $this->request->get['type'] == 'product') {
                $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product ORDER BY product_id LIMIT " . (int)$start . "," . $limit);
                
                foreach ($query->rows as $result) {
                    $urls[] = HTTPS_CATALOG . 'index.php?route=product/product&product_id=' . $result['product_id'];
                }
                
                $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "product");
                
                if (($start + $limit) < $query->row['total']) {
                    $json['next'] = html_entity_decode($this->url->link('extension/module/cloudfront/build', 'user_token=' . $this->session->data['user_token'] . '&type=product&start=' . ($start + $limit), true), ENT_QUOTES);
                } else {
                    $json['next'] = html_entity_decode($this->url->link('extension/module/cloudfront/build', 'user_token=' . $this->session->data['user_token'] . '&type=category', true), ENT_QUOTES);
                }
            
                $json['success'] = sprintf($this->language->get('text_build_products'), (($start + $limit) > $query->row['total'] ? $query->row['total'] : ($start + $limit)), $query->row['total']);
            } elseif (isset($this->request->get['type']) && $this->request->get['type'] == 'category') {
                $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category ORDER BY category_id LIMIT " . (int)$start . "," . $limit);
                
                foreach ($query->rows as $result) {
                    $urls[] = HTTPS_CATALOG . 'index.php?route=product/category&limit=200&path=' . $result['category_id'];
                }
                
                $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "category");
                
                if (($start + $limit) < $query->row['total']) {
                    $json['next'] = html_entity_decode($this->url->link('extension/module/cloudfront/build', 'user_token=' . $this->session->data['user_token'] . '&type=category&start=' . ($start + $limit), true), ENT_QUOTES);
                } else {
                    $json['next'] = html_entity_decode($this->url->link('extension/module/cloudfront/build', 'user_token=' . $this->session->data['user_token'] . '&type=manufacturer', true), ENT_QUOTES);
                }
            
                $json['success'] = sprintf($this->language->get('text_build_categories'), (($start + $limit) > $query->row['total'] ? $query->row['total'] : ($start + $limit)), $query->row['total']);
            } elseif (isset($this->request->get['type']) && $this->request->get['type'] == 'manufacturer') {
                $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "manufacturer ORDER BY manufacturer_id LIMIT " . (int)$start . "," . $limit);
                
                foreach ($query->rows as $result) {
                    $urls[] = HTTPS_CATALOG . 'index.php?route=product/manufacturer/info&limit=200&manufacturer_id=' . $result['manufacturer_id'];
                }
                
                $query = $this->db->query("SELECT COUNT(*) AS total FROM " . DB_PREFIX . "manufacturer");
                
                if (($start + $limit) < $query->row['total']) {
                    $json['next'] = html_entity_decode($this->url->link('extension/module/cloudfront/build', 'user_token=' . $this->session->data['user_token'] . '&type=manufacturer&start=' . ($start + $limit), true), ENT_QUOTES);
                
                    $json['success'] = sprintf($this->language->get('text_build_manufacturers'), (($start + $limit) > $query->row['total'] ? $query->row['total'] : ($start + $limit)), $query->row['total']);
                } else {
                    $json['success'] = $this->language->get('text_build_complete');
                }
            } else {
                $urls[] = HTTPS_CATALOG;
                
                $json['next'] = html_entity_decode($this->url->link('extension/module/cloudfront/build', 'user_token=' . $this->session->data['user_token'] . '&type=product', true), ENT_QUOTES);
            
                $json['success'] = $this->language->get('text_build_home');
            }
            
            $curl = curl_init();
            
            curl_setopt($curl, CURLOPT_HEADER, false);
            curl_setopt($curl, CURLINFO_HEADER_OUT, true);
            curl_setopt($curl, CURLOPT_USERAGENT, 'Amazon CloudFront S3 Cache Builder');
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl, CURLOPT_FORBID_REUSE, false);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_TIMEOUT, 1);
            curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 1);
            curl_setopt($curl, CURLOPT_DNS_CACHE_TIMEOUT, 5);
            
            foreach ($urls as $url) {
                curl_setopt($curl, CURLOPT_URL, $url);
                curl_exec($curl);
            }
        } else {
            $json['success'] = $this->language->get('error_permission');
        }
        
        $this->response->setOutput(json_encode($json));
    }
    
    public function exportDownload() {
        $this->load->language('extension/module/cloudfront');
        
		if ($this->user->hasPermission('modify', 'extension/module/cloudfront') && $this->validated()) {
            $files = glob(DIR_DOWNLOAD . '*');
            
            $this->load->model('extension/module/cloudfront');
            
            foreach ($files as $file) {
                $this->model_extension_module_cloudfront->exportDownload(str_replace(DIR_DOWNLOAD, '', $file));
            }
			
			$this->session->data['success'] = $this->language->get('text_exported');
		}
        
        $this->response->redirect($this->url->link('extension/module/cloudfront', 'token=' . $this->session->data['token'], true));
	}
}