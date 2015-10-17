<?php

namespace backend\models\activequery;

/**
 * This is the ActiveQuery class for [[\backend\models\ResolveIssueReply]].
 *
 * @see \backend\models\ResolveIssueReply
 */
class ResolveIssueReplyQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return \backend\models\ResolveIssueReply[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \backend\models\ResolveIssueReply|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}