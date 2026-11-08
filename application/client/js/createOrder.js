const orderForm = document.getElementById("orderForm");
const orderMessage = document.getElementById("orderMessage");

orderForm.addEventListener("submit", async function (event) {
    event.preventDefault();

    const formData = new FormData(orderForm);

    try {
        const response = await fetch("../server/createOrder.php", {
            method: "POST",
            body: formData
        });

        const result = await response.json();

        if (response.ok && result.success) {
            orderMessage.style.color = "green";
            orderMessage.textContent = result.message;

            orderForm.reset();
        } else {
            orderMessage.style.color = "#a33";
            orderMessage.textContent = result.message || "Unable to create order.";
        }

    } catch (error) {
        orderMessage.style.color = "#a33";
        orderMessage.textContent = "Unable to connect to the server.";
    }
});
