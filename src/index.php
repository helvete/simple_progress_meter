<?php
	if ($fileData = file_get_contents('./data.txt')) {
		$datei = [];
		foreach (explode("\n", $fileData) as $line) {
			if (empty(trim($line))) {
				break;
			}
			list($y, $x, $label) = explode("|", $line);
			$datei[] = [
				'x' => (new \DateTime($x))->format('Y-m-d\TH:i:s'),
				'y' => (int)$y,
				'label' => trim($label),
			];
		}
		$printData = json_encode($datei);
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
		<script type="text/javascript" src="https://code.highcharts.com/highcharts.js"></script>
		<script type="text/javascript">
			var serdat = <?php echo $printData; ?>

			var cumulativeData = [];
			var sum = 0;
			for (var inc = 0; inc < serdat.length; inc++) {
				serdat[inc].x = new Date(serdat[inc].x);
				sum += serdat[inc].y
				var cumEl = {
					x: serdat[inc].x,
					y: sum,
					label: 'total'
				};
				cumulativeData.push(cumEl);
			}

			$(function () {
				$('#chart').highcharts({
					chart: {
						type: 'line',
						marginRight: 20,
						marginBottom: 25
					},
					credits: {
						enabled: false
					},
					title: {
						text: 'Progress meter',
						x: -20
					},
					yAxis: {
						title: {
							text: 'Amount'
						},
						plotLines: [{
							value: 0,
							width: 1,
							color: '#808080'
						}]
					},
					xAxis: {
							type: 'datetime',
					},
					tooltip: {
						formatter: function() {
							return this.point.label + "<br /><b>" +
								Highcharts.numberFormat(this.y, 2) + '</b><br/>' +
								Highcharts.dateFormat('%e.%b %y', this.point.x);
						}
					},
					legend: {
						layout: 'vertical',
						align: 'right',
						verticalAlign: 'top',
						x: 0,
						y: 0,
						borderWidth: 1
					},
					series: [
						{
							name: 'Amount',
							type: 'column',
							data: serdat
						},
						{
							name: 'Total',
							data: cumulativeData
						},
					]
				});
			});
		</script>
	</head>
	<body>
	<div id="chart" style="width = 95%; height: 450px; background-color: gray;"></div>
	<center>
		<br />
		Simple progress meter since 2019-12-26. Today is <?= (new \DateTime())->format("Y-m-d"); ?>
	</center>
	</body>
</html>
