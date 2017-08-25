<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyMembers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropColumn('phone');
            $table->dropColumn('phone_ext');
            $table->dropColumn('start_date');
            $table->dropColumn('end_date');
            $table->dropColumn('users_id');

            $table->char('home_phone', 10)->nullable();
            $table->char('cell_phone', 10)->nullable();
            $table->date('member_since_date')->nullable();
            $table->date('google_group_date')->nullable();
            $table->integer('user_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('members', function (Blueprint $table) {
            $table->dropColumn('home_phone');
            $table->dropColumn('cell_phone');
            $table->dropColumn('emergency_contact_1');
            $table->dropColumn('emergency_contact_2');
            $table->dropColumn('emergency_phone_1');
            $table->dropColumn('emergency_phone_2');
            $table->dropColumn('member_since_date');
            $table->dropColumn('google_group_date');
            $table->dropColumn('user_id');

            $table->char('phone', 10)->nullable();
            $table->char('phone_ext', 10)->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('users_id');
        });
    }
}
