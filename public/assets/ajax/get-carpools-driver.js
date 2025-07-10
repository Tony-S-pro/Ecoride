
function getHtmlDriverPast(result)
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
                id="accordionDriverPast${i}"
                data-mdb-collapse-init
                data-mdb-parent="#accordionDriverPast${i}"
                href="#collapseDriverPast${i}"
                aria-controls="collapseDriverPast${i}">
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
                    <div class="d-flex flex-wrap justify-content-start mx-3">
                        <button type="button" data-bs-toggle="collapse" data-bs-target="#collapseDriverPast${i}" aria-expanded="false" aria-controls="collapseDriverPast${i}" class="btn btn-success btn-sm">Détails</button></td>
                    </div>
                </td>
            </tr>

            <tr class="hide-table-padding">
                <td colspan="3">
                    <div id="collapseDriverPast${i}" class="collapse">
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

function getHtmlDriverPlanned(result)
{
    let i=0;
    let x='';
    let smoking='';
    let animals='';
    let statusA='';
    let statusB='';
    let confirm='';

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
        if (result[i]['status']==='en_cours') {
            statusA = 'arrival';
            statusB = 'Arrivé';
            confirm = 'Confimer votre arrivée ?';
        } else {
            statusA = 'departure';
            statusB = 'Départ';
            confirm = 'Prêt à partir ?'
        }
        
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
                        <div class="mx-3"><button type="button" data-bs-toggle="collapse" data-bs-target="#collapseDriverPlanned${i}" aria-expanded="false" aria-controls="collapseDriverPlanned${i}" class="btn btn-outline-success btn-sm">Détails</button></div>
                        <div class="mx-3"><a href="dashboard/${statusA}/${result[i]['id']}"><button type="button" class="btn btn-outline-warning btn-sm" onclick="return confirm('${confirm}')">${statusB}</button></a></div>
                        <div class="mx-3"><a href="dashboard/cancel_driver/${result[i]['id']}"><button type="button" class="btn btn-danger btn-sm" onclick="return confirm('Voulez-vous annuler ce covoiturage ?')">Annuler</button></a></div>
                    </div>
                </td>
            </tr>

            <tr class="hide-table-padding">
                <td colspan="3">
                    <div id="collapseDriverPlanned${i}" class="collapse">
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

function getHtml_noResults_driver_past()
{
    let x='';
    x=x+`<div class="m-2 error-message">Aucun covoiturage terminé</div>`;
    return x;
}

function getHtml_noResults_driver_planned()
{
    let x='';
    x=x+`<div class="m-2 error-message">Aucun covoiturage planifié</div>`;
    return x;
}



$(document).ready(function(){

    $.ajax({
        url:'dashboard/carpools_driver/past',
        type:'POST',
        dataType:'JSON',

        success:function(response) {            
            let x = JSON.stringify(response);
            //console.log(x);
            if (x = null) {
                $("#driverPastResults").html(getHtml_noResults_driver_past());
            } else {
                let html = getHtmlDriverPast(response);
                $("#driverPastResults").html(html);
            }            
        },
        error:function (e) {
            $("#driverPastResults").html(getHtml_noResults_driver_past());
        }
    });
});

$(document).ready(function(){

    $.ajax({
        url:'dashboard/carpools_driver/planned',
        type:'POST',
        dataType:'JSON',

        success:function(response) {            
            let x = JSON.stringify(response);
            if (x = null) {
                $("#driverPlannedResults").html(getHtml_noResults_driver_planned());
            } else {
                let html = getHtmlDriverPlanned(response);
                $("#driverPlannedResults").html(html);
            }            
        },
        error:function (e) {
            $("#driverPlannedResults").html(getHtml_noResults_driver_planned());
        }
    });
});




