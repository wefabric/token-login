<?php


namespace Wefabric\TokenLogin\Console\Commands;


use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Wefabric\TokenLogin\Repositories\TokenLoginRepository;

class DeleteExpiredLoginTokens extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'token-login:delete-expired
        {--modelClass= : The model class to use (default is from config token-login.default_model)}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deletes the expired login tokens';

    /**
     * @throws \Exception
     */
    public function handle()
    {
        $tokenRepository = new TokenLoginRepository(app(), (string)$this->option('modelClass'));
        foreach ($tokenRepository->expired()->get() as $item) {
            $item->deleteToken();
        }

        $this->output->success('Login tokens deleted.');
    }
}
