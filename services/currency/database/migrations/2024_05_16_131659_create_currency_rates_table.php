<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('currency_rates', function (Blueprint $table) {
            $table->id();
            $table->string('currency_code', 3);
            $table->unsignedInteger('buy_rate');
            $table->unsignedInteger('sale_rate');
            $table->timestamp('fetched_at')->useCurrent();
            $table->timestamps();

            $table->index(['currency_code', 'fetched_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('currency_rates');
    }
};
