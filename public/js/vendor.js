var vendors = document.getElementById("vendors");
async function fetchVendors() {
    let e = await (
        await fetch("http://localhost:8800/api/ssd/asn/vendors", {
            method: "GET",
            headers: Object.assign({}, ...[{ Accept: "*/*" }]),
        })
    ).json();
    if (e.length > 0) {
        let n = e[0].H_vid;
        e.forEach((e) => {
            let n = document.createElement("option");
            (n.value = e.H_vid),
                (n.innerText = e.H_vendor),
                vendors.appendChild(n);
        }),
            (vendors.value = n),
            vendors.addEventListener("change", function () {
                vendors.value;
            });
    }
}
fetchVendors();
