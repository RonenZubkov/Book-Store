// trigger when login form is submitted
$(document).submit('#login_form', function(){
    // get form data
    let login_form=$(this);
    console.log($(this));
    let form_data=JSON.stringify(login_form.serializeObject());
    console.log(login_form);
    // submit form data to api
    $.ajax({
        url: "api/user/login.php",
        type : "POST",
        contentType : 'application/json',
        data : form_data,
        success : function(result){
            console.log(result);
            // store jwt to cookie
            setCookie("jwt", result.jwt, 1);
            console.log(getCookie());
            console.log('success');
            // show home page & tell the user it was a successful login
            // showHomePage();
            // $('#response').html("<div class='alert alert-success'>Successful login.</div>");

        },
        error: function(xhr, resp, text){
            console.log(form_data);
            console.log('failed response');
            console.log(xhr);
            console.log(resp);
            console.log(text);

            // // on error, tell the user login has failed & empty the input boxes
            // $('#response').html("<div class='alert alert-danger'>Login failed. Email or password is incorrect.</div>");
            // login_form.find('input').val('');
        }
    });

    return false;
});
/*Works*/
$(document).on('submit','#sign_up_form', function(){
    // get form data
    let sign_up_form=$(this);
    let form_data=JSON.stringify(sign_up_form.serializeObject());
    console.log('This serialize: ', $( this ).serialize() );
    console.log(form_data);
    console.log(sign_up_form);

    // submit form data to api
    $.ajax({
        url: "api/user/create.php",
        type : "POST",
        contentType : 'application/json',
        data : form_data,
        success : function(result) {
            console.log('Success');
            // if response is a success, tell the user it was a successful sign up & empty the input boxes
            $('.response').html("<div class='alert alert-success'>Successful sign up. Please login.</div>");
            sign_up_form.find('input').val('');
        },
        error: function(xhr, resp, text){
            console.log(xhr);
            console.log(resp);
            console.log(text);
            console.log('failed');
            // on error, tell the user sign up failed
            $('.response').html("<div class='alert alert-danger'>Unable to sign up. Please contact admin.</div>");
        }
    });

    return false;
});


