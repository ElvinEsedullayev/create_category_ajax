<!DOCTYPE html>
<html lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
		<title>Jquery Fullcalandar</title>
		{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.css" />
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha.6/css/bootstrap.css" />
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script>
    

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    {{-- sweet alert --}}
      {{-- <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script> --}} 
       <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.css" />
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script>
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
</head>
<body>
{{-- <!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
  Launch demo modal
</button> --}}

<!-- Modal -->
<div class="modal fade" id="bookingModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Booking title</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="text" name="title" id="title" class="form-control">
        <span id="titleError" class="text-danger"></span>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button id="saveBtn" type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>
  <div class="container">
    <div class="row">
      <div class="col-md-12">
        <h3 class="text-center mt-5">Fullcalandar</h3>
        <div class="col-md-11 offset-1 mt-5 mb-5">
          <div id="calendar">

          </div>
        </div>
      </div>
    </div>
  </div>

 <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
  <script>
    $(document).ready(function(){
      $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
      var booking = @json($events);
      //console.log(events)
      $('#calendar').fullCalendar({
        header:{
          left: 'prev, next today',
          center: 'title',
           right:'month,agendaWeek,agendaDay',
        },
        events: booking,
        selectable: true,
        selectHelper: true,
        defaultView: 'month',
        select: function(start,end,allDays){
          //console.log(start)
          $('#bookingModal').modal('toggle');

          $('#saveBtn').click(function(){
            var title = $('#title').val();
            var start_date = moment(start).format('YYYY-MM-DD');
            var end_date = moment(end).format('YYYY-MM-DD');
            console.log(end_date)
            $.ajax({
              url: '{{route("store.calendar")}}',
              type: 'POST',   
              dataType: 'json',
              data: {title,start_date,end_date},
              success: function(response){
                //table.draw();
                $('#bookingModal').modal('hide');
                //table.draw();
                $('#calendar').fullCalendar('renderEvent',{
                  'title': response.title,
                  'start_date': response.start,
                  'end_date': response.end,
                  'color' : response.color
                });
              },  
              error: function(error){
                if(error.responseJSON.errors){
                  $('#titleError').html(error.responseJSON.errors.title);
                }
                  
              }
            });
          });
        },
        editable: true,
        eventDrop: function(event){
          var id = event.id;
          var start_date = moment(event.start).format('YYYY-MM-DD');
          var end_date = moment(event.end).format('YYYY-MM-DD');

           $.ajax({
              url: '{{route("update.calendar","")}}'+'/'+id,
              type: 'PATCH',   
              dataType: 'json',
              data: {start_date,end_date},
              success: function(response){
                  swal("God Job", "Event updated", "success");
              },  
              error: function(error){
                console.log(error)
            }         
            });
        },
        eventClick:function(event){
          var id = event.id;
          if(confirm('Are you sure want to remove it?')){
              $.ajax({
              url: '{{route("destroy.calendar","")}}'+'/'+id,
              type: 'DELETE',   
              dataType: 'json',
              success: function(response){
                  $('#calendar').fullCalendar('removeEvents',response);
                  swal("God Job", "Event updated", "success");
              },  
              error: function(error){
                console.log(error)
            }         
            });
          }
        },
         selectAllow: function(event)
                {
                    return moment(event.start).utcOffset(false).isSame(moment(event.end).subtract(1, 'second').utcOffset(false), 'day');
                },

      });
      $("#bookingModal").on("hidden.bs.modal", function () {
          $('#saveBtn').unbind();
      });
            // $('.fc-event').css('font-size', '13px');
            // $('.fc-event').css('width', '20px');
            // $('.fc-event').css('border-radius', '50%');
    });
  </script>
</body>
</html>