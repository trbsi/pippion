<?php

namespace backend\models;

use Yii;
use backend\models\Pigeon;
use backend\models\RacingTableCategory;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\helpers\Html;
/**
 * This is the model class for table "{{%racing_table}}".
 *
 * @property integer $ID
 * @property string $racing_date
 * @property string $place_of_release
 * @property double $distance
 * @property integer $participated_competitors
 * @property integer $participated_pigeons
 * @property integer $won_place
 * @property integer $IDcategory
 * @property integer $IDuser
 * @property integer $IDpigeon
 *
 * @property RacingTableCat $iDcategory
 * @property Pigeon $iDpigeon
 * @property User $iDuser
 */
class RacingTable extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%racing_table}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['racing_date', 'place_of_release', 'distance', 'participated_competitors', 'participated_pigeons', 'won_place', 'IDcategory', 'IDuser', 'IDpigeon'], 'required'],
            [['racing_date'], 'safe'],
            [['distance'], 'number'],
            [['participated_competitors', 'participated_pigeons', 'won_place', 'IDcategory', 'IDuser', 'IDpigeon'], 'integer'],
            [['place_of_release'], 'string', 'max' => 100],
			[['racing_date'], 'date', 'format'=>'yyyy-MM-dd']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => Yii::t('default', 'ID'),
			'racing_date' => Yii::t('default', 'Datum'),
			'place_of_release' => Yii::t('default', 'Mjesto Pustanja'),
			'distance' => Yii::t('default', 'Udaljenost'),
			'participated_competitors' => Yii::t('default', 'Sud Natjecateljsa'),
			'participated_pigeons' => Yii::t('default', 'Sud Golubova'),
			'won_place' => Yii::t('default', 'Osv Mjesto'),
			'IDcategory' => Yii::t('default', 'Idkategorija'),
			'IDuser' => 'Idkorisnik',
			'IDpigeon' => Yii::t('default', 'Idgolub'),

        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelationIDcategory()
    {
        return $this->hasOne(RacingTableCategory::className(), ['ID' => 'IDcategory']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelationIDpigeon()
    {
        return $this->hasOne(Pigeon::className(), ['ID' => 'IDpigeon']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIDuser()
    {
        return $this->hasOne(User::className(), ['id' => 'IDuser']);
    }
	
	/*
	* it returns query so I can generate that little table which cointains total km, placement and average points
	* Prikaže onu malu tablicu koja sadrži podatke Total(kilometres, placements) i Average(points 33%, points 20%) za svakog goluba posebno
	* actionView
	*/
	public function littleTable($target, $pid, $cid)
	{
		$query = RacingTable::find();
		$query->where(['IDuser'=>Yii::$app->user->getId()]);

		if($target=='pigeon')
		{
			$pid=(int)$pid;
			$query->andWhere(['IDpigeon'=>$pid]); 
		}
		else if($target=='category')
		{
			$cid=(int)$cid;
			$query->andWhere(['IDcategory'=>$cid]); 
		}
		else //if($target=='both')
		{
			$pid=(int)$pid;
			$cid=(int)$cid;
			$query->andWhere(['IDcategory'=>$cid, 'IDpigeon'=>$pid]); 
		}
				
		$result=$query->all();
		
		$brojPlasmana=count($result); //Ukupno plasmana to je u biti ukupan broj elemenata u tom polju, tj. ukupan broj recorda iz baze
		$ukupnoKM=0;
		$bodoviDomaciZbroj=0;
		$bodoviStraniZbroj=0;
		$bodoviDomaci33=array();
		$bodoviStrani20=array();
		$i=0;
		//Ukupno km računam samo tako da zbrajam udaljenosti svakog uzetog goluba iz baze
		/*
			//izračunavanje Prosjeka bodova i osvojenih boidova (33% i 20%)
			//Izračun se vrši za svaki red posebno
			1.Izračunat sve bodove 33% i 20%
				Izračun se vrši za svaki red posebno
				Vrjednosti za stupac bodova 33% i bodova 20% se računaju ovako:
				a) po formuli: (broj ukupno plasiranih golubova-osvojeno mjesto+1)*100 / (broj ukupno plasiranih golubova)
				b) broj ukupno plasiranih golubova = Sudjelovalo Golubova * 33% i za strano *20%
			2. spremit sve te izračunate bodove u array, posebno za stupac  bodova 33% i bodova 20%
			3. uzet iz array, zbrojit posebno stupac  bodova 33% i bodova 20%, podijelit svaki stupac posebno s plasmanom i dobit ću prosjek bodova
		*/
		foreach($result as $value)
		{
			$ukupnoKM+=$value->distance;
			
			//korak 1. a)i b) i korak 2.
			$bodoviDomaci33[$i]=$this->calculationHomePoints33($value);//domaći bodovi, to je stupac  Osvojeno bodova 33%
			$bodoviStrani20[$i]=$this->calculationForeignPoints20($value);//strani boodovi, to je stupac Osvojeno bodova 20%
			$i++;
		}
		
		//korak 3. zbrajanje bodova da se kasnije mogu podijelit s plasmanom
		foreach($bodoviDomaci33 as $value)
		{
			$bodoviDomaciZbroj+=$value;
		}
		//korak 3.
		foreach($bodoviStrani20 as $value)
		{
			$bodoviStraniZbroj+=$value;
		}
		
		$prosjekBodovaDomaci=$bodoviDomaciZbroj/$i;
		$prosjekBodovaStrani=$bodoviStraniZbroj/$i;
		
		$return= '
		<table class="table table-bordered table-striped">
		<thead>
          <tr>
            <td colspan="2" align="center" class="create-participated">'.Yii::t('default', 'RTUkupno').'</td>
            <td colspan="2" align="center" class="create-won">'.Yii::t('default', 'RTProsjek').'</td>
          </tr>
          <tr>
            <td align="center" class="create-participated">'.Yii::t('default', 'RTkilometara').'</td>
            <td align="center" class="create-participated">'.Yii::t('default', 'RTplasmana').'</td>
            <td align="center" class="create-won">'.Yii::t('default', 'RTbodova33').'</td>
            <td align="center" class="create-won">'.Yii::t('default', 'RTbodova20').'</td>
          </tr>
		</thead>
          <tr class>
            <td align="center">'.$ukupnoKM.'</td>
            <td align="center">'.$brojPlasmana.'</td>
            <td align="center">'.round($prosjekBodovaDomaci,3).'</td>
            <td align="center">'.round($prosjekBodovaStrani,3).'</td>
          </tr>
        </table>
		';
		
		return $return;
	}


	/*
	* s ove dvije funkcije izračunavam pomoću formule koliko je osvojio bodova (33%) i (20%) za svaki red.
	* radi se o stupcu Osvojeno bodova 33% i sovojeno bodova 20%
	* treba mi u sva 3 slučaja: _view_cat, _view_cat_golub, _view_golub
			1.Izračunat sve bodove 33% i 20%
			Izračun se vrši za svaki red posebno
			Vrjednosti za stupac bodova 33% i bodova 20% se računaju ovako:
			a) po formuli: (broj ukupno plasiranih golubova-osvojeno mjesto+1)*100 / (broj ukupno plasiranih golubova)
			b) broj ukupno plasiranih golubova = Sudjelovalo Golubova * 33% i za strano *22%

	* return izračunate bodove (33% ili 20%) za svaki red posebno
	* INFO https://mail.google.com/mail/u/0/#search/broj+ukupno+plasiranih/141e40d7a36e127f
	*/
	public function calculationHomePoints33($data)
	{
		$brojUkupnoPlasiranih=round($data->participated_pigeons*(33.33/100));
		$x=( ($brojUkupnoPlasiranih-$data->won_place+1)*100 ) / ($brojUkupnoPlasiranih); //$x je Osvojeno bodova 33%
		$x=round($x,3);
		return $x;
	}
	
	//calculate points 20%
	public function calculationForeignPoints20($data)
	{
		$brojUkupnoPlasiranih=round($data->participated_pigeons*(20/100));
		$x=( ($brojUkupnoPlasiranih-$data->won_place+1)*100 ) / ($brojUkupnoPlasiranih); //$x je Osvojeno bodova 20%
		
		//jer bude moguće da ti bodovi budu u minusu jer taj golub nije unutar 20% pa kad se oduzme ukupno plasirano i njegovo osvojeno mjesto, onda x bude u minusu
		if($x<0)
			$x=0;
		else
			$x=round($x,3);	
			
		return $x;
	}
	


	/*
	* returns title depedning if only pigeon, category or pigeon+category are chosen
	* this title is used to show as <h2> in view.php
	*/
	public function racingTableH2($target,$pid,$cid)
	{
		$return=NULL;
		if($target=='pigeon')
		{
			//get relation of category and relation of pigeon
			$Pigeon=Pigeon::findOne($pid);
						
			if($Pigeon->sex=='X')
				$sex=Yii::t('default', 'GOLUB_SPOL_M');
			else if($Pigeon->sex=='Y')
				$sex=Yii::t('default', 'GOLUB_SPOL_Z');
			else
				$sex="?";
				
			$return.="[".$Pigeon->relationIDcountry->country."] ".$Pigeon->pigeonnumber." ".$sex;

		}
		else if($target=='category')
		{
			//get relation of category and relation of pigeon
			$RacingTableCategory=RacingTableCategory::findOne($cid);
			$return.=Yii::t('default', 'Kategorija').': '.$RacingTableCategory->category;

		}
		else //if($target=='both')
		{
			$Pigeon=Pigeon::findOne($pid);
			$RacingTableCategory=RacingTableCategory::findOne($cid);
			
			if($Pigeon->sex=='X')
				$sex=Yii::t('default', 'GOLUB_SPOL_M');
			else if($Pigeon->sex=='Y')
				$sex=Yii::t('default', 'GOLUB_SPOL_Z');
			else
				$sex="?";
			
			$return.=Yii::t('default', 'Kategorija').': '.$RacingTableCategory->category.'<br>';
			
			$return.="[".$Pigeon->relationIDcountry->country."] ".$Pigeon->pigeonnumber." ".$sex;

		}
		
		$return= '<h3>'.$return.'</h3>';

		return $return;

	}
	
	/*
	* depending of racing table category fill all pigeons that belongs to that chosen category
	*/
	public static function dependingOfCategoryDropDownRacingTable()
	{
		$content=NULL;
		ob_start(); ?>
        <script>
		$(document).ready(function(e) 
		{
			$("#racing-table-cat-input").change(function()
			{
				
				var IDcategory=$(this).val();
				$.ajax
				({
					url: "<?= Url::to(['/racing-table/dependant-racing-table']); ?>",
					data:{IDcategory:IDcategory},
					dataType:"json",
					type:"POST",
					success: function(data)
					{
						$("#pigeon-number-input").empty();
						$.each(data,function(index)
						{
							$("#pigeon-number-input").append('<option value="'+data[index]["key"]+'">'+data[index]["value"]+'</option>');
						});
					}	
				});
			
			});
		});
		</script>
		
        <?php
		$content.=ob_get_clean();
		$content.=Html::beginForm(Url::to(['/statistics/table-racing']), 'get', [] ) ;
		$content.=Yii::t('default', 'Category');
		$content.=Html::dropDownList('racing_table_cat', null, RacingTableCategory::dropDownListCategory(), ['class'=>'form-control', 'id'=>'racing-table-cat-input', 'prompt'=>''] );
		$content.="<br>";
		$content.=Yii::t('default', 'Pigeon number') ;
		$content.=Html::dropDownList('pigeon_number', null, [], ['class'=>'form-control', 'id'=>'pigeon-number-input', 'required'=>true] ); 
		$content.="<br>";
		$content.=Html::submitButton(Yii::t('default','Submit'), ['class'=>'btn btn-cons btn-primary'] ) ;
		$content.=Html::endForm() ;
		
		return $content;

	}

	
	
}//END CLASS
