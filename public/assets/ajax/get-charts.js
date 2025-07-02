$(document).ready(function(){
	$.ajax({
		url:'dashboard/chart',
        type:'POST',
        dataType:'JSON',
		success: function(response) {
			//console.log(response);
			let xValues = [];
			let yValues = [];
			let y2Values = [];

			for(var i in response) {
				xValues.push(response[i].xVal);
				yValues.push(response[i].yVal);
				y2Values.push(response[i].y2Val);
			}
			console.log(xValues, yValues, y2Values);

			const chartdata = {
				labels: xValues,
				datasets : [
					{
						label: 'covoiturages',
						backgroundColor: 'rgba(25, 135, 84, 0.8)', //bootstrap5 success
						borderColor: 'rgba(25, 135, 84 0.8)',
						hoverBackgroundColor: 'rgb(117, 183, 152)', //bootstrap5 sucess-emphasis
						hoverBorderColor: 'rgb(25, 135, 84)',
						data: yValues,
						yAxisID: 'y'
					},
					{
						label: 'crédits',
						backgroundColor: 'rgba(255, 193, 7, 0.8)', //bootstrap5 warning
						borderColor: 'rgba(255, 193, 7, 0.8)', 
						hoverBackgroundColor: 'rgb(255, 218, 106)', //bootstrap5 warning-emphasis
						hoverBorderColor: 'rgb(255, 193, 7)',
						data: y2Values,
						yAxisID: 'y2'
					}
				]
			};

			let chart = $("#adminCharts");

			const barGraphChart = new Chart(chart, {
				type: 'bar',
				data: chartdata,
				options: {
					responsive: true,
					plugins: {
						legend: {
							position: 'top'
						},
						title: {
							display: true,
							text: 'Nombre de covoiturages et revenus des cinq derniers jours'
						}
					},
					scales: {
						y: {
							type: 'linear',
							position: 'left',
							ticks: {
								color: 'rgb(25, 135, 84)'
							}
						},
						y2: {
							type: 'linear',
							position: 'right',
							ticks: {
								color: 'rgb(255, 193, 7)'
							},
							grid: {
								drawOnChartArea: false // gridlines only for the other yAxis
							}
						}
					}
				}
			});
		},
		error: function(response) {
			console.log(response);
		}
	});
});

