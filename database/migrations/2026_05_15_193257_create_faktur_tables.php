<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('faktur', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nomor_invoice')->unique();
            $table->string('alamat_pengiriman', 100);
            $table->string('kode_pos', 5);
            $table->bigInteger('total_harga');
            $table->timestamps();
        });

        Schema::create('faktur_item', function (Blueprint $table) {
            $table->id();
            $table->foreignId('faktur_id')->constrained('faktur')->onDelete('cascade');
            $table->foreignId('barang_id')->constrained('barang')->onDelete('cascade');
            $table->integer('kuantitas');
            $table->bigInteger('subtotal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faktur_tables');
    }
};
