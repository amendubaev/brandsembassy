<?php

ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

require_once($_SERVER['DOCUMENT_ROOT'] . '/wp-load.php');
require_once(__DIR__ . '/php/transferData.php');
require_once(__DIR__ . '/php/essence.php');

$webHookScript = 'https://brandsembassy.bitrix24.ru/rest/17/01sq2il2orj2jipq/';

$eseence = new essenceAdd($webHookScript);
$CURL = new transferData;
function writeToLog($data, $title = '')
{
	$date  =  date("m.d.y");
	$log =  getcwd() . '/log_' . $date . '.log';
	$log = file_get_contents($log);

	if (strlen($log) > 10000000) {
		$log = substr($log, -500000);
	}
	$log .= "\n------------------------\n";
	$log .= date("Y.m.d G:i:s") . "\n";
	$log .= (strlen($title) > 0 ? $title : 'DEBUG') . "\n";
	$log .= print_r($data, 1);
	$log .= "\n------------------------\n";
	file_put_contents(getcwd() . '/log_' . $date . '.log', $log);
}

$id = $_POST['data']['FIELDS']['ID'];

// Get list of all industries
$industriesFieldsData = array(
	'order' => array("DATE_CREATE"   => "ASC"),
	'filter' => array(
		"ID" => $id,
		"TYPE_ID" => "1",
	),
	'select' => 'UF_CRM_1556038167'
);
$industriesList = $eseence->contactFields($industriesFieldsData);
$industriesArr = $industriesList['result']['UF_CRM_1556038167']['items'];
// Get list of all branches
$branchesFieldsData = array(
	'order' => array("DATE_CREATE"   => "ASC"),
	'filter' => array(
		"ID" => $id,
		"TYPE_ID" => "1",
	),
	'select' => 'UF_CRM_1557069703'
);
$branchesList = $eseence->contactFields($branchesFieldsData);
$branchesArr = $branchesList['result']['UF_CRM_1557069703']['items'];

if ($id > 1) {

	$listData = array(
		'order' => array("DATE_CREATE" => "ASC"),
		'filter' => array(
			"ID" => $id,
			"TYPE_ID" => "1",
		),
		'select' => array("*", "UF_*", "IM")
	);

	$result = $eseence->contactList($listData);
	$result = $result['result'][0];

	$posts = get_posts(array(
		'post_type' => 'speakers',
		'fields' => 'ids',
		'lang' => '',
		'post_status' => 'any',
		'posts_per_page' => -1,
		'meta_key' => 'bitrix_id',
		'meta_value' => $id
	));
	$post = $posts[0];
	// remove old terms
	wp_remove_object_terms($post, NULL, array('industries', 'branches', 'locations', 'companies'));
	$language = 'ru';
	$bitrixID = $result['ID'];
	$position = $result['POST'];
	if ($result['EMAIL']) {
		$email = $result['EMAIL']['VALUE'];
	}
	if ($result['PHONE']) {
		$phone = $result['PHONE']['VALUE'];
	}
	$status = $result['UF_CRM_1557430195898'];
	if ($status == 0) {
		$status = 'publish';
	} else {
		$status = 'draft';
	}
	$addedBy = $result['UF_CRM_1557063787136'];
	if ($result['LAST_NAME'] && (!$result['NAME'])) {
		$title = $result['LAST_NAME'];
	} elseif ($result['NAME'] && !$result['LAST_NAME']) {
		$title = $result['NAME'];
	} else {
		$title = $result['NAME'] . ' ' . $result['LAST_NAME'];
	}
	$middleName = $result['SECOND_NAME'];
	$photo = $result['UF_CRM_1557064358'];
	if (count($result['UF_CRM_1556038351']) > 1) {
		$companiesArr = $result['UF_CRM_1556038351'];
	} else {
		if ($result['UF_CRM_1556038351']) {
			preg_match_all('/([^;]+)/', $result['UF_CRM_1556038351'][0], $companiesArr, PREG_PATTERN_ORDER);
			$companiesArr = $companiesArr[0];
		}
	}
	if (count($result['UF_CRM_1556038385']) > 1) {
		$locationsArr = $result['UF_CRM_1556038385'];
	} else {
		if ($result['UF_CRM_1556038385']) {
			preg_match_all('/([^;]+)/', $result['UF_CRM_1556038385'][0], $locationsArr, PREG_PATTERN_ORDER);
			$locationsArr = $locationsArr[0];
			print_r($locationsArr);
		}
	}
	$person = $result['UF_CRM_1557067491'];
	$biography = $result['UF_CRM_1556017547701'];
	$education = $result['UF_CRM_1556017557354'];
	$life = $result['UF_CRM_1556017566292'];
	$facts = $result['UF_CRM_1556017574865'];
	$quote = $result['UF_CRM_1556017582267'];


	if (array_key_exists('IM', $result)) {
		foreach ($result['IM'] as $item) {
			if ($item['VALUE_TYPE'] == 'FACEBOOK') {
				$facebook = $item['VALUE'];
				continue;
			}
			if ($item['VALUE_TYPE'] == 'INSTAGRAM') {
				$instagram = $item['VALUE'];
				continue;
			}
			if ($item['VALUE_TYPE'] == 'VK') {
				$vk = $item['VALUE'];
				continue;
			}
			if ($item['VALUE_TYPE'] == 'OTHER') {
				if (stripos($item['VALUE'], 'linkedin') !== false) {
					$linkedin = $item['VALUE'];
					continue;
				}
				if (stripos($item['VALUE'], 'youtube') !== false) {
					$youtube = $item['VALUE'];
					continue;
				}
				if (stripos($item['VALUE'], 'twitter') !== false) {
					$twitter = $item['VALUE'];
					continue;
				}
			}
		}
	}


	$additionalInfo = $result['UF_CRM_1556017597242'];

	// Get attached industries
	$industriesAttachedArr = array();
	foreach ($result['UF_CRM_1556038167'] as $industry) {
		foreach ($industriesArr as $s) {
			if ($s['ID'] == $industry) {
				array_push($industriesAttachedArr, $s['VALUE']);
			}
		}
	}

	// Get attached branches
	$additionalBranches = array();

	foreach ($result['UF_CRM_1557472176436'] as $additionalBranch) {
		$additionalBranches[] = $additionalBranch;
	}
	$branchesAttachedArr = array();
	foreach ($result['UF_CRM_1557069703'] as $branch) {
		foreach ($branchesArr as $s) {
			if ($s['ID'] == $branch) {
				array_push($branchesAttachedArr, $s['VALUE']);
			}
		}
	}
	$branchesMerged = array_merge($branchesAttachedArr, $additionalBranches);

	// Create idnustry if it doesn't exist
	$industries = array();
	$industriesExists = array();
	$industriesCreated = array();
	foreach ($industriesAttachedArr as $key) {
		$exists = term_exists($key, 'industries', 0);
		if ($exists) {
			$industryGet = get_term($exists['term_id'], 'industries');
			pll_set_term_language($industryGet->term_id, 'ru');
			$industriesExists[] = $industryGet->name;
		} else {
			$industriesCreatedGet = wp_insert_term($key, 'industries', array('parent' => 0));
			$industriesGet = get_term($industriesCreatedGet['term_id'], 'industries');
			pll_set_term_language($industriesGet->term_id, 'ru');
			$industriesCreated[] = $industriesGet->name;
		}
	}
	$industries = array_merge($industriesCreated, $industriesExists);

	// Branches terms
	$branches = array();
	$branchesExists = array();
	$branchesCreated = array();
	foreach ($branchesMerged as $key) {
		$exists = term_exists($key, 'branches', 0);
		if ($exists) {
			$branchGet = get_term($exists['term_id'], 'branches');
			pll_set_term_language($branchGet->term_id, 'ru');
			$branchesExists[] = $branchGet->name;
		} else {
			$branchesCreatedGet = wp_insert_term($key, 'branches', array('parent' => 0));
			$branchesGet = get_term($branchesCreatedGet['term_id'], 'branches');
			pll_set_term_language($branchesGet->term_id, 'ru');
			$branchesCreated[] = $branchesGet->name;
		}
	}
	$branches = array_merge($branchesCreated, $branchesExists);

	// Copmanies terms
	$companies = array();
	$companiesExists = array();
	$companiesCreated = array();
	foreach ($companiesArr as $key) {
		$exists = term_exists($key, 'companies', 0);
		if ($exists) {
			$companyGet = get_term($exists['term_id'], 'companies');
			pll_set_term_language($companyGet->term_id, 'ru');
			$companiesExists[] = $companyGet->name;
		} else {

			$companiesCreatedGet = wp_insert_term($key, 'companies', array('parent' => 0));
			if (is_wp_error($companiesCreatedGet)) {
				echo ' $companiesCreatedGet' . $companiesCreatedGet->get_error_message() . '<br/>';
				echo $companiesCreatedGet->get_error_code() . '<br/>';
			}
			$companiesGet = get_term($companiesCreatedGet['term_id'], 'companies');
			if (is_wp_error($companiesCreatedGet)) {
				echo '$companiesGet' . $companiesGet->get_error_message() . '<br/>';
				echo $companiesGet->get_error_code() . '<br/>';
			}
			pll_set_term_language($companiesGet->term_id, 'ru');
			$companiesCreated[] = $companiesGet->name;
		}
	}
	$companies = array_merge($companiesCreated, $companiesExists);

	// Locations terms
	$locations = array();
	$locationsExists = array();
	$locationsCreated = array();
	foreach ($locationsArr as $key) {
		$exists = term_exists($key, 'locations', 0);
		if ($exists) {
			$locationGet = get_term($exists['term_id'], 'locations');
			pll_set_term_language($locationGet->term_id, 'ru');
			$locationsExists[] = $locationGet->name;
		} else {
			$locationsCreatedGet = wp_insert_term($key, 'locations', array('parent' => 0));
			$locationsGet = get_term($locationsCreatedGet['term_id'], 'locations');
			pll_set_term_language($locationsGet->term_id, 'ru');
			$locationsCreated[] = $locationsGet->name;
		}
	}
	$locations = array_merge($locationsCreated, $locationsExists);

	// Update Expert
	$updated_post = array(
		'id' => $post,
		'post_title' => $title,
		'post_content' => 'expert from bitrix24',
		'post_status' => $status,
		'post_author' => '1',
		'post_type' => 'speakers',
	);

	wp_update_post(wp_slash($updated_post));
	writeToLog($post, '$expert id');
	writeToLog($bitrixID, '$bitrix id');

	// Update meta fields
	update_post_meta($post, 'expert_author', $addedBy);
	update_post_meta($post, 'expert_skills', $position);
	update_post_meta($post, 'expert_biography', $biography);
	update_post_meta($post, 'expert_middlename', $middleName);
	update_post_meta($post, 'expert_photo', $photo);
	update_post_meta($post, 'expert_person', $person);
	update_post_meta($post, 'expert_education', $education);
	update_post_meta($post, 'expert_life', $life);
	update_post_meta($post, 'expert_facts', $facts);
	update_post_meta($post, 'expert_quote', $quote);
	update_post_meta($post, 'expert_facebook', $facebook);
	update_post_meta($post, 'expert_linkedin', $linkedin);
	update_post_meta($post, 'expert_instagram', $instagram);
	update_post_meta($post, 'expert_twitter', $twitter);
	update_post_meta($post, 'expert_youtube', $youtube);
	update_post_meta($post, 'expert_vk', $vk);
	update_post_meta($post, 'expert_additionalinfo', $additionalInfo);
	update_post_meta($post, 'expert_phone', $phone);
	update_post_meta($post, 'expert_email', $email);
	// Set custom taxonomies
	// Industries
	wp_set_object_terms($post, NULL, 'industries');
	wp_set_object_terms($post, NULL, 'companies');
	wp_set_object_terms($post, NULL, 'branches');
	wp_set_object_terms($post, NULL, 'locations');
	wp_set_object_terms($post, $industries, 'industries');
	wp_set_object_terms($post, $companies, 'companies');
	wp_set_object_terms($post, $branches, 'branches');
	wp_set_object_terms($post, $locations, 'locations');

	// Set photo as thumbnail
	if ($photo) {
		$parse_image_path = array();
		if (preg_match('/id=([^&]+)/', $photo)) {
			preg_match('/id=([^&]+)/', $photo, $parse_image_path);
		}
		$parse_image_url = 'https://docs.google.com/uc?id=' . $parse_image_path[1];
		$image_url        = $parse_image_url;
		$upload_dir       = wp_upload_dir();
		$image_data       = file_get_contents($image_url);
		$unique_file_name = wp_unique_filename($upload_dir['path'], $title);
		$filename         = basename($unique_file_name);

		// Check folder permission and define file location
		if (wp_mkdir_p($upload_dir['path'])) {
			$file = $upload_dir['path'] . '/' . $filename;
		} else {
			$file = $upload_dir['basedir'] . '/' . $filename;
		}
		// Create the image  file on the server
		file_put_contents($file, $image_data);

		// Check image file type
		$wp_filetype = wp_check_filetype($filename, null);

		// Set attachment data
		$attachment = array(
			'post_mime_type' => $wp_filetype['type'],
			'post_title'     => sanitize_file_name($filename),
			'post_content'   => '',
			'post_status'    => 'inherit'
		);

		// Create the attachment
		$attach_id = wp_insert_attachment($attachment, $file, $post);

		// Include image.php
		require_once(ABSPATH . 'wp-admin/includes/image.php');

		// Define attachment metadata
		$attach_data = wp_generate_attachment_metadata($attach_id, $file);

		// Assign metadata to attachment
		wp_update_attachment_metadata($attach_id, $attach_data);

		// And finally assign featured image to post
		set_post_thumbnail($post, $attach_id);
	}
}
