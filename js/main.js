 
 const filaInicialData=4;
 const filaNombreColumnas=3;
 var parametrosFe= {
	Auth: {
		Token: "",
		Sign: "",
		Cuit: ""
	},
	FeCAEReq: {
		FeCabReq: {
			CantReg: 1,
			PtoVta: 2,
			CbteTipo: 11
		},
		FeDetReq: {
			FECAEDetRequest: {
				Concepto: 1 ,
				DocTipo: 80,
				DocNro: 30711529280,
				CbteDesde: 0,
				CbteHasta: 0,
				CbteFch: "20250404",
				ImpTotal: 200,
				ImpTotConc:0 ,
				ImpNeto: 200,
				ImpOpEx: 0,
				ImpTrib: 0,
			  	ImpIVA: 0,
				FchServDesde: "",
				FchServHasta: "",
				FchVtoPago: "",
				MonId: "PES",
				MonCotiz: 1,
		 		CbtesAsoc:
				[
		// 		{	Tipo: 11,
		// 			PtoVta: 2,
		// 			Nro: 4160,
		// 			Cuit: "",
		// 			CbteFch: "20250330"
		// 		},
        // {
		// 			Tipo: 11,
		// 			PtoVta: 3,
		// 			Nro: 99,
		// 			Cuit: "",
		// 			CbteFch: "20250330"
				// }
				],
		 		Tributos:[
				//  {
				// 		Id: "",
				// 		Desc: "",
				// 		BaseImp: "",
				// 		Alic: "",
				// 		Importe: ""
				// }
				],
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
			},
		},
	},
 };
 
 function  recolectarDatosTabla(n) {
	parametrosFe.FeCAEReq.FeCabReq.PtoVta=jsonData[n][3];
	parametrosFe.FeCAEReq.FeCabReq.CbteTipo=jsonData[n][1];

	parametrosFe.FeCAEReq.FeDetReq.FECAEDetRequest.Concepto=jsonData[n][2];
	parametrosFe.FeCAEReq.FeDetReq.FECAEDetRequest.DocTipo=jsonData[n][7];
	parametrosFe.FeCAEReq.FeDetReq.FECAEDetRequest.DocNro=jsonData[n][8];
	// Se CbteDesde se busca desde el php consultando el proximo comprobante
	// parametrosFe.FeCAEReq.FeDetReq.FECAEDetRequest.CbteDesde=jsonData[n][3];
	// parametrosFe.FeCAEReq.FeDetReq.FECAEDetRequest.CbteHasta=jsonData[n][3];
	anio=jsonData[n][0].split("/")[2];
	mes=jsonData[n][0].split("/")[1];
	dia=jsonData[n][0].split("/")[0];
	parametrosFe.FeCAEReq.FeDetReq.FECAEDetRequest.CbteFch=jsonData[n][0];//anio+mes+dia;
	parametrosFe.FeCAEReq.FeDetReq.FECAEDetRequest.ImpTotal=jsonData[n][17];
	parametrosFe.FeCAEReq.FeDetReq.FECAEDetRequest.ImpTotConc=jsonData[n][3];
	parametrosFe.FeCAEReq.FeDetReq.FECAEDetRequest.ImpNeto=jsonData[n][3];
	parametrosFe.FeCAEReq.FeDetReq.FECAEDetRequest.ImpOpEx=jsonData[n][3];
	parametrosFe.FeCAEReq.FeDetReq.FECAEDetRequest.ImpTrib=jsonData[n][3];
	parametrosFe.FeCAEReq.FeDetReq.FECAEDetRequest.ImpIVA= jsonData[n][16]=="" ? jsonData[n][16] : 0;
	parametrosFe.FeCAEReq.FeDetReq.FECAEDetRequest.FchServDesde=jsonData[n][3];
	parametrosFe.FeCAEReq.FeDetReq.FECAEDetRequest.FchServHasta=jsonData[n][3];
	parametrosFe.FeCAEReq.FeDetReq.FECAEDetRequest.FchVtoPago=jsonData[n][3];
	// parametrosFe.FeCAEReq.FeDetReq.FeCAEDetRequest.MonId=jsonData[n][3];
	// parametrosFe.FeCAEReq.FeDetReq.FeCAEDetRequest.MonCotiz=jsonData[n][3];

	return parametrosFe;
}
let jsonData=[];

// console.log(parametrosFe.Auth.Token);

document.getElementById('fileInput').addEventListener('change', function(event) {
    let file = event.target.files[0];
    let reader = new FileReader();
    
    reader.onload = function(e) {
		nfila=filaInicialData;
        let data = new Uint8Array(e.target.result);
        let workbook = XLSX.read(data, { type: 'array' });
        let sheet = workbook.Sheets[workbook.SheetNames[0]];
        jsonData = XLSX.utils.sheet_to_json(sheet, { header: 1, defval: '' });
        
        if (jsonData.length > 0) {
            let tableHeader = document.getElementById('tableHeader');
            let tableBody = document.querySelector('#dataTable tbody');
            tableHeader.innerHTML = '';
            tableBody.innerHTML = '';
            // Cabecera --------
            let thAction = document.createElement('th');
            thAction.textContent = 'Acción';
            tableHeader.appendChild(thAction);
            jsonData[filaNombreColumnas-1].forEach(header => {
                let th = document.createElement('th');
                th.textContent = header;
                tableHeader.appendChild(th);
            });
            
            //-------------------
            
            jsonData.slice(filaInicialData).forEach(row => {
                let tr = document.createElement('tr');
                tr.id='fila_'+nfila;
                let tdAction = document.createElement('td');
                let btn = document.createElement('button');
                btn.id="b_"+ nfila;
				btn.textContent = 'Enviar';
                btn.classList.add('btn', 'btn-primary', 'btn-sm');
                btn.onclick = function() {
                    const datosTabla = recolectarDatosTabla(btn.id.split("_")[1]);
					// enviarDatos();
                    // mandanga={q:'solicitar',info:parametrosFe}
                    enviarDatos({q:'solicitar',info:parametrosFe});
                    // consultarDatos('tipoDocumento');
                };
				nfila++;
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

function enviarDatos(datos) {
//   console.table(jsonData[datos]);
  const jsonString = JSON.stringify(datos);
  const xhr = new XMLHttpRequest();
  
//   console.log(datosTabla);
  

  xhr.open("POST", "vista/ajax/AjaxClAfip.php");
  xhr.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
  xhr.send(jsonString);
  xhr.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      
      var respuesta =JSON.parse( this.responseText);
      console.log(respuesta);
	  
	  if(Object.hasOwn(respuesta.Errors, 'Err')){
		errores=respuesta.Errors.Err;
		console.log(respuesta.Errors.Err)
		// let errores="";
		// for (const error of errores) {
		// 	errores=errores +(`Código: ${error.Code} -  Mensaje: ${error.Msg} \n`);
		// }
		MostrarErrores(errores,1);
		
	  } else if(respuesta.FeDetResp.FECAEDetResponse.Resultado!="A"){
    	if(respuesta.FeDetResp.FECAEDetResponse.Observaciones.hasOwnProperty('Obs')){
			const obs=respuesta.FeDetResp.FECAEDetResponse.Observaciones.Obs
			// let observaciones="";
			// for (const error of obs) {
			// 	observaciones=observaciones +(`Código: ${error.Code} -  Mensaje: ${error.Msg} \n`);
			// }
			// window.alert(observaciones);
			MostrarErrores(obs,2);
		}
      }
      else {
        window.alert("recibi rta");
        // listar();
      }
    }
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

function MostrarErrores(data,tipo){
	const container = document.getElementById('errores-container');
	const totalErrores = document.getElementById('total-errores');
	const modal=document.getElementById('Modal');

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
		container.innerHTML += errorHTML;
	});
	if (tipo==1){
		let zz=document.getElementById("mensaje");
		zz.innerText="Errores informados";
		let boton=document.getElementById("fila_4");
		boton.style.backgroundColor='red';
	}else{
		let zz=document.getElementById("mensaje");
		zz.innerText="Observaciones informadas";
		let boton=document.getElementById("fila_4");
		boton.style.backgroundColor='orange';
	}

	
	myModal=new bootstrap.Modal(document.getElementById('Modal'));
	myModal.show();
	
}
  
  