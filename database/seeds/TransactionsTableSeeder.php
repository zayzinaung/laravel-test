<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TransactionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('transactions')->insert([
            'user_id'		=> 2,
            'wallet_id'		=> 2,
			'trans_id'		=> str_random(15),
			'type'			=> 'send',
			'amount'		=> 300,
			'currency'		=> 'sgd',
			'fee'			=> 0,
			'sender'		=> 'John',
			'receiver'		=> '',
			'status'		=> 'completed',
			'description'	=> 'For Car Rent',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        DB::table('transactions')->insert([
            'user_id'		=> 2,
            'wallet_id'		=> 2,
			'trans_id'		=> str_random(15),
			'type'			=> 'send',
			'amount'		=> 600,
			'currency'		=> 'sgd',
			'fee'			=> 0,
			'sender'		=> 'John',
			'receiver'		=> '',
			'status'		=> 'completed',
			'description'	=> 'For Shared Home Rent',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        DB::table('transactions')->insert([
            'user_id'		=> 2,
            'wallet_id'		=> 2,
			'trans_id'		=> str_random(15),
			'type'			=> 'send',
			'amount'		=> 300,
			'currency'		=> 'sgd',
			'fee'			=> 0,
			'sender'		=> 'John',
			'receiver'		=> 'IBM Technologies',
			'status'		=> 'completed',
			'description'	=> 'For Buy Computer',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }
}
