<div class="modal fade" id="myModalHelp" role="dialog">
	<div class="modal-dialog modal-lg"> <!-- LARGE MODAL -->
		<div class="modal-content">

			<div class="modal-header" style="background-color: #eee;" align="center">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h5 class="modal-title">
					<img src="images/help.png" id="logo-alertaModal" style="height: 32px; width: 32px;">
				</h5>
				<h3 align="center" class="text-secondary">Preguntas frecuentes</h3>
			</div> <!-- modal-header -->

			<div class="modal-body">
				<form class="form-horizontal">
					<div class="form-group">
						<div class="col-sm-offset-1 col-sm-10" style="text-align: justify; font-size: 15px;">
							Para realizar una consulta de los <b>datos abiertos</b> que la Dirección General de Catastro Municipal de Colima ofrece en la zona conurbada de Colima y Villa de Álvarez, es necesario:
							<ul id="ulmodalhelpnested" style="margin-top: 10px;">
								<li>Agregar una o más capas y, opcionalmente, los filtros de búsqueda específicos deseados.</li>
								<li>Trazar un área de influencia en el mapa.</li>
							</ul><hr>

							<!-- accordion 1: FILTROS DE BÚSQUEDA -->
							<center><h4 class="text-pink">FILTROS DE BÚSQUEDA</h4></center>
							<div class="panel panel-default" style="padding: 20px; margin: 0px;">
								<div class="panel-group" id="accordionFB" style="margin: 0px;">

									<div class="panel panel-success"> <!-- panel-success Pregunta 1 -->
										<div class="panel-heading">
											<a data-toggle="collapse" data-parent="#accordionFB" href="#question1">
												¿Qué es un filtro de búsqueda?</a>
										</div>
										<div id="question1" class="panel-collapse collapse">
											<div class="panel-body">
												Un filtro de búsqueda es una característica o condición que deben cumplir los elementos de las capas encontrados dentro del área de influencia.
											</div>
										</div>
									</div> <!-- panel-success Pregunta 1 -->

									<div class="panel panel-success"> <!-- panel-success Pregunta 2 -->
										<div class="panel-heading">
											<a data-toggle="collapse" data-parent="#accordionFB" href="#question2">
												¿Cómo agrego un filtro de búsqueda?</a>
										</div>
										<div id="question2" class="panel-collapse collapse">
											<div class="panel-body">
												Para agregar un filtro de búsqueda es necesario seleccionar 3 parámetros: la capa (Bancos, Postes, Teléfonos Públicos, etc.); el campo (Nombre, Material, Condición Física, etc.); y el valor a buscar (Banamex, Madera, Mala, etc.). El valor puede ser especificado mediante una lista de opciones o una caja de texto. Para finalizar, pulse <strong class="text-success">AGREGAR FILTRO</strong>.
											</div>
										</div>
									</div> <!-- panel-success Pregunta 2 -->

									<div class="panel panel-success"> <!-- panel-success Pregunta 3 -->
										<div class="panel-heading">
											<a data-toggle="collapse" data-parent="#accordionFB" href="#question3">
												¿Cuántos filtros de búsqueda puedo agregar?</a>
										</div>
										<div id="question3" class="panel-collapse collapse">
											<div class="panel-body">
												No hay una cantidad límite. Una capa puede tener uno o más filtros específicos, así como una búsqueda general sin filtros. Sin embargo, un mensaje de alerta puede aparecer si ocurre un caso especial de combinación entre ellos.
											</div>
										</div>
									</div> <!-- panel-success Pregunta 3 -->

									<div class="panel panel-success"> <!-- panel-success Pregunta 4 -->
										<div class="panel-heading">
											<a data-toggle="collapse" data-parent="#accordionFB" href="#question4">
												¿Existe otra opción de búsqueda?</a>
										</div>
										<div id="question4" class="panel-collapse collapse">
											<div class="panel-body">
												Además de los filtros específicos, puede escoger <strong class="text-success">AGREGAR CAPA</strong> para mostrar todos los elementos sin importar sus características. Actualmente no es posible realizar consultas que involucren distancia o áreas de influencia pre-establecidas, como colonias y calles.
											</div>
										</div>
									</div> <!-- panel-success Pregunta 4 -->

									<div class="panel panel-success"> <!-- panel-success Pregunta 5 -->
										<div class="panel-heading">
											<a data-toggle="collapse" data-parent="#accordionFB" href="#question5">
												¿Qué significan los conectores OR y AND?</a>
										</div>
										<div id="question5" class="panel-collapse collapse">
											<div class="panel-body">
												Los conectores OR y AND, denominados <i>operadores booleanos</i>, sirven para unir 2 o más filtros de búsqueda de una misma capa. Si usa OR, los elementos encontrados deben cumplir al menos una condición; en cambio, si escoge AND deben cumplir todas las condiciones.<br>
												&emsp;&emsp;Por ejemplo, la búsqueda con OR de teléfonos públicos que funcionen o que sean de moneda puede mostrar teléfonos que funcionen pero sean de tarjeta, o que no funcionen pero sean de moneda. La búsqueda con AND muestra teléfonos que funcionen y sean de moneda.
											</div>
										</div>
									</div> <!-- panel-success Pregunta 5 -->

									<div class="panel panel-success"> <!-- panel-success Pregunta 6 -->
										<div class="panel-heading">
											<a data-toggle="collapse" data-parent="#accordionFB" href="#question6">
												¿Cómo influyen los conectores OR y AND en los totales?</a>
										</div>
										<div id="question6" class="panel-collapse collapse">
											<div class="panel-body">
												Con el conector OR, la cantidad total mostrada corresponde a cada filtro, es decir, cuántos elementos cumplen solamente esa condición en particular. Los totales en cada fila de la tabla son independientes. Dado que no es necesario que se satisfagan todas las condiciones, un filtro puede tener un total de 0 y aun así pueden aparecer marcadores en el mapa si se satisface otro filtro de la misma capa.<br>
												&emsp;&emsp;Con el conector AND, la cantidad total mostrada es siempre igual para todos los filtros de una misma capa. Es importante que los campos sean coherentes, por ejemplo: un poste no puede ser de madera y metal a la vez.<br>
												&emsp;&emsp;Si se realiza una consulta con un sólo filtro específico, o una búsqueda general sin filtros en una capa, la cantidad total mostrada es igual independientemente del conector seleccionado.
											</div>
										</div>
									</div> <!-- panel-success Pregunta 6 -->

									<div class="panel panel-success"> <!-- panel-success Pregunta 7 -->
										<div class="panel-heading">
											<a data-toggle="collapse" data-parent="#accordionFB" href="#question7">
												¿Puedo eliminar o modificar un filtro de búsqueda?</a>
										</div>
										<div id="question7" class="panel-collapse collapse">
											<div class="panel-body">
												Cada filtro de búsqueda que aparezca en la tabla puede ser eliminado o modificado con los botones <i class="fa fa-trash-o" aria-hidden="true"></i> y <i class="fa fa-pencil" aria-hidden="true"></i>. Durante el modo edición, algunos botones se deshabilitarán hasta que termine de modificar.
											</div>
										</div>
									</div> <!-- panel-success Pregunta 7 -->

									<div class="panel panel-success"> <!-- panel-success Pregunta 8 -->
										<div class="panel-heading">
											<a data-toggle="collapse" data-parent="#accordionFB" href="#question8">
												He agregado, editado o eliminado filtros de búsqueda, pero el mapa no se actualiza. ¿Por qué?</a>
										</div>
										<div id="question8" class="panel-collapse collapse">
											<div class="panel-body">
												Cada vez que agregue un nuevo filtro, edite o elimine los filtros de la consulta anterior, o cambie el área de influencia, es necesario pulsar de nuevo <strong class="text-info">CONSULTAR</strong>.
											</div>
										</div>
									</div> <!-- panel-success Pregunta 8 -->
								</div> <!-- panel-group FILTROS DE BÚSQUEDA-->
							</div><br> <!-- panel-default FILTROS DE BÚSQUEDA-->

							<!-- accordion 2: ÁREA DE INFLUENCIA -->
							<center><h4 class="text-pink">ÁREA DE INFLUENCIA</h4></center>
							<div class="panel panel-default" style="padding: 20px; margin: 0px;">
								<div class="panel-group" id="accordionAI" style="margin: 0px;">

									<div class="panel panel-success"> <!-- panel-success Pregunta 9 -->
										<div class="panel-heading">
											<a data-toggle="collapse" data-parent="#accordionAI" href="#question9">
												¿Qué es un área de influencia?</a>
										</div>
										<div id="question9" class="panel-collapse collapse">
											<div class="panel-body">
												El área de influencia es un polígono, ya sea un cuadrilátero o de figura libre, que delimita el espacio geográfico en donde buscar. Su tamaño es arbitrario: puede ser tan grande como toda la ciudad o más pequeño que una manzana.
											</div>
										</div>
									</div> <!-- panel-success Pregunta 9 -->

									<div class="panel panel-success"> <!-- panel-success Pregunta A -->
										<div class="panel-heading">
											<a data-toggle="collapse" data-parent="#accordionAI" href="#questionA">
												¿Cómo dibujo un área de influencia?</a>
										</div>
										<div id="questionA" class="panel-collapse collapse">
											<div class="panel-body">
												Si el área seleccionada es un rectángulo o cuadrado, pulse en el lugar donde quiera que empiece y arrastre el cursor hasta lograr el tamaño y la figura deseada. Pulse de nuevo para terminar.<br>
												&emsp;&emsp;Si el área es un polígono, pulse en el mapa para crear un vértice y arrastre en cualquier dirección para formar un segmento. Repita el proceso la cantidad de veces necesaria. Para cerrar el polígono, pulse de nuevo el vértice inicial o haga doble click.
											</div>
										</div>
									</div> <!-- panel-success Pregunta A -->

									<div class="panel panel-success"> <!-- panel-success Pregunta B -->
										<div class="panel-heading">
											<a data-toggle="collapse" data-parent="#accordionAI" href="#questionB">
												¿Puedo consultar simultáneamente en varias áreas de influencia?</a>
										</div>
										<div id="questionB" class="panel-collapse collapse">
											<div class="panel-body">
												Actualmente sólo es posible realizar una consulta a la vez en la última área de influencia dibujada. En el mapa no puede haber más de 2 áreas de influencia: la más reciente y la anterior como marco de referencia. Sin embargo, no se pueden hacer consultas simultáneas en ambas.
											</div>
										</div>
									</div> <!-- panel-success Pregunta B -->
								</div> <!-- panel-group ÁREA DE INFLUENCIA -->
							</div><br> <!-- panel-default ÁREA DE INFLUENCIA -->

							<!-- accordion 3: CONSULTA DE DATOS ABIERTOS -->
							<center><h4 class="text-pink">CONSULTA DE DATOS ABIERTOS</h4></center>
							<div class="panel panel-default" style="padding: 20px; margin: 0px;">
								<div class="panel-group" id="accordionDA" style="margin: 0px;">

									<div class="panel panel-success"> <!-- panel-success Pregunta C -->
										<div class="panel-heading">
											<a data-toggle="collapse" data-parent="#accordionDA" href="#questionC">
												¿Cómo consulto la información de los marcadores en el mapa?</a>
										</div>
										<div id="questionC" class="panel-collapse collapse">
											<div class="panel-body">
												Para observar la información disponible de un elemento en particular, pulse sobre el marcador que represente la capa a la que pertenece el elemento. Es recomendable utilizar el zoom <i class="fa fa-plus" aria-hidden="true"></i> en el mapa ya que muchos marcadores pueden aparecer juntos en un área muy pequeña.
											</div>
										</div>
									</div> <!-- panel-success Pregunta C -->

									<div class="panel panel-success"> <!-- panel-success Pregunta D -->
										<div class="panel-heading">
											<a data-toggle="collapse" data-parent="#accordionDA" href="#questionD">
											La latitud y/o longitud mostrada no concuerda con la localización real del elemento. ¿Por qué?</a>
										</div>
										<div id="questionD" class="panel-collapse collapse">
											<div class="panel-body">
												Catastro Colima crea archivos SHP con coordenadas UTM que georreferencian cada elemento de la capa en cuestión. Este formato se basa en una proyección en 2D, igual que un plano cartesiano, y facilita el cálculo de las coordenadas ya que requiere menos factores de procesamiento.<br>
												&emsp;&emsp;El sistema más común de las coordenadas a las que estamos acostumbrados es WGS84, que a su vez puede expresarse en grados, minutos y segundos (GMS); o grados decimales (GD). Dado que utiliza una superficie 3D, se requieren fórmulas de conversión de GD a UTM para crear el área de influencia y de UTM a GD para desplegar los marcadores en el mapa.<br>
												&emsp;&emsp;Estas fórmulas son bastante complejas y necesitan al menos 4 decimales para ser muy precisas. La latitud y longitud convertidas contienen 6 decimales. Si el marcador señalado en Google Maps no concuerda con la posición real del elemento, por más cercana que ésta sea, se debe a que las coordenadas UTM originales no fueron recabadas con exactitud.
											</div>
										</div>
									</div> <!-- panel-success Pregunta D -->

									<div class="panel panel-success"> <!-- panel-success Pregunta E -->
										<div class="panel-heading">
											<a data-toggle="collapse" data-parent="#accordionDA" href="#questionE">
												¿Cuál es la fecha de recolección y/o actualización de los datos?</a>
										</div>
										<div id="questionE" class="panel-collapse collapse">
											<div class="panel-body">
												El <a href="http://catastrocolima.gob.mx/cartografia.html" title="Ir al mapa cartográfico digital" target="_blank">mapa cartográfico digital</a> de Catastro Colima es una de las plataformas pioneras para la consulta de datos abiertos en México y todo el personal de Catastro ha trabajado arduamente para recolectarlos.<br>
												&emsp;&emsp;La mayoría de las capas fueron creadas durante 2016 y en 2017 se agregaron o modificaron los elementos correspondientes. Sin embargo, es probable que a la fecha actual las capas no contengan por completo todos los elementos que existen en la ciudad porque es muy complicado detectar todos los cambios en cada predio, negocio, luminaria, teléfono público, semáforo, tope, árbol, etc.<br>
												&emsp;&emsp;Algunas capas no se han modificado por su naturaleza intrínseca, por ejemplo: cauces y cuerpos de agua, monumentos históricos o patrimonios dañados por el sismo en 2003.
											</div>
										</div>
									</div> <!-- panel-success Pregunta E -->
								</div> <!-- panel-group CONSULTA DE DATOS ABIERTOS -->
							</div> <!-- panel-default CONSULTA DE DATOS ABIERTOS -->
						</div>
					</div> <!-- form-group -->
				</form>
			</div> <!-- modal-body -->
		</div> <!-- modal-content -->
	</div> <!-- modal-dialog -->
</div> <!-- modal fade -->