<?php

include("sessioncheck.php");
date_default_timezone_set('Asia/Kolkata');

$userid = $_SESSION['userid'];
$todaydate = date('Y-m-d');



if (isset($_POST['tabledata'])) {
    $data = new \stdClass();
    $quotationid = $_POST['id'];
    $result = mysqli_query($connection, "SET NAMES utf8");

    $result = mysqli_query($connection, "SELECT bd.*, p.name as productname, p.short_name,p.details,p.hsn 
                                            from quotation_details as bd
                                            LEFT join quotation as b ON bd.quotationid = b.id
                                            LEFT join product as p ON bd.productid = p.id                                            
                                            WHERE bd.status = 1 and bd.quotationid = '$quotationid'
                                            ");
    $data->saleList = mysqli_fetch_all($result, MYSQLI_ASSOC);

    $result = mysqli_query($connection, "SELECT b.*, c.name as customername, c.mobile as customermobile, c.address as customeraddress
                                            from quotation as b                                            
                                            LEFT join customer as c ON b.customerid = c.id
                                            WHERE b.status = 1 and b.id = '$quotationid'
                                            ");
    $data->quotationDetails = mysqli_fetch_all($result, MYSQLI_ASSOC);

    $result = mysqli_query($connection, "Select id,name,short_name,rate,saleprice,hsn from product where status='1' order by name");
    $data->productlist = mysqli_fetch_all($result, MYSQLI_ASSOC);

    echo json_encode($data);
    exit();
}


//Add quotation item
if (isset($_POST['Add'])) {

    $msg = new \stdClass();
    $quotationid = mysqli_real_escape_string($connection, $_POST['quotationid']);
    $productid = mysqli_real_escape_string($connection, $_POST['productid']);

    $saleprice = mysqli_real_escape_string($connection, $_POST['price']);
    $discount = mysqli_real_escape_string($connection, $_POST['discount']);
    $quantity = mysqli_real_escape_string($connection, $_POST['quantity']);
    $rate = mysqli_real_escape_string($connection, $_POST['purchaseprice']);
    $gst = mysqli_real_escape_string($connection, $_POST['gst']);
    $gstamount = mysqli_real_escape_string($connection, $_POST['gstamount']);
    $subtotal = mysqli_real_escape_string($connection, $_POST['subtotal']);
    $total = mysqli_real_escape_string($connection, $_POST['total']);

    $res = mysqli_query($connection, "INSERT INTO `quotation_details`(
                                                    `quotationid`, `productid`, `quantity`, `rate`, `discount`, `saleprice`, 
                                                    `gst`, `subtotal`, `gstamount`, `total`, `status`)
                                    VALUES('$quotationid','$productid','$quantity','$rate','$discount','$saleprice',
                                            '$gst','$subtotal','$gstamount','$total','1')
                                    ");
    if ($res > 0) {
        $msg->value = 1;
        $msg->data = "Item Added Successfully.";
        $msg->type = "alert alert-success alert-dismissible ";
    } else {
        $msg->value = 0;
        $msg->data = "Please Try Again";
        $msg->type = "alert alert-danger alert-dismissible ";
    }

    echo json_encode($msg);
    exit();
}

//Delete quotation item
if (isset($_POST['delete'])) {
    $msg = new \stdClass();
    $result = mysqli_query($connection, "SET NAMES utf8");
    $id = mysqli_real_escape_string($connection, trim(strip_tags($_POST['deleteid'])));

    $res = mysqli_query($connection, "UPDATE `quotation_details` SET status = 0 WHERE id = '$id' ");
    if ($res > 0) {
        $msg->value = 1;
        $msg->data = "Item Deleted Successfully.";
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
                    <li class="active"> Quotation Details </li>
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
                                <a class="btn btn-social-icon btn-success pull-right" style="margin:5px" title="Add Quotation" data-toggle="modal" data-target="#modaladdsales"><i class="fa fa-plus"></i></a>
                                <a class="btn btn-social-icon btn-primary pull-right" style="margin:5px" title="View Quotation Details" data-toggle="modal" data-target="#modalquotationDetails"><i class="fa  fa-info-circle"></i></a>
                                <a class="btn btn-social-icon btn-warning pull-right" style="margin:5px" title="Print Quotation" id="print"><i class="fa fa-print"></i></a>
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
                                            <th class='text-center'>Code </th>
                                            <th class='text-center'>HSN </th>
                                            <th class='text-center'>Details </th>
                                            <th class='text-center'>Rate </th>
                                            <th class='text-center'>Discount </th>
                                            <th class='text-center'>Quantity </th>
                                            <th class='text-center'>Subtotal </th>
                                            <th class='text-center'>GST % </th>
                                            <th class='text-center'>GST Amount </th>
                                            <th class='text-center'>Total </th>
                                            <th class='text-center'>Last Update </th>
                                            <th class='text-center'>Update</th>
                                        </tr>
                                    </thead>
                                    <tbody id="tbody">

                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th class='text-center'>SrNo </th>
                                            <th class='text-center'>Name </th>
                                            <th class='text-center'>Code </th>
                                            <th class='text-center'>HSN </th>
                                            <th class='text-center'>Details </th>
                                            <th class='text-center'>Rate </th>
                                            <th class='text-center'>Discount </th>
                                            <th class='text-center'>Quantity </th>
                                            <th class='text-center'>Subtotal </th>
                                            <th class='text-center'>GST % </th>
                                            <th class='text-center'>GST Amount </th>
                                            <th class='text-center'>Total </th>
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
                                <label for="exampleInputPassword1">Product name </label>
                                <select class="form-control select2 " style="width: 100%;" required name="productid" id="productid">
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">HSN Code</label>
                                <input type="number" class="form-control" placeholder="HSN Code" id="hsnCode" name="hsnCode" readonly>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Purchase Rate</label>
                                <input type="number" class="form-control" placeholder="Purchase Price" id="purchaseprice" name="purchaseprice" required min="0" readonly>
                            </div>


                            <div class="form-group">
                                <label for="exampleInputEmail1">Sale Rate</label>
                                <input type="number" class="form-control" placeholder="Product Price" id="price" name="price" required min="0" value="0">
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Quantity</label>
                                <input type="number" class="form-control" placeholder="Product Quantity" id="quantity" name="quantity" required min="1" value="0">
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Discount</label>
                                <input type="number" class="form-control" placeholder="Discount" id="discount" name="discount" required min="0" value="0">
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">Sub Total</label>
                                <input type="number" class="form-control" placeholder="Sub total" id="subtotal" name="subtotal" required min="0" readonly>
                            </div>

                            <div class="form-group">
                                <label for="exampleInputEmail1">GST %</label>
                                <input type="number" class="form-control" placeholder="GST %" id="gst" name="gst" required min="0" value="0">
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">GST Amount</label>
                                <input type="number" class="form-control" placeholder="GST Amount" id="gstamount" name="gstamount" required min="0" readonly>
                            </div>
                            <div class="form-group">
                                <label for="exampleInputEmail1">Total</label>
                                <input type="number" class="form-control" placeholder="Total" id="total" name="total" required min="0" readonly>
                            </div>

                        </div>
                        <div class="modal-footer ">
                            <input type="hidden" name="Add" value="Add">
                            <input type="hidden" name="quotationid" id="quotationid">
                            <button type="submit" name="Add" value="Add" id='add' class="btn btn-success">Add To Quotation</button>
                            <button type="button" class="btn pull-right btn-warning" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->

            </div>
        </form>
        <!-- End Add sales quotation modal -->

        <!-- Modal for displaye details -->

        <div class="modal fade" id="modalquotationDetails" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-green">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span></button>
                        <h4 class="modal-title">Quotation Details</h4>
                    </div>
                    <div class="modal-body">

                        <div class="form-group">
                            <label for="exampleInputPassword1">Quotation Number : </label>
                            <label for="exampleInputPassword1" id="quotationNumber"> </label>
                        </div>

                        <div class="form-group">
                            <label for="exampleInputPassword1">Quotation Date : </label>
                            <label for="exampleInputPassword1" id="quotationDate"> </label>
                        </div>

                        <div class="form-group">
                            <label for="exampleInputPassword1">Quotation Details : </label>
                            <label for="exampleInputPassword1" id="quotationDetails"> </label>
                        </div>

                        <div class="form-group">
                            <label for="exampleInputPassword1">Customer name : </label>
                            <label for="exampleInputPassword1" id="customerName"> </label>
                        </div>

                        <div class="form-group">
                            <label for="exampleInputPassword1">Mobile : </label>
                            <label for="exampleInputPassword1" id="customerMobile"> </label>
                        </div>

                        <div class="form-group">
                            <label for="exampleInputPassword1">Address : </label>
                            <label for="exampleInputPassword1" id="customerAddress"> </label>
                        </div>

                        <div class="form-group">
                            <label for="exampleInputPassword1">Total : </label>
                            <label for="exampleInputPassword1" id="totalAmount"> </label>
                        </div>
                    </div>
                    <div class="modal-footer ">
                        <button type="button" class="btn pull-right btn-warning" data-dismiss="modal">Close</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->

        </div>


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
    <!-- Select2 -->
    <script src="../../bower_components/select2/dist/js/select2.full.min.js"></script>

    <script>
        $(document).ready(function() {

            $('#print').click(function() {
                var vars = [],
                    hash;
                var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
                for (var i = 0; i < hashes.length; i++) {
                    hash = hashes[i].split('=');
                    vars.push(hash[0]);
                    vars[hash[0]] = hash[1];
                }
                window.location.href = 'quotationbill.php?quotationid='+vars["id"];
            });

            $('.sidebar-menu').tree()

            $('[data-toggle="tooltip"]').tooltip();

            //display data table
            function tabledata() {

                var vars = [],
                    hash;
                var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
                for (var i = 0; i < hashes.length; i++) {
                    hash = hashes[i].split('=');
                    vars.push(hash[0]);
                    vars[hash[0]] = hash[1];
                }

                $('.select2').empty();
                $('#example1').dataTable().fnDestroy();
                $('#example1 tbody').empty();

                $('#quotationid').val(vars["id"]);

                $.ajax({
                    url: $(location).attr('href'),
                    type: 'POST',
                    data: {
                        'tabledata': 'tabledata',
                        'id': vars["id"]
                    },
                    success: function(response) {
                        // console.log(response);
                        var returnedData = JSON.parse(response);
                        console.log(returnedData);
                        var srno = 0;
                        var totalAmount = 0;
                        $.each(returnedData['saleList'], function(key, value) {
                            srno++;
                            button1 = '';
                            button2 = '';
                            button1 = '<button type="submit" name="Delete" id="Delete" ' +
                                'data-deleteid="' + value.id + '"' +
                                '" class="btn btn-xs btn-danger delete-button" style= "margin:5px" title=" Delete Sales quotation " ><i class="fa fa-times"></i></button>';
                            value.hsn = (value.hsn===null)?'-':value.hsn;
                            var html = '<tr class="odd gradeX">' +

                                '<td class="text-center">' + srno + '</td>' +
                                '<td class="text-center">' + value.productname + '</td>' +
                                '<td class="text-center">' + value.short_name + '</td>' +
                                '<td class="text-center">' + value.hsn + '</td>' +
                                '<td class="text-center">' + value.details + '</td>' +
                                '<td class="text-center">' + parseFloat(value.saleprice).toLocaleString('en-IN') + '</td>' +
                                '<td class="text-center">' + parseFloat(value.discount).toLocaleString('en-IN') + '</td>' +
                                '<td class="text-center">' + parseFloat(value.quantity).toLocaleString('en-IN') + '</td>' +
                                '<td class="text-center">' + parseFloat(value.subtotal).toLocaleString('en-IN') + '</td>' +
                                '<td class="text-center">' + parseFloat(value.gst) + '%</td>' +
                                '<td class="text-center">' + parseFloat(value.gstamount).toLocaleString('en-IN') + '</td>' +
                                '<td class="text-center">' + parseFloat(value.total).toLocaleString('en-IN') + '</td>' +
                                '<td class="text-center">' + value.lastUpdate + '</td>' +
                                '<td class="text-center">' + button1 + '</td>' +

                                '</tr>';
                            totalAmount += parseFloat(value.total);
                            $('#example1 tbody').append(html);
                        });

                        $('#quotationNumber').empty();
                        $('#quotationDate').empty();
                        $('#quotationDetails').empty();
                        $('#customerName').empty();
                        $('#customerMobile').empty();
                        $('#customerAddress').empty();
                        $('#totalAmount').empty();

                        $('#quotationNumber').append(returnedData['quotationDetails'][0].id);
                        $('#quotationDate').append(returnedData['quotationDetails'][0].date);
                        $('#quotationDetails').append(returnedData['quotationDetails'][0].details);
                        $('#customerName').append(returnedData['quotationDetails'][0].customername);
                        $('#customerMobile').append(returnedData['quotationDetails'][0].customermobile);
                        $('#customerAddress').append(returnedData['quotationDetails'][0].customeraddress);
                        $('#totalAmount').append(parseFloat(totalAmount).toLocaleString('en-IN'));



                        $('#productid').append(new Option("Select product", ""));


                        $.each(returnedData['productlist'], function(key, value) {
                            var text = '<option value = "' + value.id + '" data-rate = "' + value.rate + '" data-saleprice = "' + value.saleprice + '" data-hsnCode = "' + value.hsn + '">' + value.name + '</option>';
                            $('#productid').append(text);
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

            $('#productid').select2()
                .on('change', function(e) {
                    $("#purchaseprice").val($('#productid option:selected').attr('data-rate'));
                    $("#price").val($('#productid option:selected').attr('data-saleprice'));
                    $("#hsnCode").val($('#productid option:selected').attr('data-hsnCode'));
                    totalCalculation();
                });



            $(document).on("click", ".delete-button", function(e) {

                var id = $(this).data('deleteid');
                $('#alertclass').removeClass();
                $('#msg').empty();
                e.preventDefault();

                if (confirm('Are you sure to remove this record ?')) {

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
                totalCalculation();
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


            $("input.form-control").change(function(e) {
                totalCalculation();
            });

            function totalCalculation() {
                var rate = (isNaN(parseFloat($("#price").val())) ? 0 : parseFloat($("#price").val())) - (isNaN(parseFloat($("#discount").val())) ? 0 : parseFloat($("#discount").val()));
                var subtotal = rate * (isNaN(parseFloat($("#quantity").val())) ? 0 : parseFloat($("#quantity").val()));
                var gst = subtotal * (isNaN(parseFloat($("#gst").val())) ? 0 : parseFloat($("#gst").val())) / 100;
                var total = subtotal + gst;

                $("#subtotal").val(subtotal);
                $("#gstamount").val(gst);
                $("#total").val(total);
            }


        })
    </script>
</body>

</html>