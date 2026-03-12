(function () {
  const script = document.currentScript;
  const siteKey = script.getAttribute("data-site-key");
  const apiUrl = script.src.replace("/widget/widget.js", "/api/submit-feedback.php");

  async function loadWidgetSettings() {
    try {
      const res = await fetch(script.src.replace("/widget/widget.js", "/api/widget-config.php?site_key=" + encodeURIComponent(siteKey)));
      return await res.json();
    } catch (e) {
      return { button_text: "Feedback", button_color: "#0b1730", position: "right" };
    }
  }

  loadWidgetSettings().then((settings) => {
    const buttonText = settings.button_text || "Feedback";
    const buttonColor = settings.button_color || "#0b1730";
    const position = settings.position === "left" ? "left" : "right";

    const wrap = document.createElement("div");
    wrap.innerHTML = `
      <button id="ff-btn" style="position:fixed;bottom:20px;${position}:20px;z-index:99999;background:${buttonColor};color:#fff;border:none;border-radius:999px;padding:12px 18px;cursor:pointer;box-shadow:0 8px 20px rgba(0,0,0,.2)">${buttonText}</button>
      <div id="ff-modal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,.35);z-index:99998">
        <div style="max-width:420px;margin:8vh auto;background:#fff;border-radius:16px;padding:20px;font-family:Arial,sans-serif">
          <h3 style="margin-top:0">Share feedback</h3>
          <input id="ff-name" placeholder="Name (optional)" style="width:100%;padding:10px;border:1px solid #ddd;border-radius:8px;margin-bottom:10px;box-sizing:border-box">
          <input id="ff-email" placeholder="Email (optional)" style="width:100%;padding:10px;border:1px solid #ddd;border-radius:8px;margin-bottom:10px;box-sizing:border-box">
          <select id="ff-rating" style="width:100%;padding:10px;border:1px solid #ddd;border-radius:8px;margin-bottom:10px;box-sizing:border-box">
            <option value="5">5 - Excellent</option>
            <option value="4">4 - Good</option>
            <option value="3">3 - Okay</option>
            <option value="2">2 - Poor</option>
            <option value="1">1 - Bad</option>
          </select>
          <textarea id="ff-message" placeholder="Tell us what can be improved" style="width:100%;min-height:110px;padding:10px;border:1px solid #ddd;border-radius:8px;margin-bottom:10px;box-sizing:border-box"></textarea>
          <div style="display:flex;gap:10px;justify-content:flex-end">
            <button id="ff-close" style="background:#e5e7eb;color:#111827;border:none;border-radius:10px;padding:10px 14px;cursor:pointer">Close</button>
            <button id="ff-submit" style="background:${buttonColor};color:#fff;border:none;border-radius:10px;padding:10px 14px;cursor:pointer">Send</button>
          </div>
        </div>
      </div>
    `;
    document.body.appendChild(wrap);

    const btn = document.getElementById("ff-btn");
    const modal = document.getElementById("ff-modal");
    const closeBtn = document.getElementById("ff-close");
    const submitBtn = document.getElementById("ff-submit");

    btn.onclick = () => modal.style.display = "block";
    closeBtn.onclick = () => modal.style.display = "none";

    submitBtn.onclick = async () => {
      const payload = {
        site_key: siteKey,
        user_name: document.getElementById("ff-name").value,
        user_email: document.getElementById("ff-email").value,
        rating: document.getElementById("ff-rating").value,
        message: document.getElementById("ff-message").value,
        page_url: window.location.href
      };

      if (!payload.message.trim()) {
        alert("Please enter your feedback.");
        return;
      }

      const res = await fetch(apiUrl, {
        method: "POST",
        headers: { "Content-Type":"application/json" },
        body: JSON.stringify(payload)
      });

      const data = await res.json();
      if (data.status === "success") {
        alert("Thank you for your feedback!");
        modal.style.display = "none";
        document.getElementById("ff-message").value = "";
      } else {
        alert(data.message || "Something went wrong.");
      }
    };
  });
})();
