<?php

namespace App\Http\Controllers\User;

use App\Models\Report;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreReportRequest;
use App\Http\Requests\UpdateReportRequest;
use App\Interfaces\ReportRepositoryInterface;
use RealRashid\SweetAlert\Facades\Alert as Swal;

class ReportController extends Controller
{
    private ReportRepositoryInterface $reportRepository;

    public function __construct(
        ReportRepositoryInterface $reportRepository,
    ) {
        $this->reportRepository = $reportRepository;
    }

    /**
     * Menampilkan daftar laporan (opsional filter kategori)
     */

    public function index(Request $request)
    {
         $type = $request->query('type');

        $query = Report::where('status', 'aktif');

        if ($type) {
            $query->where('type', $type);
        }

        $reports = $query->latest()->get();

        return view('pages.app.report.index', compact('reports', 'type'));
    }

    // public function myReport(Request $request)
    // {
    //     $query = Report::where('resident_id', auth()->id());
    //     $status = $request->query('status', 'aktif');
    //     $type   = $request->query('type', 'kehilangan');

    //     if ($request->filled('status')) {
    //         $query->where('status', $request->status);
    //     }

    //     if ($request->filled('type')) {
    //         $query->where('type', $request->type);
    //     }

    //     $reports = $query->latest()->get();

    //     return view('pages.app.report.my-report', compact('reports'));
    // }

    public function myReport(Request $request)
    {
        // DEFAULT WAJIB
        $status = $request->query('status', 'aktif');
        $type   = $request->query('type', 'kehilangan');

        $reports = Report::where('resident_id', auth()->id())
            ->where('status', $status)
            ->where('type', $type)
            ->latest()
            ->get();

        return view('pages.app.report.my-report', compact('reports', 'status', 'type'));
    }


    /**
     * Detail laporan berdasarkan kode
     */
    public function show(string $code)
    {
          $report = Report::where('code', $code)->firstOrFail();

            if (
                $report->status === 'selesai' &&
                (!auth()->check() || auth()->user()->resident?->id !== $report->resident_id)
            ) {
                abort(404);
            }

            return view('pages.app.report.show', compact('report'));
    }

    /**
     * Form buat laporan
     */
    public function take(Request $request)
    {
        $type = $request->query('type', 'kehilangan');

        // Tambahkan ini untuk cek data
        // dd($type);

        if (!in_array($type, ['kehilangan', 'temuan'])) {
            $type = 'kehilangan';
        }

        return view('pages.app.report.take', compact('type'));
    }

    public function preview(string $type = 'kehilangan')
    {
        if (!in_array($type, ['kehilangan', 'temuan'])) {
            $type = 'kehilangan';
        }

        return view('pages.app.report.preview', compact('type'));
    }


    /**
     * Simpan laporan (kehilangan / temuan)
     */
    public function store(StoreReportRequest $request)
    {
        $data = $request->validated();

        // kode laporan
        $data['code'] = 'LAPOR-' . strtoupper(Str::random(6));

        // relasi user
        $data['resident_id'] = Auth::id();

        // upload gambar
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')
                ->store('assets/report/image', 'public');
        }

        // default status
        $data['status'] = 'Aktif';

        $this->reportRepository->createReport($data);

        Swal::toast('Laporan berhasil dikirim.', 'success')
            ->timerProgressBar();

        return redirect()->route('user.report.success',['type' => $request->type]);
    }

    public function create(Request $request)
    {
        $type = $request->query('type', 'kehilangan');

        return view('pages.app.report.create', compact('type'));
    }

    public function edit(string $code)
    {
        $report = Report::where('code', $code)->firstOrFail();

        // 🔐 Proteksi: hanya pemilik laporan
        if ($report->resident_id !== Auth::id()) {
            abort(403, 'Tidak diizinkan mengedit laporan ini');
        }

        return view('pages.app.report.edit', compact('report'));
    }

    public function update(updateReportRequest $request, string $code)
    {
        $report = Report::where('code', $code)->firstOrFail();

        // 🔐 Proteksi
        if ($report->resident_id !== Auth::id()) {
            abort(403, 'Tidak diizinkan mengedit laporan ini');
        }

        $data = $request->validated();

        // ❌ Jangan izinkan ubah type & ownership
        unset($data['type'], $data['resident_id'], $data['status']);

        // 📷 Jika ganti gambar
        if ($request->hasFile('image')) {
            // hapus gambar lama
            if ($report->image && Storage::disk('public')->exists($report->image)) {
                Storage::disk('public')->delete($report->image);
            }

            $data['image'] = $request->file('image')
                ->store('assets/report/image', 'public');
        }

        $report->update($data);

        Swal::toast('Laporan berhasil diperbarui.', 'success')
            ->timerProgressBar();

        return redirect()
            ->route('user.report.show', [
                'code' => $report->code,
                'from' => 'my-report'
            ])
            ->with('success', 'Laporan berhasil diperbarui');
    }

    public function destroy(string $code)
    {
        $report = Report::where('code', $code)->firstOrFail();

        // 🔐 Pastikan user punya resident
        if (!Auth::user()->resident) {
            abort(403, 'Tidak diizinkan');
        }

        // 🔐 Pastikan laporan milik resident yang login
        if ($report->resident_id !== Auth::user()->resident->id) {
            abort(403, 'Tidak diizinkan menghapus laporan ini');
        }


        // 🗑️ Soft delete laporan
        $report->delete();

         // SweetAlert langsung
        Swal::toast('Laporan berhasil dihapus.', 'success')
            ->timerProgressBar();

        return redirect()
            ->route('user.report.my-report')
            ->with('success', 'Laporan berhasil dihapus.');
    }

    public function updateStatus(string $code)
    {
        $report = Report::where('code', $code)->firstOrFail();

        // 🔐 Pastikan user punya resident
        if (!Auth::user()->resident) {
            abort(403, 'Tidak diizinkan');
        }

        // 🔐 Pastikan laporan milik sendiri
        if ($report->resident_id !== Auth::user()->resident->id) {
            abort(403, 'Tidak diizinkan mengubah status laporan ini');
        }

        // ❌ Jika sudah selesai, tidak perlu update lagi
        if ($report->status === 'selesai') {
            return redirect()
                ->back()
                ->with('info', 'Laporan ini sudah diselesaikan.');
        }

        // ✅ Update status
        $report->update([
            'status' => 'selesai',
        ]);

        return redirect()
            ->route('user.report.my-report', [
                'status' => 'selesai',
                'type' => $report->type,
            ])
            ->with('success', 'Laporan berhasil ditandai sebagai selesai.');
    }

    public function search(Request $request)
    {
        $keyword  = $request->query('q');
        $type     = $request->query('type');
        $location = $request->query('location');
        $status   = 'Aktif';

        $query = Report::where('status', $status);

        // Filter jenis laporan
        if ($type) {
            $query->where('type', $type);
        }

        // Pencarian kata kunci
        if ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'LIKE', "%{$keyword}%")
                ->orWhere('description', 'LIKE', "%{$keyword}%")
                ->orWhere('pet_characteristics', 'LIKE', "%{$keyword}%");
            });
        }

        // ===============================
        // PENCARIAN BERDASARKAN JARAK
        // ===============================
        if ($request->has('nearby') && $request->latitude && $request->longitude) {

            $lat = $request->latitude;
            $lng = $request->longitude;

            $query->selectRaw("
                *,
                (6371 * acos(
                    cos(radians(?)) *
                    cos(radians(latitude)) *
                    cos(radians(longitude) - radians(?)) +
                    sin(radians(?)) *
                    sin(radians(latitude))
                )) AS distance
            ", [$lat, $lng, $lat])
            ->orderBy('distance', 'asc');
        } else {
            $query->latest();
        }

        $reports = $query->get();

        return view('pages.app.report.search', compact(
            'reports',
            'keyword',
            'type',
            'location'
        ));
    }


    public function success(){

        return view('pages.app.report.success');
    }
}
