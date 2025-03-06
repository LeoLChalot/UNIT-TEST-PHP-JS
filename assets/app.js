import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';
import './styles/leo_custom.css';
import './styles/login.css'
import './styles/Commun.css'
import './styles/Employe.css'

document.addEventListener('DOMContentLoaded', (event) => {
    var map = L.map("map", {
        center: [52.3727598, 4.8936041],
        zoom: 14,
    });
    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", { attribution: "Edifis Pro" }).addTo(map);
    map.addControl(L.control.search());

    map.on('search:locationfound', function(e) {
        var req = document.getElementById('adresse').value;

        
        var url = `https://nominatim.openstreetmap.org/search?format=json&q=${req}`;
        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data.length > 0) {
                    var result = data[0];
                    map.setView([result.lat, result.lon], 14);
                    L.marker([result.lat, result.lon]).addTo(map)
                        .bindPopup(result.display_name)
                        .openPopup();
                } else {
                    alert("No results found");
                }
            })
            .catch(error => console.error('Error:', error));
    });
});