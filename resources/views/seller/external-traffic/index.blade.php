@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    @include('seller.partials.nav')

    <br>
    <x-seller.navigation :seller="$seller" />
    <br>
    <x-seller.external-traffic.navigation :seller="$seller" />

    <div class="flex justify-between items-center mb-6 mt-6">
        <h1 class="text-2xl font-bold text-gray-100">Отчет по внешнему трафику</h1>
    </div>

    <div class="flex justify-end">
        <a href="{{ route('seller.external-traffic.export', $seller) }}" class="btn btn-primary">Экспорт в CSV</a>
    </div>

    <div class="card overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-700">
            <thead>
                <tr>
                    <th>Дата</th>
                    <th>utm_source</th>
                    <th>utm_medium</th>
                    <th>utm_campaign</th>
                    <th>utm_term</th>
                    <th>utm_content</th>
                    <th>Клики</th>
                    <th>Заказов</th>
                    <th>Стоимость заказа</th>
                    <th>Платформа</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($externalTraffic as $traffic)
                <tr>
                    <td>{{ $traffic->event_date }}</td>
                    <td>{{ $traffic->utm_source }}</td>
                    <td>{{ $traffic->utm_medium }}</td>
                    <td>{{ $traffic->utm_campaign ?? 'не указано' }}</td>
                    <td>{{ $traffic->utm_term }}</td>
                    <td>{{ $traffic->utm_content ?? 'не указано' }}</td>
                    <td>{{ $traffic->clicks }}</td>
                    <td>{{ $traffic->ordered_items }}</td>
                    <td>{{ $traffic->order_value_rub }}</td>
                    <td>{{ $traffic->platform ?? 'не указано' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>


    <div class="mt-4">
        {{ $externalTraffic->links() }}
    </div>

</div>
@endsection
