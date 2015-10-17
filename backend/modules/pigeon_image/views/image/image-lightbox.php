<?php
use backend\modules\pigeon_image\models\Album;
use backend\helpers\ExtraFunctions;
use backend\helpers\LinkGenerator;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ListView;
use yii\widgets\Pjax;
use backend\models\Breeder;

if(!Yii::$app->user->isGuest)
{
	$Breeder=Breeder::findUserById(Yii::$app->user->getId());
	$username=$Breeder->username;
}
else
{
	$username="Guest";
}

?>
<style>
a
{
	color: #3f729b;
}
/* ---------------------LEFT BOX - IMAGE BOX--------------*/
.image_box {
	padding-right:0px!important;
	padding-left:0px!important;
}
.current_image {
	max-height:570px;
	vertical-align:middle;
}

/*--------------RIGHT BOX - likes, comments, users-----------------------*/
.reactions_box {
	height:570px;
	background-color:white;
	padding-left:0px!important;
	padding-right:0px!important;
}
/* WHO POSTED*/
.who_when {
	border-bottom:2px double #F3F3F3;
	padding:7px;
}
.username {
	display: inline-block;
	font-size: 18px;
	font-weight: 700;
	
}
.time_image_created {
	color: #81868a;
	font-weight: 600;
}

.image_description
{
	display: block;
	overflow-y: auto;
	max-height: 70px;
}

/*LIKES*/
.like_box {
	border-bottom:2px double #DDDDDD;
	padding:10px;
}
.like {
	font-size:12px;
	text-decoration: underline;
}
.number_of_likes
{
	text-decoration: underline;
}

/*WHO LIKED PICTURE*/
.who_liked_box
{
	height:350px;
	overflow:auto;
}
.who_liked_users
{
	padding:7px;
	border-bottom:1px solid #D6D9DB;
}
.bootbox
{
  z-index: 10050 !important; /*z-index of colorbox is 9999*/
}

/*COMMENTS*/
.comment_box {
	background-color:#F8F8F8;
	border-bottom:2px double #EDEDED;
	padding:0 0 0 10px;
	height:350px;
	overflow:auto;
	padding:10px;
}
.comments_content
{
	padding:7px 0 7px 0;
	border-bottom:1px solid #E9E9E9;
}
.comments_username
{
	display: block;
	font-weight: 700;
}
.comment_time
{
	color:#999;
	font-size:10px;
	display:inline-block;
	padding-left:10px;
}

.add_comment {
	padding:10px;
}

/*colorbox fix*/
#cboxLoadedContent {
	background-color:black;
}
</style>
<!--  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script> -->
<script>
$(document).ready(function(e) {
	
	//ADD COMMENT
	$(".form_add_comment").submit(function(e)
	{
	
		var IDimage_tmp=$('input[name="idimage"]');
		var comment_text_tmp=$('input[name="comment_text"]');
		var postData = {idimage:IDimage_tmp.val(), comment_text:comment_text_tmp.val()} ;
		
		$.ajax(
		{
			url : "<?= Url::to(["/pigeon-image/comment/create"])?>",
			type: "POST",
			data : postData,
			dataType :"json",
			success:function(data, textStatus, jqXHR) 
			{
				if(data.result=="true")
				{
					var dt = new Date();
					var time = dt.getDate()+"."+(dt.getMonth()+1)+"."+dt.getFullYear()+" "+dt.getHours()+":"+dt.getMinutes()+":"+dt.getSeconds();
					
					var prepend_text = 
					'<div class="comments_content">'
						+'<span class="comments_username"><?= LinkGenerator::breederLink($username, $pictureModel->IDuser, ['target'=>"_self", 'class'=>'null']) ?></span>'
						+data.comment+'<span class="comment_time">'+time+'</span>'
					+'</div>';
					
					var comments_content=$(".comments_content");
					if(comments_content.length)
						comments_content.first().prepend(prepend_text);
					else 
						$(".empty").empty().prepend(prepend_text);
						
					comment_text_tmp.val("");
				}
			},
			error: function(jqXHR, textStatus, errorThrown) 
			{
				alert(errorThrown);   
			}
		});
		
		e.preventDefault(); //STOP default action
		e.unbind(); //unbind. to stop multiple form submit.
	});
	
	//LIKE BUTTON	
	$(".like").click(function()
	{
		var current_element=$(this);
		var IDimage = current_element.data("idimage");
		var like_tmp = current_element.data("like"); 	<?php /*if user liked this picture then data-like will be 1, it means that it has to unlike it if he click this button. If user didn't like this pic data-like=0, it means it should like it when he clicks this button*/ ?>
		$.ajax(
		{
			url: "<?= Url::to(["/pigeon-image/like/create"])?>",
			type: "POST",
			dataType: "json",
			data: {IDimage : IDimage, like: like_tmp},
			success:function(data, textStatus, jqXHR) 
			{
				if(data.result=="true")
				{
					if(data.like==1)
					{
						current_element.find(".fa-heart-o").removeClass("fa-heart-o").addClass('fa-heart');
						current_element.data("like",1);
					}
					else 
					{
						current_element.find(".fa-heart").removeClass("fa-heart").addClass('fa-heart-o');
						current_element.data("like",0);
					}
				}
			},
			error: function(jqXHR, textStatus, errorThrown) 
			{
				alert(errorThrown);   
			}
		});
	});
	
	//NUMBER OF LIKES
	$(".number_of_likes").click(function()
	{
		var current_element=$(this);
		var IDimage = current_element.data("idimage");
		$.ajax(
		{
			url: "<?= Url::to(["/pigeon-image/like/who-liked"])?>",
			type: "POST",
			dataType: "json",
			data: {IDimage : IDimage},
			success:function(data, textStatus, jqXHR) 
			{
				if(data.result=="true")
				{
					whoLiked(data.people);
				}
			},
			error: function(jqXHR, textStatus, errorThrown) 
			{
				alert(errorThrown);   
			}
		});
	});
	

	function whoLiked(people)
	{
		bootbox.dialog({
		  message: people,
		  title: "<?= Yii::t('default', 'People who like this')?>",
		  buttons: 
		  {
			success: {
			  label: "OK",
			  className: "btn-success",
			},
		  }
		});
	}   
	
	
});
</script>


<div class="container-fluid" style="background-color:black;">
  <div class="row box_wrapper">
    <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 image_box"> 
    	<img src="<?= Album::returnPathToPicture($pictureModel->IDalbum, $pictureModel->image_file, Yii::getAlias('@web'), $pictureModel->IDuser) ?>" class="img-responsive current_image center-block" /> 
    </div>
    
    <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 text-left reactions_box">
      <div class="who_when"> 
      	<span class="username">
			<?= LinkGenerator::breederLink($pictureModel->relationIDuser->username, $pictureModel->IDuser, ['target'=>"_self", 'class'=>'null']) ?>
        </span><br />
        <span class="time_image_created">
			<?= ExtraFunctions::formatDate($pictureModel->date_created, "ymd-his"); ?>
        </span>
        <span class="image_description">
        	<?= Html::encode($pictureModel->description); ?>
        </span> 
      </div>
      <div class="like_box"> 
      	<?php /*if user liked this picture then data-like will be 1, it means that it has to unlike it if he click this button 
			if user didn't like this pic data-like=0, it means it should like it when he clicks this button*/ ?>
      	<a href="javascript:;" data-idimage="<?= $pictureModel->ID; ?>" data-like="<?php echo ($didILikedIt==1) ? 1 : 0 ?>" class="like"><?php echo ($didILikedIt==1) ? '<i class="fa fa-heart"></i>' : '<i class="fa fa-heart-o"></i>'  ?></a> - 
        <a href="javascript:;" data-idimage="<?= $pictureModel->ID; ?>" class="number_of_likes"><?= $numberOfLikes." ".Yii::t('default', 'others like this') ?></a>
      </div>
      <div class="comment_box">
      <?php
	  Pjax::begin(['id'=>'reload_image_comments']);
	  echo ListView::widget([
			'dataProvider' => $commentDataProvider,
			'itemView' => function ($model, $key, $index, $widget)
			{
				return '<div class="comments_content"><span class="comments_username">'. LinkGenerator::breederLink($model->relationIDuser->username, $model->IDuser, ['target'=>"_self", 'class'=>'null']).'</span>'.$model->comment.'<span class="comment_time">'. ExtraFunctions::formatDate($model->date_created).'</span></div>';
			},

		]);
		Pjax::end();
	  ?>
      </div>
      <div class="add_comment">
      	<form method="post" class="form_add_comment">
        <div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
          <input type="text" name="comment_text" class="form-control" style="display:inline-block" required="required" />
          <input type="hidden" value="<?= $pictureModel->ID ?>" name="idimage" />
        </div>
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
          <input type="submit" value="<?= Yii::t('default', 'Submit')?>" class="btn btn-success " />
        </div>
        </form>
      </div>
    </div>
  </div>
</div>
