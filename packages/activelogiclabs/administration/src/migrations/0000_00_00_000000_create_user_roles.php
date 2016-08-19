<?php
/**
 * Created by PhpStorm.
 * User: daltongibbs
 * Date: 8/12/16
 * Time: 10:55 AM
 */

use Illuminate\Database\Schema\Blueprint;
use \Illuminate\Database\Migrations\Migration;

class CreateUserRoles extends \Illuminate\Database\Migrations\Migration
{

    public function up()
    {
        $userTable = config('admin.user_table');

        if (empty($userTable)) {

            Schema::create('users', function(Blueprint $table) {
                $table->increments('id');

            });

        }

    }

    public function down()
    {
        $userTable = config('admin.user_table');

        if (empty($userTable)) {

            Schema::drop('users');

        }
    }
}