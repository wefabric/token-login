<?php

namespace Wefabric\TokenLogin;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Wefabric\TokenLogin\Actions\GenerateTokenAction;
use Wefabric\TokenLogin\Actions\LoginByTokenAction;
use Wefabric\TokenLogin\Concerns\TokenGenerateInterface;
use Wefabric\TokenLogin\Exceptions\InvalidArgumentException;
use Wefabric\TokenLogin\Exceptions\TokenLoginException;

class TokenLogin
{
    const TRAIT = 'Wefabric\TokenLogin\Concerns\HasTokenLogin';

    /**
     * @var bool|null
     */
    public static ?bool $enabled = null;

    /**
     * @return TokenLogin
     */
    public static function make(): TokenLogin
    {
        return new self();
    }

    /**
     * @param string $uniqueValue
     * @return string
     */
    public function generateToken(string $uniqueValue): string
    {
        $class = app(config('token-login.token_generation_class'));
        if(!$class instanceof TokenGenerateInterface) {
            throw new TokenLoginException(__('Token generate class must implement :interface', ['interface' => TokenGenerateInterface::class]));
        }
        return $class->execute($uniqueValue);
    }

    /**
     * @param string $token
     * @param null $model
     * @return bool|\Illuminate\Contracts\Auth\Authenticatable
     * @throws \Exception
     */
    public function login(string $token, $model = null)
    {
        return $this->enabled() ? app(LoginByTokenAction::class)->execute($model, $token) : false;
    }

    /**
     * @param Model $model
     * @param string $redirect
     * @return string
     */
    public function loginUrl($model = null, string $redirect = ''): string
    {
        if(!$this->hasTrait($model)) {
            throw new InvalidArgumentException(__('Model has to use trait :trait', ['trait' => self::TRAIT]));
        }
        return route('token-login.login', ['token' => $model->login_token, 'redirect' => $redirect ? $redirect : url()]);
    }

    /**
     * @param  object|string $model
     * @return bool
     */
    public function hasTrait($model): bool
    {
        if(!$traits = class_uses_recursive($model)) {
            return false;
        }
        return isset($traits[self::TRAIT]);
    }

    /**
     * @param Model|string $model
     * @return string
     */
    public function model($model = ''): string
    {
        $model = $model ? $model : config('token-login.default_model');

        if(!$this->hasTrait($model)) {
            throw new InvalidArgumentException(__('Model has to use trait :trait', ['trait' => self::TRAIT]));
        }

        return $model;
    }

    /**
     * @return string
     */
    public function loginPath(): string
    {
        return config('token-login.login_path');
    }

    /**
     * @return bool
     */
    public function enabled(): bool
    {
        if(!self::$enabled) {
            $this->setEnabled((bool)config('token-login.enabled'));
        }
        return self::$enabled;
    }

    /**
     * @param bool $enabled
     */
    public function setEnabled(bool $enabled): void
    {
        self::$enabled = $enabled;
    }

}
