//  (event) {
//  var parametrosFe= {
// 	Auth: {
// 		Token: "",
// 		Sign: "",
// 		Cuit: ""
// 	},
// 	FeCAEReq: {
// 		FeCabReq: {
// 			CantReg: 1,
// 			PtoVta: 2,
// 			CbteTipo: 11
// 		},
// 		FeDetReq: {
// 			FECAEDetRequest: {
// 				Concepto: 1 ,
// 				DocTipo: 80,
// 				DocNro: 30711529280,
// 				CbteDesde: 0,
// 				CbteHasta: 0,
// 				CbteFch: "2025/0404",
// 				ImpTotal: 200,
// 				ImpTotConc:0 ,
// 				ImpNeto: 200,
// 				ImpOpEx: 0,
// 				ImpTrib: 0,
// 			  	ImpIVA: 20,
// 				FchServDesde: "",
// 				FchServHasta: "",
// 				FchVtoPago: "",
// 				MonId: "PES",
// 				MonCotiz: 1,
// 		 		CbtesAsoc:
// 				[
// 				{	Tipo: 11,
// 					PtoVta: 2,
// 					Nro: 4160,
// 					Cuit: "",
// 					CbteFch: "20250330"
// 				},
//         		{
// 					Tipo: 11,
// 					PtoVta: 3,
// 					Nro: 99,
// 					Cuit: "",
// 					CbteFch: "20250330"
// 				}
// 				],
		 		// Tributos:[
				//  {
				// 		Id: "",
				// 		Desc: "",
				// 		BaseImp: "",
				// 		Alic: "",
				// 		Importe: ""
				// }
				// ],
				// Iva: [{
				// 		Id: "",
				// 		BaseImp: "",
				// 		Importe: ""
				// }],
				// Opcionales: 
				// [{
				// 		Id: "",
				// 		Valor: ""
				// }],
				// Compradores: 
				// [{

				// 		DocTipo: "",
				// 		DocNro: "",
				// 		Porcentaje: ""
				// }],
				// PeriodoAsoc: 
				// {
				// 	FchDesde: "",
				// 	FchHasta: ""
				// },
				// Actividades: [{	Id: ""}],
// 			},
// 		},
// 	},
//  };
const filaNombreColumnas=4;
const filaInicialData=5;
let filaSelecionada=undefined;
let dataLog=[];		// array para los errores y observaciones
let jsonData=[];	// Contiene los datos del excel
let file="";

// boton para seleccionar todos los checkbox
document.getElementById('SeleccionarTodo').addEventListener('click',()=>{
	for (const checkbox of document.querySelectorAll('table input[type="checkbox"]')) {
		checkbox.checked=true;
	}
});

// boton para desmarcar todos los checkbox
document.getElementById('LimpiarSeleccion').addEventListener('click',()=>{
for (const checkbox of document.querySelectorAll('table input[type="checkbox"]:checked')) {
		checkbox.checked=false;
	}
});

// boton de envío múltiple
document.getElementById('EnviarSeleccion').addEventListener('click', EnvioMultiple)

/* Funcion para escribir un excel con los errores que se produjeron
	Se pasa el numero de la fila, la fecha, el codigo de error y la descripcion del error
*/
function escribidor(data){
	// formateo fecha para el nombre de archivo
	let fechaFile=new Date().toLocaleString()
		.slice(0, 16)
		.replace(/\//g, '').replace(/:/g, '').replace(/,/g, '');
	let nombreArchivo="ResultadosEnvio"+fechaFile+".xlsx"
	// Cargo los datos del resultado en la primer hoja
	const workbook = XLSX.utils.book_new();
	const worksheet = XLSX.utils.json_to_sheet(data);
	XLSX.utils.book_append_sheet(workbook, worksheet, "Resultados");

    // Cargo los datos del archivo original en la segunda hoja
    const worksheet1 = XLSX.utils.json_to_sheet(jsonData);
    XLSX.utils.book_append_sheet(workbook, worksheet1, "Original");
	
	// Guardar el archivo en disco
	XLSX.writeFile( workbook, nombreArchivo);
	console.log("¡Archivo Excel creado con éxito!");
	
	window.alert("Archivo " + nombreArchivo + " creado en descargas");
}


 async function EnvioMultiple(){
	const chkcant=document.querySelectorAll('table input[type="checkbox"]:checked');
	if (chkcant.length != 0) {
        // Usar for...of en lugar de forEach para poder usar await
        for (const checkbox of document.querySelectorAll('table input[type="checkbox"]:checked')) {
            var par = lcdtm(checkbox.id);
            let z= await enviarDatos({ q: 'solicitar', info: par }, checkbox.id);
        }
        // console.log(datosEnviar);
        escribidor(dataLog);
    }
	dataLog=[];
 }


/* Recolectar los datos de la fila seleccionada
 	toma los datos de una fila y devuelve un array asociativo.
	transforma el formato de la columna de CbteFech de dd/mm/aaaa a aaaammdd
	transforma el string de MonId de "$"" a "PES"
	Si Docnro no esta cargado se asigna el nro de documento emisor
*/
function lcdtm(n){
	let datosFila={};
	let camposAFIP=jsonData[1];
	let datosAFIP=jsonData[n];
	let docnro=jsonData[2][3];
	
	if( docnro=="") {
		window.alert("Debe estar cargado el doc del receptor");
		return;
	} 
	for (i=0;i<camposAFIP.length;i++){	
		value=datosAFIP[i];
		if (value !== null && value !== undefined && value !== '') {
			datosFila[camposAFIP[i]] = value;
		}	
	}
	// verificar DocNro
	if(!('DocNro'  in datosFila)){
		datosFila['DocNro']=docnro;
	}
	if(datosFila['MonId']=="$"){
		datosFila['MonId']="PES";
	}
	anio=jsonData[n][0].split("/")[2];
	mes=jsonData[n][0].split("/")[1];
	dia=jsonData[n][0].split("/")[0];
	datosFila['CbteFch']=anio+mes+dia;
	return datosFila;
}

// limpiar la tabla y volver al inicio
document.getElementById('LimpiarTabla').addEventListener('click',()=>{
	let tableHeader = document.getElementById('tableHeader');
    let tableBody = document.querySelector('#dataTable tbody');
	tableHeader.innerHTML = '';
    tableBody.innerHTML = '';
	window.location.href="http://localhost";
});

document.getElementById('fileInput').addEventListener('change', function(event) {
    file = event.target.files[0];
    let reader = new FileReader();
	let tableHeader = document.getElementById('tableHeader');
	let tableBody = document.querySelector('#dataTable tbody');
    
    reader.onload = function(e) {
		nfila=filaInicialData-1;
        let data = new Uint8Array(e.target.result);
        let workbook = XLSX.read(data, { type: 'array' });
        let sheet = workbook.Sheets[workbook.SheetNames[0]];
        jsonData = XLSX.utils.sheet_to_json(sheet, { header: 1, defval: '',blankrows: false });
        
        if (jsonData.length > 0) {
 
            tableHeader.innerHTML = '';
            tableBody.innerHTML = '';

			// Armar Cabecera --------
			let thAction = document.createElement('th');
            thAction.textContent = 'Acción';
            tableHeader.appendChild(thAction);
            jsonData[filaNombreColumnas-1].forEach(header => {
                let th = document.createElement('th');
                th.textContent = header;
                tableHeader.appendChild(th);
            });
            
            //  Armar Body -------------------
            let DocEmisor=document.getElementById('docEmisor');
			DocEmisor.innerText=DocEmisor.innerText + jsonData[2][3];
            jsonData.slice(filaInicialData-1).forEach(row => {
                let tr = document.createElement('tr');
                tr.id='fila_'+nfila;
				
                let tdAction = document.createElement('td');
				let check=document.createElement('input');
				check.type='checkbox';
				check.id=nfila;
                let btn = document.createElement('button');
                btn.id="b_"+ nfila;
				btn.textContent = 'Enviar';
                btn.classList.add('btn', 'btn-primary', 'btn-sm');
                btn.onclick = function() {
					filaSelecionada=btn.id.split("_")[1];
					var par=lcdtm(btn.id.split("_")[1]);
					enviarDatos({q:'solicitar',info:par},filaSelecionada);
                };
				nfila++;
				tdAction.appendChild(check);
                tdAction.appendChild(btn);
                tr.appendChild(tdAction);
                tableBody.appendChild(tr);
                
                jsonData[0].forEach((_, index) => {
                    let td = document.createElement('td');
					td.textContent = row[index] || '';
                    tr.appendChild(td);
                });
            });
        }
    };   
    reader.readAsArrayBuffer(file);
});



async function enviarDatos(datos,nfila) {

  
	try{
		const response=await fetch( "vista/ajax/AjaxClAfip.php",{
			method: 'POST',
            headers: {
                'Content-Type': 'application/json',
			},
			body: JSON.stringify(datos)
		}); 
		if (!response.ok) {
					throw new Error(`Error en el servidor: ${response.status}`);
		}
        
        const resultado = await response.json();
        console.log("Éxito:", resultado);
		procesarRespuesta(resultado,nfila);
        return resultado;
    } catch (error) {
        console.error("Error en enviarDatos:", error);
        throw error; 
    }
}


function procesarRespuesta(respuestaFetch,nfila){
	respuesta =respuestaFetch;
      console.log(respuesta);
	  
	  if(Object.hasOwn(respuesta, 'Errors')){
		const errores=respuesta.Errors.Err;
		console.log(respuesta.Errors);
		MostrarErrores(errores,1,nfila);
		
	  } else if(respuesta.FeDetResp.FECAEDetResponse.Resultado!="A"){
    	if(respuesta.FeDetResp.FECAEDetResponse.Observaciones.hasOwnProperty('Obs')){
			const obs=respuesta.FeDetResp.FECAEDetResponse.Observaciones.Obs
			MostrarErrores(obs,2,nfila);
		}
      }
      else {
        let zz=document.getElementById("mensaje");
		zz.innerText="Errores informados";
		let fila=document.getElementById("fila_"+nfila);
		fila.style.backgroundColor='lightgreen';
		fila.cells[7].textContent=respuesta.FeDetResp.FECAEDetResponse.CAE;
		fila.cells[6].textContent=respuesta.FeDetResp.FECAEDetResponse.CAE;
		// deshabilito boton enviar de la fila
		let boton=document.getElementById("b_"+nfila);
		boton.disabled=true;
		// deshabilito el checkbox
		let chk=document.getElementById(+nfila);
		chk.checked=false;
		chk.disable=true;
		dataLog.push ({ Tipo: "OK",Fila: nfila,Fecha:fechaActual , CAE:respuesta.FeDetResp.FECAEDetResponse.CAE });
      }
}

function consultarDatos(tipo){
    const xhr = new XMLHttpRequest();
    xhr.open("get", "vista/ajax/AjaxClAfip.php?q=" + tipo);
    xhr.send();
    xhr.onreadystatechange = function () {
      if (this.readyState == 4 && this.status == 200) {
        let data = JSON.parse(this.responseText);
        // var_dump(data);
      }                 	
    
    }
}

let fechaActual=new Date().toISOString()
		.slice(0, 10)
		.replace(/-/g, '');
/*----
Funcion MostrarErrores
data: array de objetos
tipo : 1 ->error , 2->observacion
nfila: fila que corresponde a los datos
----*/
function MostrarErrores(data,tipo,nfila){
	const container = document.getElementById('errores-container');
	const totalErrores = document.getElementById('total-errores');
	
	const modal=document.getElementById('Modal');
	
	if (Array.isArray(data)){
		// Mostrar el total de errores
		totalErrores.textContent = data.length;
		// Generar HTML para cada error
		data.forEach(error => {
			const errorHTML = `
				<div class="error-card">
					<div class="error-code">Error ${error.Code}</div>
					<div class="error-message">${error.Msg}</div>
				</div>
			`;
			dataLog.push ({ Tipo: "Error",Fila: nfila,Fecha:fechaActual , Codigo:error.Code, Error: error.Msg }),
			console.log(dataLog);
			container.innerHTML += errorHTML;
		});
	}else{
			const errorHTML = `
				<div class="error-card">
					<div class="error-code">Error ${data.Code}</div>
					<div class="error-message">${data.Msg}</div>
				</div>
			`;
			
			dataLog.push ({ Tipo: "Observaciones",Fila: nfila++,Fecha:fechaActual , Codigo:data.Code, Error: data.Msg }),
			container.innerHTML =errorHTML;
			console.log(dataLog);
			// escribidor(dataLog);
	}
	if (tipo==1){
		let zz=document.getElementById("mensaje");
		zz.innerText="Errores informados";
		let fila=document.getElementById("fila_"+nfila);
		fila.style.backgroundColor='red';
	}else{
		let zz=document.getElementById("mensaje");
		zz.innerText="Observaciones informadas";
		let boton=document.getElementById("fila_"+nfila);
		boton.style.backgroundColor='orange';
	}
}
  
