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
        Schema::dropIfExists('clicks');

        Schema::create('clicks', function (Blueprint $table) {
            $table->id();
            $table->string('click_id')->unique();
            $table->string('item_id');
            $table->integer('pp_id')->nullable();
            $table->bigInteger('seller_id')->nullable();
            $table->string('marketplace')->comment('wb или oz');
            $table->string('link_id')->comment('ID партнерской ссылки');
            $table->bigInteger('blogger_id')->comment('ID блогера');
            $table->string('utm_medium')->nullable();
            $table->string('utm_source')->nullable();
            $table->string('ip_address');
            $table->text('user_agent');
            $table->string('referer')->nullable();
            $table->string('status')->default('new');
            $table->timestamps();

            $table->index('click_id');
            $table->index('item_id');
            $table->index('seller_id');
            $table->index('link_id');
            $table->index('blogger_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clicks');
    }
};
