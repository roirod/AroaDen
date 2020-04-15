<?php

namespace App\Http\Controllers\Traits;

use App\Models\Settings;
use App\Models\User;
use Config;
use Redis;
use Lang;

trait DefaultTrait {

  /**
   *  check If Setting Exists
   *  
   *  @return object
   */
  public function checkIfUserExists()
  {
    $default_users = Config::get('aroaden.default_users');

    foreach ($default_users as $user) {

      $exits = User::where('username', $user["username"])->first();

      if ($exits == null) {

        User::insert([
          'username' => $user["username"],
          'password' => bcrypt($user["password"]),
          'type' => $user["type"],
          'full_name' => $user["full_name"]
        ]);                
          
      }

    }

    return redirect("/login");
  }

  /**
   *  check If Setting Exists
   *  
   *  @return object
   */
  public function createDefaultCompanyData()
  {
    $settings_fields = $this->config['settings_fields'];

    foreach ($settings_fields as $field) {
      $exits = Settings::getValueByKey($field['name']);

      if ($exits == null) {
        Settings::insert([
          'key' => $field['name'],
          'value' => '',
          'type' => $field['settting_type']
        ]);                
      }
    }

    if (env('REDIS_SERVER_IS_ON'))  {
      $exists = Redis::exists('settings');

      if (!$exists) {
        $settings = Settings::getArray();

        Redis::set('settings', json_encode($settings));
      }
    }
  }

  /**
   *  create Symlinks
   *  
   *  @return object
   */
  public function createSymlinks()
  {
    $app_Symlink = public_path('app');
    $public_Symlink = storage_path('app/public');

    if(!is_link($app_Symlink))
      symlink(storage_path('app'), $app_Symlink);

    if(!is_link($public_Symlink))
      symlink(public_path(), $public_Symlink);
  }

}