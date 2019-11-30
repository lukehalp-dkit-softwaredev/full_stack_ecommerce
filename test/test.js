//let auth0 = null;
//console.log("load2");
//const fetchAuthConfig = () => fetch("auth_config.json");
//const configureClient = async () => {
//    const response = await fetchAuthConfig();
//    const config = await response.json();
//
//    auth0 = await createAuth0Client({
//        domain: config.domain,
//        client_id: config.clientId
//    });
//};
//
//window.onload = async () => {
//    await configureClient();
//    const isAuthenticated = await auth0.isAuthenticated();
//
//    updateUI();
//    if (isAuthenticated) {
//        // show the gated content
//        return;
//    }
//
//    // NEW - check for the code and state parameters
//    const query = window.location.search;
//    if (query.includes("code=") && query.includes("state=")) {
//
//        // Process the login state
//        await auth0.handleRedirectCallback();
//
//        updateUI();
//
//        // Use replaceState to redirect the user away and remove the querystring parameters
//        window.history.replaceState({}, document.title, window.location.pathname);
//    }
//};
//
//const updateUI = async () => {
//    const isAuthenticated = await auth0.isAuthenticated();
//    console.log(isAuthenticated + " isAuthenticated");
//    document.getElementById("btn-logout").disabled = !isAuthenticated;
//    document.getElementById("btn-login").disabled = isAuthenticated;
//    if (isAuthenticated) {
//        document.getElementById("gated-content").classList.remove("hidden");
//
//        document.getElementById(
//                "ipt-access-token"
//                ).innerHTML = await auth0.getTokenSilently();
//
//        document.getElementById("ipt-user-profile").innerHTML = JSON.stringify(
//                await auth0.getUser()
//                );
//
//    } else {
//        document.getElementById("gated-content").classList.add("hidden");
//    }
//};
//
//const login = async () => {
//    await auth0.loginWithRedirect({
//        redirect_uri: window.location.href
//    });
//    //use origin so goes to index once logged in
//    console.log(window.location.origin);
//};
//
//const logout = () => {
//    auth0.logout({
//        returnTo: window.location.href
//    });
//};
//
