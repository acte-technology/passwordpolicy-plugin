<?php namespace Acte\PasswordPolicy\Classes;

use Log;
use BackendAuth;
use Acte\PasswordPolicy\Models\Settings;
use Flash;
// https://haveibeenpwned.com/API/v2#PwnedPasswords

class Pwned {


  function isPwned($password){

    $sha1Pass = strtoupper( sha1($password) );
    return $this->searchByRange($sha1Pass);

  }

  //GET https://api.pwnedpasswords.com/range/{first 5 hash chars}
  function searchByRange($hashedPass){

    //take first 5char of hashedPass
    $range = substr($hashedPass, 0, 5);

    $rawText = @file_get_contents("https://api.pwnedpasswords.com/range/".$range);

    if($rawText){

      $lines = explode(PHP_EOL, $rawText);

      foreach ($lines as $key => $line) {
        $result = explode(":", $line);
        $hash = $result[0];
        $found = $result[1];

        if($hashedPass == $range.$hash){
          // This is a Pwned password !

          if(BackendAuth::check()){ $foundThreshold = Settings::get('backend.pwned_found'); }
          else { $foundThreshold = Settings::get('user.pwned_found'); }

          if($foundThreshold <= $found){
            return true;
          } else {
            if(BackendAuth::check()){ Log::warning('Weak password for backend user '.BackendAuth::getUser()->id); }
            return false;
          }
        }

      }

    } else {
      Log::error('Password validation error: Pwned API call failed !');
    }

    return false;


  }





}
