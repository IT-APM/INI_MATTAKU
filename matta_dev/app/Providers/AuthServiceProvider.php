<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use App\Models\IphoneUser;
use App\Models\BpdNikPeran;
use App\Models\MtrUtility;
use App\Models\Karyawan;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        
        Gate::define('hc', function(User $user) {
            // return $user->hasAnyRoles(['hc', 'instructor']);
            return $user->userrole == 'hc';
        });
        
        Gate::define('hc_dashboard', function(User $user) {
            return $user->userrole == 'hc' || in_array($user->nik, explode(',', MtrUtility::find(44)->informasi));
        });
        
        Gate::define('staf_only', function(User $user) {
            return $user->_nik->kode_level < 3;
        });
        
        Gate::define('staf', function(User $user) {
            return $user->_nik->kode_level < 3 || $user->userrole == 'hc';
        });
        
        Gate::define('spv', function(User $user) {
            return $user->_nik->kode_level >= 3 || $user->userrole == 'hc';
        });
        
        Gate::define('asm', function(User $user) {
            // return $user->_nik->kode_level >= 4 || $user->userrole == 'hc';
            // pengecualian 01032509884 ref cm 464
            return $user->_nik->kode_level >= 4 || $user->userrole == 'hc' || $user->nik == '01032509884';
        });
        
        Gate::define('iphone', function(User $user) {
            $where = ['nik' => $user->nik];
            $check = IphoneUser::where($where)->exists();
            return $check;
        });
        
        Gate::define('payroll', function(User $user) {
            return in_array($user->nik, explode(',', MtrUtility::find(32)->informasi));
        });
        
        Gate::define('payroll_fin', function(User $user) {
            return in_array($user->nik, explode(',', MtrUtility::find(36)->informasi));
        });
        
        Gate::define('payroll_hco_fin', function(User $user) {
            return in_array($user->nik, explode(',', MtrUtility::find(32)->informasi)) || in_array($user->nik, explode(',', MtrUtility::find(36)->informasi));
        });
        
        Gate::define('bpd', function(User $user) {
            $where = ['nik' => $user->nik, 'is_active' => 1];
            $check = BpdNikPeran::where($where)->exists();
            return $check;
        });
        
        Gate::define('auth', function(User $user) {
            return true;
        });
        
        Gate::define('notif_letter', function(User $user) {
            return in_array($user->nik, explode(',', MtrUtility::find(45)->informasi));
        });
        
        Gate::define('hc_nkp', function(User $user) {
            return auth()->user()->userrole == 'hc' && Karyawan::where(['nik'=>auth()->user()->nik, 'kode_perusahaan'=>5])->exists();
        });
        //
    }
}
