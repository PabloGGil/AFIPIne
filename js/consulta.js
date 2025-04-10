document.getElementById('fileInput').addEventListener('change', function(event) {
    let file = event.target.files[0];
    let reader = new FileReader();
    
    reader.onload = function(e) {
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
            jsonData[1].forEach(header => {
                let th = document.createElement('th');
                th.textContent = header;
                tableHeader.appendChild(th);
            });
            
            //-------------------
            
            jsonData.slice(2).forEach(row => {
                let tr = document.createElement('tr');
                
                let tdAction = document.createElement('td');
                let btn = document.createElement('button');
                btn.textContent = 'Enviar';
                btn.classList.add('btn', 'btn-primary', 'btn-sm');
                btn.onclick = function() {
                    enviarDatos(row);
                };
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
    $.ajax({
        url: 'AjaxClAfip.php',
        type: 'POST',
        data: { datos: JSON.stringify(datos) },
        success: function(response) {
            alert('Datos enviados con éxito: ' + response);
        },
        error: function() {
            alert('Error al enviar los datos');
        }
    });
}