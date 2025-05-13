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
        Schema::create('master_menus', function (Blueprint $table) {
            $table->id('menu_id');
            $table->string('menu_name', 50);
            $table->string('menu_icon');
            $table->string('menu_order', 2);
            $table->boolean('is_active')->default('1');
            $table->timestamps();
        });

        Schema::create('master_submenus', function (Blueprint $table) {
            $table->id('submenu_id');
            $table->string('menu_name', 50);
            $table->string('menu_icon');
            $table->string('menu_order', 2);
            $table->boolean('is_active')->default('1');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_menus');
    }
};
