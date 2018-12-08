<div class="modal fade" id="modal-help" tabindex="-1" role="dialog" aria-labelledby="help-title" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
        	<div class="modal-header">
                <h5 class="modal-title" id="help-title"><i class="fas fa-question-circle" aria-hidden="true"></i>&nbsp;&nbsp;Preguntas frecuentes</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
            </div> <!-- modal-header ends -->
            <div class="modal-body">

				<h4 class="text-center text-hot-pink">TABLA DE BÚSQUEDA</h4>
				<div class="accordion" id="accordion-help" role="tablist">
					<div class="card accordion-inverse-info">
						<div class="card-header" role="tab" id="pregunta-1-title">
							<h6 class="mb-0"><a class="collapsed" data-toggle="collapse" href="#pregunta-1" aria-expanded="false" aria-controls="pregunta-1">¿Cómo realizo una consulta en el mapa digital de Catastro Colima?</a></h6>
						</div>
						<div id="pregunta-1" class="collapse" role="tabpanel" aria-labelledby="pregunta-1-title" data-parent="#accordion-help">
							<div class="card-body">Para realizar una <strong class="text-danger">consulta de los datos abiertos</strong> que la Dirección General de Catastro Municipal de Colima ofrece en el municipio de Colima y parte de Villa de Álvarez, es necesario:
								<ul id="ul-pregunta-1">
									<li>Agregar una o más capas con o sin filtros de búsqueda a la tabla de búsqueda.</li>
									<li>Trazar un área de influencia en el mapa.</li>
								</ul>
							</div>
						</div>
					</div> <!-- accordion-inverse-info pregunta-1 -->
					<div class="card accordion-inverse-info">
						<div class="card-header" role="tab" id="pregunta-2-title">
							<h6 class="mb-0"><a class="collapsed" data-toggle="collapse" href="#pregunta-2" aria-expanded="false" aria-controls="pregunta-2">¿Qué tipos de consultas puedo realizar?</a></h6>
						</div>
						<div id="pregunta-2" class="collapse" role="tabpanel" aria-labelledby="pregunta-2-title" data-parent="#accordion-help">
							<div class="card-body">El mapa soporta consultas <strong class="text-danger">con filtros</strong> de búsqueda, que son específicas y <strong class="text-danger">requieren una capa, un campo y un valor</strong>; y <strong class="text-danger">sin filtros</strong>, que son generales y <strong class="text-danger">requieren solo una capa</strong>. Actualmente no es posible realizar consultas que involucren distancia o áreas de influencia pre-establecidas, como colonias y calles.</div>
						</div>
					</div> <!-- accordion-inverse-info pregunta-2 -->
					<div class="card accordion-inverse-info">
						<div class="card-header" role="tab" id="pregunta-3-title">
							<h6 class="mb-0"><a class="collapsed" data-toggle="collapse" href="#pregunta-3" aria-expanded="false" aria-controls="pregunta-3">¿Qué es un filtro de búsqueda?</a></h6>
						</div>
						<div id="pregunta-3" class="collapse" role="tabpanel" aria-labelledby="pregunta-3-title" data-parent="#accordion-help">
							<div class="card-body">Un filtro de búsqueda, o simplemente filtro, es una característica o condición que deben cumplir los elementos de una capa encontrados dentro del área de influencia.</div>
						</div>
					</div> <!-- accordion-inverse-info pregunta-3 -->
					<div class="card accordion-inverse-info">
						<div class="card-header" role="tab" id="pregunta-4-title">
							<h6 class="mb-0"><a class="collapsed" data-toggle="collapse" href="#pregunta-4" aria-expanded="false" aria-controls="pregunta-4">¿Cómo agrego un filtro de búsqueda?</a></h6>
						</div>
						<div id="pregunta-4" class="collapse" role="tabpanel" aria-labelledby="pregunta-4-title" data-parent="#accordion-help">
							<div class="card-body">Para agregar un filtro de búsqueda es necesario seleccionar 3 parámetros: la <strong class="text-danger">capa</strong> (Bancos, Postes, Teléfonos públicos, etc.); el <strong class="text-danger">campo</strong> (Nombre, Material, Condición física, etc.); y el <strong class="text-danger">valor</strong> a buscar (Banamex, Madera, Mala, etc.). El valor puede ser ingresado mediante una lista de opciones o una caja de texto.</div>
						</div>
					</div> <!-- accordion-inverse-info pregunta-4 -->
					<div class="card accordion-inverse-info">
						<div class="card-header" role="tab" id="pregunta-5-title">
							<h6 class="mb-0"><a class="collapsed" data-toggle="collapse" href="#pregunta-5" aria-expanded="false" aria-controls="pregunta-5">¿Cuántos filtros de búsqueda puedo agregar?</a></h6>
						</div>
						<div id="pregunta-5" class="collapse" role="tabpanel" aria-labelledby="pregunta-5-title" data-parent="#accordion-help">
							<div class="card-body">No hay una cantidad límite. Una capa puede tener <strong class="text-danger">ya sea uno o más filtros de búsqueda específicos o una consulta general sin filtros</strong>. Un mensaje de alerta puede aparecer si ocurre un caso especial de combinación entre ellos.</div>
						</div>
					</div> <!-- accordion-inverse-info pregunta-5 -->
					<div class="card accordion-inverse-info">
						<div class="card-header" role="tab" id="pregunta-6-title">
							<h6 class="mb-0"><a class="collapsed" data-toggle="collapse" href="#pregunta-6" aria-expanded="false" aria-controls="pregunta-6">¿Qué significan los conectores OR y AND?</a></h6>
						</div>
						<div id="pregunta-6" class="collapse" role="tabpanel" aria-labelledby="pregunta-6-title" data-parent="#accordion-help">
							<div class="card-body">Los conectores OR y AND, denominados <i>operadores booleanos</i>, sirven para unir 2 o más filtros de búsqueda de una misma capa. <strong class="text-danger">Si usa OR, los elementos encontrados deben cumplir al menos una condición; con AND deben cumplir todas las condiciones</strong>.<br>
							&emsp;&emsp;Por ejemplo: la búsqueda con OR de teléfonos públicos que funcionen o que sean de moneda puede mostrar teléfonos que funcionen pero sean de tarjeta, o que no funcionen pero sean de moneda. La búsqueda con AND muestra teléfonos que funcionen y sean de moneda.</div>
						</div>
					</div> <!-- accordion-inverse-info pregunta-6 -->
					<div class="card accordion-inverse-info">
						<div class="card-header" role="tab" id="pregunta-7-title">
							<h6 class="mb-0"><a class="collapsed" data-toggle="collapse" href="#pregunta-7" aria-expanded="false" aria-controls="pregunta-7">¿Cómo influyen los conectores OR y AND en los totales?</a></h6>
						</div>
						<div id="pregunta-7" class="collapse" role="tabpanel" aria-labelledby="pregunta-7-title" data-parent="#accordion-help">
							<div class="card-body"><strong class="text-danger">Con el conector OR, la cantidad total mostrada corresponde a cada filtro</strong>, es decir, cuántos elementos cumplen solo esa condición en particular. Los totales en cada fila de la tabla son independientes. Dado que no es necesario que se satisfagan todas las condiciones, un filtro puede tener un total de 0 y aun así pueden aparecer marcadores en el mapa si se satisface otro filtro de la misma capa.<br>
							&emsp;&emsp;<strong class="text-danger">Con el conector AND, la cantidad total mostrada es siempre igual</strong> para todos los filtros de una misma capa. Es importante que los campos sean coherentes, por ejemplo: un poste no puede ser de madera y metal a la vez.<br>
							&emsp;&emsp;Si se realiza una consulta general sin filtros o una con un solo filtro específico, la cantidad total mostrada es igual independientemente del conector seleccionado.</div>
						</div>
					</div> <!-- accordion-inverse-info pregunta-7 -->
					<div class="card accordion-inverse-info">
						<div class="card-header" role="tab" id="pregunta-8-title">
							<h6 class="mb-0"><a class="collapsed" data-toggle="collapse" href="#pregunta-8" aria-expanded="false" aria-controls="pregunta-8">¿Cómo modifico o elimino una consulta?</a></h6>
						</div>
						<div id="pregunta-8" class="collapse" role="tabpanel" aria-labelledby="pregunta-8-title" data-parent="#accordion-help">
							<div class="card-body">Cada consulta de la tabla de búsqueda puede ser modificada o eliminada con los botones <label class="badge badge-warning"><i class="fas fa-fw fa-pencil-alt text-dark" aria-hidden="true"></i></label> y <label class="badge badge-danger"><i class="fas fa-fw fa-trash" aria-hidden="true"></i></label></div>
						</div>
					</div> <!-- accordion-inverse-info pregunta-8 -->
					<div class="card accordion-inverse-info">
						<div class="card-header" role="tab" id="pregunta-9-title">
							<h6 class="mb-0"><a class="collapsed" data-toggle="collapse" href="#pregunta-9" aria-expanded="false" aria-controls="pregunta-9">He agregado, modificado o eliminado las consultas, pero el mapa no se actualiza. ¿Por qué?</a></h6>
						</div>
						<div id="pregunta-9" class="collapse" role="tabpanel" aria-labelledby="pregunta-9-title" data-parent="#accordion-help">
							<div class="card-body">Cada vez que agregue, modifique o elimine una consulta, o cambie el área de influencia, es necesario pulsar de nuevo el botón <label class="badge badge-danger"><i class="fas fa-fw fa-search" aria-hidden="true"></i>&nbsp;CONSULTAR</label></div>
						</div>
					</div> <!-- accordion-inverse-info pregunta-9 -->

					<h4 class="text-center text-hot-pink">ÁREA DE INFLUENCIA</h4>
					<div class="card accordion-inverse-info">
						<div class="card-header" role="tab" id="pregunta-A-title">
							<h6 class="mb-0"><a class="collapsed" data-toggle="collapse" href="#pregunta-A" aria-expanded="false" aria-controls="pregunta-A">¿Qué es un área de influencia?</a></h6>
						</div>
						<div id="pregunta-A" class="collapse" role="tabpanel" aria-labelledby="pregunta-A-title" data-parent="#accordion-help">
							<div class="card-body">El área de influencia es un polígono, <strong class="text-danger">ya sea un cuadrilátero o de figura libre</strong>, que delimita el espacio geográfico en donde buscar. Su tamaño es arbitrario: puede ser tan grande como toda la ciudad o más pequeño que una manzana.</div>
						</div>
					</div> <!-- accordion-inverse-info pregunta-A -->
					<div class="card accordion-inverse-info">
						<div class="card-header" role="tab" id="pregunta-B-title">
							<h6 class="mb-0"><a class="collapsed" data-toggle="collapse" href="#pregunta-B" aria-expanded="false" aria-controls="pregunta-B">¿Cómo dibujo un área de influencia?</a></h6>
						</div>
						<div id="pregunta-B" class="collapse" role="tabpanel" aria-labelledby="pregunta-B-title" data-parent="#accordion-help">
							<div class="card-body">Si el área seleccionada es un rectángulo o cuadrado, pulse en el lugar donde quiera que empiece y arrastre el cursor hasta lograr el tamaño y la figura deseada. Pulse de nuevo para terminar.<br>
							&emsp;&emsp;Si el área es un polígono, pulse en el mapa para crear un vértice y arrastre en cualquier dirección para formar un segmento. Repita el proceso la cantidad de veces necesaria. Para cerrar el polígono, pulse de nuevo el vértice inicial o haga doble click.</div>
						</div>
					</div> <!-- accordion-inverse-info pregunta-B -->
					<div class="card accordion-inverse-info">
						<div class="card-header" role="tab" id="pregunta-C-title">
							<h6 class="mb-0"><a class="collapsed" data-toggle="collapse" href="#pregunta-C" aria-expanded="false" aria-controls="pregunta-C">¿Puedo consultar simultáneamente en varias áreas de influencia?</a></h6>
						</div>
						<div id="pregunta-C" class="collapse" role="tabpanel" aria-labelledby="pregunta-C-title" data-parent="#accordion-help">
							<div class="card-body">Actualmente sólo es posible realizar una consulta a la vez en la última área de influencia dibujada. En el mapa no puede haber más de 2 áreas de influencia: la más reciente y la anterior como marco de referencia. Sin embargo, no se pueden hacer consultas simultáneas en ambas.</div>
						</div>
					</div> <!-- accordion-inverse-info pregunta-C -->

					<h4 class="text-center text-hot-pink">DATOS ABIERTOS DE CATASTRO COLIMA</h4>
					<div class="card accordion-inverse-info">
						<div class="card-header" role="tab" id="pregunta-D-title">
							<h6 class="mb-0"><a class="collapsed" data-toggle="collapse" href="#pregunta-D" aria-expanded="false" aria-controls="pregunta-D">¿Cómo consulto la información de los marcadores en el mapa?</a></h6>
						</div>
						<div id="pregunta-D" class="collapse" role="tabpanel" aria-labelledby="pregunta-D-title" data-parent="#accordion-help">
							<div class="card-body">Para observar la información disponible de un elemento en particular, pulse sobre el marcador que represente la capa a la que pertenece el elemento. Muchos marcadores pueden aparecer juntos en un área muy pequeña, por lo que es recomendable utilizar el zoom <label class="badge badge-info"><i class="fas fa-plus text-dark" aria-hidden="true"></i></label> del mapa.</div>
						</div>
					</div> <!-- accordion-inverse-info pregunta-D -->
					<div class="card accordion-inverse-info">
						<div class="card-header" role="tab" id="pregunta-E-title">
							<h6 class="mb-0"><a class="collapsed" data-toggle="collapse" href="#pregunta-E" aria-expanded="false" aria-controls="pregunta-E">La latitud y/o longitud mostradas no concuerdan con la localización real del elemento. ¿Por qué?</a></h6>
						</div>
						<div id="pregunta-E" class="collapse" role="tabpanel" aria-labelledby="pregunta-E-title" data-parent="#accordion-help">
							<div class="card-body">Catastro Colima crea archivos SHP con coordenadas UTM que georreferencian cada elemento de la capa en cuestión. Este formato se basa en una proyección en 2D, igual que un plano cartesiano, y facilita el cálculo de las coordenadas ya que requiere menos factores de procesamiento.<br>
							&emsp;&emsp;El sistema más común de las coordenadas a las que estamos acostumbrados es WGS84, que a su vez puede expresarse en grados, minutos y segundos (GMS); o grados decimales (GD). Dado que utiliza una superficie 3D, se requieren fórmulas de conversión de GD a UTM para crear el área de influencia y de UTM a GD para desplegar los marcadores en el mapa.<br>
							&emsp;&emsp;Estas fórmulas son bastante complejas y necesitan al menos 4 decimales para ser muy precisas. La latitud y longitud convertidas contienen 6 decimales. Si el marcador señalado en Google Maps no concuerda con la posición real del elemento, por más cercana que ésta sea, se debe a que las coordenadas UTM originales no fueron recabadas con exactitud.</div>
						</div>
					</div> <!-- accordion-inverse-info pregunta-E -->
					<div class="card accordion-inverse-info">
						<div class="card-header" role="tab" id="pregunta-F-title">
							<h6 class="mb-0"><a class="collapsed" data-toggle="collapse" href="#pregunta-F" aria-expanded="false" aria-controls="pregunta-F">¿Cuál es la fecha de recolección y/o actualización de los datos?</a></h6>
						</div>
						<div id="pregunta-F" class="collapse" role="tabpanel" aria-labelledby="pregunta-F-title" data-parent="#accordion-help">
							<div class="card-body">El <a href="http://catastrocolima.gob.mx/cartografia.html" title="Ir al mapa digital actual" target="_blank" class="text-danger">mapa digital</a> de Catastro Colima es una de las plataformas pioneras para la consulta de datos abiertos en México y todo el personal de Catastro ha trabajado arduamente para recolectarlos.<br>
							&emsp;&emsp;La mayoría de las capas fueron creadas durante 2016 y en 2017 se agregaron o modificaron los elementos correspondientes. Sin embargo, es probable que a la fecha actual las capas no contengan por completo todos los elementos que existen en la ciudad porque es muy complicado detectar todos los cambios en cada predio, negocio, luminaria, teléfono público, semáforo, tope, árbol, etc.<br>
							&emsp;&emsp;Algunas capas no se han modificado por su naturaleza intrínseca, por ejemplo: cauces y cuerpos de agua, monumentos históricos o patrimonios dañados por el sismo en 2003.</div>
						</div>
					</div> <!-- accordion-inverse-info pregunta-F -->
				</div> <!-- accordion-help ends -->
            </div> <!-- modal-body ends -->
        </div> <!-- modal-content ends -->
    </div> <!-- modal-dialog ends -->
</div> <!-- modal fade ends -->