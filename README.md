<div align="center">

<img src="docs/assets/logo.png" alt="Logo Librería JILS" width="180"/>

# Librería JILS
### Sistema POS e Inventario Multi-Sede

Aplicación web para la gestión centralizada de ventas, inventario y sucursales.

</div>

---

## Información del proyecto

| Dato | Detalle |
|---|---|
| Proyecto | Proyecto Integrador — Facultad de Ingeniería Electromecánica |
| Institución | Universidad de Colima |
| Semestre | Agosto 2026 – Enero 2027 |
| Grupo | 3ºE — Ingeniería de Software |

---

## Descripción

Librería JILS es una aplicación web diseñada para administrar las operaciones de una librería con múltiples sucursales. El sistema centraliza la información de productos, controla el inventario de cada sede, registra ventas mediante un Punto de Venta (POS) y administra los distintos usuarios según sus responsabilidades.

El objetivo principal es reducir la gestión manual y mantener información actualizada sobre las existencias y ventas de cada sucursal.

---

## Características

### Gestión multi-sede
- Administración centralizada de sucursales.
- Inventario independiente por sede.
- Control de productos y existencias.
- Gestión de cajas y cajeros.

### Punto de venta (POS)
- Registro de ventas.
- Cálculo automático de importes.
- Gestión de métodos de pago.
- Generación de tickets.
- Descuento automático del inventario.

### Inventario
- Consulta de existencias.
- Control de stock por sucursal.
- Alertas de bajo inventario.
- Catálogo centralizado de productos y libros.

### Roles y permisos

| Rol | Funciones principales |
|---|---|
| Administrador General | Control total del sistema, sucursales, usuarios y reportes |
| Gerente de Sede | Administración de productos, inventario y cajas de su sucursal |
| Cajero | Registro de ventas y cobros en caja |

### Soporte multilingüe
La aplicación cuenta con soporte para español e inglés.

---

## Arquitectura del sistema

```
                    Administrador General
                            │
              ┌─────────────┴─────────────┐
              │                           │
         Sucursal A                  Sucursal B
          (Gerente)                   (Gerente)
              │                           │
        Inventario A                Inventario B
              │                           │
       Cajas / Cajeros            Cajas / Cajeros
              │                           │
              └─────────────┬─────────────┘
                             │
                          Ventas
```

---

## Entidades de la base de datos

Principales entidades del sistema:

- Usuarios
- Roles
- Sucursales
- Productos
- Categorías
- Inventario
- Ventas
- Detalles_Venta
- Métodos_Pago

**Relación simplificada:**

```
Usuarios ──► Roles

Sucursal ──► Inventario ──► Productos

Ventas ──► Detalles_Venta ──► Métodos_Pago
```

---

## Instalación

### Requisitos previos

- PHP 8.2 o superior
- Composer 2.0 o superior
- MySQL 8.0 o superior
- Node.js y NPM
- Git

### 1. Clonar el repositorio

```bash
git clone https://github.com/tu-usuario/libreria-jils.git
cd libreria-jils
```

### 2. Instalar dependencias

```bash
composer install
npm install
npm run build
```

### 3. Configurar el entorno

```bash
cp .env.example .env
php artisan key:generate
```

> En Windows, si el comando `cp` no funciona, copia manualmente `.env.example` y renómbralo como `.env`.

### 4. Configurar la base de datos

Edita el archivo `.env` con tus datos de conexión:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=jils_pos
DB_USERNAME=root
DB_PASSWORD=
```

Asegúrate de que la base de datos `jils_pos` exista en MySQL antes de continuar.

### 5. Ejecutar migraciones

```bash
php artisan migrate --seed
```

### 6. Iniciar el servidor

```bash
php artisan serve
```

Abre tu navegador en: [http://127.0.0.1:8000](http://127.0.0.1:8000)

---


Para instalar Laravel Boost:

```bash
composer require laravel/boost --dev
php artisan boost:install
```

---

## Tecnologías utilizadas

| Tecnología | Uso |
|---|---|
| Laravel | Framework backend |
| PHP | Lenguaje principal |
| MySQL | Base de datos |
| Node.js | Entorno para herramientas frontend |
| NPM | Gestión de paquetes |
| Blade | Motor de plantillas |
| RBAC | Control de acceso por roles |

---


## **Equipo de Desarrollo**

| Nombre             | GitHub       |
| ------------------ | ------------ |
| **ISIS SEGURA**    | @Isis-Segura |
| **JOSGUA MALUENGA** | @JGDM84      |
| **ISAI FIGUEROA** | @FigueroaShalom  |
| **LUCIA VIRGEN** | @Lucia-va |


## Recursos

- [Documentación oficial de Laravel](https://laravel.com/docs)
- [Laracasts](https://laracasts.com)
- [Laravel Learn](https://laravel.com/learn)

---

## Seguridad

Si encuentras una vulnerabilidad de seguridad en este proyecto, evita publicar los detalles públicamente. Para vulnerabilidades relacionadas con Laravel, consulta los canales oficiales de seguridad de Laravel.

---

## Licencia

Este proyecto está disponible bajo la licencia MIT. Consulta el archivo `LICENSE` para más información.
