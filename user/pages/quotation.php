<?php

include("sessioncheck.php");
date_default_timezone_set('Asia/Kolkata');

$userid = $_SESSION['userid'];
$todaydate = date('Y-m-d');



if (isset($_POST['tabledata'])) {
    $data = new \stdClass();
    $result = mysqli_query($connection, "SET NAMES utf8");
    $result = mysqli_query($connection, "SELECT b.*,  DATE_FORMAT(b.date,'%d-%m-%Y') as quotationDate, c.name as customername, c.mobile as customermobile from quotation as b
                                            LEFT join customer as c ON b.customerid = c.id
                                            WHERE b.status = 1
                                            order by b.date desc");
    $data->list = mysqli_fetch_all($result, MYSQLI_ASSOC);

    $result = mysqli_query($connection, "Select id,name,mobile from customer where status='1' order by name");
    $data->customerlist = mysqli_fetch_all($result, MYSQLI_ASSOC);

    echo json_encode($data);
    exit();
}


//Add quotation
if (isset($_POST['Add'])) {

    $msg = new \stdClass();
    $customerid = mysqli_real_escape_string($connection, $_POST['customerid']);
    $date = mysqli_real_escape_string($connection, $_POST['date']);
    $details = mysqli_real_escape_string($connection, $_POST['details']);
    $total = 0;

    $res = mysqli_query($connection, "INSERT INTO `quotation`(`date`, `customerid`, `details`, `total`, `status`, `createdby`) 
                                        VALUES('$date','$customerid','$details','$total','1','$userid')
                                    ");
    if ($res > 0) {
        $msg->value = 1;
        $msg->data = "Quotation Added Successfully.";
        $msg->type = "alert alert-success alert-dismissible ";
    } else {
        $msg->value = 0;
        $msg->data = "Please Try Again";
        $msg->type = "alert alert-danger alert-dismissible ";
    }

    echo json_encode($msg);
    exit();
}

//Edit quotation
if (isset($_POST['Edit'])) {

    $msg = new \stdClass();
    $customerid = mysqli_real_escape_string($connection, $_POST['editcustomerid']);
    $date = mysqli_real_escape_string($connection, $_POST['editdate']);
    $details = mysqli_real_escape_string($connection, $_POST['editdetails']);
    $id =  mysqli_real_escape_string($connection, $_POST['id']);;

    $res = mysqli_query($connection, "UPDATE `quotation` SET 
                                        `date` = '$date', `customerid` = '$customerid', `details` = '$details' , `createdby` = '$userid'  
                                        WHERE `id` = '$id'                                      
                                    ");

    if (mysqli_affected_rows($connection) > 0) {
        $msg->value = 1;
        $msg->data = "Quotation Updated Successfully.";
        $msg->type = "alert alert-success alert-dismissible ";
    } else {
        $msg->value = 0;
        $msg->data = "Please Try Again";
        $msg->type = "alert alert-danger alert-dismissible ";
    }

    echo json_encode($msg);
    exit();
}

//Delete quotation
if (isset($_POST['delete'])) {
    $msg = new \stdClass();
    $result = mysqli_query($connection, "SET NAMES utf8");

    $id = mysqli_real_escape_string($connection, trim(strip_tags($_POST['deleteid'])));
    $res = mysqli_query($connection, "UPDATE `quotation` SET status = 0 WHERE id = '$id' ");
    if ($res > 0) {
        $msg->value = 1;
        $msg->data = "Quotation Deleted Successfully.";
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
    <title><?= $project ?> : Add Quotation Details</title>
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
    <!-- Select2 -->
    <link rel="stylesheet" href="../../bower_components/select2/dist/css/select2.min.css">


    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->

    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

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
                    <li><a href="#"> Customer </a></li>
                    <li class="active"> Quotation </li>
                </ol>
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="row">
                    <div class="col-md-12">
                        <!-- Default box -->
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title"> Quotation Details </h3>
                                <a class="btn btn-social-icon btn-success pull-right" title="Add Quotation" data-toggle="modal" data-target="#modaladdsales"><i class="fa fa-plus"></i></a>
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
                                            <th class='text-center'>Date </th>
                                            <th class='text-center'>Customer Name </th>
                                            <th class='text-center'>Mobile </th>
                                            <th class='text-center'>Details </th>
                                            <th class='text-center'>Amount </th>
                                            <th class='text-center'>Last Update </th>
                                            <th class='text-center'>Update</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody">

                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th class='text-center'>SrNo </th>
                                            <th class='text-center'>Date </th>
                                            <th class='text-center'>Customer Name </th>
                                            <th class='text-center'>Mobile </th>
                                            <th class='text-center'>Details </th>
                                            <th class='text-center'>Amount </th>
                                            <th class='text-center'>Last Update </th>
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
        <!-- Add  sales modal -->
        <form id="addsales" action="" method="post">
            <div class="modal fade" id="modaladdsales" style="display: none;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-green">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span></button>
                            <h4 class="modal-title">Add Quotation Details</h4>
                        </div>
                        <div class="modal-body">
                            <div class="alert " id="addalertclass" style="display: none">
                                <button type="button" class="close" onclick="$('#addalertclass').hide()">×</button>
                                <p id="addmsg"></p>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputPassword1">Customer Name </label>
                                <select class="form-control select2 " style="width: 100%;" required name="customerid" id="customerid">
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Date</label>
                                <input type="date" class="form-control" id="date" name="date" max=<?= date('Y-m-d') ?>>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Details</label>
                                <textarea class="form-control" rows="3" placeholder="Details" name="details" id="details"></textarea>
                            </div>


                        </div>
                        <div class="modal-footer ">
                            <input type="hidden" name="Add" value="Add">
                            <button type="submit" name="Add" value="Add" id='add' class="btn btn-success">Add Quotation</button>
                            <button type="button" class="btn pull-right btn-warning" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->

            </div>
        </form>
        <!-- End Add sales quotation modal -->

        <!-- Edit  sales modal -->
        <form id="editsales" action="" method="post">
            <div class="modal fade" id="modaleditsales" style="display: none;">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header bg-red">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span></button>
                            <h4 class="modal-title">Edit Quotation Details</h4>
                        </div>
                        <div class="modal-body">
                            <div class="alert " id="editalertclass" style="display: none">
                                <button type="button" class="close" onclick="$('#editalertclass').hide()">×</button>
                                <p id="editmsg"></p>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputPassword1">Customer Name </label>
                                <select class="form-control select2 " style="width: 100%;" required name="editcustomerid" id="editcustomerid">
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Date</label>
                                <input type="date" class="form-control" id="editdate" name="editdate" max=<?= date('Y-m-d') ?>>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Details</label>
                                <textarea class="form-control" rows="3" placeholder="Details" name="editdetails" id="editdetails"></textarea>
                            </div>


                        </div>
                        <div class="modal-footer ">
                            <input type="hidden" name="Edit" value="Edit">
                            <input type="hidden" name="id" id="editid">
                            <button type="submit" name="Edit" value="Edit" id='Edit' class="btn btn-success">Edit Quotation</button>
                            <button type="button" class="btn pull-right btn-warning" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->

            </div>
        </form>
        <!-- End Add sales quotation modal -->

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
    <!-- AdminLTE for demo purposes -->
    <script src="../../dist/js/demo.js"></script>
    <!-- DataTables -->
    <script src="../../bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="../../bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
    <!-- Select2 -->
    <script src="../../bower_components/select2/dist/js/select2.full.min.js"></script>

    <script>
        $(document).ready(function() {

            $('.sidebar-menu').tree()

            $('[data-toggle="tooltip"]').tooltip();            

            //display data table
            function tabledata() {
                $('.select2').empty();
                $('#example1').dataTable().fnDestroy();
                $('#example1 tbody').empty();

                $.ajax({
                    url: $(location).attr('href'),
                    type: 'POST',
                    data: {
                        'tabledata': 'tabledata'
                    },
                    success: function(response) {
                        // console.log(response);
                        var returnedData = JSON.parse(response);
                        //console.log(returnedData);
                        var srno = 0;
                        $.each(returnedData['list'], function(key, value) {
                            srno++;
                            button1 = '';
                            button2 = '';
                            button3 = '';

                            button1 = '<button type="submit" name="Delete" id="Delete" ' +
                                'data-deleteid="' + value.id + '"' +
                                '" class="btn btn-xs btn-danger delete-button" style= "margin:5px" title=" Delete Quotation " ><i class="fa fa-times"></i></button>';

                            button2 = '<a ' +
                                'data-viewid="' + value.id + '"' +
                                '" class="btn btn-xs btn-success view-button" style= "margin:5px" title=" View Quotation"  href="quotationitem.php?id=' + value.id + '" ><i class="fa fa-eye"></i></a>';

                            button3 = '<button type="submit" name="Edit" id="Edit" ' +
                                'data-editid="' + value.id + '"' +
                                'data-customerid="' + value.customerid + '"' + 'data-date="' + value.date + '"' +
                                'data-details="' + value.details +
                                '" class="btn btn-xs btn-warning edit-button" style= "margin:5px" title=" Edit Quotation" data-toggle="modal" data-target="#modaleditsales" ><i class="fa fa-edit"></i></button>';

                            var html = '<tr class="odd gradeX">' +

                                '<td class="text-center">' + srno + '</td>' +
                                '<td class="text-center">' + value.quotationDate + '</td>' +
                                '<td class="text-center">' + value.customername + '</td>' +
                                '<td class="text-center">' + value.customermobile + '</td>' +
                                '<td class="text-center">' + value.details + '</td>' +
                                '<td class="text-center">' + parseFloat(value.total).toLocaleString('en-IN') + '</td>' +
                                '<td class="text-center">' + value.lastUpdate + '</td>' +
                                '<td class="text-center">' + button2 + button3 + button1 + '</td>' +
                                '</tr>';
                            $('#example1 tbody').append(html);
                        });

                        $('.select2').append(new Option("Select customer", ""));


                        $.each(returnedData['customerlist'], function(key, value) {
                            $('.select2').append(new Option(value.name, value.id));
                        });

                        $('#example1').DataTable({
                            stateSave: true,
                            destroy: true,
                        });
                        //Initialize Select2 Elements
                        $('.select2').select2()
                    }
                });
            }

            tabledata();



            $(document).on("click", ".delete-button", function(e) {
                var id = $(this).data('deleteid');
                $('#alertclass').removeClass();
                $('#msg').empty();
                e.preventDefault();

                if (confirm('Are you sure to remove this quotation?')) {

                    $.ajax({
                        url: $(location).attr('href'),
                        dataType: "json",
                        type: 'POST',
                        data: {
                            delete: 'delete',
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

            //add sales quotation
            $('#addsales').submit(function(e) {
                $('#addalertclass').removeClass();
                $('#addmsg').empty();

                e.preventDefault();

                $.ajax({
                    url: $(location).attr('href'),
                    type: 'POST',
                    data: $('#addsales').serialize(),
                    success: function(response) {
                        console.log(response);
                        var returnedData = JSON.parse(response);
                        // console.log(returnedData);

                        if (returnedData['value'] == 1) {
                            $('#addsales')[0].reset();
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


            $(document).on("click", ".edit-button", function(e) {
                $('#editalertclass').removeClass();
                $('#editmsg').empty();
                // $(".modal-body #editdate").attr("value", $(this).data('date'));
                $(".modal-body #editdate").val($(this).data('date'));
                $(".modal-body #editcustomerid").val($(this).data('customerid'));
                $('#editcustomerid').trigger('change');
                $(".modal-body #editdetails").val($(this).data('details'));
                $("#editid").val($(this).data('editid'));
            });

            //edit sales quotation
            $('#editsales').submit(function(e) {
                $('#editalertclass').removeClass();
                $('#editmsg').empty();

                e.preventDefault();

                $.ajax({
                    url: $(location).attr('href'),
                    type: 'POST',
                    data: $('#editsales').serialize(),
                    success: function(response) {
                        console.log(response);
                        var returnedData = JSON.parse(response);
                        // console.log(returnedData);

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
        })
    </script>
</body>

</html>