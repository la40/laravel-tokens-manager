<?php

    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Database\Migrations\Migration;
    use \Illuminate\Support\Facades\Schema;

    class CreateTokensManagerTable extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
            Schema::create(config('tokens_manager.table'),function(Blueprint $t)
            {
                $t->string('token');
                $t->string('manager')->index();
                $t->text('payload');
                $t->timestamp('created_at')->nullable();
            });
        }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down()
        {
            Schema::drop(config('tokens_manager.table'));
        }
    }
