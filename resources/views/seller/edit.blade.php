@extends('layouts.app')

@section('content')

    @include('seller.partials.nav')

    <div class="container mx-auto  py-8">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-gray-100">Редактировать продавца</h1>
            <a href="{{ route('seller.index') }}" class="btn btn-gray">
                Назад к списку
            </a>
        </div>

        <div class="card max-w-2xl">
            <form method="POST" action="{{ route('seller.update', $seller) }}" class="auth-form">
                @csrf
                @method('PUT')

                <!-- Seller ID (Обязательное) -->
                <div class="form-group">
                    <label for="seller_id" class="form-label">
                        ID продавца <span class="text-red-400">*</span>
                    </label>
                    <input
                        id="seller_id"
                        type="number"
                        name="seller_id"
                        value="{{ old('seller_id', $seller->seller_id) }}"
                        class="form-input @error('seller_id') error @enderror"
                        required
                        placeholder="Введите уникальный ID продавца"
                    >
                    @error('seller_id')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                    <div class="text-xs text-gray-400 mt-1">
                        Обязательное поле. Уникальный идентификатор продавца на маркетплейсе
                    </div>
                </div>

                <!-- PP ID (Необязательное) -->
                <div class="form-group">
                    <label for="pp_id" class="form-label">
                        PP ID <span class="text-gray-400 text-xs">(необязательно)</span>
                    </label>
                    <input
                        id="pp_id"
                        type="number"
                        name="pp_id"
                        value="{{ old('pp_id', $seller->pp_id) }}"
                        class="form-input @error('pp_id') error @enderror"
                        placeholder="Введите PP ID"
                    >
                    @error('pp_id')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                    <div class="text-xs text-gray-400 mt-1">
                        ID партнера в партнерской программе для интеграции с облаком
                    </div>
                </div>

                <!-- Title (Необязательное) -->
                <div class="form-group">
                    <label for="title" class="form-label">
                        Название <span class="text-gray-400 text-xs">(необязательно)</span>
                    </label>
                    <input
                        id="title"
                        type="text"
                        name="title"
                        value="{{ old('title', $seller->title) }}"
                        class="form-input @error('title') error @enderror"
                        placeholder="Введите название продавца"
                    >
                    @error('title')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                    <div class="text-xs text-gray-400 mt-1">
                        Название маркетплейса или магазина
                    </div>
                </div>

                <!-- Platform (Необязательное) -->
                <div class="form-group">
                    <label for="platform" class="form-label">
                        Платформа <span class="text-gray-400 text-xs">(необязательно)</span>
                    </label>
                    <select
                        id="platform"
                        name="platform"
                        class="form-input @error('platform') error @enderror"
                    >
                        <option value="">Выберите платформу</option>
                        <option value="ozon" {{ (old('platform', $seller->platform) == 'ozon') ? 'selected' : '' }}>Ozon</option>
                        <option value="wildberries" {{ (old('platform', $seller->platform) == 'wildberries') ? 'selected' : '' }}>Wildberries</option>
                        <option value="yandex_market" {{ (old('platform', $seller->platform) == 'yandex_market') ? 'selected' : '' }}>Яндекс.Маркет</option>
                        <option value="avito" {{ (old('platform', $seller->platform) == 'avito') ? 'selected' : '' }}>Avito</option>
                        <option value="other" {{ (old('platform', $seller->platform) == 'other') ? 'selected' : '' }}>Другое</option>
                    </select>
                    @error('platform')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                    <div class="text-xs text-gray-400 mt-1">
                        Платформа маркетплейса (ozon, wildberries, etc.)
                    </div>
                </div>

                <!-- URL (Необязательное) -->
                <div class="form-group">
                    <label for="url" class="form-label">
                        URL <span class="text-gray-400 text-xs">(необязательно)</span>
                    </label>
                    <input
                        id="url"
                        type="url"
                        name="url"
                        value="{{ old('url', $seller->url) }}"
                        class="form-input @error('url') error @enderror"
                        placeholder="https://example.com/api"
                    >
                    @error('url')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                    <div class="text-xs text-gray-400 mt-1">
                        URL для API интеграции с маркетплейсом
                    </div>
                </div>

                <!-- API Key (Необязательное) -->
                @if (!$seller?->api_key)
                <div class="form-group">
                    <label for="api_key" class="form-label">
                        API Key <span class="text-gray-400 text-xs">(необязательно)</span>
                    </label>
                    <input
                        id="api_key"
                        type="text"
                        name="api_key"
                        value="{{ old('api_key') }}"
                        class="form-input @error('api_key') error @enderror"
                        placeholder="Введите новый API ключ (оставьте пустым для сохранения текущего)"
                    >
                    @error('api_key')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                    <div class="text-xs text-gray-400 mt-1">
                        API ключ для интеграции с маркетплейсом. Оставьте пустым, если не хотите изменять текущий ключ.
                    </div>
                </div>
                @endif
                <!-- Submit Button -->
                <div class="flex justify-end gap-3 mt-6">
                    <a href="{{ route('seller.index') }}" class="btn btn-gray">
                        Отмена
                    </a>
                    <button type="submit" class="btn btn-orange">
                        Обновить продавца
                    </button>
                </div>

                <!-- Информация о полях -->
                <div class="mt-4 p-3 bg-gray-800 rounded-lg border border-gray-700">
                    <div class="text-xs text-gray-300">
                        <span class="text-red-400">*</span> - обязательные поля
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
