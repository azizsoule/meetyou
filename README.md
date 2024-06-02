# 😍 Meetyou

Copy of Elite Rencontre.
An academic project for the Web Technologies course at Université Félix Houphouët-Boigny de Cocody.
Created by [Aziz Soulé](https://github.com/azizsoule) and [Ano Romaric](https://github.com/romaricano) .

# 📝 Features

- Login
- Inscription
- Profile edition
- Profile view
- Chat
- Matching score calculation
- Game space 😅 (Will never be finished)

# 📸 Screenshots

Screenshots are available in the `screenshots` folder.

![You_Meet _ Bienvenue sur You_Meet _.jpeg](screenshots%2FYou_Meet%20_%20Bienvenue%20sur%20You_Meet%20_.jpeg)
![You_Meet _ Mon Profil _.jpeg](screenshots%2FYou_Meet%20_%20Mon%20Profil%20_.jpeg)
![You_Meet _ Messages _.jpeg](screenshots%2FYou_Meet%20_%20Messages%20_.jpeg)
![You_Meet _ Mon Profil _ · 5.02pm · 06-02.jpeg](screenshots%2FYou_Meet%20_%20Mon%20Profil%20_%20%C2%B7%205.02pm%20%C2%B7%2006-02.jpeg)
![You_Meet _ Mon Profil _zkejzlje.jpeg](screenshots%2FYou_Meet%20_%20Mon%20Profil%20_zkejzlje.jpeg)
![You_Meet _ Param_tres _.jpeg](screenshots%2FYou_Meet%20_%20Param_tres%20_.jpeg)
![You_Meet _ Profils compatibles _.jpeg](screenshots%2FYou_Meet%20_%20Profils%20compatibles%20_.jpeg)
![You_Meet _ Welcome _ · 4.56pm · 06-02.jpeg](screenshots%2FYou_Meet%20_%20Welcome%20_%20%C2%B7%204.56pm%20%C2%B7%2006-02.jpeg)

# ▶️ How to run the project ?

To run the project, you need to have docker installed on your machine.
Check the [official documentation](https://docs.docker.com/get-docker/) to install docker on your machine.
After that, simply run the following command:

```bash
docker-compose up
```

notices 💡: You need to have docker installed on your machine.

After docker-compose is done, you can access phpmyadmin at the following address: `http://localhost:8080` and the website at `http://localhost`.

From phpmyadmin, you need to import the `bd_rencontre.sql` file (in config folder) to have the database.

Once the database is imported, you can login using an account created in the database, or simply create a new account from the website (http://localhost).

To stop the project, simply run the following command:

```bash
docker-compose down
```