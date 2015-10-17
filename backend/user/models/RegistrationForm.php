<?php
namespace backend\user\models;

use dektrium\user\models\RegistrationForm as BaseRegistrationForm;

class RegistrationForm extends BaseRegistrationForm
{
	//IZMJENA START
	/**
     * @var string
     */
   // public $captcha;
	//IZMJENA END

    /** @inheritdoc */
    /*public function rules()
    {
		
		$rules = parent::rules();
		//IZMJENA START
		//API UserControllers sets scenario "mobile-registration". Don't use captcha when registering via mobile (I can't show captcha) mobile developers will integrate their own catpcha on mobile
		$rules[] = ['captcha', 'required', 'except'=>'mobile-registration'];
		$rules[] = ['captcha', 'captcha', 'except'=>'mobile-registration'];
		//IZMJENA END
		
		return $rules;
   
    }*/
}
?>