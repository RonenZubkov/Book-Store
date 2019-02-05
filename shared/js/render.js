$( document ).ready(function() {
    showLoginPage();
});


function showLoginPage(){

    // remove jwt
    setCookie("jwt", "", 1);

    // login page html
    let modalBody = `
        <div class="modal-dialog modal-login">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="avatar">
                        <img src="/examples/images/avatar.png" alt="Avatar">
                    </div>
                    <h4 class="modal-title">Member Login</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>    
                </div>
                <div class="modal-body">
                    <form id='login_form''>
                       <div class="form-group">
                           <input type="email" class="form-control" name="email" placeholder="Email" required="required">
                       </div>
                       <div class="form-group">
                          <input type="password" class="form-control" name="password" placeholder="Password" required="required">
                        </div>
                        <div class="form-group">
                            <input type="submit" class="btn btn-primary btn-lg btn-block login-btn">Login</input>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <a href="#">Forgot Password?</a>
                    <a href="register.html">Register?</a>
                </div>
           </div>
        </div>   
        `;

    $('#myModal').html(modalBody);
    // clearResponse();
    // showLoggedOutMenu();
}


