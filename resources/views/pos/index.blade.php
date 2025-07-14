@extends('layouts.app')

@section('content')
<h2 class="text-2xl font-bold mb-4">Point of Sale</h2>

@if(session('success'))
    <div class="bg-green-100 text-green-800 p-2 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

<form action="{{ route('pos.store') }}" method="POST">
    @csrf

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @foreach ($products as $product)
        <div class="border p-2 bg-white rounded shadow">
            <h3 class="font-bold">{{ $product->name }}</h3>
            <p>Stock: {{ $product->stock }}</p>
            <p>Rp{{ number_format($product->selling_price) }}</p>
            <input type="checkbox" name="items[{{ $loop->index }}][product_id]" value="{{ $product->id }}">
            <input type="number" name="items[{{ $loop->index }}][quantity]" min="1" max="{{ $product->stock }}" placeholder="Qty" class="w-full border mt-1">
            <input type="hidden" name="items[{{ $loop->index }}][price]" value="{{ $product->selling_price }}">
        </div>
        @endforeach
    </div>

    <div class="mt-6 bg-white p-4 rounded shadow space-y-2">
        <label>Cash Received</label>
        <input type="number" step="0.01" name="cash_received" class="w-full border p-2">

        <label>Payment Method</label>
        <select name="payment_method" class="w-full border p-2">
            <option value="cash">Cash</option>
            <option value="qris">QRIS</option>
            <option value="transfer">Bank Transfer</option>
        </select>

        <button type="submit" class="mt-4 bg-green-600 text-white px-4 py-2 rounded w-full">Complete Sale</button>
    </div>
</form>
@endsection
