# PrecioJusto

PrecioJusto es una aplicación web para consultar y comparar precios de productos ofrecidos por distintas tiendas. Su propósito es ayudar a los consumidores a encontrar opciones económicas y planificar mejor sus compras.

## Funcionalidades disponibles

- Catálogo de productos con búsqueda, filtros y ordenamiento.
- Consulta de categorías, ofertas y detalle de cada producto, incluidos precios y tiendas disponibles.
- Registro, inicio de sesión y edición del perfil de usuario.
- Productos favoritos para usuarios autenticados.
- Comentarios y calificaciones de productos.
- Panel de administración con pantallas para gestionar productos, tiendas y usuarios.

El documento de requisitos también propone listas de compra, alertas personalizadas y funciones para comercios asociados. Esas capacidades corresponden a objetivos de evolución y no se describen aquí como funciones disponibles en esta versión del código.

## Tecnologías

- PHP 8.1 o posterior
- MySQL o MariaDB, con la extensión PDO MySQL de PHP
- HTML5, CSS3 y JavaScript
- Bootstrap 5 y Font Awesome
- AJAX para algunas interacciones de la interfaz

## Requisitos previos

Instala un entorno local como XAMPP, WAMP o equivalente con Apache, PHP y MySQL/MariaDB. Necesitas habilitar `pdo_mysql` en PHP.

## Instalación local

1. Clona o copia el proyecto dentro del directorio público de Apache, por ejemplo `htdocs/ProyectoDes`.
2. En MySQL/MariaDB, crea una base de datos llamada `proyecto`.
3. Importa el archivo [`db/proyecto.sql`](db/proyecto.sql) en esa base de datos desde phpMyAdmin o la consola de MySQL.
4. Configura la conexión. Por defecto, la aplicación usa `localhost`, la base `proyecto`, el usuario `root` y contraseña vacía. Puedes sobrescribir estos valores con las variables de entorno `DB_HOST`, `DB_NAME`, `DB_USER` y `DB_PASSWORD`.
5. Inicia Apache y MySQL/MariaDB.
6. Abre la aplicación en el navegador. Si la carpeta es `ProyectoDes`, la página principal está en `http://localhost/ProyectoDes/app/views/index.php` y el acceso del panel administrativo en `http://localhost/ProyectoDes/app/dashboard/login.php`.

> Cambia las credenciales predeterminadas antes de publicar el sistema en un servidor accesible por otras personas.

## Estructura del proyecto

```text
app/
├── controllers/       # Controladores de páginas, acciones y administración
├── css/               # Hojas de estilo
├── img/               # Imágenes de productos
├── js/                # Scripts del navegador
├── models/            # Conexión PDO y modelos de datos
├── dashboard/         # Rutas públicas del panel administrativo
└── views/
    └── pages/         # Plantillas de la aplicación y del panel

db/
└── proyecto.sql       # Esquema y datos iniciales de la base de datos
```

Las rutas PHP existentes en `app/views` y `app/dashboard` mantienen los enlaces de la aplicación y delegan la preparación de datos a controladores. La conexión compartida se configura en `app/models/Database.php`.

## Documentación del proyecto

La especificación académica detalla el problema, los roles de usuario, los requisitos, los casos de uso, el modelo de datos y el plan de pruebas de PrecioJusto. La implementación de este repositorio debe consultarse junto con ese documento para distinguir las funciones desarrolladas de las propuestas para futuras versiones.

## Contexto académico

Proyecto del Departamento de Ingeniería de Sistemas de la Universidad de Córdoba, documentado por Rubén Darío Mora Martínez y Juan Eduardo Jattin Ceballos.
