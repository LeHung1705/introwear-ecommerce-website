<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product_name');
            $table->string('category_id');
            $table->string('color');
            $table->string('size');
            $table->bigInteger('price');
            $table->bigInteger('price_sale')->nullable();
            $table->string('description');
            $table->integer('stock_quantity');
            $table->enum('status_product',['Còn hàng','Hết hàng']);
            $table->string('supplier_id')->nullable();
            $table->string('image');   
             $table->timestamps();
         });
 
    }

    /**
     * Reverse the migrations.
     */
   
    public function down(): void
    {
        Schema::dropIfExists('products');
       
    }
 

};
