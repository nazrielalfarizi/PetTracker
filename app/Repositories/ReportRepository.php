<?php

namespace App\Repositories;

use App\Models\Report;
use App\Models\ReportCategory;
use App\Interfaces\ReportRepositoryInterface;

class ReportRepository implements ReportRepositoryInterface
{
    /**
     * Ambil semua laporan
     */
    public function getAllReports()
    {
        return Report::latest()->get();
    }

    /**
     * Ambil laporan terbaru (dashboard)
     */
    public function getLatestReports(int $limit = 5)
    {
        return Report::latest()->limit($limit)->get();
    }

    public function getReportsByType(string $type)
    {
        return Report::where('type', $type)
            ->latest()
            ->get();
    }

    /**
     * Ambil laporan milik user login
     */
    public function getReportsByUserId(int $userId)
    {
        return Report::where('resident_id', $userId)
            ->latest()
            ->get();
    }

    /**
     * Ambil laporan berdasarkan status
     */
    public function getReportsByStatus(string $status)
    {
        return Report::where('status', $status)
            ->latest()
            ->get();
    }

    /**
     * Ambil laporan berdasarkan kategori
     */
    public function getReportsByCategory(string $categoryName)
    {
        $category = ReportCategory::where('name', $categoryName)->first();

        if (!$category) {
            return collect();
        }

        return Report::where('report_category_id', $category->id)
            ->latest()
            ->get();
    }

    /**
     * Ambil laporan berdasarkan ID
     */
    public function getReportById(int $id)
    {
        return Report::findOrFail($id);
    }

    /**
     * Ambil laporan berdasarkan kode
     */
    public function getReportByCode(string $code)
    {
        return Report::where('code', $code)->firstOrFail();
    }

    /**
     * Simpan laporan baru (kehilangan / temuan)
     */
    public function createReport(array $data)
    {
        // pastikan status ada
        if (!isset($data['status'])) {
            $data['status'] = 'aktif';
        }

        return Report::create($data);
    }

    /**
     * Update laporan
     */
    public function updateReport(int $id, array $data)
    {
        $report = $this->getReportById($id);
        $report->update($data);

        return $report;
    }

    /**
     * Hapus laporan (soft delete)
     */
    public function deleteReport(int $id)
    {
        $report = $this->getReportById($id);
        return $report->delete();
    }
}
