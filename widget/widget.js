(function(){
    const script = document.currentScript;
    const siteKey = script.getAttribute("data-site-key");

    const btn = document.createElement("button");
    btn.innerText = "Feedback";
    btn.style.position = "fixed";
    btn.style.bottom = "20px";
    btn.style.right = "20px";
    btn.style.padding = "10px 15px";
    btn.style.background = "#000";
    btn.style.color = "#fff";
    btn.style.border = "none";
    btn.style.cursor = "pointer";

    document.body.appendChild(btn);

    btn.onclick = function(){
        const message = prompt("Your feedback:");
        if(!message) return;

        fetch(script.src.replace("widget/widget.js","api/submit-feedback.php"),{
            method:"POST",
            headers:{"Content-Type":"application/json"},
            body:JSON.stringify({
                site_key:siteKey,
                rating:5,
                message:message,
                page_url:window.location.href
            })
        });
        alert("Thank you for your feedback!");
    }
})();