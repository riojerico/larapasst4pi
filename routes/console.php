<?php

use Illuminate\Foundation\Inspiring;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command("make:user",function () {
   DB::table("users")->insert([
      "name"=>"Superadmin",
       "email"=>"admin@t4t.com",
       "password"=>Hash::make(123456),
       "created_at"=>now()->format("Y-m-d H:i:s")
   ]);
   $this->info("Create user success");
});

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');
