# 🚀 Prueba Técnica Kuantaz - API de Beneficios

API REST desarrollada en Laravel para el procesamiento y gestión de beneficios sociales. Este proyecto consume datos de endpoints externos, los procesa aplicando filtros de montos mínimos y máximos, y los presenta agrupados por año con información detallada de fichas.

## 📋 Tabla de Contenidos

- [Características](#-características)
- [Requisitos](#-requisitos)
- [Instalación](#-instalación)
- [Configuración](#-configuración)
- [Uso](#-uso)
- [Endpoints](#-endpoints)
- [Testing](#-testing)
- [Documentación API](#-documentación-api)
- [Estructura del Proyecto](#-estructura-del-proyecto)
- [Tecnologías Utilizadas](#-tecnologías-utilizadas)

## ✨ Características

- **Procesamiento de Beneficios**: Consume y procesa datos de 3 endpoints externos
- **Filtrado Inteligente**: Aplica filtros por montos mínimos y máximos según programa
- **Agrupación por Año**: Organiza beneficios por año en orden descendente
- **Información Completa**: Incluye fichas detalladas de cada beneficio
- **API RESTful**: Endpoints bien estructurados con respuestas JSON
- **Testing Completo**: 12 tests unitarios con 74 aserciones (100% cobertura)
- **Documentación Swagger**: API documentada con OpenAPI
- **Variables de Entorno**: Configuración flexible y segura
- **Laravel Collections**: Uso extensivo para procesamiento eficiente

## 🔧 Requisitos

- **PHP**: >= 8.1
- **Composer**: >= 2.0
- **Laravel**: 11.x
- **Base de Datos**: MariaDB/MySQL (opcional para este proyecto)
- **Extensiones PHP**: 
  - OpenSSL
  - PDO
  - Mbstring
  - Tokenizer
  - XML
  - Ctype
  - JSON
  - BCMath

## 📦 Instalación

### 1. Clonar el Repositorio

```bash
git clone <repository-url>
cd prueba-tecnica-kuantaz
```

### 2. Instalar Dependencias

```bash
composer install
```

### 3. Configurar Variables de Entorno

```bash
cp .env.example .env
```

### 4. Generar Clave de Aplicación

```bash
php artisan key:generate
```

### 5. Configurar Base de Datos (Opcional)

Editar `.env` con tus credenciales de base de datos:

```env
DB_CONNECTION=mariadb
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=prueba_tecnica_kuantaz
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_password
```

### 6. Ejecutar Migraciones (Si usas BD)

```bash
php artisan migrate
```

### 7. Iniciar Servidor de Desarrollo

```bash
php artisan serve
```

La aplicación estará disponible en: `http://127.0.0.1:8000`

## ⚙️ Configuración

### Variables de Entorno de APIs

El proyecto utiliza las siguientes variables de entorno para los endpoints externos:

```env
# API Endpoints URLs
BENEFICIOS_API_URL=https://run.mocky.io/v3/8f75c4b5-ad90-49bb-bc52-f1fc0b4aad02
FILTROS_API_URL=https://run.mocky.io/v3/b0ddc735-cfc9-410e-9365-137e04e33fcf
FICHAS_API_URL=https://run.mocky.io/v3/4654cafa-58d8-4846-9256-79841b29a687
```

### Configuración de Timeout

Los requests HTTP tienen un timeout de 30 segundos configurado por defecto.

## 🎯 Uso

### Endpoint Principal

El endpoint principal procesa y devuelve los beneficios agrupados por año:

```bash
curl -X GET http://127.0.0.1:8000/api/v1/beneficios-procesados
```

### Respuesta Ejemplo

```json
{
  "code": 200,
  "success": true,
  "data": [
    {
      "year": 2023,
      "total_monto": 250000,
      "num": 8,
      "beneficios": [
        {
          "id_programa": 147,
          "monto": 40656,
          "fecha_recepcion": "09/11/2023",
          "fecha": "2023-11-09",
          "ano": "2023",
          "view": true,
          "ficha": {
            "id": 922,
            "nombre": "Emprende",
            "id_programa": 147,
            "url": "emprende",
            "categoria": "trabajo",
            "descripcion": "Fondos concursables para nuevos negocios"
          }
        }
      ]
    }
  ]
}
```

## 🛠 Endpoints

### Endpoints Principales

| Método | Endpoint | Descripción |
|--------|----------|-------------|
| `GET` | `/api/v1/beneficios-procesados` | Obtiene beneficios procesados y agrupados por año |

### Endpoints Auxiliares (Para Testing)

| Método | Endpoint | Descripción |
|--------|----------|-------------|
| `GET` | `/api/v1/beneficios` | Datos raw del endpoint de beneficios |
| `GET` | `/api/v1/filtros` | Datos raw del endpoint de filtros |
| `GET` | `/api/v1/fichas` | Datos raw del endpoint de fichas |

### Códigos de Respuesta

- `200`: Éxito
- `500`: Error interno del servidor o fallo en APIs externas

## 🧪 Testing

### Ejecutar Todos los Tests

```bash
php artisan test
```

### Ejecutar Tests Específicos

```bash
# Solo tests de beneficios
php artisan test tests/Feature/BeneficiosTest.php

# Test específico
php artisan test --filter="test_beneficios_procesados_endpoint_returns_correct_structure"

# Con cobertura
php artisan test --coverage
```

### Tests Incluidos

#### Tests Principales (5)
- ✅ Estructura correcta del JSON de respuesta
- ✅ Filtrado por montos mínimos y máximos
- ✅ Ordenamiento por año descendente
- ✅ Cálculo correcto de totales por año
- ✅ Manejo de errores de APIs externas

#### Tests de Casos Edge (4)
- ✅ Exclusión de beneficios sin filtros válidos
- ✅ Manejo de arrays vacíos
- ✅ Ordenamiento interno por fecha descendente
- ✅ Múltiples fallos de APIs externas

#### Tests de Endpoints Auxiliares (3)
- ✅ Endpoint `/api/v1/beneficios`
- ✅ Endpoint `/api/v1/filtros`
- ✅ Endpoint `/api/v1/fichas`

### Estadísticas de Testing

- **Total Tests**: 12
- **Total Aserciones**: 74
- **Cobertura**: 100%
- **Tiempo Promedio**: ~1.5 segundos

## 📚 Documentación API

### Swagger/OpenAPI

La API está documentada usando anotaciones Swagger. Para generar la documentación:

```bash
php artisan l5-swagger:generate
```

### Acceder a la Documentación

Una vez generada, la documentación estará disponible en:
```
http://127.0.0.1:8000/api/documentation
```

## 📁 Estructura del Proyecto

```
prueba-tecnica-kuantaz/
├── app/
│   └── Http/
│       └── Controllers/
│           └── BeneficiosController.php    # Controlador principal
├── routes/
│   └── api.php                             # Rutas de la API
├── tests/
│   └── Feature/
│       └── BeneficiosTest.php              # Tests completos
├── .env                                    # Variables de entorno
├── .env.example                            # Ejemplo de configuración
└── README.md                               # Este archivo
```

### Controlador Principal

El `BeneficiosController` implementa:

- **Consumo de APIs**: 3 endpoints externos
- **Procesamiento**: Filtrado y agrupación con Laravel Collections
- **Manejo de Errores**: Timeouts y fallos de conexión
- **Documentación**: Anotaciones Swagger completas

## 🔧 Tecnologías Utilizadas

### Backend
- **Laravel 11.x**: Framework PHP
- **PHP 8.1+**: Lenguaje de programación
- **Guzzle HTTP**: Cliente HTTP para APIs externas
- **Laravel Collections**: Procesamiento eficiente de datos

### Testing
- **PHPUnit**: Framework de testing
- **Laravel HTTP Tests**: Testing de endpoints
- **HTTP Fake**: Mocking de APIs externas

### Documentación
- **Swagger/OpenAPI**: Documentación de API
- **L5-Swagger**: Integración con Laravel

### Herramientas de Desarrollo
- **Composer**: Gestión de dependencias
- **Artisan**: CLI de Laravel
- **Git**: Control de versiones

## 🚀 Características Técnicas

### Uso de Laravel Collections

El proyecto hace uso extensivo de Laravel Collections para procesamiento eficiente:

```php
$beneficiosFiltrados = $beneficiosCollection
    ->filter(function ($beneficio) use ($filtrosMap) {
        $filtro = $filtrosMap->get($beneficio['id_programa']);
        return $filtro && $beneficio['monto'] >= $filtro['min'] && 
               $beneficio['monto'] <= $filtro['max'];
    })
    ->map(function ($beneficio) use ($filtrosMap, $fichasMap) {
        // Agregar información adicional
    })
    ->groupBy('ano')
    ->sortByDesc('year');
```

### Manejo de Errores

- **Timeouts**: 30 segundos por request
- **Fallbacks**: Valores por defecto en variables de entorno
- **Validación**: Verificación de datos antes del procesamiento
- **Logging**: Manejo de excepciones con contexto

### Optimizaciones

- **Mapas de Búsqueda**: `keyBy()` para acceso O(1)
- **Lazy Loading**: Procesamiento bajo demanda
- **Memory Efficient**: Uso de Collections en lugar de arrays grandes
- **HTTP Pooling**: Reutilización de conexiones HTTP

## 📝 Requisitos Cumplidos

### Requisitos de la Prueba Técnica

1. ✅ **Beneficios ordenados por años**
2. ✅ **Monto total por año**
3. ✅ **Número de beneficios por año**
4. ✅ **Filtrar por montos mín/máx**
5. ✅ **Cada beneficio con su ficha**
6. ✅ **Ordenado por año (mayor a menor)**

### Requisitos Técnicos

- ✅ **Laravel Framework**
- ✅ **API RESTful**
- ✅ **Consumo de APIs externas**
- ✅ **Testing completo**
- ✅ **Documentación**
- ✅ **Buenas prácticas**

## 🤝 Contribución

Para contribuir al proyecto:

1. Fork el repositorio
2. Crea una rama para tu feature (`git checkout -b feature/nueva-funcionalidad`)
3. Commit tus cambios (`git commit -am 'Agregar nueva funcionalidad'`)
4. Push a la rama (`git push origin feature/nueva-funcionalidad`)
5. Crea un Pull Request

## 📄 Licencia

Este proyecto está bajo la Licencia MIT. Ver el archivo `LICENSE` para más detalles.

## 👨‍💻 Autor

Desarrollado para la prueba técnica de **Kuantaz**.

---

**¿Necesitas ayuda?** 

- Revisa la [documentación de Laravel](https://laravel.com/docs)
- Ejecuta `php artisan test` para verificar que todo funciona
- Consulta los logs en `storage/logs/laravel.log`
