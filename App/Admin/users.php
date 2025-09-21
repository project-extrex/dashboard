<div class="min-h-screen bg-gray-100 p-6">
    <h1 class="text-2xl font-bold mb-6 text-gray-800">Users</h1>

    <div class="grid grid-cols-1 md:grid-cols-1 gap-4">
        <?php foreach ($users as $user_data): ?>
            <div class="bg-white rounded-2xl shadow-lg p-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="flex flex-col md:flex-row md:items-center gap-4">
                    <div class="flex flex-col">
                        <span class="font-semibold"><?= htmlspecialchars($user_data->getName(), ENT_QUOTES, 'UTF-8') ?></span>
                        <span class="text-gray-500 text-sm"><?= htmlspecialchars($user_data->getEmail(), ENT_QUOTES, 'UTF-8') ?></span>
                    </div>
                    <div class="flex gap-2 text-sm text-gray-600">
                        <span>Coins: <?= $user_data->getResources()->getCoins() ?></span>
                        <span>Slots: <?= $user_data->getResources()->getSlots() ?></span>
                        <span>Admin: <?= $user_data->isAdmin() ? "<i class=\"fa-solid fa-circle-check\"></i>" : '<i class="fa-solid fa-circle-xmark"></i>' ?></span>
                    </div>
                </div>

                <div class="flex gap-2 mt-2 md:mt-0">
                    <button class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded-lg edit-btn"
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
                    ><i class="fa-solid fa-pen-to-square"></i> Edit</button>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<div id="editPopup" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-2xl shadow-xl p-6 w-full max-w-lg max-h-[90vh] overflow-y-auto">
        <h3 class="text-xl font-bold mb-4">Edit User</h3>
        <form id="editForm" method="post" action="/admin/users/" class="space-y-3">
            <input type="hidden" name="user_id" id="editUserId">

            <div class="flex flex-col">
                <label class="font-semibold">Name</label>
                <input type="text" name="name" id="editName" class="border rounded-lg p-2" required>
            </div>
            <div class="flex flex-col">
                <label class="font-semibold">Firstname</label>
                <input type="text" name="firstname" id="editFirstname" class="border rounded-lg p-2">
            </div>
            <div class="flex flex-col">
                <label class="font-semibold">Lastname</label>
                <input type="text" name="lastname" id="editLastname" class="border rounded-lg p-2">
            </div>
            <div class="flex flex-col">
                <label class="font-semibold">Email</label>
                <input type="email" name="email" id="editEmail" class="border rounded-lg p-2" required>
            </div>
            <div class="flex flex-col">
                <label class="font-semibold">Pterodactyl ID</label>
                <input type="text" name="ptrlid" id="editPtrlid" class="border rounded-lg p-2">
            </div>
            <div class="flex flex-col">
                <label class="font-semibold">Admin</label>
                <select name="admin" id="editAdmin" class="border rounded-lg p-2">
                    <option value="false"> <i class="fa-solid fa-circle-xmark"></i> No</option>
                    <option value="true"> <i class="fa-solid fa-circle-check"></i> Yes</option>
                </select>
            </div>

            <h4 class="font-semibold mt-4">Resources</h4>
            <div class="grid grid-cols-2 gap-3">
                <div class="flex flex-col">
                    <label>Coins</label>
                    <input type="number" name="coins" id="editCoins" class="border rounded-lg p-2">
                </div>
                <div class="flex flex-col">
                    <label>Slots</label>
                    <input type="number" name="slots" id="editSlots" class="border rounded-lg p-2">
                </div>
                <div class="flex flex-col">
                    <label>Memory</label>
                    <input type="number" name="memory" id="editMemory" class="border rounded-lg p-2">
                </div>
                <div class="flex flex-col">
                    <label>Disk</label>
                    <input type="number" name="disk" id="editDisk" class="border rounded-lg p-2">
                </div>
                <div class="flex flex-col">
                    <label>CPU</label>
                    <input type="number" name="cpu" id="editCpu" class="border rounded-lg p-2">
                </div>
                <div class="flex flex-col">
                    <label>Databases</label>
                    <input type="number" name="dbs" id="editDbs" class="border rounded-lg p-2">
                </div>
                <div class="flex flex-col">
                    <label>Backups</label>
                    <input type="number" name="backups" id="editBackups" class="border rounded-lg p-2">
                </div>
                <div class="flex flex-col">
                    <label>Allocations</label>
                    <input type="number" name="allocations" id="editAllocations" class="border rounded-lg p-2">
                </div>
            </div>

            <div class="flex justify-end gap-2 mt-4">
                <button type="button" id="closePopup" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg"><i class="fa-solid fa-xmark text-red-500"></i> Cancel</button>
                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg">
                      <i class="fa-solid fa-floppy-disk"></i> Save</button>
            </div>
        </form>
    </div>
</div>

<script>
document.querySelectorAll('.edit-btn').forEach(btn => {
    btn.addEventListener('click', function () {
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
        document.getElementById("editAdmin").value = this.dataset.admin ? "true" : "false";

        document.getElementById('editPopup').classList.remove('hidden');
        document.getElementById("editForm").action = "/admin/users/" + this.dataset.id;
    });
});

document.getElementById('closePopup').addEventListener('click', function () {
    document.getElementById('editPopup').classList.add('hidden');
});
</script>