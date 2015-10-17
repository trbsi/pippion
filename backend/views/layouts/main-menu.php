<?php
use yii\widgets\Menu;
use backend\modules\club\models\ClubTables;
use backend\modules\club\models\ClubResults;

//check current url and return true or false so that you know if menu should be active or not
function checkUrl($array)
{
	$REQUEST_URI=$_SERVER['REQUEST_URI'];
	$return=false;
	foreach($array as $key=>$value)
	{
		if(strpos($REQUEST_URI,$value))
		{
			$return=true;
			break;
		}
	}
	return $return;
}

$module=\Yii::$app->controller->module->id;//current running module
$clubModule = \Yii::$app->getModule('club')->id;
$actionParams=\Yii::$app->controller->actionParams;

if($module==$clubModule || isset($actionParams["club_page"]))
{
	$club_page=strtolower($actionParams["club_page"]);
	echo Menu::widget([
			'encodeLabels'=>false,
			'submenuTemplate'=>'<ul class="sub-menu">{items}</ul>',
			'activateParents'=>true,
			'activateItems'=>true,
			'activeCssClass'=>'active open',
			'items' => [
				// Important: you need to specify url as 'controller/action',
				// not just as 'controller' even if default action is used.
				['label' => '<i class="fa fa-arrow-left"></i><span class="title">Pippion</span>', 'url' => ['/index.php'], ],
				//**************************************************************************************************
				['label' => '<i class="icon-custom-home"></i><span class="title">'.Yii::t('default', 'Club').'</span>', 'url' => ['/club/club/view', 'club_page'=>$club_page], ],
				//**************************************************************************************************
				['label' => '<i class="fa fa-male"></i><span class="title">'.Yii::t('default', 'Members').'</span>', 'url' => ['/club/club/members', 'club_page'=>$club_page], ],
				//**************************************************************************************************
				['label' => '<i class="fa fa-table"></i><span class="title">'.Yii::t('default', 'Results').'</span> <span class="arrow"></span><span class="label label-important pull-right ">', 'url' => 'javascript:;', 'items' => 
					[
						[
							'label' =>  Yii::t('default', 'Races') , 'url' => ['/club/club-results/index', 'club_page'=>$club_page, 'ClubResultsSearch[result_type]'=>ClubResults::RESULT_TYPE_OTHER], 
						],
						[
							'label' =>  Yii::t('default', 'Tables') , 'url' => ['/club/club-tables/index', 'club_page'=>$club_page,'type'=>ClubTables::RESULT_TYPE_TEAM,], 
						],
						[
							'label' =>  Yii::t('default', 'Pigeon tables') , 'url' => ['/club/club-tables/index', 'club_page'=>$club_page, 'type'=>ClubTables::RESULT_TYPE_PIGEON], 
						],
						[
							'label' =>  Yii::t('default', 'Short distances') , 'url' => ['/club/club-results/index', 'club_page'=>$club_page, 'ClubResultsSearch[result_type]'=>ClubResults::RESULT_TYPE_SHORT], 
						],
						[
							'label' =>  Yii::t('default', 'Medium distances') , 'url' => ['/club/club-results/index', 'club_page'=>$club_page, 'ClubResultsSearch[result_type]'=>ClubResults::RESULT_TYPE_MEDIUM], 
						],
						[
							'label' =>  Yii::t('default', 'Long distances') , 'url' => ['/club/club-results/index', 'club_page'=>$club_page, 'ClubResultsSearch[result_type]'=>ClubResults::RESULT_TYPE_LONG], 
						],
						[
							'label' =>  Yii::t('default', 'Marathon') , 'url' => ['/club/club-results/index', 'club_page'=>$club_page, 'ClubResultsSearch[result_type]'=>ClubResults::RESULT_TYPE_MARATHON], 
						],
						['label' =>  Yii::t('default', 'Add result') , 'url' => ['/club/club-results/create', 'club_page'=>$club_page], ],
						['label' =>  Yii::t('default', 'Add table') , 'url' => ['/club/club-tables/create', 'club_page'=>$club_page], ],
					],
					'active'=>checkUrl(['club-tables', 'club-results']),
				],

				
				//**************************************************************************************************
				[	
					'label' => '<i class="fa fa-file-image-o"></i><span class="title">'.Yii::t('default', 'Gallery').'</span>', 
					'url' => ['/pigeon-image/album/index', 'club_page'=>$club_page], 
					'active'=>checkUrl(['pigeon-image/']),
				],
				//************************************************************************************************
				['label' => '<i class="fa fa-sun-o"></i><span class="title">'.Yii::t('default', 'Weather').'</span>', 'url' => ['/club/club/weather', 'club_page'=>$club_page], ],
				//**************************************************************************************************
				['label' => '<i class="fa fa-group"></i> <span class="title">'. Yii::t('default', 'Clubs').'</span> <span class="arrow"></span><span class="label label-important pull-right ">'.Yii::t('default', 'New').'</span>', 'url' => 'javascript:;', 'items' => 
					[
						['label' =>  Yii::t('default', 'Search clubs') , 'url' => ['/club/club/index', 'club_page'=>$club_page], ],
						['label' =>  Yii::t('default', 'Create Club') , 'url' => ['/club/club/create', 'club_page'=>$club_page], ],
					]
				],
				//**************************************************************************************************

			],
		]);	
}
else
{
	echo Menu::widget([
			'encodeLabels'=>false,
			'submenuTemplate'=>'<ul class="sub-menu">{items}</ul>',
			'activateParents'=>true,
			'activateItems'=>true,
			'activeCssClass'=>'active open',
			'items' => [
				// Important: you need to specify url as 'controller/action',
				// not just as 'controller' even if default action is used.
				['label' => '<i class="icon-custom-home"></i><span class="title">Home</span>', 'url' => ['/index.php'], ],
				//**************************************************************************************************
				['label' => '<i class="fa fa-rss"></i><span class="title">Blog</span>', 'url' => ['/pigeonblog'],  ],
				//**************************************************************************************************
				[
				'label' => '<i class="fa fa-envelope"></i><span class="title">'.Yii::t('default', 'Messages').'</span><span class="badge badge-important pull-right js-msgs-badge"></span>', 
				'url' => ['/messages/messages/inbox'], 
				],
				//**************************************************************************************************
				[
				'label' => '<i class="fa fa-legal"></i> <span class="title">'. Yii::t('default', 'Auctions').'</span> <span class="arrow"></span>', 
				'url' => 'javascript:;', 
				'items' => 
					[
						
						['label' => Yii::t('default', 'Sell pigeon'), 'url' => ['/auction/create'], ],
						['label' => Yii::t('default', 'Search opened auctions'), 'url' => ['/auction/opened'], ],
						['label' => Yii::t('default', 'Search closed auctions'), 'url' => ['/auction/closed'], ],
						['label' => Yii::t('default', 'My auctions'), 'url' => ['/auction/index'], ],
						['label' => Yii::t('default', 'My ratings menu'), 'url' => ['/auction-rating/index'], ],
						['label' => Yii::t('default', 'Auction rules'), 'url' => ['/auction/rules'], ],
						
					],
				'active'=>checkUrl(['auction', 'auction-rating']),	
				],
				
				//**************************************************************************************************
				[
				'label' => '<i class="fa fa-credit-card"></i> <span class="title">'. Yii::t('default', 'Account balance').'</span> <span class="arrow"></span>', 
				'url' => 'javascript:;', 
				'items' => 
					[
						
						['label' => Yii::t('default', 'Account balance'), 'url' => ['/account-balance/balance'], ],
						['label' => Yii::t('default', 'My gateways'), 'url' => ['/payment-gateway/index'], ],
						['label' => Yii::t('default', 'Add Payment Gateway'), 'url' => ['/payment-gateway/create'], ],
						
					],
				'active'=>checkUrl(['account-balance', 'payment-gateway']),	
				],
				
				//**************************************************************************************************
				['label' => '<i class="fa fa-cog"></i> <span class="title">'.Yii::t('default', 'Other').'</span><span class="arrow"></span>', 
				'url' => 'javascript:;', 
				'items' => 
					[
						[
							'label' => '<i class="fa fa-magic"></i><span class="title">'.Yii::t('default', 'Pigeon Insider Maker').'</span>', 
							'url' => ['/pigeon-insider/create'], 
						],
						[
							'label' => '<i class="fa fa-download"></i><span class="title">'.Yii::t('default', 'Import from Pigeon Planner').'</span>', 
							'url' => ['/import/pigeon-planner'],
						 ],
						[
							'label' => '<i class="fa fa-bolt"></i><span class="title">'.Yii::t('default', 'Quick Insert').'</span>', 
							'url' => ['/quick-insert/create'], 
						],
						[
							'label' => '<i class="fa fa-question-circle"></i><span class="title">'.Yii::t('default', 'Tutorials').'</span>', 
							'url' => ['/site/tutorials'], 
						],
					],
				'active'=>checkUrl(['pigeon-insider', 'import', 'quick-insert', 'tutorials']),
				],
				//**************************************************************************************************
				[
				'label' => '<i class="fa fa-user"></i> <span class="title">'.Yii::t('default', 'NAV_USER').'</span><span class="arrow"></span>', 
				'url' => 'javascript:;', 
				'items' => 
					[
						['label' => Yii::t('default', 'Subscriptions'), 'url' => ['/subscription/info'], ],
						['label' =>  Yii::t('default', 'Account verification'), 'url' => ['/site/verify-acc'], ],
						['label' => Yii::t('default', 'NAV_PROFILE'), 'url' => ['/user/settings/account'], ],
						['label' => Yii::t('default', 'NAV_UZGAJIVAC'), 'url' => ['/breeder/view', 'id'=>Yii::$app->user->getId()], ],
						['label' => Yii::t('default', 'Profile picture').'<span class="label label-important pull-right ">'.Yii::t('default', 'New').'</span>', 'url' => ['/breeder/upload-profile-pic'], ],
						['label' => Yii::t('default', 'Search Breeders'), 'url' => ['/breeder/index']],
						['label' => Yii::t('default', 'NAV_UZGAJIVAC_PODACI'), 'url' => ['/breeder-results/index'], ],
					],
				'active'=>checkUrl(['subscription/', '/breeder/', '/breeder-results/', 'user/']),
				],
				//**************************************************************************************************
				[
				'label' => '<i class="fa fa-twitter"></i> <span class="title">'.Yii::t('default', 'NAV_GOLUBOVI').'</span> <span class="arrow"></span>', 
				'url' => 'javascript:;', 
				'items' => 
					[
						
						['label' => Yii::t('default', 'NAV_STATUS'), 'url' => ['/status/index'], ],
						['label' => Yii::t('default', 'NAV_DODAJ_GOLUBA'), 'url' => ['/pigeon/create'], ],
						['label' => Yii::t('default', 'NAV_POPIS_GOLUBOVA'), 'url' => ['/pigeon/index'], ],
						['label' => Yii::t('default', 'Pedigree'), 'url' => ['/pigeon/pedigree'], ],
						['label' => Yii::t('default', 'NAV_PODACI_O_GOLUBU'), 'url' => ['/pigeon-data/index'], ],
					],
				'active'=>checkUrl(['status/', 'pigeon/', 'pigeon-data/']),
				],
				//**************************************************************************************************
				[
				'label' => '<i class="fa fa-truck"></i> <span class="title">'.Yii::t('default', 'NAV_LEZENJE_UZGOJNI').'</span> <span class="arrow"></span>', 
				'url' => 'javascript:;', 
				'items' => 
					[
						
						['label' => Yii::t('default', 'NAV_DNEVNIK_LEZENJA'), 'url' => ['/couple-breeding/hatching-diary'], ],
						['label' => Yii::t('default', 'NAV_POPIS_PAROVA'), 'url' => ['/couple-breeding/index'], ],
						['label' => Yii::t('default', 'NAV_NOVI_PAR'), 'url' => ['/couple-breeding/create'], ],
						['label' => Yii::t('default', 'NAV_POPIS_MLADIH_GOLUBOVA'), 'url' => ['/brood-breeding/index'], ],
						['label' => Yii::t('default', 'NAV_DODAJ_LEGLO'), 'url' => ['/brood-breeding/create'], ],
					],
				'active'=>checkUrl(['brood-breeding', 'couple-breeding']),
				],
				//**************************************************************************************************
				[
				'label' => '<i class="fa fa-plane"></i> <span class="title">'.Yii::t('default', 'NAV_LEZENJE_NATJEC').'</span> <span class="arrow"></span>', 
				'url' => 'javascript:;', 
				'items' => 
					[
						
						['label' => Yii::t('default', 'NAV_DNEVNIK_LEZENJA'), 'url' => ['/couple-racing/hatching-diary'], ],
						['label' => Yii::t('default', 'NAV_POPIS_PAROVA'), 'url' => ['/couple-racing/index'], ],
						['label' => Yii::t('default', 'NAV_NOVI_PAR'), 'url' => ['/couple-racing/create'], ],
						['label' => Yii::t('default', 'NAV_POPIS_MLADIH_GOLUBOVA'), 'url' => ['/brood-racing/index'], ],
						['label' => Yii::t('default', 'NAV_DODAJ_LEGLO'), 'url' => ['/brood-racing/create'], ],
					],
					'active'=>checkUrl(['brood-racing', 'couple-racing']),
				],
				//**************************************************************************************************
				['label' => '<i class="fa fa-table"></i> <span class="title">'. Yii::t('default', 'Racing Table').'</span> <span class="arrow"></span>', 
				'url' => 'javascript:;', 
				'items' => 
					[
						
						['label' => Yii::t('default', 'Racing Table Search'), 'url' => ['/racing-table/index'], ],
						['label' => Yii::t('default', 'Create Racing Table'), 'url' => ['/racing-table/create'], ],
						['label' => Yii::t('default', 'Create Category'), 'url' => ['/racing-table-category/create'], ],
						['label' => Yii::t('default', 'Search Categories'), 'url' => ['/racing-table-category/index'], ],
					],
				'active'=>checkUrl(['racing-table', 'racing-table-category']),	
				],
				//**************************************************************************************************
				/*['label' => '<i class="fa fa-bullhorn"></i> <span class="title">'. Yii::t('default', 'Buy it now').'</span> <span class="arrow">', 'url' => 'javascript:;', 'items' => 
					[
						
						['label' => Yii::t('default', 'Create bin ads'), 'url' => ['/buyitnow/create'], ],
						['label' => Yii::t('default', 'Search bin ads'), 'url' => ['/buyitnow/publicsearch'], ],
						['label' => Yii::t('default', 'My ads bin'), 'url' => ['/buyitnow/admin'], ],
						['label' => Yii::t('default', 'Bought pigeons'), 'url' => ['/buyitnow/bought'], ],
						['label' =>Yii::t('default', 'Sold pigeons'), 'url' => ['/buyitnow/sold'], ],
					]
				],*/
				//**************************************************************************************************
				[
				'label' => '<i class="fa fa-map-marker"></i> <span class="title">'. Yii::t('default', 'Lost and Found').'</span> <span class="arrow"></span>', 
				'url' => 'javascript:;', 
				'items' => 
					[
						
						['label' => Yii::t('default', 'Search lost pigeons'), 'url' => ['/found-pigeons/public'], ],
						['label' => Yii::t('default', 'Add found pigeon'), 'url' => ['/found-pigeons/create'], ],
						['label' => Yii::t('default', 'Pigeons Ive found'), 'url' => ['/found-pigeons/index'], ],
					],
				'active'=>checkUrl(['found-pigeons']),	
				],
				//**************************************************************************************************
				[
				'label' => '<i class="fa fa-image"></i> <span class="title">'. Yii::t('default', 'Pigeon Pictures').'</span> <span class="arrow"></span>', 
				'url' => 'javascript:;', 
				'items' => 
					[
						
						['label' => Yii::t('default', 'Albums'), 'url' => ['/pigeon-image/album/index'], ],
						['label' => Yii::t('default', 'Create album'), 'url' => ['/pigeon-image/album/create'], ],
						['label' => Yii::t('default', 'Latest pictures'), 'url' => ['/pigeon-image/image/latest'], ],
						['label' => Yii::t('default', 'Latest albums'), 'url' => ['/pigeon-image/album/latest'], ],
					],
				'active'=>checkUrl(['pigeon-image']),	
				],
				//**************************************************************************************************
				[
				'label' => '<i class="fa fa-bar-chart"></i> <span class="title">'. Yii::t('default', 'Statistics').'</span>', 
				'url' => 'javascript:;', 
				'items' => 
					[
						['label' =>  Yii::t('default', 'My pigeons') , 'url' => ['/statistics/pigeons'], ],
						['label' =>  Yii::t('default', 'Racing Table') , 'url' => ['/statistics/table-racing'], ],
					]
				],
				//**************************************************************************************************
				[
				'label' => '<i class="fa fa-group"></i> <span class="title">'. Yii::t('default', 'Clubs').'</span> <span class="arrow"></span>', 
				'url' => 'javascript:;', 
				'items' => 
					[
						['label' =>  Yii::t('default', 'Search clubs') , 'url' => ['/club/club/index', 'club_page'=>'0'], ],
						['label' =>  Yii::t('default', 'Create Club') , 'url' => ['/club/club/create', 'club_page'=>'0'], ],
					]
				],
				//**************************************************************************************************
				/*[
				'label' => '<i class="fa fa-briefcase"></i> <span class="title">'. Yii::t('default', 'Rent a breeder').'</span> <span class="arrow"></span><span class="label label-important pull-right ">'.Yii::t('default', 'New').'</span>', 
				'url' => 'javascript:;', 
				'items' => 
					[
						['label' =>  Yii::t('default', 'Rent me') , 'url' => ['/rent-a-breeder/create'], ],
						['label' =>  Yii::t('default', 'Rent a breeder') , 'url' => ['/rent-a-breeder/index']],
						['label' =>  Yii::t('default', 'My ads') , 'url' => ['/rent-a-breeder/index','mine'=>'true']],
					]
				],*/
				//**************************************************************************************************
				['label' => '<i class="fa fa-question"></i> <span class="title">'. Yii::t('default', 'NAV_ONAMA').'</span> <span class="arrow"></span>', 'url' => 'javascript:;', 'items' => 
					[
						['label' =>  Yii::t('default', 'NAV_CONTACT') , 'url' => ['/site/contact'], ],
						['label' =>  Yii::t('default', 'Who are we?') , 'url' => ['/site/about'], ],
					]
				],
				//**************************************************************************************************
				['label' => '<i class="fa fa-microphone"></i><span class="title">'.Yii::t('default', 'Pigeon radio').'</span>', 'url' => ['/site/radio'], ],
				//**************************************************************************************************
				['label' => '<i class="fa fa-plug"></i> <span class="title">'. Yii::t('default', 'Logout').'</span>', 
					'url' => '/user/security/logout', 		
					'template'=>'<a href="{url}" data-method="post">{label}</a>',
				],
				//**************************************************************************************************
			],
		]);	
}
?>