<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use backend\helpers\ExtraFunctions;
$ExtraFunctions = new ExtraFunctions;
?>

<?php if($_GET['printdownload']=="print"): ?>
<script>
window.onload
{
    window.print();
}
</script>
<?php endif; ?>

<?php ob_start(); ?>
<div id="download">
<meta charset="utf-8">
<script src="http://code.jquery.com/jquery-1.11.2.min.js"></script>
<script>
$(document).ready(function(e) {
	//hide last column where are action buttons (edit and delete). It is hidden via .ActionColumn-css, but not header column. Remove this and you will see
	$(".GridView-header th").last().hide();
});
</script>
<style>
* {
	font-family: Georgia, ‘Times New Roman’, serif;
}
a{
	text-decoration:none;
	color:black;
}
table {
	border-collapse:collapse;
	width:100%;
}
table th {
	font-weight:bold;
	border:1px solid;
}
table td {
	border:1px solid;
	padding:4px;
}
.filters, .ActionColumn-css{
	display:none;
}
.GridView-header a, .GridView-header
{
	font-size:12px;
	white-space:pre-wrap;
} 

</style>

<?php require "_view.php"; ?>	

</div>
<?php $get_ob=ob_get_clean(); ?>
<?php echo $get_ob; ?>

<?php if($_GET['printdownload']=="download"): ?>
<?php $ExtraFunctions->downloadFile($get_ob); ?>
<?php endif ;?>
