const serviceList = document.getElementById("services");
const serviceSelect = document.getElementById("service_id");
const bookingForm = document.getElementById("bookingForm");
const message = document.getElementById("message");
const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

async function loadServices() {
    try {
        const response = await fetch("services.php", { headers: { "Accept": "application/json" } });
        if (!response.ok) throw new Error("Could not load experiences.");
        const services = await response.json();

        serviceList.replaceChildren();
        serviceSelect.innerHTML = '<option value="">Select an experience</option>';

        for (const service of services) {
            const option = document.createElement("option");
            option.value = service.service_id;
            option.textContent = service.service_name;
            serviceSelect.appendChild(option);

            const card = document.createElement("article");
            card.className = "card";
            const image = document.createElement("img");
            image.src = service.image_path || "";
            image.alt = service.service_name;
            image.loading = "lazy";
            const content = document.createElement("div");
            const category = document.createElement("p");
            category.className = "eyebrow";
            category.textContent = service.category || "Experience";
            const heading = document.createElement("h3");
            heading.textContent = service.service_name;
            const location = document.createElement("p");
            location.textContent = service.location || "";
            const description = document.createElement("p");
            description.textContent = service.description || "";
            const details = document.createElement("details");
            const summary = document.createElement("summary");
            summary.textContent = "More details";
            const detailText = document.createElement("p");
            detailText.textContent = service.description || "No additional details.";
            details.append(summary, detailText);
            const choose = document.createElement("button");
            choose.type = "button";
            choose.className = "text-button";
            choose.textContent = "Choose this experience";
            choose.addEventListener("click", () => {
                serviceSelect.value = service.service_id;
                document.getElementById("booking").scrollIntoView({ behavior: "smooth" });
            });
            content.append(category, heading, location, description, details, choose);
            card.append(image, content);
            serviceList.appendChild(card);
        }

        if (services.length === 0) {
            serviceList.textContent = "No active experiences are currently listed.";
            serviceSelect.innerHTML = '<option value="">No experiences available</option>';
        }
    } catch (error) {
        serviceList.textContent = error.message + " Check that Apache, MySQL and the database are running.";
        serviceSelect.innerHTML = '<option value="">Experiences unavailable</option>';
    }
}

bookingForm.addEventListener("submit", async (event) => {
    event.preventDefault();
    message.textContent = "Submitting request…";
    const payload = Object.fromEntries(new FormData(bookingForm).entries());

    try {
        const response = await fetch("book.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-Token": csrfToken
            },
            body: JSON.stringify(payload)
        });
        const result = await response.json();
        if (!response.ok) throw new Error(result.message || "The request could not be submitted.");
        message.textContent = result.message;
        bookingForm.reset();
    } catch (error) {
        message.textContent = error.message;
    }
});

loadServices();
