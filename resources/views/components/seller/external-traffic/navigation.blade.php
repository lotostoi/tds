@props(['seller'])

<div class="flex gap-2">
    <a href="{{ route('seller.external-traffic.index', ['seller' => $seller->id]) }}" class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-md border {{ request()->routeIs('seller.external-traffic.index') ? 'bg-orange-600 text-white border-orange-600' : 'bg-gray-700 text-gray-300 border-gray-600 hover:bg-gray-600' }}">
        Отчет по продавцу
    </a>
    <a href="{{ route('seller.external-traffic.create', ['seller' => $seller->id]) }}" class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-md border {{ request()->routeIs('seller.external-traffic.create') ? 'bg-orange-600 text-white border-orange-600' : 'bg-gray-700 text-gray-300 border-gray-600 hover:bg-gray-600' }}">
        Импорт
    </a>
</div>
