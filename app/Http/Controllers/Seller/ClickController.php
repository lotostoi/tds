<?php

namespace App\Http\Controllers\Seller;

use App\Http\Requests\ClickRequest;
use App\Jobs\LogLClickInfoJob;
use App\Models\Click;
use App\Models\Seller;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;

class ClickController extends Controller
{
    public function index(Seller $seller = null)
    {
        $clicks = Click::where('seller_id', $seller?->seller_id)
            ->latest()
            ->paginate(20);

        return view('seller.clicks.index', compact('clicks', 'seller'));
    }

    public function handle(ClickRequest $request)
    {
        if (empty($request->all())) {
            return response()->json(['message' => 'Empty request']);
        }

        $clickId = 'clk_' . (string) Str::uuid();

        $url = $request->fullUrl();

        $redirectUrl = $this->generateRedirectUrl(
            $request->query('item'),
            $clickId,
            $request->query('mp'),
            $request->query('seller_id')
        );

        LogLClickInfoJob::dispatch([
            'click_id' => $clickId,
            'item_id' => $request->query('item'),
            'marketplace' => $request->query('mp'),
            'link_id' => $request->query('utm_campaign'),
            'blogger_id' => $request->query('utm_content'),
            'utm_medium' => $request->query('utm_medium'),
            'utm_source' => $request->query('utm_source'),
            'seller_id' => $request->query('seller_id'),
            'pp_id' => $request->query('pp_id', null),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'referer' => $request->header('referer'),
            'url' => $url,
            'redirect_url' => $redirectUrl,
        ]);


        return redirect($redirectUrl);
    }

    private function generateRedirectUrl(string $itemId, string $clickId, string $marketplace, string $sellerId): string
    {
        $baseUrl = $marketplace === 'wb'
            ? "https://www.wildberries.ru/catalog/{$itemId}/detail.aspx"
            : "https://www.ozon.ru/product/{$itemId}";

        $params = http_build_query([
            'utm_campaign' => $sellerId . '-id-my_ad_campaign',
            'utm_medium' => 'cpa_bloggers',
            'utm_source' => 'tds',
            'utm_term' => $clickId
        ]);

        return $baseUrl . '?' . $params;
    }

    public function exportCsv(Seller $seller = null)
    {
        $clicks = Click::where('seller_id', $seller->seller_id)
            ->orderBy('created_at', 'desc')
            ->get();

        $csvData = [];

        // Заголовки
        $csvData[] = [
            'Click ID',
            'Item ID',
            'PP ID',
            'Marketplace',
            'Link ID',
            'Blogger ID',
            'UTM Medium',
            'UTM Source',
            'IP Address',
            'User Agent',
            'Referer',
            'URL',
            'Redirect URL',
            'Status',
            'Created At'
        ];

        // Данные
        foreach ($clicks as $click) {
            $csvData[] = [
                $click->click_id,
                $click->item_id,
                $click->pp_id,
                $click->marketplace,
                $click->link_id,
                $click->blogger_id,
                $click->utm_medium,
                $click->utm_source,
                $click->ip_address,
                $click->user_agent,
                $click->referer,
                $click->url,
                $click->redirect_url,
                $click->status,
                $click->created_at
            ];
        }

        // Генерируем CSV строку
        $csvString = '';
        foreach ($csvData as $row) {
            $csvString .= implode(',', array_map(function($field) {
                return '"' . str_replace('"', '""', $field) . '"';
            }, $row)) . "\n";
        }

        return response($csvString)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="clicks.csv"');
    }
}
