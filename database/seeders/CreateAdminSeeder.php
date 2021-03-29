<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;

class CreateAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $check = Admin::first();
        if(!$check){
            $admin = new Admin();
            $admin->id = 1;
            $admin->fname = 'Francis';
            $admin->lname = 'Mogbana';
            $admin->tel = '08130148519';
            $admin->email = 'fmogbana@gmail.com';
            $admin->role = 3;
            $admin->status = 1;
            $admin->password = bcrypt('qwert');
            $admin->created_by = 1;
            $admin->save();
        }
    }
}
