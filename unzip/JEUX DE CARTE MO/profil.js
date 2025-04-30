

document.addEventListener('DOMContentLoaded', function() {
   
    const userData = {
        username: "Jean_Dupont",
        email: "jean.dupont@example.com",
        joinDate: "15 mars 2023",
        avatar: "image/vg.jpg",
        stats: {
            totalCards: 147,
            uniqueCards: 89,
            completionRate: 62,
            rank: 1245
        },
        preferences: ["Art moderne", "Impressionnisme"],
        badges: [
            { id: 1, name: "Collectionneur débutant", image: "image/badge1.png", earned: true },
            { id: 2, name: "Expert Van Gogh", image: "image/badge2.png", earned: true },
            { id: 3, name: "Marchandage", image: "image/badge3.png", earned: true },
            { id: 4, name: "À débloquer", image: "image/badge4.png", earned: false }
        ],
        activity: [
            { type: "card", action: "obtained", cardName: "La Nuit étoilée", rarity: "epic", date: "Aujourd'hui, 14:32" },
            { type: "trade", action: "exchange", partner: "ArtLover42", date: "Hier, 18:45" },
            { type: "booster", action: "opened", count: 5, date: "12 avril 2023" },
            { type: "badge", action: "earned", badgeName: "Expert Van Gogh", date: "10 avril 2023" }
        ]
    };

   
    function initProfile() {
        
        document.getElementById('userAvatar').src = userData.avatar;
    
        document.getElementById('username').textContent = userData.username;
        document.getElementById('displayUsername').textContent = userData.username;
        document.getElementById('displayEmail').textContent = userData.email;
        document.getElementById('joinDate').textContent = userData.joinDate;
        
       
        document.getElementById('totalCards').textContent = userData.stats.totalCards;
        document.getElementById('uniqueCards').textContent = userData.stats.uniqueCards;
        document.getElementById('completionRate').textContent = userData.stats.completionRate + '%';
        document.getElementById('userRank').textContent = '#' + userData.stats.rank.toLocaleString();
        
       
        const preferencesField = document.querySelector('.info-item:nth-child(4) .editable-field span');
        preferencesField.textContent = userData.preferences.join(', ');
        
      
        renderBadges();
        
     
        renderActivityLog();
    }

    function renderBadges() {
        const badgesContainer = document.querySelector('.badges-container');
        badgesContainer.innerHTML = '';
        
        userData.badges.forEach(badge => {
            const badgeElement = document.createElement('div');
            badgeElement.className = `badge ${badge.earned ? '' : 'locked'}`;
            badgeElement.setAttribute('data-fullname', badge.name);
            
            badgeElement.innerHTML = `
                <div class="badge-image-container">
                    <img src="${badge.image}" alt="${badge.name}">
                </div>
                <p>${badge.earned ? (badge.name.length > 15 ? 
                    badge.name.substring(0, 12) + '...' : badge.name) : '?'}</p>
            `;
            
            badgesContainer.appendChild(badgeElement);
        });
    }


    function renderActivityLog() {
        const activityContainer = document.querySelector('.activity-log');
        activityContainer.innerHTML = '';
        
        userData.activity.forEach(activity => {
            const activityItem = document.createElement('div');
            activityItem.className = 'activity-item';
            
            let icon, content;
            
            switch(activity.type) {
                case 'card':
                    icon = '🎨';
                    content = `Vous avez obtenu <strong>${activity.cardName}</strong> (${getRarityName(activity.rarity)})`;
                    break;
                case 'trade':
                    icon = '🔄';
                    content = `Échange avec <strong>${activity.partner}</strong>`;
                    break;
                case 'booster':
                    icon = '🎁';
                    content = `Ouverture d'un booster - ${activity.count} nouvelles cartes`;
                    break;
                case 'badge':
                    icon = '🏆';
                    content = `Badge obtenu: <strong>${activity.badgeName}</strong>`;
                    break;
            }
            
            activityItem.innerHTML = `
                <div class="activity-icon">${icon}</div>
                <div class="activity-details">
                    <p>${content}</p>
                    <small>${activity.date}</small>
                </div>
            `;
            
            activityContainer.appendChild(activityItem);
        });
    }

  
    function getRarityName(rarity) {
        const rarities = {
            common: 'Commune',
            rare: 'Rare',
            epic: 'Épique'
        };
        return rarities[rarity] || '';
    }


    function setupEditableFields() {
        document.querySelectorAll('.edit-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const fieldContainer = this.parentElement;
                const currentValue = fieldContainer.querySelector('span').textContent;
                const fieldType = fieldContainer.parentElement.querySelector('label').textContent;
                
                let newValue;
                
                switch(fieldType) {
                    case 'Nom d\'utilisateur':
                        newValue = prompt('Nouveau nom d\'utilisateur:', currentValue);
                        if (newValue && newValue !== currentValue) {
                            updateUsername(newValue);
                        }
                        break;
                    case 'Email':
                        newValue = prompt('Nouvel email:', currentValue);
                        if (newValue && newValue !== currentValue) {
                            updateEmail(newValue);
                        }
                        break;
                    case 'Mot de passe':
                        newValue = prompt('Nouveau mot de passe:');
                        if (newValue) {
                            alert('Mot de passe mis à jour avec succès!');
                        }
                        break;
                    case 'Préférences':
                        newValue = prompt('Vos préférences (séparées par des virgules):', currentValue);
                        if (newValue && newValue !== currentValue) {
                            updatePreferences(newValue);
                        }
                        break;
                }
            });
        });
    }

   
    function updateUsername(newUsername) {
        userData.username = newUsername;
        document.getElementById('username').textContent = newUsername;
        document.getElementById('displayUsername').textContent = newUsername;
        alert('Nom d\'utilisateur mis à jour avec succès!');
    }

   
    function updateEmail(newEmail) {
        userData.email = newEmail;
        document.getElementById('displayEmail').textContent = newEmail;
        alert('Email mis à jour avec succès!');
    }


    function updatePreferences(newPreferences) {
        userData.preferences = newPreferences.split(',').map(p => p.trim());
        const preferencesField = document.querySelector('.info-item:nth-child(4) .editable-field span');
        preferencesField.textContent = userData.preferences.join(', ');
        alert('Préférences mises à jour avec succès!');
    }

    document.querySelector('.edit-avatar-btn').addEventListener('click', function() {
  
        const newAvatarUrl = prompt('Entrez l\'URL de votre nouvel avatar:', userData.avatar);
        
        if (newAvatarUrl) {
            userData.avatar = newAvatarUrl;
            document.getElementById('userAvatar').src = newAvatarUrl;
            alert('Avatar mis à jour avec succès!');
        }
    });

    
    initProfile();
    setupEditableFields();

    setTimeout(() => {
        document.querySelectorAll('.stat-value').forEach(el => {
            el.style.opacity = 1;
            el.style.transform = 'translateY(0)';
        });
    }, 300);
});