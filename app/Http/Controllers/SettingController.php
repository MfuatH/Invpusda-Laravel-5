<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:super_admin,admin_barang']);
    }

    public function templateIndex()
    {
        // Add your template settings logic here
        return view('settings.template');
    }

    public function responseIndex()
    {
        // Add your response settings logic here
        return view('settings.response');
    }

    public function updateTemplate(Request $request)
    {
        $validated = $request->validate([
            'template_name' => 'required|string',
            'content' => 'required|string',
        ]);

        // Add your template update logic here

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