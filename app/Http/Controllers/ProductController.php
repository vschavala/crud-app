<?php

namespace App\Http\Controllers;

use App\Product;
use App\Repository\IProductRepository;
use Illuminate\Http\Request;
use Redirect, Response, DB;

class ProductController extends Controller
{
    public $product;

    public function __construct(IProductRepository $product)
    {
        $this->middleware('auth');
        $this->product = $product;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->ajax()) {
            return $this->product->getAllProducts();
        }

        return view('list');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $product = $this->product->createOrUpdate($request);
        return Response::json($product);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit($productId)
    {
        $product = $this->product->getProductById($productId);

        return Response::json($product);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($productId)
    {
        $product = $this->product->deleteProduct($productId);
        return Response::json($product);
    }

    /**
     * Remove the multiple resource from storage
     *
     * @param Request $request
     * @return void
     */
    public function multiDelete(Request $request){
        $prodIds = $request->ids;
        $product = $this->product->deleteAllProducts($prodIds);
        return Response::json($product);
    }
}
