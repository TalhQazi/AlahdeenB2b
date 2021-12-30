<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDriverFeedbackTable extends Migration
{
    protected $connection = 'logistics';
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::connection($this->connection)->create('driver_feedback', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_request_id')->constrained();
            $table->decimal('rating', 3, 2)->default(0);
            $table->string('feedback')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::connection($this->connection)->dropIfExists('driver_feedback');
    }
}
