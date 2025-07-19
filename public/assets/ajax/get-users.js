
function getHtmlUsers(result)
{
    let i=0;
    let x='';
    let suspended='';
    let btn='';
    let btnBan = '';
    let btnReinstate = '';

    x=x+`<div class="m2 p3">`;
    
    for(i=0;i<result.length;i++)
    {
        result[i]['suspended'] ? suspended=' (<strong>SUSPENDU</strong>)' : suspended='';
        btnBan = `<a href="admin/ban/${result[i]['id']}"><button type="button" class="btn btn-danger btn-sm">Suspendre</button></a>`;
        btnReinstate = `<a href="admin/reinstate/${result[i]['id']}"><button type="button" class="btn btn-primary btn-sm">Retablir</button></a>`;
        result[i]['suspended'] ? btn=btnReinstate : btn=btnBan;        

        x=x+`
            <div class="d-flex flex-column gap-1">
                <div><p>#${result[i]['id']} - ${result[i]['pseudo']}${suspended}</p></div>
                <div><p><a href="mailto:${result[i]['email']}">${result[i]['email']}</a></p></div>
                <div>${btn}</div>
            </div><hr>
        `;
    }

    x=x+`</div>`;
    
    return x;
}

function getHtml_noUsersResults()
{
    let x='';
    x=x+`<div class="m-2 error-message">Aucun utilisateur trouvé.</div>`;
    return x;
}


$('#searchFormUsers').on('submit', function(e) {

    e.preventDefault();

    $.ajax({
        url:'admin/users',
        type:'POST',
        dataType:'JSON',

        data:{
            "search_id": $('input[name=search_id]').val(),
            "search_pseudo": $('input[name=search_pseudo]').val(),
            "search_email": $('input[name=search_email]').val(),
            "search_role": $("#search_role").val()
        },

        success:function(response) {
            let x = JSON.stringify(response);
            //alert(x);
            //console.log(response);
            let html = getHtmlUsers(response);
            //alert(html);
            $("#results-users").html(html);
        },
        error:function (e) {
            $("#results-users").html(getHtml_noUsersResults());
        }
    });


});





