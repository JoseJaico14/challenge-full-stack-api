<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use App\Jobs\GenerateReportExcelJob;
use App\Http\Resources\ReportCollection;
use Validator;

class ReportController extends Controller
{
    
    public function list_reports(Request $request){
        $reports = Report::all();
        return response()->json([
            "reports" => ReportCollection::make($reports),
        ]);
    }

    public function get_report($report_id){
        $report = Report::findOrFail($report_id);
        return response()->json([
            'url' => $report->report_link,
        ]);
    }

    public function generate_report(Request $request){
        $validator = Validator::make(request()->all(), [
            'title' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);
  
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }
        $fileName = 'reporte_excel'.now()->format('Ymd_His').'.xlsx';
        GenerateReportExcelJob::dispatch($fileName,$request->start_date,$request->end_date,$request->title);
        return response()->json([
            'message' => 'El reporte se está generando y se guardará en disco.',
        ]);
    }

}
