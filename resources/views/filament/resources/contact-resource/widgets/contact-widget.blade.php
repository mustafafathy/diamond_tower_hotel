<div>
    Contact
    <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
        <div class="p-4 bg-primary-500 text-white rounded-lg shadow">
            <div class="text-sm font-medium">Total Contacts</div>
            <div class="mt-1 text-2xl font-bold">{{ $totalContacts }}</div>
        </div>

        <div class="p-4 bg-danger-500 text-white rounded-lg shadow">
            <div class="text-sm font-medium">Unread Messages</div>
            <div class="mt-1 text-2xl font-bold">{{ $unreadMessages }}</div>
        </div>

        <div class="p-4 bg-success-500 text-white rounded-lg shadow">
            <div class="text-sm font-medium">Contacts Added Today</div>
            <div class="mt-1 text-2xl font-bold">{{ $contactsToday }}</div>
        </div>

        <div class="p-4 bg-info-500 text-white rounded-lg shadow">
            <div class="text-sm font-medium">Contacts Added This Month</div>
            <div class="mt-1 text-2xl font-bold">{{ $contactsThisMonth }}</div>
        </div>
    </div>
</div>