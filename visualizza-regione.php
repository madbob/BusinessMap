<?php

require_once ('varie.php');

if (array_key_exists ('region', $_GET)) {
	$regione_richiesta = $_GET ['region'];
}
else {
	# leggo il terzo livello per recuperare la regione richiesta
	$livelli_del_dominio = explode('.', $_SERVER['HTTP_HOST']);
	$regione_richiesta = $livelli_del_dominio[0];
}

if (array_key_exists ($regione_richiesta, $elenco_regioni)) {
	$regione = $elenco_regioni[$regione_richiesta];
	$db_file = "$regione_richiesta.txt";
	$db_regione = file ("./db/$db_file");
	$title = 'Tutte le aziende opensource nella regione ' . $regione;
}
else {
	header("location: " . $main_url);
}

do_head ($title);

?>

<h1><?php echo $title; ?></h1>

<div id="center">
	<table id="lugListTable">
		<thead>
			<tr>
				<th>Provincia</th>
				<th>Denominazione</th>
				<th>Sito</th>
				<th>Keywords</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th>Provincia</th>
				<th>Denominazione</th>
				<th>Sito</th>
				<th>Keywords</th>
			</tr>
		</tfoot>
		<tbody>
			<?php

			while (list ($nriga, $linea) = each ($db_regione)):
				list ($province, $city, $denominazione, $web, $mail, $catogory) = explode ("|", $linea);

				?>
				<tr class="row_<?php echo ($nriga % 2); ?>">
					<td class="province"><?php echo $province ?></td>
					<td><?php echo $denominazione ?></td>
					<td class="contactUrl"><a href="<?php echo $web?>"><?php echo $web ?></a></td>
					<td class="category"><?php echo join (', ', explode (',', $category)) ?></td>
				</tr>

			<?php endwhile;?>
		</tbody>
	</table>

	<div class="region_options">
		<?php if ($db_file != null): ?>
		<a href="http://github.com/madbob/BusinessMap/tree/master/db/<?php echo $db_file ?>">&raquo; Dati in Formato CSV</a>
		<?php endif; ?>
	</div>
</div>

<?php do_foot (); ?>
