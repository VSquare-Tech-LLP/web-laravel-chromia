<?php

namespace App\Http\Controllers\Backend;

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
}
