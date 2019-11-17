let max_page;
let url;
let test;
window.onload = displayProducts();
async function displayProducts()
{
    let product_min_price;
    let product_max_price;
    let shop_page_number = 0;
    let shop_products_per_page = document.getElementById("ag_products_per_page").value;
    url = new URL(window.location.href);
    let urlParams = (url).searchParams;
    if (urlParams.get("pagenumber"))
    {
        shop_page_number = urlParams.get("pagenumber");
    }
    if (urlParams.get("pagelimit"))
    {
        shop_products_per_page = urlParams.get("pagelimit");
    }
    if (urlParams.get("minprice"))
    {
        product_min_price = urlParams.get("minprice");
    }
    if (urlParams.get("maxprice"))
    {
        product_max_price = urlParams.get("maxprice");
    }
    let call_url = "php/ajax_get_all_products_on_page.php?pagenumber=" + shop_page_number + "&pagelimit=" + shop_products_per_page + "&minprice=" + product_min_price + "&maxprice=" + product_max_price;   /* name of file to send request to */
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
        test = response;
        let product_string = "";
        let pages_string = "";
        if (response.data.products.length > 0 && response.data.products[0] !== false)
        {
            for (let i = 0; i < response.data.products.length; i++)
            {
                product_string += '<!-- product --><div class="col-lg-4 col-md-6"><div class="single-product" title="' + response.data.products[i].product_id + '"><img class="img-fluid" src="' + response.data.products[i].image_url + '" alt=""><div class="product-details"><h6>' + response.data.products[i].name + '</h6><div class="price"><h6>' + response.data.products[i].unit_price + '€</h6></div><div class="prd-bottom"><a href="" class="social-info"><span class="ti-bag"></span><p class="hover-text">add to bag</p></a><a href="" class="social-info"><span class="lnr lnr-heart"></span><p class="hover-text">Wishlist</p></a><a href="" class="social-info"><span class="lnr lnr-sync"></span><p class="hover-text">compare</p></a><a href="" class="social-info"><span class="lnr lnr-move"></span><p class="hover-text">view more</p></a></div></div></div></div>';
            }
            let pages_elements = document.getElementsByClassName("pagination");
            max_page = Math.ceil(response.data.prod_count.count / shop_products_per_page);
            for (let i = 0; i < pages_elements.length; i++)
            {
                let disable_class = "";
                if (shop_page_number <= 0)
                {
                    disable_class = "disable_page_button";
                }
                pages_string = '<a href="" title="' + (parseInt(shop_page_number) - 1) + '" class="prev-arrow ag-page-selector-arrow ' + disable_class + '" style="margin-left: -1px" ><i class="fa fa-long-arrow-left" aria-hidden="true"></i></a>';
                let page_start = shop_page_number - 2; //to keep the current page always in the middle of selection
                /* If the current page is at the start then set it to start 
                 * from very first page, not keeping current page in the middle of selection*/
                if (page_start < 0) {
                    page_start = 0;
                } else {
                    page_start = shop_page_number - 1;
                }
                let iterate_for = page_start + 3;
                /* If the page is less than the set iteration then just iterate
                 *  for that amount (e.g if max page 2 then only do 2 pages and don't go further*/
                if (max_page < iterate_for)
                {
                    iterate_for = max_page;
                }
                /* If current page number is greater than 1 (starting from 0) add the dots and move first page to left
                 * so you can go back to first page from any page.*/
                if (shop_page_number > 1)
                {
                    pages_string += '<a href="" class="page-btn" >1</a>';
                    pages_string += '<a href="" class="dot-dot"><i class="fa fa-ellipsis-h" aria-hidden="true"></i></a>';
                }
                for (let j = page_start; j < iterate_for; j++)
                {
                    if (shop_page_number == j) //== works but === doesn't. rip
                    {
                        pages_string += '<a href="" class="active">' + (j + 1) + '</a>';
                    } else
                    {
                        pages_string += '<a href="" class="page-btn">' + (j + 1) + '</a>';
                    }
                }
                if (max_page > 3 && shop_page_number < max_page - 2)
                {
                    pages_string += '<a href="" class="dot-dot"><i class="fa fa-ellipsis-h" aria-hidden="true"></i></a>';
                    pages_string += '<a href=""  class="page-btn" >' + max_page + '</a>';
                }
                disable_class = "";
                if (shop_page_number >= max_page - 1)
                {
                    disable_class = "disable_page_button";
                }
                pages_string += '<a href="" title="' + (parseInt(shop_page_number) + 1) + '" class="next-arrow ag-page-selector-arrow ' + disable_class + '"><i class="fa fa-long-arrow-right" aria-hidden="true"></i></a>';
                pages_elements[i].innerHTML = pages_string;
            }
        } else
        {
            product_string = "<h2>Oops! No products were found!</h2>";
        }
        document.getElementById("product_list").classList.add("show_nice");
        document.getElementById("product_list").innerHTML = product_string;
    }
}
$(document).on("click", '.disable_page_button', function (event) {
    event.preventDefault();
});
$(document).on("click", '.page-btn', function (event) {
    event.preventDefault();
    goNewPage(this.text - 1);
});
$(document).on("click", '.ag-page-selector-arrow', function (event) {
    event.preventDefault();
    goNewPage(parseInt(this.title));
});
$(document).on("click", '.single-product', function (event) {
    location.href = "single-product.html?product=" + this.title;
});
function goNewPage(pageNumber)
{
    if (pageNumber >= 0 && pageNumber < max_page)
    {
        url.searchParams.set("pagenumber", pageNumber);
        let pageLimit = $("#ag_products_per_page").val();
        if (url.searchParams.get("pagelimit"))
        {
            pageLimit = url.searchParams.get("pagelimit");
        }
        url.searchParams.set("pagelimit", pageLimit);
        window.location.href = url;
    }
}
function setParam(param_name, page_limit)
{
    if (url.searchParams.get(param_name) !== page_limit)
    {
        url.searchParams.set(param_name, page_limit);
        goNewPage(0);
    }
}