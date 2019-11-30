let test;
let url = new URL(window.location.href);
window.onload = onWindowLoaded();
function onWindowLoaded()
{
    displayProduct();
}
async function displayProduct()
{
    let urlParams = (url).searchParams;
    let product_id = urlParams.get("product");
    let call_url = "api/products/get.php?product=" + product_id;
    try
    {
        const response = await fetch(call_url,
                {
                    method: "GET",
                    headers: {'Content-type': 'application/x-www-form-urlencoded; charset=UTF-8'}
                });

        updateWebpage(await response.json());
    } catch (error)
    {
        console.log("Fetch failed: ", error);
    }
    function updateWebpage(response)
    {
        test = response;
        if (response.data)
        {
            let data = response.data;
            let product_images_container = document.getElementById("ag_single_product_images");
            let product_title_container = document.getElementById("ag_single_product_title");
            let product_price_container = document.getElementById("ag_single_product_price");
            let product_category_container = document.getElementById("ag_single_product_category");
            let product_availability_container = document.getElementById("ag_single_product_availablity");
            let product_short_description_container = document.getElementById("ag_single_product_short_description");
            let product_long_description_container = document.getElementById("ag_product_description");
            product_images_container.innerHTML = '<div class="single-prd-item"><img class="img-fluid ag_single_product_image" src="' + data.image_url + '" alt=""></div>';
            product_title_container.innerHTML = data.product_title;
            product_price_container.innerHTML = "€" + data.unit_price;
            product_category_container.innerHTML = '<a class="active" href="#"><span>Category</span> : ' + data.category_name + '</a>';
            product_short_description_container.innerHTML = data.description;
            if (data.stock > 0 || data.stock == -1)
            {
                product_availability_container.innerHTML = '<a href="#"><span>Availibility</span> : In Stock <span title="' + data.stock + '" id="ag_single_product_stock_level">(' + data.stock + ')</span></a>';

            } else
            {
                product_availability_container.innerHTML = '<a href="#"><span>Availibility</span> : Unavailable</a>';
            }
            product_long_description_container.innerHTML = "<p>" + data.description + "</p>";
        } else
        {
            document.getElementById("ag_single_product_container").innerHTML = '<section class="product_description_area"><div class="container"><h1>Oops, product not found!</h1><a class="ag_link" href="category.php">BROWSE PRODUCTS</a></div></section>';
        }
    }
}