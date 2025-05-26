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
        Schema::create('questionnaire_actions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('questionnaire_session_id')->constrained()->onDelete('cascade');
            $table->string('node_key');
            $table->string('selected_option');
            $table->text('justification')->nullable();
            $table->timestamps();
        });
        }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questionnaire_actions');
    }
};
