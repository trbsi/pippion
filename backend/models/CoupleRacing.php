<?php

namespace backend\models;

use Yii;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\data\ActiveDataProvider;
use yii\helpers\ArrayHelper;
use backend\models\BroodRacing;
use backend\models\BroodBreeding;
use backend\models\CoupleBreeding;

/**
 * This is the model class for table "{{%couple_racing}}".
 *
 * @property integer $ID
 * @property integer $IDuser
 * @property integer $male
 * @property integer $female
 * @property string $couplenumber
 * @property string $year
 *
 * @property BroodRacing[] $broodRacings
 * @property User $iDuser
 * @property Pigeon $female0
 * @property Pigeon $male0
 */
class CoupleRacing extends \yii\db\ActiveRecord
{
	const CoupleRacingName="CoupleRacing";
	const CoupleBreedingName="CoupleBreeding";
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%couple_racing}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['IDuser', 'male', 'female', 'couplenumber', 'year'], 'required'],
            [['IDuser', 'male', 'female'], 'integer'],
            [['year'], 'safe'],
            [['couplenumber'], 'string', 'max' => 20]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'ID' => Yii::t('default', 'ID'),
            'IDuser' => Yii::t('default', 'Iduser'),
            'male' => Yii::t('default', 'Male'),
            'female' => Yii::t('default', 'Female'),
            'couplenumber' => Yii::t('default', 'PAR_NATJEC_ATTR_BROJ_PARA'),
            'year' => Yii::t('default', 'Year'),
			
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelationBroodRacing()
    {
        return $this->hasMany(BroodRacing::className(), ['IDcouple' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIDuser()
    {
        return $this->hasOne(User::className(), ['id' => 'IDuser']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelationFemale()
    {
        return $this->hasOne(Pigeon::className(), ['ID' => 'female']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelationMale()
    {
        return $this->hasOne(Pigeon::className(), ['ID' => 'male']);
    }
	
	
	/*
	* creates year input field, dropdownlist and button. When user clicks on button it will list all couples from that year in dropdownlist
	* it will be done via ajax
	* $_MODEL_CHOOSE - (BroodRacing or BroodBreeding)
	* $fill - when user updates model dropdownlist will be filled with just that one specific couple
			- when user list all broods from specific couple and click on button "Add new brood" via $_GET will be sent just ID of that on couple
			- $fill is ID of couple in my_couple_breeding or mg_couple_racing
	*/
	public function chooseCouplesDropDown($_MODEL_CHOOSE, $fill=NULL)
	{
		//becaue I'm calling this from brood and couple controllers
		if($_MODEL_CHOOSE=="CoupleRacing" || $_MODEL_CHOOSE=="BroodRacing")
		{
			$listcouples_url=Url::to('/couple-racing/list-couples');
			
			if($fill!=NULL)
				$modelCouple = new CoupleRacing;
		}
		else if($_MODEL_CHOOSE=="CoupleBreeding" || $_MODEL_CHOOSE=="BroodBreeding")
		{
			$listcouples_url=Url::to('/couple-breeding/list-couples');
			
			if($fill!=NULL)
				$modelCouple = new CoupleBreeding;
		}
		
		$dropdown_fill=[];
		if($fill!=NULL)
		{
			$model_temp=$modelCouple->find()->where(['IDuser'=>Yii::$app->user->getId(), 'ID'=>$fill])->with(['relationMale.relationIDcountry', 'relationFemale.relationIDcountry'])->one();
			$content=$this->formatCouple($model_temp);
			$dropdown_fill=[$model_temp->ID=>$content];
		}
		
	
		ob_start();
		?>
		<script>
		$(document).ready(function(e) {
			$('#list_couples_btn').click(function(e)
			{
				e.preventDefault();
				var val=$('input[name="couple_year"]').val();
				var couple=$("#brood-idcouple");
				
				$.ajax(
				{
					type:"POST",
					url:"<?= $listcouples_url ?>",
					data:{couple_year:val}, // in actionListCouples() using as $_POST["couple_year"]
					dataType: 'json',  
					success:function(data) 
					{
						couple.empty();
						$.each(data,function( index ) {
						  couple.append('<option value="'+data[index]["key"]+'">'+data[index]["value"]+'</option>');
						});
						
					},
				});
			});
		});
		</script>
		
        <div class="form-group" style="display:inline-block"> 
            <label class="control-label"><?= Yii::t('default', 'Year'); ?></label>
            <div style="display:inline-block; margin-right:20px; min-width:200px;"> 
                <?= Html::input('number', "couple_year", date("Y"), ['max'=>date("Y")+5, 'min'=>1900,  'class'=>'form-control', ] ); ?>
            </div> 
		</div>
		
        <div class="form-group" style="display:inline-block"> 
            <div style="display:inline-block;">
                <?= Html::submitButton(Yii::t('default', 'List couples'), ['class' => 'btn btn-success btn-small', 'id'=>'list_couples_btn']) ?>
            </div>
		</div>
		
		<div class="form-group">
			<label class="control-label" ><?= Yii::t('default', 'LEGLO_NATJEC_ATTR_PAR') ?></label>
			<?= Html:: dropDownList('IDcouple',  null, $dropdown_fill, ['required'=>true, 'class'=>'form-control', 'id'=>'brood-idcouple'] )?>

		</div>
        <?php
		$return = ob_get_clean();
		return $return;

	}
	
	/*
	* using to format couples like: Couple [1-01] - M/xxx/CRO <==> F/yyy/CRO
	* $model - CoupleBreeding or CoupleRacing
	* $relation - if relation isset (BroodRacing or BroodBreeding) that means I sent $model of BroodBreeding(BroodRacing) so I need to collect data differently
	* call - $content=new BroodRacing(BroodBreeding)->formatCouple($model);
	*/
	public static function formatCouple($model, $relation=NULL)
	{
		if($relation=="BroodRacing")
		{
			$couplenumber=$model->relationIDcoupleRacing->couplenumber;
			$M_pigeonnumber=$model->relationIDcoupleRacing->relationMale->pigeonnumber;
			$M_country=$model->relationIDcoupleRacing->relationMale->relationIDcountry->country;
			
			$F_pigeonnumber=$model->relationIDcoupleRacing->relationFemale->pigeonnumber;
			$F_country=$model->relationIDcoupleRacing->relationFemale->relationIDcountry->country;
		}
		else if($relation=="BroodBreeding")
		{
			$couplenumber=$model->relationIDcoupleBreeding->couplenumber;
			$M_pigeonnumber=$model->relationIDcoupleBreeding->relationMale->pigeonnumber;
			$M_country=$model->relationIDcoupleBreeding->relationMale->relationIDcountry->country;
			
			$F_pigeonnumber=$model->relationIDcoupleBreeding->relationFemale->pigeonnumber;
			$F_country=$model->relationIDcoupleBreeding->relationFemale->relationIDcountry->country;
		}
		else
		{
			$couplenumber=$model->couplenumber;
			$M_pigeonnumber=$model->relationMale->pigeonnumber;
			$M_country=$model->relationMale->relationIDcountry->country;
			
			$F_pigeonnumber=$model->relationFemale->pigeonnumber;
			$F_country=$model->relationFemale->relationIDcountry->country;
		}
		$content=Yii::t('default', 'LEGLO_UZGOJNI_CREATE_PAR');
		$content.=' ['.$couplenumber.'] - ';
		$content.=Yii::t('default', 'GOLUB_SPOL_M').'/'.$M_pigeonnumber.'/'.$M_country;
		$content.=' <==> ';
		$content.=Yii::t('default', 'GOLUB_SPOL_Z').'/'.$F_pigeonnumber.'/'.$F_country;
		return $content;

	}


	/*
	* List information about male and female of specific couple
	* @param int - $IDcouple is ID in mg_couple_racing/mg_couple_breeding
	* $model - new CoupleRacing/CoupleBreeding
	* $_MODEL_CHOOSE - "CouplaRacing"/"CoupleBreeding"
	*/
	public function hatchingDiaryCoupleDetails($IDcouple, $model, $_MODEL_CHOOSE)
	{
		if($_MODEL_CHOOSE=="CoupleRacing")
		{
			$url=Url::to(['/brood-racing/create', 'idcouple'=>$IDcouple]);
		}
		else
		{
			$url=Url::to(['/brood-breeding/create', 'idcouple'=>$IDcouple]);
		}
		
		$sql=$model->find()->where(['ID'=>$IDcouple, 'IDuser'=>Yii::$app->user->getId()])->with(['relationMale.relationIDcountry', 'relationFemale.relationIDcountry'])->one();

		$return = '
			<p>
			<strong><h4>'.Yii::t('default', 'LEGLO_UZGOJNI_CREATE_PAR').' ['.$sql->couplenumber.']</h4></strong>
			<table border="0" align="center" class="table table-bordered table-striped">
			  <tr>
				<th style="border-bottom:1px solid #ADADAD; width:50%;" align="left">'.Yii::t('default', 'PAR_UZGOJNI_VIEW_MUZJAK').'</th>
				<th style="border-bottom:1px solid #ADADAD; width:50%;" align="right">'.Yii::t('default', 'PAR_UZGOJNI_VIEW_ZENKA').'</th>
			  </tr>
			  <tr>
				<td align="left">['.$sql->relationMale->relationIDcountry->country.'] - '.$sql->relationMale->pigeonnumber.'</td>
				<td align="right">['.$sql->relationFemale->relationIDcountry->country.'] - '.$sql->relationFemale->pigeonnumber.'</td>
			  </tr>
			  <tr>
				<td align="left">'.$sql->relationMale->color.'</td>
				<td align="right">'.$sql->relationFemale->color.'</td>
			  </tr>
			  <tr>
				<td align="left">'.$sql->relationMale->name.'</td>
				<td align="right">'.$sql->relationFemale->name.'</td>
			  </tr>
			  <tr>
				<td align="left">'.$sql->relationMale->breed.'</td>
				<td align="right">'.$sql->relationFemale->breed.'</td>
			  </tr>
			  <tr>
			    <td colspan="2" align="center">
					'.Html::a(Yii::t('default', 'PAR_UZGOJNI_DNEVNIK_LEZENJA_NOVO_LEGLO'), $url, ['class'=>'btn btn-primary btn-cons', 'target'=>'_blank']).'
				</td>
		      </tr>
			</table>
			</p>
		';
		
		return $return;
	}

	/*
	* return dataProvider for listing broods of specific coupl
	* @param int - $IDcouple - IDcouple in mg_brood_racing/mg_brood_breeding
	* $model - new CoupleRacing/CoupleBreeding
	* $_MODEL_CHOOSE - "CoupleRacing"/"CoupleBreeding"
	*/
	public function broodsOfSpecificCouple($IDcouple,  $model, $_MODEL_CHOOSE)
	{
		$return=NULL;
		if($_MODEL_CHOOSE=="CoupleRacing")
		{
			$broodTable=BroodRacing::getTableSchema();
			$BroodModel=new BroodRacing;

		}
		else if($_MODEL_CHOOSE=="CoupleBreeding")
		{
			$broodTable=BroodBreeding::getTableSchema();
			$BroodModel=new BroodBreeding;
		}
		$pigeonCountryTable=PigeonCountry::getTableSchema();
		
		/*$sql="SELECT DATE_FORMAT($broodTable->name.firstegg,'%d.%m.%Y') AS firstegg,
					DATE_FORMAT($broodTable->name.hatchingdate,'%d.%m.%Y') AS hatchingdate,
					GROUP_CONCAT($broodTable->name.ringnumber SEPARATOR ',') AS ringnumber,
					GROUP_CONCAT($pigeonCountryTable->name.country SEPARATOR '<br>') AS group_concat_country, 
					GROUP_CONCAT($broodTable->name.color SEPARATOR ',') AS color,
					$broodTable->name.ID AS ID
			FROM $broodTable->name
			JOIN $pigeonCountryTable->name ON ($pigeonCountryTable->name.ID=$broodTable->name.IDcountry)
			WHERE $broodTable->name.IDuser=:user AND $broodTable->name.IDcouple=:couple
			GROUP BY $broodTable->name.IDD
			HAVING COUNT($broodTable->name.IDD)>=2
			ORDER BY $broodTable->name.ID ASC";*/
 
		$query=$BroodModel->find()
		->select(["DATE_FORMAT($broodTable->name.firstegg,'%d.%m.%Y') AS firstegg",
					"DATE_FORMAT($broodTable->name.hatchingdate,'%d.%m.%Y') AS hatchingdate",
					"GROUP_CONCAT($broodTable->name.ringnumber SEPARATOR ',') AS ringnumber",
					"GROUP_CONCAT($pigeonCountryTable->name.country SEPARATOR '<br>') AS group_concat_country", 
					"GROUP_CONCAT($broodTable->name.color SEPARATOR ',') AS color",
					"$broodTable->name.ID AS ID"])
		->from($broodTable->name)
		->join('INNER JOIN', $pigeonCountryTable->name,"$pigeonCountryTable->name.ID=$broodTable->name.IDcountry", [] )
		->where("$broodTable->name.IDuser=:user AND $broodTable->name.IDcouple=:couple")
		->addParams([':user'=>Yii::$app->user->getId(), ':couple'=>$IDcouple])
		->groupBy("$broodTable->name.IDD")
		->having("COUNT($broodTable->name.IDD)>=2", [] )
		->orderBy("$broodTable->name.ID ASC")
		;
		
		
		$dataProvider = new ActiveDataProvider([
			'query' => $query,
			'pagination' => [
				'pageSize' => 30,
			],
		]);
		
		return  $dataProvider;
		
	}
	
	/**
	* QUICK-INSERT/CREATE
	* return dropdown list of ALL pigeon couples
	* it is for quick-insert/create
	* $type - BroodRacing or BroodBreeding so you know which couple you have to call (Racing or Breeding)
	*/
	public static function dropDownListAllCouples($type)
	{
		if($type==BroodRacing::BroodRacingName)
		{
			$Couple = new CoupleRacing;
		}
		else if($type==BroodRacing::BroodBreedingName)		
		{
			$Couple = new CoupleBreeding;
		}
		
		$result = $Couple->find()->where(['IDuser'=>Yii::$app->user->getId()])->all();
		
		return  ArrayHelper::map($result, 'ID', function($result)
				{
					return CoupleRacing::formatCouple($result);
				});
	}
	
//END CLASS
}
