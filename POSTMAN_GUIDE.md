# 📮 Guía de Postman - API de Beneficios Kuantaz

Esta guía te ayudará a configurar y usar la colección de Postman para probar la API de Beneficios de Kuantaz.

## 📁 Archivos Incluidos

- **`Kuantaz_API_Collection.postman_collection.json`**: Colección principal con todos los endpoints
- **`Kuantaz_API_Environment.postman_environment.json`**: Variables de entorno para desarrollo local
- **`POSTMAN_GUIDE.md`**: Esta guía de uso

## 🚀 Instalación y Configuración

### **Paso 1: Importar la Colección**

1. Abre **Postman**
2. Haz clic en **"Import"** (esquina superior izquierda)
3. Arrastra el archivo `Kuantaz_API_Collection.postman_collection.json` o haz clic en **"Upload Files"**
4. Confirma la importación

### **Paso 2: Importar el Entorno**

1. En Postman, ve a **"Environments"** (icono de engranaje)
2. Haz clic en **"Import"**
3. Arrastra el archivo `Kuantaz_API_Environment.postman_environment.json`
4. Confirma la importación

### **Paso 3: Activar el Entorno**

1. En la esquina superior derecha, selecciona **"Kuantaz API - Local Development"**
2. Verifica que aparezca como activo

## 📋 Estructura de la Colección

### 🎯 **Endpoints Principales**
- **Beneficios Procesados**: Endpoint principal que procesa y agrupa beneficios por año

### 🧪 **Tests de Validación**
- **Test de Conectividad**: Verificación básica del servidor
- **Test de Performance**: Análisis de tiempos de respuesta

## 🔧 Variables de Entorno

| Variable | Valor | Descripción |
|----------|-------|-------------|
| `base_url` | `http://127.0.0.1:8000` | URL base del servidor Laravel |
| `api_version` | `v1` | Versión de la API |
| `timeout` | `30000` | Timeout en milisegundos |
| `content_type` | `application/json` | Tipo de contenido |
| `accept_header` | `application/json` | Header Accept |

## 🧪 Tests de Validación - Explicación Detallada

### **1. Test de Conectividad**

Este test verifica que el servidor esté funcionando correctamente y responda adecuadamente:

#### **¿Qué hace?**
```javascript
// Verifica que el servidor responda sin errores
pm.test("Server is responding", function () {
    pm.response.to.not.be.error;
});

// Verifica que el tiempo de respuesta sea aceptable (< 5 segundos)
pm.test("Response time is acceptable", function () {
    pm.expect(pm.response.responseTime).to.be.below(5000);
});

// Verifica que tenga headers apropiados
pm.test("Has proper headers", function () {
    pm.response.to.have.header('Content-Type');
});
```

#### **¿Para qué sirve?**
- ✅ **Diagnóstico rápido**: Confirma que el servidor Laravel está ejecutándose
- ✅ **Detección de problemas**: Identifica si hay problemas de conectividad
- ✅ **Validación básica**: Verifica que el endpoint responde correctamente
- ✅ **Monitoreo de latencia**: Asegura que el tiempo de respuesta sea aceptable

#### **¿Cuándo usarlo?**
- Antes de hacer pruebas más complejas
- Para diagnosticar problemas de conectividad
- Como "health check" del servidor
- Para verificar que el entorno está configurado correctamente

### **2. Test de Performance**

Este test analiza el rendimiento del endpoint y proporciona métricas detalladas:

#### **¿Qué hace?**
```javascript
// Verifica que responda en menos de 10 segundos (límite máximo)
pm.test("Response time is under 10 seconds", function () {
    pm.expect(pm.response.responseTime).to.be.below(10000);
});

// Verifica que responda en menos de 5 segundos (óptimo)
pm.test("Response time is under 5 seconds (optimal)", function () {
    pm.expect(pm.response.responseTime).to.be.below(5000);
});

// Clasifica el rendimiento y lo muestra en consola
const responseTime = pm.response.responseTime;
let performance = '';
if (responseTime < 1000) performance = '🚀 Excelente';
else if (responseTime < 3000) performance = '✅ Bueno';
else if (responseTime < 5000) performance = '⚠️ Aceptable';
else performance = '🐌 Lento';

// Verifica que el tamaño de respuesta sea razonable (< 1MB)
pm.test("Response size is reasonable", function () {
    pm.expect(responseSize).to.be.below(1000000);
});
```

#### **¿Para qué sirve?**
- 📊 **Análisis de rendimiento**: Mide tiempos de respuesta reales
- 🎯 **Clasificación automática**: Categoriza el rendimiento (Excelente/Bueno/Aceptable/Lento)
- 📦 **Control de tamaño**: Verifica que las respuestas no sean excesivamente grandes
- 📈 **Monitoreo continuo**: Permite detectar degradación de performance

#### **¿Cuándo usarlo?**
- Para evaluar el rendimiento del endpoint con datos reales
- Antes de desplegar a producción
- Para detectar problemas de performance
- Para comparar rendimiento entre diferentes versiones

#### **Métricas que proporciona:**
- **🚀 Excelente**: < 1 segundo (ideal para APIs)
- **✅ Bueno**: 1-3 segundos (aceptable para la mayoría de casos)
- **⚠️ Aceptable**: 3-5 segundos (puede necesitar optimización)
- **🐌 Lento**: > 5 segundos (requiere investigación)

### **¿Por qué son importantes estos tests?**

1. **Detección temprana de problemas**: Identifican issues antes de que afecten a usuarios
2. **Validación de infraestructura**: Confirman que el entorno está configurado correctamente
3. **Monitoreo de SLA**: Aseguran que se cumplan los acuerdos de nivel de servicio
4. **Debugging facilitado**: Proporcionan información valiosa para troubleshooting
5. **Documentación automática**: Generan logs detallados del comportamiento del sistema

## 📈 Uso Recomendado

### **1. Verificación Inicial**
```
🧪 Tests de Validación
├── Test de Conectividad
└── Test de Performance
```

### **2. Prueba del Endpoint Principal**
```
🎯 Endpoints Principales
└── Beneficios Procesados
```

## 📊 Interpretación de Resultados

### **✅ Tests Exitosos**
- **Verde**: Todos los tests pasaron
- **Consola**: Información detallada del procesamiento
- **Response**: Datos estructurados correctamente

### **❌ Tests Fallidos**
- **Rojo**: Algún test falló
- **Test Results**: Detalles específicos del fallo
- **Console**: Logs de debugging

### **📈 Métricas de Performance**
- **🚀 Excelente**: < 1 segundo
- **✅ Bueno**: 1-3 segundos
- **⚠️ Aceptable**: 3-5 segundos
- **🐌 Lento**: > 5 segundos

## 🔍 Debugging y Troubleshooting

### **Problema: Error de Conexión**
```
❌ Error: connect ECONNREFUSED 127.0.0.1:8000
```
**Solución**: Verificar que el servidor Laravel esté ejecutándose:
```bash
php artisan serve --host=127.0.0.1 --port=8000
```

### **Problema: Timeout**
```
❌ Error: Request timeout
```
**Solución**: 
1. Verificar conectividad a APIs externas
2. Aumentar timeout en variables de entorno
3. Revisar logs del servidor

### **Problema: Tests Fallando**
```
❌ AssertionError: expected 500 to equal 200
```
**Solución**:
1. Revisar logs de Laravel: `storage/logs/laravel.log`
2. Verificar configuración de variables de entorno
3. Comprobar conectividad a APIs externas

## 🎨 Personalización

### **Cambiar URL Base**
1. Ve a **Environments** → **Kuantaz API - Local Development**
2. Modifica `base_url` (ej: `https://api.kuantaz.com`)
3. Guarda los cambios

### **Agregar Headers Personalizados**
1. Selecciona cualquier request
2. Ve a la pestaña **Headers**
3. Agrega headers adicionales (ej: `Authorization`, `X-API-Key`)

### **Modificar Tests**
1. Selecciona un request
2. Ve a la pestaña **Tests**
3. Modifica el código JavaScript según tus necesidades

## 📝 Ejemplos de Uso

### **Ejemplo 1: Prueba Rápida**
1. Selecciona **"Test de Conectividad"**
2. Haz clic en **"Send"**
3. Verifica que todos los tests pasen ✅

### **Ejemplo 2: Análisis Completo**
1. Ejecuta **"Beneficios Procesados"**
2. Revisa la consola para ver el resumen:
   ```
   📊 Resumen de Beneficios Procesados:
   📅 Años procesados: 1
      2023: 8 beneficios, $295,608
   ```

### **Ejemplo 3: Ejecución en Lote**
1. Selecciona la colección **"API de Beneficios - Kuantaz"**
2. Haz clic en **"Run"**
3. Configura los parámetros de ejecución
4. Ejecuta todos los tests automáticamente

## 📋 Estructura de Respuesta del Endpoint Principal

El endpoint `/api/v1/beneficios-procesados` devuelve una respuesta con la siguiente estructura:

```json
{
  "code": 200,
  "success": true,
  "data": [
    {
      "year": 2023,
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
}
```

### **Campos de la Respuesta:**

- **`code`**: Código de estado HTTP
- **`success`**: Indica si la operación fue exitosa
- **`data`**: Array con los datos agrupados por año
  - **`year`**: Año de los beneficios
  - **`total_monto`**: Suma total de montos para ese año
  - **`num`**: Número de beneficios para ese año
  - **`beneficios`**: Array con los beneficios individuales
    - **`ficha`**: Información detallada de la ficha del programa

## 🔗 Enlaces Útiles

- **Endpoint Principal**: `http://127.0.0.1:8000/api/v1/beneficios-procesados`
- **Repositorio del Proyecto**: [GitHub](https://github.com/tu-usuario/prueba-tecnica-kuantaz)
- **Documentación de Postman**: [Postman Learning Center](https://learning.postman.com/)

## 🆘 Soporte

Si encuentras problemas:

1. **Revisa esta guía** completa
2. **Verifica la configuración** del entorno
3. **Consulta los logs** de Laravel
4. **Ejecuta los tests unitarios** del proyecto
5. **Contacta al equipo** de desarrollo

---

**¡Feliz testing! 🚀** 