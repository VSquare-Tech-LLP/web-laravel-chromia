<?php
/**
 * Created by PhpStorm.
 * User: r.r
 * Date: 04/04/18
 * Time: 5:01 PM
 */

namespace App\Services;

use App\Models\Redirect;
use Illuminate\Support\Facades\Schema;
use Spatie\MissingPageRedirector\Redirector\Redirector;
use Symfony\Component\HttpFoundation\Request;

class DatabaseRedirector implements Redirector
{
    public function getRedirectsFor(Request $request): array
    {
        $redirects = Schema::hasTable('redirects')? Redirect::all() : [];
        $redirectsArray = [];

        foreach ($redirects as $redirect) {
            $redirectsArray[$redirect->from_url] = [$redirect->to_url, $redirect->status_code];
        }

        return $redirectsArray;
    }
}
