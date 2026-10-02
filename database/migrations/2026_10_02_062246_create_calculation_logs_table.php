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
        Schema::create('calculation_logs', function (Blueprint $table) {
            $table->id();
            $table->double('flow_before');
            $table->double('cod_before');
            $table->double('load_before');
            $table->double('err_before')->default(10);
            $table->double('flow_after');
            $table->double('cod_after');
            $table->double('load_after');
            $table->double('err_after')->default(10);
            $table->double('load_change');
            $table->double('unc_combined');
            $table->double('kwh_extra')->default(0);
            $table->double('kwh_saved')->default(0);
            $table->double('co2_net')->default(0);
            $table->string('verdict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calculation_logs');
    }
};
