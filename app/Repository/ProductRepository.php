<?php

namespace App\Repository;

use App\Repository\IProductRepository;
use App\Product;
use Illuminate\Http\Request;
use Redirect, Response, DB;
use File;

class ProductRepository implements IProductRepository
{
    protected $product = null;

    public function getAllProducts()
    {
        if (request()->ajax()) {
            return datatables()
                ->of(Product::select('*'))
                ->addColumn('action', 'action')
                ->addColumn('image', 'image')
                ->addColumn('check','check')
                ->rawColumns(['action', 'image','check'])
                ->addIndexColumn()
                ->make(true);
        }
        return true;
    }

    public function getProductById($id)
    {
        $where = ['id' => $id];
        $product = Product::where($where)->first();

        return $product;
    }

    public function createOrUpdate($request)
    {
        request()->validate([
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $productId = $request->product_id;

        $details = [
            'name' => $request->name,
            'upc' => $request->upc,
            'price' => $request->price,
            'status' => $request->status,
        ];

        if ($files = $request->file('image')) {
            //delete old file
            File::delete('public/product/' . $request->hidden_image);

            //insert new file
            $destinationPath = 'public/product/'; // upload path
            $profileImage =
                date('YmdHis') . '.' . $files->getClientOriginalExtension();
            $files->move($destinationPath, $profileImage);
            $details['image'] = "$profileImage";
        }

        $product = Product::updateOrCreate(['id' => $productId], $details);

        return $product;
    }

    public function deleteProduct($id)
    {
        $data = Product::where('id', $id)->first(['image']);
        File::delete('public/product/' . $data->image);
        $product = Product::where('id', $id)->delete();

        return $product;
    }

    public function deleteAllProducts($ids)
    {
        $data = Product::whereIn('id', explode(',',$ids));
        foreach($data as $dat){
            File::delete('public/product/' . $dat->image);
        }
        $product = Product::where('id', explode(',',$ids))->delete();

        return $product;
    }
}
