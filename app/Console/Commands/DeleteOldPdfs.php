<?php

namespace App\Console\Commands;

use App\Models\TicketPdf;
use Carbon\Carbon;
use File;
use Illuminate\Console\Command;
use Log;

class DeleteOldPdfs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // protected $signature = 'app:delete-old-pdfs';
    protected $signature = 'pdf:cleanup';
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Removes PDF files older than 3 months from folders and databases';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $folderPath = storage_path('app');
        $now = Carbon::now();

        $batchSize = 500; // Processing 500 records at a time

        do {
            // Getting 500 old files
            $oldPdfs = TicketPdf::where('created_at', '<', $now->copy()->subMonths(3))->limit($batchSize)->get();
            Log::info('count' . count($oldPdfs));
            if ($oldPdfs->isEmpty()) {
              break;
            }


            foreach ($oldPdfs as $pdf) {
                $filePath = $folderPath . '/' . $pdf->pdf_path;

                if (File::exists($filePath)) {
                    File::delete($filePath);
                }
            }

            // Delete row from db
            TicketPdf::whereIn('id', $oldPdfs->pluck('id')->toArray())->delete();

            $this->info("Удалено " . count($oldPdfs) . " файлов");

        } while (count($oldPdfs) === $batchSize); // Let's keep going as long as there are records

        $this->info("Cleanup complete!");
        Log::info('Loggggg - Cleanup complete!');
    }
}
