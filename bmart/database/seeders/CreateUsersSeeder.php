<?php
  
namespace Database\Seeders;
  
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Carbon\Carbon;

class CreateUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $users = [
            [
               'name'=>'Vendor',
               'birthdate'=>Carbon::create('2001', '07', '08'),
                'number'=>'0912345',                
               'email'=>'vendor@email.com',
               'isVendor'=>1,
               'password'=> bcrypt('12345678'),
            ],
            [
               'name'=>'Buyer',
               'birthdate'=>Carbon::create('2001', '07', '08'),
                'number'=>'0912345',
               'email'=>'customer@email.com',
               'isVendor'=>0,
               'password'=> bcrypt('12345678'),
            ],
            [
                'name'=>'Vendor2',
                'birthdate'=>Carbon::create('2001', '07', '08'),
                 'number'=>'0912345',                
                'email'=>'vendor2@email.com',
                'isVendor'=>1,
                'password'=> bcrypt('12345678'),
             ],
        ];
    
        foreach ($users as $key => $user) {
            User::create($user);
        }
    }
}