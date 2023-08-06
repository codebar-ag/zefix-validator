<?php

namespace App\Jobs;

use App\Models\Company;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ZefixCompanyJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $endpoint;

    protected string $username;

    protected string $password;

    /**
     * Create a new job instance.
     */
    public function __construct(protected Company $company)
    {
        $this->endpoint = config('services.zefix.endpoint');
        $this->username = config('services.zefix.username');
        $this->password = config('services.zefix.password');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        sleep(1);

        if ($this->company->zefix_completed_at) {
            return;
        }

        if (! $this->company->check_valid_uid) {

            $this->company->update([
                'zefix_status' => 'invalid uid',
                'zefix_completed_at' => now(),
            ]);

            return;
        }
        if (! $this->company->uid) {

            $this->company->update([
                'zefix_status' => 'no uid',
                'zefix_completed_at' => now(),
            ]);

            return;
        }

        $response = $this->zefixUid($this->company->uid);

        $name = Arr::get($response, 'name');
        $uid = Arr::get($response, 'uid');

        if (! $name || ! $uid) {

            $this->company->update([
                'zefix_completed_at' => now(),
            ]);

            return;
        }

        $uid = $this->convertToFormattedCHE($uid);

        $this->company->update([
            'zefix_name' => $name,
            'zefix_uid' => $uid,
            'zefix_status' => Arr::get($response, 'status'),
            'zefix_completed_at' => now(),
            'check_valid_name' => $name === $this->company->name,
        ]);

    }

    protected function zefixUid(string $uid): array
    {
        $uid = Str::remove('.', $uid);
        $uid = Str::remove('-', $uid);

        $url = "{$this->endpoint}/api/v1/company/uid/$uid";

        $response = Http::withBasicAuth($this->username, $this->password)
            ->acceptJson()
            ->get($url);

        if (! $response->successful()) {
            throw new \Exception('Zefix API error.');
        }

        $company = collect($response->json())->first();

        return [
            'status' => Arr::get($company, 'status'),
            'name' => Arr::get($company, 'name'),
            'uid' => Arr::get($company, 'uid'),
        ];
    }

    public function convertToFormattedCHE($input)
    {
        if (! is_string($input)) {
            throw new \InvalidArgumentException('Input must be a string.');
        }
        // Remove any non-digit characters from the input
        $digitsOnly = preg_replace('/[^0-9]/', '', $input);

        // Check if the input has at least 9 digits
        if (strlen($digitsOnly) < 9) {
            throw new \InvalidArgumentException('Input must have at least 9 digits.');
        }

        // Extract the first 3 characters from the input
        $firstPart = substr($digitsOnly, 0, 3);

        // Extract the remaining digits after the first 3 characters
        $remainingDigits = substr($digitsOnly, 3);

        // Split the remaining digits into groups of 3
        $groupedDigits = str_split($remainingDigits, 3);

        // Join the groups of 3 digits with dots
        $formattedRemaining = implode('.', $groupedDigits);

        // Combine the first part and the formatted remaining part with a hyphen
        $formattedOutput = $firstPart.'-'.$formattedRemaining;

        return 'CHE-'.$formattedOutput;
    }
}
