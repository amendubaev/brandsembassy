
<?php 
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once( $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php' );
require_once(__DIR__ . '/php/transferData.php'   ); // CURL
require_once(__DIR__ . '/php/essence.php'    ); // Создание лида

function writeToLog($data, $title = '') { 
	$date  =  date("m.d.y");
	$log =  getcwd() . '/log_'. $date .'.log';
	$log = file_get_contents($log);

	if (strlen($log) > 10000000) {
		$log = substr($log, -500000);
	}
	$log .= "\n------------------------\n"; 
	$log .= date("Y.m.d G:i:s") . "\n"; 
	$log .= (strlen($title) > 0 ? $title : 'DEBUG') . "\n"; $log .= print_r($data, 1); 
	$log .= "\n------------------------\n"; 
	file_put_contents(getcwd() . '/log_'. $date .'.log', $log);
}

$webHookScript = 'https://brandsembassy.bitrix24.ru/rest/17/01sq2il2orj2jipq/';

$eseence = new essenceAdd($webHookScript)	;
$CURL = new transferData;


$id = $_POST['data']['FIELDS']['ID'];

if($id > 1) {
	$posts = get_posts(array(
		'post_type' => 'speakers',
		'post_status' => 'any',
		'fields' => 'ids',
		'lang' => '',
		'posts_per_page' => -1,
		'meta_key' => 'bitrix_id',
		'meta_value' => $id
	));
	foreach ($posts as $post) {
		wp_delete_post($post, false);
		writeToLog($post, 'Removed post');
	}

}