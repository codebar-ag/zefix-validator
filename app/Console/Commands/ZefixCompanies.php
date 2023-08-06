<?php

namespace App\Console\Commands;

use App\Jobs\ZefixCompanyJob;
use App\Models\Company;
use Illuminate\Console\Command;

class ZefixCompanies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'companies:zefix';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Zefix Companies';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Company::whereNull('zefix_completed_at')->whereNotNull('uid')->each(function ($company) {
            ZefixCompanyJob::dispatch($company);
        });

    }
}
