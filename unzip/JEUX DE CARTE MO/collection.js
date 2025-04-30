const searchUrl = "https://collectionapi.metmuseum.org/public/collection/v1/search?hasImages=true&q=";
const objectUrl = "https://collectionapi.metmuseum.org/public/collection/v1/objects/";

const searchThemes = [
    "Van Gogh",
    "Monet", 
    "Picasso",
    "Rembrandt",
    "Degas",
    "Cézanne",
    "Renoir",
    "Klimt"
];

let userCollection = [];
let userCredits = 10;
let cachedoeuvres = [];

document.addEventListener('DOMContentLoaded', function() {
    const openBoosterBtn = document.getElementById('openBooster');
    const boosterResult = document.getElementById('boosterResult');
    const creditsDisplay = document.getElementById('creditsCount');
    const cardsGrid = document.getElementById('cardsGrid');
    const emptyCollection = document.getElementById('emptyCollection');

    loadCollection();
    initializeArtCache();

    openBoosterBtn.addEventListener('click', function() {
        if (userCredits >= 5) {
            openBooster();
        } else {
            alert("Vous n'avez pas assez de crédits !");
        }
    });

    async function initializeArtCache() {
        try {
            
            for (const theme of searchThemes) {
                const response = await fetch(`${searchUrl}${encodeURIComponent(theme)}`);
                const data = await response.json();
                
                
                const ids = data.objectIDs.slice(0, 2);
                for (const id of ids) {
                    const oeuvre = await fetch(`${objectUrl}${id}`).then(res => res.json());
                    if (oeuvre.primaryImageSmall) {
                        cachedoeuvres.push({
                            id: oeuvre.objectID,
                            title: oeuvre.title || "Sans titre",
                            artist: oeuvre.artistDisplayName || "Artiste inconnu",
                            image: oeuvre.primaryImageSmall,
                            theme: theme,
                            rarity: getRandomRarity()
                        });
                    }
                }
            }
            console.log(`${cachedoeuvres.length} œuvres chargées en cache`);
        } catch (error) {
            console.error("Erreur lors du chargement du cache:", error);
        }
    }

    function getRandomRarity() {
        const rand = Math.random();
        if (rand < 0.6) return "common"; 
        if (rand < 0.9) return "rare";  
        return "epic";                   
    }

    function loadCollection() {
        const savedCollection = localStorage.getItem('artCollection');
        if (savedCollection) {
            userCollection = JSON.parse(savedCollection);
            updateCollectionDisplay();
        }
    }

    function updateCollectionDisplay() {
        creditsDisplay.textContent = userCredits;
        
        if (userCollection.length > 0) {
            emptyCollection.style.display = 'none';
            cardsGrid.innerHTML = '';
            
            userCollection.forEach(art => {
                const card = createCardElement(art);
                cardsGrid.appendChild(card);
            });
        } else {
            emptyCollection.style.display = 'block';
        }
    }

    function openBooster() {
        if (cachedoeuvres.length < 5) {
            alert("Les œuvres d'art sont encore en cours de chargement. Veuillez patienter.");
            return;
        }

        userCredits -= 5;
        creditsDisplay.textContent = userCredits;
        
        const boosterCards = [];
        const shuffled = [...cachedoeuvres].sort(() => 0.5 - Math.random());
        boosterCards.push(...shuffled.slice(0, 5));
       
        displayBoosterCards(boosterCards);
    }

    function displayBoosterCards(cards) {
        boosterResult.innerHTML = '';
        boosterResult.style.display = 'flex';
        
        cards.forEach(card => {
            const cardElement = document.createElement('div');
            cardElement.className = `booster-card ${card.rarity}`;
            cardElement.innerHTML = `
                <img src="${card.image}" alt="${card.title}">
                <div class="booster-card-info">
                    <div><strong>${card.title}</strong></div>
                    <div>${card.artist}</div>
                    <div class="rarity ${card.rarity}">${getRarityName(card.rarity)}</div>
                    <button class="add-to-collection" data-id="${card.id}">Ajouter à ma collection</button>
                </div>
            `;
            boosterResult.appendChild(cardElement);
        });
        
        
        document.querySelectorAll('.add-to-collection').forEach(btn => {
            btn.addEventListener('click', function() {
                const cardId = this.getAttribute('data-id');
                const cardToAdd = cachedoeuvres.find(art => art.id.toString() === cardId);
                if (cardToAdd) {
                    addToCollection(cardToAdd);
                    this.textContent = 'Ajouté !';
                    this.disabled = true;
                }
            });
        });
    }

    function getRarityName(rarity) {
        const names = {
            common: "Commune",
            rare: "Rare", 
            epic: "Épique"
        };
        return names[rarity] || "";
    }

    function addToCollection(cardToAdd) {
        if (!userCollection.some(art => art.id === cardToAdd.id)) {
            userCollection.push(cardToAdd);
            localStorage.setItem('artCollection', JSON.stringify(userCollection));
            updateCollectionDisplay();
        }
    }

    function createCardElement(art) {
        const card = document.createElement('div');
        card.className = `card ${art.rarity}`;
        card.innerHTML = `
            <img src="${art.image}" alt="${art.title}">
            <div class="card-info">
                <div class="card-title">${art.title}</div>
                <div class="card-artist">${art.artist}</div>
                <div class="rarity ${art.rarity}">${getRarityName(art.rarity)}</div>
            </div>
        `;
        return card;
    }
});