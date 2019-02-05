$(document).ready(function(){
    renderProducts();
});


    // will run if create product form was submitted
    $(document).on('click', '#create-product-form', function(){
        // get form data
        let form_data=JSON.stringify($(this).serializeObject());

        // submit form data to api
        $.ajax({
            url: "../api/book/create.php",
            type : "POST",
            contentType : 'application/json',
            data : form_data,
            success : function(result) {
                // product was created, go back to product list
                renderProducts();
            },
            error: function(xhr, resp, text) {
                // show error to console
                console.log(xhr, resp, text);
            }
        });

        return false;
    });

    function renderProducts (){
        // load list of categories
        console.log('start render');
        $.getJSON("../api/category/read.php", function(data) {
            console.log(data);

            console.log('get');
            // build categories option html
            // loop through returned list of data
            let categories_options_html = "";
            categories_options_html += "<select name='category_id' class='form-control'>";
            console.log(categories_options_html);

            $.each(data.records, function (key, val) {
                categories_options_html += "<option value='" + val.id + "'>" + val.name + "</option>";
            });
            categories_options_html += "</select>";

            // we have our html form here where product information will be entered
            // we used the 'required' html5 property to prevent empty fields
            let create_product_html = "";

            // 'read product' button to show list of product
            create_product_html += "<div id='read-product' class='btn btn-primary pull-right m-b-15px read-product-button'>";
            create_product_html += "<span class='glyphicon glyphicon-list'></span> Read Products";
            create_product_html += "</div>";

            // 'create product' html form
            create_product_html += "<form id='create-product-form' action='#' method='post' border='0'>";
            create_product_html += "<table class='table table-hover table-responsive table-bordered'>";

            // name field
            create_product_html += "<tr>";
            create_product_html += "<td>Name</td>";
            create_product_html += "<td><input type='text' name='name' class='form-control' required /></td>";
            create_product_html += "</tr>";

            // price field
            create_product_html += "<tr>";
            create_product_html += "<td>Price</td>";
            create_product_html += "<td><input type='number' min='1' name='price' class='form-control' required /></td>";
            create_product_html += "</tr>";

            // description field
            create_product_html += "<tr>";
            create_product_html += "<td>Description</td>";
            create_product_html += "<td><textarea name='description' class='form-control' required></textarea></td>";
            create_product_html += "</tr>";

            // categories 'select' field
            create_product_html += "<tr>";
            create_product_html += "<td>Category</td>";
            create_product_html += "<td>" + categories_options_html + "</td>";
            create_product_html += "</tr>";

            // button to submit form
            create_product_html += "<tr>";
            create_product_html += "<td></td>";
            create_product_html += "<td>";
            create_product_html += "<button type='submit' class='btn btn-primary'>";
            create_product_html += "<span class='glyphicon glyphicon-plus'></span> Create Product";
            create_product_html += "</button>";
            create_product_html += "</td>";
            create_product_html += "</tr>";

            create_product_html += "</table>";
            create_product_html += "</form>";

            // inject html to 'page-content' of our app
            $("#page-content").html(create_product_html);

            // change page title
            // changePageTitle("Create Product");
    })}
