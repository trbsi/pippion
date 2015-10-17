<?php 
use backend\models\Auction;
class Configuration
{
	// For a full list of configuration parameters refer in wiki page (https://github.com/paypal/sdk-core-php/wiki/Configuring-the-SDK)
	public static function getConfig()
	{
		$config = array(
				// values: 'sandbox' for testing
				//		   'live' for production
				"mode" => (Auction::PAYPAL_SANDBOX==true) ? "sandbox" : "live", 
	
				// These values are defaulted in SDK. If you want to override default values, uncomment it and add your value.
				// "http.ConnectionTimeOut" => "5000",
				// "http.Retry" => "2",	
		);
		return $config;
	}
	
	// Creates a configuration array containing credentials and other required configuration parameters.
	public static function getAcctAndConfig()
	{
		//SANDBOX
		if(Auction::PAYPAL_SANDBOX==true)
		{
			$config = array(
				// Signature Credential
				"acct1.UserName" => "admin_api1.pippion.com",
				"acct1.Password" => "1402761471",
				"acct1.Signature" => "AFcWxV21C7fd0v3bYYYRCpSSRl31A-cElODXl1Rn0UfDhCdT4Q66Sk4B",
				"acct1.AppId" => "APP-80W284485P519543T",
				
				// Sample Certificate Credential
				// "acct1.UserName" => "certuser_biz_api1.paypal.com",
				// "acct1.Password" => "D6JNKKULHN3G5B8A",
				// Certificate path relative to config folder or absolute path in file system
				// "acct1.CertPath" => "cert_key.pem",
				// "acct1.AppId" => "APP-80W284485P519543T"
		
				// Sandbox Email Address
				//"service.SandboxEmailAddress" => "pp.devtools@gmail.com"
				);
		}
		//LIVE
		else
		{
			$config = array(
				// Signature Credential
				"acct1.UserName" => "payments_api1.pippion.com",
				"acct1.Password" => "WG7PNBFA2ESGTDEC",
				"acct1.Signature" => "AI3JVh2qclbN3shlme97O6bfUcKzA0yXKHiYfn7c-1M8JilXAkwv03MD",
				"acct1.AppId" => "APP-1VJ9533028143682N",
				);
			
		}
		
		return array_merge($config, self::getConfig());;
	}

}

