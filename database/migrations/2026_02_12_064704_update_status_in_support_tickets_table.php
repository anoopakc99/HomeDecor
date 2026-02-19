<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('support_tickets', function (Blueprint $table) {
            // Drop the existing status column and recreate with new values
            $table->dropColumn('status');
        });
        Schema::table('support_tickets', function (Blueprint $table) {
            $table->enum('status', ['open', 'pending', 'processing', 'resolved', 'closed'])->default('open')->after('message');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('support_tickets', function (Blueprint $table) {
            //
        });
    }
};
