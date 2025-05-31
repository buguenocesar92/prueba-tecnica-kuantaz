# 🚀 Prueba Técnica Kuantaz - API de Beneficios

[![CI](https://github.com/buguenocesar92/prueba-tecnica-kuantaz/actions/workflows/ci.yml/badge.svg)](https://github.com/buguenocesar92/prueba-tecnica-kuantaz/actions)
[![Coverage](https://img.shields.io/badge/coverage-100%25-brightgreen)](https://github.com/buguenocesar92/prueba-tecnica-kuantaz)
[![PHPStan](https://img.shields.io/badge/PHPStan-level%205-brightgreen)](https://phpstan.org/)
[![Laravel Pint](https://img.shields.io/badge/Laravel%20Pint-passing-brightgreen)](https://laravel.com/docs/pint)
[![Tests](https://img.shields.io/badge/tests-46%20passing-brightgreen)](https://github.com/buguenocesar92/prueba-tecnica-kuantaz)
[![PHP Version](https://img.shields.io/badge/php-%5E8.2-blue)](https://php.net)
[![Laravel](https://img.shields.io/badge/laravel-12.x-red)](https://laravel.com)

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
- [Colección de Postman](#-colección-de-postman)
- [Estructura del Proyecto](#-estructura-del-proyecto)
- [Tecnologías Utilizadas](#-tecnologías-utilizadas)

## ✨ Características

- **Procesamiento de Beneficios**: Consume y procesa datos de 3 endpoints externos
- **Filtrado Inteligente**: Aplica filtros por montos mínimos y máximos según programa
- **Agrupación por Año**: Organiza beneficios por año en orden descendente
- **Información Completa**: Incluye fichas detalladas de cada beneficio
- **API RESTful**: Endpoint bien estructurado con respuestas JSON
- **Testing Completo**: 46 tests unitarios y de integración con 130 aserciones (100% cobertura)
- **Arquitectura Limpia**: Implementación con DTOs, Repositories y Service Layer
- **Documentación Swagger**: API documentada con OpenAPI
- **Variables de Entorno**: Configuración flexible y segura
- **Laravel Collections**: Uso extensivo para procesamiento eficiente

## 🔧 Requisitos

- **PHP**: >= 8.2
- **Composer**: >= 2.0
- **Laravel**: 12.x
- **Base de Datos**: No requerida (API consume datos externos)
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
git clone https://github.com/buguenocesar92/prueba-tecnica-kuantaz.git
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

### 5. Iniciar Servidor de Desarrollo

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

El proyecto tiene un único endpoint que procesa y devuelve los beneficios agrupados por año:

```bash
curl -X GET http://127.0.0.1:8000/api/v1/beneficios-procesados
```

### Respuesta Ejemplo

```json
[
  {
    "ano": "2023",
    "total_monto": 295608,
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
```

## 🛠 Endpoints

### Endpoint Principal

| Método | Endpoint | Descripción |
|--------|----------|-------------|
| `GET` | `/api/v1/beneficios-procesados` | Obtiene beneficios procesados y agrupados por año |

### Códigos de Respuesta

- `200`: Éxito - Retorna array de beneficios agrupados por año
- `500`: Error interno del servidor o fallo en APIs externas

## 🧪 Testing

### Ejecutar Todos los Tests

```bash
php artisan test
```

### Ejecutar Tests con Cobertura

```bash
composer test-coverage
```

### Tests Incluidos

#### Tests de DTOs (4 tests)
- ✅ Creación correcta de FiltroDTOs desde arrays
- ✅ Conversión a array de FiltroDTOs
- ✅ Validación de montos válidos e inválidos
- ✅ Métodos de validación de FiltroDTOs

#### Tests de Modelos (5 tests)
- ✅ Atributos fillable del modelo User
- ✅ Atributos hidden del modelo User
- ✅ Métodos de casting del modelo User
- ✅ Traits utilizados por el modelo User
- ✅ Instanciación correcta del modelo User

#### Tests de Servicios (5 tests)
- ✅ Procesamiento de beneficios con datos válidos
- ✅ Filtrado por montos mínimos y máximos
- ✅ Exclusión de beneficios sin filtros válidos
- ✅ Exclusión de beneficios sin fichas
- ✅ Ordenamiento por año descendente

#### Tests de Endpoints (9 tests)
- ✅ Estructura correcta del JSON de respuesta
- ✅ Filtrado por montos mínimos y máximos
- ✅ Ordenamiento por año descendente
- ✅ Cálculo correcto de totales por año
- ✅ Manejo de errores de APIs externas
- ✅ Exclusión de beneficios sin filtros válidos
- ✅ Manejo de arrays vacíos
- ✅ Ordenamiento interno por fecha ascendente
- ✅ Múltiples fallos de APIs externas

#### Tests de Providers (3 tests)
- ✅ Registro correcto de bindings de interfaces
- ✅ Ejecución sin errores del método boot
- ✅ Registro correcto del provider en la aplicación

#### Tests de Repositories (18 tests)
- ✅ **BeneficiosRepository** (6 tests): Constructor, manejo de errores HTTP, timeouts, formatos inválidos y respuestas exitosas
- ✅ **FichasRepository** (6 tests): Constructor, manejo de errores HTTP, timeouts, formatos inválidos y respuestas exitosas
- ✅ **FiltrosRepository** (6 tests): Constructor, manejo de errores HTTP, timeouts, formatos inválidos y respuestas exitosas

#### Tests Básicos (2 tests)
- ✅ Test unitario básico
- ✅ Test de aplicación básico

### Estadísticas de Testing

- **Total Tests**: 46
- **Total Aserciones**: 130
- **Cobertura**: 100%
- **Tiempo Promedio**: ~1.5 segundos
- **Líneas Cubiertas**: 173/173
- **Funciones Cubiertas**: 32/32
- **Clases Cubiertas**: 11/11

## 🔧 Herramientas de Calidad de Código

### Scripts Disponibles

```bash
# Testing
composer test              # Ejecutar tests
composer test-coverage     # Tests con cobertura HTML y texto

# Análisis de Código
composer analyse           # Análisis estático con PHPStan (nivel 5)
composer fix              # Corregir estilo con Laravel Pint
composer refactor         # Modernizar código con Rector

# Scripts Combinados
composer quality          # analyse + fix + test
composer ci               # analyse + test-coverage (para CI/CD)

# Badge de Cobertura
composer update-coverage-badge  # Actualizar badge en README
composer coverage-badge         # test-coverage + update-coverage-badge
```

### Herramientas Configuradas

#### **PHPStan (Análisis Estático)**
- **Nivel**: 5
- **Configuración**: `phpstan.neon`
- **Beneficios**: Detección de errores antes de runtime, verificación de tipos

#### **Laravel Pint (Estilo de Código)**
- **Estándar**: PSR-12 + Laravel
- **Configuración**: `pint.json`
- **Beneficios**: Código consistente, imports ordenados, formato automático

#### **Rector (Modernización)**
- **Target**: PHP 8.2
- **Configuración**: `rector.php`
- **Beneficios**: Actualización automática, mejoras de calidad, eliminación de código muerto

### Flujo de Trabajo Recomendado

```bash
# Durante desarrollo
composer fix && composer analyse && composer test

# Antes de commit
composer quality

# Para CI/CD
composer ci

# Actualizar badge de cobertura
composer coverage-badge
```

## 🔧 Documentación API

### Swagger/OpenAPI

El proyecto incluye documentación interactiva de la API usando **Swagger/OpenAPI 3.0**.

#### Generar Documentación
```bash
# Generar documentación Swagger
php artisan l5-swagger:generate
```

#### Acceder a la Documentación
Una vez que el servidor esté corriendo:

- **Interfaz Swagger**: `http://127.0.0.1:8000/api/documentation`
- **JSON API Docs**: `http://127.0.0.1:8000/docs/api-docs.json`

#### Características de la Documentación

- ✅ **Interfaz interactiva** para probar el endpoint
- ✅ **Ejemplos de respuesta** con datos reales
- ✅ **Esquemas detallados** de request/response
- ✅ **Descripciones completas** de cada campo
- ✅ **Códigos de error** y manejo de excepciones
- ✅ **Información del proyecto** y contacto

## 📮 Colección de Postman

El proyecto incluye una **colección completa de Postman** para facilitar las pruebas de la API.

### Archivos Incluidos

- **`Kuantaz_API_Collection.postman_collection.json`**: Colección principal con el endpoint
- **`Kuantaz_API_Environment.postman_environment.json`**: Variables de entorno para desarrollo local
- **`POSTMAN_GUIDE.md`**: Guía completa de uso

### Importar en Postman

1. **Importar Colección**:
   - Abre Postman
   - Haz clic en "Import"
   - Arrastra `Kuantaz_API_Collection.postman_collection.json`

2. **Importar Entorno**:
   - Ve a "Environments"
   - Haz clic en "Import"
   - Arrastra `Kuantaz_API_Environment.postman_environment.json`

3. **Activar Entorno**:
   - Selecciona "Kuantaz API - Local Development" en la esquina superior derecha

### Estructura de la Colección

#### 🎯 **Endpoint Principal**
- **Beneficios Procesados**: Endpoint principal con tests automatizados

#### 🧪 **Tests de Validación**
- **Test de Conectividad**: Verificación básica del servidor
- **Test de Performance**: Análisis de tiempos de respuesta

### Tests Automatizados

La colección incluye **tests automatizados** que verifican:

- ✅ **Status codes** correctos (200, 500)
- ✅ **Estructura JSON** apropiada
- ✅ **Tipos de datos** correctos
- ✅ **Lógica de negocio** (ordenamiento, cálculos)
- ✅ **Performance** (tiempos de respuesta)
- ✅ **Headers** apropiados

### Variables de Entorno

| Variable | Valor | Descripción |
|----------|-------|-------------|
| `base_url` | `http://127.0.0.1:8000` | URL base del servidor |
| `api_version` | `v1` | Versión de la API |
| `timeout` | `30000` | Timeout en milisegundos |

### Uso Rápido

```bash
# 1. Iniciar servidor Laravel
php artisan serve

# 2. En Postman, ejecutar "Test de Conectividad"
# 3. Ejecutar "Beneficios Procesados"
# 4. Revisar resultados en la consola de Postman
```

### Métricas de Performance

- **🚀 Excelente**: < 1 segundo
- **✅ Bueno**: 1-3 segundos  
- **⚠️ Aceptable**: 3-5 segundos
- **🐌 Lento**: > 5 segundos

Para más detalles, consulta **`POSTMAN_GUIDE.md`**.

## 📁 Estructura del Proyecto

```
prueba-tecnica-kuantaz/
├── app/
│   ├── DTOs/                               # Data Transfer Objects
│   │   ├── BeneficioDTO.php               # DTO para beneficios
│   │   ├── FichaDTO.php                   # DTO para fichas
│   │   └── FiltroDTO.php                  # DTO para filtros
│   ├── Http/
│   │   └── Controllers/
│   │       └── BeneficiosController.php    # Controlador principal
│   ├── Models/
│   │   └── User.php                        # Modelo de usuario
│   ├── Providers/
│   │   └── RepositoryServiceProvider.php   # Provider para repositories
│   ├── Repositories/                       # Capa de repositorios
│   │   ├── BeneficiosRepository.php       # Repository para beneficios
│   │   ├── FichasRepository.php           # Repository para fichas
│   │   └── FiltrosRepository.php          # Repository para filtros
│   └── Services/
│       └── BeneficiosService.php           # Lógica de negocio
├── routes/
│   ├── api.php                             # Rutas de la API
│   └── web.php                             # Rutas web básicas
├── tests/
│   ├── Feature/
│   │   ├── BeneficiosTest.php              # Tests de endpoint
│   │   ├── ExampleTest.php                 # Test básico de aplicación
│   │   ├── Providers/
│   │   │   └── RepositoryServiceProviderTest.php # Tests de providers
│   │   └── Repositories/                   # Tests de repositories
│   │       ├── BeneficiosRepositoryTest.php
│   │       ├── FichasRepositoryTest.php
│   │       └── FiltrosRepositoryTest.php
│   └── Unit/
│       ├── DTOs/
│       │   └── FiltroDTOTest.php           # Tests de DTOs
│       ├── ExampleTest.php                 # Test unitario básico
│       ├── Models/
│       │   └── UserTest.php                # Tests de modelos
│       └── Services/
│           └── BeneficiosServiceTest.php   # Tests de servicio
├── .github/
│   └── workflows/
│       └── ci.yml                          # GitHub Actions CI/CD
├── coverage-html/                          # Reportes de cobertura HTML
├── scripts/
│   └── update-coverage-badge.php           # Script para actualizar badge
├── .env                                    # Variables de entorno
├── .env.example                            # Ejemplo de configuración
└── README.md                               # Este archivo
```

### Componentes Principales

#### **Capa de DTOs**
- **BeneficioDTO**: Estructura de datos para beneficios
- **FichaDTO**: Estructura de datos para fichas
- **FiltroDTO**: Estructura de datos para filtros con validaciones

#### **Capa de Repositories**
- **BeneficiosRepository**: Manejo de datos de beneficios desde API externa
- **FichasRepository**: Manejo de datos de fichas desde API externa
- **FiltrosRepository**: Manejo de datos de filtros desde API externa

#### **BeneficiosController**
- Único controlador de la API
- Documentación Swagger completa
- Manejo de errores robusto

#### **BeneficiosService**
- Lógica de negocio separada
- Consumo de APIs externas a través de repositories
- Procesamiento con Laravel Collections

#### **RepositoryServiceProvider**
- Registro de bindings para inyección de dependencias
- Configuración de interfaces y implementaciones

## 🔧 Tecnologías Utilizadas

### Backend
- **Laravel 12.x**: Framework PHP
- **PHP 8.2+**: Lenguaje de programación
- **Guzzle HTTP**: Cliente HTTP para APIs externas
- **Laravel Collections**: Procesamiento eficiente de datos

### Arquitectura y Patrones
- **Repository Pattern**: Abstracción de la capa de datos
- **Data Transfer Objects (DTOs)**: Estructuras de datos tipadas
- **Service Layer**: Separación de lógica de negocio
- **Dependency Injection**: Inyección de dependencias con Service Container
- **Interface Segregation**: Interfaces específicas para cada repository

### Testing
- **PHPUnit**: Framework de testing
- **Laravel HTTP Tests**: Testing de endpoints
- **HTTP Fake**: Mocking de APIs externas
- **Code Coverage**: Análisis de cobertura con Xdebug

### Documentación
- **Swagger/OpenAPI**: Documentación de API
- **L5-Swagger**: Integración con Laravel

### Herramientas de Desarrollo
- **Composer**: Gestión de dependencias
- **Artisan**: CLI de Laravel
- **Git**: Control de versiones
- **GitHub Actions**: CI/CD

## 🚀 Características Técnicas

### Arquitectura Limpia

El proyecto implementa una **arquitectura limpia** con separación clara de responsabilidades:

#### **Capa de DTOs (Data Transfer Objects)**
```php
class FiltroDTO
{
    public function __construct(
        public readonly int $idPrograma,
        public readonly int $min,
        public readonly int $max
    ) {}

    public function isMontoValid(int $monto): bool
    {
        return $monto >= $this->min && $monto <= $this->max;
    }
}
```

#### **Repository Pattern**
```php
interface BeneficiosRepositoryInterface
{
    public function getAll(): Collection;
}

class BeneficiosRepository implements BeneficiosRepositoryInterface
{
    public function getAll(): Collection
    {
        // Lógica de obtención de datos desde API externa
        return collect($response['data'])->map(fn($item) => 
            BeneficioDTO::fromArray($item)
        );
    }
}
```

### Uso de Laravel Collections

El proyecto hace uso extensivo de Laravel Collections para procesamiento eficiente:

```php
$beneficiosFiltrados = $beneficiosCollection
    ->filter(function ($beneficio) use ($filtrosMap) {
        $filtro = $filtrosMap->get($beneficio->idPrograma);
        return $filtro?->isMontoValid($beneficio->monto) ?? false;
    })
    ->map(function ($beneficio) use ($filtrosMap, $fichasMap) {
        // Agregar información adicional con DTOs tipados
    })
    ->groupBy('ano')
    ->sortByDesc('ano');
```

### Inyección de Dependencias

```php
class BeneficiosService
{
    public function __construct(
        private readonly BeneficiosRepositoryInterface $beneficiosRepository,
        private readonly FiltrosRepositoryInterface $filtrosRepository,
        private readonly FichasRepositoryInterface $fichasRepository
    ) {}
}
```

### Manejo de Errores

- **Timeouts**: 30 segundos por request
- **Fallbacks**: Valores por defecto en variables de entorno
- **Validación**: Verificación de datos antes del procesamiento con DTOs
- **Logging**: Manejo de excepciones con contexto
- **Type Safety**: DTOs tipados para prevenir errores en runtime

### Optimizaciones

- **Mapas de Búsqueda**: `keyBy()` para acceso O(1)
- **Lazy Loading**: Procesamiento bajo demanda
- **Memory Efficient**: Uso de Collections en lugar de arrays grandes
- **HTTP Pooling**: Reutilización de conexiones HTTP
- **Type Hints**: Tipado estricto en toda la aplicación
- **Readonly Properties**: Inmutabilidad en DTOs

## 📝 Requisitos Cumplidos

### Requisitos de la Prueba Técnica

1. ✅ **Beneficios ordenados por años** (descendente)
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

**César Bugueno**  
Email: buguenocesar92@gmail.com  
Desarrollado para la prueba técnica de **Kuantaz**.

---

**¿Necesitas ayuda?** 

- Revisa la [documentación de Laravel](https://laravel.com/docs)
- Ejecuta `php artisan test` para verificar que todo funciona
- Consulta los logs en `storage/logs/laravel.log`
- Visita la documentación Swagger en `http://127.0.0.1:8000/api/documentation`
