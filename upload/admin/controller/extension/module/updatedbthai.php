<?php
class ControllerExtensionModuleUpdatedbthai extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/module/updatedbthai');
		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/updatedbthai');
        $result = $this->model_catalog_updatedbthai->chk_translate();
        if (isset($result['name'])) {
			$data['thai_translated'] = 'no';
        } else {
			 $data['thai_translated'] = 'yes';
		}
 
		if (($this->request->server['REQUEST_METHOD'] == 'POST')  ) {
		     $this->model_catalog_updatedbthai->addlanguage();
			 $this->model_catalog_updatedbthai->update();
 
			 $this->session->data['success'] = $this->language->get('text_success');

			 $this->response->redirect($this->url->link('extension/module/updatedbthai', 'user_token=' . $this->session->data['user_token'],true));
		}

		$data['heading_title'] = $this->language->get('heading_title');
        $data['text_update'] = $this->language->get('text_update');
		 $data['text_translated'] = $this->language->get('text_translated');
		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');


		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'],true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_module'),
			'href' => $this->url->link('extension/extension', 'user_token=' . $this->session->data['user_token'],true)
		);

 
		$data['action'] = $this->url->link('extension/module/updatedbthai', 'user_token=' . $this->session->data['user_token'],true);
 
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/updatedbthai', $data));
	}
 
}