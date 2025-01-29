<?php

namespace App\Jobs;

use Throwable;
use App\Models\User;
use App\Models\Report;
use Illuminate\Http\Request;
use App\Exports\ReportExport;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class GenerateReportExcelJob implements ShouldQueue
{
    use Queueable;

    protected $fileName;
    protected $start_date;
    protected $end_date;
    protected $title;
    /**
     * Create a new job instance.
    */
    public function __construct(string $fileName,$start_date,$end_date,string $title)
    {
        $this->fileName = $fileName;
        $this->start_date = $start_date;
        $this->end_date = $end_date;
        $this->title = $title;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $filePath = "reports/{$this->fileName}";

        $users = User::whereBetween("birth_date",[
            $this->start_date,
            $this->end_date
        ])->orderBy("id","desc")->get();

        Report::create([
            "title" => $this->title,
            "report_link" => env("APP_URL")."storage/".$filePath,
        ]);

        Excel::store(new ReportExport($users), $filePath, 'public'); 
    }

    public function failed(Throwable $exception)
    {
        logger()->info("Error generando el reporte: {$exception->getMessage()}");
    }
}