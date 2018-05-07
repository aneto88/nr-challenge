# Challenge Crawler

Esse projeto é apenas um teste de crawler, usando o [CNPQ](http://www.cnpq.br/web/guest/licitacoes)

## Installation using [Composer](https://getcomposer.org/)

Clone ou faça o download este repositório em sua máquina local(em um http server), e em seu terminal rode:

```sh
$ cd ~/MeusProjetos/challenge-crawler
$ composer install
```

Após o composer instalar as dependências, precisamos configurar o projeto:

```sh
$ cd ~/MeusProjetos/challenge-crawler
$ cp .env.example .env //modificar no arquivo as credenciais do banco para rodar as migrações;

 //caso deseje salvar os arquivos anexados em disco local.
$ php artisan storage:link

//criando tabelas no banco.
$ php artisan migrate
```


## Usage

A importação funciona via command line(cli).

São importados dados básicos da licitação e alguns dados de anexo.

```sh
//por default esse comando importa apenas a primeira página de licitações (10) e os anexos são importados apenas as urls.
$ php artisan scrape:cnpq 

//importando os arquivos para disco local
$ php artisan scrape:cnpq --importFile

//importando com um numero de página definido, basta user o parâmetro --pages=X
$ php artisan scrape:cnpq --pages=3

//importando todas as páginas encontradas, basta usar o parâmetro --allPages
$ php artisan scrape:cnpq --allPages

//para resetar o banco e importar dados novos, basta usar o parâmetro --reset
$ php artisan scrape:cnpq --reset
```

```
 Para visualizar os dados importados, basta acessar (http://host/biddings) (é retornado um json puro, sem tratamentos, apenas para fins de visualização).
```

## TODO

Implementar Telas para visualização de dados;
Importar meta_dados;
Refatorar código para novas fontes de dados;
