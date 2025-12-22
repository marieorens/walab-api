

(function() {
    'use strict';

    // Fonction principale de recherche
    function initTableSearch() {
        const searchInput = document.getElementById('searchInput');
        const tableBody = document.getElementById('table-body');
        
        if (!searchInput || !tableBody) {
            return; // Pas de recherche sur cette page
        }

        let allRows = Array.from(tableBody.getElementsByTagName('tr'));
        
        // Fonction de filtrage
        function filterTable() {
            const searchTerm = searchInput.value.toLowerCase().trim();
            
            let visibleCount = 0;
            
            allRows.forEach(row => {
                // Ne pas filtrer les lignes de message (colspan)
                if (row.querySelector('td[colspan]')) {
                    row.style.display = 'none';
                    return;
                }
                
                // Récupérer tout le texte de la ligne
                const rowText = row.textContent.toLowerCase();
                
                // Afficher ou cacher selon la correspondance
                if (searchTerm === '' || rowText.includes(searchTerm)) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });
            
            // Afficher un message si aucun résultat
            showNoResultsMessage(visibleCount, searchTerm);
        }
        
        function showNoResultsMessage(count, searchTerm) {
            const oldMessage = tableBody.querySelector('.no-results-message');
            if (oldMessage) {
                oldMessage.remove();
            }
            
            if (count === 0 && searchTerm !== '') {
                const colspan = tableBody.closest('table')?.querySelector('thead tr')?.children.length || 10;
                const messageRow = document.createElement('tr');
                messageRow.className = 'no-results-message';
                messageRow.innerHTML = `
                    <td colspan="${colspan}" class="text-center py-5">
                        <i class="ri-search-line" style="font-size: 48px; color: #ccc;"></i>
                        <p class="text-muted mt-2 mb-0">Aucun résultat trouvé pour "<strong>${searchTerm}</strong>"</p>
                        <small class="text-muted">Essayez avec d'autres mots-clés</small>
                    </td>
                `;
                tableBody.appendChild(messageRow);
            }
        }
        
        searchInput.addEventListener('input', filterTable);
        searchInput.addEventListener('keyup', filterTable);
        
        const searchButton = document.getElementById('searchButton');
        if (searchButton) {
            searchButton.addEventListener('click', function(e) {
                e.preventDefault();
                filterTable();
            });
        }
        
        searchInput.value = '';
        
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.addedNodes.length > 0) {
                    allRows = Array.from(tableBody.getElementsByTagName('tr'));
                    if (searchInput.value.trim() !== '') {
                    if (searchInput.value.trim() !== '') {
                        filterTable();
                    }
                }
            });
        });
        
        observer.observe(tableBody, {
            childList: true,
            subtree: false
        });
    }
    
    // Initialiser au chargement du DOM
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initTableSearch);
    } else {
        initTableSearch();
    }
    
})();
