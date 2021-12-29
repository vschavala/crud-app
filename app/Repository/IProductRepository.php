<?php

namespace App\Repository;


interface IProductRepository{

    public function getAllProducts();

    public function getProductById($id);

    public function createOrUpdate($request);

    public function deleteProduct($id);

    public function deleteAllProducts($ids);
}
