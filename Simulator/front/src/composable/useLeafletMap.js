import { shallowRef } from 'vue'
import 'leaflet/dist/leaflet.css'
import * as L from 'leaflet'
import img from "../assets/capteur.png"

export function useLeafletMap() {
    const initialMap = shallowRef(null)

    const initialiseMap = () => {
        if (!initialMap.value) {
            // On initialise la carte
            initialMap.value = new L.Map('mapBaseStation').setView([45.75, 4.85], 11)
            // Leaflet ne récupère pas les cartes (tiles) sur un serveur par défaut. Nous devons lui préciser où nous souhaitons les récupérer. Ici, openstreetmap.fr
            const tileLayer = L.tileLayer('https://{s}.tile.openstreetmap.fr/osmfr/{z}/{x}/{y}.png', {
                // Il est toujours bien de laisser le lien vers la source des données
                attribution:
                    'données © <a href="//osm.org/copyright">OpenStreetMap</a>/ODbL - rendu <a href="//openstreetmap.fr">OSM France</a>',
                minZoom: 1,
                maxZoom: 20
            })
            initialMap.value.addLayer(tileLayer)
        }
    }


    const getCapteurFromApi = async () => {


 const response = await fetch('http://localhost:8083/api/capteur',{method:'GET' })
         const data = await response.json();
        console.log(data)
        return data
    }
    const putCapteurOnMap = async () => {
        const capteurIcon = L.icon({
            iconUrl: img,


            iconSize:     [15, 15], // size of the icon
            shadowSize:   [50, 64], // size of the shadow
            shadowAnchor: [4, 62],  // the same for the shadow
        });

        const data = await getCapteurFromApi()
        console.log(data)
        data.forEach(feu => {
            L.marker([feu.coorX, feu.coorY], {icon : capteurIcon} ).addTo(initialMap.value)
    })
        //pour test de la requete put
        // let newData = []
        // data.forEach(capteur => {
        //     capteur.valeur = 0
        //     newData.push(capteur)
        // })
        // console.log(newData)
        //
        // const responseTest = await fetch('http://localhost:8081/api/capteur',{method:'PUT', body: JSON.stringify(newData), headers: { 'Content-Type': 'application/json' }})
        // const dataTest = await responseTest.json();
        // console.log(dataTest)
    }

    return {
        initialMap,
        initialiseMap,
        putCapteurOnMap
    }
}
