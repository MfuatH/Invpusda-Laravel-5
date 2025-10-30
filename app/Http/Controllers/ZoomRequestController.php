<?php

namespace App\Http\Controllers;

use App\RequestLinkZoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ZoomRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:super_admin,admin_barang']);
    }

    public function index()
    {
        $requests = RequestLinkZoom::with('bidang')
            ->when(Auth::user()->role !== 'super_admin', function($query) {
                return $query->where('bidang_id', Auth::user()->bidang_id);
            })
            ->latest()
            ->paginate(10);

        return view('admin_page.approvals.zoom', compact('requests'));
    }

    public function approve(RequestLinkZoom $reqZoom)
    {
        if (Auth::user()->role === 'admin_barang' && 
            (!$reqZoom->bidang_id || Auth::user()->bidang_id !== $reqZoom->bidang_id)) {
            abort(403, 'Unauthorized action.');
        }

        // Here you would typically integrate with Zoom API to create a meeting
        // For now, we'll just update the status
        $reqZoom->update([
            'status' => 'approved',
            'link_zoom' => 'https://zoom.us/j/example', // Replace with actual Zoom link generation
            'approved_by' => Auth::id()
        ]);

        return redirect()->route('zoom.requests.index')
            ->with('success', 'Permintaan link zoom berhasil disetujui.');
    }

    public function reject(RequestLinkZoom $reqZoom)
    {
        if (Auth::user()->role === 'admin_barang' && 
            (!$reqZoom->bidang_id || Auth::user()->bidang_id !== $reqZoom->bidang_id)) {
            abort(403, 'Unauthorized action.');
        }

        $reqZoom->update([
            'status' => 'rejected',
            'rejected_by' => Auth::id()
        ]);

        return redirect()->route('zoom.requests.index')
            ->with('success', 'Permintaan link zoom berhasil ditolak.');
    }
}