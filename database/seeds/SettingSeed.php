<?php

use Illuminate\Database\Seeder;
use App\Http\Model\Setting;
use App\Http\Model\Employee;


class SettingSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      Employee::truncate();
      Setting::truncate();

      factory(Employee::class, 20)->create();
      Setting::create([
        'setting_category' => 'Setting',
        'setting_key' => 'Nama Toko',
        'setting_value' => 'Laundry SPN',
        'setting_active' => '1',
        'setting_created_at' => now()->toDateTimeString(),
        'setting_created_by' => '1'
      ]);

      Setting::create([
        'setting_category' => 'Setting',
        'setting_key' => 'Alamat',
        'setting_value' => 'Sungai Penuh - Jambi',
        'setting_active' => '1',
        'setting_created_at' => now()->toDateTimeString(),
        'setting_created_by' => '1'
      ]);
      
      Setting::create([
        'setting_category' => 'Setting',
        'setting_key' => 'Telepon',
        'setting_value' => '0748-XXXXX',
        'setting_active' => '1',
        'setting_created_at' => now()->toDateTimeString(),
        'setting_created_by' => '1'
      ]);
      
      Setting::create([
        'setting_category' => 'Setting',
        'setting_key' => 'Invoice Laundry',
        'setting_value' => 'SPN-LD/',
        'setting_active' => '1',
        'setting_created_at' => now()->toDateTimeString(),
        'setting_created_by' => '1'
      ]);
      
      Setting::create([
        'setting_category' => 'Setting',
        'setting_key' => 'Invoice Steam',
        'setting_value' => 'SPN-Steam',
        'setting_active' => '1',
        'setting_created_at' => now()->toDateTimeString(),
        'setting_created_by' => '1'
      ]);
      
      Setting::create([
        'setting_category' => 'Setting',
        'setting_key' => 'Footer Invoice',
        'setting_value' => 'Terima Kasih Atas Kepercayaan Anda',
        'setting_active' => '1',
        'setting_created_at' => now()->toDateTimeString(),
        'setting_created_by' => '1'
      ]);
      
      Setting::create([
        'setting_category' => 'Logo',
        'setting_key' => 'Logo Aplikasi',
        'setting_value' => null,
        'setting_active' => '1',
        'setting_created_at' => now()->toDateTimeString(),
        'setting_created_by' => '1'
      ]);

    }
}
