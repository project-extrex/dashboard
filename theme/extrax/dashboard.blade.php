{{-- Display GET error --}}
@if(isset($_GET['error']))
    <div class="error">{{ htmlspecialchars($_GET['error'], ENT_QUOTES, 'UTF-8') }}</div>
@endif

{{-- Display user resources --}}
@if(isset($user_data))
    <div>
        <strong>{{ htmlspecialchars($user_data->getName(), ENT_QUOTES, 'UTF-8') }}</strong><br>
        <strong>Resources:</strong><br>
        Coins: {{ htmlspecialchars($user_data->getResources()->getCoins(), ENT_QUOTES, 'UTF-8') }}<br>
        Memory: {{ htmlspecialchars($user_data->getResources()->getMemory(), ENT_QUOTES, 'UTF-8') }}<br>
        Disk: {{ htmlspecialchars($user_data->getResources()->getDisk(), ENT_QUOTES, 'UTF-8') }}<br>
        CPU: {{ htmlspecialchars($user_data->getResources()->getCpu(), ENT_QUOTES, 'UTF-8') }}<br>
        Databases: {{ htmlspecialchars($user_data->getResources()->getDbs(), ENT_QUOTES, 'UTF-8') }}<br>
        Backups: {{ htmlspecialchars($user_data->getResources()->getBackups(), ENT_QUOTES, 'UTF-8') }}<br>
        Allocations: {{ htmlspecialchars($user_data->getResources()->getAllocations(), ENT_QUOTES, 'UTF-8') }}<br>
        Server slots: {{ htmlspecialchars($user_data->getResources()->getSlots(), ENT_QUOTES, 'UTF-8') }}<br>
        Owner of the resources that no one asked for: {{ isset($resources) ? htmlspecialchars($user->getResources->getUser()->getName(), ENT_QUOTES, 'UTF-8') : 'N/A' }}<br>
    </div>
@endif