<?php
use App\Icon\Icon;
?>

<div class="users-container">
    <h2>Users</h2>

    <div class="users-table-wrapper">
        <table class="users-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Coins</th>
                    <th>Slots</th>
                    <th>Admin</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user_data): ?>
                    <tr>
                        <td><?= htmlspecialchars($user_data->getName(), ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($user_data->getEmail(), ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= $user_data->getResources()->getCoins() ?></td>
                        <td><?= $user_data->getResources()->getSlots() ?></td>
                        <td><?= $user_data->isAdmin() ? Icon::use('tick', 32) : Icon::use("x", 32) ?></td>
                        <td>
                            <button class="edit-btn"
                                data-id="<?= $user_data->getId() ?>"
                                data-name="<?= htmlspecialchars($user_data->getName(), ENT_QUOTES, 'UTF-8') ?>"
                                data-firstname="<?= htmlspecialchars($user_data->getFirstname(), ENT_QUOTES, 'UTF-8') ?>"
                                data-lastname="<?= htmlspecialchars($user_data->getLastname(), ENT_QUOTES, 'UTF-8') ?>"
                                data-email="<?= htmlspecialchars($user_data->getEmail(), ENT_QUOTES, 'UTF-8') ?>"
                                data-ptrlid="<?= $user_data->getPtrlid() ?>"
                                data-admin="<?= $user_data->isAdmin() ? '1' : '0' ?>"
                                data-coins="<?= $user_data->getResources()->getCoins() ?>"
                                data-slots="<?= $user_data->getResources()->getSlots() ?>"
                                data-memory="<?= $user_data->getResources()->getMemory() ?>"
                                data-disk="<?= $user_data->getResources()->getDisk() ?>"
                                data-cpu="<?= $user_data->getResources()->getCpu() ?>"
                                data-dbs="<?= $user_data->getResources()->getDbs() ?>"
                                data-backups="<?= $user_data->getResources()->getBackups() ?>"
                                data-allocations="<?= $user_data->getResources()->getAllocations() ?>"
                            >Edit</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Popup Edit Form -->
<div id="editPopup" class="popup-overlay">
    <div class="popup-content">
        <h3>Edit User</h3>
        <form id="editForm" method="post" action="">
            <input type="hidden" name="user_id" id="editUserId">

            <label>Name:</label>
            <input type="text" name="name" id="editName" required>

            <label>Firstname:</label>
            <input type="text" name="firstname" id="editFirstname">

            <label>Lastname:</label>
            <input type="text" name="lastname" id="editLastname">

            <label>Email:</label>
            <input type="email" name="email" id="editEmail" required>

            <label>Pterodactyl ID:</label>
            <input type="text" name="ptrlid" id="editPtrlid">

            <label>Admin:</label>
            <select name="admin" id="editAdmin">
                <option value="0">No</option>
                <option value="1">Yes</option>
            </select>

            <h4>Resources</h4>

            <label>Coins:</label>
            <input type="number" name="coins" id="editCoins">

            <label>Slots:</label>
            <input type="number" name="slots" id="editSlots">

            <label>Memory:</label>
            <input type="number" name="memory" id="editMemory">

            <label>Disk:</label>
            <input type="number" name="disk" id="editDisk">

            <label>CPU:</label>
            <input type="number" name="cpu" id="editCpu">

            <label>Databases:</label>
            <input type="number" name="dbs" id="editDbs">

            <label>Backups:</label>
            <input type="number" name="backups" id="editBackups">

            <label>Allocations:</label>
            <input type="number" name="allocations" id="editAllocations">

            <div class="form-actions">
                <button type="submit">Save</button>
                <button type="button" id="closePopup">Cancel</button>
            </div>
        </form>
    </div>
</div>

<style>
/* ====== Users Table ====== */
.users-container {
    padding: 20px;
}

.users-table-wrapper {
    overflow-x: auto;
}

.users-table {
    width: 100%;
    border-collapse: collapse;
    background: #fff;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}

.users-table th, .users-table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #eee;
}

.users-table th {
    background: #f4f4f4;
    font-weight: bold;
}

.edit-btn {
    padding: 6px 12px;
    background: #3498db;
    color: #fff;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    transition: background 0.2s ease;
}
.edit-btn:hover {
    background: #2980b9;
}

/* ====== Popup ====== */
.popup-overlay {
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgba(0,0,0,0.6);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 2000;
}

.popup-content {
    background: #fff;
    padding: 20px;
    border-radius: 12px;
    max-width: 400px;
    width: 90%;
    box-shadow: 0 4px 10px rgba(0,0,0,0.2);
    max-height: 90vh;
    overflow-y: auto;
}

.popup-content h3 {
    margin-bottom: 15px;
    font-size: 18px;
}

.popup-content form label {
    display: block;
    margin-top: 10px;
    font-weight: bold;
}

.popup-content form input,
.popup-content form select {
    width: 100%;
    padding: 8px;
    margin-top: 5px;
    border-radius: 6px;
    border: 1px solid #ccc;
    box-sizing: border-box;
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    margin-top: 15px;
}

.form-actions button {
    margin-left: 10px;
    padding: 8px 14px;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    transition: opacity 0.2s ease;
}

.form-actions button[type="submit"] {
    background: #27ae60;
    color: #fff;
}
.form-actions button[type="submit"]:hover {
    opacity: 0.85;
}

.form-actions button[type="button"] {
    background: #e74c3c;
    color: #fff;
}
.form-actions button[type="button"]:hover {
    opacity: 0.85;
}

/* ====== Responsive ====== */
@media (max-width: 768px) {
    .users-table th, .users-table td {
        font-size: 14px;
        padding: 8px;
    }

    .popup-content {
        max-width: 95%;
    }
}
</style>

<script>
document.querySelectorAll('.edit-btn').forEach(btn => {
    btn.addEventListener('click', function () {
        // Fill form with dataset
        document.getElementById('editUserId').value = this.dataset.id;
        document.getElementById('editName').value = this.dataset.name;
        document.getElementById('editFirstname').value = this.dataset.firstname;
        document.getElementById('editLastname').value = this.dataset.lastname;
        document.getElementById('editEmail').value = this.dataset.email;
        document.getElementById('editPtrlid').value = this.dataset.ptrlid;
        document.getElementById('editAdmin').value = this.dataset.admin;
        document.getElementById('editCoins').value = this.dataset.coins;
        document.getElementById('editSlots').value = this.dataset.slots;
        document.getElementById('editMemory').value = this.dataset.memory;
        document.getElementById('editDisk').value = this.dataset.disk;
        document.getElementById('editCpu').value = this.dataset.cpu;
        document.getElementById('editDbs').value = this.dataset.dbs;
        document.getElementById('editBackups').value = this.dataset.backups;
        document.getElementById('editAllocations').value = this.dataset.allocations;

        document.getElementById('editPopup').style.display = 'flex';
    });
});

document.getElementById('closePopup').addEventListener('click', function () {
    document.getElementById('editPopup').style.display = 'none';
});
</script>