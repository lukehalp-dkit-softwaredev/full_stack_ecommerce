window.onload = onWindowLoaded();
function onWindowLoaded()
{
    displayProduct();
}
async function displayProduct()
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

        updateWebpage(await response.json());
    } catch (error)
    {
        console.log("Fetch failed: ", error);
    }
    function updateWebpage(response)
    {
        test = response;
        console.log(response);
    }
}