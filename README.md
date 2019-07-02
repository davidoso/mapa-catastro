# Consultas dinámicas para el mapa digital de Catastro Colima
- [Mapa digital actual]
- [Propuesta de solución beta]: 1a versión. Ahora obsoleta
- [Propuesta de solución mejorada]: 2a versión. Para producción
*user*: catastro
*pwd*: catastrocolima2018
- Update: Antes de junio 2019, retiré la beta de Hostinger y Palma removió mi acceso a su fork


## Configuración de PHP requerida
```
php.ini en carpeta xampp/php:
max_execution_time=120
```


## Marcadores (iconos) para las capas del mapa
```
Descargar marcadores de: https://mapicons.mapsmarker.com
Nota 1: utilizar paleta de colores de otras categorías (checar marcadores que ya existen)
Nota 2: seleccionar el estilo glossy (3ro en la fila de izq. a der.)
que tiene contorno del mismo color que el interior pero más oscuro, no blanco o negro
```


## Antes de importar una nueva versión de la BD (catastro_full.sql) en Hostinger
```
1. Eliminar función tr
2. Eliminar vista v_campos_a_filtrar_por_capa
3. Importar el script SQL y crear manualmente la función y la vista de:
    https://github.com/fbuccioni/mysql-routines-collection/blob/master/tr.func.sql
    CREATE VIEW AS.. se encuentra en el archivo MySQL Functions for Catastro.txt
```


## ¿Cómo cambiar o agregar consultas de búsqueda (con o sin filtros) Un filtro requiere capa / campo / valor
### Paso 1/4
#### Agregrar registros en 3 tablas en MariaDB
- **ctrl_select_capas**: para llenar etiquetas *optgroup* (equivale a carpeta, e.g. VIALIDAD) y múltiples *option* (equivale a capa, e.g. TOPES) del 1er *select* con id=`cbCapas`. El marcador es el nombre de la imagen de la capa localizada en [images/mapMarkers](images/mapMarkers) y se genera automáticamente con un trigger. **El nombre del marcador y la imagen deben coincidir**
##### Campos a ingresar manualmente: carpeta, capa, nombre_tabla
- **ctrl_campos_a_filtrar**: para llenar múltiples *option* (equivale a campo, e.g. COLOR) del 2do *select* con id=`cbCampos` con los campos a filtrar por capa
##### Campos a ingresar manualmente: campo_frontend
- **ctrl_nombre_columnas**: para obtener el nombre de las columnas en la BD (cuando se pulse el botón CONSULTAR con id=`btnQuery` o la etiqueta homónima en el menú ACCIONES del sidebar). Algunas columnas comunes ya están, e.g. tipo, material, cond_fisica o empresa, por lo que no se deben agregar de nuevo
##### Campos a ingresar manualmente: columna_frontend, columna_bd
#### Nota 1 del paso 1
campo_frontend y columna_frontend **deben llamarse igual si hacen referencia al mismo campo** del 2do *select* con id=`cbCampos`, e.g. CONDICIÓN FÍSICA
#### Nota 2 del paso 1
Comprobar el nombre con el que aparecerán los campos por capa en `cbCampos` ejecutando la vista en la BD **v_campos_a_filtrar_por_capa**


### Paso 2/4
#### De ser necesario, concatenar con OR otra condición a la sentencia if(campo == "NOMBRE" || campo == ["OTRO CAMPO"]) en la función switchSelectCampo() del archivo [filter.js](js/filter/filter.js)
- Este if es necesario para agregar un input de texto para campos que **NO** tienen una serie de valores predefinidos y que deben ser ingresados manualmente en lugar de un selectbox, e.g. nombre de un banco u hotel
- El case es el nombre de la capa en el frontend (checar ctrl_select_capas en la BD)
- **NO APLICA** en los campos con valores predefinidos, e.g. material, cond_fisica o empresa, que llaman la función getValores() por ajax del modelo Sidebar_m.php para llenar el 3er *select* con id=`cbValores`


### Paso 3/4
#### Modificar el switch de las siguientes funciones del modelo [Sidebar_m.php](application/models/Sidebar_m.php)
#### Importante: no editar las otras funciones del modelo
- **Función getValores()**: los case corresponden a los nombres de las columnas en el frontend (checar ctrl_nombre_columnas en la BD). La función transforma y envía los campos de la BD al frontend
- **Función valueExceptions($capa, $campo, $nombre_tabla)**: para campos que no se puedan obtener con una simple consulta DISTINCT(campo) y requieran un alias para un valor nulo, un orden diferente a *order_by(1)* u otro caso especial de mostrarse. La función transforma y envía los campos de la BD al frontend
#### Nota del paso 3
**NO APLICA** si todos los campos a filtrar de la capa fueron ingresados en el archivo filter.js, es decir, este paso es necesario cuando hay al menos un campo que tenga valores predefinidos y requiera el 3er *select* con id=`cbValores`


### Paso 4/4
#### Modificar el switch de las siguientes funciones del modelo [Map_m.php](application/models/Map_m.php)
#### Importante: no editar las otras funciones del modelo
- **Función switchColumn($column, $value)**: los case corresponden a los nombres de las columnas en el frontend (checar ctrl_nombre_columnas en la BD). La función transforma los campos del frontend a la BD
- **Función getJoinCondition($baseTable)**: los case corresponden al nombre de la capa en el frontend. Los valores de los campos a filtrar de estas capas no se encuentran en su tabla base ni en un catálogo *ct_...* y por ende requieren un JOIN con otra tabla, e.g. GIROS COMERCIALES, LOCATARIOS MERCADOS o TIANGUISTAS
- **Funciones switchTableSelectedMarker($marcador)**
- **y switchColumnSelectedMarker($marcador)**: los case corresponden al nombre de la imagen del [marcador](images/mapMarkers) de la capa (checar ctrl_select_capas en la BD). Las funciones transforman los campos de la BD al frontend


[Mapa digital actual]: <http://www.catastrocolima.gob.mx/cartografia.html>
[Propuesta de solución beta]: <http://ateneorid.com/osint-beta>
[Propuesta de solución mejorada]: <http://ateneorid.com/osint>