<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Product;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Intervention\Image\Facades\Image;

class ProductController extends Controller
{
    public function index()
    {
        $products = ProductResource::collection(Product::latest()->paginate());
        return response()->json([
            'products' => $products,
        ], Response::HTTP_ACCEPTED);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => ['required',],
            'price' => ['required', 'gt:0'],
            'description' => ['required'],
            'image' => ['required'],
        ]);
        if ($request->image) {
            $name = time() . '.' . explode('/', explode(':', substr($request->image, 0, strpos($request->image, ';')))[1])[1];
            $img = Image::make($request->image);
            $img->fit(1200, 600, function ($constraint) {
                $constraint->upsize();
            });
            $img->save(public_path() . '/image/products/' . $name);
            $path = '/image/products/' . $name;
        }
        Product::create([
            'title' => $request['title'],
            'description' => $request['description'],
            'price' => $request['price'],
            'image' => $path,
        ]);
        $products = ProductResource::collection(Product::latest()->paginate());
        return response()->json([
            'products' => $products,
        ], Response::HTTP_ACCEPTED);
    }

    public function distroy(Product $product)
    {
        $product->delete();
        $products = ProductResource::collection(Product::latest()->paginate());
        return response()->json([
            'products' => $products,
        ], Response::HTTP_ACCEPTED);
    }

    public function update(Product $product, Request $request)
    {
        $this->validate($request, [
            'title' => ['required',],
            'price' => ['required', 'gt:0'],
            'description' => ['required'],
        ]);
        if ($request->image) {
            $name = time() . '.' . explode('/', explode(':', substr($request->image, 0, strpos($request->image, ';')))[1])[1];
            $img = Image::make($request->image);
            $img->fit(1200, 600, function ($constraint) {
                $constraint->upsize();
            });
            $img->save(public_path() . '/image/products/' . $name);
            $product->image = '/image/products/' . $name;
            $product->save();
        }
        $product->update([
            'title' => $request['title'],
            'description' => $request['description'],
            'price' => $request['price'],
        ]);
        $products = ProductResource::collection(Product::latest()->paginate());
        return response()->json([
            'products' => $products,
        ], Response::HTTP_ACCEPTED);
    }
}
