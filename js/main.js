 
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
				[{
					Tipo: 11,
					PtoVta: 2,
					Nro: 4160,
					Cuit: "",
					CbteFch: "20250330"
				},
        {
					Tipo: 11,
					PtoVta: 3,
					Nro: 99,
					Cuit: "",
					CbteFch: "20250330"
				}],
				Tributos:null,
				// [ {
				// 		Id: "",
				// 		Desc: "",
				// 		BaseImp: "",
				// 		Alic: "",
				// 		Importe: ""
				// }],
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
 


// console.log(parametrosFe.Auth.Token);

document.getElementById('fileInput').addEventListener('change', function(event) {
    let file = event.target.files[0];
    let reader = new FileReader();
    
    reader.onload = function(e) {
		nfila=filaInicialData;
        let data = new Uint8Array(e.target.result);
        let workbook = XLSX.read(data, { type: 'array' });
        let sheet = workbook.Sheets[workbook.SheetNames[0]];
        let jsonData = XLSX.utils.sheet_to_json(sheet, { header: 1, defval: '' });
        
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
                
                let tdAction = document.createElement('td');
                let btn = document.createElement('button');
                btn.id="b_"+nfila;
				btn.textContent = 'Enviar';
                btn.classList.add('btn', 'btn-primary', 'btn-sm');
                btn.onclick = function() {
                    enviarDatos(btn.id.split("_")[1]);
                    mandanga={q:'solicitar',info:parametrosFe}
                    //enviarDatos(mandanga);
                    // consultarDatos('tipoDocumento');
                };
				nfila++;
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

function enviarDatos(datos) {
  const jsonString = JSON.stringify(datos);
  const xhr = new XMLHttpRequest();
  const datosTabla = recolectarDatosTabla(datos);
  console.log(datosTabla);
  
  xhr.open("POST", "vista/ajax/AjaxClAfip.php");
  xhr.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
  xhr.send(jsonString);
  xhr.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      
      var respuesta =JSON.parse( this.responseText);
      // if (respuesta.Errors.length!=0){
      if (respuesta.FeDetResp.FECAEDetResponse.Resultado!="A"){
      
        window.alert(respuesta.FeDetResp.FECAEDetResponse.Observaciones.Obs[0].Msg);
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

function  recolectarDatosTabla(n) {
		const tabla = document.querySelector('table');
		const filas = tabla.querySelectorAll('tr');
		const headers = Array.from(filas[0].querySelectorAll('th, td')).map(h => h.textContent.trim());
		const datos = [];
		
		// for (let i = 1; i < filas.length; i++) {
		//   const celdas = filas[i].querySelectorAll('td');
		const celdas = filas[n].querySelectorAll('td');  
		const filaData = {};
		  
		  celdas.forEach((celda, index) => {
			filaData[headers[index]] = celda.textContent.trim();
		  });
		  
		//   datos.push(filaData);
		// }
		
		// return datos;
		return filaData;
	  }
  
  