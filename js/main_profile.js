async function changeNickname()
{
    let nickname_element = document.getElementById("nickname");
    let call_url = "api/users/change_nickname.php";
    let urlParameters = "nickname=" + nickname_element.value;
    console.log(nickname_element.value);
    try
    {
        const response = await fetch(call_url,
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
    function updateWebpage(response)
    {
        console.log(response);
    }

    function nicknameChangeMessage(message)
    {
        let url = new URL(window.location.href);
        let urlParams = url.searchParams;
        if (urlParams.get("change_status") === "success")
        {
            //nickname change success message
        } else if (urlParams.get("change_status") === "fail")
        {
            //nickname change failed message
        }
    }
}

