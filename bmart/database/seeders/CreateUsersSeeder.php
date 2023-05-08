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
               'name'=>'Aaron Vendor',
               'birthdate'=>Carbon::create('2001', '07', '08'),
                'number'=>'09123456789', 
                'address'=>'123 Crest Subdivision',
                'city'=>'Baguio',
                'country'=>'Philippines',
                'postalcode'=>'2700',               
               'email'=>'vendor@email.com',
               'isVendor'=>1,
               'password'=> bcrypt('12345678'),
            ],
            [
               'name'=>'Paul Buyer',
               'birthdate'=>Carbon::create('2000', '08', '09'),
                'number'=>'0912345',
                'address'=>'246 Windsor Village',
                'city'=>'Baguio',
                'country'=>'Philippines',
                'postalcode'=>'2700', 
               'email'=>'customer@email.com',
               'isVendor'=>0,
               'password'=> bcrypt('12345678'),
            ],
            [
                'name'=>'Second Vendor',
                'birthdate'=>Carbon::create('2003', '03', '04'),
                 'number'=>'09123456789', 
                 'address'=>'123 Camdas Subdivision',
                 'city'=>'Baguio',
                 'country'=>'Philippines',
                 'postalcode'=>'2700',               
                'email'=>'vendor2@email.com',
                'isVendor'=>1,
                'password'=> bcrypt('12345678'),
             ]
        ];
    
        foreach ($users as $key => $user) {
            User::create($user);
        }
    }
}