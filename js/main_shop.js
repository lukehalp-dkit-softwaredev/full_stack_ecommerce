let test;
let shop_page_number = 0;
let max_page;

window.onload = displayProducts();
async function displayProducts()
{
//    let shop_products_per_page_element = document.getElementById("ag_products_per_page")
    let shop_products_per_page = document.getElementById("ag_products_per_page").value
//    let current_url = new URL(window.location.href);
//    console.log(shop_products_per_page);
//    current_url.searchParams.set('pageLimit', shop_products_per_page_element);
//    current_url.searchParams.set('pageNumber', shop_page_number_element);
    let call_url = "php/ajax_get_all_products_on_page.php?pageNumber=" + shop_page_number + "&pageLimit=" + shop_products_per_page;   /* name of file to send request to */
//    let call_url_parameters = "pageNumber=" + shop_page_number_element + "&pageLimit=" + shop_products_per_page_element.value; /* Construct a url parameter string to POST to fileName */

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


    /* use the fetched data to change the content of the webpage */
    function updateWebpage(response)
    {
        console.log(response);
        test = response;
        console.log(response[0].length);
        let product_string = "";
        let pages_string = "";
        if (response[0].length > 0)
        {
            for (let i = 0; i < response[0].length; i++)
            {
                product_string += '<!-- product --><div class="col-lg-4 col-md-6"><div class="single-product"><img class="img-fluid" src="' + response[0][i].image_url + '" alt=""><div class="product-details"><h6>' + response[0][i].name + '</h6><div class="price"><h6>' + response[0][i].unit_price + '€</h6></div><div class="prd-bottom"><a href="" class="social-info"><span class="ti-bag"></span><p class="hover-text">add to bag</p></a><a href="" class="social-info"><span class="lnr lnr-heart"></span><p class="hover-text">Wishlist</p></a><a href="" class="social-info"><span class="lnr lnr-sync"></span><p class="hover-text">compare</p></a><a href="" class="social-info"><span class="lnr lnr-move"></span><p class="hover-text">view more</p></a></div></div></div></div>';
            }
            let pages_elements = document.getElementsByClassName("pagination");
            max_page = Math.ceil(response[1].product_count / shop_products_per_page);
            for (let i = 0; i < pages_elements.length; i++)
            {
                pages_elements[i].innerHTML = '<a href="#" onclick="previousPage()" class="prev-arrow"><i class="fa fa-long-arrow-left" aria-hidden="true"></i></a>';
                let page_start = 0;
                let current_page_position = (max_page / shop_page_number) % 3;
                page_start = shop_page_number - 2;
                if (page_start < 0)
                {
                    page_start = 0;
                } else
                {
                    page_start = shop_page_number - 1;
                }
                console.log("shoppagenumber" + shop_page_number);
                console.log("page_start" + page_start);

                let iterate_for = page_start + 3;
                if (max_page < iterate_for)
                {
                    iterate_for = max_page;
                }

                if (shop_page_number > 1)
                {
                    pages_elements[i].innerHTML += '<a href="#" onclick="goToPage(0)" >1</a>';
                    pages_elements[i].innerHTML += '<a href="#" class="dot-dot"><i class="fa fa-ellipsis-h" aria-hidden="true"></i></a>';
                }
                for (let j = page_start; j < iterate_for; j++)
                {
                    if (shop_page_number === j)
                    {
                        pages_elements[i].innerHTML += '<a href="#" class="active" onclick="goToPage(' + j + ');">' + (j + 1) + '</a>';
                    } else
                    {
                        pages_elements[i].innerHTML += '<a href="#" onclick="goToPage(' + j + ');">' + (j + 1) + '</a>';
                    }
                }
                if (max_page > 3 && shop_page_number < max_page - 2)
                {
                    pages_elements[i].innerHTML += '<a href="#" class="dot-dot"><i class="fa fa-ellipsis-h" aria-hidden="true"></i></a>';
                    pages_elements[i].innerHTML += '<a href="#" onclick="goToPage(' + (max_page - 1) + ')" >' + max_page + '</a>';
                }
                pages_elements[i].innerHTML += '<a href="#" onclick="nextPage()" class="next-arrow"><i class="fa fa-long-arrow-right" aria-hidden="true"></i></a>';
            }
        } else
        {
            product_string = "<h2>Oops! No products were found!</h2>";
        }
        document.getElementById("product_list").innerHTML = product_string;
    }
}

function goToPage(page)
{
    if (page < 0)
    {
        shop_page_number = 0;
    }
    if (page >= max_page)
    {
        shop_page_number = max_page - 1;
    } else
    {
        shop_page_number = page;
    }
    displayProducts();
}
function previousPage()
{
    if (shop_page_number > 0)
    {
        shop_page_number--;
        goToPage(shop_page_number);
    }
}

function nextPage()
{
    if (shop_page_number < max_page - 1)
    {
        shop_page_number++;
        goToPage(shop_page_number);
    }
}