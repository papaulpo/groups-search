<?php
error_reporting(E_ALL);
/**
 * Génère la liste des groupes
 */

// Chemin absolu du présent script
$path = dirname(__file__) .'/';

// Chemin absolu du site
$rootpath = dirname($path) .'/';

// Chemin absolu des données
$datapath = $path . 'data/';

// Nom du RS selon rs_icon
$rsFromIcon = [
	'bluesky' => 'Bluesky',
	'facebookp' => 'Facebook (pages)',
	'facebookg' => 'Facebook (groupes)',
	'instagram' => 'Instagram',
	'piaille' => 'Piaille',
	'signal' => 'Signal',
	'telegram' => 'Telegram',
	'tiktok' => 'TikTok',
	'twitter' => 'X (Twitter)',
];

// Catégories de groupes
$cat_groups = [
	'Réseaux sociaux' => [
		'fields' => ['rs_icon', 'nom_rs', 'url'],
		'filepath' => 'groupes_nat.csv'
	],
	'Monde' => [
		'fields' => ['nom', 'rs_icon', 'nom_rs', 'url'],
		'filepath' => 'groupes_monde.csv'
	],
	'Autres groupes' => [
		'fields' => ['nom', 'rs_icon', 'nom_rs', 'url'],
		'filepath' => 'groupes_autres.csv'
	],
	'Régions' => [
		'fields' => ['code_insee_region', 'nom', 'rs_icon', 'nom_rs', 'url', 'latitude', 'longitude'],
		'filepath' => 'groupes_reg.csv',
		'child' => ['Départements' => 'code_insee_region']
	],
	'Départements' => [
		'fields' => ['code_insee_region', 'code_insee_dep', 'nom', 'rs_icon', 'nom_rs', 'url', 'latitude', 'longitude'],
		'filepath' => 'groupes_dep.csv',
		'ischild' => true,
		'child' => ['' => 'code_insee_dep']
	],
	'' => [
		'fields' => ['code_insee_dep', 'nom', 'rs_icon', 'nom_rs', 'url', 'latitude', 'longitude'],
		'filepath' => 'groupes_loc.csv',
		'ischild' => true,
	]
];

/**
 * Lit fichier CSV dans un tableau
 */
function readCSV($csvFilename) {
	$file = fopen($csvFilename, 'r');
	while (($line = fgetcsv($file)) !== FALSE) {
		$array[] =$line;
	}
	fclose($file);
	return $array;
}

/**
 * Retourne un tableau trié par $field
 *
 * @param array $array
 * @param string $field
 * @return array
 */
function sort_by_field($array, $field) {
	$result = [];
	$i = 0;
	foreach ($array as $rec) {
		$field_value = isset($rec[$field]) ? $rec[$field] : '';
		unset($rec[$field]);
		$result[$field_value][$i] = $rec;
		$i++;
	}
	return $result;
}

/**
 * Retourne un tableau structuré pour la génération du html
 *
 * @param array $groups
 * @param array $cat_groups
 * @return array
 */
function load_groups($cat_groups) {
	global $datapath;

	$groups = [];
	foreach ($cat_groups as $cat_name => $cat_description ) {
		$cat_array = readCSV($datapath . $cat_description['filepath']);
		$fields = $cat_description['fields'];

		// vérif structure des fichiers CSV
		if (($fields != $cat_array[0]) ){
			throw new Exception("\nFichier CSV non conforme: " . $cat_description['filepath'] . '.' .
				"\nFormat attendu: [" . implode(', ', $cat_description['fields']) . "]\n");
			exit();
		}
		// On enlève les noms des champs (1ère ligne du CSV)
		$cat_array = array_slice($cat_array, 1);
		$nb_fields = count($fields);
		$nb_records = count($cat_array);
		$records = [];
		// On lit tous les enregistrements de la catégorie de groupes
		// dans un tableau structuré avec les noms des champs:
		for ($i = 0; $i < $nb_records; $i++) {
			$ca = [];
			for ($j = 0; $j < $nb_fields; $j++) {
				$ca += [$fields[$j] => $cat_array[$i][$j]];
			}
			$records += [$i => $ca];
		}
		$groups += [$cat_name => $records];
	}
	return $groups;
}

/**
 *  Trie les groupes et y ajoute les enfants
 *
 * @param array $cat_groups
 * @param array $groups
 * @return array
 */
function sort_groups($cat_groups, $groups) {
	$sorted_groups = [];
	foreach ($cat_groups as $cat_name => $cat_description ) {
		if (isset($cat_description['ischild']))
			break;
		// tri par nom
		$records = sort_by_field($groups[$cat_name], 'nom');
		// tri par rs_icon
		$sorted_recs = [];
		foreach($records as $nom => $rec) {
			$sorted_recs += [$nom => sort_by_field($rec, 'rs_icon')];
		}
		$sorted_groups += [$cat_name => $sorted_recs];
	}
	return $sorted_groups;
}

$records = load_groups($cat_groups);
$groups = sort_groups($cat_groups, $records);
print_r($groups);

// Remplit le template
ob_start();
include($path . 'templates/groupes.php');
$html = ob_get_clean();

$destfile = fopen($rootpath . 'global/ssi/groupes.shtml', 'w');
fwrite($destfile, $html);
fclose($destfile);


