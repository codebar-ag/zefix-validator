<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();

            $table->string('account_number')->nullable();

            $table->string('name');
            $table->string('uid')->nullable();

            $table->string('zefix_name')->nullable();
            $table->string('zefix_uid')->nullable();
            $table->string('zefix_status')->nullable();
            $table->dateTime('zefix_completed_at')->nullable();

            $table->boolean('check_valid_name')->nullable();
            $table->boolean('check_valid_uid')->nullable();
            $table->boolean('check_account_number_duplicate')->nullable();
            $table->integer('check_account_number_duplicate_count')->nullable();
            $table->boolean('check_uid_duplicate')->nullable();
            $table->integer('check_uid_duplicate_count')->nullable();
            $table->dateTime('check_completed_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
