<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('roast_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('roaster_name');
            $table->string('bean_name');
            $table->string('origin')->nullable();
            $table->string('varietas')->nullable();
            $table->string('process_method')->nullable();
            $table->decimal('green_weight', 10, 2);
            $table->decimal('charge_temp', 8, 2)->nullable();
            $table->decimal('roasted_weight', 10, 2)->nullable();
            $table->unsignedInteger('duration_seconds')->nullable();
            $table->json('checklist')->nullable();
            $table->json('temp_log')->nullable();
            $table->text('notes')->nullable();
            $table->dateTime('roast_date');
            $table->timestamps();

            $table->index('roaster_name');
            $table->index('roast_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('roast_logs');
    }
};
