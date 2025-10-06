[general]
installed = On
base_url = "http://ojs.localhost"
app_key = "base64:xH4r14KAVRzUMHglH8Og7GvGdUvD0WtbZsfPdgFscyo="
session_cookie_name = OJSSID
session_lifetime = 30
restful_urls = On
time_zone = "UTC"
date_format_short = "Y-m-d"
date_format_long = "F j, Y"
datetime_format_short = "Y-m-d h:i A"
datetime_format_long = "F j, Y - h:i A"
time_format = "h:i A"

[database]
driver = mysqli
host = ojs_db
username = ojs_user
password = ojs_pass
name = ojs_db
persistent = Off
debug = Off

[files]
files_dir = /var/www/html/public/files

[email]
default = log

[schedule]
task_runner = Off
