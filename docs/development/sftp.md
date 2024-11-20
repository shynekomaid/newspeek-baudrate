# Configurating SFTP

## Table of Contents

- [Configurating SFTP](#configurating-sftp)
  - [Table of Contents](#table-of-contents)
  - [Steps](#steps)
    - [0. Installing Extension](#0-installing-extension)
    - [1. Create Enviroment File](#1-create-enviroment-file)
    - [2. Securing Enviroment File](#2-securing-enviroment-file)
    - [3. Ownering Files](#3-ownering-files)

## Steps

> 📝 Yes, you can use `scp`, `FileZilla` or any other solution you wish to deploy files to the debug server, but I strongly recommend using extention instead.

### 0. Installing Extension

Install [this](https://marketplace.visualstudio.com/items?itemName=satiromarra.code-sftp) extension and remove all another forks

> ⚠️ If you still want to use old extention by `liximomo` or `natizyskunk`, you can't use .env files, use [assume-unchanged](../README.md#preventing-leaking-passwords) mechanism for `.sftp` file
>
> 📝 If you use any microsoft synchronization extension, look for documentation yourself on how to make it work and not save passwords in the repository

### 1. Create Enviroment File

> 📝 Make Sure Enviroment File Do Not Sync

Create enviroment file `.env` (in root of project) with content like this:

```INI
SERVER1_HOST="xxx.yyy"
SERVER1_USER="root"
SERVER1_PASS="xxx"
SERVER2_HOST="xxx.yyy"
SERVER2_USER="root"
SERVER2_PASS="xxx"
```

### 2. Securing Enviroment File

Read this [file](/docs/development/secured_env.md) for more info

### 3. Ownering Files

If `chmod` and/or `chown` not work as excepted, use `setfacl` instead:

```Bash
setfacl -R -m u:username:rwx /path/to/your/folder
```
