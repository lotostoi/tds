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
        Schema::dropIfExists('sellers');
        Schema::create('sellers', function (Blueprint $table) {

            $table->id();
            $table->bigInteger('seller_id')->unique()->index()->comment('ID продавца на маркетплейсе');
            $table->integer('pp_id')->nullable()->comment('ID партнера в партнерской программе - для интеграции с облаком');
            $table->string('title')->nullable()->comment('Название маркетплейса');
            $table->string('platform')->nullable()->comment('Платформа маркетплейса (ozon, wildberries, etc.)');
            $table->string('url')->nullable()->comment('API ключ для интеграции с маркетплейсом');
            $table->string('api_key')->nullable()->comment('API ключ для интеграции с маркетплейсом');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sellers');
    }
};
