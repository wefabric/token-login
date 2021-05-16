<?php


namespace Wefabric\TokenLogin\Repositories;


use Carbon\Carbon;
use Illuminate\Container\Container as Application;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Wefabric\TokenLogin\TokenLogin;

class TokenLoginRepository extends BaseRepository
{

    protected array $fieldsSearchable = [];

    protected string $modelClass;

    /**
     * TokenLoginRepository constructor.
     * @param Application $app
     * @param Model|string $model
     * @throws \Exception
     */
    public function __construct(Application $app, $model = '')
    {
        $this->modelClass = TokenLogin::make()->model($model);
        parent::__construct($app);
    }

    /**
     * @return array|string[]
     */
    public function getFieldsSearchable(): array
    {
        return $this->fieldsSearchable;
    }

    /**
     * @param string $token
     * @param bool $validate
     * @param Carbon|null $expireDate
     * @return Builder
     */
    public function byToken(string $token, bool $validate = true, Carbon $expireDate = null): Builder
    {
        $query = $this->query()
            ->where('login_token', $token);

        if($validate) {
            if(!$expireDate) {
                $expireDate = new Carbon();
            }
            $query = $query->where('login_token_expires_at', '>=', $expireDate);
        }
        return $query;
    }

    /**
     * @param Carbon|null $expireDate
     * @return Builder
     */
    public function notExpired(Carbon $expireDate = null): Builder
    {
        if($expireDate === null) {
            $expireDate = new Carbon();
        }
        return $this->query()->where('login_token_expires_at', '>=', $expireDate);
    }

    /**
     * @param Carbon|null $expireDate
     * @return Builder
     */
    public function expired(Carbon $expireDate = null): Builder
    {
        if($expireDate === null) {
            $expireDate = new Carbon();
        }
        return $this->query()->where('login_token_expires_at', '<', $expireDate);
    }

    /**
     * @return Builder
     */
    public function nullable(): Builder
    {
        return $this->query()->whereNull('login_token_expires_at');
    }

    /**
     * @param Carbon|null $expireDate
     * @return Builder
     */
    public function refreshable(Carbon $expireDate = null): Builder
    {
        if($expireDate === null) {
            $expireDate = new Carbon();
        }
        return $this->query()->whereNull('login_token_expires_at')->orWhere('login_token_expires_at', '<', $expireDate);
    }

    public function allowed()
    {
        return $this->query()->whereNotIn(config('token-login.not_allowed.key'), config('token-login.not_allowed.items'));
    }

    /**
     * @return string
     */
    public function model(): string
    {
        return $this->modelClass;
    }
}
