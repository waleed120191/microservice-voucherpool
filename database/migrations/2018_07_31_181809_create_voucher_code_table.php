<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVoucherCodeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection('mysql2')->create('voucher_codes', function (Blueprint $table) {
            $table->increments('id');
            $table->char('code', 8);
            $table->integer('offer_id');
            $table->string('user_email');
            $table->date('expired_at');
            $table->date('used_at')->nullable();
            $table->timestamps();

            $table->unique(['offer_id', 'user_email']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection('mysql2')->dropIfExists('voucher_codes');
    }
}
