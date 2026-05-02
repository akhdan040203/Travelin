<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('destination_id')->constrained()->cascadeOnDelete();
            $table->date('departure_date');
            $table->date('return_date');
            $table->integer('quota');
            $table->integer('booked')->default(0);
            $table->decimal('price', 12, 2)->nullable(); // Override destination price if needed
            $table->string('meeting_point')->nullable();
            $table->enum('status', ['open', 'closed', 'full', 'departed', 'completed', 'cancelled'])->default('open');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
