<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('main_category')->nullable()->constrained('categories');
            $table->foreignId('user_id')->nullable()->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->bigInteger('parent_id')->default(0);
            $table->string('slug');
            $table->string('title')->nullable();
            $table->text('image')->nullable();
            $table->longText('body')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->tinyInteger('is_featured')->nullable()->default(0);
            $table->string('excerpt')->nullable();
            $table->integer('published_status')->nullable()->default('0')->comment('0=draft,1=published');
            $table->integer('type')->nullable()->default('0')->comment('0=manual,1=auto,2=page');
            $table->text('extras')->nullable()->comment('to show extra details');
            $table->tinyInteger('is_revision')->default(0);
            $table->timestamp('display_published_at')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('category_post', function (Blueprint $table) {
            $table->foreignId('post_id')->constrained('posts');
            $table->foreignId('category_id')->constrained('categories');
        });

        Schema::create('post_tag', function (Blueprint $table) {
            $table->foreignId('post_id')->constrained('posts');
            $table->foreignId('tag_id')->constrained('tags');
        });
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('category_post');
        Schema::dropIfExists('post_tag');
        Schema::dropIfExists('posts');
    }
};
