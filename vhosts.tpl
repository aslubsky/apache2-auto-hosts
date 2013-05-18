<VirtualHost *:80>
        ServerAdmin webmaster@localhost
        ServerName {{hostName}}
        DocumentRoot {{projectsDir}}/{{hostName}}/public/
        <Directory {{projectsDir}}/{{hostName}}/public/>
                AllowOverride All
                Order allow,deny
                allow from all
        </Directory>

        ErrorLog ${APACHE_LOG_DIR}/{{hostName}}.error.log
</VirtualHost>