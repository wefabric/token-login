<?php

namespace Wefabric\TokenLogin\Concerns;

use Carbon\Carbon;
use Wefabric\TokenLogin\Exceptions\TokenLoginException;
use Wefabric\TokenLogin\TokenLogin;

trait HasTokenLogin
{
    public function getHasTokenLoginCasts()
    {
        return [
            'login_token_expires_at' => 'datetime'
        ];
    }

    /**
     * Use false to unset the login token
     *
     * @param null|string|false $value
     * @throws \Exception
     */
    public function setLoginTokenAttribute($value = null)
    {
        if($value === false) {
            $this->attributes['login_token'] = null;
            return;
        }

        if($value === null) {
            if(!$this->defaultLoginTokenKey) {
                throw new TokenLoginException('Default token key not set in model');
            }
            $value = $this->getAttribute($this->defaultLoginTokenKey);
        }

        $this->attributes['login_token'] = TokenLogin::make()->generateToken($value);
        $this->setAttribute('login_token_expires_at', (new Carbon())->modify(config('token-login.expires_at')));
    }

    /**
     * @param false $force - Forces the creation or updating the token
     * @throws \Exception
     */
    public function createOrUpdateToken($force = false)
    {
        if($force === false && !$this->tokenLoginAllowed()) {
            return;
        }
        $this->setLoginTokenAttribute();
        $this->save();
    }

    /**
     * @throws \Exception
     */
    public function deleteToken()
    {
        $this->setLoginTokenAttribute(false);
        $this->login_token_expires_at = null;
        $this->save();
    }

    /**
     * @return bool
     */
    public function tokenLoginAllowed(): bool
    {
        return !in_array($this->getAttribute($this->tokenNotAllowedKey ?? config('token-login.not_allowed.key')), config('token-login.not_allowed.items'));
    }
}
