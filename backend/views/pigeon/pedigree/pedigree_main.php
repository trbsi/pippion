<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use backend\helpers\ExtraFunctions;
$ExtraFunctions = new ExtraFunctions;
?>


<?php if(isset($_POST['printdownload']) && $_POST['printdownload']=="print"): ?>
<script>
window.onload
{
	window.print();
}
</script>
<?php endif; ?>


<?php ob_start(); ?>
<div id="download">
  <page size="A4" id="page">
    <meta charset="utf-8" />
    <title>Pedigree</title>
    <style>

body {
  background: rgb(204,204,204); 
}
page[size="A4"] {
  background: white;
  width: 21cm;
  height: 29.7cm;
  display: block;
  margin: 0 auto;
  margin-bottom: 0.5cm;
 /* box-shadow: 0 0 0.5cm rgba(0,0,0,0.5);*/
}
@media print 
{
  body, page[size="A4"] {
    margin: 0;
    box-shadow: 0;
  }
  

}

/*---------------------------------------------------*/


*{
	font-family: Calibri;
}

@media screen{
	
	#tablebig
	{
		width:95%;
		height:95%;
		padding-top:30px;
	}
}

table
{
	width:100%;
	height:100%;
	font-size:12px;
}

.sirinaStupca
{
	width:24.5%;
}



/*
UZGAJIVAČ I INFORMACIJE
*/
span#uzgajivac {
	font-size: 16px;
	font-weight: bold;
}
#info {
	font-size: 13px;
	border: 1px dotted #000000;
	padding-left: 5px;
	height:60px;
}

#info_space{
	height:10px;
}
/*
-----------------------------------------------

1.STUPAC

-----------------------------------------------
*/
.stupac1_razmak {
	height: 5px;
	
}
.drzava {
	border: 1px solid #000;
	
}
.broj {
	border-top: 1px solid #000;
	border-bottom: 1px solid #000;
	border-right: 1px solid #000;
}
.spol {
	border-top: 1px solid #000;
	border-bottom: 1px solid #000;
	border-right: 1px solid #000;
}
.ime {
	color: #CE2226;
	
	
	padding-left: 5px;
	border-bottom: 1px dotted #333;
	border-left: 1px solid #000;
	border-right: 1px solid #000;
}
.boja {
	color: #215C9E;
	
	
	padding-left: 5px;
	border-bottom: 1px dotted #333;
	border-left: 1px solid #000;
	border-right: 1px solid #000;
}
.rasa {
	color: #2C7831;
	
	
	padding-left: 5px;
	border-bottom: 1px dotted #333;
	border-left: 1px solid #000;
	border-right: 1px solid #000;
}
.podaci_1stupac {
	
	padding-left: 5px;
	border-left: 1px solid #000;
	border-right: 1px solid #000;
	border-bottom: 1px solid #000;
}
/*
-----------------------------------------------

2. STUPAC

-----------------------------------------------
*/
.podaci_2stupac {
	
	border-left: 1px solid #000;
	border-right: 1px solid #000;
	border-bottom: 1px solid #000;
	padding-left: 5px;
}


/*
-----------------------------------------------

3. STUPAC

-----------------------------------------------
*/
.stupac3_rasa {
	border-bottom: 1px solid #000;
	
}
.stupac3_razmak {
	height: 5px;
	
}
.stupac3_razmak_glavni {
	height: 5px;
	
}
/*
-----------------------------------------------

4.STUPAC

-----------------------------------------------
*/
.drzava4 {
	border: 1px solid #000;
}
.broj4 {
	border-top: 1px solid #000;
	border-bottom: 1px solid #000;
	border-right: 1px solid #000;
}
.spol4 {
	border-top: 1px solid #000;
	border-bottom: 1px solid #000;
	border-right: 1px solid #000;
}
.ime4 {
	color: #CE2226;
	padding-left: 5px;
	border-bottom: 1px dotted #333;
	border-left: 1px solid #000;
	border-right: 1px solid #000;
	
}
.boja4 {
	color: #215C9E;
	padding-left: 5px;
	border-bottom: none;
	border-left: 1px solid #000;
	border-right: 1px solid #000;
	
}
.rasa4 {
	color: #2C7831;
	padding-left: 5px;
	border-left: 1px solid #000;
	border-right: 1px solid #000;
	
}
.stupac4_rasa {
	border-bottom: 1px solid #000;
}
.stupac4_razmak {
	height: 9px;
	
}
</style>
 <?php
require "pedigree_functions.php";

$IDgolub=(int)$IDpigeon; //this variable isset in pedigree.php file as $IDpigeon
$ID_KORISNIK=(int)$IDuser; //this variable isset in pedigree.php file by $IDuser=Yii::$app->user->getId() 

//INFO O UZGAJIVAČU
$uzgajivac="SELECT * FROM mg_breeder WHERE IDuser='$ID_KORISNIK'";
//PRIKUPI PODATKE O SVAKOM GOLUBU
$sql_mg_golub="SELECT mg_pigeon.*, mg_pigeon_country.*
				FROM mg_pigeon 
				JOIN mg_pigeon_country ON (mg_pigeon_country.ID=mg_pigeon.IDcountry)
				WHERE mg_pigeon.IDuser='$ID_KORISNIK'";
//POTRAŽI ID OCA I MAJKE IZ mg_popis_golubova
$sql_mg_popis_golubova="SELECT * FROM mg_pigeon_list WHERE IDuser='$ID_KORISNIK'";

//PIRKUPI PODATKE O GOLUBU IZ mg_podaci_o_golubu
$sql_mg_podaci_o_golubu="SELECT * FROM mg_pigeon_data WHERE IDuser='$ID_KORISNIK'";

//NAPRAVI GLOBALS[] ZA SPOL
$GLOBALS["M"]=Yii::t('default', 'GOLUB_SPOL_M');
$GLOBALS["Z"]=Yii::t('default', 'GOLUB_SPOL_Z');

dobij_sve_potrebne_ID($mysqli, $sql_mg_popis_golubova, $IDgolub);
?>
    <table border="0" align="center" cellpadding="0" cellspacing="0" id="tablebig">
      <tr>
        <td colspan="7" id="info"><?php uzgajivac($mysqli, $uzgajivac); ?></td>
      </tr>
      <tr>
        <td colspan="7" id="info_space"></td>
      </tr>
      <tr>
        <td valign="top" class="sirinaStupca"><!-- 1. STUPAC -->
          
          <table border="0" cellpadding="0" cellspacing="0">
            <!-- OTAC -->
            <?php stupac1($mysqli, $sql_mg_golub, $sql_mg_podaci_o_golubu, "otac"); ?>
            <!-- OTAC -->
            
            <tr>
              <td colspan="3" class="stupac1_razmak"></td>
            </tr>
            <!-- GOLUB -->
            <?php golub($mysqli, $sql_mg_golub, $IDgolub); ?>
            <!-- GOLUB -->
            
            <tr>
              <td colspan="3" class="stupac1_razmak"></td>
            </tr>
            <!-- MAJKA -->
            <?php stupac1($mysqli, $sql_mg_golub, $sql_mg_podaci_o_golubu, "majka"); ?>
            <!-- MAJKA -->
            
          </table>
          
          <!-- 1. STUPAC --></td>
        <td valign="top" width="10"></td>
        <!-- RAZMAK -->
        
        <td valign="top" class="sirinaStupca"><!-- 2. STUPAC -->
          
          <table border="0" cellpadding="0" cellspacing="0">
            <!-- OA -->
            <?php stupac2($mysqli, $sql_mg_golub, $sql_mg_podaci_o_golubu, "OA") ?>
            <!-- OA -->
            
            <tr>
              <td colspan="3" class="stupac1_razmak"></td>
            </tr>
            <!-- OB -->
            <?php stupac2($mysqli, $sql_mg_golub, $sql_mg_podaci_o_golubu, "OB") ?>
            <!-- OB -->
            
            <tr>
              <td colspan="3" class="stupac1_razmak"></td>
            </tr>
            <!-- MA -->
            <?php stupac2($mysqli, $sql_mg_golub, $sql_mg_podaci_o_golubu, "MA") ?>
            <!-- MA -->
            
            <tr>
              <td colspan="3" class="stupac1_razmak"></td>
            </tr>
            <!-- MB -->
            
            <?php stupac2($mysqli, $sql_mg_golub, $sql_mg_podaci_o_golubu, "MB") ?>
            <!-- MB -->
            
          </table>
          
          </td><!-- 2. STUPAC -->
        <td valign="top" width="10"></td>
        <td valign="top" class="sirinaStupca"><!-- 3. STUPAC -->
          
          <table border="0" cellpadding="0" cellspacing="0" style="font-size:11px;">
            <!-- OAA -->
            <?php stupac3($mysqli, $sql_mg_golub, "OAA") ?>
            <!-- OAA -->
            
            <tr>
              <td colspan="3" class="stupac3_razmak"></td>
            </tr>
            <!-- OAB -->
            <?php stupac3($mysqli, $sql_mg_golub, "OAB") ?>
            <!-- OAB -->
            <tr>
              <td colspan="3" class="stupac3_razmak"></td>
            </tr>
            
            <!-- OBA -->
            <?php stupac3($mysqli, $sql_mg_golub, "OBA") ?>
            <!-- OBA -->
            
            <tr>
              <td colspan="3" class="stupac3_razmak"></td>
            </tr>
            <!-- OBB -->
            <?php stupac3($mysqli, $sql_mg_golub, "OBB") ?>
            <!-- OBB -->
            <tr>
              <td colspan="3" class="stupac3_razmak_glavni"></td>
            </tr>
            
            <!-- MAA -->
            <?php stupac3($mysqli, $sql_mg_golub, "MAA") ?>
            <!-- MAA -->
            
            <tr>
              <td colspan="3" class="stupac3_razmak"></td>
            </tr>
            <!-- MAB -->
            <?php stupac3($mysqli, $sql_mg_golub, "MAB") ?>
            <!-- MAB -->
            
            <tr>
              <td colspan="3" class="stupac3_razmak_glavni"></td>
            </tr>
            
            <!-- MBA -->
            <?php stupac3($mysqli, $sql_mg_golub, "MBA") ?>
            <!-- MBA -->
            
            <tr>
              <td colspan="3" class="stupac3_razmak"></td>
            </tr>
            <!-- MBB -->
            <?php stupac3($mysqli, $sql_mg_golub, "MBB") ?>
            <!-- MBB -->
            
          </table>
          
          </td><!-- 3. STUPAC -->
        <td valign="top" width="10"></td>
        <td valign="top" class="sirinaStupca"><!--4. STUPAC-->
          
          <table border="0" cellpadding="0" cellspacing="0" style="font-size:10px;">
            <!-- OAA1 -->
            <?php stupac4($mysqli, $sql_mg_golub, "OAA1") ?>
            <!-- OAA1 --> 
            
            <!-- OAA2 -->
            <?php stupac4($mysqli, $sql_mg_golub, "OAA2") ?>
            <!-- OAA2 -->
            <tr>
              <td colspan="3" class="stupac4_razmak"></td>
            </tr>
            
            <!-- OAB1 -->
            <?php stupac4($mysqli, $sql_mg_golub, "OAB1") ?>
            <!-- OAB1 --> 
            
            <!-- OAB2 -->
            <?php stupac4($mysqli, $sql_mg_golub, "OAB2") ?>
            <!-- OAB2 -->
            
            <tr>
              <td colspan="3" class="stupac4_razmak"></td>
            </tr>
            
            <!-- OBA1 -->
            <?php stupac4($mysqli, $sql_mg_golub, "OBA1") ?>
            <!-- OBA1 --> 
            
            <!-- OBA2 -->
            <?php stupac4($mysqli, $sql_mg_golub, "OBA2") ?>
            <!-- OBA2 -->
            <tr>
              <td colspan="3" class="stupac4_razmak"></td>
            </tr>
            
            <!-- OBB1 -->
            <?php stupac4($mysqli, $sql_mg_golub, "OBB1") ?>
            <!-- OBB1 --> 
            
            <!-- OBB2 -->
            <?php stupac4($mysqli, $sql_mg_golub, "OBB2") ?>
            <!-- OBB2 -->
            <tr>
              <td colspan="3" class="stupac4_razmak"></td>
            </tr>
            
            <!-- MAA1 -->
            <?php stupac4($mysqli, $sql_mg_golub, "MAA1") ?>
            <!-- MAA1 --> 
            
            <!-- MAA2 -->
            <?php stupac4($mysqli, $sql_mg_golub, "MAA2") ?>
            <!-- MAA2 -->
            
            <tr>
              <td colspan="3" class="stupac4_razmak"></td>
            </tr>
            
            <!-- MAB1 -->
            <?php stupac4($mysqli, $sql_mg_golub, "MAB1") ?>
            <!-- MAB1 --> 
            
            <!-- MAB2 -->
            <?php stupac4($mysqli, $sql_mg_golub, "MAB2") ?>
            <!-- MAB2 -->
            
            <tr>
              <td colspan="3" class="stupac4_razmak"></td>
            </tr>
            
            <!-- MBA1 -->
            <?php stupac4($mysqli, $sql_mg_golub, "MBA1") ?>
            <!-- MBA1 --> 
            
            <!-- MBA2 -->
            <?php stupac4($mysqli, $sql_mg_golub, "MBA2") ?>
            <!-- MBA2 -->
            
            <tr>
              <td colspan="3" class="stupac4_razmak"></td>
            </tr>
            
            <!-- MBB1 -->
            <?php stupac4($mysqli, $sql_mg_golub, "MBB1") ?>
            <!-- MB1 --> 
            
            <!-- MBB2 -->
            <?php stupac4($mysqli, $sql_mg_golub, "MBB2") ?>
            <!-- MBB2 -->
            
          </table>
          
          </td><!--4. STUPAC-->
      </tr>
    </table>
  </page>
</div>
<?php $get_ob=ob_get_clean(); ?>
<?php echo $get_ob; ?>

<?php if(isset($_POST['printdownload']) && $_POST['printdownload']=="download"): ?>
<?php //$ExtraFunctions->downloadFile($get_ob); ?>
<script type="text/javascript" src="<?= Yii::getAlias("@web"); ?>/js/html2canvas.js"></script>
<script type="text/javascript">
function checkBrowser()
{
    c=navigator.userAgent.search("Chrome");
    f=navigator.userAgent.search("Firefox");
    m8=navigator.userAgent.search("MSIE 8.0");
    m9=navigator.userAgent.search("MSIE 9.0");
    if (c>-1)
	{
        brwsr = "Chrome";
    }
    else if(f>-1)
	{
        brwsr = "Firefox";
    }
	else if (m9>-1)
	{
        brwsr ="MSIE 9.0";
    }
	else if (m8>-1)
	{
        brwsr ="MSIE 8.0";
    }
    return brwsr;
}

var anchor_link = document.createElement('a');
var pedigree_name=document.getElementById("pedigree_name");
html2canvas(document.getElementById("page")).then(function(canvas) 
{
	document.body.appendChild(canvas);
	//http://christianheilmann.com/2014/04/22/quick-one-using-download-attribute-on-links-to-save-canvas-as-png/
	anchor_link.href = canvas.toDataURL();
	anchor_link.download = pedigree_name.value+".png";

	if(checkBrowser()=="Firefox")
	{
		//http://stackoverflow.com/questions/809057/how-do-i-programmatically-click-on-an-element-in-firefox
		var theEvent = document.createEvent("MouseEvent");
		theEvent.initMouseEvent("click", true, true, window, 0, 0, 0, 0, 0, false, false, false, false, 0, null);
		anchor_link.dispatchEvent(theEvent);
	}
	else
		anchor_link.click();
	
	canvas.remove();
	window.history.back();
});
</script>
<?php endif; ?>

