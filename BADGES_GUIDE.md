# 🏷️ Guía de Badges para GitHub

## 📋 Resumen

Tu proyecto ahora tiene un sistema completo de badges que muestran el estado de calidad del código en GitHub:

- ✅ **Badge de CI/CD** - Estado de GitHub Actions
- ✅ **Badge de Cobertura** - Porcentaje de cobertura de tests (88%)
- ✅ **Badge de PHPStan** - Nivel de análisis estático (5)
- ✅ **Badge de Laravel Pint** - Estado del formateador de código
- ✅ **Badge de Tests** - Número de tests pasando (16)
- ✅ **Badge de PHP** - Versión de PHP requerida (^8.2)
- ✅ **Badge de Laravel** - Versión del framework (12.x)

## 🎯 Badges Actuales en README.md

```markdown
[![CI](https://github.com/TU_USUARIO/prueba-tecnica-kuantaz/workflows/CI/badge.svg)](https://github.com/TU_USUARIO/prueba-tecnica-kuantaz/actions)
[![Coverage](https://img.shields.io/badge/coverage-88%25-brightgreen)](https://github.com/TU_USUARIO/prueba-tecnica-kuantaz)
[![PHPStan](https://img.shields.io/badge/PHPStan-level%205-brightgreen)](https://phpstan.org/)
[![Laravel Pint](https://img.shields.io/badge/Laravel%20Pint-passing-brightgreen)](https://laravel.com/docs/pint)
[![Tests](https://img.shields.io/badge/tests-16%20passing-brightgreen)](https://github.com/TU_USUARIO/prueba-tecnica-kuantaz)
[![PHP Version](https://img.shields.io/badge/php-%5E8.2-blue)](https://php.net)
[![Laravel](https://img.shields.io/badge/laravel-12.x-red)](https://laravel.com)
```

## 🔄 Actualización Automática de Cobertura

### **Script Automatizado**

Hemos creado un script que actualiza automáticamente el badge de cobertura:

```bash
# Ejecutar tests y actualizar badge automáticamente
composer coverage-badge

# O paso a paso:
composer test-coverage          # Generar reporte de cobertura
composer update-coverage-badge  # Actualizar badge en README
```

### **¿Cómo Funciona?**

1. **Extrae** el porcentaje de cobertura del archivo `coverage.txt`
2. **Calcula** el color apropiado según el porcentaje:
   - 🔴 **Rojo**: < 40%
   - 🟠 **Naranja**: 40-59%
   - 🟡 **Amarillo**: 60-79%
   - 🟢 **Verde**: ≥ 80%
3. **Actualiza** automáticamente el badge en `README.md`

### **Uso Manual**

```bash
# Ejecutar el script directamente
php scripts/update-coverage-badge.php
```

**Salida esperada:**
```
🔍 Extrayendo porcentaje de cobertura...
📊 Cobertura encontrada: 88.02%
📝 Actualizando badge en README.md...
✅ Badge de cobertura actualizado exitosamente a 88.02%
🎯 Recuerda hacer commit de los cambios en README.md
```

## 🚀 Configuración de GitHub Actions

### **Workflow de CI/CD**

El archivo `.github/workflows/ci.yml` ejecuta automáticamente:

1. **Setup de PHP 8.2** con Xdebug
2. **Instalación de dependencias** con Composer
3. **Análisis estático** con PHPStan
4. **Formateo de código** con Laravel Pint
5. **Tests con cobertura** completa
6. **Upload a Codecov** (opcional)

### **Badge de CI Dinámico**

Una vez que subas el código a GitHub, el badge de CI se actualizará automáticamente:

```markdown
[![CI](https://github.com/TU_USUARIO/prueba-tecnica-kuantaz/workflows/CI/badge.svg)](https://github.com/TU_USUARIO/prueba-tecnica-kuantaz/actions)
```

**Estados posibles:**
- 🟢 **passing** - Todos los tests pasan
- 🔴 **failing** - Hay tests fallando
- 🟡 **pending** - Workflow en ejecución

## 📊 Badges Dinámicos vs Estáticos

### **Badges Dinámicos** (Se actualizan automáticamente)
- ✅ **CI Badge** - GitHub Actions
- ✅ **Coverage Badge** - Script automatizado

### **Badges Estáticos** (Actualización manual)
- 📌 **PHPStan Level** - Cambiar cuando subas el nivel
- 📌 **Tests Count** - Actualizar cuando agregues tests
- 📌 **PHP/Laravel Version** - Actualizar al cambiar versiones

## 🔧 Personalización de Badges

### **Cambiar Colores**

Puedes personalizar los colores de los badges:

```markdown
<!-- Colores disponibles -->
brightgreen, green, yellowgreen, yellow, orange, red, lightgrey, blue

<!-- Ejemplo -->
[![Custom](https://img.shields.io/badge/custom-badge-blue)](https://example.com)
```

### **Agregar Nuevos Badges**

```markdown
<!-- Badge de licencia -->
[![License](https://img.shields.io/badge/license-MIT-blue)](LICENSE)

<!-- Badge de versión -->
[![Version](https://img.shields.io/badge/version-1.0.0-green)](https://github.com/TU_USUARIO/prueba-tecnica-kuantaz/releases)

<!-- Badge de issues -->
[![Issues](https://img.shields.io/github/issues/TU_USUARIO/prueba-tecnica-kuantaz)](https://github.com/TU_USUARIO/prueba-tecnica-kuantaz/issues)
```

## 🎯 Flujo de Trabajo Recomendado

### **Durante Desarrollo**

```bash
# 1. Hacer cambios en el código
# 2. Ejecutar calidad de código
composer quality

# 3. Actualizar badge de cobertura
composer coverage-badge

# 4. Commit y push
git add .
git commit -m "feat: nueva funcionalidad con tests"
git push origin main
```

### **Antes de Release**

```bash
# 1. Verificar que todos los badges estén actualizados
composer coverage-badge

# 2. Actualizar badges estáticos si es necesario
# - Número de tests en README.md
# - Nivel de PHPStan si cambió
# - Versiones de PHP/Laravel si cambiaron

# 3. Commit final
git add README.md
git commit -m "docs: actualizar badges para release"
```

## 📝 Mantenimiento de Badges

### **Actualizar Badge de Tests**

Cuando agregues nuevos tests:

```bash
# 1. Contar tests actuales
php artisan test --list | wc -l

# 2. Actualizar manualmente en README.md
[![Tests](https://img.shields.io/badge/tests-XX%20passing-brightgreen)](https://github.com/TU_USUARIO/prueba-tecnica-kuantaz)
```

### **Actualizar Badge de PHPStan**

Cuando subas el nivel de PHPStan:

```bash
# 1. Editar phpstan.neon
level: 6  # o el nivel que quieras

# 2. Verificar que pase
composer analyse

# 3. Actualizar badge en README.md
[![PHPStan](https://img.shields.io/badge/PHPStan-level%206-brightgreen)](https://phpstan.org/)
```

## 🔗 Servicios Externos para Badges

### **Codecov (Cobertura Dinámica)**

Si quieres cobertura dinámica real:

1. **Registrarte** en [codecov.io](https://codecov.io)
2. **Conectar** tu repositorio de GitHub
3. **Usar badge dinámico**:
   ```markdown
   [![codecov](https://codecov.io/gh/TU_USUARIO/prueba-tecnica-kuantaz/branch/main/graph/badge.svg)](https://codecov.io/gh/TU_USUARIO/prueba-tecnica-kuantaz)
   ```

### **Shields.io (Badges Personalizados)**

Para badges más avanzados:

```markdown
<!-- Badge dinámico de GitHub -->
[![GitHub issues](https://img.shields.io/github/issues/TU_USUARIO/prueba-tecnica-kuantaz)](https://github.com/TU_USUARIO/prueba-tecnica-kuantaz/issues)

<!-- Badge de último commit -->
[![GitHub last commit](https://img.shields.io/github/last-commit/TU_USUARIO/prueba-tecnica-kuantaz)](https://github.com/TU_USUARIO/prueba-tecnica-kuantaz/commits/main)

<!-- Badge de tamaño del repo -->
[![GitHub repo size](https://img.shields.io/github/repo-size/TU_USUARIO/prueba-tecnica-kuantaz)](https://github.com/TU_USUARIO/prueba-tecnica-kuantaz)
```

## 🎉 Resultado Final

Con este sistema tendrás un README profesional que muestra:

- ✅ **Estado de CI/CD** en tiempo real
- ✅ **Cobertura de código** actualizada automáticamente
- ✅ **Calidad de código** con métricas claras
- ✅ **Información técnica** (versiones, tests, etc.)
- ✅ **Enlaces útiles** a documentación y herramientas

## 🚀 Próximos Pasos

1. **Reemplazar** `TU_USUARIO` con tu usuario real de GitHub
2. **Subir** el código a GitHub para activar el CI badge
3. **Configurar** Codecov si quieres cobertura dinámica
4. **Personalizar** badges adicionales según tus necesidades

¡Tu proyecto ahora tiene un aspecto profesional con badges que se mantienen actualizados automáticamente! 🎯 