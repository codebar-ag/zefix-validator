<?php

namespace App\Console\Commands;

use App\Jobs\ValidateCompanyJob;
use App\Models\Company;
use Illuminate\Console\Command;

class ValidateCompanies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'companies:validate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Validate Companies';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $collection = Company::all();

        Company::whereNotNull('uid')->each(function ($company) use ($collection) {
            ValidateCompanyJob::dispatch($company, $collection);
        });

    }
}
