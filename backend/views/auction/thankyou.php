<?php
use yii\helpers\Url;
$this->title =Yii::t('default', 'Thank you');

?>

<div class="row">
  <div class="col-md-12 col-vlg-12 col-sm-12">
    <div class="tiles green m-b-10">
      <div class="tiles-body"> 
        	<h4 style="color:white;"><?= Yii::t('default', 'Auction thank you', ['0'=>Url::to(['/auction/view', 'id'=>$IDauction])]) ?></h4>
      </div>
    </div>
  </div>
</div>
