<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model backend\models\Auction */

$this->title =Yii::t('default', 'Rules');
?>
<script>
//http://stackoverflow.com/questions/26998841/change-scroll-to-anchor-offset-jquery
$(document).ready(function(e) {
	$("a.questions").click(function(e)
	{
		e.preventDefault();
		var attr=$(this).attr('href');
		$('body,html').animate({
			scrollTop: $(attr).offset().top-90
		},0);
	});
});
</script>
<div class="row">
    <div class="col-md-12">
      <div class="grid simple">
        <div class="grid-title no-border">
          <h4><?php echo Yii::t('default', 'Rules') ?></h4>
          <div class="tools"> <a href="javascript:;" class="collapse"></a> <a href="#grid-config" data-toggle="modal" class="config"></a> <a href="javascript:;" class="reload"></a> <a href="javascript:;" class="remove"></a> </div>
        </div>
        <div class="grid-body no-border">
        <a href="#responsibilities" class="questions">RESPONSIBILITIES</a>
        <a href="#seller-problem" class="questions"><br />
        <br />
        [BUYER] If you're having a problem with a seller</a><br>
        <a href="#won-but-no-buy" class="questions">[BUYER] I won an pigeon but no longer want to buy it. What should I do?</a><br>
        <a href="#contact-seller" class="questions"></a><a href="#pay-for-pigeon">[BUYER] How can I pay for a bought pigeon?</a><br />
        <a href="#buyer-never-arrived" class="questions">[BUYER] What if bought pigeon never arrived and seller never responds?</a><br />
        <a href="#buyer-pay-without-paypal" class="questions">[BUYER] Can I pay without having PayPal account?</a>
        <br />
        <br>
        <a href="#transport-pigeon" class="questions">[SELLER] How do I transport pigeon to a buyer?</a><br>
        <a href="#seller-money-transferred" class="questions">[SELLER] When  the money will be transferred to my account?</a><br />
        <a href="#seller-buyer-refuse-to-buy" class="questions">[SELLER] Buyer doesn't want to buy a pigeon he won, what should I do?</a><br />
        <a href="#seller-trust-buyer" class="questions">[SELLER] How can I trust a buyer and be sure that he will pay for a bought pigeon?</a><br />
        <br />
        <a href="#rules-bidding" class="questions">[BOTH] Rules about bidding and paying</a><br />
        <a href="#feedback-rules" class="questions">[BOTH] Rules about Feedback</a> <br />
        <a href="#contact-seller" class="questions">[BOTH] How do I contact a seller or buyer?</a><br />
        <a href="#percentage-take" class="questions">[BOTH] What percentage does Pippion take from auctions?</a>
        <br />
        <a href="#both-resolve-issue" class="questions">[BOTH] How can I resolve an issue or a problem?</a>
        <br />
        <a href="#both-more-questions" class="questions">[BOTH] I have more questions, who should I contact?</a>
        <br />
        <a href="#both-break-rules" class="questions"> [BOTH] What happens if I break some of those rules?</a>
        <br />
        <a href="#both-verify-acc" class="questions"> [BOTH] How can I verify my account?</a>
        <hr>
We want to maintain a safe, fair, and enjoyable marketplace for both buyers and sellers. If you're a buyer, we ask that you review and understand our policies before you bid on or buy a pigeon. Following FAQ and policies are considered to be a contract between buyer/seller and Pippion and anyone who breaks the contract will be banned from auctions and will get negative review from Pippion.<br />
<br>
Our policies are intended to:
<ul>
<li>Follow local laws and regulations</li>
<li>Minimize risks to buyers and sellers</li>
<li>Make sure that no one has an unfair advantage</li>
<li>Create an enjoyable buying experience</li>
<li>Protect intellectual property rights</li>
<li>Guidelines for buyers</li>
</ul>
The buying practices policy outlines the type of behavior that we don't allow from buyers—such as making unreasonable demands of a seller. 
<br><br>
<strong><a id="rules-bidding">[BOTH] Rules about bidding and paying</a></strong><br>
<ul>
<li>You can't use Pippion if your account contains false contact information. Your account has to verified  to access auctions.
Buyers and sellers sometimes need to be able to get in touch with each other, and we need to be able to contact our members.</li>
<li>You must pay for any pigeon you commit to buying.
Some Pippion sellers use an auction-style format, allowing you to bid on an pigeon. Bidding is fun, but keep in mind that each bid you make is a binding contract to buy the pigeon  if you win. Not paying for a pigeon after you agree to buy it has negative consequences:
<br><br>
When a buyer wins a pigeon, the buyer is obligated to complete the purchase by sending full payment to the seller.
<br><br>
If a buyer doesn't pay within 2 days, a seller can open an unpaid pigeon case in the Resolution Center. If the buyer still doesn't pay or reach some other agreement with the seller, Pippion may record the unpaid pigeon on the buyer's account.
When an unpaid pigeon  case closes without payment from the buyer, the seller is eligible to receive a final value fee credit to their seller account.
<br><br>
Excessive unpaid pigeons on a buyer's account may result in a range of consequences, including limits on or loss of buying privileges. Even if you don't have excessive unpaid pigeons, we may limit your buying until you've established a good buying history or paid for the pigeons you've committed to.<br>
</li>
<li>You can only bid if you really intend to buy the pigeon. The bottom line is, don't place a bid unless you mean to buy the pigeon.</li>
<li>You can't bid on your own pigeon.<br>
We call this shill bidding and it not only violates our policies, it's against the law in many places.
<br><br>
Shill bidding happens when anyone—including family, friends, roommates, employees, or online connections—bids on a pigeon with the intent to artificially increase its price or desirability. In addition, members cannot bid on or buy pigeons in order to artificially increase a seller's Feedback or to improve the pigeon's search standing. <br><br>
Make sure you follow these guidelines. If you dont, you may be subject to a range of actions, including limits of your buying and selling privileges and suspension of your account. Shill bidding is also illegal in many places and can carry severe penalties.
Reporting shill bidding<br><br>
If you think you see shill bidding taking place on a listing, report it to us. Be sure to provide the member's username and the auction ID number. We thoroughly investigate every report we receive. Often what appears to be shill bidding isn't a violation. If there is evidence of shill bidding, we will take action, which may include listing cancellation or referral to law enforcement. 
</li>
<li>Be careful about bidding on several pigeons if you only want one.
If you're the winning bidder of more than one auction-style listing, you need to purchase all the pigeons you've won, even if they're the same or similar.</li>
	
<li>Make sure that you read the listing description before you bid.
Many of the problems buyers and sellers encounter are the result of simple misunderstandings about what is for sale and the terms of the sale. For example, some sellers only want to sell to bidders who live in a certain country, or who will pay using PayPal. Only bid on or buy a pigeon if you can meet the requirements described in the listing. If you bid on a pigeonand you don't meet the seller's requirements, we consider that unwelcome and malicious buying.</li>

<li>If you know the seller, you can't bid on the pigeon  with the intent to increase its price or desirability artificially.
This rule applies to family, friends, roommates, employees, and online connections.
Buying pigeons  from someone just to increase their Feedback score or improve their search standing is called shill bidding, and it's against our policies.</li>

<li>You can't offer to buy pigeons  outside of Pippion.
Our policies don't cover pigeons  bought outside of our site. If you buy pigeons outside of Pippion, we don't protect you against fraud. Sellers must follow the same rule, so if a seller offers to sell you something outside of Pippion, don't accept the offer.</li>
<li>If you buy a pigeon from a seller in another country, you can't ask the seller to mark the pigeon as a gift in the customs declaration.
This is illegal, and against our policies. Learn more about our rules against encouraging illegal activity.
<br><br>

We also don't allow buyers to ask sellers to break any laws. For example, buyers can't ask sellers to falsify customs declarations or have a pigeon marked as "gift" in order to avoid customs fees. We are not liable if seller or buyer breaks law during pigeon tranportation.
<br><br>
If you're a seller, make sure your listing follows these guidelines. If it doesn't, it may be removed, and you may be subject to a range of other actions, including limits of your buying and selling privileges and suspension of your account.</li>
<li>You must have verified PayPal account or any other account you use to pay for a pigeon after auction.</li>
<li>Before bidding or creating an auction your account has to be verified. That's how we confirm that the person behind the user account is real legitimate person. If in a future there is problem between buyer and seller we use this data to help resolve this issue easier.</li>
</ul>
<p><br>
  <strong><a id="feedback-rules"> [BOTH] Rules about Feedback</a></strong>
  <br>You can't abuse the Feedback system.
  This means you can't threaten to leave a seller negative Feedback if that seller won't do something that wasn't promised in the original listing. This is called Feedback extortion and is against our policy.<br><br>
  Also, you can't leave Feedback if you're only doing it to help increase a seller's Feedback score. This is a type of Feedback manipulation and is also not allowed.<br><br>
  Make sure that you understand and follow all of our Feedback policies.
  <br>
</p>
<p><strong><a id="contact-seller">[BOTH] How do I contact a seller or a buyer?</a></strong><br />
Use messages and enter user’s username or visit user’s profile and find his/hers contact information there.<br />
<a href="<?= Url::to(['/messages/messages/inbox']) ?>">Messages</a> or <a href="<?= Url::to(['/breeder/index']); ?>">Search Breeders</a> </p>
<p><strong><a id="percentage-take">[BOTH] What percentage does Pippion take from auctions?</a></strong><br />
  Pippion takes commission from every sold pigeon on Pippion. Commission is calculated from the last bid after auction closes.
  <br />
This is how we calculate it:<br />
5% - &lt;=99€/$ <br />
10% - 100€/$ - 999€/$ <br />
15% - 1000€/$ - 9999€/$ <br />
20% - &gt;=10 000€/$</p>
<p>But be informed that during transactions PayPal (or any other payment gateway) takes fee also (usually up to 4% or less), check it here <a href="https://www.paypal.com/us/webapps/mpp/paypal-fees" target="_blank">https://www.paypal.com/us/webapps/mpp/paypal-fees</a></p>
<p><strong><a id="both-resolve-issue">[BOTH] How can I resolve an issue or a problem?</a></strong><br />
You can resolve an issue by clicking on &quot;Resolve an issue&quot; button on a page of an auction you won. The person who has to reply, either buyer or seller, has 3 days to reply before a case is closed and the person who opened a case wins.</p>
<p><strong><a id="both-more-questions">[BOTH] I have more questions, who should I contact?</a></strong><br />
  You can contact us here: <a href="<?= Url::to(['/site/contact'])?>">Contact</a></p>
<p><strong><a id="both-break-rules">[BOTH] What happens if I break some of those rules?</a></strong><br />
  If you break any of these rules your account will be banned from auctions or you will get negative review or both. Be very careful, we are sure you don't want to get a bad reputation. </p>
<p><strong><a id="both-verify-acc">[BOTH] How can I verify my account?</a></strong><br />
  Data you enter in your profile is public and every user can see it. That's how we encourage transparency, saftey and communication with each other. <a href="<?= Url::to(['/site/verify-acc']) ?>" target="_blank">Follow these steps and verify your account.</a></p>

<hr />
<p><br>
  <strong><a id="seller-problem">[BUYER] If you're having a problem with a seller</a></strong></p>
<ul>
  <li>Be honest and have good intentions when you try to resolve a transaction problem. Learn more about what to do if you don't receive a pigeon or it doesn't match the listing description.</li>
<li>You can't try to contact other potential buyers to "warn" them about a seller. This is called transaction interference. If you have a concern about a seller's behavior, report it to us and we'll investigate.</li>
</ul>

<p><strong><a id="won-but-no-buy">[BUYER] I won an pigeon but no longer want to buy it. What should I do?</a></strong>
  <br>
  A bid or commitment to buy on Pippion is considered a contract and you're obligated to purchase the pigeon. However, if you feel that you have a legitimate reason for not buying the pigeon, you can contact the seller and explain your situation.<br><br>
  Many sellers are willing to work with you if you communicate with them openly and honestly, explain the situation, and see if the seller is willing to cancel the transaction.<br><br>
  If the seller doesn't agree to the cancel the transaction and you don't pay for the pigeon, an unpaid pigeon may be recorded on your account. If you get too many unpaid pigeons recorded on your account, your account may be limited or suspended.
  <br>
  <br>
  <strong><a id="pay-for-pigeon">[BUYER] How can I pay for a bought pigeon?</a></strong><br />
At the moment, the only way to pay for bought pigeons is via PayPal. When auctions end if you are awinner you will see  PayPal  button. By clicking on this button it will take you to a PayPal page.</p>
<p>Since we care a lot about user's safety and money transfer we will deposit the money on our account so you are protected. When you receive a pigeon from a seller you can confirm it that you received a pigeon and the money will be transfered to seller's account. You can confirm that you received pigeons by clicking on &quot;Pigeon has arrived&quot; button. Since the seller is responsible for transport of pigeons, ask from him some kind of tracking code or whatever can help you to track transport so it can be safer.</p>
<p>After you confirmed that pigeon has arrived we will automatically take percentage and the rest goes to seller.</p>
<p>To resolve an issue about receiving pigeons, please click on &quot;Resolve an issue&quot; button on auction you won.</p>
<p><strong><a id="buyer-never-arrived">[BUYER] What if bought pigeon never arrived and seller never responds?</a></strong><br />
In that case you will get full refund to your account</p>
<p><strong><a id="buyer-pay-without-paypal">[BUYER] Can I pay without having PayPal account?</a></strong><br /> 
Unfortunately not at this moment. You must have PayPal account to pay for a pigeon.
<a href="https://www.paypal.com/us/webapps/helpcenter/helphub/article/?solutionId=FAQ1525&topicID=&m=ARA" target="_blank"></a></p>
<hr />
<p><strong><a id="transport-pigeon">[SELLER] How do I transport pigeon to a buyer?</a></strong><br>
  Pippion is auction website, place where you can find, sell, buy and bid on pigeons. We offer services of verification of users, saftey and money transfer. Buyer and seller are responsible for transporting and taking care of a pigeon. Very good way would be to give a buyer somekind of tracking code or meet in person (if buyer is near you). It all depends on the way of transporting.
</p>
<p><strong><a id="seller-money-transferred">[SELLER] When  the money will be transferred to my account?</a></strong><br />
 The money will be transferred immediately when a buyer pays for a pigeon. We will automatically take commission. After you receive the payment you can deliver a pigeon. We are using 3rd party payment gateway such as PayPal to make buying and selling as secure as possible.</p>
<p>.<strong><a id="seller-buyer-refuse-to-buy">[SELLER] Buyer doesn't want to buy a pigeon he won, what should I do?</a></strong><br />
  If a person bids on a pigeon and won but refuses to pay than you can try to contact a buyer. If problem is not resolved you can click on 
 &quot;Resolve an issue&quot; button on a page of your auction. That's where we come in and we will try to resolve it.</p>
<p>If buyer refuses to buy, he will get negative review even get banned and you will get an option to sell it to the second highest bidder.</p>
<p><strong><a id="seller-trust-buyer">[SELLER] How can I trust a buyer and be sure that he will pay for abought pigeon?</a></strong><br />
  At the moment buyer bids on your auction he  agrees to the terms of use where he is obligated to buy won pigeon. If he breaks the rules he will get banned and get negative review and you will get chance to sell your pigeon to second highest bidder.</p>
<p>
<hr />
<a id="responsibilities"></a>
<h3>RESPONSIBILITIES</h3>
</p><strong>Pippion is responsible for</strong>:<br />
<ul>
<li><a href="<?= Url::to(['/site/verify-acc']) ?>" target="_blank">Verifying</a> user's account</li>
<li>Taking care of auction system and website</li>
<li>Taking care of security on site</li>
<li>Resolving issue between a buyer and a seller (together with a buyer and a seller)</li>
<li>Checking if user's PayPal account (or any other account user use as payment gateway) is verified. *To create auction or bid on one, your payment gateway account must be verified.</li>
</ul>
<p><strong>Pippion is not responsible for:</strong>
<ul>
<li>The possible damage during transport</li>
<li>For data legitimicy of a pigeon being sold</li>
</ul>
<p><strong>Seller is responsible for:</strong>
<ul>
<li>Taking complete care of pigeon (health, feeding...)</li>
<li>Taking care of a transport of a pigeon</li>
<li>Data legitimicy of a pigeon being sold</li>
</ul>
<p><strong>Buyer is responsible for:</strong>
<ul>
<li>Paying for a bought pigeon (which is neccessary before seller transport a pigeon to a buyer)</li>
<li>Taking care of a transport of a pigeon</li>
</ul>
</p>
</p>
        </div>
      </div>
    </div>
  </div>