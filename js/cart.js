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
        failUpdateWebpage(error);
    }

    function updateWebpage(response) {
        let items = response.data.items;
        for(let i=0;i<items.length;i++) {
            let item = items[i];
            let htmlString =
            `<tr>
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
                        <input type="text" name="qty" id="sst" maxlength="12" value="${ item.quantity }" title="Quantity:"
                            class="input-text qty">
                        <button onclick="var result = document.getElementById('sst'); var sst = result.value; if( !isNaN( sst )) result.value++;return false;"
                            class="increase items-count" type="button"><i class="lnr lnr-chevron-up"></i></button>
                        <button onclick="var result = document.getElementById('sst'); var sst = result.value; if( !isNaN( sst ) &amp;&amp; sst > 0 ) result.value--;return false;"
                            class="reduced items-count" type="button"><i class="lnr lnr-chevron-down"></i></button>
                    </div>
                </td>
                <td>
                    <h5>€${ item.unit_price * item.quantity }</h5>
                </td>
            </tr>`;
            console.log(htmlString);
            $("#cartlist").prepend(htmlString);
        }
    }

    function failUpdateWebpage(error) {

    }
}

