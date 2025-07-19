
function getHtml(result)
{
    let i=0;
    let x='';
    let smoking='';
    let animals='';

    //x=x+`<div><button class="btn btn-warning btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#myModal" id="btn-AddNew">Ajouter</button></div>`;
    x=x+`<div class="table-responsive-sm">
        <table class="table table-condensed cstmTable mt-2">`;

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
        
        result[i]['smoking'] ? smoking='OUI' : smoking='NON';
        result[i]['animals'] ? animals='OUI' : animals='NON';
        //use backticks `` to avoid concatenations (</td> + <td>)
        x=x+`
            <tr class="accordion-toggle collapsed"
                id="accordionTwo${i}"
                data-mdb-collapse-init
                data-mdb-parent="#accordionTwo${i}"
                href="#collapseTwo${i}"
                aria-controls="collapseTwo${i}">
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
                        <div class="mx-3"><button type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo${i}" aria-expanded="false" aria-controls="collapseTwo${i}" class="btn btn-outline-success btn-sm">Détails</button></div>
                        <div class="mx-3"><a href="carpools/details/${result[i]['id']}"><button type="button" class="btn btn-warning btn-sm">Réserver</button></a></div>                        
                    </div>
                </td>
            </tr>

            <tr class="hide-table-padding">
                <td colspan="3">
                    <div id="collapseTwo${i}" class="collapse">
                        <div class="p-3">
                            <div><strong>adr. dép. :</strong> ${result[i]['departure_address']}</div>
                            <div><strong>adr. arr. :</strong> ${result[i]['arrival_address']}</div>
                            <div><strong>durée (est.) :</strong> ${result[i]['travel_time']}h</div>                            
                            <div><strong>description :</strong> ${result[i]['description']}</div>
                            <div><strong>prix :</strong> ${result[i]['price']} Crédit(s)</div>
                            <div><strong>note chauffeur :</strong> ${result[i]['avg_rating']}/5 (${result[i]['ratings_nbr']} avis)</div>
                            <div><strong>sièges dispo :</strong> ${result[i]['remaining_seats']}</div>
                            <div><strong>animaux:</strong> ${animals}, <strong>fumeur :</strong> ${smoking}</div>
                            <div><strong>autres :</strong> ${result[i]['misc']}</div>
                        </div>
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


$('#searchForm-full').on('submit', function(e) {

    e.preventDefault();

    $.ajax({
        url:'carpools',
        type:'POST',
        dataType:'JSON',

        data:{
            "search_city1": $('input[name=search_city1]').val(),
            "search_city2": $('input[name=search_city2]').val(),
            "search_address1": $('input[name=search_address1]').val(),
            "search_address2": $('input[name=search_address2]').val(),
            "search_date": $('input[name=search_date]').val(),
            "minRating": $('input[name=minRating]').val(),
            "maxPrice": $('input[name=maxPrice]').val(),
            "maxTime": $('input[name=MaxTime]').val(),
            "checkEco": $('input[name=checkEco]').prop('checked')
        },

        success:function(response) {
            
            let x = JSON.stringify(response);
            //$("#checkEco").prop( "checked", false ); //uncheck the box
            //$('form').get(0).reset();
            //alert(x);
            //console.log(x);
            if (x = null) {
                $("#results-carpool").html(getHtml_noResults());
            } else {

                let html = getHtml(response);
                $("#results-carpool").html(html);
            }            
        },
        error:function (e) {
            $("#results-carpool").html(getHtml_noResults());
        }
    });


});





