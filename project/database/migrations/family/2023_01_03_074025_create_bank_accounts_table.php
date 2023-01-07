<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBankAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('iban');
            $table->string('uid')->nullable();
            $table->unsignedBigInteger('bank_id');
            $table->unsignedBigInteger('member_id');
            $table->unsignedBigInteger('requisition_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('bank_id')
                ->references('id')
                ->on(config('database.connections.main.database') . '.banks')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreign('member_id')
                ->references('id')
                ->on('members')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreign('requisition_id')
                ->references('id')
                ->on('requisitions')
                ->cascadeOnUpdate()
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bank_accounts');
    }
}
