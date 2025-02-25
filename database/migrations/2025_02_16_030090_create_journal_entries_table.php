<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('journal_entries', function (Blueprint $table) {
            $table->id();
            $table->string('journalable_type')->nullable();
            $table->unsignedBigInteger('journalable_id')->nullable();
            $table->date('date');
            $table->string('remarks')->nullable();
            $table->unsignedBigInteger('base_currency_id')->nullable();
            $table->unsignedBigInteger('currency_id')->nullable();
            $table->unsignedBigInteger('exchange_rate')->nullable();
            $table->timestamps();
        });

    }

    public function down(): void
    {
        Schema::dropIfExists('journal_entries');
    }
};
