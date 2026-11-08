const form = document.getElementById("searchForm");
const button = document.getElementById("showAllButton");
const result = document.getElementById("ordersResult");

// Search order
form.addEventListener("submit", function(event) {
    event.preventDefault();

    const id = document.getElementById("orderId").value;
    loadOrders(id);
});

button.addEventListener("click", function() {
    loadOrders("");
});


function loadOrders(id) {

    const data = new FormData();
    data.append("orderId", id);

    fetch("../server/viewOrders.php", {
        method: "POST",
        body: data
    })
    .then(response => response.json())
    .then(data => {

        result.innerHTML = "";

        if (!data.success) {
            result.textContent = data.message;
            return;
        }

        data.orders.forEach(order => {
            result.innerHTML += `
                <div class="documentation-card">
                    <h2>Order ${order.orderId}</h2>
                    <p>Customer: ${order.customerName}</p>
                    <p>Email: ${order.email}</p>
                    <p>Total with VAT: €${order.totalWithVat}</p>
                </div>
            `;
        });

    })
    .catch(() => {
        result.textContent = "Server connection error.";
    });
}
