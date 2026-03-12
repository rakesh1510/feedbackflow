(function(){
  const script = document.currentScript;
  const siteKey = script && script.getAttribute("data-site-key") ? script.getAttribute("data-site-key") : "";

  var btn=document.createElement("button");
  btn.innerText="Feedback";
  btn.style.position="fixed";
  btn.style.bottom="20px";
  btn.style.right="20px";
  btn.style.padding="10px 15px";
  btn.style.background="#0b1730";
  btn.style.color="#fff";
  btn.style.borderRadius="10px";
  btn.style.border="none";
  btn.style.cursor="pointer";
  document.body.appendChild(btn);

  btn.onclick=function(){
    var message=prompt("Your feedback:");
    if(!message)return;

    fetch("/feedback/api/submit-feedback.php",{
      method:"POST",
      headers:{"Content-Type":"application/json"},
      body:JSON.stringify({message:message,rating:5,site_key:siteKey})
    })
    .then(r=>r.json())
    .then(()=>alert("Thanks for your feedback!"))
    .catch(()=>alert("Could not send feedback"));
  };
})();