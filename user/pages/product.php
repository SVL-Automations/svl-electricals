<?php

include("sessioncheck.php");
date_default_timezone_set('Asia/Kolkata');

$userid = $_SESSION['userid'];

if (isset($_POST['tabledata'])) {
  $data = new \stdClass();
  $result = mysqli_query($connection, "SET NAMES utf8");
  $result = mysqli_query($connection, "SELECT p.* FROM `product` as p ORDER BY p.status desc, p.name");
  $data->list = mysqli_fetch_all($result, MYSQLI_ASSOC);
  echo json_encode($data);
  exit();
}


//Add product
if (isset($_POST['Add'])) {

  $msg = new \stdClass();

  $name = mysqli_real_escape_string($connection, $_POST['name']);
  $shortname = mysqli_real_escape_string($connection, $_POST['shortname']);
  $details = mysqli_real_escape_string($connection, $_POST['details']);
  $rate = mysqli_real_escape_string($connection, $_POST['rate']);
  $saleprice = mysqli_real_escape_string($connection, $_POST['saleprice']);
  $hsnCode = mysqli_real_escape_string($connection, $_POST['hsnCode']);

  $res = mysqli_query($connection, "INSERT INTO `product`(`name`, `short_name`,`hsn`, `details`, `rate`, `saleprice`, `status`)
                                    VALUES('$name','$shortname','$hsnCode','$details','$rate','$saleprice','1')
                                    ");
  if ($res > 0) {
    $msg->value = 1;
    $msg->data = "Product Added Successfully";
    $msg->type = "alert alert-success alert-dismissible ";
  } else {
    $msg->value = 0;
    $msg->data = "Please Try Again or Product name or code already exist";
    $msg->type = "alert alert-danger alert-dismissible ";
  }
  echo json_encode($msg);
  exit();
}

//Edit product
if (isset($_POST['Edit'])) {
  $msg = new \stdClass();
  $result = mysqli_query($connection, "SET NAMES utf8");

  $name = mysqli_real_escape_string($connection, $_POST['editname']);
  $shortname = mysqli_real_escape_string($connection, $_POST['editshortname']);
  $details = mysqli_real_escape_string($connection, $_POST['editdetails']);
  $rate = mysqli_real_escape_string($connection, $_POST['editrate']);
  $saleprice = mysqli_real_escape_string($connection, $_POST['editsaleprice']);
  $id = mysqli_real_escape_string($connection, $_POST['id']);
  $hsnCode = mysqli_real_escape_string($connection, $_POST['edithsnCode']);


  $updaterain = mysqli_query($connection, "UPDATE `product` SET 
                                            `name`='$name',`short_name`='$shortname',`details`='$details',
                                            `rate`='$rate',`saleprice`='$saleprice',`hsn` = '$hsnCode'
                                            WHERE id = '$id'
                                        ");

  if ($updaterain > 0) {
    $msg->value = 1;
    $msg->data = "Product Update Successfully.";
    $msg->type = "alert alert-success alert-dismissible ";
  } else {
    $msg->value = 0;
    $msg->data = " Some data is missing or Please Try Again.";
    $msg->type = "alert alert-danger alert-dismissible ";
  }


  echo json_encode($msg);
  exit();
}

if (isset($_POST['delete'])) {
  $msg = new \stdClass();
  $result = mysqli_query($connection, "SET NAMES utf8");

  $id = mysqli_real_escape_string($connection, $_POST['deleteid']);

  $updaterain = mysqli_query($connection, "UPDATE `product` SET status = 0 WHERE id = '$id'");

  if ($updaterain > 0) {
    $msg->value = 1;
    $msg->data = "Product Delete Successfully.";
    $msg->type = "alert alert-success alert-dismissible ";
  } else {
    $msg->value = 0;
    $msg->data = " Some data is missing or Please Try Again.";
    $msg->type = "alert alert-danger alert-dismissible ";
  }


  echo json_encode($msg);
  exit();
}

?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title><?= $project ?> : Product Details </title>
  <link rel="icon" href="../../dist/img/small.png" type="image/x-icon">
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="../../bower_components/bootstrap/dist/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../../bower_components/font-awesome/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="../../bower_components/Ionicons/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../../dist/css/AdminLTE.min.css">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="../../dist/css/skins/_all-skins.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="../../bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  <style>
    tfoot input {
      width: 50%;
      padding: 3px;
      box-sizing: border-box;
    }
  </style>
</head>

<body class="hold-transition skin-blue sidebar-mini">
  <!-- Site wrapper -->
  <div class="wrapper">

    <?php include("header.php"); ?>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h4>
          <?= $project ?>
          <small><?= $slogan ?></small>
        </h4>
        <ol class="breadcrumb">
          <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
          <li><a href="#"> Product </a></li>
          <li class="active"> Add / Update </li>
        </ol>
      </section>

      <!-- Main content -->
      <section class="content">
        <div class="row">
          <div class="col-md-12">
            <!-- Default box -->
            <div class="box box-primary">
              <div class="box-header with-border">
                <h3 class="box-title">Product Details </h3>
                <a class="btn btn-social-icon btn-success pull-right" title="Add Product" data-toggle="modal" data-target="#modaladdproduct"><i class="fa fa-plus"></i></a>
              </div>
              <div class="alert " id="alertclass" style="display: none">
                <button type="button" class="close" onclick="$('#alertclass').hide()">×</button>
                <p id="msg"></p>
              </div>
              <!-- /.box-header -->
              <!-- form start -->
              <div class="box-body  table-responsive">
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th class='text-center'>SrNo </th>
                      <th class='text-center'>Name </th>
                      <th class='text-center'>Short name </th>
                      <th class='text-center'>HSN Code </th>
                      <th class='text-center'>Details </th>
                      <th class='text-center'>Rate </th>
                      <th class='text-center'>Sales Price </th>
                      <th class='text-center'>Update</th>
                    </tr>
                  </thead>
                  <tbody id="tbody">

                  </tbody>
                  <tfoot>
                    <tr>
                      <th class='text-center'>SrNo </th>
                      <th class='text-center'>Name </th>
                      <th class='text-center'>Short name </th>
                      <th class='text-center'>HSN Code </th>
                      <th class='text-center'>Details </th>
                      <th class='text-center'>Rate </th>
                      <th class='text-center'>Sales Price </th>
                      <th class='text-center'>Update</th>
                    </tr>
                  </tfoot>
                </table>
              </div>
            </div>
            <!-- /.box-body -->
            <!-- /.box-footer-->
          </div>
        </div>
      </section>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <!-- Add product modal -->
    <form id="addproduct" action="" method="post">
      <div class="modal fade" id="modaladdproduct" style="display: none;">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header bg-green">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span></button>
              <h4 class="modal-title">Add Product</h4>
            </div>
            <div class="modal-body">
              <div class="alert " id="addalertclass" style="display: none">
                <button type="button" class="close" onclick="$('#addalertclass').hide()">×</button>
                <p id="addmsg"></p>
              </div>

              <div class="form-group">
                <label for="exampleInputEmail1">Product Name</label>
                <input type="text" class="form-control" placeholder="Product Name" name="name" id="name" required pattern="[a-zA-Z0-9\s]+">
              </div>

              <div class="form-group">
                <label for="exampleInputEmail1">Short Name</label>
                <input type="text" class="form-control" placeholder="Product Short Name" name="shortname" id="shortname" required pattern="[a-zA-Z0-9\s]+">
              </div>

              <div class="form-group">
                <label for="exampleInputEmail1">HSN Code</label>
                <input type="text" class="form-control" placeholder="HSN Code" name="hsnCode" id="hsnCode" >
              </div>

              <div class="form-group">
                <label for="exampleInputEmail1">Details</label>
                <textarea class="form-control" rows="3" placeholder="Details" name="details" id="details"></textarea>
              </div>

              <div class="form-group">
                <label for="exampleInputEmail1">Purchase Rate</label>
                <input type="number" class="form-control" placeholder="Purchase Rate" id="rate" name="rate" required min="0">
              </div>

              <div class="form-group">
                <label for="exampleInputEmail1">Sales Rate</label>
                <input type="number" class="form-control" placeholder="Sales Rate" id="saleprice" name="saleprice" required min="0">
              </div>

            </div>
            <div class="modal-footer ">
              <input type="hidden" name="Add" value="Add">
              <button type="submit" name="Add" value="Add" id='add' class="btn btn-success">Add Product</button>
              <button type="button" class="btn pull-right btn-warning" data-dismiss="modal">Close</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->

      </div>
    </form>
    <!-- End Add product modal -->

    <!-- Edit product modal -->
    <form id="editproduct" action="" method="post">
      <div class="modal fade" id="modaleditproduct" style="display: none;">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header bg-red">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span></button>
              <h4 class="modal-title">Product Edit</h4>
            </div>
            <div class="modal-body">
              <div class="alert " id="editalertclass" style="display: none">
                <button type="button" class="close" onclick="$('#editalertclass').hide()">×</button>
                <p id="editmsg"></p>
              </div>

              <div class="form-group">
                <label for="exampleInputEmail1">Product Name</label>
                <input type="text" class="form-control" placeholder="Product Name" name="editname" id="editname" required pattern="[a-zA-Z0-9\s]+">
              </div>

              <div class="form-group">
                <label for="exampleInputEmail1">Short Name</label>
                <input type="text" class="form-control" placeholder="Product Short Name" name="editshortname" id="editshortname" required pattern="[a-zA-Z0-9\s]+">
              </div>

              <div class="form-group">
                <label for="exampleInputEmail1">HSN Code</label>
                <input type="text" class="form-control" placeholder="HSN Code" name="edithsnCode" id="edithsnCode">
              </div>

              <div class="form-group">
                <label for="exampleInputEmail1">Details</label>
                <textarea class="form-control" rows="3" placeholder="Details" name="editdetails" id="editdetails"></textarea>
              </div>

              <div class="form-group">
                <label for="exampleInputEmail1">Purchase Rate</label>
                <input type="number" class="form-control" placeholder="Purchase Rate" id="editrate" name="editrate" required min="0">
              </div>

              <div class="form-group">
                <label for="exampleInputEmail1">Sales Rate</label>
                <input type="number" class="form-control" placeholder="Sales Rate" id="editsaleprice" name="editsaleprice" required min="0">
              </div>
            </div>
            <div class="modal-footer ">
              <input type="hidden" name="id" id="editid">
              <input type="hidden" name="Edit" value="Edit">
              <button type="submit" name="Edit" value="Edit" id='Edit' class="btn btn-success">Edit product</button>
              <button type="button" class="btn pull-right btn-warning" data-dismiss="modal">Close</button>
            </div>
          </div>
          <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->

      </div>
    </form>
    <!-- End Edit Admin modal -->
    <?php include("footer.php"); ?>

  </div>
  <!-- ./wrapper -->

  <!-- jQuery 3 -->
  <script src="../../bower_components/jquery/dist/jquery.min.js"></script>
  <!-- Bootstrap 3.3.7 -->
  <script src="../../bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
  <!-- SlimScroll -->
  <script src="../../bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
  <!-- FastClick -->
  <script src="../../bower_components/fastclick/lib/fastclick.js"></script>
  <!-- AdminLTE App -->
  <script src="../../dist/js/adminlte.min.js"></script>  
  <!-- DataTables -->
  <script src="../../bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
  <script src="../../bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
  <script src="../../bower_components/datatables.net-bs/js/dataTables.buttons.min.js"></script>
  <script src="../../bower_components/datatables.net-bs/js/jszip.min.js"></script>
  <script src="../../bower_components/datatables.net-bs/js/pdfmake.min.js"></script>
  <script src="../../bower_components/datatables.net-bs/js/vfs_fonts.js"></script>
  <script src="../../bower_components/datatables.net-bs/js/buttons.html5.min.js"></script>
  <script src="../../bower_components/datatables.net-bs/js/buttons.print.min.js"></script>
  <script src="../../bower_components/datatables.net-bs/js/buttons.colVis.min.js"></script>

  <script>
    $(document).ready(function() {
      $('.sidebar-menu').tree()

      $('[data-toggle="tooltip"]').tooltip();

      //display data table
      function tabledata() {

        $('#example1').dataTable().fnDestroy();
        $('#example1 tbody').empty();

        $.ajax({
          url: $(location).attr('href'),
          type: 'POST',
          data: {
            'tabledata': 'tabledata'
          },
          success: function(response) {
              console.log(response); 
            var returnedData = JSON.parse(response);
            //  console.log(returnedData);
            var srno = 0;
            $.each(returnedData['list'], function(key, value) {
              srno++;
              button1 = '';
              button2 = '';

              if(value.status==1){
                button1 = '<button type="submit" name="Edit" id="Edit" ' +
                  'data-editid="' + value.id + '" data-name="' + value.name +
                  '" data-hsnCode="' + value.hsn + 
                  '" data-shortname="' + value.short_name + '" data-details="' + value.details +
                  '" data-rate="' + value.rate + '" data-saleprice="' + value.saleprice +
                  '" class="btn btn-xs btn-warning edit-button" style= "margin:5px" title=" Edit product " data-toggle="modal" data-target="#modaleditproduct"><i class="fa fa-edit"></i></button>';

                button2 = '<button type="submit" name="Delete" id="Delete" ' +
                  'data-deleteid="' + value.id +
                  '" class="btn btn-xs btn-danger delete-button" style= "margin:5px" title=" Delete Product"><i class="fa fa-close"></i></button>';
              }
              value.hsn = (value.hsn===null)?'':value.hsn;
              value.details = (value.details===null)?'':value.details;
              var html = '<tr class="odd gradeX">' +
                '<td class="text-center">' + srno + '</td>' +
                '<td class="text-center">' + value.name + '</td>' +
                '<td class="text-center">' + value.short_name + '</td>' +
                '<td class="text-center">' + value.hsn + '</td>' +
                '<td class="text-center">' + value.details + '</td>' +
                '<td class="text-center">' + value.rate + '</td>' +
                '<td class="text-center">' + value.saleprice + '</td>' +                
                '<td class="text-center">' + button1 + button2 + '</td>' +
                '</tr>';
              $('#example1 tbody').append(html);
            });

            $('#example1').DataTable({
              dom: 'Bfrtip',
              buttons: [{
                  extend: 'copy',
                  className: ' btn btn-success',
                  exportOptions: {
                    columns: ':visible'
                  }
                },
                {
                  extend: 'csv',
                  className: ' btn bg-maroon',
                  exportOptions: {
                    columns: ':visible'
                  }
                },
                {
                  extend: 'excel',
                  className: ' btn bg-purple',
                  exportOptions: {
                    columns: ':visible'
                  }
                },
                {
                  extend: 'pdf',
                  className: ' btn bg-navy',
                  exportOptions: {
                    columns: ':visible'
                  }
                },
                {
                  extend: 'print',
                  className: ' btn bg-olive',
                  exportOptions: {
                    columns: ':visible'
                  }
                },
                {
                  extend: 'colvis',
                  columns: ' :not(.noVis)',
                  className: ' btn btn-warning '
                }
              ],
              stateSave: true,
              destroy: true,
            });
          }
        });
      }

      tabledata();

      $(document).on("click", ".edit-button", function(e) {
        $('#editalertclass').removeClass();
        $('#editmsg').empty();
        $(".modal-body #editname").attr("value", $(this).data('name'));
        $(".modal-body #editshortname").attr("value", $(this).data('shortname'));
        $(".modal-body #edithsnCode").attr("value", $(this).data('hsnCode'));
        $(".modal-body #editrate").attr("value", $(this).data('rate'));
        $(".modal-body #editsaleprice").attr("value", $(this).data('saleprice'));
        $(".modal-body #editdetails").val($(this).data('details'));
        $("#editid").val($(this).data('editid'));
      });

      //add product
      $('#addproduct').submit(function(e) {

        $('#addalertclass').removeClass();
        $('#addmsg').empty();

        e.preventDefault();

        $.ajax({
          url: $(location).attr('href'),
          type: 'POST',
          data: $('#addproduct').serialize(),
          success: function(response) {
            // console.log(response);
            var returnedData = JSON.parse(response);
            // console.log(returnedData);

            if (returnedData['value'] == 1) {
              $('#addproduct')[0].reset();
              $('#addalertclass').addClass(returnedData['type']);
              $('#addmsg').append(returnedData['data']);
              $("#addalertclass").show();
              tabledata();
            } else {
              $('#addalertclass').addClass(returnedData['type']);
              $('#addmsg').append(returnedData['data']);
              $("#addalertclass").show();
            }
          }
        });
      });

      //edit product 
      $('#editproduct').submit(function(e) {
        $('#editalertclass').removeClass();
        $('#editmsg').empty();
        e.preventDefault();

        $.ajax({
          url: $(location).attr('href'),
          type: 'POST',
          data: $('#editproduct').serialize(),
          success: function(response) {
            // console.log(response);                      
            var returnedData = JSON.parse(response);

            if (returnedData['value'] == 1) {
              $('#editalertclass').addClass(returnedData['type']);
              $('#editmsg').append(returnedData['data']);
              $("#editalertclass").show();
              tabledata();
            } else {
              $('#editalertclass').addClass(returnedData['type']);
              $('#editmsg').append(returnedData['data']);
              $("#editalertclass").show();
            }
          }
        });
      });

      //Delete product
      $(document).on("click", ".delete-button", function(e) {

        var id = $(this).data('deleteid');
        $('#alertclass').removeClass();
        $('#msg').empty();
        e.preventDefault();

        if (confirm('Are you sure to remove this product?')) {

          $.ajax({
            url: $(location).attr('href'),
            dataType: "json",
            type: 'POST',
            data: {
              delete: 'Delete',
              deleteid: id
            },
            encode: true,
            success: function(response) {
              //console.log(response);                      
              var returnedData = response;

              if (returnedData['value'] == 1) {
                $('#alertclass').addClass(returnedData['type']);
                $('#msg').append(returnedData['data']);
                $("#alertclass").show();
                tabledata();
              } else {
                $('#alertclass').addClass(returnedData['type']);
                $('#msg').append(returnedData['data']);
                $("#alertclass").show();
                tabledata();
              }
            }
          });
        }
      });
    })
  </script>
</body>

</html>