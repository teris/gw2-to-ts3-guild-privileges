<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<title>Installer</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="formoid1/formoid-biz-red.css" type="text/css" />
	<script type="text/javascript" src="formoid1/jquery.min.js"></script>
</head>
<body class="blurBg-false" style="background-color:#EBEBEB">
<?php

include "ts3admin.class.php";
include('class.gw.php');

if($_GET['step']==NULL):
	header("LOCATION: ?step=1");
endif;

if($_GET['step'] == 1):
	if(isset($_POST['next01'])):
		$ts3_ip = $_POST['input'];		
		$ts3_queryport = $_POST['input1'];
		$ts3_user = $_POST['input2'];
		$ts3_pass = $_POST['input3'];
		$ts3_port = $_POST['input4'];
		$step = $_POST['next01'];
		
		$ts3 = new ts3admin($ts3_ip, $ts3_queryport);
		$connect = $ts3->connect();
		//$login = $ts3->login($ts3_user, $ts3_pass);
		//$selcet = $ts3->selectServer($ts3_port);
		echo '<form class="formoid-biz-red">';
		if($connect['success']):
			//print_r($ts3->serverGroupList());
			echo '<div class="title">'."Verbindung zum Server Hergestellt!".'</div>';
			echo '<div class="submit"><a href="?step=2">Weiter</a></div>';
			
			$connect_code_1='<?php
							$ts3_ip = "'.$ts3_ip.'";
							$ts3_queryport = '.$ts3_queryport.';
							$ts3_user = "'.$ts3_user.'";
							$ts3_pass = "'.$ts3_pass.'";
							$ts3_port = '.$ts3_port.';
						   ?>';


			if($handle = fopen("temp.config.1.php", 'w')):

				$fp = fopen('temp.config.1.php', 'wb');
				fwrite($fp,$connect_code_1);
				fclose($fp);
				chmod('temp.config.1.php', 0777);

			else:

				echo '<div class="title">'."<p>Sorry, I can't write to <b>temp.config.1.php</b>.
				You will have to edit the file yourself. Here is what you need to insert in that file:<br /><br />
				<textarea rows='5' cols='50' onclick='this.select();'>$connect_code_1</textarea></p></div>";

			endif;
			
		else:
			echo '<div class="title">Fehler bei der Verbindung</div>';
		endif;
		echo '</form>';
	else:
		//Step 1
		echo '<form class="formoid-biz-red" name="field1" method="post">
		<div class="title">
			<h2>Installer</h2>
		</div>
		<div class="element-input">
			<label class="title">TS3 Serveradresse:</label>
			<input class="large" type="text" name="input" placeholder="voice.ts3.example.com"/>
		</div>
		<div class="element-input">
			<label class="title">TS3 QueryPort:</label>
			<input class="large" type="text" name="input1" placeholder="Default: 10011"/>
		</div>
		<div class="element-input">
			<label class="title">TS3 QueryUser:</label>
			<input class="large" type="text" name="input2" placeholder="Default: serveradmin"/>
		</div>
		<div class="element-input">
			<label class="title">TS3 QueryPasswort:</label>
			<input class="large" type="password" name="input3" placeholder="*************"/>
		</div>
		<div class="element-input">
			<label class="title">TS3 Serverport:</label>
			<input class="large" type="text" name="input4" placeholder="Default: 9987"/>
		</div>
		<div class="submit"><input type="submit" name="next01" value="Weiter"/></div>
		</form>
		';
	endif;
endif;

if($_GET['step'] == 2):
	if(isset($_POST['next02'])):		
		$gw_api_key = $_POST['input'];
		$gw2 = new GW2($authkey = $gw_api_key);
		$acc_info	= $gw2->accountDetails();
		echo '<form class="formoid-biz-red">';
		if($acc_info["text"] != NULL):
			echo "Dein GuildWars 2 API-Schlüssel funktioniert nicht korrekt.";
		else:
			echo '<div class="title">'."Verbindung zu GuildWars2 Hergestellt!".'</div>';
			echo '<div class="submit"><a href="?step=3">Weiter</a></div>';
			
			$connect_code_2='<?php
							$gw_api_key = "'.$gw_api_key.'";
						   ?>';


			if($handle = fopen("temp.config.2.php", 'w')):

				$fp = fopen('temp.config.2.php', 'wb');
				fwrite($fp,$connect_code_2);
				fclose($fp);
				chmod('temp.config.2.php', 0777);

			else:

				echo '<div class="title">'."<p>Sorry, I can't write to <b>temp.config.2.php</b>.
				You will have to edit the file yourself. Here is what you need to insert in that file:<br /><br />
				<textarea rows='5' cols='50' onclick='this.select();'>$connect_code_2</textarea></p></div>";

			endif;
		
		endif;
		echo '</form>';
	else:
		echo '<form class="formoid-biz-red" name="field2" method="post">
		<div class="title"><h2>Installer</h2></div>
		<div class="element-input">
			<label class="title">Guild Wars 2 API-Schlüssel:</label>
			<input class="large" type="text" name="input" placeholder="********-****-****-****-********************-****-****-****-************"/>
		</div>
		<div class="submit"><input type="submit" name="next02" value="Weiter"/></div>
		</form>';
	endif;
endif;

if($_GET['step'] == 3):
	include("temp.config.1.php");
	include("temp.config.2.php");
	
	if(isset($_POST['next03'])):
		$connect_code='<?php

		##################################################################
		#####################Teamspeak####################################
		##################################################################

		$ts3_ip = "'.$ts3_ip.'";			//TS3 ServerIP / Domain
		$ts3_queryport = '.$ts3_queryport.';						//TS3 ServerQueryPort
		$ts3_user = "'.$ts3_user.'";					//TS3 ServerQueryBenutzer
		$ts3_pass = "'.$ts3_pass.'";						//TS3 ServerQueryPasswort
		$ts3_port = '.$ts3_port.';							//TS3 ServerInstancePort

		##################################################################
		###################Guild Wars 2###################################
		##################################################################

		$gw_api_key = "'.$gw_api_key.'";  //Guildwars 2 API Schlüssel Berechtigungen nötig: "account" & "guilds"
		$gw_gilden_id = "'.$_POST['select'].'";										//Guild Wars 2 Gilden ID - Nutze die Debug Funktion

		##################################################################
		####################Admin & Debug#################################
		##################################################################

		$debug = false;								//Debug Informationen

		$tokentype = 0; 							//0 = ServerGruppe / 1 = ServerGruppe / 2 = TempServerGruppe / 3 = TempChannelGruppe
		$token_1 = '.$_POST['select1'].';								// ID der gewünschten ServerGruppe wenn benutzer in der Gilde ist - Nutze die Debug Funktion							
		$token_2 = '.$_POST['select2'].';								// ID der gewunschten ServerGruppe wenn benutzer nicht in der Gilde ist - Nutze die Debug Funtktion
		?>';

		if(!is_writable("config.php")):
			if($handle = fopen("config.php", 'w')):

				$fp = fopen('config.php', 'wb');
				fwrite($fp,$connect_code);
				fclose($fp);
				chmod('config.php', 0644);
				echo '<form class="formoid-biz-red">
						<div class="title"><h2>Fertig, du kannst nun loslegen!</h2></div></form>';
				unlink("temp.config.1.php");
				unlink("temp.config.2.php");

			else:

				echo "<p>Sorry, I can't write to <b>config.php</b>.
				You will have to edit the file yourself. Here is what you need to insert in that file:<br /><br />
				<textarea rows='5' cols='50' onclick='this.select();'>$connect_code</textarea></p>";

			endif;

		else:

			$fp = fopen('config.php', 'wb');
			fwrite($fp,$connect_code);
			fclose($fp);
			chmod('config.php', 0644);

		endif;
	else:
		
		$ts3 = new ts3admin($ts3_ip, $ts3_queryport);
		$gw2 = new GW2($authkey = $gw_api_key);
		
		$guilds = $gw2->accountDetails()['guilds'];

		$connect = $ts3->connect();
		$login = $ts3->login($ts3_user, $ts3_pass);
		$selcet = $ts3->selectServer($ts3_port);
		
	echo '
	<form class="formoid-biz-red" name="field3" method="post">
		<div class="title"><h2>Installer</h2></div>
		<div class="element-select">
			<label class="title">Guild Wars 2 Gilde:</label>
			<div class="large">
				<select name="select" >';
					foreach($guilds as $k => $ka):
						echo "<option value='".$gw2->getGuildInfo($guilds[$k])['id']."'>";
						echo $gw2->getGuildInfo($guilds[$k])['name']." [".$gw2->getGuildInfo($guilds[$k])['tag']."]</option>";
					endforeach;
				echo '</select>
			</div>
		</div>
		<div class="element-select">
			<label class="title">TS3 ServerGruppe:</label>
			<div class="large">
				<select name="select1" >';
				foreach($ts3->serverGroupList()['data'] as $key => $value):
					if($value['type'] == 1):
						echo "<option value='".$value['sgid']."'>". $value['name'] ."</option>";
					endif;
					
				endforeach;
			echo '</select>
			</div>
		</div>
		<div class="element-select">
			<label class="title">TS3 ServerGruppeAlternative:</label>
			<div class="large">
				<select name="select2" >';
				foreach($ts3->serverGroupList("0")['data'] as $key => $value):
					if($value['type'] == 1):
						echo "<option value='".$value['sgid']."'>". $value['name'] ."</option>";
					endif;
				endforeach;
			echo '</select>
			</div>
		</div>
		<div class="submit"><input type="submit" name="next03" value="Weiter"/></div>
	</form>
	';
	endif;
endif;


?>

<script type="text/javascript" src="formoid1/formoid-biz-red.js"></script>
</body>
</html>