<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\ProductCard;
use App\Models\Seller;
use Illuminate\Http\Request;

class ProductCardsController extends Controller
{

    public function index(Seller $seller)
    {
        $productCards = ProductCard::query()
            ->select('seller_id','nm_id','imt_id', 'nm_uuid', 'subject_id', 'subject_name', 'brand', 'vendor_code', 'wb_created_at')
            ->where('seller_id', $seller->id)
            ->orderBy('imt_id', 'asc')
            ->paginate(30);

        // Добавляем группировку к данным
        $productCards->getCollection()->transform(function ($card, $index) use ($productCards) {
            return $this->addGroupInfo($card, $index, $productCards->getCollection());
        });

        return view('seller.product-cards.index', compact('seller', 'productCards'));
    }

    /**
     * Добавляет информацию о группировке к карточке товара
     */
    private function addGroupInfo($card, $index, $collection)
    {
        static $currentImtId = null;
        static $groupIndex = 0;

        // Определяем группу
        if ($currentImtId !== $card->imt_id) {
            $currentImtId = $card->imt_id;
            $groupIndex++;
        }

        // Проверяем, первая ли это карточка в группе
        $isFirstInGroup = $index === 0 ||
            ($index > 0 && $collection[$index - 1]->imt_id !== $card->imt_id);

        // Добавляем метаданные группировки
        $card->group_index = $groupIndex;
        $card->group_class = ($groupIndex % 2 === 0) ? 'group-even' : 'group-odd';
        $card->is_first_in_group = $isFirstInGroup;
        $card->should_show_border = $isFirstInGroup && $groupIndex > 1;

        return $card;
    }
}
