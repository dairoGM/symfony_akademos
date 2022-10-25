## How to install for develop

```
cp .env .env.local
```

```
export APP_ENV=dev
```

```
composer install
```

```
composer dump-env
```

```
php bin/console doctrine:migrations:migrate
```

```
php bin/console cache:clear
```

## How to install for production

```
cp .env .env.local
```

```
export APP_ENV=prod
```

```
composer install --no-dev -o --no-scripts
```

```
composer dump-env prod
```

```
php bin/console doctrine:migrations:migrate
php bin/console doctrine:schema:update --dump-sql
```

```
php bin/console --env=prod cache:clear
```

## Scaffolding

```
Project
|
│   README.md
│   migrations    
│       │   migration1.php      
│       │   migration2.php
│   
└─── Controller 
|   
|   SomeCommonClass.php
│   
│   └─── Admin
│       │   UserController.php       
│       │   ...
│   └─── Rrhh
│       │   PersonController.php       
│       │   ...
| 
│   
└─── Entity
|   
|   SomeCommonClass.php
|
│   └─── Security
│       │   UserRepository.php      
│       │   ...
│   └─── Rrhh
│       │   PersonRepository.php       
│       │   ...
│   
└─── Repository
|   
|   SomeCommonClass.php
|
│   └─── Security
│       │   UserRepository.php      
│       │   ...
│   └─── Rrhh
│       │   PersonRepository.php       
│       │   ...
│   
└─── Form
|   
|   SomeCommonClass.php
|
│   └─── Admin
│       │   UserRepository.php      
│       │   ...
│   └─── Rrhh
│       │   PersonRepository.php       
│       │   ...
|
└─── Services
|   
|   HorizontalService1.php
|   HorizontalService2.php
|   UtilService.php
|
│   └─── Admin
│       │   UserService.php       
│       │   ...
│   └─── Rrhh
│       │   PersonService.php       
│       │   ...
|   └─── Plannig
│       │   PlanningService.php       
│       │   ...

```

### Migrations ###
Contiene las migraciones para las actualizaciones de la BD. Las migraciones serán aplicadas para mantener la BD. Sólo deben ser generadas por el diseñador de BD.


### Controller ###
Controlladoras. Organizadas en carpetas por Módulos (visuales) de la aplicación. Usarán la capa de negocio y Formularios. No pueden usar los Repositorios directamente.

### Entity ###
Definirá las entidadades del sistema. Organizadas en carpetas por bd-schema.

### Fomr ###
Formularios de symfony. Organizadas en carpetas por Módulos (visuales) de la aplicación.

### Security ###
Carpeta especial que contendrá los servicios de seguridad, configuraciones, relativo a núcleo como tal. 

### Services ###
Servicios generales y horizontales, útiles, etc.
También aquí se programarán los servicios organizados por lógica de negocio en carpetas (no necesariamente por módulo o bd-schema), aunque generalmente uno por entidad. 

Se programará la lógica de negocio. Ya que puede ser reutilizada por cada uno de las controlladoras y demás partes del sistema.





## How To Configure LexikJWTAuthenticationBundle?

View [Documentation](https://github.com/lexik/LexikJWTAuthenticationBundle/blob/master/Resources/doc/index.md#installation).

> URL API

```
 https://xx.dev/
```


##  How To generate new user

```
php bin/console app:add-user
```
```
This command receives four parameters:
  1-mail
  2-role: ROLE_ADMIN o ROLE_USER
  3-password
  4-confirm password
```

## Endpoint para autenticarse:

> Request

```
curl -X POST https://127.0.0.1:8000/api/login_check \
     -H "Content-Type: application/json" \
     -d '
      {
        "email":"admin@aotronivel.com",
        "password":"123"
      }
     '
```

> Response

```
{
   "token":"eyiOiJSUzI1NiJ9.eyJpYXQiOJ9.4NTdlhy183w5JY"
}
```

> Migrations
```
 php bin/console doctrine:database:drop --force
 php bin/console doctrine:database:create 
 php bin/console doctrine:schema:update --force
 php bin/console doctrine:fixtures:load

 php bin/console make:entity 
 php bin/console make:migration
 php bin/console doctrine:migrations:migrate
```

> Install 

### Instalar Módulos
```
 php bin/console app:modules
```
update: creay y actualiza los módulos (Partiendo de la configuración de Instalación).


### Instalar Funcionalidades
```
php bin/console app:functionalities
```
create : Crea las funcionalidades nuevas. (Partiendo de la configuración de Instalación).
update : Crea las funcionalidades nuevas. Modifica las creadas. (Partiendo de la configuración de Instalación)
disable: Deshabilita las funcionlidades obsoletas. (Las que ya no se encuentran en de la configuración de Instalación)
delete: Elimina todas las funcionalidades que no están asignadas (Partiendo de la configuración de Instalación).

### Instalar Roles
```
 php bin/console app:roles
```
create: crea los roles del sistema con las funcionalidades asociadas (Partiendo de la configuración de Instalación).
update: actualiza los roles del sistema con las funcionalidades asociadas, elimina primero todas las funcionalidades que fueron  asociadas anteriomente (Partiendo de la configuración de Instalación).
