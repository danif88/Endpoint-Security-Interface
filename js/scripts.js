$( document ).ready(function() {
    $("#msg_error").text("");
	$('#fileForm').ajaxForm(function(data) {
        console.log(data);
		if(data.status===1)
			alert(data.data);
        else if(data.status===3)
            redirect(data);
		else
			$("#msg_error").text("ERROR " + data.error);
    }); 
    $("#msg_error").text("");
    $('#updateForm').ajaxForm(function(data) {
        console.log(data);
        if(data.status===1)
            alert(data.data);
        else if(data.status===3)
            redirect(data);
        else
            $("#msg_error").text("ERROR " + data.error);
    }); 
});

function redirect(data){
    //alert(data.error);
    window.location.assign($("#uri_int").val());
}

function doQuery(){
	var query = encodeURI($("#query").val());
	window.location.assign($("#uri_fuseki").val() + "/ds/query?query=" + query + "&output=" + $("#outputType").val());
}

function logOut(){
    $.ajax({url: $("#uri_api").val() + "/logOut?session=" + $("#session_id").val(), dataType: "json", contentType: "application/json; charset=utf-8",type:"GET", success: function(data){
        redirect(data);
    }});
}

function createUser(){
	// $.ajax({
	// 	url: "http://localhost:8080/addUser?session=" + $("#session_id").val() + "&name=" + $("#user").val() + "&encrypted=" + $("#password").val()
	// }).done(function(data) {
	// 	console.log(data);
	// 	//data1= $.parseJSON(data);
	// 	//alert(data1.data);
	// });
	/*$.get( "http://localhost:8080/addUser?session=" + $("#session_id").val() + "&name=" + $("#user").val() + "&encrypted=" + $("#password").val(), function( data ) {
		console.log(data);
	});*/
    $("#msg_error").text("");
	var pass=md5($("#password").val());

	$.ajax({url: $("#uri_api").val() + "/addUser?session=" + $("#session_id").val() + "&name=" + $("#user").val() + "&encrypted=" + pass, dataType: "json", contentType: "application/json; charset=utf-8",type:"GET", success: function(data){
        if(data.status===1)
            alert(data.data);
        else if(data.status===3)
            redirect(data);
        else
            $("#msg_error").text("ERROR " + data.error);
    }});
}

function createGraphOwnerUser(){
        // $.ajax({
        //      url: "http://localhost:8080/addUser?session=" + $("#session_id").val() + "&name=" + $("#user").val() + "&encrypted=" + $("#password").val()
        // }).done(function(data) {
        //      console.log(data);
        //      //data1= $.parseJSON(data);
        //      //alert(data1.data);
        // });
        /*$.get( "http://localhost:8080/addUser?session=" + $("#session_id").val() + "&name=" + $("#user").val() + "&encrypted=" + $("#password").val(), function( data ) {
                console.log(data);
        });*/
    $("#msg_error").text("");
        var pass=md5($("#password").val());

        $.ajax({url: $("#uri_api").val() + "/addGraphOwnerUser?session=" + $("#session_id").val() + "&name=" + $("#user").val() + "&encrypted=" + pass, dataType: "json", contentType: "application/json; charset=utf-8",type:"GET", success: function(data){
        if(data.status===1)
            alert(data.data);
        else if(data.status===3)
            redirect(data);
        else
            $("#msg_error").text("ERROR " + data.error);
    }});
}


function deleteUserFromGraph(name,graph,n){
    $("#msg_error_users").text("");
	$.ajax({url: $("#uri_api").val() + "/deleteUserFromGraph?session=" + $("#session_id").val() + "&name=" + name + "&graph=" + graph, dataType: "json", contentType: "application/json; charset=utf-8",type:"GET", success: function(data){
//        $("#msg_users").text(data.data);
//        console.log(data);
        if(data.status===1)
            $("#users_" + n).remove();
        else if(data.status===3)
            redirect(data);
        else
            $("#msg_error_users").text("ERROR " + data.error);
    }});
}

function deleteGraphOwnerUser(name,n){
	$("#msg_error_users").text("");
        $.ajax({url: $("#uri_api").val() + "/deleteGraphOwnerUser?session=" + $("#session_id").val() + "&name=" + name, dataType: "json", contentType: "application/json; charset=utf-8",type:"GET", success: function(data){
//        $("#msg_users").text(data.data);
//        console.log(data);
        if(data.status===1)
            $("#users_" + n).remove();
        else if(data.status===3)
            redirect(data);
        else
            $("#msg_error_users").text("ERROR " + data.error);
    }});
}

function deleteGraph(graph,n){
    $("#msg_error_graphs").text("");
	$.ajax({url: $("#uri_api").val() + "/deleteGraph?session=" + $("#session_id").val() + "&graph=" + graph, dataType: "json", contentType: "application/json; charset=utf-8",type:"GET", success: function(data){
//        $("#msg_graphs").text(data.data);
//        console.log(data);
        if(data.status===1)
            $("#graphs_" + n).remove();
        else if(data.status===3)
            redirect(data);
        else
            $("#msg_error_graphs").text("ERROR " + data.error);
    }});
}

function addUserToGraph(graph){
    $("#msg_error_graphs").text("");
    bootbox.prompt({
      title: "User name?",
      value: "",
      callback: function(name) {
        if (name === "") {
          alert("User cannot be empty")
          return false;
        } else {
            $.ajax({url: $("#uri_api").val() + "/addUserToGraph?session=" + $("#session_id").val() + "&name=" + name + "&graph=" + graph, dataType: "json", contentType: "application/json; charset=utf-8",type:"GET", success: function(data){
//                $("#msg_graphs").text(data.data);
//                console.log(data);
                if(data.status===1)
                    $("#graphs_" + n).remove();
                else if(data.status===3)
                    redirect(data);
                else
                    $("#msg_error_graphs").text("ERROR " + data.error);
            }});
        }
      }
    });
}

/*
function changePass(){
    bootbox.prompt({
      title: "New password?",
      type: "password",
      value: "",
      callback: function(pass) {
        if (pass === "") {
          alert("Password cannot be empty")
          return false;
        } else {
            $.ajax({url: $("#uri_int").val() + "/changePass.php?session=" + $("#session_id").val() + "&pass=" + pass, type:"GET", success: function(data){
                $.ajax({url: $("#uri_api").val() + "/changePass?session=" + $("#session_id").val() + "&encrypted=" + data, dataType: "json", contentType: "application/json; charset=utf-8",type:"GET", success: function(data){
                    if(data.status===1)
                        alert(data.data);
                    else if(data.status===3)
                        redirect(data);
                    else
                        alert(data.error);
                }});
            }});
        }
      }
    });
}*/

function changePass(){
	bootbox.dialog({
  		message: "Password:<input id='pass1' type='password'></input><br>Repeat Password:<input id='pass2' type='password'></input><br><button type='button' class='btn btn-default' onclick='runChangePass();'>OK</button>",
  		title: "New Password?"});

}

function runChangePass(){
	var pass1=$("#pass1").val();
	var pass2=$("#pass2").val();
	if(pass1 === ""){
		alert("Password cannot be empty")
          	return false;
	}
	if(pass1 !== pass2){
		alert("Passwords are different")
          	return false;
	}
	$.ajax({url: $("#uri_int").val() + "/changePass.php?session=" + $("#session_id").val() + "&pass=" + pass1, type:"GET", success: function(data){
                $.ajax({url: $("#uri_api").val() + "/changePass?session=" + $("#session_id").val() + "&encrypted=" + data, dataType: "json", contentType: "application/json; charset=utf-8",type:"GET", success: function(data){
                    if(data.status===1)
                        alert(data.data);
                    else if(data.status===3)
                        redirect(data);
                    else
                        alert(data.error);
                }});
            }});
}
