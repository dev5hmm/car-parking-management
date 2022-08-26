<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\BookingSetting;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class ProjectInitiate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'project:initiate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Project Initiate';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $user = User::where('email', "admin@gmail.com")->first();
        if(is_null($user)) {

            $user = User::create([
                "name"=>"Admin",
                "email"=>"admin@gmail.com",
                "password"=>Hash::make('internet'),
            ]);
        }
        $admin = Role::where('name', 'admin')->first();
        if(is_null($admin)) {
            Role::create(['name' => 'admin']);
        }
        $user->assignRole('admin');
        BookingSetting::getQuery()->delete();

        $setting = new BookingSetting();
        $setting->from = config('day-range.min_day');
        $setting->to = config('day-range.max_day');
        $setting->fee = 100;
        $setting->save();
        $this->info('You can login with email(admin@gmail.com) and internet password(internet)');




    }
}
