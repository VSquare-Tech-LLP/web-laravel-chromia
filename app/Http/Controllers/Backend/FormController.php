<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Form;
use Gate;
use Illuminate\Http\Request;
use Spatie\Permission\Exceptions\UnauthorizedException;

class FormController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @throws \Spatie\Permission\Exceptions\UnauthorizedException
     */
    public function index()
    {
        if (!Gate::allows('form_access')) {
            throw UnauthorizedException::forPermissions([]);
        }

        $forms = Form::get();
        return view('backend.forms.index', compact('forms'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     * @throws \Spatie\Permission\Exceptions\UnauthorizedException
     */
    public function create()
    {
        if (!Gate::allows('form_create')) {
            throw UnauthorizedException::forPermissions([]);
        }

        return view('backend.forms.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Spatie\Permission\Exceptions\UnauthorizedException
     */
    public function store(Request $request)
    {
        if (!Gate::allows('form_create')) {
            throw UnauthorizedException::forPermissions([]);
        }

        $request->validate([
            'title' => 'required|max:255',
            'mail_to' => 'required',
            'mail_from' => 'required',
            'mail_subject' => 'required',
        ]);


        Form::create([
            'title' => $request->title,
            'form_template' => json_encode(json_decode($request->form_template)),
            'status' => $request->status ? 1 : 0,
            'captcha' => $request->captcha ? 1 : 0,
            'mail_to' => $request->mail_to,
            'mail_from' => $request->mail_from,
            'mail_subject' => $request->mail_subject,
        ]);

        return redirect()->route('admin.forms.index')->withType('success')->withMessage('Form is successfully created');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Form $form
     * @return \Illuminate\Http\Response
     * @throws \Spatie\Permission\Exceptions\UnauthorizedException
     */
    public function edit(Form $form)
    {
        if (!Gate::allows('form_edit')) {
            throw UnauthorizedException::forPermissions([]);
        }

        return view('backend.forms.edit', compact('form'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Models\Form $form
     * @return \Illuminate\Http\Response
     * @throws \Spatie\Permission\Exceptions\UnauthorizedException
     */
    public function update(Request $request, Form $form)
    {
        if (!Gate::allows('form_edit')) {
            throw UnauthorizedException::forPermissions([]);
        }

        $data = [
            'title' => $request->title,
            'form_template' => json_encode(json_decode($request->form_template)),
            'status' => $request->status ? 1 : 0,
            'captcha' => $request->captcha ? 1 : 0,
            'mail_to' => $request->mail_to,
            'mail_from' => $request->mail_from,
            'mail_subject' => $request->mail_subject,
        ];
        $form->update($data);
        return redirect()->route('admin.forms.index')->withType('success')->withMessage('Form is successfully updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Form $form
     * @return \Illuminate\Http\Response
     * @throws \Spatie\Permission\Exceptions\UnauthorizedException
     */
    public function destroy(Form $form)
    {
        if (!Gate::allows('form_delete')) {
            throw UnauthorizedException::forPermissions([]);
        }

        $form->delete();
        return redirect()->route('admin.forms.index')->withType('success')->withMessage('Form is successfully deleted');
    }
}
