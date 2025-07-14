@extends('layouts.app')

@section('content')
<div class="flex justify-between items-center mb-4">
    <h2 class="text-2xl font-bold">Products</h2>
    <a href="{{ route('products.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded">+ Add Product</a>
</div>

@if(session('success'))
    <div class="bg-green-100 text-green-800 p-2 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

<table class="w-full bg-white shadow rounded">
    <thead>
        <tr class="bg-gray-200 text-left">
            <th class="p-2">Name</th>
            <th class="p-2">Category</th>
            <th class="p-2">Stock</th>
            <th class="p-2">Price</th>
            <th class="p-2">Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($products as $product)
        <tr class="border-t">
            <td class="p-2">{{ $product->name }}</td>
            <td class="p-2">{{ $product->category }}</td>
            <td class="p-2">{{ $product->stock }}</td>
            <td class="p-2">Rp{{ number_format($product->selling_price, 0, ',', '.') }}</td>
            <td class="p-2 space-x-2">
                <a href="{{ route('products.edit', $product) }}" class="text-blue-600">Edit</a>
                <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline">
                    @csrf @method('DELETE')
                    <button class="text-red-600" onclick="return confirm('Are you sure?')">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection
