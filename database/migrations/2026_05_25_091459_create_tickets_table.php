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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();

            $table->string('ticket_number')->unique();

            // aset yang dilaporkan
            $table->foreignId('asset_id')
                ->constrained('assets')
                ->restrictOnDelete();

            // staff pelapor
            $table->foreignId('reported_by')
                ->constrained('users')
                ->restrictOnDelete();

            // teknisi penanggung jawab
            $table->foreignId('assigned_to')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('title');

            $table->text('description');

            // low | medium | high | critical
            $table->enum('priority', [
                'low',
                'medium',
                'high',
                'critical'
            ])->default('medium');

            // open | in_progress | waiting_parts | resolved | closed | rejected
            $table->enum('status', [
                'open',
                'in_progress',
                'waiting_parts',
                'resolved',
                'closed',
                'rejected'
            ])->default('open');

            $table->timestamp('reported_at');

            $table->timestamp('resolved_at')->nullable();

            $table->timestamp('closed_at')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index('ticket_number');
            $table->index('status');
            $table->index('priority');
            $table->index('reported_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};