access_log /var/log/nginx/hh-access.log;
error_log /var/log/nginx/hh-error.log;

#rewrite ^/app\.php/?(.*)$ /$1 permanent;
#rewrite ^/(css|img|js|media)/(.*) /$1/$2 last;

#rewrite ^/app.php /app.php?__action=index&__path= last;
#rewrite ^/([A-Za-z_]+)/?(.*)(/)?$ /app.php?__action=$1&__path=$2 last;

#location / {
#    index app.php;
#}

rewrite ^/(css|img|js|media)/(.*) /$1/$2 last;
rewrite ^/.*$ /index.php last;

            location / {
                        index  index.php;
            }
