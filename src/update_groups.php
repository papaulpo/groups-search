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

// Liste de tous les groupes
$groups = array();

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
		'children' => [
			'Départements' => [
				'fields' => ['code_insee_region', 'code_insee_dep', 'nom', 'rs_icon', 'nom_rs', 'url', 'latitude', 'longitude'],
				'filepath' => 'groupes_dep.csv',
				'children' => [
					'' => [
					'fields' => ['code_insee_dep', 'nom', 'rs_icon', 'nom_rs', 'url', 'latitude', 'longitude'],
					'filepath' => 'groupes_loc.csv'
					]
				]
			]
		]
	],
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
function update_ssi($groups, $cat_groups) {
	global $datapath;

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
		// tri par nom
		$recs = sort_by_field($records, 'nom');
		// tri par rs_icon
		$sorted_recs = [];
		foreach($recs as $nom => $rec) {
			print_r("--- sorting '$nom'\n");
			print_r($rec);
			$sorted_recs += [$nom => sort_by_field($rec, 'rs_icon')];
			print_r("--- sorted '$nom'\n");
			print_r($sorted_recs);
			print_r("--- fin sorted '$nom'\n");
		}
		$groups += [$cat_name => $sorted_recs];
	}
	return $groups;
}

$groups = update_ssi($groups, $cat_groups);
print_r($groups);

// Remplit le template
ob_start();
include($path . 'templates/groupes.php');
$html = ob_get_clean();
print_r($groups);
$destfile = fopen($rootpath . 'global/ssi/groupes.shtml', 'w');
fwrite($destfile, $html);
fclose($destfile);


































