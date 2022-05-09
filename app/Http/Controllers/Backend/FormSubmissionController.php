<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Mail\Frontend\FormSubmission as FrontendFormSubmission;
use App\Models\Form;
use App\Models\FormSubmission;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Exceptions\UnauthorizedException;

class FormSubmissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @throws \Spatie\Permission\Exceptions\UnauthorizedException
     */
    public function index()
    {
        if (!Gate::allows('form_submission_access')) {
            throw UnauthorizedException::forPermissions([]);
        }

        $submissions = optional();
        $form = optional();

        $forms = Form::get();
        if (request()->form) {
            $form = Form::where('id', request()->form)->first();
            $submissions = FormSubmission::with(['form'])->where('form_id', request()->form)->get();
        }
        return view('backend.formsubmissions.index', compact('submissions', 'forms', 'form'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
	    $form = Form::find($request->id);
	    if($form->captcha) {
		    $request->validate([
			    'g-recaptcha-response' => ['required', 'captcha'],
		    ], ['required' => ':attribute is mandatory', 'g-recaptcha-response.required' => ' Select the captcha']);
	    }

//        if($validatedData->fails()){
//            return response()->json(['success' => 'false', 'message' => 'Select the captcha']);
//        }

        $data = [
            'form_id' => $request->id,
            'form_data' => json_encode($request->except(['id'])),
        ];

        //Mail::send(new FrontendFormSubmission($request, $form)); //TODO:enable after SMTP setup
        FormSubmission::create($data);
        return response()->json(['success' => 'true', 'message' => 'save data success']);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\FormSubmission $formSubmission
     * @return \Illuminate\Http\Response
     * @throws \Spatie\Permission\Exceptions\UnauthorizedException
     */
    public function destroy(FormSubmission $formSubmission)
    {
        if (!Gate::allows('form_submission_delete')) {
            throw UnauthorizedException::forPermissions([]);
        }

        $formSubmission->delete();
        return redirect()->route('admin.form-submission.index')->withType('success')->withMessage(__('alerts.backend.forms.deleted'));
    }
}
