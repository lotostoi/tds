<?php

namespace App\Http\Controllers;

use App\Http\Requests\ClickRequest;
use App\Models\Click;
use Illuminate\Support\Str;

class ClickController extends Controller
{
    public function index()
    {
        $clicks = Click::latest()->paginate(20);

        return view('clicks.index', compact('clicks'));
    }

    public function handle(ClickRequest $request)
    {
        if (empty($request->all())) {
            return response()->json(['message' => 'Empty request']);
        }

        $clickId = 'clk_' . (string) Str::uuid();

        Click::create([
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
            'referer' => $request->header('referer')
        ]);

        // Формирование URL для редиректа
        $redirectUrl = $this->generateRedirectUrl(
            $request->query('item'),
            $clickId,
            $request->query('mp'),
            $request->query('seller_id')
        );

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
}
