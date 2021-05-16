<?php

namespace Wefabric\TokenLogin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Wefabric\TokenLogin\TokenLogin;

class LoginController extends Controller
{

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function login(Request $request)
    {
        if(!$request->input('token')) {
            abort(401);
        }

        if(TokenLogin::make()->login($request->input('token')) === false) {
            abort(403);
        }

        $request->session()->regenerate();

        return redirect($request->input('redirect', config('token-login.default_redirect')));
    }
}
