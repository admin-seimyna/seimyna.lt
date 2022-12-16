<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('family_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('name');
            $table->enum('status', \App\Enum\MemberStatusEnum::values()->toArray());
            $table->timestamps();

            if (!app()->runningUnitTests()) {
                $table->foreign('family_id')
                    ->references('id')
                    ->on('seimyna.families')
                    ->cascadeOnUpdate()
                    ->cascadeOnDelete();

                $table->foreign('user_id')
                    ->references('id')
                    ->on('seimyna.users')
                    ->cascadeOnUpdate()
                    ->cascadeOnDelete();
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
        Schema::dropIfExists('family_members');
    }
}
