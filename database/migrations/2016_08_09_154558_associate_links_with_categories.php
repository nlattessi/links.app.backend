<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AssociateLinksWithCategories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $dbConnection = env('DB_CONNECTION', 'mysql');

        Schema::table('links', function (Blueprint $table) use ($dbConnection) {
            if ($dbConnection === 'sqlite') {
                $table->integer('category_id')->unsigned()->default(0);
            } else {
                $table->integer('category_id')->after('id')->unsigned();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('links', function (Blueprint $table) {
            $table->dropColumn('category_id');
        });
    }
}
