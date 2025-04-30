
const baseSearchUrl = "https://collectionapi.metmuseum.org/public/collection/v1/search?hasImages=true&q=";
const objectUrl = "https://collectionapi.metmuseum.org/public/collection/v1/objects/";


const artists = [
    "Van Gogh", "Monet", "Picasso", "Rembrandt",
    "Degas", "Cézanne", "Renoir", "Klimt"
];


function getRandomRarity() {
    const r = Math.random();
    return r < 0.7 ? 'common' : r < 0.95 ? 'rare' : 'epic';
}

async function fetch12Mixedoeuvres() {
    const gallery = document.getElementById("gallery");
    gallery.innerHTML = '<div class="loader">Chargement des 12 œuvres...</div>';

    try {
        let oeuvres = [];
        let attempts = 0;
        const maxAttempts = 30; 
        const shuffledArtists = [...artists].sort(() => Math.random() - 0.5);
        
        while (oeuvres.length < 12 && attempts < maxAttempts) {
            attempts++;
            const artist = shuffledArtists[attempts % shuffledArtists.length];
            
            try {
                const searchUrl = `${baseSearchUrl}${encodeURIComponent(artist)}`;
                const response = await fetch(searchUrl);
                if (!response.ok) continue;
                
                const data = await response.json();
                if (!data.objectIDs?.length) continue;
                
                
                const randomId = data.objectIDs[Math.floor(Math.random() * data.objectIDs.length)];
                const oeuvre = await fetchoeuvreDetails(randomId);
                
                if (oeuvre && !oeuvres.some(a => a.objectID === oeuvre.objectID)) {
                    oeuvres.push(oeuvre);
                    displayoeuvre(oeuvre, oeuvres.length); 
                }
            } catch (error) {
                console.error(`Tentative ${attempts} échouée:`, error);
            }
        }

        if (oeuvres.length < 12) {
            gallery.innerHTML += `<div class="loader">Seulement ${oeuvres.length} œuvres chargées. Rafraîchissez pour essayer d'en obtenir plus.</div>`;
        }

    } catch (error) {
        console.error("Erreur générale:", error);
        gallery.innerHTML = `<div class="loader">Erreur: ${error.message}</div>`;
    }
}

async function fetchoeuvreDetails(id) {
    try {
        const response = await fetch(`${objectUrl}${id}`);
        if (!response.ok) throw new Error("Échec de la requête");
        
        const oeuvre = await response.json();
        if (!oeuvre.primaryImageSmall) throw new Error("Pas d'image");
        
        return oeuvre;
    } catch (error) {
        console.error(`Erreur sur l'œuvre ${id}:`, error);
        return null;
    }
}

function displayoeuvre(oeuvre, count) {
    const gallery = document.getElementById("gallery");
    if (count === 1) gallery.innerHTML = ''; 
    
    const rarity = getRandomRarity();
    const card = document.createElement("div");
    card.className = "oeuvre-card";
    card.innerHTML = `
        <img src="${oeuvre.primaryImageSmall}" 
             alt="${oeuvre.title || 'Œuvre sans titre'}" 
             loading="lazy"
             onerror="this.src='image/placeholder.jpg'">
        <div class="oeuvre-content">
            <h3>${oeuvre.title || "Titre inconnu"}</h3>
            <p>${oeuvre.artistDisplayName || "Artiste inconnu"}</p>
            <span class="rarity ${rarity}">
                ${rarity === 'epic' ? 'Épique' : rarity === 'rare' ? 'Rare' : 'Commune'}
            </span>
        </div>
    `;
    gallery.appendChild(card);
}


document.addEventListener('DOMContentLoaded', fetch12Mixedoeuvres);