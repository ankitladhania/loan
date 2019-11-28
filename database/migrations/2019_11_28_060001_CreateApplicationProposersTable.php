<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicationProposersTable extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up () {
    Schema::create('account_application_proposers', function (Blueprint $table) {
      $table->increments('proposer_id');
      $table->unsignedInteger('account_application_id');
      $table->string('name', 200);
      $table->string('m_no', 30);
      $table->string('address', 200);
      $table->string('occupation', 200);
      $table->timestamps();
      $table->softDeletes();

      $table->foreign('account_application_id')->references('account_application_id')->on('account_applications');;

    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down () {
    Schema::dropIfExists('account_application_proposers');
  }
}
