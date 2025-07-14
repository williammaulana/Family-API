@extends('layouts.app')

@section('content')
<h1 class="text-2xl font-bold mb-4">Welcome to Family Store POS!</h1>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
  <div class="bg-white p-4 rounded shadow">
    <h2 class="text-sm text-gray-500">Total Products</h2>
    <p class="text-xl font-bold">150</p>
  </div>
  <div class="bg-white p-4 rounded shadow">
    <h2 class="text-sm text-gray-500">Monthly Sales</h2>
    <p class="text-xl font-bold">Rp 21.500.000</p>
  </div>
  <div class="bg-white p-4 rounded shadow">
    <h2 class="text-sm text-gray-500">Cashiers</h2>
    <p class="text-xl font-bold">3</p>
  </div>
  <div class="bg-white p-4 rounded shadow">
    <h2 class="text-sm text-gray-500">Low Stock Items</h2>
    <p class="text-xl font-bold">7</p>
  </div>
</div>
@endsection
