$('document').ready(function() {
    var ajax_url = customurl_ajax_object.ajax_url;
    var userid = 0;
    var nonce_ = customurl_ajax_object.nonce_token;
    var tableuser = $('#userlist').DataTable( {
        "processing": true,
        "ajax": ajax_url+"?action=userlist",
        "columns": [
            { "data": "id","mRender": function ( data, type, row ) {
                return '<a href="javascript:void(0)" id="view_user_detail">'+row.id+'</a>';} 
            },
            { "data": "name","mRender": function ( data, type, row ) {
                return '<a href="javascript:void(0)" id="view_user_detail">'+row.name+'</a>';} 
            },
            { "data": "username","mRender": function ( data, type, row ) {
                return '<a href="javascript:void(0)" id="view_user_detail">'+row.username+'</a>';}  
            },
            { "data": "email" },
            { "data": "phone" },
            { "data": "address.zipcode" },
            { "data": "company.name" }
        ]
    } );

    $("#userlist").on("click",'a',function(){
        if($(this).attr("id") == "view_user_detail"){ 
            var tdata = tableuser.row($(this).parents('tr')).data();   
            userid =tdata.id;       
            $("#userview").modal("show");
        }
    });
    //view customer details
    $("#userview").on('show.bs.modal', function (event) {
        $.ajax({
            url:ajax_url,
            method:"POST",
            data:{userid:userid,action:"userdetail",security:nonce_},
            success:function(response)
            {   
                response = response.data;
                if(!response.error) {
                    $(".modal-body div span").text("");
                    $(".id span").text(response.id);
                    $(".name span").text(response.name);
                    $(".username span").text(response.username);
                    $(".address span").html(response.address.street+", "+response.address.suite+", "+response.address.city);
                    $(".zipcode span").text(response.address.zipcode);
                    $(".geo span").text("lat: "+response.address.geo.lat+", lng: "+response.address.geo.lng);
                    $(".email span").text(response.email);
                    $(".phone span").text(response.phone);
                    $(".website span").text(response.website);
                    $(".company span").html(response.company.name+" <br>Catch Phrase: "+response.company.catchPhrase+"<br>Bs "+response.company.bs);
                }else {
                    $(".error").text("Error! No detail found");
                }
            },
            dataType:'json'
        });        
      });
    
} );