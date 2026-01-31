<?php

namespace App\Http\Controllers;
use App\Models\Product;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    
    public function index()
    {
        $products = Product::orderBy('created_at', 'desc')->get();
        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }
    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'price_presencial' => 'required|numeric|min:0',
            'price_online' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        Product::create([
            'title' => $validated['title'],
            'price_presencial' => $validated['price_presencial'],
            'price_online' => $validated['price_online'],
            'description' => $validated['description'],
            'is_active' => true, // Por defecto activo
        ]);

        return redirect()->route('products.index')
            ->with('success', 'Producto creado correctamente');
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'price_presencial' => 'required|numeric|min:0',
            'price_online' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        $product->update($validated);

        return redirect()->route('products.index')
            ->with('success', 'Producto actualizado correctamente');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json(['success' => true]);
    }
}
