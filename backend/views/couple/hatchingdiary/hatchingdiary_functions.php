<?php
function uzgajivac($mysqli,$ID_KORISNIK)
{
	$r=mysqli_query($mysqli,"SELECT name_of_breeder FROM mg_breeder WHERE IDuser='$ID_KORISNIK'");
	$x=mysqli_fetch_assoc($r);
	echo $x["name_of_breeder"];
}



function uzmi_id_parova($mysqli,$ID_KORISNIK,&$IDpara, $godina) //PASS BY REFERENCE
{
	$i=0;
	$r=mysqli_query($mysqli,"SELECT ID 
							FROM ".$GLOBALS['mg_par']."
							WHERE IDuser='$ID_KORISNIK' AND year='$godina'
							ORDER BY ID ASC");
	while($x=mysqli_fetch_assoc($r))
	{
		$IDpara[$i]=$x["ID"];
		$i++;
	}
}



function legla($mysqli, $ID_KORISNIK, $IDpara)
{
	$mg_par=$GLOBALS['mg_par'];
	$mg_leglo=$GLOBALS['mg_leglo'];
	
	for($i=0;$i<count($IDpara);$i++)
	{ 
		//PRIKUPIT PODATKE O MUŽJAKU I ŽENKI I BROJ PARA
		$r=mysqli_query($mysqli,"SELECT 
								male.pigeonnumber AS Mbroj, 
								male.color AS Mboja, 
								male.breed AS Mrasa, 
								male.name AS Mime, 
								Mdrzava.country AS Mdrzava,
								female.pigeonnumber AS Zbroj, 
								female.color AS Zboja, 
								female.breed AS Zrasa, 
								female.name AS Zime, 
								Zdrzava.country AS Zdrzava,
								$mg_par.couplenumber
								FROM $mg_par
								JOIN mg_pigeon AS male ON (male.ID=$mg_par.male)
								JOIN mg_pigeon AS female ON (female.ID=$mg_par.female)
								LEFT JOIN mg_pigeon_country AS Mdrzava ON (male.IDcountry=Mdrzava.ID)
								LEFT JOIN mg_pigeon_country AS Zdrzava ON (female.IDcountry=Zdrzava.ID)
								WHERE $mg_par.ID='".$IDpara[$i]."' AND $mg_par.IDuser='$ID_KORISNIK' ");
		$x=mysqli_fetch_assoc($r);
		
		//TABELA ZA PODATKE O MUŽJAKU I ŽENKI (BOJA, IMA RASA, BROJ PARA)
		echo '
		<table border="0" align="center" cellpadding="0" cellspacing="0" class="table_podacipar">
		  <tr>
			<td colspan="3" align="center" class="par"><strong>'.$GLOBALS['PDF_DLEZENJA_PAR'].'</strong> ['.$x["couplenumber"].']
		
		</td>
			</tr>
		  <tr>
			<td align="left" width="35%" class="muzjak"><strong>'.$GLOBALS['PDF_DLEZENJA_MUZJAK'].'</strong></td>
			<td width="30%" class="izmeduMiZ">&nbsp;</td>
			<td align="left" width="35%" class="zenka"><strong>'.$GLOBALS['PDF_DLEZENJA_ZENKA'].'</strong></td>
		  </tr>
		  <tr>
			<td align="left" class="muzjak">['.$x["Mdrzava"].'] - '.$x["Mbroj"].'</td>
			<td class="izmeduMiZ">&nbsp;</td>
			<td align="left" class="zenka">['.$x["Zdrzava"].'] - '.$x["Zbroj"].'</td>
		  </tr>
		  <tr>
			<td align="left" class="muzjak">'.$x["Mboja"].'</td>
			<td class="izmeduMiZ">&nbsp;</td>
			<td align="left" class="zenka">'.$x["Zboja"].'</td>
		  </tr>
		  <tr>
			<td align="left" class="muzjak">'.$x["Mrasa"].'</td>
			<td class="izmeduMiZ">&nbsp;</td>
			<td align="left" class="zenka">'.$x["Zrasa"].'</td>
		  </tr>
		  <tr>
			<td align="left" class="muzjak">'.$x["Mime"].'</td>
			<td class="izmeduMiZ">&nbsp;</td>
			<td align="left" class="zenka">'.$x["Zime"].'</td>
		  </tr>
		</table>
		';
		
		echo '<br>';
		
		
		//PRIKUPI SVA LEGLA I NJIHOVE PODATKE IZ mg_leglo...
		$r=mysqli_query($mysqli,"SELECT DATE_FORMAT($mg_leglo.firstegg,'%d.%m.%Y') AS prvojaje, 
								DATE_FORMAT($mg_leglo.hatchingdate,'%d.%m.%Y') AS datumlezenja, 
								GROUP_CONCAT($mg_leglo.ringnumber SEPARATOR ',') AS brojprstena,
								GROUP_CONCAT($mg_leglo.color SEPARATOR ',') AS boja, 
								GROUP_CONCAT(mg_pigeon_country.country SEPARATOR '<br>') AS drzava
								FROM $mg_leglo
								JOIN mg_pigeon_country ON (mg_pigeon_country.ID=$mg_leglo.IDcountry)
								WHERE $mg_leglo.IDcouple='".$IDpara[$i]."' AND $mg_leglo.IDuser='$ID_KORISNIK'
								GROUP BY $mg_leglo.IDD
								HAVING COUNT($mg_leglo.IDD)>=2
								ORDER BY $mg_leglo.firstegg ASC");
		
		//TABELA ZA POPIS LEGALA PARA
		echo '
		<table border="1" class="table_legla" cellpadding="0" cellspacing="0" align="center">
		  <tr>
			<td align="center" class="legla_cell">'.$GLOBALS['PDF_DLEZENJA_1JAJE'].'</td>
			<td align="center" class="legla_cell">'.$GLOBALS['PDF_DLEZENJA_DATUMLEZENJA'].'</td>
			<td align="center" class="legla_cell">'.$GLOBALS['PDF_DLEZENJA_DRZAVA'].'</td>
			<td align="center" class="legla_cell">'.$GLOBALS['PDF_DLEZENJA_BROJPRSTENA'].'</td>
			<td align="center" class="legla_cell">'.$GLOBALS['PDF_DLEZENJA_BOJA'].'</td>
		  </tr>';
		while($x=mysqli_fetch_assoc($r))
		{
			$ringnumber_color_format=function($x)
			{
				//for example $x=125-6,369-58
				$x=explode(',',$x);
				$array=[];
				foreach($x as $key=>$value)
				{
					 $array[]=(empty($value)) ? 'X' : $value;
				}
				
				return implode("<br>", $array);
			};
			
			 echo'
			  <tr>
				<td align="center">'.$x["prvojaje"].'</td>
				<td align="center">'.$x["datumlezenja"].'</td>
				<td align="center">'.$x["drzava"].'</td>
				<td align="center">'.$ringnumber_color_format($x["brojprstena"]).'</td>
				<td align="center">'.$ringnumber_color_format($x["boja"]).'</td>
			  </tr>
			';
		}
		echo '</table>';

		echo '<br>';
	}
}
?>