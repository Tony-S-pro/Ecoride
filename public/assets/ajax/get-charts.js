$(document).ready(function(){
	$.ajax({
		url:'dashboard/chart/carpools',
        type:'POST',
        dataType:'JSON',
		success: function(data) {
			console.log(data);
			let xValues = [];
			let yValues = [];

			for(var i in data) {
				xValues.push(data[i].xVal);
				yValues.push(data[i].yVal);
			}

			const chartdata = {
				labels: xValues,
				datasets : [
					{
						label: 'Nombre de covoiturages',
						backgroundColor: 'rgba(0, 0, 0, 0.75)',
						borderColor: 'rgba(200, 200, 200, 0.75)',
						hoverBackgroundColor: 'rgb(0, 250, 50)',
						hoverBorderColor: 'rgb(45, 216, 205)',
						data: yValues
					}
				]
			};

			const chart = $("#adminChart");

			const barGraphChart = new Chart(chart, {
				type: 'bar',
				data: chartdata
			});
		},
		error: function(data) {
			console.log(data);
		}
	});
});