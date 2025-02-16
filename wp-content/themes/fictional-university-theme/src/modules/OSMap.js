class OSMap {

    #map = {}

    constructor() {
        if(!document.getElementById('acf-map')) {
            return
        }

        this.newMap('acf-map')
        this.addMarker('.marker')
    }

    newMap(containerId) {
        let map = L.map(containerId).setView([42.69917,  23.32250], 13)

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map)

        this.#map = map
    }

    addMarker(markerClass) {
        let markers = []

        document.querySelectorAll(markerClass).forEach((item) => {
            let latLng = [
                item.getAttribute('data-lat'),
                item.getAttribute('data-lng')
            ],
                marker = L.marker(latLng).addTo(this.#map),
                popup = item.innerHTML.trim()

            if(popup) {
                marker.bindPopup(popup)
            }

            markers.push(latLng)
            item.remove()
        })

        if(markers.length > 0) {
            this.#map.fitBounds(markers)
        }
    }
}

export default OSMap