<?php


namespace Wefabric\TokenLogin\Console\Commands;


use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Wefabric\TokenLogin\Repositories\TokenLoginRepository;
use Wefabric\TokenLogin\TokenLogin;

class CreateLoginTokens extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'token-login:create
        {--modelClass= : The model class to use (default is from config token-login.default_model)}
        {--force : If the creation of the tokens must be forced}
    ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates the login tokens';


    /**
     * @throws \Exception
     */
    public function handle()
    {
        if($this->option('force') === false && !TokenLogin::make()->enabled()) {
            $this->output->warning('Token login disabled. Not creating tokens');
            return;
        }

        $tokenRepository = new TokenLoginRepository(app(), (string)$this->option('modelClass'));
        foreach ($tokenRepository->query()->get() as $item) {
            $item->createOrUpdateToken();
        }

        $this->output->success('Login tokens created');
    }

}
