<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class TriggerModelRetraining implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public $tries = 3;

    /**
     * The number of seconds the job can run before timing out.
     *
     * @var int
     */
    public $timeout = 360; // 6 minutes

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info('Starting model retraining job...');

        $flaskApiUrl = config('tahumelati.flask_api_url');
        if (!$flaskApiUrl) {
            Log::error('FLASK_API_URL is not set. Aborting retraining job.');
            // Fail the job permanently if the URL isn't set
            $this->fail(new Exception('FLASK_API_URL is not configured.'));
            return;
        }

        try {
            // Use a longer timeout as this is a background job
            $response = Http::timeout(300)->post($flaskApiUrl . '/retrain');

            if ($response->successful()) {
                Log::info('Model retraining job completed successfully on Flask API.', [
                    'status' => $response->status(), 
                    'body' => $response->json()
                ]);
            } else {
                // If Flask returns an error (4xx or 5xx), fail the job
                $errorMessage = 'Flask API returned an error during retraining.';
                Log::error($errorMessage, [
                    'status' => $response->status(), 
                    'body' => $response->json()
                ]);
                $this->fail(new Exception($errorMessage));
            }

        } catch (Exception $e) {
            Log::error('Failed to execute model retraining job.', ['error' => $e->getMessage()]);
            // Re-throw the exception to let the queue worker know it failed and should be retried.
            throw $e;
        }
    }
}