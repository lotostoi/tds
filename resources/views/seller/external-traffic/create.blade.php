@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    @include('seller.partials.nav')

    <br>
    <x-seller.navigation :seller="$seller" />
    <br>
    <x-seller.external-traffic.navigation :seller="$seller" />

    <div class="flex justify-between items-center mb-6 mt-6">
        <h1 class="text-2xl font-bold text-gray-100">Импорт файла</h1>
    </div>

    <div class="card max-w-lg">
        <h2 class="text-xl font-semibold text-gray-100 mb-6">Импорт файла внешнего трафика</h2>

        <form action="{{ route('seller.external-traffic.store', ['seller' => $seller->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-4">
                <label for="file" class="block text-sm font-medium text-gray-300 mb-2">
                    Выберите файл для импорта
                </label>
                <input
                    type="file"
                    name="file"
                    id="file"
                    accept=".csv,.xlsx,.xls"
                    class="block w-full text-sm text-gray-300 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-gray-700 file:text-gray-300 hover:file:bg-gray-600 border border-gray-600 rounded-md bg-gray-800 p-2"
                    required
                >
                <p class="mt-1 text-xs text-gray-500">Поддерживаемые форматы: Excel (XLSX, XLS)</p>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="btn btn-orange">
                    Импортировать файл
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
