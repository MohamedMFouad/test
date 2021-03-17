console.log("hello world");
if(window.location.pathname == "/order-tracking/") {
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    const orderId = urlParams.get('orderid');
    const email = urlParams.get('email');
    if(orderId && email) {
        document.getElementById("orderid").value = orderId;
        document.getElementById("order_email").value = email;
    
        setTimeout(() => {
            document.getElementsByClassName("woocommerce-form-track-order")[0].submit();    
        }, 500);
    }
}



if(window.location.pathname == "/checkout/") {
	document.querySelector('.fee th').innerHtml = "Free Shipping";
	
	 setTimeout(() => {
            document.querySelector('.fee th').innerHtml = "Free Shipping";   
        }, 500);
}