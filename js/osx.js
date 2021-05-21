/*
 * SimpleModal OSX Style Modal Dialog
 * http://www.ericmmartin.com/projects/simplemodal/
 * http://code.google.com/p/simplemodal/
 *
 * Copyright (c) 2010 Eric Martin - http://ericmmartin.com
 *
 * Licensed under the MIT license:
 *   http://www.opensource.org/licenses/mit-license.php
 *
 * Revision: $Id: osx.js 238 2010-03-11 05:56:57Z emartin24 $
 */

jQuery(function ($) {
	$("#man1").click(function(){
		$("#osx-modal-title").html("MANUAL PARA LEVANTAR UN CASO");
		$("#cont_modal").html("<object data='manuales/LEVANTA_CASO.pdf' type='application/pdf' width='100%' height='700px'></object>")
	});
	
	$("#man2").click(function(){
		$("#osx-modal-title").html("MANUAL HELPDESK PARA USUARIO");
		$("#cont_modal").html("<object data='manuales/HD_USUARIO.pdf' type='application/pdf' width='100%' height='700px'></object>")
	});
	
	$("#man3").click(function(){
		$("#osx-modal-title").html("MANUAL PARA LA INSTALACION DE JAVA");
		$("#cont_modal").html("<object data='manuales/JAVA.pdf' type='application/pdf' width='100%' height='700px'></object>")
	});
	
	$("#man4").click(function(){
		$("#osx-modal-title").html("MANUAL DE INSTALACION DE KASPERSKY");
		$("#cont_modal").html("<object data='manuales/KASPERSKY.pdf' type='application/pdf' width='100%' height='700px'></object>")
	});
	
	$("#man5").click(function(){
		$("#osx-modal-title").html("MANUAL DE CONFIGURACION MICROSOFT OUTLOOK 2000");
		$("#cont_modal").html("<object data='manuales/MicrosoftOutlook2000.pdf' type='application/pdf' width='100%' height='700px'></object>")
	});
	
	$("#man6").click(function(){
		$("#osx-modal-title").html("MANUAL DE CONFIGURACION ORACLE ClIENT");
		$("#cont_modal").html("<object data='manuales/oracleclient.pdf' type='application/pdf' width='100%' height='700px'></object>")
	});
	
	$("#man7").click(function(){
		$("#osx-modal-title").html("MANUAL DE CONFIGURACION ODBC");
		$("#cont_modal").html("<object data='manuales/odbcsoal.pdf' type='application/pdf' width='100%' height='700px'></object>")
	});

	$("#man8").click(function(){
		$("#osx-modal-title").html("CONFIGURACION DE LAS PISTOLAS DE LASER");
		$("#cont_modal").html("<object data='manuales/config_laser_guns.pdf' type='application/pdf' width='100%' height='700px'></object>")
	});
	
	$("#man9").click(function(){
		$("#osx-modal-title").html("CONFIGURACION HANDHELD");
		$("#cont_modal").html("<object data='manuales/handheld.pdf' type='application/pdf' width='100%' height='700px'></object>")
	});
	
	$("#man10").click(function(){
		$("#osx-modal-title").html("ESTRUCTURACION NUEVA GUIA FACT. ELECT.");
		$("#cont_modal").html("<object data='manuales/factura.swf' type='application/x-shockwave-flash' width='100%' height='700px'></object>")
	});
	
	$("#man11").click(function(){
		$("#osx-modal-title").html("MANUAL PARA DESCARGAR VARIAS FACTURAS A LA VEZ");
		$("#cont_modal").html("<object data='manuales/descargar_facturas.pdf' type='application/pdf' width='150%' height='700px'></object>")
	});
	
	var OSX = {
		container: null,
		init: function () {
			$("input.osx, a.osx").click(function (e) {
				e.preventDefault();	

				$("#osx-modal-content").modal({
					overlayId: 'osx-overlay',
					containerId: 'osx-container',
					closeHTML: null,
					minHeight: 80,
					opacity: 65, 
					position: ['0',],
					overlayClose: true,
					onOpen: OSX.open,
					onClose: OSX.close
				});
			});
		},
		open: function (d) {
			var self = this;
			self.container = d.container[0];
			d.overlay.fadeIn('slow', function () {
				$("#osx-modal-content", self.container).show();
				var title = $("#osx-modal-title", self.container);
				title.show();
				d.container.slideDown('slow', function () {
					setTimeout(function () {
						var h = $("#osx-modal-data", self.container).height()
							+ title.height()
							+ 20; // padding
						d.container.animate(
							{height: h}, 
							200,
							function () {
								$("div.close", self.container).show();
								$("#osx-modal-data", self.container).show();
							}
						);
					}, 300);
				});
			})
		},
		close: function (d) {
			var self = this; // this = SimpleModal object
			d.container.animate(
				{top:"-" + (d.container.height() + 20)},
				500,
				function () {
					self.close(); // or $.modal.close();
				}
			);
		}
	};

	OSX.init();

});