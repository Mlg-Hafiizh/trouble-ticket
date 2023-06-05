<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categorizations', function (Blueprint $table) {
            $table->string('categorization_id',30);
            $table->string('name',120);
            $table->text('description');
            $table->enum('type',['user','ticket']);
            $table->softDeletes();
            $table->timestamps();

            $table->primary('categorization_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categorizations');
    }
};
