document.addEventListener('DOMContentLoaded', function() {
    
    const exchangeBtn = document.getElementById('exchangeBtn');
    const exchangeModal = document.getElementById('exchangeModal');
    const closeModal = document.querySelector('.close-modal');
    const exchangeForm = document.querySelector('.exchange-form');

   
    exchangeBtn.addEventListener('click', function() {
        exchangeModal.style.display = 'block';
        document.body.style.overflow = 'hidden';
    });

   
    closeModal.addEventListener('click', function() {
        exchangeModal.style.display = 'none';
        document.body.style.overflow = 'auto';
    });

   
    window.addEventListener('click', function(event) {
        if (event.target === exchangeModal) {
            exchangeModal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }
    });

   
    exchangeForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const receiver = document.getElementById('receiver').value;
        const cardToGive = document.getElementById('card-to-give').value;
        
        if (!receiver || !cardToGive) {
            alert('Veuillez sélectionner un destinataire et une carte');
            return;
        }
        
        alert(`Échange proposé : votre carte ${cardToGive} à ${receiver}`);
        
      
        exchangeForm.reset();
        exchangeModal.style.display = 'none';
        document.body.style.overflow = 'auto';
    });

 
    function loadUserCards() {
        const cardSelect = document.getElementById('card-to-give');
    
        const userCards = [
            { id: 'card1', name: 'La Nuit étoilée' },
            { id: 'card2', name: 'Les Nymphéas' },
            { id: 'card3', name: 'Le Déjeuner des canotiers' }
        ];
        
        userCards.forEach(card => {
            const option = document.createElement('option');
            option.value = card.id;
            option.textContent = card.name;
            cardSelect.appendChild(option);
        });
    }
    
    loadUserCards();
});