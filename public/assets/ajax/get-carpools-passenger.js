
function getHtmlPast(result)
{
    let i=0;
    let x='';
    let smoking='';
    let animals='';
    let reviewBtn='';

    x=x+`<div class="table-responsive-sm">
        <table class="table table-condensed cstmTable mt-2">`;

    x=x+`<thead>
                <tr>
                    <th>Date/Heure</th>
                    <th>Départ</th>
                    <th>Arrivée</th>
                </tr>
            </thead>
            <tbody>`;
    
    for(i=0;i<result.length;i++)
    {
        
        result[i]['smoking'] ? smoking='OUI' : smoking='NON';
        result[i]['animals'] ? animals='OUI' : animals='NON';        
        result[i]['reviewed'] ? reviewBtn='' : (reviewBtn=`<div class="mx-3"><a href="review/passenger/${result[i]['id']}"><button type="button" class="btn btn-warning btn-sm">Avis</button></a></div>`);
        //use backticks `` to avoid concatenations (</td> + <td>)
        x=x+`
            <tr class="accordion-toggle collapsed"
                id="accordionPast${i}"
                data-mdb-collapse-init
                data-mdb-parent="#accordionPast${i}"
                href="#collapsePast${i}"
                aria-controls="collapsePast${i}">
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
                        <div class="mx-3"><button type="button" data-bs-toggle="collapse" data-bs-target="#collapsePast${i}" aria-expanded="false" aria-controls="collapsePast${i}" class="btn btn-success btn-sm">Détails</button></div>
                        ${reviewBtn}                       
                    </div>
                </td>
            </tr>

            <tr class="hide-table-padding">
                <td colspan="3">
                    <div id="collapsePast${i}" class="collapse">
                        <div class="p-3">
                            <div><strong>adr. dép. :</strong> ${result[i]['departure_address']}</div>
                            <div><strong>adr. arr. :</strong> ${result[i]['arrival_address']}</div>
                            <div><strong>durée (est.) :</strong> ${result[i]['travel_time']}h</div>                            
                            <div><strong>description :</strong> ${result[i]['description']}</div>
                            <div><strong>prix :</strong> ${result[i]['price']} Crédit(s)</div>
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

function getHtmlPlanned(result)
{
    let i=0;
    let x='';
    let smoking='';
    let animals='';

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
                id="accordionPlanned${i}"
                data-mdb-collapse-init
                data-mdb-parent="#accordionPlanned${i}"
                href="#collapsePlanned${i}"
                aria-controls="collapsePlanned${i}">
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
                        <div class="mx-3"><button type="button" data-bs-toggle="collapse" data-bs-target="#collapsePlanned${i}" aria-expanded="false" aria-controls="collapsePlanned${i}" class="btn btn-success btn-sm">Détails</button></div>
                        <div class="mx-3"><a href="dashboard/cancel_passenger/${result[i]['id']}"><button type="button" class="btn btn-danger btn-sm" onclick="return confirm('Etes-vous sûr(e) de vouloir annuller votre participation à ce covoiturage ?')">Annuler</button></a></div>
                    </div>
                </td>
            </tr>

            <tr class="hide-table-padding">
                <td colspan="3">
                    <div id="collapsePlanned${i}" class="collapse">
                        <div class="p-3">
                            <div><strong>adr. dép. :</strong> ${result[i]['departure_address']}</div>
                            <div><strong>adr. arr. :</strong> ${result[i]['arrival_address']}</div>
                            <div><strong>durée (est.) :</strong> ${result[i]['travel_time']}h</div>                            
                            <div><strong>description :</strong> ${result[i]['description']}</div>
                            <div><strong>prix :</strong> ${result[i]['price']} Crédit(s)</div>
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

function getHtml_noResults_past()
{
    let x='';
    x=x+`<div class="m-2 error-message">Aucun covoiturage terminé</div>`;
    return x;
}

function getHtml_noResults_planned()
{
    let x='';
    x=x+`<div class="m-2 error-message">Aucun covoiturage planifié</div>`;
    return x;
}



$(document).ready(function(){

    $.ajax({
        url:'dashboard/carpools_passenger/past',
        type:'POST',
        dataType:'JSON',

        success:function(response) {            
            let x = JSON.stringify(response);
            //console.log(x);
            if (x = null) {
                $("#carpoolsPastResults").html(getHtml_noResults_past());
            } else {
                let html = getHtmlPast(response);
                $("#carpoolsPastResults").html(html);
            }            
        },
        error:function (e) {
            $("#carpoolsPastResults").html(getHtml_noResults_past());
        }
    });
});

$(document).ready(function(){

    $.ajax({
        url:'dashboard/carpools_passenger/planned',
        type:'POST',
        dataType:'JSON',

        success:function(response) {            
            let x = JSON.stringify(response);
            if (x = null) {
                $("#carpoolsPlannedResults").html(getHtml_noResults_planned());
            } else {
                let html = getHtmlPlanned(response);
                $("#carpoolsPlannedResults").html(html);
            }            
        },
        error:function (e) {
            $("#carpoolsPlannedResults").html(getHtml_noResults_planned());
        }
    });
});




