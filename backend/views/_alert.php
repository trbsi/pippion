<?php
/*
HOW TO USE
<?= $this->render('/_alert'); ?>

*/
?>
<?php if(Yii::$app->session->getAllFlashes()):?>

<div class="row">
  <?php foreach (Yii::$app->session->getAllFlashes() as $type => $message): ?>
	  <?php if (in_array($type, ['success', 'danger', 'warning', 'info'])): ?>
      <?php if($type=="success")
	  			$type_tmp="green";
			else if($type=="info")
				$type_tmp="blue";
			else if($type=="warning" || $type=="danger")
				$type_tmp="red"; 
	?>
      <div class="col-md-12 col-vlg-12 col-sm-12">
        <div class="tiles <?= $type_tmp ?> m-b-10">
          <div class="tiles-body">
            <div class="controller"> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
              <?php
                //for example I'm using this when I'm setting multiple flashes on auctions when I upload pics (addFlash())
                if(is_array($message))
                {
                    foreach($message as $value)
                    {
                        echo "<strong>".$value."</strong><br>";
                    }	
                }
                else
                {
                    echo "<strong>".$message."</strong>";
                }
                ?>
          </div>
        </div>
      </div>
      <?php endif ?>
  <?php endforeach ?>
</div>
<?php endif; ?>
