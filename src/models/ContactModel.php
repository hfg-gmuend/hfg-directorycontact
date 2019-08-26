<?php

namespace hfg\directorycontact\models;

use Craft;
use yii\base\Model;

class ContactModel extends Model
{
  public $person = '';
  public $email = '';
  public $telephone = '';

  public function rules()
  {
    return [
      ['person', 'required']
    ];
  }

  public function __toString()
  {

  }
}
