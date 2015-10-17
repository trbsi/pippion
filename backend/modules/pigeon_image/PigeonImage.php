<?php

namespace backend\modules\pigeon_image;

use Yii;
use backend\modules\pigeon_image\models\Album;
class PigeonImage extends \yii\base\Module
{
    public $controllerNamespace = 'backend\modules\pigeon_image\controllers';
	
    public function init()
    {
        parent::init();
		//$this->params['ALBUM_DIR'] = Album::ALBUM_DIR;
        // custom initialization code goes here
    }
}
