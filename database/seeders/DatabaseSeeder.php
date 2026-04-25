<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin User
        User::create([
            'name'            => 'Admin',
            'email'           => 'admin@bloodbank.com',
            'password'        => Hash::make('password'),
            'phone'           => '01700000000',
            'blood_group'     => 'O+',
            'gender'          => 'male',
            'address'         => 'Dhaka, Bangladesh',
            'city'            => 'Dhaka',
            'district'        => 'Dhaka',
            'division'        => 'Dhaka',
            'role'            => 'admin',
            'status'          => 'approved',
            'total_donations' => 0,
            'is_available'    => false,
        ]);

        // Sample Approved Donors
        $donors = [
            ['name' => 'রাকিব হাসান', 'email' => 'rakib@example.com', 'blood_group' => 'A+', 'phone' => '01711111111', 'district' => 'Dhaka', 'status' => 'approved', 'total_donations' => 5, 'last_donation_date' => '2024-09-15'],
            ['name' => 'তানিয়া আক্তার', 'email' => 'tania@example.com', 'blood_group' => 'B+', 'phone' => '01722222222', 'district' => 'Chittagong', 'status' => 'approved', 'total_donations' => 3, 'last_donation_date' => '2024-10-20'],
            ['name' => 'সাকিব আল হাসান', 'email' => 'sakib@example.com', 'blood_group' => 'O-', 'phone' => '01733333333', 'district' => 'Rajshahi', 'status' => 'approved', 'total_donations' => 8, 'last_donation_date' => '2024-08-01'],
            ['name' => 'ফারহানা ইসলাম', 'email' => 'farhana@example.com', 'blood_group' => 'AB+', 'phone' => '01744444444', 'district' => 'Sylhet', 'status' => 'temporary', 'total_donations' => 0],
            ['name' => 'মাহমুদ হোসেন', 'email' => 'mahmud@example.com', 'blood_group' => 'A-', 'phone' => '01755555555', 'district' => 'Khulna', 'status' => 'temporary', 'total_donations' => 0],
            ['name' => 'নুসরাত জাহান', 'email' => 'nusrat@example.com', 'blood_group' => 'B-', 'phone' => '01766666666', 'district' => 'Barishal', 'status' => 'approved', 'total_donations' => 2, 'last_donation_date' => '2024-11-01'],
        ];

        foreach ($donors as $donor) {
            User::create(array_merge([
                'password'        => Hash::make('password'),
                'gender'          => 'male',
                'address'         => $donor['district'] . ', Bangladesh',
                'city'            => $donor['district'],
                'division'        => $donor['district'],
                'role'            => 'donor',
                'is_available'    => true,
                'contact_visible' => true,
                'address_visible' => true,
            ], $donor));
        }
    }
}
