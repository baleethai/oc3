<?php

require __DIR__ . '\core.php';

class ModelFoxHome extends ModelFoxCore {
    
    public function all() {
       
        // $row = QB::table('customer')->select('*')->get();
        $query = QB::table('product')
                    ->select($this->filters('product'));
                    // ->join('product_description', 'product.product_id', '=', 'product_description.product_id')
                    // ->select('product_description.name')
                    // ->join('product_image', 'product_image.product_id', '=', 'product.product_id')
                    // ->select($this->filters('product_image'));
        $query->orderBy('product.product_id', 'ASC');
        $query->limit(2);
        $query->offset(0);

        return $query->get();
    }

    public function add($data) {
        $data = array(
            'name'        => 'Sana',
            'description' => 'Blah'
        );
        $insertId = QB::table('product')->insert($data);
    }

    public function edit() {

    }

    public function delete() {

    }
}