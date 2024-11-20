# Right Way to Store .env in Git

## Table of Contents

- [Right Way to Store .env in Git](#right-way-to-store-env-in-git)
  - [Table of Contents](#table-of-contents)
  - [Encrypting .env File](#encrypting-env-file)
    - [Install OpenSSL](#install-openssl)
    - [Decrypt Current .env](#decrypt-current-env)
    - [Encrypt Current .env](#encrypt-current-env)
    - [OpenSSL Password Save Note](#openssl-password-save-note)

> You can notice that the .env file is listed in the .gitignore file. This is because storing sensitive information such as API keys, server host name and database credentials in your Git repository is a security risk.
>
> But from a convenience point of view, it would be convenient to move the .env file. But how do we do that? We use encryption, of course!

Follow these steps for a secure backup of your .env file into Git

## Encrypting .env File

### Install OpenSSL

- For Windows:

  [Follow these instructions](http://certificate.fyicenter.com/1966_Download_and_Install_OpenSSL_slproweb_Binary_for_Windows.html).

  Launch the OpenSSL shell before proceeding.

- For Ubuntu:

  ```bash
  sudo apt install openssl
  ```

### Decrypt Current .env

> cd to root folder of git repo before.

Run the following command, replacing USER with your username:

```bash
openssl enc -d -aes-256-cbc -salt -pbkdf2 -in .env.crypt/.env.USER -out .env
```

Enter your password when prompted (avoid using -k key to prevent saving the password in .bash_history)

### Encrypt Current .env

> cd to root folder of git repo before.

Create folder:

```bash
mkdir .env.crypt
```

Run the following command, replacing USER with your username:

```bash
openssl enc -aes-256-cbc -salt -pbkdf2 -in .env -out .env.crypt/.env.USER
```

When prompted, type a strong password. Remember, this password will be needed to decrypt the file later (avoid using -k key to prevent saving the password in .bash_history)

### OpenSSL Password Save Note

> As mentioned above, storing the password used to encrypt your .env file is crucial for later decryption. It is highly recommended to never store this password within the .env file itself or in plain text, telegram message, etc anywhere
>
> For added security, I recommend using a password manager like _Bitwarden_ and creating a secure note for the password. This ensures that your password is encrypted and protected, further minimizing the risk of unauthorized access

Here's how to create a secure note in _Bitwarden_:

- Log in to your _Bitwarden_ vault
- Click the "Add Item" button
- In the "Item Type" dropdown menu, select "Secure Note"
- Enter a name for your note, such as **"Encrypted .env Password For ..."**

  In the "Note" field, paste your .env file encryption password

- Click the "Save" button

By following these steps, you can ensure that your .env file password is stored securely and conveniently within your _Bitwarden_ vault, alongside your other sensitive information
