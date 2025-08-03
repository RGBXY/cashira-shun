<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

use function Pest\Laravel\json;

class ProductController extends Controller
{
    public function index(){

        $products = Product::latest()->paginate(20);

        return Inertia::render("product/index", [
            'products' => $products
        ]);
    }

    public function searchProductName(Request $request)
    {
        $product = Product::where('name', 'like', '%' . $request->name . '%')
                    ->paginate(12); 
                       
        return Inertia::render('product/index', [
            'products' => $product,
        ]);                     
    }

    public function searchProductBarcode(Request $request)
    {
        $product = Product::where('barcode', $request->barcode)
                    ->paginate(12); 
                       
        return Inertia::render('product/index', [
            'products' => $product,
        ]);                     
    }

    public function store(Request $request){
        $request->validate([
            'name' => [
                'required', 'max:225', 'unique:products,name'
            ],
            'price' => [
                'required', 'max:225', 
            ],
            'barcode' => [
                'required', 'max:225', 'unique:products,barcode'
            ],
            'unit' => [
                'required', 'max:225', 
            ]
        ]);

        Product::create([
            'name' => $request->name,
            'price' => $request->price,
            'barcode' => $request->barcode,
            'unit' => $request->unit,
        ]);
    }

    public function show($id)
    {
        $product = Product::find($id);
    
        if ($product) {
            return response()->json([
                'success' => true,
                'data'    => $product
            ]);
        }
    
        return response()->json([
            'success' => false,
            'data'    => []
        ]);
    }

    public function update(Request $request, Product $product){
        $request->validate([
            'name' => [
                'required',
                'max:225',
                Rule::unique('products', 'name')->ignore($product->id),
            ],
            'price' => [
                'required', 'max:225', 
            ],
            'barcode' => [
                'required',
                'max:225',
                Rule::unique('products', 'barcode')->ignore($product->id),
            ],
            'unit' => [
                'required', 'max:225', 
            ],

        ]);

        $product->update([
            'name' => $request->name,
            'price' => $request->price,
            'barcode' => $request->barcode,
            'unit' => $request->unit,
        ]);
    }

    public function delete($id) {
        $product = Product::find($id);

        $product->delete();
    }
    
}
