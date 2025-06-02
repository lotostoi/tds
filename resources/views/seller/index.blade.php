@extends('layouts.app')

@section('content')
@include('seller.partials.nav')

<div class="container mx-auto py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-100">Список продавцов</h1>
        <a href="{{ route('seller.create') }}" class="btn btn-orange">
            Добавить продавца
        </a>
    </div>

    <div class="card overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-700">
            <thead>
                <tr>
                    <th class="text-left">Seller ID</th>
                    <th class="text-left">PP ID</th>
                    <th class="text-left">Название</th>
                    <th class="text-left">Платформа</th>
                    <th class="text-left">Дата создания</th>
                    <th class="text-left">Посмотреть</th>
                    <th class="text-left">Редактировать</th>
                    <th class="text-left">Удалить</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sellers as $seller)
                <tr>
                    <td class="text-left">{{ $seller->seller_id }}</td>
                    <td class="text-left">{{ $seller->pp_id }}</td>
                    <td class="text-left">{{ $seller->title }}</td>
                    <td class="text-left">{{ $seller->platform ?? 'не указано' }}</td>
                    <td class="text-left">{{ $seller->created_at->format('d.m.Y H:i:s') }}</td>
                    <td class="text-left">
                        <a href="{{ route('seller.show', $seller->id) }}" class="btn btn-blue">
                            Посмотреть
                        </a>
                    </td>
                    <td class="text-left">
                        <a href="{{ route('seller.edit', $seller->id) }}" class="btn btn-green">
                            Редактировать
                        </a>
                    </td>
                    <td class="text-left">
                        <form id="delete-form-{{ $seller->id }}" action="{{ route('seller.destroy', $seller->id) }}" method="POST" style="display: none;">
                            @csrf
                            @method('DELETE')
                        </form>
                        <button type="button" class="btn btn-orange delete-btn" data-seller-id="{{ $seller->id }}" data-seller-title="{{ $seller->title }}">
                            Удалить
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $sellers->links() }}
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
            <button type="button" id="confirmDelete" class="btn btn-orange">
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
