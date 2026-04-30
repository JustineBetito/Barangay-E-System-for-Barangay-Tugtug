// ============================================================
//  Barangay Tugtug E-System — Document Records Logic
//  File: Javascript/documentrequest.js
// ============================================================

document.addEventListener("DOMContentLoaded", function () {

    const recordsContainer = document.querySelector(".document-records");
    const searchInput      = document.querySelector(".search-input");
    const filterSelect     = document.querySelector(".filter-select");
    const btnDisplay       = document.querySelector(".btn-display");
    const btnPrint         = document.querySelector(".btn-print");

    // ── Inject date range inputs into filter group ────────────
    const filterGroup = document.querySelector(".filter-group");
    const dateWrapper = document.createElement("div");
    dateWrapper.id = "date-filter-wrapper";
    dateWrapper.style.cssText = `
        display: none; align-items: center; gap: 0.5vw;
        position: relative; left: 6vh;
    `;
    dateWrapper.innerHTML = `
        <label style="color:#f3efe8;font-size:1.5vh;font-weight:600;white-space:nowrap;">From:</label>
        <input type="date" id="date-from" style="
            background:#f3efe8;border:none;border-radius:5px;
            padding:5px 8px;color:#273b07;font-size:1.4vh;font-weight:600;cursor:pointer;outline:none;">
        <label style="color:#f3efe8;font-size:1.5vh;font-weight:600;white-space:nowrap;">To:</label>
        <input type="date" id="date-to" style="
            background:#f3efe8;border:none;border-radius:5px;
            padding:5px 8px;color:#273b07;font-size:1.4vh;font-weight:600;cursor:pointer;outline:none;">
        <button id="btn-apply-date" style="
            background:#7d9e3b;color:white;border:none;border-radius:5px;
            padding:5px 12px;font-weight:bold;cursor:pointer;font-size:1.4vh;transition:0.3s;">
            Apply
        </button>
    `;
    filterGroup.insertBefore(dateWrapper, document.querySelector(".btn-display"));

    // ── Show/hide date inputs ─────────────────────────────────
    filterSelect.addEventListener("change", function () {
        if (filterSelect.value === "date") {
            dateWrapper.style.display = "flex";
        } else {
            dateWrapper.style.display = "none";
            fetchRecords();
        }
    });

    document.addEventListener("click", function (e) {
        if (e.target && e.target.id === "btn-apply-date") fetchRecords();
    });

    // ── Status colors ─────────────────────────────────────────
    const statusColors = {
        "Pending":    { bg: "#fff3cd", color: "#856404", border: "#ffc107" },
        "Processing": { bg: "#cfe2ff", color: "#084298", border: "#0d6efd" },
        "Ready":      { bg: "#d1e7dd", color: "#0a3622", border: "#198754" },
    };

    // ── Init ──────────────────────────────────────────────────
    fetchRecords();

    // ── Events ────────────────────────────────────────────────
    btnDisplay.addEventListener("click", () => {
        filterSelect.value = "";
        dateWrapper.style.display = "none";
        document.getElementById("date-from").value = "";
        document.getElementById("date-to").value   = "";
        searchInput.value = "";
        fetchRecords();
    });
    searchInput.addEventListener("input", debounce(() => fetchRecords(), 400));
    btnPrint.addEventListener("click",    () => window.print());

    // Delegated click for dynamically rendered Update buttons
    recordsContainer.addEventListener("click", function (e) {
        const btn = e.target.closest(".btn-update-record");
        if (!btn) return;
        const rec = JSON.parse(btn.getAttribute("data-record").replace(/&apos;/g, "'"));
        openUpdateModal(rec);
    });

    // ── Fetch ─────────────────────────────────────────────────
    function fetchRecords() {
        const search   = searchInput.value.trim();
        const filter   = filterSelect.value;
        const dateFrom = document.getElementById("date-from") ? document.getElementById("date-from").value : "";
        const dateTo   = document.getElementById("date-to")   ? document.getElementById("date-to").value   : "";

        let url = "php/GetDocuments.php?";
        if (search)                        url += "search="    + encodeURIComponent(search)   + "&";
        if (filter && filter !== "date")   url += "filter="    + encodeURIComponent(filter)   + "&";
        if (filter === "date" && dateFrom) url += "date_from=" + encodeURIComponent(dateFrom) + "&";
        if (filter === "date" && dateTo)   url += "date_to="   + encodeURIComponent(dateTo)   + "&";

        recordsContainer.innerHTML = `
            <div style="display:flex;justify-content:center;align-items:center;
                height:30vh;color:#375309;font-family:'Segoe UI',sans-serif;font-size:2vh;gap:1vw;">
                <span style="width:2.5vh;height:2.5vh;border:3px solid #375309;
                    border-top-color:transparent;border-radius:50%;
                    display:inline-block;animation:spin 0.7s linear infinite;"></span>
                Loading records…
            </div>`;

        fetch(url)
            .then(res => res.json())
            .then(data => {
                if (!data.success) { showError(data.message || "Failed to load records."); return; }
                updateCounts(data.counts);
                renderTable(data.records);
            })
            .catch(err => { console.error(err); showError("Server error. Please try again."); });
    }

    // ── Summary cards ─────────────────────────────────────────
    function updateCounts(counts) {
        setCount(document.querySelector(".total"),      counts.Total      || 0);
        setCount(document.querySelector(".processing"), counts.Processing || 0);
        setCount(document.querySelector(".pending"),    counts.Pending    || 0);
        setCount(document.querySelector(".ready"),      counts.Ready      || 0);
    }
    function setCount(el, count) {
        if (!el) return;
        let numEl = el.querySelector(".count-number");
        if (!numEl) {
            numEl = document.createElement("p");
            numEl.className = "count-number";
            numEl.style.cssText = `position:absolute;bottom:1vh;left:1vw;
                font-size:4vh;font-weight:700;color:#273b07;margin:0;
                font-family:'Segoe UI',Tahoma,sans-serif;`;
            el.appendChild(numEl);
        }
        numEl.textContent = count;
    }

    // ── Render table ──────────────────────────────────────────
    function renderTable(records) {

        recordsContainer.style.cssText = `
            position: relative;
            top: 12vh;
            padding: 0 1.5%;
            padding-bottom: 3vh;
        `;

        if (!records || records.length === 0) {
            recordsContainer.innerHTML = `
                <div style="display:flex;justify-content:center;align-items:center;
                    height:30vh;color:#888;font-family:'Segoe UI',sans-serif;font-size:2vh;">
                    No records found.</div>`;
            return;
        }

        const table = document.createElement("table");
        table.style.cssText = `width:100%;border-collapse:collapse;
            font-family:'Segoe UI',Tahoma,sans-serif;font-size:1.6vh;`;

        table.innerHTML = `
            <thead>
                <tr style="background-color:#273b07;color:#f3efe8;position:sticky;top:0;z-index:1;">
                    <th style="${th()}">📋 Req. ID</th>
                    <th style="${th()}">🪪 Resident ID</th>
                    <th style="${th()}">👤 Full Name</th>
                    <th style="${th()}">⚧ Sex</th>
                    <th style="${th()}">📞 Contact</th>
                    <th style="${th()}">📄 Document Purpose</th>
                    <th style="${th()}">📅 Date Requested</th>
                    <th style="${th()}">📅 Date Released</th>
                    <th style="${th()}">🔢 Qty</th>
                    <th style="${th()}">🔖 Status</th>
                    <th style="${th()}">⚙ Action</th>
                </tr>
            </thead>
            <tbody id="records-tbody"></tbody>`;

        const tbody = table.querySelector("#records-tbody");

        records.forEach((rec, i) => {
            const tr = document.createElement("tr");
            tr.style.cssText = `background-color:${i % 2 === 0 ? "#fafaf7" : "#f3efe8"};transition:background-color 0.2s;`;
            tr.onmouseover = () => tr.style.backgroundColor = "#e8f0d8";
            tr.onmouseout  = () => tr.style.backgroundColor = i % 2 === 0 ? "#fafaf7" : "#f3efe8";

            const fullName = `${rec.first_name || ""} ${rec.middle_initial ? rec.middle_initial + ". " : ""}${rec.last_name || ""}`.trim();
            const sc = statusColors[rec.status] || { bg:"#eee", color:"#333", border:"#aaa" };

            tr.innerHTML = `
                <td style="${td()}text-align:center;">${rec.request_ID}</td>
                <td style="${td()}text-align:center;">${rec.resident_ID}</td>
                <td style="${td()}">${fullName}</td>
                <td style="${td()}text-align:center;">${rec.sex || "—"}</td>
                <td style="${td()}text-align:center;">${rec.contact || "—"}</td>
                <td style="${td()}">${rec.document_purpose || "—"}</td>
                <td style="${td()}text-align:center;">${fmtDate(rec.date)}</td>
                <td style="${td()}text-align:center;">${rec.date_released ? fmtDate(rec.date_released) : "—"}</td>
                <td style="${td()}text-align:center;">${rec.quantity || 0}</td>
                <td style="${td()}text-align:center;">
                    <span style="background:${sc.bg};color:${sc.color};border:1px solid ${sc.border};
                        padding:0.4vh 0.8vw;border-radius:20px;font-size:1.4vh;font-weight:600;white-space:nowrap;">
                        ${rec.status}
                    </span>
                </td>
                <td style="${td()}text-align:center;">
                    <button class="btn-update-record"
                        data-record='${JSON.stringify(rec).replace(/'/g,"&apos;")}'
                        style="background:#375309;color:#f3efe8;border:none;border-radius:5px;
                            padding:0.5vh 0.8vw;cursor:pointer;font-size:1.4vh;font-weight:600;
                            transition:background 0.2s;white-space:nowrap;"
                        onmouseover="this.style.background='#7d9e3b'"
                        onmouseout="this.style.background='#375309'">
                        ✏ Update
                    </button>
                </td>`;
            tbody.appendChild(tr);
        });

        recordsContainer.innerHTML = "";
        recordsContainer.appendChild(table);
    }

    function th() { return `padding:1.2vh 1vw;text-align:left;font-size:1.5vh;font-weight:600;white-space:nowrap;`; }
    function td() { return `padding:1vh 1vw;border-bottom:1px solid #ddd;`; }
    function fmtDate(d) {
        if (!d) return "—";
        return new Date(d).toLocaleDateString("en-PH", { year:"numeric", month:"short", day:"numeric" });
    }

    function showError(msg) {
        recordsContainer.innerHTML = `<div style="display:flex;justify-content:center;align-items:center;
            height:30vh;color:#cc0000;font-family:'Segoe UI',sans-serif;font-size:2vh;">${msg}</div>`;
    }
    function debounce(fn, delay) {
        let t;
        return function (...args) { clearTimeout(t); t = setTimeout(() => fn.apply(this, args), delay); };
    }

    // ── CSS ───────────────────────────────────────────────────
    const style = document.createElement("style");
    style.textContent = `
        @keyframes spin { to { transform:rotate(360deg); } }
        .modal-overlay { position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:9999;
            display:flex;align-items:center;justify-content:center;animation:fadeIn 0.2s ease; }
        .modal-box { background:#f3efe8;border-radius:15px;padding:3vh 2.5vw;width:38vw;min-width:320px;
            box-shadow:0 10px 40px rgba(0,0,0,0.3);font-family:'Segoe UI',Tahoma,sans-serif; }
        .modal-title { font-size:2.2vh;font-weight:700;color:#273b07;margin-bottom:2vh;font-family:'Crimson Text',serif; }
        .modal-label { font-size:1.6vh;color:#375309;font-weight:600;margin-bottom:0.5vh;display:block; }
        .modal-select { width:100%;padding:1vh 1vw;border-radius:8px;border:1.5px solid #7d9e3b;
            font-size:1.7vh;background:white;color:#273b07;margin-bottom:2.5vh;cursor:pointer;outline:none; }
        .modal-select:focus { border-color:#375309; }
        .modal-buttons { display:flex;gap:1vw;justify-content:flex-end; }
        .modal-btn-cancel { background:transparent;border:2px solid #375309;color:#375309;
            padding:0.8vh 1.5vw;border-radius:8px;font-size:1.6vh;font-weight:600;cursor:pointer;transition:0.2s; }
        .modal-btn-cancel:hover { background:#375309;color:white; }
        .modal-btn-save { background:#375309;border:none;color:#f3efe8;padding:0.8vh 1.5vw;
            border-radius:8px;font-size:1.6vh;font-weight:600;cursor:pointer;transition:0.2s; }
        .modal-btn-save:hover { background:#7d9e3b; }
        .modal-resident { font-size:1.5vh;color:#555;margin-bottom:2vh; }
        .toast { position:fixed;bottom:4vh;right:2vw;background:#273b07;color:#f3efe8;
            padding:1.5vh 2vw;border-radius:10px;font-size:1.7vh;font-family:'Segoe UI',sans-serif;
            z-index:99999;box-shadow:0 4px 20px rgba(0,0,0,0.3);animation:slideUp 0.3s ease; }
        @keyframes slideUp { from{transform:translateY(20px);opacity:0} to{transform:translateY(0);opacity:1} }
    `;
    document.head.appendChild(style);

    // ── Modal ─────────────────────────────────────────────────
    function openUpdateModal(rec) {
        const existing = document.getElementById("update-modal");
        if (existing) existing.remove();

        const fullName = `${rec.first_name || ""} ${rec.middle_initial ? rec.middle_initial + ". " : ""}${rec.last_name || ""}`.trim();

        function field(label, value) {
            return `
                <div style="display:flex;justify-content:space-between;align-items:center;
                    padding:0.8vh 0;border-bottom:1px solid #dde8cc;">
                    <span style="font-size:1.4vh;color:#666;font-weight:600;min-width:40%;">${label}</span>
                    <span style="font-size:1.5vh;color:#273b07;text-align:right;">${value || "—"}</span>
                </div>`;
        }

        // ✅ Format price — show "Free" if 0, otherwise show ₱ amount
        const priceDisplay = (!rec.price || rec.price == 0)
            ? '<span style="color:#0a3622;font-weight:700;">Free</span>'
            : `<span style="color:#0a3622;font-weight:700;">₱${parseFloat(rec.price).toFixed(2)}</span>`;

        const overlay = document.createElement("div");
        overlay.className = "modal-overlay";
        overlay.id = "update-modal";
        overlay.innerHTML = `
            <div class="modal-box" style="width:38vw;min-width:320px;max-height:85vh;overflow-y:auto;">
                <div class="modal-title"> Request Details  #${rec.request_ID}</div>

                <div style="background:#e8f0d8;border-radius:10px;padding:1.5vh 1.5vw;margin-bottom:2vh;">
                    <div style="font-size:1.3vh;color:#375309;font-weight:700;margin-bottom:1vh;letter-spacing:0.05em;">
                        RESIDENT INFORMATION
                    </div>
                    ${field("Resident ID",  rec.resident_ID)}
                    ${field("Full Name",    fullName)}
                    ${field("Sex",          rec.sex)}
                    ${field("Birthdate",    rec.birthdate ? fmtDate(rec.birthdate) : "—")}
                </div>

                <div style="background:#f0ede4;border-radius:10px;padding:1.5vh 1.5vw;margin-bottom:2vh;">
                    <div style="font-size:1.3vh;color:#375309;font-weight:700;margin-bottom:1vh;letter-spacing:0.05em;">
                        REQUEST INFORMATION
                    </div>
                    ${field("Request ID",       rec.request_ID)}
                    ${field("Document Type",    rec.document_type || "—")}
                    ${field("Contact",          rec.contact)}
                    ${field("Document Purpose", rec.document_purpose)}
                    ${field("Date Requested",   fmtDate(rec.date))}
                    ${field("Date Released",    rec.date_released ? fmtDate(rec.date_released) : "—")}
                    ${field("Age",              rec.age)}
                    ${field("Length of Stay",   (rec.length_stay_years || 0) + " yr(s) " + (rec.length_stay_months || 0) + " mo(s)")}
                    ${field("Quantity",         rec.quantity)}
                    <div style="display:flex;justify-content:space-between;align-items:center;
                        padding:0.8vh 0;">
                        <span style="font-size:1.4vh;color:#666;font-weight:600;min-width:40%;">Price</span>
                        ${priceDisplay}
                    </div>
                </div>

                <div style="background:#fff8e6;border-radius:10px;padding:1.5vh 1.5vw;margin-bottom:2vh;">
                    <div style="font-size:1.3vh;color:#856404;font-weight:700;margin-bottom:1vh;letter-spacing:0.05em;">
                        UPDATE STATUS
                    </div>
                    <label class="modal-label">Current Status:
                        <span style="
                            margin-left:1vw;padding:0.3vh 0.8vw;border-radius:20px;font-size:1.4vh;
                            background:${rec.status==="Pending" ? "#fff3cd" : rec.status==="Processing" ? "#cfe2ff" : "#d1e7dd"};
                            color:${rec.status==="Pending" ? "#856404" : rec.status==="Processing" ? "#084298" : "#0a3622"};
                            border:1px solid ${rec.status==="Pending" ? "#ffc107" : rec.status==="Processing" ? "#0d6efd" : "#198754"};
                        ">${rec.status}</span>
                    </label>
                    <label class="modal-label" style="margin-top:1.5vh;">New Status</label>
                    <select class="modal-select" id="modal-status-select">
                        <option value="Pending"    ${rec.status==="Pending"    ? "selected":""}>Pending</option>
                        <option value="Processing" ${rec.status==="Processing" ? "selected":""}>Processing</option>
                        <option value="Ready"      ${rec.status==="Ready"      ? "selected":""}>Ready</option>
                    </select>
                </div>

                <div class="modal-buttons">
                    <button class="modal-btn-cancel" onclick="document.getElementById('update-modal').remove()">Cancel</button>
                    <button class="modal-btn-save" onclick="saveStatus(${rec.request_ID})">Save Changes</button>
                </div>
            </div>`;
        overlay.addEventListener("click", e => { if (e.target === overlay) overlay.remove(); });
        document.body.appendChild(overlay);
    }

    window.saveStatus = function (requestId) {
        const newStatus = document.getElementById("modal-status-select").value;
        const saveBtn   = document.querySelector(".modal-btn-save");
        saveBtn.textContent = "Saving…";
        saveBtn.disabled    = true;

        fetch("php/GetDocuments.php", {
            method : "POST",
            headers: { "Content-Type": "application/json" },
            body   : JSON.stringify({ request_ID: requestId, status: newStatus })
        })
        .then(res => res.json())
        .then(data => {
            document.getElementById("update-modal").remove();
            showToast(data.success ? "✅ Status updated to " + newStatus : "❌ " + (data.message || "Update failed."));
            if (data.success) fetchRecords();
        })
        .catch(() => {
            document.getElementById("update-modal").remove();
            showToast("❌ Server error. Please try again.");
        });
    };

    function showToast(msg) {
        const ex = document.querySelector(".toast");
        if (ex) ex.remove();
        const t = document.createElement("div");
        t.className = "toast";
        t.textContent = msg;
        document.body.appendChild(t);
        setTimeout(() => t.remove(), 3000);
    }

});