class OSMap {

    #map = {}

    constructor() {
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
                marker = L.marker(latLng)

            marker.addTo(this.#map)
            markers.push(latLng)
        })

        this.#map.fitBounds(markers)
    }
}

export default OSMap