# safex-password-vault
A no-nonsense password vault developed using the LAMP stack, with admittedly poor-quality HTML/CSS/JS code.

# SafeX
https://github.com/user-attachments/assets/edcb6bf7-7cc4-47a0-bbd6-ed464a0a4f25

# Installation and Setup

### Server and MariaDB (Fedora)
Link:https://tecadmin.net/install-lamp-on-fedora/ 

### Clone the repo
```
https://github.com/prakshitvjain/safex-password-vault.git
cd safex-password-vault
```
### Setup MariaDB
Change the default password values in the `DB_setup.sql` file
```
mysql -u root -p < DB_setup.sql
```

### Create a .env file in with same variable names from below file
([.env_sample](https://github.com/prakshitvjain/safex-password-vault/blob/main/.env_sample))

### Load environment variables into the PHP web app
```
composer init
composer require vlucas/phpdotenv
```

### Make the script executable and run
```
chmod +x start_safex.sh
./start_safex.sh
```

The project will be accessible at the provided link

note: works best on google chrome
