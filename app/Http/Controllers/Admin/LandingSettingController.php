<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LandingSetting;
use Illuminate\Http\Request;

class LandingSettingController extends Controller
{
    public function index()
    {
        $settings = LandingSetting::orderBy('group')->orderBy('key')->get()->groupBy('group');
        return view('admin.landing-settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        // ── Simple settings ────────────────────
        if ($request->has('set') && is_array($request->set)) {
            foreach ($request->set as $key => $value) {
                LandingSetting::where('key', $key)->update(['value' => $value ?? '']);
            }
        }

        // ── Decoded array settings ─────────────
        $decodableKeys = ['statistics_data', 'features_data', 'how_steps', 'testimonials_data', 'pricing_plans', 'benefits_data', 'faq_data', 'clients_logos'];

        if ($request->has('dec') && is_array($request->dec)) {
            foreach ($request->dec as $dbKey => $itemsArray) {
                if (!in_array($dbKey, $decodableKeys)) continue;

                $clean = [];
                foreach ($itemsArray as $item) {
                    // Hanya ambil field yg ada isinya
                    $filtered = array_filter($item, fn($v) => $v !== null && $v !== '');

                    // Special: pricing features dari comma-separated jadi array
                    if ($dbKey === 'pricing_plans' && isset($filtered['features_text'])) {
                        $filtered['features'] = array_map('trim', explode(',', $filtered['features_text']));
                        unset($filtered['features_text']);
                    }

                    if (!empty($filtered)) {
                        // Booleans
                        if (isset($filtered['featured'])) {
                            $filtered['featured'] = (bool) $filtered['featured'];
                        }
                        $clean[] = $filtered;
                    }
                }

                if (!empty($clean)) {
                    LandingSetting::where('key', $dbKey)->update(['value' => json_encode($clean, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)]);
                }
            }
        }

        return back()->with('success', 'Pengaturan halaman utama berhasil diperbarui.');
    }
}
