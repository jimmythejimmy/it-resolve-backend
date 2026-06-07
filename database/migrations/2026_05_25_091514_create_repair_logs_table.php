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
        Schema::create('repair_logs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('ticket_id')
                ->constrained('tickets')
                ->cascadeOnDelete();

            $table->foreignId('asset_id')
                ->constrained('assets')
                ->restrictOnDelete();

            // siapa yang melakukan aksi
            $table->foreignId('actor_user_id')
                ->constrained('users')
                ->restrictOnDelete();

            // status sebelumnya
            $table->string('from_status')->nullable();

            // status baru
            $table->string('to_status');

            // created | assigned | repaired | closed | etc
            $table->string('action_type');

            $table->text('notes')->nullable();

            // data tambahan fleksibel
            $table->json('metadata')->nullable();

            $table->timestamp('logged_at');

            $table->timestamps();

            $table->index('action_type');
            $table->index('logged_at');
            $table->index('to_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repair_logs');
    }
};