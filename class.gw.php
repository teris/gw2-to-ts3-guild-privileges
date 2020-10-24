<?php
/**
 * @name GW2 API Test
 * @author flutterderp
 * @version 0.5.1
 * 
 * @todo
 */
date_default_timezone_set('UTC');

class GW2
{
	protected $api_key;
	protected $base_url;
	protected $ch;
	protected $base_dir;
	protected $cache_time;
	
	/**
	 * GW2 constructor
	 * 
	 * @param string $authkey An optional API key used for accessing authenticated endpoints
	 */
	function __construct($authkey = '')
	{
		$this->base_dir		= __DIR__ . '/json_files/';
		$this->base_url 	= 'https://api.guildwars2.com/v2/';
		$this->icon_url		= 'https://api.guildwars2.com/v1/';
		$this->cache_time	= 5 * 60;
		$this->ch					= curl_init();
		$headers					= array();
		$headers[]				= 'Content-type: application/json';
		if($authkey)
		{
			$this->api_key	= $authkey;
			$headers[]			= 'Authorization: Bearer ' . $this->api_key;
		}
		
		curl_setopt($this->ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($this->ch, CURLOPT_HEADER, false);
		curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, 0);
	}

	function __destruct()
	{
		curl_close($this->ch);
	}
	
	/**
	 * Method to save response data to a JSON file
	 *
	 * @param string $file file name to save to
	 * @param string $data JSON-encoded response string
	 *
	 * @todo 
	 */
	protected function makeCacheFile($file, $data)
	{
		// Do stuff
	}
	
	function accountDetails()
	{
		$cache_file		= 'https://api.guildwars2.com/v2/account_' . sha1($this->api_key) . '.json';
		$fetch_cache	= @file_get_contents($cache_file);
		
		if(($fetch_cache !== false) && ((filemtime($cache_file) + $this->cache_time) > time()))
		{
			// Use our cached results
			$response = $fetch_cache;
			$return		= json_decode($response, true);
		}
		else
		{
			// Write to a JSON file
			curl_setopt($this->ch, CURLOPT_URL, 'https://api.guildwars2.com/v2/account?lang=de');
			$response	= curl_exec($this->ch);
			$return		= json_decode($response, true);
			
			// Fetch name of world first…
			if(isset($return['world']))
			{
				curl_setopt($this->ch, CURLOPT_URL, $this->base_url . 'worlds?id=' . $return['world'] . '&lang=de');
				$response								= curl_exec($this->ch);
				$return['world_name']		= json_decode($response, true)['name'];
			}
			
			@file_put_contents($cache_file, json_encode($return));
			@touch($cache_file, time());
		}
		
		return $return;
	}
	
	
	function getGuildInfo($guild_id)
	{
		$cache_file		= 'https://api.guildwars2.com/v2/guild/id_' . sha1($this->api_key) . '.json';
		$fetch_cache	= @file_get_contents($cache_file);	
			
		if(($fetch_cache !== false) && ((filemtime($cache_file) + $this->cache_time) > time()))
		{
			// Use our cached results
			$response = $fetch_cache;
			$return		= json_decode($response, true);
		}
		else
		{
			// Write to a JSON file
			curl_setopt($this->ch, CURLOPT_URL, 'https://api.guildwars2.com/v2/guild/'.$guild_id.'?lang=de');
			$response	= curl_exec($this->ch);
			$return		= json_decode($response, true);
			
			@file_put_contents($cache_file, json_encode($return));
			@touch($cache_file, time());
		}
		return $return;
	}
	
	function getWallet()
	{
		$cache_file		= 'https://api.guildwars2.com/v2/account/wallet_' . sha1($this->api_key) . '.json';
		$fetch_cache	= @file_get_contents($cache_file);
		
		if(($fetch_cache !== false) && ((filemtime($cache_file) + $this->cache_time) > time()))
		{
			// Use our cached results
			$response = $fetch_cache;
			$return		= json_decode($response, true);
		}
		else
		{
			// Write to a JSON file
			curl_setopt($this->ch, CURLOPT_URL, 'https://api.guildwars2.com/v2/account/wallet?lang=de');
			$response	= curl_exec($this->ch);
			$return		= json_decode($response, true);
			
			
			@file_put_contents($cache_file, json_encode($return));
			@touch($cache_file, time());
		}
		
		return $return;
		
	}
	
	function getCharacters()
	{
		$cache_file		= $this->base_dir . 'chars_' . sha1($this->api_key) . '.json';
		$fetch_cache	= @file_get_contents($cache_file);
		
		if(($fetch_cache !== false) && ((filemtime($cache_file) + $this->cache_time) > time()))
		{
			// Use our cached results
			$response = $fetch_cache;
		}
		else
		{
			// Write to a JSON file
			curl_setopt($this->ch, CURLOPT_URL, $this->base_url . 'characters?lang=de');
			$response = curl_exec($this->ch);
			@file_put_contents($cache_file, $response);
			@touch($cache_file, time());
		}	
		
		return json_decode($response, true);
	}
	
	function charInfo($char_name = '')
	{
		$char_name		= rawurlencode($char_name);
		$cache_file		= $this->base_dir . $char_name . '.json';
		$fetch_cache	= @file_get_contents($cache_file);
		
		if(($fetch_cache !== false) && ((filemtime($cache_file) + $this->cache_time) > time()))
		{
			// Use our cached results
			$response = $fetch_cache;
		}
		else
		{
			// Write to a JSON file
			curl_setopt($this->ch, CURLOPT_URL, $this->base_url . 'characters/' . $char_name . '?lang=de');
			$response = curl_exec($this->ch);
			@file_put_contents($cache_file, $response);
			@touch($cache_file, time());
		}
		
		$response = json_decode($response, true);
		
		if($response['age'])
		{
			$age									= array('h' => 0, 'm' => 0, 's' => 0);
			$age['h']							= floor($response['age'] / 3600);
			$age['m']							= floor(($response['age'] / 60) % 60);
			$age['s']							= floor($response['age'] % 60);
			$response['name_me']	= $age;
		}
		
		return $response;
		// return json_decode($response, true);
	}
	
	function minisInfo()
	{
		$cache_file		= 'https://api.guildwars2.com/v2/account/minis_' . sha1($this->api_key) . '.json';
		$fetch_cache	= @file_get_contents($cache_file);
		
		if(($fetch_cache !== false) && ((filemtime($cache_file) + $this->cache_time) > time()))
		{
			// Use our cached results
			$response = $fetch_cache;
			$return		= json_decode($response, true);
		}
		else
		{
			// Write to a JSON file
			curl_setopt($this->ch, CURLOPT_URL, 'https://api.guildwars2.com/v2/account/minis?lang=de');
			$response	= curl_exec($this->ch);
			$return		= json_decode($response, true);
			
			
			@file_put_contents($cache_file, json_encode($return));
			@touch($cache_file, time());
		}
		
		return $return;
	}
	
	function item_details($icons = '')
	{
		$icons 		= rawurlencode($icons);
		$cache_file		= $this->icon_url . $icons . '.json';
		$fetch_cache	= @file_get_contents($cache_file);
		
		if(($fetch_cache !== false) && ((filemtime($cache_file) + $this->cache_time) > time()))
		{
			// Use our cached results
			$response = $fetch_cache;
		}
		else
		{
			// Write to a JSON file
			curl_setopt($this->ch, CURLOPT_URL, $this->icon_url . 'item_details' . $icons . '?lang=de');
			$response = curl_exec($this->ch);
			@file_put_contents($cache_file, $response);
			@touch($cache_file, time());
		}
		
		$response = json_decode($response, true);
		return $response;
	}
}
