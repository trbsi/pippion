<?php

namespace backend\models\activequery;

/**
 * This is the ActiveQuery class for [[\backend\models\ResolveIssue]].
 *
 * @see \backend\models\ResolveIssue
 */
class ResolveIssueQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return \backend\models\ResolveIssue[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \backend\models\ResolveIssue|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}