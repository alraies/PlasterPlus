<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameVendorcustomersTableColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vendor_customers', function (Blueprint $table) {
            $table->renameColumn('userId', 'customerId');
            $table->renameColumn('userName', 'customerName');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vendor_customers', function (Blueprint $table) {
            //
        });
    }
}
