
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

function start($start = false)
{
	$listData = array(
		'order' => array("DATE_CREATE" => "ASC"),
		'filter' => array("TYPE_ID" => 1),
		'select' => array("*", "UF_*", "IM")
	);
	if ($start > 1) {
		$listData['start'] = $start;
	}
	return $listData;
}

$result = array();
$next = false;
do {
	usleep(2000);
	$data = $eseence->contactList(start($next));
	$result = array_merge($result, $data['result']);
	$next = $data['next'];
} while ($data['next'] > 1);
// Check expert existing
$args = array(
	'post_type' => 'speakers',
	'lang' => '',
	'post_status' => 'any',
	'posts_per_page' => -1,
	'meta_key' => 'bitrix_id',
);

$allExperts = get_posts($args);
$expertCheck = array();

foreach ($allExperts as $p) {
	$expertCheck[] = get_field('bitrix_id', $p->ID);
}

// Get list of all industries
$industriesFieldsData = array(
	'order' => array("DATE_CREATE" => "ASC"),
	'filter' => array(
		"TYPE_ID" => "1",
	),
	'select' => 'UF_CRM_1556038167'
);
$industriesList = $eseence->contactFields($industriesFieldsData);
$industriesArr = $industriesList['result']['UF_CRM_1556038167']['items'];

// Get list of all branches
$branchesFieldsData = array(
	'order' => array("DATE_CREATE" => "ASC"),
	'filter' => array(
		"TYPE_ID" => "1",
	),
	'select' => 'UF_CRM_1557069703'
);
$branchesList = $eseence->contactFields($branchesFieldsData);
$branchesArr = $branchesList['result']['UF_CRM_1557069703']['items'];

foreach ($result as $value) {
	// Get Expert's taxonomies
	if (!in_array($value['ID'], $expertCheck)) {
		echo $value['ID'] . '<br/>';
		$language = 'ru';
		$bitrixID = $value['ID'];
		$position = $value['POST'];
		if ($value['EMAIL']) {
			$email = $value['EMAIL']['VALUE'];
		}
		if ($value['PHONE']) {
			$phone = $value['PHONE']['VALUE'];
		}
		$status = $value['UF_CRM_1557430195898'];
		if ($status == 0) {
			$status = 'publish';
		} else {
			$status = 'draft';
		}
		$addedBy = $value['UF_CRM_1557063787136'];
		if ($value['LAST_NAME'] && (!$value['NAME'])) {
			$title = $value['LAST_NAME'];
		} elseif ($value['NAME'] && !$value['LAST_NAME']) {
			$title = $value['NAME'];
		} else {
			$title = $value['NAME'] . ' ' . $value['LAST_NAME'];
		}
		$middleName = $value['SECOND_NAME'];
		$photo = $value['UF_CRM_1557064358'];
		// if (count($value['UF_CRM_1556038351']) > 1) {
		// 	$companiesArr = $value['UF_CRM_1556038351'];
		// } else {
		// 	if($value['UF_CRM_1556038351']) {
		// 		preg_match_all('/([^;]+)/', $value['UF_CRM_1556038351'][0], $companiesArr, PREG_PATTERN_ORDER);
		// 		$companiesArr = $companiesArr[0];
		// 	}
		// }
		if (count($value['UF_CRM_1556038385']) > 1) {
			$locationsArr = $value['UF_CRM_1556038385'];
		} else {
			if ($value['UF_CRM_1556038385']) {
				preg_match_all('/([^;]+)/', $value['UF_CRM_1556038385'][0], $locationsArr, PREG_PATTERN_ORDER);
				$locationsArr = $locationsArr[0];
				print_r($locationsArr);
			}
		}
		$person = $value['UF_CRM_1557067491'];
		$biography = $value['UF_CRM_1556017547701'];
		$education = $value['UF_CRM_1556017557354'];
		$life = $value['UF_CRM_1556017566292'];
		$facts = $value['UF_CRM_1556017574865'];
		$quote = $value['UF_CRM_1556017582267'];

		// Get socials
		if (array_key_exists('IM', $value)) {
			foreach ($value['IM'] as $item) {
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


		$additionalInfo = $value['UF_CRM_1556017597242'];

		// Get attached industries
		$industriesAttachedArr = array();
		foreach ($value['UF_CRM_1556038167'] as $industry) {
			foreach ($industriesArr as $s) {
				if ($s['ID'] == $industry) {
					array_push($industriesAttachedArr, $s['VALUE']);
				}
			}
		}

		// Get attached branches
		$additionalBranches = array();

		foreach ($value['UF_CRM_1557472176436'] as $additionalBranch) {
			$additionalBranches[] = $additionalBranch;
		}
		$branchesAttachedArr = array();
		foreach ($value['UF_CRM_1557069703'] as $branch) {
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
		// $companies = array();
		// $companiesExists = array();
		// $companiesCreated = array();
		// foreach ($companiesArr as $key) {
		// 	$exists = term_exists( $key, 'companies', 0 );
		// 	if ( $exists ) {
		// 		$companyGet = get_term($exists['term_id'], 'companies');
		// 		pll_set_term_language($companyGet->term_id, 'ru');
		// 		$companiesExists[] = $companyGet->name;
		// 	} else {

		// 		$companiesCreatedGet = wp_insert_term( $key, 'companies', array( 'parent' => 0 ) );
		// 		if(is_wp_error($companiesCreatedGet)) {
		// 			echo ' $companiesCreatedGet' . $companiesCreatedGet->get_error_message() . '<br/>';
		// 			echo $companiesCreatedGet->get_error_code() . '<br/>';
		// 		}
		// 		$companiesGet = get_term($companiesCreatedGet['term_id'], 'companies');
		// 		if(is_wp_error($companiesCreatedGet)) {
		// 			echo '$companiesGet' . $companiesGet->get_error_message() . '<br/>';
		// 			echo $companiesGet->get_error_code() . '<br/>';
		// 		}
		// 		pll_set_term_language($companiesGet->term_id, 'ru');
		// 		$companiesCreated[] = $companiesGet->name;
		// 	}
		// }
		// $companies = array_merge($companiesCreated, $companiesExists);

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

		// Create Expert
		$new_post = array(
			'post_title' => $title,
			'post_content' => 'expert from bitrix24',
			'post_status' => $status,
			'post_author' => '1',
			'post_type' => 'speakers',
			'meta_input' => array(
				'expert_author' => ($addedBy ? $addedBy : ''),
				'expert_skills' => ($position ? $position : ''),
				'expert_biography' => ($biography ? $biography : ''),
				'expert_middlename' => ($middleName ? $middleName : ''),
				'expert_photo' => ($photo ? $photo : ''),
				'expert_person' => ($person ? $person : ''),
				'expert_education' => ($education ? $education : ''),
				'expert_life' => ($life ? $life : ''),
				'expert_facts' => ($facts ? $facts : ''),
				'expert_quote' => ($quote ? $quote : ''),
				'expert_facebook' => ($facebook ? $facebook : ''),
				'expert_linkedin' => ($linkedin ? $linkedin : ''),
				'expert_instagram' => ($instagram ? $instagram : ''),
				'expert_twitter' => ($twitter ? $twitter : ''),
				'expert_youtube' => ($youtube ? $youtube : ''),
				'expert_vk' => ($vk ? $vk : ''),
				'expert_additionalinfo' => ($additionalInfo ? $additionalInfo : ''),
				'bitrix_id' => ($bitrixID ? $bitrixID : ''),
				'expert_phone' => ($phone ? $phone : ''),
				'expert_email' => ($email ? $email : ''),
			),
		);
		$post_id = wp_insert_post($new_post);
		// Set expert to attached language
		pll_set_post_language($post_id, 'ru');
		// Set custom taxonomies
		// Industries
		wp_set_object_terms($post_id, $industries, 'industries');
		// wp_set_object_terms($post_id, $companies, 'companies');
		wp_set_object_terms($post_id, $branches, 'branches');
		wp_set_object_terms($post_id, $locations, 'locations');

		// Set photo as thumbnail
		if ($photo) {
			$parse_image_path = array();
			if (preg_match('/id=([^&]+)/', $photo)) {
				preg_match('/id=([^&]+)/', $photo, $parse_image_path);
			}
			$parse_image_url = 'https://docs.google.com/uc?id=' . $parse_image_path[1];
			$image_url        = $parse_image_url;
			$image_name       = 'expert-photo.png';
			$upload_dir       = wp_upload_dir();
			$image_data       = file_get_contents($image_url);
			$unique_file_name = wp_unique_filename($upload_dir['path'], $image_name);
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
			$attach_id = wp_insert_attachment($attachment, $file, $post_id);

			// Include image.php
			require_once(ABSPATH . 'wp-admin/includes/image.php');

			// Define attachment metadata
			$attach_data = wp_generate_attachment_metadata($attach_id, $file);

			// Assign metadata to attachment
			wp_update_attachment_metadata($attach_id, $attach_data);

			// And finally assign featured image to post
			set_post_thumbnail($post_id, $attach_id);
		}
	} else {
		echo 'exist ' . $value['ID'] . '<br/>';
	}
}
?>