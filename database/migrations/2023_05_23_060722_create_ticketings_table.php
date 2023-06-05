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
        Schema::create('ticketings', function (Blueprint $table) {
            $table->string('ticket_id',30);
            $table->string('subject',120);
            $table->text('description');
            $table->dateTime('ticket_date');
            $table->json('evidance',120);
            $table->string('requester_id',30);
            $table->string('requester_name',120);
            $table->softDeletes();
            $table->timestamps();

            $table->primary('ticket_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ticketings');
    }
};
