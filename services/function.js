const SKIN_ENDPOINT = "https://minecraft-api.com/api/skins/";

function redirectToCheckout() {
    window.location.replace('/services/create-checkout-session.php?username=' + document.querySelector('input').value);
}

async function getSkin(username) {
    const response = await fetch(SKIN_ENDPOINT + username + '/json');
    const resp = await response.text();
    
    /**
     * // check if user skin exist
     * if b64 img less than 500 characters => empty img
     *  */ 
    if(resp.length > 500) {
        // display skin avatar using username
        displaySkin(resp);
    }
}

function displaySkin(img_html) {
    let skin_btn = document.getElementById("skin_btn");
    let skin_img = document.getElementById("skin_img");
    let stripe_btn = document.getElementById("stripe_btn");

    skin_btn.classList.add('hidden');
    skin_img.classList.remove('hidden');
    stripe_btn.classList.remove('hidden');

    const b64 = img_html.match(/src="(.*?)" alt/i)[1]
    document.getElementById("img_src").src = b64;

    document.getElementById("username_label").innerHTML = 'Utilisateur Minecraft trouver avec succès!'

    // disabled input search username
    document.getElementsByTagName("input")[0].setAttribute("disabled", ""); 
}

function getUrlParams(params) {
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    return urlParams.get(params);
}
