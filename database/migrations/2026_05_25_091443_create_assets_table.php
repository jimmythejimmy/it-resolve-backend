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
        Schema::create('assets', function (Blueprint $table) {
            $table->id();

            // kode internal perusahaan
            $table->string('asset_code')->unique();

            $table->string('asset_name');

            // contoh: Laptop, Printer, Router
            $table->string('category');

            $table->string('brand')->nullable();

            $table->string('model')->nullable();

            // SN perangkat wajib unik
            $table->string('serial_number')->unique();

            // JSON untuk fleksibilitas spesifikasi
            $table->json('specification')->nullable();

            $table->date('purchase_date')->nullable();

            $table->decimal('purchase_price', 15, 2)->nullable();

            // good | damaged | maintenance | retired
            $table->enum('condition', [
                'good',
                'damaged',
                'maintenance',
                'retired'
            ])->default('good');

            // active | in_repair | inactive
            $table->enum('status', [
                'active',
                'in_repair',
                'inactive'
            ])->default('active');

            $table->string('location')->nullable();

            // aset sedang dipakai siapa
            $table->foreignId('assigned_to_user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();

            $table->index('asset_code');
            $table->index('serial_number');
            $table->index('category');
            $table->index('status');
            $table->index('condition');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};