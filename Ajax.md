# AJAX Features

**Admin Module:**
1. Delete announcement - XMLHttpRequest to `api/delete-announcement.php`, removes from DOM without page reload
2. Delete complaint - XMLHttpRequest to `admin.php?action=complaints&sub=ajaxDelete`, removes from DOM without page reload

**Patient Module:**
1. Get available time slots - When date is selected, XMLHttpRequest to `patient.php?action=appointments&sub=getSlots`, returns JSON slots and displays dynamically without page reload