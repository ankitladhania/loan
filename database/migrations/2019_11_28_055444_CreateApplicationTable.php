<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicationTable extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up () {
    Schema::create('account_applications', function (Blueprint $table) {
      $table->increments('account_application_id');
      $table->string('name', 200);
      $table->string('m_no', 30);
      $table->string('address', 300);
      $table->string('father_name', 300);
      $table->string('aadhar_no', 100);
      $table->string('pan_no', 100);
      $table->string('landholding', 100);
      $table->string('crops_grown', 150);
      $table->timestamps();
      $table->softDeletes();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down () {
    Schema::dropIfExists('account_applications');
  }
}
