window.onload = onWindowLoaded();
let test;
let test2;
function onWindowLoaded()
{
    getOrderId();
}
async function getOrderId()
{
    let session_id = new URL(window.location.href).searchParams.get("session_id");
    let call_url = "api/orders/retrieve_stripe_session.php?session_id=" + session_id;
    try
    {
        const response = await fetch(call_url,
                {
                    method: "GET",
                    headers: {'Content-type': 'application/x-www-form-urlencoded; charset=UTF-8'}
                });

        getProducts(await response.json());
    } catch (error)
    {
        console.log("Fetch failed: ", error);
    }
    async function getProducts(order_id)
    {
        test = order_id;
        console.log(order_id);
        if (!order_id.error)
        {
            let call_url = "api/orders/get.php?order=" + order_id;
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
        } else
        {
            //error, order not found, user may not be allowed to view this order, session doesn't exist etc.
        }
        function updateWebpage(response)
        {
            test2 = response;
            console.log(response);
            if (response.data)
            {

            }
        }
    }
}