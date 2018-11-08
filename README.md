# Filtros de búsqueda dinámicos para el mapa digital de Catastro Colima

- [Mapa digital actual]
- [Propuesta de solución]

## Configuración de XAMPP requerida

```
php.ini en carpeta xampp/php:
max_execution_time=90
```

## ¿Cómo cambiar o agregar filtros?

### Paso 1
#### Modificar 3 tablas en MySQL para agregar los filtros en la vista filter.php

- **ctrl_select_capas**: para llenar etiquetas *optgroup* (equivale a carpeta, e.g. VIALIDAD) y *option* (equivale a capa, e.g. TOPES) del 1er *select* con id=`cbCapas`
- **ctrl_campos_a_filtrar**: para llenar etiquetas *option* (equivale a campo, e.g. COLOR) del 2do *select* con id=`cbCampos` con los campos a filtrar por capa
- **ctrl_nombre_columnas**: para obtener el nombre de las columnas en la BD (cuando se pulse el botón *CONSULTAR* con id=`btnQuery` y se llama por ajax el archivo **mapFilterSwitchColumn.php** dependiendo los campos a filtrar especificados en **ctrl_campos_a_filtrar**
#### NOTA 1
Comprobar el nombre con el que aparecerán los campos por capa en el 2do *select* con id=`cbCampos` ejecutando la vista en la BD **v_campos_a_filtrar_por_capa**. El nombre exacto debe escribirse en los case de la **función switchSelect2()** en la vista *filter.php*

### Paso 2
#### Modificar switch en las funciones de los siguientes 3 códigos php:
- **Función switchSelect2()** en la vista *filter.php* para agregar campos/valores en el frontend
- **Función switchColumn()** en [mapFilterSwitchColumn.php](sqlqueries/mapFilterSwitchColumn.php)
- **Funciones switchTableMarker() y switchColumnMarker()** en [mapSelectedMarkerSwitchs.php](sqlqueries/mapSelectedMarkerSwitchs.php)
#### NOTA 2
Los case en *mapSelectedMarkerSwitchs.php* son **IGUALES** que los nombres del marcador de la capa localizados en la carpeta *images/mapMarkers*

### Paso 3
#### Modificar el modelo map_m.php y el controlador map_c.php
- *Modelo*: sirve para llenar el 3er *select* con id=`cbFiltros` (se encuentra en *macro_valor_select.php*) de valores según el campo seleccionado en el 2do *select* con id=`cbCampos`
- *Controlador*: obtiene los valores del modelo para llenar los 3 *select*. Ver ejemplos comentados con "Llenar selects de la capa .."
#### NOTA 3
Este paso sólo es necesario si el campo tiene una serie de valores predefinidos, e.g. material, cond_fisica o empresa

[Mapa digital actual]: <http://www.catastrocolima.gob.mx/cartografia.html>
[Propuesta de solución]: <http://osint.ateneoitc.com>