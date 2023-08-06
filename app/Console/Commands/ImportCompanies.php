<?php

namespace App\Console\Commands;

use App\Imports\CompanyImport;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ImportCompanies extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'companies:import';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import Companies';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $file = Storage::disk('public')->get('Documents-20230805-091205.xlsx');
        Excel::import(new CompanyImport(), $file);
    }
}
