$(document).ready(function(){
	$.ajax({
		url:'dashboard/chart/carpools',
        type:'POST',
        dataType:'JSON',
		success: function(response) {
			console.log(response);
			let xValues = [];
			let yValues = [];

			for(var i in response) {
				xValues.push(response[i].xVal);
				yValues.push(response[i].yVal);
			}
			console.log(xValues, yValues);

			const chartdata = {
				labels: xValues,
				datasets : [
					{
						label: 'Nombre de covoiturages',
						backgroundColor: 'rgba(255, 193, 7, 0.8)', //bootstrap5 warning
						borderColor: 'rgba(255, 193, 7, 0.8)', //bootstrap5 light
						hoverBackgroundColor: 'rgb(255, 218, 106)', //bootstrap5 warning-emphasis
						hoverBorderColor: 'rgb(255, 193, 7)',
						data: yValues
					}
				]
			};

			let chart = $("#adminChart-carpools");

			const barGraphChart = new Chart(chart, {
				type: 'bar',
				data: chartdata
			});
		},
		error: function(response) {
			console.log(response);
		}
	});
});

