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
        Schema::dropIfExists('identification_details');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('identification_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('questionnaire_session_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('email');
            $table->string('company')->nullable();
            $table->string('system_name')->nullable();
            $table->timestamps();
        });
    }
};
