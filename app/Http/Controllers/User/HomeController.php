<?php

namespace App\Http\Controllers\User;

use App\Models\Report;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Interfaces\ReportRepositoryInterface;

class HomeController extends Controller
{

    private ReportRepositoryInterface $reportRepository;

    public function __construct(ReportRepositoryInterface $reportRepository)
    {
        $this->reportRepository = $reportRepository;
    }

    public function index()
        {
            $reportsKehilangan = Report::where('status', 'aktif')
                ->where('type', 'kehilangan')
                ->latest()
                ->take(5)
                ->get();

            $reportsTemuan = Report::where('status', 'aktif')
                ->where('type', 'temuan')
                ->latest()
                ->take(5)
                ->get();

            return view('pages.app.home', compact(
                'reportsKehilangan',
                'reportsTemuan'
            ));
        }
}
