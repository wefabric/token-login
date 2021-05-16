<?php


namespace Wefabric\TokenLogin\Console\Commands;


use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Wefabric\TokenLogin\Repositories\TokenLoginRepository;
use Wefabric\TokenLogin\TokenLogin;

class RefreshLoginTokens extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'token-login:refresh
        {--modelClass= : The model class to use (default is from config token-login.default_model)}
        {--force : If the refreshing of the tokens must be forced}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refreshes the login tokens';


    /**
     * @throws \Exception
     */
    public function handle()
    {
        if($this->option('force') === false && !TokenLogin::make()->enabled()) {
           $this->output->warning('Token login disabled. Not refreshing tokens');
           return;
        }

        $tokenRepository = new TokenLoginRepository(app(), (string)$this->option('modelClass'));

        foreach ($tokenRepository->refreshable()->get() as $item) {
            if(!$item->tokenLoginAllowed()) {
                $item->deleteToken();
                continue;
            }
            $item->createOrUpdateToken();
        }

        $this->output->success('Login tokens refreshed');
    }

}
