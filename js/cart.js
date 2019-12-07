/* <tr>
 <td>
 <div class="media">
 <div class="d-flex">
 <img src="img/cart.jpg" alt="">
 </div>
 <div class="media-body">
 <p>Minimalistic shop for multipurpose use</p>
 </div>
 </div>
 </td>
 <td>
 <h5>$360.00</h5>
 </td>
 <td>
 <div class="product_count">
 <input type="text" name="qty" id="sst" maxlength="12" value="1" title="Quantity:"
 class="input-text qty">
 <button onclick="var result = document.getElementById('sst'); var sst = result.value; if( !isNaN( sst )) result.value++;return false;"
 class="increase items-count" type="button"><i class="lnr lnr-chevron-up"></i></button>
 <button onclick="var result = document.getElementById('sst'); var sst = result.value; if( !isNaN( sst ) &amp;&amp; sst > 0 ) result.value--;return false;"
 class="reduced items-count" type="button"><i class="lnr lnr-chevron-down"></i></button>
 </div>
 </td>
 <td>
 <h5>$720.00</h5>
 </td>
 </tr> */


window.onload = onWindowLoaded();
function onWindowLoaded()
{
    loadBasket();
}
let items;
async function loadBasket() {
    let call_url = "api/orders/basket.php";
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

    function updateWebpage(response) {
        if (!response.error)
        {
            items = response.data.items;
            for (let i = 0; i < items.length; i++) {
                let item = items[i];
                let htmlString =
                        `<tr id="product_${ item.product_id }">
                <td>
                    <div class="media">
                        <div class="d-flex">
                            <img class="cart_image" src="img/products/${ item.product_id }.png" alt="">
                        </div>
                        <div class="media-body">
                            <p>${ item.name }</p>
                        </div>
                    </div>
                </td>
                <td>
                    <h5>€${ item.unit_price }</h5>
                </td>
                <td>
                    <div class="product_count">
                        <input type="text" name="qty" id="product_${ item.product_id }_quantity" maxlength="12" value="${ item.quantity }" title="Quantity:"
                            class="input-text qty">
                        <button onclick="var result = document.getElementById('product_${ item.product_id }_quantity'); var sst = result.value; if( !isNaN( sst )) result.value++;updateItemTotals();return false;"
                            class="increase items-count" type="button"><i class="lnr lnr-chevron-up"></i></button>
                        <button onclick="var result = document.getElementById('product_${ item.product_id }_quantity'); var sst = result.value; if( !isNaN( sst ) &amp;&amp; sst > 0 ) result.value--;updateItemTotals();return false;"
                            class="reduced items-count" type="button"><i class="lnr lnr-chevron-down"></i></button>
                    </div>
                </td>
                <td>
                    <h5>€<span id='product_${item.product_id}_total_price'>${ item.unit_price * item.quantity }</span></h5>
                </td>
            </tr>`;
                $("#cartlist").prepend(htmlString);
            }
            updateItemTotals();
            if (items.length == 0)
            {
                $("#cartlist").prepend("<tr><td><h2><a class='ag-active-category' href='category.php'>You've not added any items yet!</a></h2></td><td></td><td></td><td></td></tr>");
            }
        } else
        {
            displayMessage(response.error.msg, 2500);
        }
    }
}

$(document).on("keyup", '.input-text.qty', function (event) {
    updateItemTotals();
});
function updateItemTotals()
{
    let cartTotal = 0;
    for (let i = 0; i < items.length; i++) {
        let item = items[i];
        let quantity = document.getElementById("product_" + item.product_id + "_quantity").value;
        let newItemTotal = quantity * item.unit_price;
        if (newItemTotal < 999999.99)
        {
            document.getElementById("product_" + item.product_id + "_total_price").innerHTML = newItemTotal;
        }
        cartTotal += newItemTotal;
    }
    if (cartTotal > 999999)
    {
        displayMessage("Cart total cannot exceed €999'999!", 2000);
    } else
    {
        document.getElementById("total_price").innerHTML = cartTotal;
    }
}
async function updateCart() {

    let error_msg = "";
    for (let i = 0; i < items.length; i++) {
        let item = items[i];
        let newQuantity = document.getElementById("product_" + item.product_id + "_quantity").value;
        console.log(newQuantity);
        if (!(newQuantity >= 0))
        {
            error_msg += item.name + " quantity is not a valid integer!";
        } else if (newQuantity == 0)
        {
            //remove it
            let response = await updateProduct(item.product_id, newQuantity);
            if (!response.error)
            {
                document.getElementById("product_" + item.product_id).parentNode.removeChild(document.getElementById("product_" + item.product_id));
            } else
            {
                error_msg += response.error.msg + "<br>";
            }
        } else if (newQuantity !== item.quantity)
        {
            //modify quantity
            let response = await updateProduct(item.product_id, newQuantity);
            if (!response.error)
            {
                items[i].quantity = newQuantity;
            } else
            {
                error_msg += response.error.msg + "<br>";
            }
        }
    }
    if (error_msg.length === 0)
    {
        displayMessage("Cart has been updated successfully", 4500);
    } else
    {
        displayMessage("Cart has been updated<br>" + error_msg, 5000);
    }
    async function updateProduct(product_id, newQuantity)
    {
        let call_url = "api/products/update_order.php?product=" + product_id + "&quantity=" + newQuantity;
        try
        {
            const response = await fetch(call_url,
                    {
                        method: "GET",
                        headers: {'Content-type': 'application/x-www-form-urlencoded; charset=UTF-8'}
                    });

            return updateWebpage(await response.json());
        } catch (error)
        {
            console.log("Fetch failed: ", error);
            return error;
        }
    }
    function updateWebpage(response)
    {
        if (!response.error)
        {

        }
        return response;
    }
}

