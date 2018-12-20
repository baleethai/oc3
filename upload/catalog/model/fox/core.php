<?php

class ModelFoxCore extends Model {
    
	public function __construct() {
        // Create a connection, once only.
        $config = array(
            'driver'    => DB_DRIVER,
            'host'      => DB_HOSTNAME,
            'database'  => DB_DATABASE,
            'username'  => DB_USERNAME,
            'password'  => DB_PASSWORD,
            'charset'   => 'utf8', // Optional
            'collation' => 'utf8_unicode_ci', // Optional
            'prefix'    => DB_PREFIX, // Table prefix, optional
            'options'   => array( // PDO constructor options, optional
                PDO::ATTR_TIMEOUT => 5,
                PDO::ATTR_EMULATE_PREPARES => false,
            ),
        );
        new \Pixie\Connection('mysql', $config, 'QB');
    }

    public function filters($table = null) {

        switch ($table) {
            case 'product':
                return $this->getFilterProduct();
                break;
                case 'product_image':
                return $this->getFilterProductImage();
                break;                
            default:
                return array();
                break;
        }
    }

    public function getFilterProduct() {
        return array (
            'product.product_id',
            // 'product.price',
            // 'product.quantity',
            // 'product.sku',
            // 'product.image'
        );
    }

    public function getFilterProductImage() {
        return array (
            'product_image.image'
        );
    }
}