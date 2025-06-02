<?php

declare(strict_types=1);

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Seller;
use App\Models\ExternalTraffic;
use App\Services\ImportExternalTraffic\Dto\ImportExternalTrafficDto;
use App\Services\ImportExternalTraffic\StrategyImportExternalTrafficFactory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExternalTrafficController extends Controller
{

    public function __construct(
        private StrategyImportExternalTrafficFactory $strategyImportExternalTrafficFactory
    ) {}

    public function index(Seller $seller = null): View
    {
        $externalTraffic = ExternalTraffic::where('seller_id', $seller->id)
            ->orderBy('event_date', 'desc')
            ->paginate(20);

        return view('seller.external-traffic.index', compact('seller', 'externalTraffic'));
    }

    public function create(Seller $seller = null): View
    {
        return view('seller.external-traffic.create', compact('seller'));
    }

    public function store(Request $request, Seller $seller = null)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls',
        ]);

        try {

            $file = $request->file('file');
            $path = $file->store('external-traffic', 'public');

            $dto = (new ImportExternalTrafficDto())
                ->setPlatform($seller->platform)
                ->setSellerId($seller->id)
                ->setFilePath($path);

            $strategy = $this->strategyImportExternalTrafficFactory->make($dto);

            $importedCount = $strategy->import($dto);

            return redirect()
                ->back()
                ->with('success', "Успешно импортировано {$importedCount} записей");

        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Ошибка при импорте файла: ' . $e->getMessage());
        }
    }

    public function exportCsv(Seller $seller = null)
    {
        $externalTraffic = ExternalTraffic::where('seller_id', $seller->id)
            ->orderBy('event_date', 'desc')
            ->get();

        return response()->streamDownload(function() use ($externalTraffic) {
            $output = fopen('php://output', 'w');
            fputcsv($output, [
                'Event Date',
                'UTM Source',
                'UTM Medium',
                'UTM Campaign',
                'UTM Term',
                'UTM Content',
                'Clicks',
                'Ordered Items',
                'Order Value (RUB)',
                'Platform'
            ]);

            foreach ($externalTraffic as $traffic) {
                fputcsv($output, [
                    $traffic->event_date,
                    $traffic->utm_source,
                    $traffic->utm_medium,
                    $traffic->utm_campaign,
                    $traffic->utm_term,
                    $traffic->utm_content,
                    $traffic->clicks,
                    $traffic->ordered_items,
                    $traffic->order_value_rub,
                    $traffic->platform
                ]);
            }

            fclose($output);
        }, 'external-traffic.csv', ['Content-Type' => 'text/csv']);
    }
}
