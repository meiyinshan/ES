<?php
	function LevenshteinDistance($s,$t) {
		$m = strlen($s);
		$n = strlen($t);
		for($i=0;$i<=$m;$i++) $d[$i][0] = $i;
		for($j=0;$j<=$n;$j++) $d[0][$j] = $j;
		for($i=1;$i<=$m;$i++) {
			for($j=1;$j<=$n;$j++) {
				if($s[$i-1] == $t[$j-1]) {
					$c = 0;
				} else {
					$c = 1;
				}
				$d[$i][$j] = min($d[$i-1][$j]+1,$d[$i][$j-1]+1,$d[$i-1][$j-1]+$c);
			}
		}
		return $d[$m][$n];
	}
	
	function ConnectDB() {
		try {
			$link = mysql_connect('localhost', 'root', '');
			mysql_select_db('entries', $link) or die(mysql_error());
		} catch(Exception $e) {
			echo 'Caught exception: ',  $e->getMessage(), "\n";
		}
	}
	
	function GetDataEntries($key) {
		$key = trim($key, ' ');
		$list = explode(' ', $key);
		$string = strtolower($list[count($list) - 1]);
		$strlen = strlen($string);
		$merged_string = "";
		for($i = 0; $i < count($list) - 1; $i++) {
			$merged_string .= $list[$i]." ";
		}

		$query = "SELECT `word`,`length`, (";	
		for($id = 0 ; $id < $strlen - 2; $id++){
			$query .= "alias.cond_".$id." + ";
		}
		$query .= "alias.cond_".($strlen - 2).") AS total";
		$query .= " FROM (SELECT `word`, `length` ";
		for($id = 0 ; $id < $strlen - 1; $id++){
			$query .= ", IF(`word` LIKE '%".($string[$id].$string[$id+1])."%', 1, 0) AS cond_".$id."";
		}
		$query .= " FROM `entries` WHERE `length` BETWEEN ".($strlen - 3)." AND ".($strlen + 3).") AS alias" ;	
		
		
		$result=mysql_query($query);
		$i = 0;
		$array_res = array(
			array('word' => '', 'distance' => 99),
			array('word' => '', 'distance' => 99),
			array('word' => '', 'distance' => 99),
			array('word' => '', 'distance' => 99),
			array('word' => '', 'distance' => 99),
			array('word' => '', 'distance' => 99),
			array('word' => '', 'distance' => 99)
		);

		$array_last_id = count($array_res) - 1;
		$k = 0;
		if ($result) 
		{
			while($data = mysql_fetch_array($result)) {
				$distance = 99;
				$cond_cnt = 0;
				$get_word = $data['word'];
				if( $data['total'] < $strlen * 4 / 10) 
					continue;
				$k++;
				
				usort($array_res, function($a, $b) {
					return $a['distance'] - $b['distance'];
				});
				
				if($distance == 0) {
					break;
				}
				$distance  = levenshtein(strtolower($get_word), $string);
				if($distance < $array_res[$array_last_id]['distance']) {
					$array_res[$array_last_id]['word'] = $get_word;
					$array_res[$array_last_id]['distance'] = $distance;
				}
			}
			for($i = 0; $i <=  $array_last_id - 1; $i++) {
				for($j = $i+1; $j <=  $array_last_id; $j++) {
					if($array_res[$j]['distance'] < $array_res[$i]['distance']
					|| ( $array_res[$j]['distance'] == $array_res[$i]['distance']
						&& strlen($array_res[$j]['word']) > strlen($array_res[$i]['word']))) {
						$tmp = $array_res[$i];
						$array_res[$i] = $array_res[$j];
						$array_res[$j] = $tmp;
					}
				}
			}
			for($i = 0; $i < count($array_res); $i++) {
				if($array_res[$i]['word'] != '') {
					echo '<div class="sbsb_c" id="sugg_'.$i.'">'.$merged_string.strtolower($array_res[$i]['word']).'</div>';
				}
			}
		}
	}
	ConnectDB();
	GetDataEntries($_POST['key']);

?>