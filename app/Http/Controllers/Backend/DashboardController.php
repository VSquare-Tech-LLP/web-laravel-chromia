<?php

namespace App\Http\Controllers\Backend;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Spatie\Permission\Exceptions\UnauthorizedException;

/**
 * Class DashboardController.
 */
class DashboardController
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        return view('backend.dashboard');
    }

    public function getFileManager()
    {
        if (!Gate::allows('file_manager_access')) {
            throw UnauthorizedException::forPermissions([]);
        }

        return view('backend.file-manager.index');
    }

    public function robotsFileRead()
    {
        if (!Gate::allows('robots_text_menu')) {
            throw UnauthorizedException::forPermissions([]);
        }

        $file = file_get_contents(public_path('robots.txt'));
        return view('backend.robots.form', compact('file'));
    }

    public function robotsFileWrite(Request $request)
    {
        $file = public_path("robots.txt");
        $fp = fopen($file, "w");
        $data = $request->robots_file;
        fwrite($fp, $data);
        fclose($fp);
        return redirect()->back()->withType('success')->withMessage(__('alerts.backend.robots.updated'));
    }
}
