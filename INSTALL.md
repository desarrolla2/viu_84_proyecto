# INSTALL

## Install project dependencies

For the execution of this project, it is necessary to have these packages installed together with their versions. Please
read your instructions to install them.

| package        | minimum version |
|----------------|-----------------|
| docker         | 20.10           |
| docker-compose | 1.25            |

## Installation process

### 1. Download source code from our repository

```bash
git clone git@github.com:desarrolla2/viu_84_trabajo_fin_grado
```

### 2. Configure your docker environment

#### 2.1 Copy docker configuration files

```bash
cp docker/.env.dist .env
cp docker/docker-compose.yml.dist docker-compose.yml
cp docker/Makefile.dist Makefile
```

If you want to also mount the ELK containers for viewing logs

```bash
cp docker/docker-compose.override.yml.dist docker-compose.override.yml
```

Then, you may overwrite configuration to meet your needs.

### 3. Build containers and install dependencies

#### 3.1 Build containers

```bash
make docker-build
```

#### 3.2 Install PHP dependencies

```bash
make php-composer-install
```

#### 3.3 Up all containers

```bash
make docker-up
```

### c. Run tests

Edit the `.env` file to update the following values:

* **APP_SECRET**: set a unique token. You can generate one using this command: < /dev/urandom tr -dc 'a-z0-9' | head -c
  32
* **OPENAI_AUTHENTICATION_TOKEN**: generate a valid token through the platform https://platform.openai.com/

### 5. Run tests

```bash
make tests
```
