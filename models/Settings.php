<?php namespace Acte\PasswordPolicy\Models;

use Model;

class Settings extends Model
{

  use \October\Rain\Database\Traits\Validation;

  public $rules = [
    'backend.length' => 'required_if:backend.isActive,0|numeric',
    'backend.numbers' => 'required_if:backend.isActive,true|numeric',
    'backend.upperCase' => 'required_if:backend.isActive,true|numeric',
    'backend.lowerCase' => 'required_if:backend.isActive,true|numeric',
    'backend.specialChar' => 'required_if:backend.isActive,true|numeric',
    'user.length' => 'required_if:user.isActive,true|numeric',
    'user.numbers' => 'required_if:user.isActive,true|numeric',
    'user.upperCase' => 'required_if:user.isActive,true|numeric',
    'user.lowerCase' => 'required_if:user.isActive,true|numeric',
    'user.specialChar' => 'required_if:user.isActive,true|numeric',
  ];

  public $implement = ['System.Behaviors.SettingsModel'];

  // A unique code
  public $settingsCode = 'acte_passwordpolicy_settings';

  // Reference to field configuration
  public $settingsFields = 'fields.yaml';

}
