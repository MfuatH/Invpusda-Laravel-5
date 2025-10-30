<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Bidang;
use Illuminate\Support\Facades\Auth;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:super_admin,admin_barang']);
    }

    public function templateIndex()
    {
        // Load bidang templates depending on the logged in user's role
        if (Auth::user()->role === 'super_admin') {
            $bidangs = Bidang::orderBy('nama')->get();
        } else {
            // For admin_barang show only their bidang (via relation)
            $bidang = Auth::user()->bidang; // relation will return Bidang model or null
            $bidangs = $bidang ? collect([$bidang]) : collect();
        }

        return view('admin_page.settings.master_pesan', compact('bidangs'));
    }

    public function responseIndex()
    {
        // Add your response settings logic here
        return view('admin_page.settings.index');
    }

    public function updateTemplate(Request $request)
    {
        $validated = $request->validate([
            'bidang_id' => 'required|integer|exists:bidang,id',
            'content' => 'nullable|string',
        ]);

        $bidang = Bidang::findOrFail($validated['bidang_id']);
        $bidang->pesan_template = $validated['content'];
        $bidang->save();

        return back()->with('success', 'Template berhasil diperbarui.');
    }

    public function updateResponse(Request $request)
    {
        $validated = $request->validate([
            'response_type' => 'required|string',
            'content' => 'required|string',
        ]);

        // Add your response update logic here

        return back()->with('success', 'Response berhasil diperbarui.');
    }
}