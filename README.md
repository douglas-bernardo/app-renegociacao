<h1 style="display: flex; align-items: center; justify-content: center;" class="logo">
    <img width="100" style="margin-right: 15px;" src="https://ik.imagekit.io/rcjzrqiiqm7/logo_vacation_wyhJXU5a0.svg?updatedAt=1629735371452" alt="logo">
  API - Renegociação WEB
</h1>

&nbsp;

<h4 align="center">
	🚧  Status 🚀 Em construção...  🚧
</h4>

# Índice

- [Sobre](#-sobre)
- [Tecnologias Utilizadas](#-tecnologias-utilizadas)
- [Como baixar o projeto](#-como-baixar-o-projeto)
- [Features](#-features)


&nbsp;


## 🔖&nbsp; Sobre

---
O projeto **Renegociação Web** é um sistema de gerenciamento de ocorrências geradas nas operações de atendimento 
ao cliente **Beach Park Vacation Club**, direcionadas especificamente para o departamento **ADM TS - RENEGOCIAÇÃO**.

A aplicação é dividida em três partes:
- [Backend API PHP - renegociacao (principal)](https://github.com/douglas-bernardo/app-renegociacao)
- [Backend API PHP - timesharing (importação de ocorrências timesharing)](https://github.com/douglas-bernardo/api-timesharing)
- [Frontend React](https://github.com/douglas-bernardo/renegociacao-web)

A aplicação importa as ocorrências abertas no sistema Timesharing (sistema interno) e as disponibiliza em um sistema web,
para que a equipe de renegociação possa ter fácil acesso as ocorrências que foram atribuídas a cada negociadora, 
onde poderão ser gerenciadas conforme a negociação com o cliente.

&nbsp;
---

## 🚀 Tecnologias utilizadas

---
Este projeto foi desenvolvido utilizando as seguintes tecnologias:

**Backend**
- [PHP](https://www.php.net)

#### Principais componentes PHP utilizados
 
- Core - Framework
    - [The Front Controller](https://symfony.com/doc/current/create_framework/front_controller.html)
    - [The HttpKernel Component](https://symfony.com/doc/current/create_framework/http_kernel_controller_resolver.html)
    - [HttpFoundation](https://symfony.com/doc/current/create_framework/http_foundation.html)
- Roteamento
    - [Routing Component](https://symfony.com/doc/current/create_framework/routing.html)
- Autenticação
    - [lcobucci](https://github.com/lcobucci/jwt)
- Injeção de dependência
    - [The DependencyInjection Component](https://symfony.com/doc/current/create_framework/dependency_injection.html) 

**Frontend** - [Link Repositório Frontend](https://github.com/douglas-bernardo/app-renegociacao)
- [ReactJS](https://reactjs.org)
- [TypeScript](https://www.typescriptlang.org/)
- [Axios](https://github.com/axios/axios)

&nbsp;

## 🗂 Como baixar o projeto

---
### Pré-requisitos
Antes de começar, você vai precisar montar um ambiente padrão para desenvolvimento web em PHP (Recomendo fortemente uma pilha [LAMP](https://www.digitalocean.com/community/tutorials/how-to-install-linux-apache-mysql-php-lamp-stack-on-ubuntu-20-04-pt)).
- PHP 7.4+
- Apache 2.4+
- MariaDb 10+ 

Ferramentas:
- [Composer](https://getcomposer.org/) (prefira uma instalação global)
- [Git](https://git-scm.com/)

Além disto é bom ter uma boa IDE ou editor para trabalhar com o código. Recomendo o [PHPStorm](https://www.jetbrains.com/pt-br/phpstorm/) da Jetbrains ou o [VSCode](https://code.visualstudio.com/).

Instale as seguintes extensões PHP:
```bash
php8.0-common php8.0-mysql php8.0-xml php8.0-curl php8.0-gd php8.0-imagick php8.0-cli php8.0-dev php8.0-imap php8.0-mbstring php8.0-opcache php8.0-soap php8.0-zip php8.0-intl
```

&nbsp;

```bash

    # Clonar o repositório
    $ git clone https://github.com/douglas-bernardo/app-renegociacao

    # Entrar no diretório
    $ cd app-renegociacao

    # Instalar as dependências
    $ compposer install

    # Iniciar o projeto
    ## servidor imnutido PHP
    $ php -S localhost:8080 -t public/
    
    # ou acesse via localhost
    # http://localhost/pasta-do-projeto
```

&nbsp;

## ⚙️ Features

---

#### Infra
- [x] Integração com a API - Timesharing
- [x] Importação de ocorrências via crontab
- [x] Logs de importação

#### Autenticação / Permissão
- [x] JWT authenticate
- [x] Gerenciamento de permissões

#### Services
- [x] Endpoints reports 
- [x] Listagem de ocorrências por negociador
- [x] Detalhes da ocorrência
- [x] Detalhamento de negociações por negociador
- [x] Transferência de negociações entre colaboradores
- [x] Restaurar negociação
- [x] Criação/Listagem/Edição de usuários
- [x] Reset de senha de usuários
- [x] Registrar de negociação
- [x] Detalhes na negociação
- [x] Finalização de negociação
- [ ] Gerenciamento de metas da operação

---

&nbsp;

# Autor

<p>
  Feito com 💗 por Jackson Douglas 👋🏽 Entre em contato!
</p>

<br/>
<div>
  <a href = "mailto:jkdouglas21@gmail.com"><img src="https://img.shields.io/badge/-Gmail-%23333?style=for-the-badge&logo=gmail&logoColor=white" target="_blank"></a>
  <a href="https://www.linkedin.com/in/douglas-bernardo" target="_blank"><img src="https://img.shields.io/badge/-LinkedIn-%230077B5?style=for-the-badge&logo=linkedin&logoColor=white" target="_blank"></a>
  <a href="https://twitter.com/jkdouglas21" target="_blank"><img src="https://img.shields.io/badge/Twitter-1DA1F2?style=for-the-badge&logo=twitter&logoColor=white" target="_blank"></a>
</div>