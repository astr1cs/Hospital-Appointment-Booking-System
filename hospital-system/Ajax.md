# AJAX Features

**Admin Module:**
1. Delete announcement - XMLHttpRequest to `api/delete-announcement.php`, removes from DOM without page reload
2. Delete complaint - XMLHttpRequest to `admin.php?action=complaints&sub=ajaxDelete`, removes from DOM without page reload

**Patient Module:**
1. Get available time slots - When date is selected, XMLHttpRequest to `patient.php?action=appointments&sub=getSlots`, returns JSON slots and displays dynamically without page reload

**Doctor Module:**
1. Check-in patient - Click "Check In" button sends XMLHttpRequest to `doctor.php?action=appointments&sub=checkin`, updates appointment status to "checked_in" and changes UI dynamically without page reload
2. Update profile - Submit profile form sends XMLHttpRequest to `doctor.php?action=profile&sub=ajaxUpdate`, updates doctor information and updates header name dynamically without page reload
3. Change password - Submit password form sends XMLHttpRequest to `doctor.php?action=profile&sub=changePassword`, validates and updates password with JSON response without page reload