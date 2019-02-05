$( document ).ready(function() {
    showRegisterPage();
});


function showRegisterPage(){
    let registerForm = `
            <h2 class="col-md-2 col-md-offset-5">Sign Up</h2>
            <form id='sign_up_form' class="col-md-6 col-md-offset-3">
            
            	<div class="form-group col-md-6">
            		<label for="firstname">Firstname</label>
            		<input type="text" class="form-control" name="firstname" id="firstname" required />
            	</div>

            	<div class="form-group col-md-6">
            		<label for="lastname">Lastname</label>
            		<input type="text" class="form-control" name="lastname" id="lastname" required />
            	</div>

            	<div class="form-group col-md-6">
            		<label for="email">Email</label>
            		<input type="email" class="form-control" name="email" id="email" required />
            	</div>

            	<div class="form-group col-md-6">
            		<label for="password">Password</label>
            		<input type="password" class="form-control" name="password" id="password" required />
            	</div>
            	
            	<div class="form-group col-md-6">
            		<label for="address">Address</label>
            		<input type="text" class="form-control" name="address" id="address" required />
            	</div>
            	
            	<div class="form-group col-md-6">
            		<label for="contact_number">Phone</label>
            		<input type="phone" class="form-control" name="contact_number" id="contact_number" required />
            	</div>
            	
                <button type='submit' class='col-md-6 col-md-offset-3 col-md-10 btn btn-primary'>
                    <span class='glyphicon glyphicon-plus'></span> Create User
                </button>
             
                <div class="form-group col-md-6 col-md-offset-3 response"></div>
            </form>
            `;
    $('#registerPage').html(registerForm);
}


