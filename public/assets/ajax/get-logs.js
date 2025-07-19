function getLogsHtml(result)
{
    let i=0;
    let x='';
    let updated='';
    let status='';

    x=x+`<div class="m2 p3">`;
    
    for(i=0;i<result.length;i++)
    {   
       result[i].hasOwnProperty( 'update_date' ) ? updated=` (maj : ${result[i]['update_date']})` : updated='';

        if (result[i].hasOwnProperty( 'decision' )) {
            if (result[i]['decision']=='accepted') {
                status = '<div>Objection acceptée</div>';
            } else {
                status = '<div>Objection rejetée</div>'
            }
        }      

        x=x+`
        <div class="d-flex">    
            <div class="d-flex flex-grow-1 flex-column gap-1 mb-3">
                <div>${result[i]['creation_date']}${updated}</div>
                ${status}
                <a data-bs-toggle="collapse" href="#collapse_${i}" aria-expanded="false" aria-controls="collapse_${i}">Plus de détails</a>
                <div class="collapse" id="collapse_${i}">
                    <div class="card card-body">
                        <div>utilisateur n°${result[i]['user_id']}</br>employé n°${result[i]['employee_id']}</div>
                        <div><strong>commentaire :</strong> ${result[i]['comment']}</div>
                    </div>
                </div>                      
            </div>
        
            <div>
            <a href="admin/delete_log/${result[i]['id']}"><button type="button" class="btn btn-danger btn-sm">X</button></a>
            </div>
        </div><hr>
        
        `;
    }

    x=x+`</div>`;
    
    return x;
}

function getHtml_noObjections()
{
    let x='';
    x=x+`<div class="m-2 error-message">Aucunes entrées objection trouvées.</div>`;
    return x;
}


$(document).ready(function(){

    $.ajax({
        url:'admin/objections_log',
        type:'POST',
        dataType:'JSON',

        success:function(response) {            
            let x = JSON.stringify(response);
            if (x = null) {
            } else {
                let id='';
                response.forEach(r =>  {
                    id = r['_id']['$oid'];
                    r['id']= id;
                });
                let html = getLogsHtml(response);
                $("#results-objections").html(html);
            }            
        },
        error:function (e) {
            $("#results-objections").html(getHtml_noObjections());
        }
    });
});





