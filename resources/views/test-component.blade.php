<!DOCTYPE html>
<html>
<head>
    <title>Test Booking Status Badge</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="p-8">
    <h1 class="text-xl font-bold mb-4">Booking Status Badge Test</h1>
    
    <div class="space-y-2">
        <div>Pending: <x-booking-status-badge status="pending" /></div>
        <div>Confirmed: <x-booking-status-badge status="confirmed" /></div>
        <div>Active: <x-booking-status-badge status="active" /></div>
        <div>Completed: <x-booking-status-badge status="completed" /></div>
        <div>Cancelled: <x-booking-status-badge status="cancelled" /></div>
        <div>Overdue: <x-booking-status-badge status="overdue" /></div>
        <div>Returned: <x-booking-status-badge status="returned" /></div>
    </div>
</body>
</html>
