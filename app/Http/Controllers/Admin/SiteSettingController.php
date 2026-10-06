<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class SiteSettingController extends Controller
{
    public function index()
    {
        $settings = SiteSetting::all()->keyBy('key');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->except('_token');

        foreach ($data as $key => $value) {
            if ($request->hasFile($key)) {
                $path = $request->file($key)->store('settings', 'public');
                SiteSetting::setByKey($key, $path, 'general', null, 'image');
            } else {
                SiteSetting::setByKey($key, $value);
            }
        }

        AuditLog::log('Settings Updated', "ওয়েবসাইটের সেটিংস আপডেট করা হয়েছে।");

        return redirect()->back()->with('success', 'ওয়েবসাইট সেটিংস সফলভাবে আপডেট করা হয়েছে!');
    }
}
