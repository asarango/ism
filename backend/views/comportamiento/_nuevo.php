<!-- Button trigger modal -->
<td>
    <a class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModalTr">
      N
    </a>
</td>
    
<!-- Modal -->
<div class="modal fade" id="exampleModalTr" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" style="background-color: white">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Información nuevo estudiante</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        
          <?= $alumno['last_name'] ?>
          
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>
