// const xhr = new XMLHttpRequest();
// xhr.open("POST", "vista/ajax/AjaxClAfip.php");
// xhr.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
// xhr.send(jsonString);
// xhr.onreadystatechange = function () {
//   if (this.readyState == 4 && this.status == 200) {
const urlParams = new URLSearchParams(window.location.search);
const nombre = urlParams.get('consulta');
console.log(nombre);
function enviarDatos() {
    const xhr = new XMLHttpRequest();
    xhr.open("GET", "../vista/ajax/AjaxClAfip.php?q=tipoComprobante");
    xhr.onload = () => {      
        // console.log(JSON.stringify(this.responseText));
        if (xhr.status >= 200 && xhr.status ==200) {
            // La solicitud fue exitosa
            try {
                mostrarTabla(JSON.parse(xhr.responseText));
              
            } catch (e) {
                window.alert("no anduvio")
            }
        }
    }
    xhr.send(null);
}

function mostrarTabla(data) {
    const contenedor = document.getElementById('json-data');
    
    // Crear tabla
    const tabla = document.createElement('table');
    
    // Crear encabezado
    const thead = document.createElement('thead');
    const headerRow = document.createElement('tr');
    const conceptos=data.coleccion.ResultGet.CbteTipo;
    // Obtener las claves del primer objeto para los encabezados
    const headers = Object.keys(conceptos[0]);
    
    headers.forEach(header => {
        const th = document.createElement('th');
        th.textContent = header.trim(); // Eliminar espacios en blanco
        headerRow.appendChild(th);
    });
    
    thead.appendChild(headerRow);
    tabla.appendChild(thead);
    
    // Crear cuerpo de la tabla
    const tbody = document.createElement('tbody');
    
    conceptos.forEach(concepto => {
        const row = document.createElement('tr');
        
        headers.forEach(header => {
            const td = document.createElement('td');
            // Mostrar "Nulo" si el valor es "NULL", de lo contrario mostrar el valor
            td.textContent = concepto[header] === "NULL" ? "Nulo" : concepto[header];
            row.appendChild(td);
        });
        
        tbody.appendChild(row);
    });
    
    tabla.appendChild(tbody);
    contenedor.appendChild(tabla);
}

// Llamar a la función con los datos
// mostrarTabla(data.ConceptoTipo);
enviarDatos();