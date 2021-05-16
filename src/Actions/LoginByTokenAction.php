<?php


namespace Wefabric\TokenLogin\Actions;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Wefabric\TokenLogin\Repositories\TokenLoginRepository;

class LoginByTokenAction
{
    /**
     * @param $model
     * @param string $token
     * @return bool|\Illuminate\Contracts\Auth\Authenticatable
     * @throws \Exception
     */
    public function execute($model, string $token)
    {
        $tokenRepository = new TokenLoginRepository(app(), $model);

        if(!$item = $tokenRepository->byToken($token)->first()) {
            return false;
        }

        if(!$item->tokenLoginAllowed()) {
            return false;
        }

        if(!$result = Auth::guard()->loginUsingId($item->id)) {
            return false;
        }

        if(Auth::guard()->check()) {
            return $result;
        }

        return false;
    }
}
