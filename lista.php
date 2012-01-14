<?php

require_once ('varie.php');
do_head ('Lista completa delle aziende', array ('js/jquery.dataTables.min.js', 'js/lista.js'));

?>

<div>
	<?php

	$db_regione = array ();

	foreach ($elenco_regioni as $shortfile => $name) {
		$file = file ("./db/${shortfile}.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

		foreach ($file as &$row)
			$row = "$shortfile|$name|$row";

		$db_regione = array_merge ($db_regione, $file);
	}

	sort ($db_regione);

	?>

	<div class="description">
		<p>
			Ci sono <?php echo count ($db_regione); ?> aziende opensource in Italia.
		</p>

		<p>
			Probabilmente, almeno una di queste è vicina al tuo ufficio.
		</p>
	</div>

	<table id="lugListTable">
		<thead>
			<tr>
				<th>Regione</th>
				<th>Provincia</th>
				<th>Città</th>
				<th>Denominazione</th>
				<th>Keywords</th>
			</tr>
		</thead>

		<tfoot>
			<tr>
				<th>Regione</th>
				<th>Provincia</th>
				<th>Città</th>
				<th>Denominazione</th>
				<th>Keywords</th>
			</tr>
		</tfoot>

		<tbody>
			<?php

			$regione_riferimento = "";

			foreach ($db_regione as $linea):
				list ($shortregion, $regione, $province, $city, $denominazione, $web, $mail, $category) = explode("|",$linea);
				?>

				<tr>
					<td class="region"><a href="http://<?php echo $shortregion ?>.businessmap.it/"><?php echo $regione ?></a></td>
					<td class="province"><?php echo $province ?></td>
					<td class="zone"><?php echo $city ?></td>
					<td class="contactUrl"><a href="<?php echo $web?>"><?php echo $denominazione ?></a></td>
					<td class="category"><?php echo join (', ', explode (',', $category)) ?></td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>

<?php
do_foot ();
?>
