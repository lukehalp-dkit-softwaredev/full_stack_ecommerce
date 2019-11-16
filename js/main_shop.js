window.onload = ajaxListAllModels2();
async function ajaxListAllModels2()
{
    let url = "php/ajax_get_all_products_on_page.php";   /* name of file to send request to */
    let urlParameters = ""; /* Construct a url parameter string to POST to fileName */

    try
    {
        const response = await fetch(url,
                {
                    method: "POST",
                    headers: {'Content-type': 'application/x-www-form-urlencoded; charset=UTF-8'},
                    body: urlParameters
                });

        updateWebpage(await response.json());
    } catch (error)
    {
        console.log("Fetch failed: ", error);
    }


    /* use the fetched data to change the content of the webpage */
    function updateWebpage(response)
    {
        let product_string = "";
        if (response.length > 0)
        {
            for (let i = 0; i < response.length; i++)
            {
                product_string += '<!-- product --><div class="col-lg-4 col-md-6"><div class="single-product"><img class="img-fluid" src="' + response[i].image_url + '" alt=""><div class="product-details"><h6>' + response[i].name + '</h6><div class="price"><h6>' + response[i].unit_price + '</h6></div><div class="prd-bottom"><a href="" class="social-info"><span class="ti-bag"></span><p class="hover-text">add to bag</p></a><a href="" class="social-info"><span class="lnr lnr-heart"></span><p class="hover-text">Wishlist</p></a><a href="" class="social-info"><span class="lnr lnr-sync"></span><p class="hover-text">compare</p></a><a href="" class="social-info"><span class="lnr lnr-move"></span><p class="hover-text">view more</p></a></div></div></div></div>';
            }
        } else
        {
            product_string = "<h2>Oops! No products were found!</h2>";
        }
        document.getElementById("product_list").innerHTML = product_string;
    }
}