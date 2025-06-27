
function getHtml(result)
{
    let i=0;
    let x='';

    x=x+`<div class="table-responsive">
        <table class="table table-responsive table-condensed table-striped mt-2">`;

    x=x+`<thead>
                <tr>
                    <th>Date</th>
                    <th>Départ</th>
                    <th>Arrivée</th>
                    <th>Heure</th>
                    <th>Durée (est.)</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>`;
    
    for(i=0;i<result.length;i++)
    {
        //use backticks `` to avoid concatenations (</td> + <td>)
        x=x+`
            <tr class="accordion-toggle collapsed"
                id="accordion${i}"
                data-mdb-collapse-init
                data-mdb-parent="#accordion${i}"
                href="#collapse${i}"
                aria-controls="collapse${i}">
            <td>${result[i]['departure_date']}</td>
            <td>${result[i]['departure_city']}</td>
            <td>${result[i]['arrival_city']}</td>
            <td>${result[i]['departure_time']}</td>
            <td>${result[i]['travel_time']}h</td>
            <td><a href="carpools"><button type="button" class="btn btn-success btn-sm">Détails</button></a></td>
            </tr>
        `;
    }

    x=x+`</tbody>
        </table>
    </div>`;
    
    return x;
}


$('#searchForm-lite').on('submit', function(e) {

    e.preventDefault();

    $.ajax({
        url:'home',
        type:'POST',
        dataType:'JSON',

        data:{
            "search_city1": $('input[name=search_city1]').val(),
            "search_city2": $('input[name=search_city2]').val(),
            "search_date": $('input[name=search_date]').val()
        },

        success:function(response) {
            let x = JSON.stringify(response);
            //alert(x);
            //console.log($('input[name=search_date]').val());
            let html = getHtml(response);
            //alert(html);
            $("#results-carpool-lite").html(html);
        }
    });


});





