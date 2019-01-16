<?php namespace Acte\PasswordPolicy;

use System\Classes\PluginBase;
use Backend\Models\User as BackendUserModel;
use Validator;
use Lang;

use Acte\PasswordPolicy\Models\Settings as PasswordPolicySettings;
use Log;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
    }

    public function registerSettings()
    {
    return [
      'settings' => [
        'label'       => 'acte.passwordpolicy::lang.plugin.name',
        'description' => 'acte.passwordpolicy::lang.plugin.description',
        'icon'        => 'icon-cog',
        'class'       => 'Acte\PasswordPolicy\Models\Settings',
        'order'       => 500,
        'keywords'    => 'password policy user',
        'permissions' => ['acte.passwordpolicy.manage_policies']
      ]
    ];
    }

    public function boot(){

        /* VALIDATORS */
        //minimum upper case validator
        Validator::extend('min_upper_case', function($attribute, $value, $parameters){
          if( preg_match_all('/[A-Z]/',$value) < $parameters[0] ) return FALSE;
          return TRUE;
        }, Lang::get('acte.passwordpolicy::validation.min_upper_case') );

        //minimum lower case validator
        Validator::extend('min_lower_case', function($attribute, $value, $parameters){
          if( preg_match_all('/[a-z]/',$value) < $parameters[0] ) return FALSE;
          return TRUE;
        }, Lang::get('acte.passwordpolicy::validation.min_lower_case') );

        //minimum number validator
        Validator::extend('min_number', function($attribute, $value, $parameters){
          if( preg_match_all('/[0-9]/',$value) < $parameters[0] ) return FALSE;
          return TRUE;
        }, Lang::get('acte.passwordpolicy::validation.min_number') );

        //minimum special characters validator
        Validator::extend('min_special_char', function($attribute, $value, $parameters){
          if( preg_match_all('/[\W]/',$value) < $parameters[0] ) return FALSE;
          return TRUE;
        }, Lang::get('acte.passwordpolicy::validation.min_special_char') );


      /* BACKEND USERS */
      //Backend user password policy if activated
      if( PasswordPolicySettings::get('backend.isActive', false) ){

        //extend backend user model
        BackendUserModel::extend(function($model) {
            $model->bindEvent('model.beforeValidate', function() use ($model) {

              $length = PasswordPolicySettings::get('backend.length');
              $upperCase = PasswordPolicySettings::get('backend.upperCase');
              $lowerCase = PasswordPolicySettings::get('backend.lowerCase');
              $numbers = PasswordPolicySettings::get('backend.numbers');
              $specialChar = PasswordPolicySettings::get('backend.specialChar');

              $model->rules['email'] = 'required|between:6,255|email|unique:backend_users';
              $model->rules['login'] = 'required|between:3,255|unique:backend_users';
              $model->rules['password'] = "required:create|between:4,255|min_upper_case:$upperCase|min_lower_case:$lowerCase|min:$length|min_number:$numbers|min_special_char:$specialChar|confirmed";
              $model->rules['password_confirmation'] = "required_with:password|between:4,255|min_upper_case:$upperCase|min_lower_case:$lowerCase|min:$length|min_number:$numbers|min_special_char:$specialChar";

            });
        });
      }


      /* FRONT END USER */
      //User password policy if activated and RainLab.User class Auth exists
      if( class_exists('Auth') && PasswordPolicySettings::get('user.isActive', false) ){

        //extend backend user model
        \RainLab\User\Models\User::extend(function($model) {
            $model->bindEvent('model.beforeValidate', function() use ($model) {

              $length = PasswordPolicySettings::get('user.length');
              $upperCase = PasswordPolicySettings::get('user.upperCase');
              $lowerCase = PasswordPolicySettings::get('user.lowerCase');
              $numbers = PasswordPolicySettings::get('user.numbers');
              $specialChar = PasswordPolicySettings::get('user.specialChar');

              $model->rules['email']    = 'required|between:6,255|email|unique:users';
              $model->rules['avatar']   = 'nullable|image|max:4000';
              $model->rules['username'] = 'required|between:2,255|unique:users';
              $model->rules['password'] = "required:create|between:4,255|min_upper_case:$upperCase|min_lower_case:$lowerCase|min:$length|min_number:$numbers|min_special_char:$specialChar|confirmed";
              $model->rules['password_confirmation'] = "required_with:password|between:4,255|min_upper_case:$upperCase|min_lower_case:$lowerCase|min:$length|min_number:$numbers|min_special_char:$specialChar";

            });
        });
      }





    }
}
