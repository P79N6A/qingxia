<?php

class DES3 {
		//加密 by john lee
		public function encrypt($input,$key){
			$size = mcrypt_get_block_size (MCRYPT_3DES, MCRYPT_MODE_ECB );
			$input = $this->pkcs7_pad($input, $size);
			$key = base64_decode($key);
			$td = @mcrypt_module_open(MCRYPT_3DES, '', MCRYPT_MODE_ECB, '');
			@mcrypt_generic_init($td, $key,'');
			$data = mcrypt_generic($td, $input);
			mcrypt_generic_deinit($td);
			mcrypt_module_close($td);
			return strtoupper(bin2hex($data));
		}
		
		private function pkcs7_pad($text,$size){
			$padding_char = $size - (strlen($text) % $size);
			if ($padding_char <= $size) {
				$char = chr($padding_char);
				$text .= str_repeat($char, $padding_char);
			}
			return $text;
		} 
		
		private function bin2hex($text)
		{
			$hex = "";
			for ($i=0; $i < strlen($text); $i++) {
				$hi = dechex(ord($text[$i]));
				if(strlen($hi)<2)
				{
					$hex = "0".$hi;
				}
				else{
					$hex .=$hi;
				}
			}
			return $hex	;
		}
	
		//解密by john lee
		public function decrypt($input,$key){
			$input  = $this->hex2bin($input);
			$key = base64_decode($key);
			$td = mcrypt_module_open(MCRYPT_3DES,'',MCRYPT_MODE_ECB,'');
			@mcrypt_generic_init($td, $key,'');
			$data = mdecrypt_generic($td, $input);
			mcrypt_generic_deinit($td);
			mcrypt_module_close($td);
			$y=$this->pkcs7_unpad($data);
			return $y;
		}
		
		private function pkcs7_unpad($text)
		{
			$char = substr($text, -1, 1);
			$num = ord($char);
			if($num > 8){
				return $text;
			}
			$len = strlen($text);
			for($i = $len - 1; $i >= $len - $num; $i--){
				if(ord(substr($text, $i, 1)) != $num){
					return $text;
				}
			}
			$text = substr($text, 0, -$num);
			return $text;
		}
		
		private function hex2bin($hexData) {
			$binData = "";
			for($i = 0; $i < strlen ( $hexData ); $i += 2) {
				$binData .= chr ( hexdec ( substr ( $hexData, $i, 2 ) ) );
			}
			return $binData;
		}
	}
