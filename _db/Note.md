# Hikvision Attendance
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

# Event Processor
----------------------------
php artisan make:command ProcessHikvisionQueue
cPanel Cron Job
cd /home/USERNAME/attendance && /usr/local/bin/php artisan hikvision:queue

Run php artisan queue:work to process pending events
Command: php artisan queue:work database --sleep=1 --tries=3 --timeout=60
Description: Start queue worker for Hikvision events

# Current session worker
php artisan queue:work database --sleep=1 --tries=3 --timeout=60
# Production example with Supervisor
supervisorctl start hikvision-queue

