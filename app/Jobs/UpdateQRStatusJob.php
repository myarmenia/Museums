<?php

namespace App\Jobs;

use App\Models\TicketQr;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateQRStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $modelId;
    protected $visitedDate;
    protected $status;

  /**
   * Create a new job instance.
   */
    public function __construct($modelId, $visitedDate, $status)
    {
        $this->modelId = $modelId;
        $this->visitedDate = $visitedDate;
        $this->status = $status;


    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $model = TicketQr::find($this->modelId);

        if ($model) {
            $model->status = $this->status;
            $model->visited_date = $this->visitedDate;
            $model->save();
        }
    }
}
