<?php
include 'head.php';
?>
    <header class="cabecera">
     
     <!-- <img src="./imagenes/mago.jpg" alt="Logo" size  width="100"> -->
     <span >FACTURACION ELECTRONICA - AFIP</span>

 </header>
 <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
     <div class="container-fluid">
       <a class="navbar-brand" href="#">
         <!-- <img src="imagenes/mago.jpg" alt="" width="60" height="48"> -->
       </a>
       <div class="collapse navbar-collapse" id="navbarSupportedContent">
         <ul class="navbar-nav me-auto mb-2 mb-lg-0">
           <li class="nav-item">
             <a class="nav-link active" aria-current="page" href="index.html">Inicio</a>
           </li>
           <li class="nav-item dropdown">
             <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
               Consultas
             </a>
              <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                 <li><a class="dropdown-item" href="#">Tipos de Comprobante</a></li>
                 <li><a class="dropdown-item" href="#">Tipos de concepto</a></li>
                 <li><a class="dropdown-item" href="#">Condicion IVA Receptor</a></li>
                 <li><a class="dropdown-item" href="#">Tipos de documento</a></li>
                 <li><a class="dropdown-item" href="vista/Consulta.php">Consultar Comprobantes</a></li>
              </ul>
           </li>
         </ul>
         
         <div class="mb-3 ms-auto">
           <input type="file" id="fileInput" class="form-control " accept=".xlsx, .xls">
         </div>
       </div>

     </div>
 </nav>