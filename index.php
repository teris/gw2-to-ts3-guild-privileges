<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<title>TS3 Berechtigung</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body class="blurBg-false" style="background-color:#EBEBEB">

<!-- Start Formoid form-->
<link rel="stylesheet" href="formoid1/formoid-biz-blue.css" type="text/css" />
<script type="text/javascript" src="formoid1/jquery.min.js"></script>

<?php

include_once("config.php");

if(!$_GET['token']!= NULL):
	$_GET['token'] = $gw_api_key;
endif;

include "ts3admin.class.php";
include('class.gw.php');

$ts3 = new ts3admin($ts3_ip, $ts3_queryport);
$gw2 = new GW2($authkey = $gw_api_key);

	$acc_info	= $gw2->accountDetails();

	$connect = $ts3->connect();
	$login = $ts3->login($ts3_user, $ts3_pass);
	$selcet = $ts3->selectServer($ts3_port);

if(isset($_POST['absenden'])):



	if($connect['success']):
		//print_r($ts3->privilegekeyList());
		foreach($acc_info['guilds'] as $gilden):
			if($gilden == $gw_gilden_id):
				$tokenMyOk = $ts3->privilegekeyAdd($tokentype,$token_1,'0'); 
				$ok = 1;
			endif;
		endforeach;
		?>
		<form class="formoid-biz-blue" style="background-color:#1A2223;color:#ECECEC;max-width:480px;min-width:150px">
			<div class="title"><h2>TS3 Berechtigung</h2></div>
				<div class="element-input">
				<?php
				if($ok != NULL):
					echo "<br>Glückwunsch, es hat alles Funktioniert!<br><br>";
					echo "Dein TS3-Schlüssel: <input class='large' type='text' value='".$tokenMyOk['data']['token']."' />";
					echo "<br><br>Server-Adresse: <a href='ts3server://'".$ts3_ip."'>".$ts3_ip."</a><br>";
				else:
					$tokenMyNo = $ts3->privilegekeyAdd($tokentype,$token_2,'0'); 
					echo "<br>Es tut mir leid, aber du bekommst nur eingeschränkten Zugriff!<br><br>";
					echo "Dein TS3-Schlüssel: <input class='large' type='text' value='".$tokenMyNo['data']['token']."' />";
					echo "<br><br>TS3Server-Adresse: <a href='ts3server://'".$ts3_ip."'>".$ts3_ip."</a><br>";
				endif;
				?>
				</div>
			</div>
			<div class="submit"></div>
		</form>
		<?php
	else:
		echo "Fehler bei der Verbindung";
	endif;

else:
	?>
	<form class="formoid-biz-blue" style="background-color:#1A2223;color:#ECECEC;max-width:480px;min-width:150px" method="post">
		<div class="title"><h2>TS3 Berechtigung - GW2 Gildenrecht</h2></div>
		<div class="element-input">
			<label class="title">
				<span class="required">* GW2 API-Schlüssel benutzen.</span>
			</label>
			<input class="large" type="text" name="apikey" required="required" placeholder="API-Schlüssel"/>
			<br>
			Einen GW2-Schlüssel mit den Berechtigungen "account" und "guilds" reicht aus. <br> 
			Diesen Schlüssel bekommst du <a target="_blank" href="https://account.arena.net/applications">hier</a> her.<br><br>
			TS3Server-Adresse: <a href="ts3server://<?php echo $ts3_ip; ?>"><?php echo $ts3_ip; ?></a><br>
		</div>
		<div class="submit">
			<input type="submit" name="absenden" value="Absenden"/>
		</div>
	</form>
	
	<?php
endif;

if($debug == true):
	echo "<pre>";
	
	echo "Benutze die eine der IDs für die Einstellung in der Config.php um die Entsprechende ServerGruppe fest zu legen.<br>";
	foreach($ts3->serverGroupList("0")['data'] as $key => $value):
		echo "ServerGruppe: ".$value['name']." - ID:" . $value['sgid'] ."<br>";
	endforeach;
	
	echo "<hr>";
	$guilds = $gw2->accountDetails()['guilds'];
	$leader_guild = $gw2->accountDetails()['guild_leader'];

	echo "Kopiere die entsprechende ID deiner Gilde aus, um diese in der Config.php fest zu legen.<br>";
	foreach($guilds as $k => $ka):
		echo "ID: ".$gw2->getGuildInfo($guilds[$k])['id']."<br>";
		echo "Name: ".$gw2->getGuildInfo($guilds[$k])['name']." [".$gw2->getGuildInfo($guilds[$k])['tag']."]<br><hr>";
	endforeach;

	echo "</pre>";
endif;
?>

</body>
</html>