<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SettingController extends Controller
{
    public function index(Request $request)
    {
        $settings = Setting::query()
            ->when($request->search, function ($query, $search) {
                $query->where('key', 'like', "%{$search}%")
                    ->orWhere('value', 'like', "%{$search}%")
                    ->orWhere('group', 'like', "%{$search}%");
            })
            ->get()
            ->groupBy('group');

        return view('settings.index', compact('settings'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'key' => 'required|string|unique:settings',
            'value' => 'required',
            'group' => 'required|string',
            'type' => 'required|string|in:string,integer,boolean,json,array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $setting = Setting::create($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Setting created successfully',
            'setting' => $setting
        ]);
    }

    public function update(Request $request, Setting $setting)
    {
        $validator = Validator::make($request->all(), [
            'value' => 'required',
            'group' => 'required|string',
            'type' => 'required|string|in:string,integer,boolean,json,array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $setting->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Setting updated successfully',
            'setting' => $setting
        ]);
    }

    public function destroy(Setting $setting)
    {
        $setting->delete();

        return response()->json([
            'success' => true,
            'message' => 'Setting deleted successfully'
        ]);
    }

    public function bulkUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'settings' => 'required|array',
            'settings.*.key' => 'required|string',
            'settings.*.value' => 'required',
            'settings.*.group' => 'required|string',
            'settings.*.type' => 'required|string|in:string,integer,boolean,json,array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        foreach ($request->settings as $settingData) {
            Setting::updateOrCreate(
                ['key' => $settingData['key']],
                $settingData
            );
        }

        return response()->json([
            'success' => true,
            'message' => 'Settings updated successfully'
        ]);
    }
}
