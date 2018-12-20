<?php
class ModelCatalogUpdatedbthai extends Model {
 
	public function chk_translate() {
		$query = $this->db->query("SELECT name FROM `" . DB_PREFIX . "zone` WHERE `country_id` = '209' and name = 'Bangkok' ");
        return $query->row;
	}

	public function addlanguage() {
        $data['name']=   'Thai'; 
        $data['code'] = 'th'; 
		$data['directory'] = 'thai'; 
        $data['sort_order'] ='0';
        $data['status'] = '1';
        $data['image'] = 'th.png';
        $data['locale'] = 'en_TH.UTF-8,en_TH,en-th,thai'; 
		
         $language_id = $this->db->getLastId();
		 $query = $this->db->query("DELETE  FROM `" . DB_PREFIX . "language` WHERE `directory` = 'thai'  ");
 
		 $this->db->query("INSERT INTO " . DB_PREFIX . "language SET language_id = " . (int)$language_id . "  , name = '" . $this->db->escape($data['name']) . "', code = '" . $this->db->escape($data['code']) . "', locale = '" . $this->db->escape($data['locale']) . "', directory = '" . $this->db->escape($data['directory']) . "', image = '" . $this->db->escape($data['image']) . "', sort_order = '" . $this->db->escape($data['sort_order']) . "', status = '" . (int)$data['status'] . "'");

		 $this->cache->delete('language');
         $language_id = $this->db->getLastId();
		
      
		// Attribute
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "attribute_description WHERE language_id = '1' 
                                   and attribute_id not in (select attribute_id FROM " . DB_PREFIX . "attribute_description WHERE language_id =   " . (int)$language_id . "    )
		");
             
		foreach ($query->rows as $attribute) {
						$this->db->query("INSERT INTO " . DB_PREFIX . "attribute_description 
						SET attribute_id = '" . (int)$attribute['attribute_id'] . "', 
						language_id = '" . (int)$language_id . "',
						name = '" . $this->db->escape($attribute['name']) . "'  ");
		}

		// Attribute Group
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "attribute_group_description WHERE language_id = '1' 
		                           and  attribute_group_id  not in (select attribute_group_id FROM " . DB_PREFIX . "attribute_group_description WHERE language_id =   " . (int)$language_id . "   )
		");

		foreach ($query->rows as $attribute_group) {
 
				     $this->db->query("INSERT INTO " . DB_PREFIX . "attribute_group_description SET attribute_group_id = '" . (int)$attribute_group['attribute_group_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($attribute_group['name']) . "'");
		}

		$this->cache->delete('attribute');
		
		// Banner
		if(version_compare(VERSION, '2.2.1', '>')) {
			
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "banner_image WHERE language_id = '1'  
									   and  banner_image_id  not in (select banner_image_id   FROM " . DB_PREFIX . "banner_image  WHERE language_id =  " . (int)$language_id . "    )
			");

			foreach ($query->rows as $banner_image) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "banner_image  SET banner_id = '" . (int)$banner_image['banner_id'] . "', language_id = '" . (int)$language_id . "', link = '" . $this->db->escape($banner_image['link']) . "'
				, image= '" . $this->db->escape($banner_image['image']) . "'
				
				");
			}
			
			
		} else { 
		
			$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "banner_image_description WHERE language_id = '1'  
									   and  banner_image_id  not in (select banner_image_id   FROM " . DB_PREFIX . "banner_image_description WHERE language_id =  " . (int)$language_id . "   )
			");

			foreach ($query->rows as $banner_image) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "banner_image_description SET banner_image_id = '" . (int)$banner_image['banner_image_id'] . "', banner_id = '" . (int)$banner_image['banner_id'] . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($banner_image['title']) . "'");
			}
        }
		
		$this->cache->delete('banner');
       
		// Category
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "category_description WHERE language_id = '1'");

		foreach ($query->rows as $category) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "category_description SET category_id = '" . (int)$category['category_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($category['name']) . "', meta_description = '" . $this->db->escape($category['meta_description']) . "', meta_keyword = '" . $this->db->escape($category['meta_keyword']) . "', description = '" . $this->db->escape($category['description']) . "'");
		}

		$this->cache->delete('category');

		// Customer Group
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "customer_group_description WHERE language_id = '1'");

		foreach ($query->rows as $customer_group) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "customer_group_description SET customer_group_id = '" . (int)$customer_group['customer_group_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($customer_group['name']) . "', description = '" . $this->db->escape($customer_group['description']) . "'");
		}

		// Custom Field
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "custom_field_description WHERE language_id = '1'");

		foreach ($query->rows as $custom_field) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "custom_field_description SET custom_field_id = '" . (int)$custom_field['custom_field_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($custom_field['name']) . "'");
		}

		// Custom Field Value
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "custom_field_value_description WHERE language_id = '1'");

		foreach ($query->rows as $custom_field_value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "custom_field_value_description SET custom_field_value_id = '" . (int)$custom_field_value['custom_field_value_id'] . "', language_id = '" . (int)$language_id . "', custom_field_id = '" . (int)$custom_field_value['custom_field_id'] . "', name = '" . $this->db->escape($custom_field_value['name']) . "'");
		}

		// Download
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "download_description WHERE language_id = '1'");

		foreach ($query->rows as $download) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "download_description SET download_id = '" . (int)$download['download_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($download['name']) . "'");
		}

		// Filter
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "filter_description WHERE language_id = '1'");

		foreach ($query->rows as $filter) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "filter_description SET filter_id = '" . (int)$filter['filter_id'] . "', language_id = '" . (int)$language_id . "', filter_group_id = '" . (int)$filter['filter_group_id'] . "', name = '" . $this->db->escape($filter['name']) . "'");
		}

		// Filter Group
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "filter_group_description WHERE language_id = '1'");

		foreach ($query->rows as $filter_group) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "filter_group_description SET filter_group_id = '" . (int)$filter_group['filter_group_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($filter_group['name']) . "'");
		}

		// Information
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "information_description WHERE language_id = '1'");

		foreach ($query->rows as $information) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "information_description SET information_id = '" . (int)$information['information_id'] . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($information['title']) . "', description = '" . $this->db->escape($information['description']) . "'");
		}

		$this->cache->delete('information');

		// Length
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "length_class_description WHERE language_id = '1'");

		foreach ($query->rows as $length) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "length_class_description SET length_class_id = '" . (int)$length['length_class_id'] . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($length['title']) . "', unit = '" . $this->db->escape($length['unit']) . "'");
		}

		$this->cache->delete('length_class');

		// Option
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "option_description WHERE language_id = '1'");

		foreach ($query->rows as $option) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "option_description SET option_id = '" . (int)$option['option_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($option['name']) . "'");
		}

		// Option Value
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "option_value_description WHERE language_id = '1'");

		foreach ($query->rows as $option_value) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "option_value_description SET option_value_id = '" . (int)$option_value['option_value_id'] . "', language_id = '" . (int)$language_id . "', option_id = '" . (int)$option_value['option_id'] . "', name = '" . $this->db->escape($option_value['name']) . "'");
		}
 

		// Product
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_description WHERE language_id = '1'");

		foreach ($query->rows as $product) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "product_description SET product_id = '" . (int)$product['product_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($product['name']) . "', meta_description = '" . $this->db->escape($product['meta_description']) . "', meta_keyword = '" . $this->db->escape($product['meta_keyword']) . "', description = '" . $this->db->escape($product['description']) . "', tag = '" . $this->db->escape($product['tag']) . "'");
		}

		$this->cache->delete('product');

		// Product Attribute
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "product_attribute WHERE language_id = '1'");

		foreach ($query->rows as $product_attribute) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "product_attribute SET product_id = '" . (int)$product_attribute['product_id'] . "', attribute_id = '" . (int)$product_attribute['attribute_id'] . "', language_id = '" . (int)$language_id . "', text = '" . $this->db->escape($product_attribute['text']) . "'");
		}

		// Return Action
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "return_action WHERE language_id = '1'");

		foreach ($query->rows as $return_action) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "return_action SET return_action_id = '" . (int)$return_action['return_action_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($return_action['name']) . "'");
		}

		// Return Reason
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "return_reason WHERE language_id = '1'");

		foreach ($query->rows as $return_reason) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "return_reason SET return_reason_id = '" . (int)$return_reason['return_reason_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($return_reason['name']) . "'");
		}

		// Return Status
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "return_status WHERE language_id = '1'");

		foreach ($query->rows as $return_status) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "return_status SET return_status_id = '" . (int)$return_status['return_status_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($return_status['name']) . "'");
		}

		// Stock Status
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "stock_status WHERE language_id = '1'");

		foreach ($query->rows as $stock_status) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "stock_status SET stock_status_id = '" . (int)$stock_status['stock_status_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($stock_status['name']) . "'");
		}

		$this->cache->delete('stock_status');

		// Voucher Theme
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "voucher_theme_description WHERE language_id = '1'");

		foreach ($query->rows as $voucher_theme) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "voucher_theme_description SET voucher_theme_id = '" . (int)$voucher_theme['voucher_theme_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($voucher_theme['name']) . "'");
		}

		$this->cache->delete('voucher_theme');

		// Weight Class
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "weight_class_description WHERE language_id = '1'");

		foreach ($query->rows as $weight_class) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "weight_class_description SET weight_class_id = '" . (int)$weight_class['weight_class_id'] . "', language_id = '" . (int)$language_id . "', title = '" . $this->db->escape($weight_class['title']) . "', unit = '" . $this->db->escape($weight_class['unit']) . "'");
		}

		$this->cache->delete('weight_class');

		// Profiles
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "recurring_description WHERE language_id = '1'");

		foreach ($query->rows as $recurring) {
			$this->db->query("INSERT INTO " . DB_PREFIX . "recurring_description SET recurring_id = '" . (int)$recurring['recurring_id'] . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($recurring['name']));
		}
	}


	
	public function update() {
 
	 	 $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "language  WHERE  name ='Thai'  ");
		 $language_id =  $query->row['language_id'];
	 	 $query = $this->db->query("DELETE FROM `" . DB_PREFIX . "order_status` WHERE `language_id` =  '" . (int)$language_id . "'   ");
         $query = $this->db->query("INSERT INTO  `" . DB_PREFIX . "order_status`
		   (`order_status_id`, `language_id`, `name`) VALUES
			(2,   " . (int)$language_id . "   , 'อยู่ระหว่างดำเนินการ'),
			(3,   " . (int)$language_id . "  , 'จัดส่งแล้ว'),
			(7,   " . (int)$language_id . "  , 'ยกเลิกคำสั่งซื้อ'),
			(5,   " . (int)$language_id . "  , 'เสร็จสมบูรณ์'),
			(9,   " . (int)$language_id . "  , 'คืนเงินกลับทางร้าน'),
			(10,  " . (int)$language_id . "  , 'การชำระเงินไม่ผ่าน'),
			(11,   " . (int)$language_id . "   , 'คืนเงินลูกค้า'),
			(12,   " . (int)$language_id . "   , 'การชำระเงินถูกยกเลิก'),
			(13,   " . (int)$language_id . "  , 'ลูกค้าดึงเงินคืน'),
			(16,   " . (int)$language_id . "   , 'ถูกยกเลิก'),
			(14,  " . (int)$language_id . "  , 'เกินกำหนด'),
			(8,    " . (int)$language_id . "  , 'ปฏิเสธการจ่ายเงิน'),
			(15,   " . (int)$language_id . "    , 'ชำระเงินแล้ว'), 
			(1,   " . (int)$language_id . "  , 'ได้รับคำสั่งซื้อ')
		 ");  


        $query = $this->db->query("Delete FROM `" . DB_PREFIX . "zone` WHERE `country_id` = '209'  ");  
		$query = $this->db->query("INSERT INTO  `" . DB_PREFIX . "zone`
	    (`zone_id`, `country_id`, `name`, `code`, `status`) VALUES	
		(3189, 209, 'อำนาจเจริญ', 'อำนาจเจริญ', 1),
		(3190, 209, 'อ่างทอง', 'อ่างทอง', 1),
		(3191, 209, 'อยุธยา', 'อยุธยา', 1),
		(3192, 209, 'กรุงเทพฯ', 'กรุงเทพฯ', 1),
		(3193, 209, 'บุรีรัมย์', 'บุรีรัมย์', 1),
		(3194, 209, 'ฉะเชิงเทรา', 'ฉะเชิงเทรา', 1),
		(3195, 209, 'ชัยนาท', 'ชัยนาท', 1),
		(3196, 209, 'ชัยภูมิ', 'ชัยภูมิ', 1),
		(3197, 209, 'จันทบุรี', 'จันทบุรี', 1),
		(3198, 209, 'เชียงใหม่', 'เชียงใหม่', 1),
		(3199, 209, 'เชียงราย', 'เชียงราย', 1),
		(3200, 209, 'ชลบุรี', 'ชลบุรี', 1),
		(3201, 209, 'ชุมพร', 'ชุมพร', 1),
		(3202, 209, 'กาฬสินธุ์', 'กาฬสินธุ์', 1),
		(3203, 209, 'กำแพงเพชร', 'กำแพงเพชร', 1),
		(3204, 209, 'กาญจนบุรี', 'กาญจนบุรี', 1),
		(3205, 209, 'ขอนแก่น', 'ขอนแก่น', 1),
		(3206, 209, 'กระบี่', 'กระบี่', 1),
		(3207, 209, 'ลำปาง', 'ลำปาง', 1),
		(3208, 209, 'ลำพูน', 'ลำพูน', 1),
		(3209, 209, 'เลย', 'เลย', 1),
		(3210, 209, 'ลพบุรี', 'ลพบุรี', 1),
		(3211, 209, 'แม่ฮ่องสอน', 'แม่ฮ่องสอน', 1),
		(3212, 209, 'มหาสารคาม', 'มหาสารคาม', 1),
		(3213, 209, 'มุกดาหาร', 'มุกดาหาร', 1),
		(3214, 209, 'นครนายก', 'นครนายก', 1),
		(3215, 209, 'นครปฐม', 'นครปฐม', 1),
		(3216, 209, 'นครพนม', 'นครพนม', 1),
		(3217, 209, 'นครราชสีมา', 'นครราชสีมา', 1),
		(3218, 209, 'นครสวรรค์', 'นครสวรรค์', 1),
		(3219, 209, 'นครศรีธรรมราช', 'นครศรีธรรมราช', 1),
		(3220, 209, 'น่าน', 'น่าน', 1),
		(3221, 209, 'นราธิวาส', 'นราธิวาส', 1),
		(3222, 209, 'หนองบัวลำพู', 'หนองบัวลำพู', 1),
		(3223, 209, 'หนองคาย', 'หนองคาย', 1),
		(3224, 209, 'นนทบุรี', 'นนทบุรี', 1),
		(3225, 209, 'ปทุมธานี', 'ปทุมธานี', 1),
		(3226, 209, 'ปัตตานี', 'ปัตตานี', 1),
		(3227, 209, 'พังงา', 'พังงา', 1),
		(3228, 209, 'พัทลุง', 'พัทลุง', 1),
		(3229, 209, 'พะเยา', 'พะเยา', 1),
		(3230, 209, 'เพชรบูรณ์', 'เพชรบูรณ์', 1),
		(3231, 209, 'เพชรบุรี', 'เพชรบุรี', 1),
		(3232, 209, 'พิจิตร', 'พิจิตร', 1),
		(3233, 209, 'พิษณุโลก', 'พิษณุโลก', 1),
		(3234, 209, 'แพร่', 'แพร่', 1),
		(3235, 209, 'ภูเก็ต', 'ภูเก็ต', 1),
		(3236, 209, 'ปราจีนบุรี', 'ปราจีนบุรี', 1),
		(3237, 209, 'ประจวบคิริขันธ์', 'ประจวบคิริขันธ์', 1),
		(3238, 209, 'ระนอง', 'ระนอง', 1),
		(3239, 209, 'ราชบุรี', 'ราชบุรี', 1),
		(3240, 209, 'ระยอง', 'ระยอง', 1),
		(3241, 209, 'ร้อยเอ็ด', 'ร้อยเอ็ด', 1),
		(3242, 209, 'สระแก้ว', 'สระแก้ว', 1),
		(3243, 209, 'สกลนคร', 'สกลนคร', 1),
		(3244, 209, 'สมุทรปราการ', 'สมุทรปราการ', 1),
		(3245, 209, 'สมุทรสาคร', 'สมุทรสาคร', 1),
		(3246, 209, 'สมุทรสงคราม', 'สมุทรสงคราม', 1),
		(3247, 209, 'สระบุรี', 'สระบุรี', 1),
		(3248, 209, 'สตูล', 'สตูล', 1),
		(3249, 209, 'สิงห์บุรี', 'สิงห์บุรี', 1),
		(3250, 209, 'ศรีสะเกษ', 'ศรีสะเกษ', 1),
		(3251, 209, 'สงขลา', 'สงขลา', 1),
		(3252, 209, 'สุโขทัย', 'สุโขทัย', 1),
		(3253, 209, 'สุพรรณบุรี', 'สุพรรณบุรี', 1),
		(3254, 209, 'สุราษฏร์ธานี', 'สุราษฏร์ธานี', 1),
		(3255, 209, 'สุรินทร์', 'สุรินทร์', 1),
		(3256, 209, 'ตาก', 'ตาก', 1),
		(3257, 209, 'ตรัง', 'ตรัง', 1),
		(3258, 209, 'ตราด', 'ตราด', 1),
		(3259, 209, 'อุบลราชธานี', 'อุบลราชธานี', 1),
		(3260, 209, 'อุดรธานี', 'อุดรธานี', 1),
		(3261, 209, 'อุทัยธานี', 'อุทัยธานี', 1),
		(3262, 209, 'อุตรดิตถ์', 'อุตรดิตถ์', 1),
		(3263, 209, 'ยะลา', 'ยะลา', 1),
		(3264, 209, 'ยโสธร', 'ยโสธร', 1)		
		");


       $query = $this->db->query("UPDATE  `" . DB_PREFIX . "setting`  set value ='th' where  `key` = 'config_admin_language' ");
	   $query = $this->db->query("UPDATE  `" . DB_PREFIX . "setting`  set value ='th' where  `key` = 'config_language' ");
	   $query = $this->db->query("UPDATE  `" . DB_PREFIX . "setting`  set value ='3192' where  `key` = 'config_zone_id' ");
	   $query = $this->db->query("UPDATE  `" . DB_PREFIX . "setting`  set value ='209' where  `key` = 'config_country_id' ");

 

	}	
		 
}