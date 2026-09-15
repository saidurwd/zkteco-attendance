Hikvision Attendance
------------------------
Event Alarm IP/Domain:
attendance.example.com

Port:
443

Protocol:
HTTPS

URL:
/api/hikvision/events

If the interface asks for a complete URL:
https://attendance.example.com/api/hikvision/events

Event Processor
----------------------------
php artisan make:command ProcessHikvisionQueue
cPanel Cron Job
cd /home/USERNAME/attendance && /usr/local/bin/php artisan hikvision:queue




