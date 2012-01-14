<?php

require_once ('../../varie.php');

init_geocache ();
global $geocache;

/*
	Per dettagli sul formato del file accettato da OpenLayer.Layer.Text
	http://dev.openlayers.org/apidocs/files/OpenLayers/Layer/Text-js.html
*/
$h = "lat\tlon\ttitle\tdescription\ticonSize\ticonOffset\ticon";
$contents = array ();
$contents ['networking'] = array ($h);
$contents ['sviluppo'] = array ($h);
$contents ['web'] = array ($h);
$contents ['formazione'] = array ($h);
$contents ['consulenza'] = array ($h);

foreach ($elenco_regioni as $region => $name) {
	/*
		I gruppi di carattere nazionale non possono essere messi sulla
		cartina (a meno di piazzare un grosso marker di traverso su
		tutta la nazione, ma non mi sembra il caso...), dunque li salto
	*/
	if ($name == "Italia")
		continue;

        $businesses = file ('../../db/' . $region . '.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
	$cities = file ('liste_comuni/' . $region . '.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
	$found_cities = array ();

        foreach ($businesses as $business) {
		$found = false;
		list ($prov, $city, $name, $site, $mail, $category) = explode ('|', $business);

		$targets = array ();
		$cats = explode (',', $category);

		foreach ($cats as $c) {
			switch ($c) {
				case 'Networking':
				case 'VoIP':
					$targets [] = 'networking';
					break;

				case 'Sviluppo':
					$targets [] = 'sviluppo';
					break;

				case 'Web':
					$targets [] = 'web';
					break;

				case 'Formazione':
					$targets [] = 'formazione';
					break;

				case 'Consulenza':
					$targets [] = 'consulenza';
					break;
			}
		}

		foreach ($cities as $c) {
			if ($city == $c) {
				$c = str_replace (' ', '%20', $city);

				$result = ask_coordinates ($c);
				if ($result == null)
					continue;

				list ($lat, $lon) = $result;
				$lon = shift_city ($city, $lon, $found_cities);
				$found_cities [] = $city;

				foreach ($targets as $t) {
					$rows = &$contents [$t];
					$rows [] = "$lat\t$lon\t$name\t<a href=\"$site\">$site</a>\t16,19\t-8,-19\thttp://businessmap.it/images/icon.png";
				}

				$found = true;

				break;
			}
		}

		if ($found == false)
			echo "Impossibile gestire la zona '$city', si consiglia l'analisi manuale\n";
	}
}

foreach ($contents as $name => $rows)
	write_geo_file ('dati_' . $name . '.txt', $rows);

save_geocache ();

?>
