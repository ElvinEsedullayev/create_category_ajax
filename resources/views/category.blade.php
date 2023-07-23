<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Bootstrap demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
     <link
      rel="stylesheet"
      href="https://cdn.datatables.net/1.11.3/css/dataTables.bootstrap5.min.css"
    />
    <script
  src="https://code.jquery.com/jquery-3.7.0.js"
  integrity="sha256-JlqSTELeR4TLqP0OG9dxM7yDPqX1ox/HfgiSLBj8+kM="
  crossorigin="anonymous"></script>
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  </head>
  <body>
 
<!-- Modal -->
<div class="modal fade ajax-model" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <form id="ajaxForm">
    
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="modal-title"></h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
        
            <div class="form-group mb-3">
              <label for="">Name</label>
              <input type="hidden" name="category_id" id="category_id">
            <input type="text" name="name" id="name" class="form-control">
            <span id="errorName" class="text-danger error-message"></span>
            </div>
            
          <div class="form-group mb-3">
              <label for="">Category</label>
            <select name="type" id="type" class="form-control mb-3">
              <option disabled selected>Choose Option</option>
              <option value="electronics">Electronics</option>
            </select>
            <span id="errorType" class="text-danger error-message"></span>
            </div>
        
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" id="saveBtn"></button>
        </div>
      </div>
    </div>
  </form>
</div>
    <div class="row">
      <div class="col-md-6 offset-3" style="margin-top: 100px">
        <a class="btn btn-info mb-3" href="" data-bs-toggle="modal" data-bs-target="#exampleModal" id="add-category">Add Category</a>
        <table class="table" id="category-table">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Name</th>
            <th scope="col">Type</th>
            <th scope="col">Action</th>
          </tr>
        </thead>
        <tbody>
   
        </tbody>
      </table>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
    {{-- <script src="https://cdn.datatables.net/1.11.3/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script> --}}
    <script src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.min.js"></script>
    {{-- <script>
      $(document).ready(function () {
        $('#datatable').DataTable();
      });
    </script> --}}
    <script>
      $(document).ready(function(){
          $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var table = $('#category-table').DataTable({
          processing: true,
          serverSide: true,
          ajax: '{{route("category.index")}}',
          columns: [
            {data : 'id'},
            {data : 'name'},
            {data : 'type'},
            {data : 'action', name: 'action', orderable: false, searchable: false},
          ]
        })
          $('#modal-title').html('Add Category');
          $('#saveBtn').html('Save Category');
         
          var form = $("#ajaxForm")[0];
          //console.log(form)
          $('#saveBtn').click(function(){
            $('#saveBtn').attr('disabled',true);
            $('.error-message').html('');
            //var name = $('#name').val();
            // console.log(name)
            
            // var type = $('#type').val()
            // //console.log(type)
            var form_data = new FormData(form);
            //console.log(form_data)
            $.ajax({
              url: '{{route("store.category")}}',
              method: 'POST',   
              processData: false,
              contentType: false,
              data: form_data,
              success: function(response){
                table.draw();//edit olanda refresh olmadan deyisikliyi gosterir
                $('#saveBtn').attr('disabled',false);
                $('#name').val('');
                $('#type').val('');
                $('#category_id').val('');
                $('.ajax-model').modal('hide');
                if(response){
                  $('#saveBtn').attr('disabled',false);
                  //console.log(response.success)
                  swal("Success", response.success, "success");
                }
                
              },
              error: function(error){
                //console.log(error)
                if(error){
                  //console.log(error.responseJSON.errors.name);
                 $('#errorName').html(error.responseJSON.errors.name); 
                 $('#errorType').html(error.responseJSON.errors.type); 
                }
                
              }
            });
          });

          //edit button code
          $('body').on('click','.editButton',function(){
            //console.log('clicked')
            var id = $(this).data('id')
            //console.log(id)
            $.ajax({
              url: '{{url("category" , '')}}'+'/'+id+'/'+'edit',
              method: 'GET',
              success: function(response){
               $('.ajax-model').modal('show');
               $('#modal-title').html('Edit Category');
               $('#saveBtn').html('Update Category');

               $('#category_id').val(response.id);
               $('#name').val(response.name);
               var type = capitalizeFirstLetter(response.type)
               $("#type").empty().append('<option selected value="'+response.type+'">'+type+'</option>');
              },
              error: function(error){
                console.log(error)
              }
            })
          })


          //delete button code
          $('body').on('click','.delButton',function(){
            //console.log('clicked')
            var id = $(this).data('id')
            //console.log(id)
            if(confirm('Are you sure deleted category?')){
                $.ajax({
              url: '{{url("category/destroy/" , '')}}'+'/'+id+'/',
              method: 'GET',
              success: function(response){
                table.draw();//edit olanda refresh olmadan deyisikliyi gosterir
                swal("Success", response.success, "success");
              },
              error: function(error){
                console.log(error)
              }
            })
            }
          })


          //add category
          $('#add-category').click(function(){
            $('#modal-title').html('Create Category');
            $('#saveBtn').html('Save Category');
          })
          function capitalizeFirstLetter(string){
            return string.charAt(0).toUpperCase() + string.slice(1);
          }
      });
    </script>
  </body>
</html>