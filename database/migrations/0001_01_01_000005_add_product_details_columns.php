<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->json('images')->nullable()->after('description');
            $table->text('ingredients')->nullable()->after('images');
            $table->text('benefits')->nullable()->after('ingredients');
            $table->text('usage_instructions')->nullable()->after('benefits');
            $table->text('safety_notes')->nullable()->after('usage_instructions');
            $table->decimal('price', 10, 2)->default(0)->after('safety_notes');
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['images', 'ingredients', 'benefits', 'usage_instructions', 'safety_notes', 'price']);
        });
    }
};
