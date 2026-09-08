<p align="center">
     <img src="public/vendor/adminlte/dist/img/J_logo.jpeg">
</p>

<div align="center">

# 📚 Librería JILS

### Sistema POS e Inventario Multi-Sede

Aplicación web para la gestión centralizada de ventas, inventario y sucursales.

<br>

[![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com/)
[![PHP](https://img.shields.io/badge/PHP-8.2+-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://www.php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-8.0+-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://www.mysql.com/)
[![Node.js](https://img.shields.io/badge/Node.js-20+-339933?style=for-the-badge&logo=node.js&logoColor=white)](https://nodejs.org/)
[![License](https://img.shields.io/badge/License-MIT-yellow?style=for-the-badge)](https://opensource.org/licenses/MIT)

<br>

**Proyecto Integrador — Facultad de Ingeniería Electromecánica**  
**Universidad de Colima**

**Semestre:** Agosto 2026 – Enero 2027  
**Grupo:** 3ºE — Ingeniería de Software

</div>

---

## 📌 Descripción

**Librería JILS** es una aplicación web diseñada para administrar las operaciones de una librería con múltiples sucursales.

El sistema permite centralizar la información de productos, controlar el inventario de cada sede, registrar ventas mediante un **Punto de Venta (POS)** y administrar los diferentes usuarios según sus responsabilidades.

El objetivo principal es reducir la gestión manual y proporcionar información actualizada sobre las existencias y ventas de cada sucursal.

---

## ✨ Características

### 🏢 Gestión Multi-Sede

- Administración centralizada de sucursales.
- Inventario independiente por sede.
- Control de productos y existencias.
- Gestión de cajas y cajeros.

### 🛒 Punto de Venta (POS)

- Registro de ventas.
- Cálculo automático de importes.
- Gestión de métodos de pago.
- Generación de tickets.
- Descuento automático del inventario.

### 📦 Inventario

- Consulta de existencias.
- Control de stock por sucursal.
- Alertas de bajo inventario.
- Catálogo centralizado de productos y libros.

### 🔐 Roles y Permisos

| Rol | Funciones principales |
|---|---|
| 👑 **Administrador General** | Control total del sistema, sucursales, usuarios y reportes |
| 🏢 **Gerente de Sede** | Administración de productos, inventario y cajas de su sucursal |
| 💰 **Cajero** | Registro de ventas y cobros en caja |

### 🌐 Soporte Multilingüe

La aplicación cuenta con soporte para:

- 🇲🇽 Español
- 🇺🇸 Inglés

---

## 🏗️ Arquitectura del Sistema

```text
                    ┌─────────────────────────┐
                    │ Administrador General   │
                    └────────────┬────────────┘
                                 │
                 ┌───────────────┴───────────────┐
                 │                               │
                 ▼                               ▼
        ┌─────────────────┐             ┌─────────────────┐
        │   Sucursal A    │             │   Sucursal B    │
        │    Gerente      │             │    Gerente      │
        └────────┬────────┘             └────────┬────────┘
                 │                               │
                 ▼                               ▼
        ┌─────────────────┐             ┌─────────────────┐
        │   Inventario A  │             │   Inventario B  │
        └────────┬────────┘             └────────┬────────┘
                 │                               │
                 ▼                               ▼
        ┌─────────────────┐             ┌─────────────────┐
        │ Cajas / Cajeros │             │ Cajas / Cajeros │
        └────────┬────────┘             └────────┬────────┘
                 │                               │
                 └───────────────┬───────────────┘
                                 ▼
                           🛒 Ventas
```

---

## 🗄️ Entidades de la Base de Datos

Las principales entidades contempladas para el sistema son:

```text
Usuarios
Roles
Sucursales
Productos
Categorías
Inventario
Ventas
Detalles_Venta
Métodos_Pago
```

Relación simplificada:

```text
Usuarios ──────► Roles
   │
   ▼
Sucursal ──────► Inventario
   │                 │
   │                 ▼
   └────────────► Productos
                     │
                     ▼
                   Ventas
                     │
                     ▼
              Detalles_Venta
                     │
                     ▼
               Métodos_Pago
```

---

# 🚀 Instalación

## 📋 Requisitos

Antes de comenzar, asegúrate de tener instalado:

- **PHP 8.2 o superior**
- **Composer 2.0 o superior**
- **MySQL 8.0 o superior**
- **Node.js y NPM**
- **Git**

---

## 1️⃣ Clonar el repositorio

```bash
git clone https://github.com/tu-usuario/libreria-jils.git
cd libreria-jils
```

---

## 2️⃣ Instalar dependencias

Instala las dependencias de PHP:

```bash
composer install
```

Después instala las dependencias de JavaScript:

```bash
npm install
```

Compila los assets:

```bash
npm run build
```

---

## 3️⃣ Configurar el entorno

Copia el archivo `.env.example`:

```bash
cp .env.example .env
```

Genera la clave de la aplicación:

```bash
php artisan key:generate
```

> **Nota:** En Windows también puedes copiar `.env.example` manualmente y renombrarlo como `.env`.

---

## 4️⃣ Configurar la base de datos

Abre el archivo `.env` y configura los datos de conexión:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=jils_pos
DB_USERNAME=root
DB_PASSWORD=
```

Asegúrate de que la base de datos `jils_pos` exista en MySQL.

---

## 5️⃣ Ejecutar migraciones

Ejecuta las migraciones y los seeders:

```bash
php artisan migrate --seed
```

---

## 6️⃣ Iniciar el servidor

Ejecuta:

```bash
php artisan serve
```

Después abre en tu navegador:

```text
http://127.0.0.1:8000
```

---

# 🤖 Desarrollo con IA

El proyecto puede complementarse con herramientas de desarrollo asistido por inteligencia artificial, como:

- Claude Code
- Cursor
- GitHub Copilot
- Laravel Boost

### Laravel Boost

Para instalar Laravel Boost:

```bash
composer require laravel/boost --dev
```

Después:

```bash
php artisan boost:install
```

---

# 🛠️ Tecnologías Utilizadas

| Tecnología | Uso |
|---|---|
| 🟥 **Laravel** | Framework backend |
| 🐘 **PHP** | Lenguaje principal |
| 🗄️ **MySQL** | Base de datos |
| 🟢 **Node.js** | Entorno para herramientas frontend |
| 📦 **NPM** | Gestión de paquetes |
| 🎨 **Blade** | Motor de plantillas |
| 🔐 **RBAC** | Control de acceso por roles |

---

# 👥 Equipo de Desarrollo

## 🎓 Grupo 3E — Ingeniería de Software

| Integrante |
|---|
| **Díaz Maluenga Joshua Gabriel** |
| **Figueroa Huerta Isai Shalom** |
| **Segura Paulino Isis Alejandra** |
| **Virgen Ambriz Lucia Lorena** |

### 👨‍🏫 Tutor de Grupo

**Mtro. Emilio Ballinas Arteaga**

---

# 👨‍🏫 Comité Docente

| Asignatura | Docente |
|---|---|
| **Estructura de Datos** | Ernesto Navarro Álvarez |
| **Base de Datos** | Enrique C. Rosales Busquets |
| **Metodologías Ágiles** | Emilio Ballinas Arteaga |
| **Legislación y Derecho Informático** | Fernando Tomás Díaz García |
| **Matemáticas Discretas** | Juan Pablo Martínez Vargas |
| **Estructuras de Computadoras** | Daniel Alfonso Verde Romero |
| **Inglés III** | Ilse Abarca Torres |

---

# 📚 Recursos

- 📖 [Documentación oficial de Laravel](https://laravel.com/docs)
- 🎓 [Laracasts](https://laracasts.com/)
- 🚀 [Laravel Learn](https://laravel.com/learn)

---

# 🔒 Seguridad

Si encuentras una vulnerabilidad de seguridad en este proyecto, evita publicar los detalles públicamente.

Para vulnerabilidades relacionadas con Laravel, consulta los canales oficiales de seguridad de Laravel.

---

# 📄 Licencia

Este proyecto está disponible bajo la licencia **MIT**.

Consulta el archivo `LICENSE` para obtener más información.

---

<div align="center">

### 📚 Librería JILS

**Sistema POS e Inventario Multi-Sede**

</div>
