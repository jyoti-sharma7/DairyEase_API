<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMilkDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('milk_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->enum('shift', ['morning', 'evening']);
            $table->date('date');
            $table->decimal('per_fat_amt', 8, 2);
            $table->decimal('fat_rate', 8, 2);
            $table->decimal('per_snf_amt', 8, 2);
            $table->decimal('snf_rate', 8, 2);
            $table->decimal('liter', 8, 2);
            $table->decimal('total_fat', 8, 2)->nullable();
            $table->decimal('total_snf', 8, 2)->nullable();
            $table->decimal('per_liter_amt', 8, 2)->nullable(); 
            $table->decimal('balance', 8, 2)->nullable();
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('milk_details');
    }
}
