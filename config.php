<?php

##################################################################
#####################Teamspeak####################################
##################################################################

$ts3_ip = '';				//TS3 ServerIP / Domain
$ts3_queryport = 10011;		//TS3 ServerQueryPort DEFAULT: 10011
$ts3_user = 'serveradmin';	//TS3 ServerQueryBenutzer DEFAULT: serveradmin
$ts3_pass = '';				//TS3 ServerQueryPasswort
$ts3_port = 9987;			//TS3 ServerInstancePort DEFAULT: 9987

##################################################################
###################Guild Wars 2###################################
##################################################################

$gw_api_key = "";  	//Guildwars 2 API Schlüssel Berechtigungen nötig: "account" & "guilds"
$gw_gilden_id = "";	//Guild Wars 2 Gilden ID - Nutze die Debug Funktion

##################################################################
####################Admin & Debug#################################
##################################################################

$debug = true;		//Debug Informationen

$tokentype = 0; 	//0 = ServerGruppe / 1 = ChannelGruppe / 2 = TempServerGruppe / 3 = TempChannelGruppe
$token_1 = 10;		// ID der gewünschten ServerGruppe wenn benutzer in der Gilde ist - Nutze die Debug Funktion							
$token_2 = 8;		// ID der gewunschten ServerGruppe wenn benutzer nicht in der Gilde ist - Nutze die Debug Funtktion
?>