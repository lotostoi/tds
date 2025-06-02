@extends('layouts.app')

@section('content')


<div class="container mx-auto">
    @include('seller.partials.nav')
    <br>

    <x-seller.navigation :seller="$seller" />

    <br>
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-100">Информация о продавце</h1>
        <div class="flex gap-2">
            <a href="{{ route('seller.edit', $seller) }}" class="inline-flex items-center px-3 py-1 text-sm font-medium rounded-md bg-orange-600 text-white border border-orange-600 hover:bg-orange-700">
                Редактировать
            </a>
            <form id="delete-form-{{ $seller->id }}" action="{{ route('seller.destroy', $seller->id) }}" method="POST" style="display: none;">
                @csrf
                @method('DELETE')
            </form>
            <button type="button" class="inline-flex items-center px-3 py-1 text-sm font-medium rounded-md bg-red-600 text-white border border-red-600 hover:bg-red-700 delete-btn" data-seller-id="{{ $seller->id }}" data-seller-title="{{ $seller->title }}">
                Удалить
            </button>
        </div>
    </div>

    <div class="card max-w-2xl">
        <div class="space-y-6">
            <!-- ID продавца -->
            <div class="flex flex-col">
                <span class="text-sm text-gray-400">ID продавца</span>
                <span class="text-lg text-gray-100">{{ $seller->seller_id }}</span>
            </div>

            <!-- PP ID -->
            <div class="flex flex-col">
                <span class="text-sm text-gray-400">PP ID</span>
                <span class="text-lg text-gray-100">{{ $seller->pp_id ?: 'Не указан' }}</span>
            </div>

            <!-- Название -->
            <div class="flex flex-col">
                <span class="text-sm text-gray-400">Название</span>
                <span class="text-lg text-gray-100">{{ $seller->title ?: 'Не указано' }}</span>
            </div>

            <!-- Платформа -->
            <div class="flex flex-col">
                <span class="text-sm text-gray-400">Платформа</span>
                <span class="text-lg text-gray-100">{{ $seller->platform ?: 'Не указана' }}</span>
            </div>

            <!-- URL -->
            <div class="flex flex-col">
                <span class="text-sm text-gray-400">URL</span>
                @if($seller->url)
                <a href="{{ $seller->url }}" target="_blank" class="text-lg text-blue-400 hover:text-blue-300">
                    {{ $seller->url }}
                </a>
                @else
                <span class="text-lg text-gray-100">Не указан</span>
                @endif
            </div>

            <!-- Дата создания -->
            <div class="flex flex-col">
                <span class="text-sm text-gray-400">Дата создания</span>
                <span class="text-lg text-gray-100">{{ $seller->created_at->format('d.m.Y H:i') }}</span>
            </div>

            <!-- Дата обновления -->
            <div class="flex flex-col">
                <span class="text-sm text-gray-400">Последнее обновление</span>
                <span class="text-lg text-gray-100">{{ $seller->updated_at->format('d.m.Y H:i') }}</span>
            </div>
        </div>
    </div>
</div>

<!-- Модальное окно подтверждения удаления -->
<div id="deleteModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-gray-800 rounded-lg p-6 max-w-md w-full mx-4">
        <div class="flex items-center mb-4">
            <svg class="w-6 h-6 text-red-400 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
            </svg>
            <h3 class="text-lg font-semibold text-gray-100">Подтверждение удаления</h3>
        </div>

        <p class="text-gray-300 mb-6">
            Вы уверены, что хотите удалить продавца "<span id="sellerName" class="font-semibold text-white"></span>"?<br>
            Это действие нельзя отменить.
        </p>

        <div class="flex justify-end space-x-3">
            <button type="button" id="cancelDelete" class="btn bg-gray-600 hover:bg-gray-700 text-white">
                Отмена
            </button>
            <button type="button" id="confirmDelete" class="btn btn-red">
                Удалить
            </button>
        </div>
    </div>
</div>

<script>
    let currentSellerId = null;

    // Обработчики для кнопок удаления
    document.addEventListener('DOMContentLoaded', function() {
        const deleteButtons = document.querySelectorAll('.delete-btn');
        const modal = document.getElementById('deleteModal');
        const sellerNameSpan = document.getElementById('sellerName');
        const cancelBtn = document.getElementById('cancelDelete');
        const confirmBtn = document.getElementById('confirmDelete');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                currentSellerId = this.dataset.sellerId;
                sellerNameSpan.textContent = this.dataset.sellerTitle;
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            });
        });

        // Закрытие модального окна
        function closeModal() {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            currentSellerId = null;
        }

        cancelBtn.addEventListener('click', closeModal);

        // Закрытие по клику на фон
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeModal();
            }
        });

        // Подтверждение удаления
        confirmBtn.addEventListener('click', function() {
            if (currentSellerId) {
                const form = document.getElementById(`delete-form-${currentSellerId}`);
                form.submit();
            }
        });

        // Закрытие по Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                closeModal();
            }
        });
    });
</script>

@endsection
