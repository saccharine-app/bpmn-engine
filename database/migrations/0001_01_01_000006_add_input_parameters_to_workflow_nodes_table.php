<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('workflow_nodes', function (Blueprint $table) {
            // Preserve node input parameters to merge with user data
            $table->json('input_parameters')->nullable();
        });
    }

    public function down()
    {
        Schema::table('workflow_nodes', function (Blueprint $table) {
            $table->dropColumn('input_parameters');
        });
    }
};