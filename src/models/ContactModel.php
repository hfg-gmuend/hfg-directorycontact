<?php

namespace hfg\directorycontact\models;

use Craft;
use yii\base\Model;
use hfg\directorycontact\fields\Contactperson;

class ContactModel extends Model
{
  public $person = '';
  public $email = '';
  public $telephone = '';

  public function rules()
  {
    
    return array_merge(parent::rules(),
      ['person', 'required']
    );
  }

  public function getID()
  {
    return (int) $this->person[0];
  }

  
}
