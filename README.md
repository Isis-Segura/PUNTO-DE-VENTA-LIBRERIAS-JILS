<p align="center">
    <img src="public/vendor/adminlte/dist/img/J_logo.jpeg" alt="Logo Librería JILS">
</p>

<div align="center">

# Librería JILS

### Sistema POS e Inventario Multi-Sede

Aplicación web integral para la gestión centralizada de ventas, control de inventario y administración de sucursales.

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

## Descripción del Proyecto

Librería JILS es una plataforma web diseñada para optimizar y administrar las operaciones de una red de librerías con múltiples sucursales.

El sistema permite centralizar la información del catálogo de productos, controlar el inventario de cada sede de forma independiente, procesar transacciones mediante un Punto de Venta (POS) y gestionar los accesos de usuario mediante un sistema basado en roles.

El objetivo principal es automatizar los procesos operativos, reducir la carga de gestión manual y proporcionar métricas precisas y actualizadas sobre las existencias y el flujo de ventas corporativo.

---

## Características Principales

### Gestión Multi-Sede

- Administración centralizada del esquema de sucursales.
- Segmentación de inventario independiente por cada sede.
- Control detallado de productos y trazabilidad de existencias.
- Gestión de cajas registradoras y asignación de personal.

### Punto de Venta (POS)

- Interfaz optimizada para el registro ágil de ventas.
- Cálculo automatizado de importes, impuestos y subtotales.
- Integración y gestión de múltiples métodos de pago.
- Emisión de comprobantes y tickets de compra.
- Conciliación y descuento automático en el inventario.

### Control de Inventario

- Panel de consulta de existencias en tiempo real.
- Supervisión del nivel de stock segmentado por sucursal.
- Sistema de alertas automatizadas para bajo inventario.
- Catálogo maestro unificado para productos y fondo editorial.

### Roles y Permisos (RBAC)

| Rol | Funciones Principales |
|---|---|
| **Administrador General** | Acceso global y control total del sistema, gestión de sucursales, administración de usuarios y visualización de métricas generales. |
| **Gerente de Sede** | Administración del catálogo local, supervisión del inventario específico de su sucursal y gestión de aperturas/cierres de caja. |
| **Cajero** | Operación directa del Punto de Venta, procesamiento de transacciones y cobros al cliente. |

### Soporte Multilingüe

La arquitectura de la aplicación incluye soporte de internacionalización para los siguientes idiomas:

- Español
- Inglés

---

## Arquitectura del Sistema

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
                               Ventas
