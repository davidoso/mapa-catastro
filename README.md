# Consultas dinámicas para el mapa digital de Catastro Colima
- [Mapa digital actual]
- [Propuesta de solución beta]: 1a versión. Ahora obsoleta
- [Propuesta de solución mejorada]: 2a versión. Para producción
*user*: catastro
*pwd*: catastrocolima2018


## Configuración de PHP requerida
```
php.ini en carpeta xampp/php:
max_execution_time=120
```


## Marcadores
```
Descargar marcadores de: https://mapicons.mapsmarker.com
Nota 1: utilizar paleta de colores de otras categorías
Nota 2: conservar mismo estilo glossy (3ro en la fila de izq. a der.)
con contorno del mismo color que el interior pero más oscuro, no blanco o negro
```


## ¿Cómo cambiar o agregar consultas de búsqueda (con o sin filtros) Un filtro requiere capa / campo / valor
### Paso 1
#### Agregrar registros en 3 tablas en MariaDB
- **ctrl_select_capas**: para llenar etiquetas *optgroup* (equivale a carpeta, e.g. VIALIDAD) y múltiples *option* (equivale a capa, e.g. TOPES) del 1er *select* con id=`cbCapas`. El marcador es el nombre de la imagen de la capa localizada en [images/mapMarkers](images/mapMarkers) y se genera automáticamente con un trigger. **El nombre del marcador y la imagen deben coincidir**
##### Campos a agregar en esta tabla: carpeta, capa, nombre_tabla
- **ctrl_campos_a_filtrar**: para llenar múltiples *option* (equivale a campo, e.g. COLOR) del 2do *select* con id=`cbCampos` con los campos a filtrar por capa
##### Campos a agregar en esta tabla: campo_frontend
- **ctrl_nombre_columnas**: para obtener el nombre de las columnas en la BD (cuando se pulse el botón CONSULTAR con id=`btnQuery` o la etiqueta homónima en el menú ACCIONES del sidebar). Algunas columnas comunes ya están, e.g. tipo, material, cond_fisica o empresa, por lo que no se deben agregar de nuevo
##### Campos a agregar en esta tabla: columna_frontend, columna_bd
#### Nota 1 del paso 1
campo_frontend y columna_frontend deben ser iguales si hacen referencia al mismo campo del 2do *select* con id=`cbCampos`, e.g. CONDICIÓN FÍSICA
#### Nota 2 del paso 1
Comprobar el nombre con el que aparecerán los campos por capa en el 2do *select* ejecutando la vista en la BD **v_campos_a_filtrar_por_capa**


### Paso 2
#### De ser necesario, concatenar con OR otra condición a la sentencia if(campo == "NOMBRE" || campo == ["OTRO CAMPO"]) en la función switchSelectCampo() del archivo [filter.js](js/filter/filter.js)
- Este if es necesario para agregar un input de texto para campos que **NO** tienen una serie de valores predefinidos y que deben ser ingresados manualmente en lugar de un selectbox, e.g. nombre de un banco u hotel
- El case es el nombre de la capa en el eend (checar ctrl_select_capas en la BD)
- **NO APLICA** en los campos con valores predefinidos, e.g. material, cond_fisica o empresa, que llaman la función getValores() por ajax del modelo Sidebar_m.php para llenar el 3er *select* con id=`cbValores`


### Paso 3
#### Modificar el switch de las siguientes funciones del modelo [Sidebar_m.php](application/models/Sidebar_m.php)
- **Función getValores()**: los case corresponden a los nombres de las columnas en el frontend (checar ctrl_nombre_columnas en la BD). Transforma y envía los campos de la BD al frontend
- **Función valueExceptions($capa, $campo, $nombre_tabla)**: campos que no se pueden obtener con una simple consulta DISTINCT(campo). Transforma y envía los campos de la BD al frontend
#### Nota del paso 3
**NO APLICA** si todos los campos a filtrar de la capa fueron modificados en el archivo filter.js, es decir, este paso es necesario cuando hay al menos un campo que sea predefinido y requiera el 3er *select* con id=`cbValores`
#### Importante: no editar las otras funciones del modelo


### Paso 4
#### Modificar el switch de las siguientes funciones del modelo [Map_m.php](application/models/Map_m.php)
- **Función switchColumn($column, $value)**: los case corresponden a los nombres de las columnas en el frontend (checar ctrl_nombre_columnas en la BD). Transforma los campos del frontend a la BD
- **Funciones switchTableSelectedMarker($marcador)**
- **y switchColumnSelectedMarker($marcador)**: los case corresponden al nombre de la imagen del [marcador](images/mapMarkers) de la capa (checar ctrl_select_capas en la BD). Transforman los campos de la BD al frontend
#### Importante: no editar las otras funciones del modelo a excepción de getMapTotals() y getMapMarkers() si los campos a filtrar de una capa requieren un JOIN con otra tabla que no sea catálogo, e.g. GIROS COMERCIALES


[Mapa digital actual]: <http://www.catastrocolima.gob.mx/cartografia.html>
[Propuesta de solución beta]: <http://ateneorid.com/osint-beta>
[Propuesta de solución mejorada]: <http://ateneorid.com/osint>