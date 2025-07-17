
function getHtml(result)
{
    let i=0;
    let x='';

    x=x+`<div class="table-responsive-sm">
        <table class="table table-condensed cstmTableLite mt-2">`;

    x=x+`<thead>
                <tr>
                    <th>Date/heure</th>
                    <th>Départ</th>
                    <th>Arrivée</th>                   
                </tr>
            </thead>
            <tbody>`;
    
    for(i=0;i<result.length;i++)
    {
        //use backticks `` to avoid concatenations (</td> + <td>)
        x=x+`
            <tr class="accordion-toggle collapsed"
                id="accordionDriverPlanned${i}"
                data-mdb-collapse-init
                data-mdb-parent="#accordionDriverPlanned${i}"
                href="#collapseDriverPlanned${i}"
                aria-controls="collapseDriverPlanned${i}">
                <td>
                    <div class="d-flex flex-wrap justify-content-start">
                        <div>${result[i]['departure_date']}</div>
                        <div class="ms-2">${result[i]['departure_time']}</div>
                    </div>
                </td>
                <td>${result[i]['departure_city']}</td>
                <td>${result[i]['arrival_city']}</td>
            </tr>
            <tr>
                <td colspan="3">
                    <div class="d-flex flex-wrap justify-content-start">
                        <div class="mx-3"><a href="carpools"><button type="button" class="btn btn-success btn-sm">En savoir plus</button></a></div>
                    </div>
                </td>
            </tr>
        `;
    }

    x=x+`</tbody>
        </table>
    </div>`;
    
    return x;
}

function getHtml_noResults()
{
    let x='';
    x=x+`<div class="m-2 error-message">Aucun covoiturages disponibles</div>`;
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
            //console.log(response);
            let html = getHtml(response);
            //alert(html);
            $("#results-carpool-lite").html(html);
        },
        error:function (e) {
            $("#results-carpool-lite").html(getHtml_noResults());
        }
    });


});





