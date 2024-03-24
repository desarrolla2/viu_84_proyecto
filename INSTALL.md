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
git clone git@github.com:desarrolla2/viu_47_proyecto_de_ingenieria_del_software.git
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

### 4. Run tests

```bash
make tests
```
