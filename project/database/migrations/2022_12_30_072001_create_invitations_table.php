<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvitationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invitations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('member_id');
            $table->unsignedBigInteger('family_id');
            $table->unsignedBigInteger('invited_by');
            $table->string('code');
            $table->enum('type', \App\Enum\MemberInvitationTypesEnum::values()->toArray());
            $table->string('identifier');
            $table->string('name');
            $table->dateTime('expires_in');
            $table->dateTime('activated_at')->nullable();
            $table->timestamps();

            $table->unique(['identifier', 'family_id']);

            $table->foreign('family_id')
                ->references('id')
                ->on('families')
                ->cascadeOnUpdate()
                ->cascadeOnUpdate();

            $table->foreign('invited_by')
                ->references('id')
                ->on('users')
                ->cascadeOnUpdate()
                ->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invitations');
    }
}
