<?php

namespace App\Exports;

use App\Models\Report;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ReportExport implements FromView
{
    protected $users;
    public function __construct($users) {
        $this->users = $users;
    }

    public function view(): View {
        return view("users.user_report_excel",[
            "users" => $this->users,
        ]);
    }
}