<?php

namespace backend\models;

use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use backend\models\PigeonCountry;
use backend\models\Status;

/**
 * This is the model class for table "{{%pigeon}}".
 *
 * @property integer $ID
 * @property integer $IDuser
 * @property string $pigeonnumber
 * @property string $sex
 * @property string $color
 * @property string $breed
 * @property string $name
 * @property string $year
 * @property integer $IDcountry
 * @property integer $IDstatus
 *
 * @property CoupleBreeding[] $coupleBreedings
 * @property CoupleRacing[] $coupleRacings
 * @property PigeonCountry $iDcountry
 * @property Status $iDstatus
 * @property User $iDuser
 * @property PigeonData[] $pigeonDatas
 * @property PigeonList[] $pigeonLists
 * @property RacingTable[] $racingTables
 */
class Pigeon extends \yii\db\ActiveRecord
{
	
	const MALE_PIGEON="X";
	const FEMALE_PIGEON="Y";
	const UNKNOWN_SEX_PIGEON="?";
	const MAX_IMAGE_SIZE_PIGEON_EYE_IMAGE=5000000;
	
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%pigeon}}';
    }

	
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['IDuser', 'pigeonnumber', 'year', 'IDcountry', 'IDstatus'], 'required'],
            [['IDuser', 'IDcountry', 'IDstatus'], 'integer'],
            [['year'], 'safe'],
            [['pigeonnumber', 'color'], 'string', 'max' => 40],
            [['breed', 'name'], 'string', 'max' => 50],
            [['sex'], 'string', 'max' => 5],
			[['pigeon_image', 'eye_image'], 'string', 'max' => 150],
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
            'pigeonnumber' => Yii::t('default', 'GOLUB_BROJ_GOLUBA'),
            'sex' => Yii::t('default', 'GOLUB_SPOL'),
            'color' => Yii::t('default', 'GOLUB_BOJA'),
            'breed' => Yii::t('default', 'GOLUB_RASA'),
            'name' => Yii::t('default', 'GOLUB_IME'),
            'year' => Yii::t('default', 'GOLUB_GODINA'),
            'IDcountry' => Yii::t('default', 'GOLUB_DRZAVA'),
            'IDstatus' => Yii::t('default', 'GOLUB_STATUS'),
			'pigeon_image' => Yii::t('default', 'Pigeon Image'),
            'eye_image' => Yii::t('default', 'Eye Image'),
        ];
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelationPigeonListIDfather()
    {
		//https://github.com/yiisoft/yii2/issues/2377
        return $this->hasMany(PigeonList::className(), ['IDfather' => 'ID'])->from(['pigeon_list_IDfather' => PigeonList::tableName()]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelationPigeonListIDmother()
    {
		//https://github.com/yiisoft/yii2/issues/2377
        return $this->hasMany(PigeonList::className(), ['IDmother' => 'ID'])->from(['pigeon_list_IDmother' => PigeonList::tableName()]);
    }
	
	 /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelationPigeonListIDpigeon()
    {
        return $this->hasMany(PigeonList::className(), ['IDpigeon' => 'ID'])->from(['pigeon_list_IDpigeon' => PigeonList::tableName()]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCoupleBreedings()
    {
        return $this->hasMany(CoupleBreeding::className(), ['male' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCoupleRacings()
    {
        return $this->hasMany(CoupleRacing::className(), ['male' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelationIDcountry()
    {
        return $this->hasOne(PigeonCountry::className(), ['ID' => 'IDcountry']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelationIDstatus()
    {
        return $this->hasOne(Status::className(), ['ID' => 'IDstatus']);
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
    public function getPigeonDatas()
    {
        return $this->hasMany(PigeonData::className(), ['IDpigeon' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPigeonLists()
    {
        return $this->hasMany(PigeonList::className(), ['IDpigeon' => 'ID']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRacingTables()
    {
        return $this->hasMany(RacingTable::className(), ['IDpigeon' => 'ID']);
    }
	
	
	
	/*
	* return dropdownlist of all pigeon countries
	*/
	public static function dropDownListPigeonCountry()
	{
		$countries=PigeonCountry::find()->orderBy('country ASC')->all();
		return ArrayHelper::map($countries, 'ID', 'country');
	}
	
	/*
	* DROPDOWN for sex -> X/Y
	* @return array - sex X/Y=M/Ž or ? if they don't know sex
	*/
	public static function dropDownListSex()
	{
		$array = [
			['key'=>'?', 'value'=>'?'],
			['key'=>'X', 'value'=>Yii::t('default', 'GOLUB_SPOL_M')],
			['key'=>'Y', 'value'=>Yii::t('default', 'GOLUB_SPOL_Z')],
		];
		
		return ArrayHelper::map($array, 'key', 'value');
	}
	
	/*
	* return proper value for sex when showing/returning from X,Y or ?
	*/
	public static function getSex($value)
	{
		if($value=="X")
			return Yii::t('default', 'GOLUB_SPOL_M');
		else if($value=="Y")
			return Yii::t('default', 'GOLUB_SPOL_Z');
		else
			return '?';
	}
	/*
	* return statuses for dropdownlist
	*/
	public static function dropDownListStatus()
	{
		$countries=Status::find()->where(['IDuser'=>Yii::$app->user->getId()])->all();
		return ArrayHelper::map($countries, 'ID', 'status');
	}

	/*
	* Get pigeon's father and mother
	* @param int - ID from mg_pigeon, id of pigeon
	* @return string - number of mother or father
	*/
	public static function getParents($IDpigeon, $who)
	{
		//first find in pigeon list that one specific pigeon. 
		$return=PigeonList::find()->where(["IDpigeon"=>$IDpigeon])->one();
		
		if($who=="X")
			$pigeon=$return->relationIDfather;
		else
			$pigeon=$return->relationIDmother;
			
		//AKO USPIJE NEŠTO NAĆI, TJ. AKO NIJE PRAZAN BROJGOLUBA TADA NJEGA RETURN, U SUPROTNOM CRTICU
		if(!empty($pigeon->pigeonnumber))
		{
			$data["pigeonnumber"]="[".$pigeon->relationIDcountry->country."/".$pigeon->year."] ".$pigeon->pigeonnumber;
			$data["IDparent"]=$pigeon->ID;
		}
		else
		{
			$data["pigeonnumber"]="0";
			$data["IDparent"]=$pigeon->ID;
		}
		return $data;
	}
	
	/*
	* return dependent dropdown list for father (country and pigeon number dropdown field)
	* also return javascript code for filling dropdown list
	* this $id is for collecting all male/female pigeons except that specific one, because he/she cannot be father/mother to itself
	* it doesn't count for actionCreate, only for actionUpdate
	* sometimes I need to fill dropdown just with one value, for example in couple-racing/update - here I use $fill
	* $fill is ID for mg_pigeon
	* HOW TO USE
		just call this function to generate dropdownlist for male pigeons
	*/
	public function dependentDropDownFather($id=0, $fill=NULL)
	{
		
		$return=NULL;
		$dropDownFill=[];
		if($fill!=NULL)
		{
			$result=Pigeon::find()->where(['IDuser'=>Yii::$app->user->getId(), 'ID'=>$fill])->one();
			$dropDownFill=[$result->ID=>$result->pigeonnumber];
		}
		
		ob_start();
		?>
			<script>
			$(document).ready(function(e) {
				
				var Father_ID=$("#Father_ID");
				//ajax request to fill dropdownlist when someone wants to add existing father
				$("#dependentCountryFather").change(function()
				{
					$.ajax
					({
						dataType:'json',
						url:'<?= Url::to('/pigeon/ajax-dependent-parents')?>?id=<?= $id ?>&sex=X&dependentCountryFather='+$(this).val()+'', //OVAJ $id šaljem KAKO BI POKUPIO SVE GOLUBOVE OSIM TOG JEDNON (NOT LIKE ID) JER NE MOŽE SAM SEBI BITI MAJKA/OTAC, no za create action to ne vrijedi
						type:"GET",
						success:function(data)
						{
							Father_ID.empty();
							$.each(data, function(index, value)
							{
								Father_ID.append('<option value="'+data[index]["id"]+'">'+data[index]["pigeonnumber"]+'</option>');
							});
							
						}
					});	
				});
			
			});
			</script>

		  <div class="form-group">
          <?= Html::label(Yii::t('default', 'GOLUB_UPDATE_COUNTRY'),'dependentCountryFather', ['class'=>'form-label']) ?>
			  <?= Html::dropDownList('dependentCountryFather',null, $this->dropDownListPigeonCountry(),
					 [	
						'prompt'=>Yii::t('default', 'GOLUB_UPDATE_SELECT_COUNTRY'),
						'class'=>'form-control',
						'id'=>'dependentCountryFather',
					]
				)?>
		  </div>
		  <div class="form-group">
			<?= Html::label(Html::encode(Yii::t('default', 'GOLUB_BROJ_GOLUBA')), null, ['class'=>'form-label'])?>
			<?= Html::dropDownList('Father_ID', null, $dropDownFill, ['id'=>'Father_ID', 'class'=>'form-control'])?>
		  </div>
		<?php
		$return=ob_get_clean();
		return $return;
	}

	/*
	* return dependent dropdown list for mother (country and pigeon number dropdown field)
	* also return javascript code for filling dropdown list
	* this $id is for collecting all male/female pigeons except that specific one, because he/she cannot be father/mother to itself
	* it doesn't count for actionCreate, only for actionUpdate
	* sometimes I need to fill dropdown just with one value, for example in couple-racing/update - here I use $fill
	* $fill is ID for mg_pigeon
¸	*/
	public function dependentDropDownMother($id=0, $fill=NULL)
	{
		$return=NULL;
		$dropDownFill=[];
		if($fill!=NULL)
		{
			$result=Pigeon::find()->where(['IDuser'=>Yii::$app->user->getId(), 'ID'=>$fill])->one();
			$dropDownFill=[$result->ID=>$result->pigeonnumber];
		}

		ob_start();
		?>
		<script>
		$(document).ready(function(e) {
			
			var Mother_ID=$("#Mother_ID");
			//ajax request to fill dropdownlist when someone wants to add existing mother
			$("#dependentCountryMother").change(function()
			{
				$.ajax
				({
					dataType:'json',
					url:'<?= Url::to('/pigeon/ajax-dependent-parents')?>?id=<?= $id ?>&sex=Y&dependentCountryMother='+$(this).val()+'', //OVAJ id=>0 šaljem KAKO BI POKUPIO SVE GOLUBOVE OSIM TOG JEDNON (NOT LIKE ID) JER NE MOŽE SAM SEBI BITI MAJKA/OTAC, no za actionCreate to ne vrijedi
					type:"GET",
					success:function(data)
					{
						Mother_ID.empty();
						$.each(data, function(index, value)
						{
							Mother_ID.append('<option value="'+data[index]["id"]+'">'+data[index]["pigeonnumber"]+'</option>');
						});
						
					}
				});	
			});
		
		});
		</script>
              <div class="form-group">
			  	<?= Html::label(Yii::t('default', 'GOLUB_UPDATE_COUNTRY'),'dependentDrzavaMajka', ['class'=>'form-label']) ?>
                <?= Html::dropDownList('dependentCountryMother','', $this->dropDownListPigeonCountry(),
                                [
									'prompt'=>Yii::t('default', 'GOLUB_UPDATE_SELECT_COUNTRY'),
									'class'=>'form-control',
									'id'=>'dependentCountryMother'
                                ]
                            ) ?>
              </div>
              <div class="form-group">
                <?= Html::label(Html::encode(Yii::t('default', 'GOLUB_BROJ_GOLUBA')), null, ['class'=>'form-label'])?>
                <?= Html::dropDownList('Mother_ID', null, $dropDownFill, ['id'=>'Mother_ID', 'class'=>'form-control'])?>
              </div>
		<?php
		$return = ob_get_clean();
		return $return;
	}
	
	
	/*
	* When user has to choose between male and female pigeon for dropdownlist
	* This is jquery for hidding or showing dropdownlist for male or female pigeons
	* HOW TO USE:
	1. call this function (to show radio buttons)
	2. put this code anywhere you want
	 <div style="display:none" id="div-male">
		<?php
		echo $Pigeon->dependentDropDownFather();
		?>
	  </div>
	  <div style="display:none" id="div-female">
		<?php
		echo $Pigeon->dependentDropDownMother();
		?>
	  </div>
	* I cannot put this html code with div insite function dependentDropDownFather()/dependentDropDownMother() because somewhere I need put that dropdownlist for male and female pigeons to be visible
	*/
	
	public static function maleFemaleDropDownChoose()
	{
		$return=NULL;
		ob_start();
		?>
		<script>
			$(document).ready(function(e) 
			{
				var Father_ID=$("#Father_ID");
				var Mother_ID=$("#Mother_ID");
				
				var div_male=$("#div-male");
				var div_female=$("#div-female");
				var malefemale=$('input[name="malefemale"]');
				
				//show male pigeon at the very beginning
				malefemale.first().click();
				div_male.show()
				Father_ID.prop("required",true);
				
				malefemale.click(function()
				{	
					var val=$(this).val();					
					if(val=="male")
					{
						div_male.show();
						Father_ID.prop("required",true);
						
						div_female.hide();
						Mother_ID.prop("required",false);
					}
					else
					{
						div_male.hide();
						Father_ID.prop("required",false);
						
						div_female.show();
						Mother_ID.prop("required",true);
					}
				});
			});
		</script>
			<div class="radio radio-primary">
				<input id="radio-male" type="radio" name="malefemale" value="male">
					<label for="radio-male">
					<?= Yii::t('default', 'Male')?>
				</label>
			<input id="radio-female" type="radio" name="malefemale" value="female">
				<label for="radio-female">
					<?= Yii::t('default', 'Female')?>
				</label>
			</div>

		<?php
		$return=ob_get_clean();
		return $return;
	}
	
	/*
	* Get dropdown list of only male or only female pigeons, I'm using it currently in quick-insert/create for creating couples of pigeons
	* $which = "male" or "female"
	*/
	public static function dropDownListOnlyMaleOnlyFemale($which)
	{
		if($which==Pigeon::MALE_PIGEON)
			$sex=Pigeon::MALE_PIGEON;
		else if($which==Pigeon::FEMALE_PIGEON)
			$sex=Pigeon::FEMALE_PIGEON;
		
		$pigeons=Pigeon::find()->where(['IDuser'=>Yii::$app->user->getId(), 'sex'=>$sex])->all();
		return ArrayHelper::map($pigeons, 'ID', function($pigeons)
			{
				return "[".$pigeons->relationIDcountry->country."/".$pigeons->year."] ".$pigeons->pigeonnumber; 
			});
	}
	
	/*
	* If user wants to print or download pedigree, hatching diary or racing table
	* HOW TO USE:
		just call this function to show those 2 buttons and later you just use its name and value to see if user wants to download or print
	*/
	public static function printDownloadRadioChoose()
	{
		$return=
		'
		<div class="radio radio-primary">
		  <input id="radio3" type="radio" name="printdownload" value="print">
		  <label for="radio3">
			'.Yii::t('default', 'Print').'
		  </label>
		  <input id="radio4" type="radio" name="printdownload" value="download" checked>
		  <label for="radio4">
			'.Yii::t('default', 'Download').'
		  </label>
		  <input id="radio5" type="radio" name="printdownload" value="view">
		  <label for="radio5">
			'.Yii::t('default', 'View').'
		  </label>
		</div>
		';
		
		return $return;
	}
	
	/*
	* return path to folder where pigeon image and eye are located
	* $path = Yii::getAlias('@web') or Yii::getAlias('@webroot')
	*/
	public static function returnPathToPigeonImageEye($path=NULL, $IDuser)
	{
		if($path==NULL)
			return "/files/pigeons/".$IDuser."/";
		else
			return $path."/files/pigeons/".$IDuser."/";
	}

}
