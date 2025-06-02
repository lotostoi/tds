@extends('layouts.app')

@section('content')
<div class="container mx-auto">
    @include('seller.partials.nav')

    <br>
    <x-seller.navigation :seller="$seller" />

    <div class="flex justify-between items-center mb-6 mt-6">
        <h1 class="text-2xl font-bold text-gray-100"> Лог переходов</h1>
    </div>

    <div class="flex justify-end">
        <a href="{{ route('clicks.export', $seller) }}" class="btn btn-primary">Экспорт в CSV</a>
    </div>

    <div class="card overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-700">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Товар</th>
                    <th>Маркетплейс</th>
                    <th>Продавец</th>
                    <th>ID ссылки</th>
                    <th>Блогер</th>
                    <th>UTM Medium</th>
                    <th>UTM Source</th>
                    <th>PP ID</th>
                    <th>IP</th>
                    <th>User Agent</th>
                    <th>Referer</th>
                    <th>URL</th>
                    <th>Redirect URL</th>
                    <th>Дата</th>
                </tr>
            </thead>
            <tbody>
                @foreach($clicks as $click)
                <tr>
                    <td>{{ $click->click_id }}</td>
                    <td>{{ $click->item_id }}</td>
                    <td>{{ $click->marketplace }}</td>
                    <td>{{ $click->seller_id }}</td>
                    <td>{{ $click->link_id }}</td>
                    <td>{{ $click->blogger_id }}</td>
                    <td>{{ $click->utm_medium }}</td>
                    <td>{{ $click->utm_source }}</td>
                    <td>{{ $click->pp_id }}</td>
                    <td>{{ $click->ip_address }}</td>
                    <td>
                        <div class="max-w-xs truncate" title="{{ $click->user_agent }}">
                            {{ $click->user_agent }}
                        </div>
                    </td>
                    <td>
                        <div class="max-w-xs truncate" title="{{ $click->referer }}">
                            {{ $click->referer }}
                        </div>
                    </td>
                    <td>{{ $click->url }}</td>
                    <td>{{ $click->redirect_url }}</td>
                    <td>{{ $click->created_at->format('d.m.Y H:i:s') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $clicks->links() }}
    </div>
</div>
@endsection
