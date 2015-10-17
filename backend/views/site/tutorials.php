<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

$this->title = Yii::t('default','Tutorials');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="site-login">
  <h1>
    <?= Html::encode($this->title) ?>
  </h1>
  <div class="row">
     <div class="text-center col-sm-6 ">
        <div class='embed-container'><iframe src='https://www.youtube.com/embed/tX4a-aDiKJg' frameborder='0' allowfullscreen></iframe></div>
    </div>        
    <div class="text-center col-sm-6 ">
        <div class='embed-container'><iframe src='https://www.youtube.com/embed/gMdA5xBnAdE' frameborder='0' allowfullscreen></iframe></div>
    </div>         
  </div>
  
  <div class="row m-t-20">
    <div class="col-md-12">
      <div class="panel-group" id="accordion" data-toggle="collapse">
        <div class="panel panel-default">
          <div class="panel-heading collapsed">
            <h4 class="panel-title"> <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseOne"><?= Yii::t('default', 'Login') ?></a> </h4>
          </div>
          <div id="collapseOne" class="panel-collapse collapse" style="height: 0px;">
            <div class="panel-body"> 
            	<iframe width="560" height="315" src="https://www.youtube.com/embed/uQcoyWditpQ" frameborder="0" allowfullscreen></iframe>
            </div>
          </div>
        </div>
        <div class="panel panel-default">
          <div class="panel-heading collapsed">
            <h4 class="panel-title"> <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo"><?= Yii::t('default', 'Register') ?> </a> </h4>
          </div>
          <div id="collapseTwo" class="panel-collapse collapse" style="height: 0px;">
            <div class="panel-body">
				<iframe width="560" height="315" src="https://www.youtube.com/embed/AtjJd7POFg4" frameborder="0" allowfullscreen></iframe>
            </div>
          </div>
        </div>
        
        <div class="panel panel-default">
          <div class="panel-heading collapsed">
            <h4 class="panel-title"> <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseThree"><?= Yii::t('default', 'Auctions') ?></a> </h4>
          </div>
          <div id="collapseThree" class="panel-collapse collapse">
            <div class="panel-body">
            	<iframe width="560" height="315" src="https://www.youtube.com/embed/LTRSxh9y0bw" frameborder="0" allowfullscreen></iframe>
            </div>
          </div>
        </div>
        
        <div class="panel panel-default">
          <div class="panel-heading collapsed">
            <h4 class="panel-title"> <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseThree1"><?= Yii::t('default', 'NAV_DODAJ_GOLUBA') ?></a> </h4>
          </div>
          <div id="collapseThree1" class="panel-collapse collapse">
            <div class="panel-body">
            	<iframe width="560" height="315" src="https://www.youtube.com/embed/3T3OpuHTpKM" frameborder="0" allowfullscreen></iframe>
            </div>
          </div>
        </div>
        
        <div class="panel panel-default">
          <div class="panel-heading collapsed">
            <h4 class="panel-title"> <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseThree2"><?= Yii::t('default', 'PAR_NATJEC_LINK_CREATE') ?></a> </h4>
          </div>
          <div id="collapseThree2" class="panel-collapse collapse">
            <div class="panel-body">
            	<iframe width="560" height="315" src="https://www.youtube.com/embed/nyKo5NLVmlY" frameborder="0" allowfullscreen></iframe>
            </div>
          </div>
        </div>
        
        <div class="panel panel-default">
          <div class="panel-heading collapsed">
            <h4 class="panel-title"> <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseThree3"><?= Yii::t('default', 'NAV_DODAJ_LEGLO') ?></a> </h4>
          </div>
          <div id="collapseThree3" class="panel-collapse collapse">
            <div class="panel-body">
            	<iframe width="560" height="315" src="https://www.youtube.com/embed/wRIaCPM_YwM" frameborder="0" allowfullscreen></iframe>
            </div>
          </div>
        </div>
        
        <div class="panel panel-default">
          <div class="panel-heading collapsed">
            <h4 class="panel-title"> <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseThree4"><?= Yii::t('default', 'Pigeon Insider Maker') ?></a> </h4>
          </div>
          <div id="collapseThree4" class="panel-collapse collapse">
            <div class="panel-body">
            	<iframe width="560" height="315" src="https://www.youtube.com/embed/-dlPR3wtw5M" frameborder="0" allowfullscreen></iframe>
            </div>
          </div>
        </div>
        
        <div class="panel panel-default">
          <div class="panel-heading collapsed">
            <h4 class="panel-title"> <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseThree5"><?= Yii::t('default', 'Racing Table') ?></a> </h4>
          </div>
          <div id="collapseThree5" class="panel-collapse collapse">
            <div class="panel-body">
            	<iframe width="560" height="315" src="https://www.youtube.com/embed/m4QDLUq4uYg" frameborder="0" allowfullscreen></iframe>
            </div>
          </div>
        </div>
        
        <div class="panel panel-default">
          <div class="panel-heading collapsed">
            <h4 class="panel-title"> <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseThree6"><?= Yii::t('default', 'Quick insert') ?></a> </h4>
          </div>
          <div id="collapseThree6" class="panel-collapse collapse">
            <div class="panel-body">
            	<iframe width="560" height="315" src="https://www.youtube.com/embed/ft-BmJw3xPI" frameborder="0" allowfullscreen></iframe>
            </div>
          </div>
        </div>
        
        <div class="panel panel-default">
          <div class="panel-heading collapsed">
            <h4 class="panel-title"> <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseThree7"><?= Yii::t('default', 'Lost and Found') ?></a> </h4>
          </div>
          <div id="collapseThree7" class="panel-collapse collapse">
            <div class="panel-body">
            	<iframe width="560" height="315" src="https://www.youtube.com/embed/G7wQzX8QPqo" frameborder="0" allowfullscreen></iframe>
            </div>
          </div>
        </div>
        
        
      </div>
    </div>
  </div>
</div>
