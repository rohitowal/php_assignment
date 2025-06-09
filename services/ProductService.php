<?php

namespace Services;
include_once(__DIR__ . '/../config/dbconfig.php'); 

use Repositories\ProductRepository;
use Models\Product;

/**
 * Class ProductService
 * 
 * Service layer for handling product-related business logic.
 * Provides CRUD operations for products and manages data validation.
 */
class ProductService {

    private $repo;

    //dependency injection
    public function __construct(ProductRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Retrieve all products from the database
     * 
     * @return array List of all products
     */
    public function getAll() {
        return $this->repo->getAllProducts(); 
    }

    /**
     * Create a new product in the database
     * 
     * @param array $data Product data containing:
     *                    - name: Product name
     *                    - description: Product description
     *                    - price: Product price (will be converted to float)
     * @return array Response message indicating success
     */
    public function create($data) {
        global $connection;
        $product = new Product(null, $data['name'], $data['description'], floatval($data['price']));
        $this->repo->create($product);
        return ["message" => "Product created"];
    }

    /**
     * Update an existing product in the database
     * 
     * @param array $data Updated product data
     * @param int $id ID of the product to update
     * @return array Response message indicating success or error
     */
    public function update($data, $id) {
        global $connection;
        if (!$id) return ["error" => "Missing ID"];

        $product = new Product($id, $data['name'], $data['description'], floatval($data['price']));
        $this->repo->update($product);
        return ["message" => "Product updated"];
    }

    /**
     * Delete a product from the database
     * 
     * @param int $id ID of the product to delete
     * @return array Response message indicating success or error
     */
    public function delete($id) {
        if (!$id) return ["error" => "Missing ID"];

        $deleted = $this->repo->delete($id);
        if ($deleted) {
            return ["message" => "Product deleted"];
        } else {
            return ["error" => "No product found with given ID"];
        }
    }
}

