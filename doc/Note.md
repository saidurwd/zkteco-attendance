# 1. Configure the SenseFace 7A network
On the device:
Menu → Communication / Network
Set either Wi-Fi or Ethernet with:
- IP Address: device's local IP
- Subnet Mask: your network mask
- Gateway: your router/firewall gateway
- DNS: your DNS server or 8.8.8.8
Make sure the device can access your Laravel server over the Internet.

# 2. Enable ADMS / Push
On the SenseFace 7A, look for something similar to:
Menu → Communication → ADMS / Cloud Server / Push
Enable:
ADMS: ON

Depending on the firmware, the wording/menu location can differ.
Configure:
Setting	Value
ADMS/Push	Enabled
Server Mode	Domain Name
Server Address	attendance.yourdomain.com
Server Port	443
HTTPS/SSL	Enabled
Push/ADMS	Enabled
Real-time	Enabled if available
Upload interval	1 or lowest practical value

Your actual server should be something like:
https://attendance.yourdomain.com


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

