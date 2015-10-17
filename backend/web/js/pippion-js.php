<?php
use yii\helpers\Url;
use backend\helpers\ExtraFunctions;
$ExtraFunctions = new ExtraFunctions;
?>
<script>
function izaberiJezik(el)
{
	<?php 	
		$actual_link = $ExtraFunctions->actual_link;
		$parse_url=parse_url($actual_link, PHP_URL_QUERY) ? '&' : '?'; 
	?>
	var jezik=$(el).val();
	if(jezik=="en")
		window.location="<?= $actual_link.$parse_url.'lang=en';  ?>";
	else if(jezik=="hr")
		window.location="<?= $actual_link.$parse_url.'lang=hr'; ?>";
	else if(jezik=="fr")
		window.location="<?= $actual_link.$parse_url.'lang=fr'; ?>";
	else if(jezik=="nl")
		window.location="<?= $actual_link.$parse_url.'lang=nl'; ?>";
	else if(jezik=="hu")
		window.location="<?= $actual_link.$parse_url.'lang=hu'; ?>";
	else if(jezik=="de")
		window.location="<?= $actual_link.$parse_url.'lang=de'; ?>";
	else if(jezik=="zh-cn")
		window.location="<?= $actual_link.$parse_url.'lang=zh-cn'; ?>";
	else if(jezik=="fr")
		window.location="<?= $actual_link.$parse_url.'lang=fr'; ?>";
}

function tokenInput_user()
{
	$("#searchUserFilter, .searchUserFilter").tokenInput("<?php echo Url::to('/site/suggest-user') ?>", 
	{
		theme: "facebook",
		tokenLimit:1,
		minChars:3,
		placeholder: '<?= Yii::t('default', 'Username') ?>',
		onAdd: function(item)
		{
			$("#searchUserFilter").val(item.id);
		},
		<?php 
		//this is used when someone wants to send direct message to specific user without searching it, for example after auctions ended
		if(isset($_GET["iduser"]) && isset($_GET["username"])):
		?>
		prePopulate:
		[
			<?= '{id:'.$_GET["iduser"].', name:"'.$_GET["username"].'"}'; ?>
		],
		<?php endif; ?>
		
	});	
}

$(document).ready(function(e) 
{  
	tokenInput_user();
});

/*Ako korisnik želi obrisati neku stvari, bilo koju, da ga prvo pita želi li stvarno obrisati
<form onsubmit="return areYouSure()">
*/
function areYouSure()
{
	var x = confirm('<?= Yii::t('default', 'Are you sure?'); ?>');
	if(x==true)
		return true;
	else
		return false;
}


//----------------PUSHER-------------------------
var pusher = new Pusher('f047a8d93ab7001f3bee');
var channel = pusher.subscribe('<?= Yii::$app->params['pusher_channel'] ?>');

//new message event
channel.bind('<?= Yii::$app->params['event_new_message'].Yii::$app->user->getId() ?>', function(data) 
{
	notificationNewMessage(data.number_of_unread_msgs);
});

/*MESSAGES*/
//number - number of unread messages
function notificationNewMessage(number)
{
	$(".js-msgs-badge").text(number);
}
</script>
