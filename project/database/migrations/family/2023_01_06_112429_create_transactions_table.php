<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bank_account_id');
            $table->unsignedBigInteger('to_bank_account_id')->nullable();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('type', \App\Enum\TransactionTypeEnum::values()->toArray());
            $table->enum('status', \App\Enum\TransactionStatusEnum::values()->toArray())->default(\App\Enum\TransactionStatusEnum::COMPLETE);
            $table->double('amount', 15,2);
            $table->unsignedBigInteger('created_by');
            $table->date('date');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('bank_account_id')
                ->references('id')
                ->on('bank_accounts')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreign('to_bank_account_id')
                ->references('id')
                ->on('bank_accounts')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreign('created_by')
                ->references('id')
                ->on('members')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
