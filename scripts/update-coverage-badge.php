<?php

/**
 * Script para actualizar automáticamente el badge de cobertura en README.md
 * 
 * Uso: php scripts/update-coverage-badge.php
 */

function extractCoverageFromReport(): ?float
{
    $coverageFile = __DIR__ . '/../coverage.txt';
    
    if (!file_exists($coverageFile)) {
        echo "❌ Archivo coverage.txt no encontrado. Ejecuta 'composer test-coverage' primero.\n";
        return null;
    }
    
    $content = file_get_contents($coverageFile);
    
    // Buscar el patrón: Lines:   XX.XX% (XXX/XXX)
    if (preg_match('/Lines:\s+(\d+\.\d+)%/', $content, $matches)) {
        return (float) $matches[1];
    }
    
    echo "❌ No se pudo extraer el porcentaje de cobertura del reporte.\n";
    return null;
}

function updateReadmeBadge(float $coverage): bool
{
    $readmeFile = __DIR__ . '/../README.md';
    
    if (!file_exists($readmeFile)) {
        echo "❌ Archivo README.md no encontrado.\n";
        return false;
    }
    
    $content = file_get_contents($readmeFile);
    $coverageInt = (int) round($coverage);
    
    // Determinar color del badge según el porcentaje
    $color = 'red';
    if ($coverage >= 80) $color = 'brightgreen';
    elseif ($coverage >= 60) $color = 'yellow';
    elseif ($coverage >= 40) $color = 'orange';
    
    // Verificar si ya está actualizado
    $currentPattern = "coverage-{$coverageInt}%25-{$color}";
    if (strpos($content, $currentPattern) !== false) {
        echo "✅ Badge ya está actualizado con {$coverage}% ({$color})\n";
        return true;
    }
    
    // Buscar y reemplazar usando regex simple
    $pattern = '/coverage-(\d+)%25-(\w+)/';
    $replacement = "coverage-{$coverageInt}%25-{$color}";
    
    $newContent = preg_replace($pattern, $replacement, $content);
    
    if ($newContent === $content) {
        echo "⚠️  No se encontró el badge de cobertura en README.md para actualizar.\n";
        return false;
    }
    
    file_put_contents($readmeFile, $newContent);
    return true;
}

function main(): void
{
    echo "🔍 Extrayendo porcentaje de cobertura...\n";
    
    $coverage = extractCoverageFromReport();
    if ($coverage === null) {
        exit(1);
    }
    
    echo "📊 Cobertura encontrada: {$coverage}%\n";
    
    echo "📝 Actualizando badge en README.md...\n";
    
    if (updateReadmeBadge($coverage)) {
        echo "✅ Badge de cobertura actualizado exitosamente a {$coverage}%\n";
        echo "🎯 Recuerda hacer commit de los cambios en README.md\n";
    } else {
        echo "❌ Error al actualizar el badge de cobertura\n";
        exit(1);
    }
}

// Ejecutar solo si se llama directamente
if (basename(__FILE__) === basename($_SERVER['SCRIPT_NAME'])) {
    main();
} 