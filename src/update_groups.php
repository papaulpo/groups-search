<?php

/**
 * Récupère data depuis Framacalcs (PAS FAIT MAIS HYPER FACILE À FAIRE, SI ON VEUT)
 * Attention : ne pas mettre l'url du framacalc en clair dans les fichiers - utiliset secret github
 * Format DU secret : (à venir)
 *
 */
//$arguments = array_slice($argv, 1);
//var_dump($arguments);

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
		'fields' => ['rs_icon', 'nom', 'nom_rs', 'url'],
		'filepath' => 'groupes_monde.csv'
	],
	'Autres groupes' => [
		'fields' => ['rs_icon', 'nom', 'nom_rs', 'url'],
		'filepath' => 'groupes_autres.csv'
	],
	'Régions' => [
		'fields' => ['code_insee_region', 'rs_icon', 'nom', 'nom_rs', 'url', 'latitude', 'longitude'],
		'filepath' => 'groupes_reg.csv',
		'children' => [
			'Départements' => [
				'fields' => ['code_insee_region', 'code_insee_dep', 'rs_icon', 'nom', 'nom_rs', 'url', 'latitude', 'longitude'],
				'filepath' => 'groupes_dep.csv',
				'children' => [
					'' => [
					'fields' => ['code_insee_dep', 'rs_icon', 'nom', 'nom_rs', 'url', 'latitude', 'longitude'],
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
		if ($fields != $cat_array[0]) {
			throw new Exception("\nFichier CSV non conforme: " . $cat_description['filepath'] . '.' .
				"\nFormat attendu: [" . implode(', ', $cat_description['fields']) . "]\n");
		}
		$cat_array = array_slice($cat_array, 1);
		$nb_fields = count($fields);
		$nb_records = count($cat_array);
		$records = [];
		for ($i = 0; $i < $nb_records; $i++) {
			$ca = [];
			for ($j = 0; $j < $nb_fields; $j++) {
				$ca += [$fields[$j] => $cat_array[$i][$j]];
			}
			print_r($ca);
			$records += [$i => $ca];
		}
		$groups += [$cat_name => $records];
	}
	return $groups;
}


$groups = update_ssi($groups, $cat_groups);

ob_start();
include($path . 'templates/groupes.php');
$html = ob_get_clean();

$destfile = fopen($rootpath . 'global/ssi/groupes.html', 'w');
print_r($html);
fclose($destfile);


































