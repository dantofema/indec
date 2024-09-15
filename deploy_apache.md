### Para publicar en apache2
En caso de tener habilitado el directorio /var/www/html como default site :
```
ln -s /indec/mandarina/public /var/www/html/segmentador
```

#### Creando un virtualHsot en apache2
```
<VirtualHost *:80>
   ServerName segmentador segmentador.indec.gob.ar
   ServerAdmin webmaster@indec.gob.ar
   DocumentRoot /indec/mandarina/public

   <Directory /indec/mandarina/menta>
        Options +Indexes +FollowSymLinks +MultiViews
        AllowOverride All
        Require all granted
   </Directory>
    ErrorLog ${APACHE_LOG_DIR}/error.log
   CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
```
