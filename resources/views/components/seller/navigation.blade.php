@props(['seller'])

<div class="flex gap-2">
    <a href="{{ route('seller.show', $seller) }}" class="btn {{ request()->routeIs('seller.show') ? 'btn-orange' : 'btn-gray' }}">
        Информация о продавце
    </a>
    <a href="{{ route('seller.external-traffic.index', ['seller' => $seller->id]) }}" class="btn {{ request()->routeIs('seller.external-traffic.*')  ? 'btn-orange' : 'btn-gray' }}">
        Внешний трафик
    </a>
    <a href="{{ route('clicks.index', ['seller' => $seller->id]) }}" class="btn {{ request()->routeIs('clicks.index') ? 'btn-orange' : 'btn-gray' }}">
        Лог переходов
    </a>
    <a href="{{ route('seller.product-cards.index', ['seller' => $seller->id]) }}" class="btn {{ request()->routeIs('seller.product-cards.*') ? 'btn-orange' : 'btn-gray' }}">
        Карточки товаров
    </a>
</div>
