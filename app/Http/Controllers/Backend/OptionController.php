<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Option;
use Gate;
use Illuminate\Http\Request;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Spatie\ResponseCache\Facades\ResponseCache;

class OptionController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Gate::allows('setting_menu')) {
            throw UnauthorizedException::forPermissions([]);
        }

        return view('backend.setting.general');
    }


    /**
     * Store a newly created resource in storage.
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->saveLogos($request);
        $this->saveIcons($request);

        $switchInputs = [];

        // GeneralPageSetting Switch Inputs
        if ($request->get('general_page_setting')) {
            $switchInputs = ['app__amp_status'];
        }

        $requests = $request->except(['favicon_image', 'og_image', 'site_logo', 'home__how_it_work_icon_1', 'home__how_it_work_icon_2', 'home__how_it_work_icon_3', 'home__how_it_work_icon_4', 'general_page_setting']);
        foreach ($switchInputs as $switchInput) {
            if ($request->get($switchInput) == null) {
                $requests[$switchInput] = 0;
            }
        }

        foreach ($requests as $key => $value) {
            if ($key != '_token') {
                $key = str_replace('__', '.', $key);
                $option = Option::firstOrCreate(['name' => $key]);
                $option->value = $value;
                $option->save();
            }
        }

        return redirect()->back()->withFlashSuccess('Setting updated successfully!');
    }

    /**
     * create or update logos
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     **/
    private function saveLogos(Request $request)
    {
        if (!file_exists(storage_path('app/public/logos'))) {
            mkdir(storage_path('app/public/logos'), 0777);
        }

        foreach ($request->only('favicon_image', 'og_image', 'site_logo') as $key => $file) {
            if ($image = Option::where('name', $key)->first()) {
                if (file_exists(public_path('storage/logos/' . $image->value))) {
                    //unlink(public_path('storage/logos/' . $image->value)); //removed to store for cached versions
                }
            }
            $extension = array_last(explode('.', $request->file($key)->getClientOriginalName()));
            $name = array_first(explode('.', $request->file($key)->getClientOriginalName()));
            $filename = str_slug($name) . '-' . time() . '.' . $extension;
            $request->file($key)->move(public_path('storage/logos'), $filename);
            $config = Option::updateOrCreate(['name' => $key]);
            $config->value = $filename;
            $config->save();
        }
    }

    private function saveIcons(Request $request)
    {
        if (!file_exists(storage_path('app/public/home/icons'))) {
            mkdir(storage_path('app/public/home/icons'), 0777, true);
        }

        foreach ($request->only('home__how_it_work_icon_1', 'home__how_it_work_icon_2', 'home__how_it_work_icon_3', 'home__how_it_work_icon_4') as $key => $file) {
            $key1 = str_replace('__', '.', $key);
            if ($image = Option::where('name', $key1)->first()) {
                if (file_exists(public_path('storage/home/icons/' . $image->value))) {
                    if (!in_array($image->value, ['icon-1.png', 'icon-2.png', 'icon-3.png', 'icon-4.png'])) {
                        //unlink(storage_path('app/public/home/icons/' . $image->value)); //removed to store for cached versions
                    }
                }
            }
            $extension = array_last(explode('.', $request->file($key)->getClientOriginalName()));
            $name = array_first(explode('.', $request->file($key)->getClientOriginalName()));
            $filename = str_slug($name) . '-' . time() . '.' . $extension;
            $request->file($key)->move(public_path('storage/home/icons'), $filename);
            $config = Option::updateOrCreate(['name' => $key1]);
            $config->value = $filename;
            $config->save();
        }
    }

    public function general()
    {
        return view('backend.setting.partials.general');
    }

    public function homePage()
    {
        return view('backend.setting.partials.homepage');
    }

    public function footer()
    {
        return view('backend.setting.partials.footer');
    }

    public function scripts()
    {
        return view('backend.setting.partials.header-footer-scripts');
    }

    public function logo()
    {
        return view('backend.setting.partials.logo');
    }

    public function colors()
    {
        return view('backend.setting.partials.colors');
    }

    public function cache()
    {
        return view('backend.setting.partials.cache');
    }

    public function cachePurge(Request $request)
    {
        ResponseCache::forget($request->perge_url);
        return redirect()->back()->withFlashSuccess(url($request->perge_url) . ' purged successfully!');
    }

    public function cachePurgeAll()
    {
        ResponseCache::clear();
        return redirect()->back()->withFlashSuccess('All cache purged successfully!');
    }
}
