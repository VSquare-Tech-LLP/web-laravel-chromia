<?php

namespace App\Http\Controllers\Frontend;

use App\Domains\Auth\Models\User;
use App\Mail\ContactMail;
use App\Models\Blog\Category;
use App\Models\Blog\Post;
use App\Rules\Captcha;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

/**
 * Class HomeController.
 */
class HomeController
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        //return view('frontend.index');
        return view('frontend.pages.coming-soon');
    }
    public function getContactUs()
    {
        return view('frontend.pages.contact-us');
    }

    public function saveContactUs(Request $request)
    {

//        $rules = [
//            'name' => 'required',
//            'email' => 'required|email',
//            'subject' => 'required',
//            'message' => 'required',
//            'g-recaptcha-response' => ['required_if:captcha_status,true', new Captcha],
//        ];
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'subject' => 'required',
            'message' => 'required'
            ]);

        $myEmail = env('CONTACT_MAIL');

        $details = [
            'name' => $request->name,
            'email' => $request->email,
            'subject' => $request->subject,
            'message' => $request->message,
        ];

//        Mail::to($myEmail)->send(new ContactMail($details));

        \Session::flash('flash_success', 'Your message has been received, We will be in touch shortly');

        return redirect()->back();

    }
}
