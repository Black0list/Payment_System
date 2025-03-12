# API Utilisateur et Transaction

Cette API est construite avec **Laravel** et permet l'authentification des utilisateurs, la gestion des rôles et des transactions financières. Elle permet aux utilisateurs de s'inscrire, se connecter, et effectuer des transactions telles que des transferts d'argent, des dépôts et des annulations. L'API utilise **Laravel Sanctum** pour l'authentification basée sur des tokens.

## Installation

### Étapes pour installer le projet

1. Clonez le repository dans votre répertoire local :
   ```bash
   git clone https://github.com/Black0list/Payment_System.git

2. Allez dans le répertoire du projet :
   ```bash
   cd Payment_System
   ```

3. Installez les dépendances via Composer :
   ```bash
   composer install
   ```

4. Créez une copie du fichier `.env.example` et renommez-le en `.env` :
   ```bash
   cp .env.example .env
   ```

5. Générez la clé d'application :
   ```bash
   php artisan key:generate
   ```

6. Configurez la base de données dans le fichier `.env`. Assurez-vous de bien indiquer les bonnes informations pour votre base de données (par exemple, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`).


7. Exécutez les migrations pour configurer la base de données :
   ```bash
   php artisan migrate
   ```

8. Démarrez le serveur de développement :
   ```bash
   php artisan serve
   ```

> Votre API devrait maintenant être accessible à `http://127.0.0.1:8000`.

---

## Points de terminaison API

### 1. Points de terminaison d'authentification des utilisateurs

#### Inscription d'un nouvel utilisateur

**Endpoint**: `POST /api/register`  
Permet d'enregistrer un nouvel utilisateur avec les informations suivantes :
- `name` (obligatoire)
- `email` (obligatoire, doit être unique)
- `password` (obligatoire, minimum 8 caractères)
- `role` (obligatoire)

#### Connexion de l'utilisateur

**Endpoint**: `POST /api/login`  
Permet à un utilisateur de se connecter avec les informations suivantes :
- `email` (obligatoire)
- `password` (obligatoire)

#### Déconnexion de l'utilisateur

**Endpoint**: `POST /api/logout`  
Permet à un utilisateur de se déconnecter (nécessite un token d'authentification valide via `auth:sanctum`).

---

### 2. Points de terminaison de gestion des transactions

#### Transfert d'argent

**Endpoint**: `POST /api/transfer`  
Permet de transférer de l'argent d'un utilisateur à un autre. Les paramètres requis sont :
- `receiver_email` (obligatoire) : l'email du destinataire.
- `receiver_name` (obligatoire) : le nom du destinataire.
- `amount` (obligatoire) : le montant à transférer.

#### Annulation d'une transaction

**Endpoint**: `POST /api/transaction/cancel`  
Permet d'annuler une transaction en fournissant l'ID de la transaction.

#### Dépôt d'argent

**Endpoint**: `POST /api/transaction/deposit`  
Permet à un utilisateur de déposer de l'argent sur son portefeuille en fournissant le montant à déposer.

---

## Exemple de requêtes

### Inscription d'un utilisateur

```bash
POST /api/register
{
    "name": "John Doe",
    "email": "john@example.com",
    "password": "password123",
    "role": "user"
}
```

### Connexion d'un utilisateur

```bash
POST /api/login
{
    "email": "john@example.com",
    "password": "password123"
}
```

### Transfert d'argent

```bash
POST /api/transfer
{
    "receiver_email": "jane@example.com",
    "receiver_name": "Jane Doe",
    "amount": 50
}
```

### Annulation de transaction

```bash
POST /api/transaction/cancel
{
    "transaction": "transaction_id_here"
}
```

### Dépôt d'argent

```bash
POST /api/transaction/deposit
{
    "amount": 100
}
```

---

## Contribution

Les contributions sont les bienvenues ! Pour proposer une modification, veuillez suivre les étapes suivantes :

1. Forkez le projet.
2. Créez une branche (`git checkout -b feature/nouvelle-fonctionnalité`).
3. Faites vos modifications et committez (`git commit -am 'Ajout de nouvelle fonctionnalité'`).
4. Poussez sur votre fork (`git push origin feature/nouvelle-fonctionnalité`).
5. Ouvrez une Pull Request.

---

## 👨‍💻 Auteur

Ce projet a été développé par **HADOUI ABDELKEBIR** (**Black0list**).  
N'hésitez pas à me suivre sur GitHub et à contribuer ! 🚀

📧 Contact : contact.abdelkebir@gmail.com
🔗 GitHub : [Black0list](https://github.com/Black0list)
