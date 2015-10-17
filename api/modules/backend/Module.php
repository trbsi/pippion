<?php
namespace api\modules\backend;

/**
 * iKargo API V1 Module
 * 
 * @author Budi Irawan <budi@ebizu.com>
 * @since 1.0
 */
class Module extends \yii\base\Module
{
    public $controllerNamespace = 'api\modules\backend\controllers';

    public function init()
    {
        parent::init();        
    }
}
