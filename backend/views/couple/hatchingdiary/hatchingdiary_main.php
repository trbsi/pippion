<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use backend\helpers\ExtraFunctions;
$ExtraFunctions = new ExtraFunctions;

?>
<?php if(isset($_POST['printdownload']) && $_POST["printdownload"]=="print"):?>
<script>
window.onload
{
	window.print();
}
</script>
<?php endif ;?>


<?php ob_start(); ?>
<div id="download">
<meta charset="utf-8" />

<style>

table
{
	width:100%;
}
.table_legla{
	width:80%;
}
.naslov {
	text-transform:uppercase;
	border-bottom:1px solid #333;
	width:185mm;
}
.table_podacipar {
	font-size:12px;
}
.muzjak {
	padding-left:30px;
	border-bottom:1px dotted #666;
	border-left:1px dotted #666;
}
.zenka {
	border-bottom:1px dotted #666;
	border-right:1px dotted #666;
}
.izmeduMiZ {
	border-bottom:1px dotted #666;
}
.par {
	border:1px solid #666;
	width:110mm;
}
.table_legla {
	border:1px solid #999;
	font-size:12px;
	border-collapse:collapse;
}
.legla_cell {
	width:110px;
}
</style>
</head>
<body>
<?php
//LOADIRAJ FUNKCIJE
require "hatchingdiary_functions.php";
//SPREMI U VRAIJABLU POSTANE PODATKE
$ID_KORISNIK=(int)$_POST_USER_ID;
$godina=(int)$_POST_YEAR;
$GLOBALS['mg_par']=$coupleTable;
$GLOBALS['mg_leglo']=$broodTable;


//DEFINIRAJ NEKE STRINGOVE IZ LANGUAGE DATOTEKE DA MOGU KORISTITI U FUNKCIJI U PDFfunkcije.php
$GLOBALS['PDF_DLEZENJA_PAR']=Yii::t('default', "PDF_DLEZENJA_PAR");
$GLOBALS['PDF_DLEZENJA_MUZJAK']=Yii::t('default', "PDF_DLEZENJA_MUZJAK");
$GLOBALS['PDF_DLEZENJA_ZENKA']=Yii::t('default', "PDF_DLEZENJA_ZENKA");

$GLOBALS['PDF_DLEZENJA_LEGLO']=Yii::t('default', "PDF_DLEZENJA_LEGLO");
$GLOBALS['PDF_DLEZENJA_1JAJE']=Yii::t('default', "PDF_DLEZENJA_1JAJE");
$GLOBALS['PDF_DLEZENJA_DATUMLEZENJA']=Yii::t('default', "PDF_DLEZENJA_DATUMLEZENJA");
$GLOBALS['PDF_DLEZENJA_DRZAVA']=Yii::t('default', "PDF_DLEZENJA_DRZAVA");
$GLOBALS['PDF_DLEZENJA_BROJPRSTENA']=Yii::t('default', "PDF_DLEZENJA_BROJPRSTENA");
$GLOBALS['PDF_DLEZENJA_BOJA']=Yii::t('default', "PDF_DLEZENJA_BOJA");

?>

<!-- NASLOV, UZGAJIVAČ I GODINA -->
<table border="0" align="center">
  <tr>
    <td colspan="3" align="center" class="naslov"><strong><?php echo Yii::t('default', "PDF_DLEZENJA_NASLOV")?></strong></td>
  </tr>
  <tr>
    <td colspan="3" align="center" style="text-transform:uppercase;"><strong></strong></td>
  </tr>
  <tr>
    <td style="border-bottom:1px dotted #333333;">
		<?php echo Yii::t('default', "PDF_DLEZENJA_UZGAJIVAC").": ";   uzgajivac($mysqli,$ID_KORISNIK);?>
    </td>
    <td style="border-bottom:1px dotted #333333;">&nbsp;</td>
    <td align="right" style="border-bottom:1px dotted #333333;"><?php echo Yii::t('default', "PDF_DLEZENJA_GODINA").": ".$godina ?></td>
  </tr>
</table>
<br />
<!-- NASLOV, UZGAJIVAČ I GODINA -->

<?php
//PREUZMI SVE ID-ove IZ mg_par_... TAKO DA ONDA MOGU UZETI PODATKE O MUŽJAKU I ŽENKI I PROVJERAVAT LEGLA TOG PARA
$IDpara=array();
uzmi_id_parova($mysqli, $ID_KORISNIK, $IDpara, $godina);
legla($mysqli,$ID_KORISNIK,$IDpara);

?>
</div>

<?php $get_ob=ob_get_clean(); ?>
<?php echo $get_ob; ?>

<?php if(isset($_POST['printdownload']) && $_POST["printdownload"]=="download"):?>
<?php $ExtraFunctions->downloadFile($get_ob); ?>
<?php endif ;?>
