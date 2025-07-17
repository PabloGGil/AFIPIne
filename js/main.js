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
let dataErr=[];		// array para los errores 
let jsonData=[];	// Contiene los datos del excel
document.getElementById('EnviarSeleccion').addEventListener('click', EnvioMultiple)

/* Funcion para escribir un excel con los errores que se produjeron
	Se pasa el numero de la fila, la fecha, el codigo de error y la descripcion del error
*/
function escribidor(data){
	// formateo fecha para el nombre de archivo
	let fechaFile=new Date().toISOString()
  .slice(0, 10)
  .replace(/-/g, '');
//   console.log(ahora);
// 	data['Fecha']=ahora;
	// Crear un libro de Excel y una hoja
	const workbook = XLSX.utils.book_new();
	const worksheet = XLSX.utils.json_to_sheet(data);
	
	// let fechaFile= ahora.getFullYear() + ahora.getMonth() + ahora.getDay() + ahora.getMilliseconds();
	// fechaFile=ahora;
	// console.log("Año:" +ahora.getFullYear() + "mes: "+ahora.getMonth() + "dia:"+ahora.getDay() + "mili:"+ ahora.getMilliseconds())
	// Añadir la hoja al libro
	XLSX.utils.book_append_sheet(workbook, worksheet, "Hoja1");

	// Guardar el archivo en disco
	XLSX.writeFile(workbook, "Errores_"+fechaFile +".xlsx");
	console.log("¡Archivo Excel creado con éxito!");
}


function EnvioMultiple(){
	datosEnviar=[];
	const chkcant=document.querySelectorAll('table input[type="checkbox"]:checked');
	if(chkcant.length!=0){
		document.querySelectorAll('table input[type="checkbox"]:checked').forEach(checkbox => {
			var par=lcdtm(checkbox.id);
			enviarDatos({q:'solicitar',info:par},checkbox.id);
		});
		console.log(datosEnviar);
	}
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


document.getElementById('fileInput').addEventListener('change', function(event) {
    let file = event.target.files[0];
    let reader = new FileReader();
    
    reader.onload = function(e) {
		nfila=filaInicialData-1;
        let data = new Uint8Array(e.target.result);
        let workbook = XLSX.read(data, { type: 'array' });
        let sheet = workbook.Sheets[workbook.SheetNames[0]];
        jsonData = XLSX.utils.sheet_to_json(sheet, { header: 1, defval: '',blankrows: false });
        
        if (jsonData.length > 0) {
            let tableHeader = document.getElementById('tableHeader');
            let tableBody = document.querySelector('#dataTable tbody');
            tableHeader.innerHTML = '';
            tableBody.innerHTML = '';
            // Cabecera --------
			// let thcheck = document.createElement('th');
            // thcheck.textContent = 'Check';
			// tableHeader.appendChild(thcheck);
            let thAction = document.createElement('th');
            thAction.textContent = 'Acción';
            tableHeader.appendChild(thAction);
            jsonData[filaNombreColumnas-1].forEach(header => {
                let th = document.createElement('th');
                th.textContent = header;
                tableHeader.appendChild(th);
            });
            
            //-------------------
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
                    // td.id=
					td.textContent = row[index] || '';
                    tr.appendChild(td);
                });
                
            });
        }
    };   
    reader.readAsArrayBuffer(file);
});

function enviarDatos(datos,nfila) {

  const jsonString = JSON.stringify(datos);
  const xhr = new XMLHttpRequest();
  
  xhr.open("POST", "vista/ajax/AjaxClAfip.php");
  xhr.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
  xhr.send(jsonString);
  xhr.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      
      var respuesta =JSON.parse( this.responseText);
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
		let boton=document.getElementById("b_"+nfila);
		boton.disabled=true;
		let chk=document.getElementById(+nfila);
		chk.disabled=true;
      }
    }
  }
  if(dataErr.length>0){
  	escribidor(dataErr);
	dataErr=[];
  }
}

function consultarDatos(tipo){
    // const jsonString = JSON.stringify("");
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
			dataErr.push ({ Fila: nfila, Codigo:error.Code, Error: error.Msg }),
			console.log(dataErr);

			container.innerHTML += errorHTML;
		});
	}else{
			const errorHTML = `
				<div class="error-card">
					<div class="error-code">Error ${data.Code}</div>
					<div class="error-message">${data.Msg}</div>
				</div>
			`;
			dataErr.push ({ Fila: nfila, Codigo:data.Code, Error: data.Msg }),
			container.innerHTML =errorHTML;
			console.log(dataErr);
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

	myModal=new bootstrap.Modal(document.getElementById('Modal'));
	// myModal.show();
	
}
  
  