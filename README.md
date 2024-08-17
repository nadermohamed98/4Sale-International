## Run the application 
Instruction to run the app.
    1. Clone the repo localy
    2. Copy .env.example to .env without editing 
    3. Run the following commands to run the app smoothly 
        - cd to the project dir
        - docker-compose app build 
        - docker-compose up -d 
        - composer install 
        - composer update 
        - php artisan migrate --seed 
    4. Change the DB_HOST to db "the database service name in docker file" after migrating and seeding the database
    5. Run the following commands
        - php artisan config:cache
        - php artisan config:clear
        - php artisan cache:clear
    6. Now the app is running at http://localhost:8000/api. Have a nice testing time :)
