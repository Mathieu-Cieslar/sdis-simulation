import { shallowRef } from 'vue'
import 'leaflet/dist/leaflet.css'
import * as L from 'leaflet'
import img from "../assets/capteur.png"
import capteur9 from "../assets/capteur9.png"
import capteur8 from "../assets/capteur8.png"
import capteur7 from "../assets/capteur7.png"
import capteur6 from "../assets/capteur6.png"
import capteur5 from "../assets/capteur5.png"
import capteur4 from "../assets/capteur4.png"
import capteur3 from "../assets/capteur3.png"
import capteur2 from "../assets/capteur2.png"
import capteur1 from "../assets/capteur1.png"

export function useLeafletMap() {
    const initialMap = shallowRef(null)
    const capteurMarkers = new Map() // Associe les IDs des capteurs aux marqueurs Leaflet

    // Fonction pour retourner l'icône appropriée en fonction de l'intensité
    const getIconByIntensity = (valeur) => {
        let iconUrl;
        switch (true) {
            case valeur === 9:
                iconUrl = capteur9;
                break;
            case valeur === 8:
                iconUrl = capteur8;
                break;
            case valeur === 7:
                iconUrl = capteur7;
                break;
            case valeur === 6:
                iconUrl = capteur6;
                break;
            case valeur === 5:
                iconUrl = capteur5;
                break;
            case valeur === 4:
                iconUrl = capteur4;
                break;
            case valeur === 3:
                iconUrl = capteur3;
                break;
            case valeur === 2:
                iconUrl = capteur2;
                break;
            case valeur === 1:
                iconUrl = capteur1;
                break;
            default:
                iconUrl = img; // Icône par défaut
        }

        return L.icon({
            iconUrl: iconUrl,
            iconSize: [15, 15]
        });
    };

    const initialiseMap = () => {
        if (!initialMap.value) {
            initialMap.value = new L.Map('mapBaseStation').setView([45.75, 4.85], 11)
            const tileLayer = L.tileLayer('https://{s}.tile.openstreetmap.fr/osmfr/{z}/{x}/{y}.png', {
                attribution:
                    'données © <a href="//osm.org/copyright">OpenStreetMap</a>/ODbL - rendu <a href="//openstreetmap.fr">OSM France</a>',
                minZoom: 1,
                maxZoom: 20
            })
            initialMap.value.addLayer(tileLayer)
        }
    }

    const getCapteurFromApi = async () => {
        const response = await fetch('http://localhost:8083/api/capteur', { method: 'GET' })
        const data = await response.json()
        console.log(data)
        return data
    }

    const putCapteurOnMap = async () => {
        const data = await getCapteurFromApi()
        data.forEach(capteur => {
            if (!capteurMarkers.has(capteur.id)) {
                const capteurIcon = getIconByIntensity(capteur.valeur)
                const marker = L.marker([capteur.coorX, capteur.coorY], { icon: capteurIcon })
                    .addTo(initialMap.value)
                    .bindPopup(`Capteur ID: ${capteur.id}, Valeur: ${capteur.valeur}`)
                capteurMarkers.set(capteur.id, marker)
            }
        })
    }

    const updateCapteursOnMap = (updatedCapteurs) => {
        console.log(capteurMarkers)
        updatedCapteurs.forEach(updatedCapteur => {
            const marker = capteurMarkers.get(parseInt(updatedCapteur.id))
            if (marker) {
                const updatedIcon = getIconByIntensity(updatedCapteur.valeur)
                marker.setIcon(updatedIcon)
                marker.setPopupContent(`Capteur ID: ${updatedCapteur.id}, Valeur: ${updatedCapteur.valeur}`)
                console.log(`Capteur ID ${updatedCapteur.id} mis à jour avec intensité ${updatedCapteur.valeur}`)
            } else {
                console.warn(`Capteur ID ${updatedCapteur.id} non trouvé sur la carte.`)
            }
            // console.log('jsp :',capteurMarkers.get(updatedCapteur.id) ,updatedCapteur)

        })
    }

    const eventSource = new EventSource('http://localhost:3001/.well-known/mercure?topic=https://example.com/new-fire')

    eventSource.onmessage = (event) => {
        const data = JSON.parse(event.data)
        console.log('Nouvel événement Mercure reçu :', data)

        if (Array.isArray(data.capteur)) {
            updateCapteursOnMap(data.capteur)
        } else {
            updateCapteursOnMap([data.capteur])
        }
    }

    return {
        initialMap,
        initialiseMap,
        putCapteurOnMap
    }
}
