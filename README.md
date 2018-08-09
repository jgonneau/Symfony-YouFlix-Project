# Symfony YouFlix Project

###### Requirements :

Docker<br>
Docker-compose<br>

###### How to start :

```
docker-compose up -d
docker-compose exec web composer install
```

Open a web-browser and type 'localhost'. (Make sure there is nothing listening on port *80 to avoid conflits)


### More :

Create an admin by php bin/console with :
````
commande            params
app:create-admin    (pseudo) (email) (password)
````

