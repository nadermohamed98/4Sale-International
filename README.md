## Run the application 
Instruction to run the app.
    1. copy .env.example to .env without editing 
    2. run the next commands to run the app smoothly 
        - docker-compose app build 
        - docker-compose up -d 
        - composer install 
        - composer update 
        - php artisan migrate --seed 
    3. change the DB_HOST to db after creating and migrating the database
    4. run the following commands
        - php artisan config:cache
        - php artisan config:clear
        - php artisan cache:clear
    5. now the app is running. Have a nice testing time :)
