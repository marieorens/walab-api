@extends('laboratoire.layout')

@section('page_content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">
                    <i class="ri-notification-3-line me-2"></i>
                    Mes Notifications
                </h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <!-- Filtres -->
                    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-sm btn-outline-primary filter-btn active" data-filter="all">
                                Toutes
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-primary filter-btn" data-filter="unread">
                                Non lues (<span id="unread-count">0</span>)
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-primary filter-btn" data-filter="read">
                                Lues
                            </button>
                        </div>
                        
                        <button type="button" class="btn btn-sm btn-success" id="mark-all-read">
                            <i class="ri-check-double-line me-1"></i>
                            Tout marquer comme lu
                        </button>
                    </div>

                    <!-- Liste des notifications -->
                    <div id="notifications-container">
                        <div class="text-center py-5" id="loading-spinner">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Chargement...</span>
                            </div>
                            <p class="mt-2 text-muted">Chargement des notifications...</p>
                        </div>
                        
                        <div id="notifications-list" style="display: none;"></div>
                        
                        <div id="no-notifications" style="display: none;" class="text-center py-5 text-muted">
                            <i class="ri-notification-off-line" style="font-size: 48px; color: #ccc;"></i>
                            <p class="mt-3">Aucune notification disponible</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .notification-item {
        padding: 15px;
        border-bottom: 1px solid #f0f0f0;
        cursor: pointer;
        transition: all 0.3s;
        border-radius: 8px;
        margin-bottom: 8px;
    }
    
    .notification-item:hover {
        background-color: #f8f9fa;
    }
    
    .notification-item.unread {
        background-color: #e8f4ff;
        border-left: 4px solid #667eea;
    }
    
    .notification-item.read {
        opacity: 0.7;
    }
    
    .notification-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        margin-bottom: 8px;
    }
    
    .notification-title {
        font-weight: 600;
        color: #333;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .notification-time {
        font-size: 12px;
        color: #999;
    }
    
    .notification-body {
        color: #666;
        line-height: 1.5;
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease;
    }
    
    .notification-item.expanded .notification-body {
        max-height: 500px;
        margin-top: 10px;
    }
    
    .notification-badge {
        width: 8px;
        height: 8px;
        background-color: #667eea;
        border-radius: 50%;
        display: inline-block;
    }
    
    .filter-btn.active {
        background-color: #667eea;
        color: white;
        border-color: #667eea;
    }
    
    .notification-actions {
        margin-top: 10px;
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }
    
    .expand-icon {
        transition: transform 0.3s;
    }
    
    .notification-item.expanded .expand-icon {
        transform: rotate(180deg);
    }
</style>

<script>
    let notifications = [];
    let currentFilter = 'all';

    document.addEventListener('DOMContentLoaded', function() {
        console.log('Page notifications laboratoire chargée');
        console.log('Route notifications.index:', '{{ route("notifications.index") }}');
        console.log('CSRF Token:', '{{ csrf_token() }}');
        
        loadNotifications();
        
        // Filtres
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                currentFilter = this.dataset.filter;
                renderNotifications();
            });
        });
        
        // Marquer tout comme lu
        document.getElementById('mark-all-read').addEventListener('click', markAllAsRead);
    });

    async function loadNotifications() {
        try {
            console.log('Chargement des notifications laboratoire...');
            const response = await fetch('{{ route("notifications.index") }}', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            });
            
            console.log('Statut réponse:', response.status);
            
            if (!response.ok) {
                throw new Error('Erreur HTTP: ' + response.status);
            }
            
            const data = await response.json();
            console.log('Données reçues:', data);
            console.log('Nombre de notifications:', data.data ? data.data.length : 0);
            
            if (data.success) {
                notifications = data.data || [];
                const unreadCount = data.unread_count || 0;
                document.getElementById('unread-count').textContent = unreadCount;
                updateBellBadge(unreadCount);
                renderNotifications();
            } else {
                console.error('Erreur dans la réponse:', data.message);
                showNoNotifications('Une erreur est survenue lors du chargement');
            }
        } catch (error) {
            console.error('Erreur chargement notifications:', error);
            console.error('Stack:', error.stack);
            showNoNotifications('Erreur de connexion: ' + error.message);
            // Forcer l'affichage du conteneur vide
            document.getElementById('notifications-list').style.display = 'none';
            document.getElementById('no-notifications').style.display = 'block';
        } finally {
            const spinner = document.getElementById('loading-spinner');
            if (spinner) {
                spinner.style.display = 'none';
            }
        }
    }

    function renderNotifications() {
        const container = document.getElementById('notifications-list');
        const noNotif = document.getElementById('no-notifications');
        
        let filtered = notifications;
        if (currentFilter === 'unread') {
            filtered = notifications.filter(n => !n.read_at);
        } else if (currentFilter === 'read') {
            filtered = notifications.filter(n => n.read_at);
        }
        
        console.log('Rendu de', filtered.length, 'notifications (filtre:', currentFilter, ')');
        
        if (filtered.length === 0) {
            container.style.display = 'none';
            noNotif.style.display = 'block';
            return;
        }
        
        container.style.display = 'block';
        noNotif.style.display = 'none';
        
        container.innerHTML = filtered.map(notif => `
            <div class="notification-item ${notif.read_at ? 'read' : 'unread'}" 
                 data-id="${notif.id}" 
                 onclick="toggleNotification('${notif.id}')">
                <div class="notification-header">
                    <div class="notification-title">
                        ${!notif.read_at ? '<span class="notification-badge"></span>' : ''}
                        <strong>${notif.title || notif.type}</strong>
                        <i class="ri-arrow-down-s-line expand-icon"></i>
                    </div>
                    <span class="notification-time">${notif.created_at_human}</span>
                </div>
                <div class="notification-body">
                    <p class="mb-2">${notif.data || notif.body}</p>
                    <div class="notification-actions">
                        ${!notif.read_at ? `<button class="btn btn-sm btn-outline-primary" onclick="event.stopPropagation(); markAsRead('${notif.id}')">
                            <i class="ri-check-line me-1"></i>Marquer comme lu
                        </button>` : ''}
                        ${notif.url ? `<a href="${notif.url}" class="btn btn-sm btn-primary" onclick="event.stopPropagation()">
                            <i class="ri-eye-line me-1"></i>Voir détails
                        </a>` : ''}
                    </div>
                </div>
            </div>
        `).join('');
    }

    function showNoNotifications(message) {
        const noNotif = document.getElementById('no-notifications');
        noNotif.innerHTML = `
            <i class="ri-notification-off-line" style="font-size: 48px; color: #ccc;"></i>
            <p class="mt-3">${message || 'Aucune notification disponible'}</p>
        `;
        noNotif.style.display = 'block';
        document.getElementById('notifications-list').style.display = 'none';
    }

    function toggleNotification(id) {
        const item = document.querySelector(`[data-id="${id}"]`);
        if (!item) return;
        
        item.classList.toggle('expanded');
        
        // Si on ouvre une notification non lue, la marquer comme lue
        if (item.classList.contains('expanded') && item.classList.contains('unread')) {
            setTimeout(() => markAsRead(id), 500);
        }
    }

    async function markAsRead(id) {
        try {
            const response = await fetch('{{ route("notifications.markAsRead") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin',
                body: JSON.stringify({ id: id })
            });
            
            const data = await response.json();
            
            if (data.success) {
                // Mettre à jour localement
                const notif = notifications.find(n => n.id === id);
                if (notif) notif.read_at = new Date().toISOString();
                
                document.getElementById('unread-count').textContent = data.unread_count;
                updateBellBadge(data.unread_count);
                renderNotifications();
            }
        } catch (error) {
            console.error('Erreur marquage notification:', error);
        }
    }

    async function markAllAsRead() {
        if (!confirm('Marquer toutes les notifications comme lues ?')) return;
        
        try {
            const response = await fetch('{{ route("notifications.markAllAsRead") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                credentials: 'same-origin'
            });
            
            const data = await response.json();
            
            if (data.success) {
                notifications.forEach(n => n.read_at = new Date().toISOString());
                document.getElementById('unread-count').textContent = '0';
                updateBellBadge(0);
                renderNotifications();
            }
        } catch (error) {
            console.error('Erreur marquage tout:', error);
        }
    }

    function updateBellBadge(count) {
        const badge = document.getElementById('notification-bell-badge');
        if (badge) {
            if (count > 0) {
                badge.textContent = count > 99 ? '99+' : count;
                badge.style.display = 'inline-block';
            } else {
                badge.style.display = 'none';
            }
        }
    }
</script>
@endsection
