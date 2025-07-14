<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Family Store - Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  @vite('resources/css/app.css')
</head>
<body class="flex bg-gray-100 text-gray-800 min-h-screen">

  {{-- Sidebar --}}
  <aside class="w-64 bg-white shadow-md h-screen fixed z-10">
    <div class="p-4 text-xl font-bold border-b">
      🛒 Family Store
    </div>

    <nav class="p-4 space-y-2">
      <a href="/dashboard" class="flex items-center gap-2 p-2 rounded hover:bg-gray-100">
        📊 <span>Dashboard</span>
      </a>
      <a href="/products" class="flex items-center gap-2 p-2 rounded hover:bg-gray-100">
        📦 <span>Products</span>
      </a>
      <a href="/pos" class="flex items-center gap-2 p-2 rounded hover:bg-gray-100">
        🧾 <span>POS</span>
      </a>
      @if (Auth::user()->role === 'super_admin')
      <a href="/users" class="flex items-center gap-2 p-2 rounded hover:bg-gray-100">
        👥 <span>Users</span>
      </a>
      @endif
      <a href="/reports" class="flex items-center gap-2 p-2 rounded hover:bg-gray-100">
        📈 <span>Reports</span>
      </a>
    </nav>
  </aside>

  {{-- Main Content Area --}}
  <div class="ml-64 flex-1 flex flex-col">
    
    {{-- Navbar --}}
    <header class="bg-white shadow p-4 flex justify-between items-center">
      <div>
        <input type="text" placeholder="Search..." class="border px-3 py-1 rounded" />
      </div>
      <div class="flex items-center gap-4">
        <span class="text-sm font-medium">{{ Auth::user()->name }} ({{ Auth::user()->role }})</span>
        <form action="{{ route('logout') }}" method="POST">
          @csrf
          <button class="text-red-500 text-sm hover:underline">Logout</button>
        </form>
      </div>
    </header>

    {{-- Dynamic Page Content --}}
    <main class="p-6">
      @yield('content')
    </main>

  </div>
</body>
</html>
