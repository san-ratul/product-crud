<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class ProductController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth')->except('show');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return redirect('/');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'description' => ['required'],
            'image' => ['required', 'image', 'mimes:jpeg,jpg,png', 'max:2048'],
            'price' => ['required', 'regex:/^[0-9]+(\.[0-9][0-9]?)?$/'],
        ]);
        $path = "";
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            // open file a image resource
            $img = Image::make($image->getRealPath());
            // add callback functionality to retain maximal original image size
            $img->fit(1200, 600, function ($constraint) {
                $constraint->upsize();
            });
            $extention = $request->file('image')->getClientOriginalExtension();
            $filename = time() . '.' . $extention;
            $path = '/image/products/' . $filename;
            $img->save(public_path() . '/image/products/' . $filename);
        }

        Product::create([
            'title' => $request['title'],
            'description' => $request['description'],
            'price' => $request['price'],
            'image' => $path,
        ]);

        return redirect()->route('home')->with('status', "Product Added Successfully!");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
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
        $this->validate($request, [
            'title' => ['required',],
            'price' => ['required', 'gt:0'],
            'description' => ['required'],
        ]);
        $product->update([
            'title' => $request['title'],
            'description' => $request['description'],
            'price' => $request['price'],
        ]);
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $img = Image::make($image->getRealPath());
            $img->fit(1200, 600, function ($constraint) {
                $constraint->upsize();
            });
            $extention = $request->file('image')->getClientOriginalExtension();
            $filename = time() . '.' . $extention;
            $img->save(public_path() . '/image/products/' . $filename);
            $product->image = '/image/products/' . $filename;
            $product->save();
        }
        return redirect()->route('home')->with('status', "Product Updated Successfully!");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('home')->with('status', "Product Deleted Successfully!");
    }
}
