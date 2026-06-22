<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('action', 50);       // created | updated | deleted | checkout
            $table->string('model_type', 100);  // Product | Transaction | User
            $table->unsignedBigInteger('model_id')->nullable();
            $table->string('label')->nullable(); // readable name e.g. "Charger USB-C 65W"
            $table->json('changes')->nullable(); // {old: {...}, new: {...}}
            $table->timestamps();

            $table->index(['model_type', 'model_id']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
