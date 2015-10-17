<?php

namespace backend\user\models;

use dektrium\user\models\LoginForm as BaseLoginForm;

class LoginForm extends BaseLoginForm
{

    /**
     * Validates form and logs the user in.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return \Yii::$app->getUser()->login($this->user, $this->rememberMe ? $this->module->rememberFor : 31536000);  //IZMJENA vremena iz 0 u 31536000
        } else {
            return false;
        }
    }
}
