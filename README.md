<div align="center">

<img src="public\vendor\adminlte\dist\img\logoooo.jpeg" alt="Logo Librería JILS" width="180"/>

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

- Windows
- Docker Desktop instalado y en ejecución (con Docker Compose)
- Git

### 1. Descargar el proyecto

Clona el repositorio y abre su carpeta:

```powershell
git clone https://github.com/Isis-Segura/PUNTO-DE-VENTA-LIBRERIAS-JILS.git
cd PUNTO-DE-VENTA-LIBRERIAS-JILS
```

### 2. Iniciar el sistema

Con Docker Desktop en ejecución, abre `iniciar.bat` con doble clic o ejecútalo desde PowerShell:

```powershell
.\iniciar.bat
```

El script construye la imagen si hace falta, inicia los contenedores y abre estas direcciones:

- Aplicación: [http://localhost:8000](http://localhost:8000)
- Administración de la base de datos (phpMyAdmin): [http://localhost:8081](http://localhost:8081)

La primera vez puede tardar unos minutos mientras Docker descarga y construye los servicios. Al iniciar, el contenedor de la aplicación prepara la base de datos y ejecuta las migraciones y los datos iniciales automáticamente. No es necesario instalar PHP, Composer, MySQL ni Node.js en Windows.

### 3. Detener el sistema

Cuando termines, abre `detener.bat` o ejecútalo desde PowerShell:

```powershell
.\detener.bat
```

Esto detiene y elimina los contenedores, pero conserva los datos de la base de datos para el siguiente inicio. Para volver a usar el sistema, ejecuta `iniciar.bat` de nuevo.

> En este repositorio el archivo para detener el proyecto se llama `detener.bat` (no `apagar.bat`).

### Correo electrónico (opcional)

Por defecto, los correos no se envían: Laravel los registra en sus logs. Para habilitar el envío, crea un archivo `.env` en la raíz del proyecto con la configuración SMTP de tu proveedor. No compartas ni subas ese archivo o sus credenciales al repositorio. Después de cambiar la configuración, detén e inicia el proyecto para aplicar los cambios.

### Imágenes de las portadas

Las portadas que se suben desde el formulario de productos se guardan directamente en:

```text
public/portadas
```

La carpeta forma parte del proyecto y las imágenes se sirven con URLs como:

```bash
/portadas/nombre-del-archivo.jpg
```

Al estar dentro de `public`, las imágenes se pueden subir a Git junto con el resto del proyecto y estarán disponibles después de clonar el repositorio. No es necesario ejecutar `php artisan storage:link`.

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
## Licencia

Este proyecto está disponible bajo la licencia MIT. Consulta el archivo `LICENSE` para más información.
