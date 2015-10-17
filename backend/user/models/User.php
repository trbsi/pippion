<?php
namespace backend\user\models;

use dektrium\user\models\User as BaseUser;
use yii\log\Logger;
//IZMJENA START
use backend\models\Breeder;
use backend\models\Subscription;
use backend\helpers\ExtraFunctions;
use Yii;
//IZMJENA END

class User extends BaseUser
{
	    /**
     * This method is used to register new user account. If Module::enableConfirmation is set true, this method
     * will generate new confirmation token and use mailer to send it to the user. Otherwise it will log the user in.
     * If Module::enableGeneratingPassword is set true, this method will generate new 8-char password. After saving user
     * to database, this method uses mailer component to send credentials (username and password) to user via email.
     *
     * @return bool
     */

    public function register()
    {
        if ($this->getIsNewRecord() == false) {
            throw new \RuntimeException('Calling "' . __CLASS__ . '::' . __METHOD__ . '" on existing user');
        }

        $this->confirmed_at = $this->module->enableConfirmation ? null : time();
        $this->password     = $this->module->enableGeneratingPassword ? Password::generate(8) : $this->password;

        $this->trigger(self::BEFORE_REGISTER);

        if (!$this->save()) 
		{
            return false;
        }
		else
		{
			//IZMJENA START
			ExtraFunctions::onUserRegistration($this);
			//IZMJENA END
		}

        if ($this->module->enableConfirmation) {
            /** @var Token $token */
            $token = Yii::createObject(['class' => Token::className(), 'type' => Token::TYPE_CONFIRMATION]);
            $token->link('user', $this);
        }

        $this->mailer->sendWelcomeMessage($this, isset($token) ? $token : null);
        $this->trigger(self::AFTER_REGISTER);

        return true;
    }


	
	
    /** @inheritdoc */
    public static function findIdentityByAccessToken($token, $type = null)
    {
		//IZMJENA START
		return static::findOne(['access_token' => $token]);
		//IZMJENA END
       // throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

}