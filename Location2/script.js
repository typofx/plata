// Lazy loading for Google Maps
let mapsLoaded = false;

/**
 * Loads the Google Maps API script dynamically.
 */
function loadGoogleMaps() {
    if (mapsLoaded) return;
    mapsLoaded = true;

    const script = document.createElement('script');
    script.src = 'https://maps.googleapis.com/maps/api/js?key=' +
        CONFIG.GOOGLE_MAPS_API_KEY +
        '&callback=initMap&loading=async';
    script.async = true;
    script.defer = true;
    document.body.appendChild(script);
}

/**
 * Initializes the map, markers, and events.
 */
async function initMap() {
    // Branch coordinates
    const locations = {
        dublin: { lat: 53.33533135354178, lng: -6.2653143722446165, title: "77 Camden Street Lower, Dublin 2" },
        santos: { lat: -23.9525136, lng: -46.3274502, title: "Av. Senador Feijó, 686 cj 621, Santos/SP" }
    };

    const mapContainer = document.querySelector('#map-view');

    if (!mapContainer) {
        return;
    }

    try {
        const { Map } = await google.maps.importLibrary("maps");
        const { AdvancedMarkerElement } = await google.maps.importLibrary("marker");
        const { LatLngBounds } = await google.maps.importLibrary("core");

        const map = new Map(mapContainer, {
            mapId: "124618566934ee813e326279",
            mapTypeId: 'roadmap',
            mapTypeControl: true,
            streetViewControl: true,
            gestureHandling: 'auto'
        });

        // Object to calculate map bounds
        const bounds = new LatLngBounds();

        // Add Dublin Marker
        const markerDublin = new AdvancedMarkerElement({
            map: map,
            position: locations.dublin,
            title: locations.dublin.title
        });
        bounds.extend(locations.dublin);

        markerDublin.addListener('click', () => {
            const streetViewUrl = `https://www.google.com/maps?layer=c&cbll=${locations.dublin.lat},${locations.dublin.lng}&cbp=12,295,,0,0`;
            window.open(streetViewUrl, '_blank');
        });

        // Add Santos Marker
        const markerSantos = new AdvancedMarkerElement({
            map: map,
            position: locations.santos,
            title: locations.santos.title
        });
        bounds.extend(locations.santos);

        // Fits the map to show all markers at once
        map.fitBounds(bounds);

        markerSantos.addListener('click', () => {
            // cbll: lat and lng | cbp: 12, heading, pitch, zoom, tilt
            const streetViewUrl = `https://www.google.com/maps?layer=c&cbll=${locations.santos.lat},${locations.santos.lng}&cbp=12,302.61,,0,-22`;
            window.open(streetViewUrl, '_blank');
        });

        // Global function to pan map perspective (used by contact list)
        window.panToLocation = function (locationKey) {
            if (locations[locationKey]) {
                map.setCenter(locations[locationKey]);
                map.setZoom(16);
            }
        };

    } catch (error) {
        mapContainer.innerHTML = '<p style="padding: 20px; text-align: center; color: #999;">Map unavailable at the moment.</p>';
    }
}

// Initialization and Observers
document.addEventListener('DOMContentLoaded', function () {

    const mapContainer = document.querySelector('#map-view');

    if (mapContainer) {
        // Only load maps API when the map enters the viewport
        const observer = new IntersectionObserver((entries) => {
            if (entries[0].isIntersecting) {
                loadGoogleMaps();
                observer.disconnect();
            }
        }, {
            rootMargin: '50px'
        });

        observer.observe(mapContainer);
    } else {
        loadGoogleMaps();
    }

    // Form Submittion Logic
    const form = document.getElementById('contactForm');

    if (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(form);
            const data = {
                question: formData.get('question'),
                name: formData.get('name'),
                phone: formData.get('phone'),
                email: formData.get('email')
            };

            // Visual success feedback
            const btn = form.querySelector('.btn-submit');
            const originalText = btn.innerText;
            btn.innerText = 'SENT!';
            btn.style.background = '#008766';

            setTimeout(() => {
                alert('Message sent successfully! We will contact you soon.');
                btn.innerText = originalText;
                btn.style.background = '';
                form.reset();
            }, 500);
        });
    }
});