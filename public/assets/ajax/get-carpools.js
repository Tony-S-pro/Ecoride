
function getHtml(result)
{
    let i=0;
    let x='';
    let smoking='';
    let animals='';

    //x=x+`<div><button class="btn btn-warning btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#myModal" id="btn-AddNew">Ajouter</button></div>`;
    x=x+`<div class="table-responsive">
        <table class="table table-responsive table-condensed table-striped mt-2">`;

    x=x+`<thead>
                <tr>
                    <th>Départ</th>
                    <th></th>
                    <th></th>
                    <th>Arrivée</th>
                    <th>Prix</th>
                    <th></th>
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
            <td>${result[i]['departure_date']}</td>
            <td>${result[i]['departure_time']}</td>
            <td>${result[i]['departure_city']}</td>
            <td>${result[i]['arrival_city']}</td>
            <td>${result[i]['price']}</td>
            <td><button type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo${i}" aria-expanded="false" aria-controls="collapseTwo${i}" class="btn btn-success btn-sm">Détails</button></td>
            </tr>

            <tr class="hide-table-padding">
                <td colspan="6"=>
                    <div id="collapseTwo${i}" class="collapse">
                        <div class="p-3">
                            <div>adr. dép. : ${result[i]['departure_address']}</div>
                            <div>adr. arr. : ${result[i]['arrival_address']}</div>
                            <div>durée (est.) : ${result[i]['travel_time']}h</div>
                            <div>4,5/5 *, ${result[i]['price']} crédits, 3 places</div>
                            <div>description : ${result[i]['description']} je rajoute du texte pour voir comment ca afect tout ca rt tout et tou.</div>
                            <div>animaux: ${animals}, fumeur ${smoking}</div>
                            <div>autres :${result[i]['misc']}</div>
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
            "search_address1": $('input[name=search_address2]').val(),
            //"checkEco": $('input[name=checkEco]').val(),
            "search_date": $('input[name=search_date]').val()
        },

        success:function(response) {
            let x = JSON.stringify(response);
            //$('form').get(0).reset();
            //alert(x);
            //console.log(x);
            let html = getHtml(response);
            //alert(html);
            $("#results-carpool").html(html);
        }
    });


});

// $(document).on("click", "#btn-AddNew", function() {
//     //alert('feik,pioef,k');
//     $("#myModal").modal('show');
    
// });





