<?php

namespace backend\models\activequery;

/**
 * This is the ActiveQuery class for [[\backend\models\AccountBalance]].
 *
 * @see \backend\models\AccountBalance
 */
class AccountBalanceQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return \backend\models\AccountBalance[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \backend\models\AccountBalance|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}