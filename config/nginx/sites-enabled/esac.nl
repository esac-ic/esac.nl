# www to non-www redirect -- duplicate content is BAD:
# https://github.com/h5bp/html5-boilerplate/blob/5370479476dceae7cc3ea105946536d6bc0ee468/.htaccess#L362
# Choose between www and non-www, listen on the *wrong* one and redirect to
# the right one -- http://wiki.nginx.org/Pitfalls#Server_Name




server {
     listen 80;
     index index.php index.html;
     root /var/www/public;
     access_log /var/log/nginx/access.log;
     error_log /var/log/nginx/error.log;
     location / {
         try_files $uri /index.php?$args;
     }

     location ~ \.php$ {
         fastcgi_split_path_info ^(.+\.php)(/.+)$;
         fastcgi_pass app:9000;
         fastcgi_index index.php;
         include fastcgi_params;
         fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
         fastcgi_param PATH_INFO $fastcgi_path_info;
     }
 }
 server {
     listen 443 ssl;
     server_name beta.esac.nl;

     location / {
         proxy_pass beta.esac.nl; #for demo purposes
     }
 }