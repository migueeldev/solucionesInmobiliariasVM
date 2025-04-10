document.getElementById("filterCity").addEventListener("change", function () {
    const city = this.value;
    fetch(`filter_properties.php?city=${city}`)
        .then(response => response.json())
        .then(data => {
            let output = '';
            data.forEach(property => {
                output += `
                    <div class="col-md-4">
                        <div class="card">
                            <img src="images/${property.image}" class="card-img-top" alt="Imagen de la propiedad">
                            <div class="card-body">
                                <h5 class="card-title">${property.location}</h5>
                                <p class="card-text">Precio: $${property.price}</p>
                                <p class="card-text">Ciudad: ${property.city}</p>
                                <a href="pages/view_property.php?id=${property.id}" class="btn btn-primary">Ver más</a>
                            </div>
                        </div>
                    </div>`;
            });
            document.getElementById("propertiesContainer").innerHTML = output;
        });
});
