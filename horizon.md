# Horizon / Redis
## Pre-requisito
Servidor redis y cliente redis en php instalados y funcionando.

## Instalación y ejecución
Ubuntu/Debian

```bash
sudo apt-get install supervisor
```

### Supervisor
Supervisor configuration files are typically stored within your server's `/etc/supervisor/conf.d` directory.

Copiar archivo de configuración [https://github.com/manureta/segmenter/blob/master/horizon.conf]

#### Starting Supervisor

Once the configuration file has been created, you may update the Supervisor configuration and start the monitored processes using the following commands:

```bash
sudo supervisorctl reread
 
sudo supervisorctl update
 
sudo supervisorctl start horizon
```
