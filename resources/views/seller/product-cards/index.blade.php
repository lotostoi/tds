@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    @include('seller.partials.nav')

    <br>
    <x-seller.navigation :seller="$seller" />

    <div class="flex justify-between items-center mb-6 mt-6">
        <h1 class="text-2xl font-bold text-gray-100">Карточки товаров</h1>
    </div>

    <div class="card overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-700 product-cards-table">
            <thead>
                <tr>
                    <th>NM ID</th>
                    <th>IMT ID</th>
                    <th>UUID</th>
                    <th>Категория</th>
                    <th>Бренд</th>
                    <th>Артикул продавца</th>
                    <th>Дата создания WB</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($productCards as $productCard)
                <tr class="{{ $productCard->group_class }} {{ $productCard->should_show_border ? 'group-border-top' : '' }}">
                    <td>{{ $productCard->nm_id }}</td>
                    <td>
                        <span class="imt-id">{{ $productCard->imt_id }}</span>
                    </td>
                    <td>
                        <span class="text-xs text-gray-400 font-mono">
                            {{ Str::limit($productCard->nm_uuid, 20) }}
                        </span>
                    </td>
                    <td>{{ $productCard->subject_name ?? 'не указано' }}</td>
                    <td>{{ $productCard->brand ?? 'не указано' }}</td>
                    <td>{{ $productCard->vendor_code ?? 'не указано' }}</td>
                    <td>
                        @if($productCard->wb_created_at)
                            {{ $productCard->wb_created_at->format('d.m.Y H:i') }}
                        @else
                            не указано
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-gray-400 py-8">
                        Карточки товаров не найдены
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $productCards->links() }}
    </div>
</div>
@endsection
