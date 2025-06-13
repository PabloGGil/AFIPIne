<?php
include "navbar.php";
?>

<body>

<body>
    <div id="json-data"></div>
    <main  class="bodyHome">
  
    <!-- Modal  -->
      <div class="modal fade" id="productoModal" tabindex="-1" aria-labelledby="productoModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="productoModalLabel">Detalles del Producto</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <!-- Aquí se mostrarán los datos -->
              <p id="modal-content"></p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
          </div>
        </div>
      </div>
      <div class="table-responsive">

      <table class="table  table-striped table-bordered text-center" id="dataTable">
        <thead class="table-dark" >
            <tr id="tableHeader"></tr>
        </thead>
        <tbody></tbody>
    </table>
  </div>
    </main>   
    <footer>
      
      <nav class="navbar fixed-bottom  navbar-dark bg-dark">
        <div class="container-fluid">
          
          <div class="col-md-6 d-flex align-items-center">
            <a href="/" class="mb-3 me-2 mb-md-0 text-muted text-decoration-none lh-1">
              <svg class="bi" width="30" height="24">
                <use xlink:href="#bootstrap" />
              </svg>
            </a>
            <span class="mb-3 mb-md-0 text-muted">&copy; 2025 @2707 </span>
          </div>
    
          <ul class="col-md-6 justify-content-end list-unstyled d-flex">
            <li class="ms-3"><a class="bi bi-map" href="ubicacion.html"><svg class="bi" width="20"  height="24"></svg></a></li
            <li class="ms-3"><a class="bi bi-youtube" href=""><svg class="bi" width="20" height="24"></svg></a></li>
            <li class="ms-3"><a class="bi bi-instagram" href=""><svg class="bi" width="24" height="24">
                  <!-- <use xlink:href="#instagram" /> -->
                </svg></a></li>
            <li class="ms-3"><a class="bi bi-facebook" href=""><svg class="bi" width="24" height="24"></svg></a></li>
            
          </ul>
    
      </nav>

   
    </footer>
    <!-- <script src="js/api.js"> </script>  -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="../js/consulta.js"></script>
  </body>
</html>
