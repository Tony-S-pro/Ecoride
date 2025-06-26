
function getTable(result)
{
    let i=0;
    let x='';

    x=x+`<div><button class="btn btn-warning btn-sm mt-2" data-bs-toggle="modal" data-bs-target="#myModal" id="btn-AddNew">Ajouter</button></div>`;
    x=x+`<table class="table table-success table-striped mt-2">`;

    x=x+`<thead>
                <tr>
                    <th>Départ</th>
                    <th>--</th>
                    <th>--</th>
                    <th>Arrivée</th>
                    <th>Prix</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>`;
    
    for(i=0;i<result.length;i++)
    {
        //use backticks `` to avoid concatenations (</td> + <td>)
        x=x+`
            <tr>
            <td>${result[i]['departure_date']}</td>
            <td>${result[i]['departure_time']}</td>
            <td>${result[i]['departure_city']}</td>
            <td>${result[i]['arrival_city']}</td>
            <td>${result[i]['price']}</td>
            <td><button type="button" class="btn btn-success btn-sm">Détails</button></td>
            </tr>
        `;
    }

    x=x+`</tbody>
            </table>`;
    
    return x;
}


$('#searchForm').on('submit', function(e) {

    e.preventDefault();

    let search = $('input[name=search]').val();

    $.ajax({
        url:'carpools',
        type:'POST',
        dataType:'JSON',

        data:{
            search: search
        },

        success:function(response) {
            let x = JSON.stringify(response);
            //alert(x);
            let html = getTable(response);
            //alert(html);
            $("#results-carpool").html(html);
        }
    });


});

$(document).on("click", "#btn-AddNew", function() {
    //alert('feik,pioef,k');
    $("#myModal").modal('show');
    
});





