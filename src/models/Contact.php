<?php

namespace hfg\directorycontact\models;

use Craft;
use craft\base\Models;

class Contact extends Model {
  public $person = '';
  public $email = '';
  public $telephone = '';

  public function rules()
  {
    return [
      ['person', 'required']
    ];
  }
}
