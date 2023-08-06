<?php

namespace App\Jobs;

use App\Models\Company;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;

class ValidateCompanyJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(protected Company $company, protected Collection $collection)
    {

    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $accountNumberCount = $this->collection->where('account_number', $this->company->account_number)->count();
        $uidNumberCount = $this->collection->where('uid', $this->company->uid)->count();

        $this->company->update([
            'check_valid_uid' => Validator::make(['uid' => $this->company->uid], [
                'uid' => 'required|regex:/^CHE-[0-9]{3}\.[0-9]{3}\.[0-9]{3}$/',
            ])->passes(),
            'check_account_number_duplicate' => $accountNumberCount > 1,
            'check_account_number_duplicate_count' => $accountNumberCount,
            'check_uid_duplicate' => $uidNumberCount > 1,
            'check_uid_duplicate_count' => $uidNumberCount,
            'check_completed_at' => now(),
        ]);

    }
}
