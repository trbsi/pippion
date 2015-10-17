<?php

namespace backend\user\controllers;

use dektrium\user\controllers\SettingsController as BaseSettingsController;

use dektrium\user\Finder;
use dektrium\user\models\SettingsForm;
use dektrium\user\Module;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use dektrium\user\traits\AjaxValidationTrait;
use backend\models\Breeder;

class SettingsController extends BaseSettingsController
{
    /**
     * Disconnects a network account from user.
     * @param  integer $id
     * @return \yii\web\Response
     * @throws \yii\web\NotFoundHttpException
     * @throws \yii\web\ForbiddenHttpException
     */
    public function actionDisconnect($id)
    {
        $account = $this->finder->findAccountById($id);
        if ($account === null) {
            throw new NotFoundHttpException;
        }
        if ($account->user_id != \Yii::$app->user->id) {
            throw new ForbiddenHttpException;
        }
        $account->delete();
		
		//unverify user
		$verified=Breeder::findUserById($account->user_id);
		$verified->verified=0;
		$verified->save();

        return $this->redirect(['networks']);
    }

}
?>