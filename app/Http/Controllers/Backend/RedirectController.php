<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Redirect;
use Gate;
use Illuminate\Http\Request;
use Spatie\Permission\Exceptions\UnauthorizedException;

class RedirectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Gate::allows('redirects_access')) {
            throw UnauthorizedException::forPermissions([]);
        }

        return view('backend.redirect.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Gate::allows('redirects_create')) {
            throw UnauthorizedException::forPermissions([]);
        }

        $validatedData = $request->validate([
            "from_url" => "string|nullable|distinct",
            "to_url" => "required_with:from_url",
            "status_code" => "in:301,302",
        ]);
        Redirect::create($validatedData);

        return redirect()->route('admin.redirects.index')->withFlashSuccess(__('The Redirect URL is successfully saved.'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Redirect  $redirect
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Redirect $redirect)
    {
        if (!Gate::allows('redirects_update')) {
            throw UnauthorizedException::forPermissions([]);
        }

        $validatedData = $request->validate([
            "from_url" => "string|nullable|distinct",
            "to_url" => "required_with:from_url",
            "status_code" => "in:301,302",
        ]);
        $redirect->update($validatedData);

        return redirect()->route('admin.redirects.index')->withFlashSuccess(__('The Redirect record is successfully updated.'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Redirect  $redirect
     * @return \Illuminate\Http\Response
     */
    public function destroy(Redirect $redirect)
    {
        if (!Gate::allows('redirects_delete')) {
            throw UnauthorizedException::forPermissions([]);
        }

        $redirect->delete();

        return redirect()->back()->withFlashSuccess(__('The Redirect URL is successfully deleted.'));
    }
}
