<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDesignsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('designs', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
                        
            $table->unsignedBigInteger('tag_id');
            $table->unsignedBigInteger('designer_id');
            $table->unsignedBigInteger('company_id')->nullable();
            $table->text('description');
            $table->string('title');
            $table->float('price', 8, 2);
            $table->enum('state', ['sketch','sold','processing','produced']);	
            $table->enum('category', ['men','women','kids','teenagers']);	
            $table->string('source_file');
            $table->bigInteger('total_likes')->default(0)	;	


            $table->foreign('tag_id')->references('id')->on('tags');
            $table->foreign('designer_id')->references('id')->on('users');
            $table->foreign('company_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('designs');
    }
}
