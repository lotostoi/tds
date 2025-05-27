<?php

namespace App\Jobs;

use App\Models\Click;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class LogLClickInfoJob implements ShouldQueue
{
    use Queueable;

    public $tries = 3;

    public $timeout = 120;

    private array $params;


    public function __construct(array $params)
    {
        $this->params = $params;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Click::create($this->params);
            Log::info('Click logged', $this->params);
        } catch (\Exception $e) {
            Log::error('Click logging failed', [
                'error' => $e->getMessage(),
                'params' => $this->params,
            ]);
        }
    }
}
