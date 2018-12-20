<?php

class ControllerHomeIndex extends Controller {

    public function index() {
        s($this->request->get['page']);
    }

    public function test() {
        
        $queries = array();
        s(parse_str($_SERVER['QUERY_STRING'], $queries));

		// s($this->request->get['page']);
        // exit;
        
        $this->load->model('fox/home');
        $products = $this->model_fox_home->all();
        s($products);
    }

}
