<?php
 include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/panel/is_logged.php';
// Inclui o arquivo de conexão com o banco de dados
include "conexao.php";
$upload_dir = $_SERVER['DOCUMENT_ROOT'] . '/images/uploads-services/';
// Função para gerar JSON (opcional, se necessário)
function generateJson($conn)
{
    $query = "SELECT * FROM granna80_bdlinks.typofx_services";
    $result = $conn->query($query);

    $data = [];
    while ($row = $result->fetch_assoc()) {
        // Cria uma estrutura associativa para cada linha
        $service = [
            'id' => $row['id'],
            'name' => $row['name'],
            'logo' => $row['logo'],
            'link' => $row['link'],
            'date' => $row['date'],
            'created_at' => $row['created_at'],
            'created_by' => $row['created_by'],
            'visible' => $row['visible']
        ];
        $data[] = $service; // Adiciona ao array de dados
    }

    // Salva o JSON em um arquivo
    file_put_contents('data.json', json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_NUMERIC_CHECK));
}

// Gera o JSON ao carregar a página
generateJson($conn);

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Service Management</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-5">
        <!-- Logo -->
        <a href="<?php include $_SERVER['DOCUMENT_ROOT'] . '/plataforma/panel/main.php';?>">[Back]</a>
        <a href="service_editable_items.php">[Editable items]</a>

        <a href="data.json" target="_blank">[JSON]</a>

        <!-- Add New Service Button -->
        <button type="button" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addServiceModal">
            <i class="fas fa-plus"></i> Add New Service
        </button>

        <!-- Table -->
        <table id="servicesTable" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Logo</th>
        
                    <th>Date</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                    <th>Visible</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Fetch data from the database
                $query = "SELECT * FROM granna80_bdlinks.typofx_services";
                $result = $conn->query($query);

                $cont = 1;
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>
                    <td>{$cont}</td>
                    <td><a href='" . htmlspecialchars($row['link'], ENT_QUOTES, 'UTF-8') . "' target='_blank'>{$row['name']}</a></td>
                    <td><img src='/images/uploads-services/{$row['logo']}' alt='Logo' style='height: 50px;'></td>
             
                    <td>{$row['date']}</td>
                    
                    <td>{$row['created_at']}</td>
                    <td>{$row['created_by']}</td>
                    <td>" . ($row['visible'] ? 'Yes' : 'No') . "</td>
                    <td>
                        <button class='btn btn-sm btn-warning' data-bs-toggle='modal' 
                            data-bs-target='#editServiceModal' data-id='{$row['id']}'  data-link='{$row['link']}'>
                            <i class='fas fa-edit'></i>
                        </button>
                        <button class='btn btn-sm btn-danger' data-bs-toggle='modal' 
                            data-bs-target='#deleteServiceModal' data-id='{$row['id']}'>
                            <i class='fas fa-trash'></i>
                        </button>
                    </td>
                  </tr>";
                  $cont++;
                }
                ?>
            </tbody>
        </table>
    </div>

 <!-- Add Service Modal -->
 <div class="modal fade" id="addServiceModal" tabindex="-1" aria-labelledby="addServiceModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addServiceModalLabel">Add New Service</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="addServiceForm" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="logo" class="form-label">Logo (JPEG, PNG, SVG)</label>
                            <input type="file" class="form-control" id="logo" name="logo" accept=".jpg,.jpeg,.png,.svg" >
                        </div>
                        <div class="mb-3">
                            <label for="link" class="form-label">Link</label>
                            <input type="text" class="form-control" id="link" name="link">
                        </div>
                        <div class="mb-3">
                            <label for="date" class="form-label">Date</label>
                            <input type="date" class="form-control" id="date" name="date">
                        </div>
                        <div class="mb-3">
                            <label for="created_by" class="form-label">Updated At</label>
                            <input type="text" value="<?php echo $userEmail; ?>" class="form-control" id="created_by" name="created_by">
                        </div>
                        <div class="mb-3">
                            <label for="visible" class="form-label">Visible</label>
                            <select class="form-control" id="visible" name="visible">
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="saveService">Save</button>
                </div>
            </div>
        </div>
    </div>

<!-- Edit Service Modal -->
<div class="modal fade" id="editServiceModal" tabindex="-1" aria-labelledby="editServiceModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editServiceModalLabel">Edit Service</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="editServiceForm" enctype="multipart/form-data">
                    <input type="hidden" id="edit_id" name="id">
                    <input type="hidden" id="edit_current_logo" name="current_logo"> <!-- Campo oculto para a imagem atual -->
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_logo" class="form-label">Logo (JPEG, PNG, SVG)</label>
                        <input type="file" class="form-control" id="edit_logo" name="logo" accept=".jpg,.jpeg,.png,.svg">
                        
                        <!-- Exibe a imagem atual -->
                        <div class="mt-2">
                            <img id="edit_current_logo_preview" src="" alt="Current Logo" style="max-width: 100px; height: auto;">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_link" class="form-label">Link</label>
                        <input type="text" class="form-control" id="edit_link" name="link">
                    </div>
                    <div class="mb-3">
                        <label for="edit_date" class="form-label">Date</label>
                        <input type="date" class="form-control" id="edit_date" name="date">
                    </div>
                    <div class="mb-3">
                        <label for="edit_created_by" class="form-label">Updated At</label>
                        <input type="text" value="<?php echo $userEmail; ?>"  class="form-control" id="edit_created_by" name="created_by" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_visible" class="form-label">Visible</label>
                        <select class="form-control" id="edit_visible" name="visible">
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="updateService">Update</button>
            </div>
        </div>
    </div>
</div>

   <!-- Delete Service Modal -->
<div class="modal fade" id="deleteServiceModal" tabindex="-1" aria-labelledby="deleteServiceModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteServiceModalLabel">Delete Service</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this service?</p>
                <input type="hidden" id="delete_id">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Delete</button>
            </div>
        </div>
    </div>
</div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <!-- jQuery -->
   
    <!-- Custom Script -->
    <script>
    $(document).ready(function() {
        // Initialize DataTable
        const table = $('#servicesTable').DataTable({
            "paging": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "lengthMenu": [10, 25, 50, 100]
        });

        // Save new service
        $('#saveService').click(function() {
            const formData = new FormData($('#addServiceForm')[0]);
            $.ajax({
                url: 'save_service.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    location.reload(); // Recarrega a página para refletir as mudanças
                }
            });
        });

        // Edit service
        $('#editServiceModal').on('show.bs.modal', function(event) {
            const button = $(event.relatedTarget);
            const id = button.data('id');
            const row = button.closest('tr');

        
            const name = row.find('td:eq(1)').text(); // Nome
            const logo = row.find('td:eq(2) img').attr('src'); // Caminho da imagem
            
            const date = row.find('td:eq(3)').text(); // Data
        
            const visible = row.find('td:eq(6)').text() === 'Yes' ? 1 : 0; // Visível
            const link = button.data('link');

            $('#edit_id').val(id);
            $('#edit_name').val(name);
            $('#edit_current_logo').val(logo); // Armazena o caminho da imagem atual
            $('#edit_current_logo_preview').attr('src', logo); // Exibe a imagem atual
            $('#edit_link').val(link);
            $('#edit_date').val(date);
         
            $('#edit_visible').val(visible);
        });

        // Update service
        $('#updateService').click(function() {
            const formData = new FormData($('#editServiceForm')[0]);
            $.ajax({
                url: 'update_service.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    location.reload(); // Recarrega a página para refletir as mudanças
                }
            });
        });

        // Delete service
        $('#deleteServiceModal').on('show.bs.modal', function(event) {
            const button = $(event.relatedTarget);
            const id = button.data('id');
            $('#delete_id').val(id);
        });

        $('#confirmDelete').click(function() {
            const id = $('#delete_id').val();
            $.post('delete_service.php', { id: id }, function(response) {
                location.reload(); // Recarrega a página para refletir as mudanças
            });
        });
    });
</script>
</body>

</html>