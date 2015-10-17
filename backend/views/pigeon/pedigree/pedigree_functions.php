<?php
function uzgajivac($mysqli, $sql)
{
	$r=mysqli_query($mysqli,$sql);
	$x=mysqli_fetch_assoc($r);
	$uzgajivac=$x["name_of_breeder"];
	$drzava=$x["country"];
	$mjesto=$x["town"];
	$adresa=$x["address"];
	$tel1=$x["tel1"];
	$tel2=$x["tel2"];
	$mob1=$x["mob1"];
	$mob2=$x["mob2"];
	$email1=$x["email1"];
	$email2=$x["email2"];
	$fax=$x["fax"];
	$website=$x["website"];
	
	//NEŠTO JEDNOSTAVNO AKO POSTOJI TELEFON2 I MOBITEL2 DA STAVI ZAREZ ISPRED, AKO NE POSTOJI DA NE STAVI
	(!empty($tel2)) ? $zarez1=", " : $zarez1="";
	(!empty($mob2)) ? $zarez2=", " : $zarez2="";
	(!empty($email2)) ? $zarez3=", " : $zarez3="";
	
	echo '
		<table width="100%" border="0">
		  <tr>
			<td width="500"> '.$drzava.', '.$mjesto.', '.$adresa.'<br>
			  <strong>tel:</strong> '.$tel1.''.$zarez1.''.$tel2.'; <strong>mob:</strong> '.$mob1.''.$zarez2.''.$mob2.'<br>
			  <strong>email:</strong> '.$email1.''.$zarez3.''.$email2.'<br>
			  <strong>website:</strong> '.$website.' </td>
			<td align="center"><span id="uzgajivac">'.$uzgajivac.'</span></td>
		  </tr>
		</table>
		';
}

function dobij_sve_potrebne_ID($mysqli, $sql_mg_popis_golubova, $IDgolub)
{
	//UZMI ID OCA I MAJKE IZ mg_popis_golubova
	$r=mysqli_query($mysqli,$sql_mg_popis_golubova." AND IDpigeon='$IDgolub'");
	$x=mysqli_fetch_assoc($r);
	
	//SPREMI id OCA I MAJKE U GLOBALS
	$GLOBALS["otac"]=$x["IDfather"];
	$GLOBALS["majka"]=$x["IDmother"];
	
	//*************OČEVA STRANA***************
	//OA, OB
	$r=mysqli_query($mysqli,$sql_mg_popis_golubova." AND IDpigeon='".$GLOBALS["otac"]."'");
	$x=mysqli_fetch_assoc($r);
	$GLOBALS["OA"]=$x["IDfather"];
	$GLOBALS["OB"]=$x["IDmother"];

	//OAA, OAB
	$r=mysqli_query($mysqli,$sql_mg_popis_golubova." AND IDpigeon='".$GLOBALS["OA"]."'");
	$x=mysqli_fetch_assoc($r);
	$GLOBALS["OAA"]=$x["IDfather"];
	$GLOBALS["OAB"]=$x["IDmother"];

	//OBA, OBB
	$r=mysqli_query($mysqli,$sql_mg_popis_golubova." AND IDpigeon='".$GLOBALS["OB"]."'");
	$x=mysqli_fetch_assoc($r);
	$GLOBALS["OBA"]=$x["IDfather"];
	$GLOBALS["OBB"]=$x["IDmother"];
	
	//OAA1, OAA2
	$r=mysqli_query($mysqli,$sql_mg_popis_golubova." AND IDpigeon='".$GLOBALS["OAA"]."'");
	$x=mysqli_fetch_assoc($r);
	$GLOBALS["OAA1"]=$x["IDfather"];
	$GLOBALS["OAA2"]=$x["IDmother"];
	
	//OAB1, OAB2
	$r=mysqli_query($mysqli,$sql_mg_popis_golubova." AND IDpigeon='".$GLOBALS["OAB"]."'");
	$x=mysqli_fetch_assoc($r);
	$GLOBALS["OAB1"]=$x["IDfather"];
	$GLOBALS["OAB2"]=$x["IDmother"];
	
	//OBA1, OBA2
	$r=mysqli_query($mysqli,$sql_mg_popis_golubova." AND IDpigeon='".$GLOBALS["OBA"]."'");
	$x=mysqli_fetch_assoc($r);
	$GLOBALS["OBA1"]=$x["IDfather"];
	$GLOBALS["OBA2"]=$x["IDmother"];

	//OBB1, OBB2
	$r=mysqli_query($mysqli,$sql_mg_popis_golubova." AND IDpigeon='".$GLOBALS["OBB"]."'");
	$x=mysqli_fetch_assoc($r);
	$GLOBALS["OBB1"]=$x["IDfather"];
	$GLOBALS["OBB2"]=$x["IDmother"];
	//*************OČEVA STRANA***************


	//*************MAJČINA STRANA***************
	//MA, MB
	$r=mysqli_query($mysqli,$sql_mg_popis_golubova." AND IDpigeon='".$GLOBALS["majka"]."'");
	$x=mysqli_fetch_assoc($r);
	$GLOBALS["MA"]=$x["IDfather"];
	$GLOBALS["MB"]=$x["IDmother"];

	//MAA, MAB
	$r=mysqli_query($mysqli,$sql_mg_popis_golubova." AND IDpigeon='".$GLOBALS["MA"]."'");
	$x=mysqli_fetch_assoc($r);
	$GLOBALS["MAA"]=$x["IDfather"];
	$GLOBALS["MAB"]=$x["IDmother"];

	//MBA, MBB
	$r=mysqli_query($mysqli,$sql_mg_popis_golubova." AND IDpigeon='".$GLOBALS["MB"]."'");
	$x=mysqli_fetch_assoc($r);
	$GLOBALS["MBA"]=$x["IDfather"];
	$GLOBALS["MBB"]=$x["IDmother"];

	//MAA1, MAA2
	$r=mysqli_query($mysqli,$sql_mg_popis_golubova." AND IDpigeon='".$GLOBALS["MAA"]."'");
	$x=mysqli_fetch_assoc($r);
	$GLOBALS["MAA1"]=$x["IDfather"];
	$GLOBALS["MAA2"]=$x["IDmother"];

	//MAB1, MAB2
	$r=mysqli_query($mysqli,$sql_mg_popis_golubova." AND IDpigeon='".$GLOBALS["MAB"]."'");
	$x=mysqli_fetch_assoc($r);
	$GLOBALS["MAB1"]=$x["IDfather"];
	$GLOBALS["MAB2"]=$x["IDmother"];
	
	//MBA1, MBA2
	$r=mysqli_query($mysqli,$sql_mg_popis_golubova." AND IDpigeon='".$GLOBALS["MBA"]."'");
	$x=mysqli_fetch_assoc($r);
	$GLOBALS["MBA1"]=$x["IDfather"];
	$GLOBALS["MBA2"]=$x["IDmother"];

	//MBB1, MBB2
	$r=mysqli_query($mysqli,$sql_mg_popis_golubova." AND IDpigeon='".$GLOBALS["MBB"]."'");
	$x=mysqli_fetch_assoc($r);
	$GLOBALS["MBB1"]=$x["IDfather"];
	$GLOBALS["MBB2"]=$x["IDmother"];

	//*************MAJČINA STRANA***************

}

function golub($mysqli, $sql_mg_golub, $IDgolub)
{
	//UZMI PODATKE IZ mg_golub
	$r=mysqli_query($mysqli,$sql_mg_golub." AND mg_pigeon.ID='$IDgolub'");
	$x=mysqli_fetch_assoc($r);
	
	//SPOL DA SE ISPRAVAN ISPIŠE JER JE U BAZI SA X I Y ODREĐEN
	if($x["sex"]=="X")
		{$spol=$GLOBALS["M"];}
	else
		{$spol=$GLOBALS["Z"];}
	
	$ime=empty($x["name"])? "---" : $x["name"];
	$boja=empty($x["color"])? "---" : $x["color"];
	$rasa=empty($x["breed"])? "---" : $x["breed"];
	
	//this hidden input I use to collect the name of this pedigree at the end of pedigree_main.php javscript: anchor_link.download
	echo '<input type="hidden" id="pedigree_name" value="'.$x["country"].'_'.$spol.'_'.$x["pigeonnumber"].'">';
	
	echo '
		<tr>
          <td align="center" valign="middle" class="drzava">'.$x["country"].'</td>
          <td align="center" valign="middle" class="broj">'.$x["pigeonnumber"].'</td>
          <td align="center" valign="middle" class="spol">'.$spol.'</td>
        </tr>
        <tr>
          <td colspan="3" valign="middle" class="ime">'.$ime.'</td>
        </tr>
        <tr>
          <td colspan="3" valign="middle" class="boja">'.$boja.'</td>
        </tr>
        <tr>
          <td colspan="3" valign="middle" class="rasa" style="border-bottom:1px solid #000;">'.$rasa.'</td>
        </tr>
		';
		
		//IME ZA PDF
		$GLOBALS["ime"]=$x["country"]."_".$x["pigeonnumber"]."_".$spol;
}


function stupac1($mysqli, $sql_mg_golub, $sql_mg_podaci_o_golubu, $celija)
{
	
	//$celija mi predstavlja o kojoj celiji se radi je li to OA, OB, MA ili MB
	//PA ĆU U VARIJABLU $ID STAVITI VRIJEDNOST GLOBALS
	if($celija=="otac")
	{
		$ID=$GLOBALS["otac"];
	}
	else
	{
		$ID=$GLOBALS["majka"];
	}
	
	//UZMI PODATKE IZ mg_golub
	$r=mysqli_query($mysqli,$sql_mg_golub." AND mg_pigeon.ID='$ID'");
	$x=mysqli_fetch_assoc($r);
	$row=mysqli_num_rows($r);
	//PROVJERI JEL JE UHVATIO NEKE REDOVE, AKO NIJE STAVI CRTICE KAO STRING
	if ($row!=0)
	{
		//ako su polja iz baze prazna stavi crtice
		$drzava=empty($x["country"]) ? "----" : $x["country"];
		$brojgoluba=empty($x["pigeonnumber"]) ? "----" : $x["pigeonnumber"];
		$ime=empty($x["name"]) ? "----" : $x["name"];
		$boja=empty($x["color"]) ? "----" : $x["color"];
		$rasa=empty($x["breed"]) ? "----" : $x["breed"];

		if($x["sex"]=="X")
			{$spol=$GLOBALS["M"];}
		else
			{$spol=$GLOBALS["Z"];}
	}
	else
	{
		$drzava="---";
		$brojgoluba="-----------";
		$spol="-";
		$ime="------";
		$boja="------";
		$rasa="------";
	}
	

	echo '
		<tr>
          <td align="center" valign="middle" class="drzava">'.$drzava.'</td>
          <td align="center" valign="middle" class="broj">'.$brojgoluba.'</td>
          <td align="center" valign="middle" class="spol">'.$spol.'</td>
        </tr>
        <tr>
          <td colspan="3" valign="middle" class="ime">'.$ime.'</td>
        </tr>
        <tr>
          <td colspan="3" valign="middle" class="boja">'.$boja.'</td>
        </tr>
        <tr>
          <td colspan="3" valign="middle" class="rasa">'.$rasa.'</td>
        </tr>';
		
	//SAD PRIKUPI PODATKE O GOLUBU IZ mg_podaci_o_golubu
	$podatak=[];
	$r=mysqli_query($mysqli,$sql_mg_podaci_o_golubu." AND IDpigeon='$ID' ORDER BY date_created DESC LIMIT 10");
	while($x=mysqli_fetch_assoc($r))
	{
		$podatak[]=$x["pigeondata"];
	}
	$tmp_podatak=implode("<br><br>",$podatak);
	echo '
        <tr>
          <td colspan="3" valign="top" class="podaci_1stupac"><br>'.substr($tmp_podatak,0,340).'...</td>
        </tr>';
}


function stupac2($mysqli, $sql_mg_golub, $sql_mg_podaci_o_golubu, $celija)
{
	
	//$celija mi predstavlja o kojoj celiji se radi je li to OA, OB, MA ili MB
	//PA ĆU U VARIJABLU $ID STAVITI VRIJEDNOST GLOBALS
	if($celija=="OA")
	{
		$ID=$GLOBALS["OA"];
	}
	else if($celija=="OB")
	{
		$ID=$GLOBALS["OB"];
	}
	else if($celija=="MA")
	{
		$ID=$GLOBALS["MA"];
	}
	else//MB
	{
		$ID=$GLOBALS["MB"];
	}
	
	//UZMI PODATKE IZ mg_golub
	$r=mysqli_query($mysqli,$sql_mg_golub." AND mg_pigeon.ID='$ID'");
	$x=mysqli_fetch_assoc($r);
	$row=mysqli_num_rows($r);
	//PROVJERI JEL JE UHVATIO NEKE REDOVE, AKO NIJE STAVI CRTICE KAO STRING
	if ($row!=0)
	{
		//ako su polja iz baze prazna stavi crtice
		$drzava=empty($x["country"]) ? "----" : $x["country"];
		$brojgoluba=empty($x["pigeonnumber"]) ? "----" : $x["pigeonnumber"];
		$ime=empty($x["name"]) ? "----" : $x["name"];
		$boja=empty($x["color"]) ? "----" : $x["color"];
		$rasa=empty($x["breed"]) ? "----" : $x["breed"];

		if($x["sex"]=="X")
			{$spol=$GLOBALS["M"];}
		else
			{$spol=$GLOBALS["Z"];}
	}
	else
	{
		$drzava="---";
		$brojgoluba="-----------";
		$spol="-";
		$ime="------";
		$boja="------";
		$rasa="------";
	}

	echo '<tr>
          <td align="center" valign="middle" class="drzava">'.$drzava.'</td>
          <td align="center" valign="middle" class="broj">'.$brojgoluba.'</td>
          <td align="center" valign="middle" class="spol">'.$spol.'</td>
        </tr>
        <tr>
          <td colspan="3" valign="middle" class="ime">'.$ime.'</td>
        </tr>
        <tr>
          <td colspan="3" valign="middle" class="boja">'.$boja.'</td>
        </tr>
        <tr>
          <td colspan="3" valign="middle" class="rasa">'.$rasa.'</td>
        </tr>';
		
	$podatak=[];
	$r=mysqli_query($mysqli,$sql_mg_podaci_o_golubu." AND IDpigeon='$ID' ORDER BY date_created DESC LIMIT 5");
	while($x=mysqli_fetch_assoc($r))
	{
		$podatak[]=$x["pigeondata"];
	}

	$tmp_podatak=implode("<br><br>",$podatak);
	echo '<tr>
          <td colspan="3" valign="top" class="podaci_2stupac"><br>'.substr($tmp_podatak,0,130).'...</td>
        </tr>';
}

function stupac3($mysqli, $sql_mg_golub, $celija)
{
	//$celija mi predstavlja o kojoj celiji se radi je li to OA, OB, MA ili MB
	//PA ĆU U VARIJABLU $ID STAVITI VRIJEDNOST GLOBALS
	if($celija=="OAA")
	{		$ID=$GLOBALS["OAA"];	}
	else if($celija=="OAB")
	{		$ID=$GLOBALS["OAB"];	}
	else if($celija=="OBA")
	{		$ID=$GLOBALS["OBA"];	}
	else if($celija=="OBB")
	{		$ID=$GLOBALS["OBB"];	}
	else if($celija=="MAA")
	{		$ID=$GLOBALS["MAA"];	}
	else if($celija=="MAB")
	{		$ID=$GLOBALS["MAB"];	}
	else if($celija=="MBA")
	{		$ID=$GLOBALS["MBA"];	}
	else//MBB
	{		$ID=$GLOBALS["MBB"];	}

	//UZMI PODATKE IZ mg_golub
	$r=mysqli_query($mysqli,$sql_mg_golub." AND mg_pigeon.ID='$ID'");
	$x=mysqli_fetch_assoc($r);
	$row=mysqli_num_rows($r);
	//PROVJERI JEL JE UHVATIO NEKE REDOVE, AKO NIJE STAVI CRTICE KAO STRING
	if ($row!=0)
	{
		//ako su polja iz baze prazna stavi crtice
		$drzava=empty($x["country"]) ? "----" : $x["country"];
		$brojgoluba=empty($x["pigeonnumber"]) ? "----" : $x["pigeonnumber"];
		$ime=empty($x["name"]) ? "----" : $x["name"];
		$boja=empty($x["color"]) ? "----" : $x["color"];
		$rasa=empty($x["breed"]) ? "----" : $x["breed"];
		if($x["sex"]=="X")
			{$spol=$GLOBALS["M"];}
		else
			{$spol=$GLOBALS["Z"];}
	}
	else
	{
		$drzava="---";
		$brojgoluba="-----------";
		$spol="-";
		$ime="------";
		$boja="------";
		$rasa="------";
	}


	echo '<tr>
          <td align="center" valign="middle" class="drzava">'.$drzava.'</td>
          <td align="center" valign="middle" class="broj">'.$brojgoluba.'</td>
          <td align="center" valign="middle" class="spol">'.$spol.'</td>
        </tr>
        <tr>
          <td colspan="3" valign="middle" class="ime">'.$ime.'</td>
        </tr>
        <tr>
          <td colspan="3" valign="middle" class="boja">'.$boja.'</td>
        </tr>
        <tr>
          <td colspan="3" valign="middle" class="rasa stupac3_rasa">'.$rasa.'</td>
        </tr>';
}



function stupac4($mysqli, $sql_mg_golub, $celija)
{
	//$celija mi predstavlja o kojoj celiji se radi je li to OA, OB, MA ili MB
	//PA ĆU U VARIJABLU $ID STAVITI VRIJEDNOST GLOBALS
	$style=""; // OVO MI KORISTI KAKO BI DODIJELIO STIL stupac4_rasa ZBOG ONE DOLJNE CRTE  U ĆELIJAMA
	if($celija=="OAA1")
	{		$ID=$GLOBALS["OAA1"];	}
	else if($celija=="OAA2")
	{		$ID=$GLOBALS["OAA2"]; $style="stupac4_rasa";	}
	else if($celija=="OAB1")
	{		$ID=$GLOBALS["OAB1"];	}
	else if($celija=="OAB2")
	{		$ID=$GLOBALS["OAB2"]; $style="stupac4_rasa";	}
	else if($celija=="OBA1")
	{		$ID=$GLOBALS["OBA1"];	}
	else if($celija=="OBA2")
	{		$ID=$GLOBALS["OBA2"]; $style="stupac4_rasa";	}
	else if($celija=="OBB1")
	{		$ID=$GLOBALS["OBB1"];	}
	else if($celija=="OBB2")
	{		$ID=$GLOBALS["OBB2"]; $style="stupac4_rasa";	}
	else if($celija=="MAA1")
	{		$ID=$GLOBALS["MAA1"];	}
	else if($celija=="MAA2")
	{		$ID=$GLOBALS["MAA2"]; $style="stupac4_rasa";	}
	else if($celija=="MAB1")
	{		$ID=$GLOBALS["MAB1"];	}
	else if($celija=="MAB2")
	{		$ID=$GLOBALS["MAB2"]; $style="stupac4_rasa";	}
	else if($celija=="MBA1")
	{		$ID=$GLOBALS["MBA1"];	}
	else if($celija=="MBA2")
	{		$ID=$GLOBALS["MBA2"]; $style="stupac4_rasa";	}
	else if($celija=="MBB1")
	{		$ID=$GLOBALS["MBB1"];	}
	else//MBB2
	{		$ID=$GLOBALS["MBB2"]; $style="stupac4_rasa";	 }

	//UZMI PODATKE IZ mg_golub
	$r=mysqli_query($mysqli,$sql_mg_golub." AND mg_pigeon.ID='$ID'");
	$x=mysqli_fetch_assoc($r);
	$row=mysqli_num_rows($r);
	//PROVJERI JEL JE UHVATIO NEKE REDOVE, AKO NIJE STAVI CRTICE KAO STRING
	if ($row!=0)
	{
		//ako su polja iz baze prazna stavi crtice
		$drzava=empty($x["country"]) ? "----" : $x["country"];
		$brojgoluba=empty($x["pigeonnumber"]) ? "----" : $x["pigeonnumber"];
		$ime=empty($x["name"]) ? "----" : $x["name"];
		$boja=empty($x["color"]) ? "----" : $x["color"];
		$rasa=empty($x["breed"]) ? "----" : $x["breed"];

		if($x["sex"]=="X")
		{$spol=$GLOBALS["M"];}
		else
		{$spol=$GLOBALS["Z"];}
	}
	else
	{
		$drzava="---";
		$brojgoluba="-----------";
		$spol="-";
		$ime="------";
		$boja="------";
		$rasa="------";
	}
	

	echo '<tr>
          <td align="center" valign="middle" class="drzava4">'.$drzava.'</td>
          <td align="center" valign="middle" class="broj4">'.$brojgoluba.'</td>
          <td align="center" valign="middle" class="spol4">'.$spol.'</td>
        </tr>
        <tr>
          <td colspan="3" valign="middle" class="ime4">'.$ime.'</td>
        </tr>
        <tr>
          <td colspan="3" valign="middle" class="boja4 '.$style.'">'.$boja.'</td>
        </tr>';
		/*<tr>
          <td colspan="3" valign="middle" class="rasa4 '.$style.'">'.$rasa.'</td>
        </tr>*/
}
?>
