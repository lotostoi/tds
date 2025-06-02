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
        Schema::create('external_traffic', function (Blueprint $table) {
            $table->id();
            $table->foreignId('seller_id')->index()->constrained('sellers')->onDelete('cascade');
            $table->date('event_date')->index();
            $table->string('utm_source');
            $table->string('utm_medium');
            $table->text('utm_campaign')->nullable();
            $table->string('utm_term')->index();
            $table->text('utm_content')->nullable();
            $table->unsignedInteger('clicks')->default(0);
            $table->unsignedInteger('ordered_items')->default(0);
            $table->decimal('order_value_rub', 10, 2)->default(0.00);
            $table->string('platform')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('external_traffic');
    }
};
