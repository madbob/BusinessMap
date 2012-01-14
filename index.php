<?php

if ($_SERVER ['HTTP_HOST'] != 'businessmap.it' && $_SERVER ['HTTP_HOST'] != 'www.businessmap.it') {
	$domain = explode ('.', $_SERVER ['HTTP_HOST']);
	$host = $domain [0];

	switch ($host) {
		default:
			include ('visualizza-regione.php');
			break;
	}

	exit (0);
}

require_once ('varie.php');
do_head ('Homepage', array ('http://openlayers.org/api/OpenLayers.js', 'js/mappa.js'));

$transformed = false;

if (array_key_exists ('zoom', $_GET)) {
	$found = false;
	$contents = file ('dati.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

	foreach ($contents as $row) {
		list ($lat, $lon, $lug, $useless) = explode ("\t", $row, 4);
		if ($lug == $_GET ['zoom']) {
			$found = true;
			break;
		}
	}

	if ($found == true) {
		$transformed = true;

		?>

		<input type="hidden" name="zooming_lat" value="<?php echo $lat ?>" />
		<input type="hidden" name="zooming_lon" value="<?php echo $lon ?>" />
		<input type="hidden" name="default_zoom" value="12" />

		<?php
	}
}

if ($transformed == false) {
	?>
	<input type="hidden" name="default_zoom" value="6" />
	<?php
}

?>

<input type="hidden" name="networking_coords_file" value="dati_networking.txt" />
<input type="hidden" name="sviluppo_coords_file" value="dati_sviluppo.txt" />
<input type="hidden" name="web_coords_file" value="dati_web.txt" />
<input type="hidden" name="formazione_coords_file" value="dati_formazione.txt" />
<input type="hidden" name="consulenza_coords_file" value="dati_consulenza.txt" />

<div id="map" class="smallmap"></div>

<div class="filters">
	<input type="radio" name="type" value="networking"> Networking<br />
	<input type="radio" name="type" value="sviluppo"> Sviluppo Software<br />
	<input type="radio" name="type" value="web"> Web<br />
	<input type="radio" name="type" value="formazione"> Formazione<br />
	<input type="radio" name="type" value="consulenza"> Consulenza<br />
	<input type="radio" name="type" value="tutti" checked="checked"> Tutti
</div>

<?php do_foot (); ?>
