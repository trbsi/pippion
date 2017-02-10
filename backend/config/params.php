<?php
//\Yii::$app->params['subscriptionFee']
return [
    'adminEmail' => 'dario.trbovic@pippion.com',
    'adminEmail2' => 'pippion.com@gmail.com',
	'thetta'=>'http://www.thetta.com.hr',
	'Pippion'=>'Pippion',
	'subscriptionFee'=>100,
	'subscriptionFeeMonthly'=>10,
	'maxImageSizeOnPippion'=>2,
	'maxPedigreeSizeOnPippion'=>4,
	'adminId'=>[2],

	//live
	'pippion_site'=>'http'.(isset($_SERVER['HTTPS']) ? 's' : '').'://www.pippion.com/',
	'paypalEmail'=>'payments@pippion.com',
	'paypalIPN'=>'http://www.pippion.com/subscription/ipn',

	//test
	/*'pippion_site'=>'http://pippion.test.thetta.com.hr/',
	'paypalIPN'=>'http://pippion.test.thetta.com.hr/subscription/ipn',//sandbox account
	'paypalEmail'=>'admin@pippion.com',//test sandbox account*/
	
	//PUSHER
	'pusher_channel'=>'pippion_channel',
	'chat_channel_name'=>'pippion_chat_',//pippion_chat_IDMESSAGE
	'event_new_message'=>'new_message_',//new_message_IDuser whom you sending this notification
	'app_id' => '113089',
	'app_key' => 'f047a8d93ab7001f3bee',
	'app_secret' => '7e302195e49848a288cb',
	
	//cookie
	'username_cookie'=>'srnm',
	'name_of_breeder_cookie'=>'nmfbrd',
	'breeder_data_cookie'=>'brddta', //1 or 0 indicates whether breeder entered data or not
	'is_breeder_verified_cookie'=>'brdrvrf',
];
