<?php


namespace Wefabric\TokenLogin\Console\Commands;


use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Wefabric\TokenLogin\Repositories\TokenLoginRepository;

class DeleteLoginTokens extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'token-login:delete
        {--modelClass= : The model class to use (default is from config token-login.default_model)}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deletes the login tokens';

    /**
     * @throws \Exception
     */
    public function handle()
    {
        if (!$this->confirm('Are you sure you want to delete all tokens? Users are unable to login after this action')) {
            return;
        }

        $tokenRepository = new TokenLoginRepository(app(), (string)$this->option('modelClass'));
        foreach ($tokenRepository->query()->get() as $item) {
            $item->deleteToken();
        }
        $this->output->success('Login tokens deleted.');
    }

}
