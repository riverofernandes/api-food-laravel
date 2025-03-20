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
            $table->string('code')->unique();
            $table->text('url');
            $table->string('creator');
            $table->timestamp('created_t');
            $table->timestamp('last_modified_t');
            $table->text('product_name')->nullable();
            $table->string('quantity')->nullable();
            $table->text('brands')->nullable();
            $table->text('categories')->nullable();
            $table->text('labels')->nullable();
            $table->string('cities')->nullable();
            $table->text('purchase_places')->nullable();
            $table->text('stores')->nullable();
            $table->text('ingredients_text')->nullable();
            $table->text('traces')->nullable();
            $table->string('serving_size')->nullable();
            $table->decimal('serving_quantity', 8, 2)->nullable();
            $table->integer('nutriscore_score')->nullable();
            $table->string('nutriscore_grade')->nullable();
            $table->text('main_category')->nullable();
            $table->string('image_url')->nullable();
            $table->timestamp('imported_t')->useCurrent();
            $table->enum('status', ['draft', 'trash', 'published'])->default('draft');
            $table->softDeletes();
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
