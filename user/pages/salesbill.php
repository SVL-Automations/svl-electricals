<?php
//include("sessioncheck.php");
session_start();
ob_start();

include("../../db.php");

if (isset($_POST['billid'])) {
    $data = new \stdClass();
    $billid = $_POST['billid'];
    $result = mysqli_query($connection, "SET NAMES utf8");

    $result = mysqli_query($connection, "SELECT bd.*, p.name as productname, p.short_name,p.details,p.hsn 
                                            from bill_details as bd
                                            LEFT join bill as b ON bd.billid = b.id
                                            LEFT join product as p ON bd.productid = p.id                                            
                                            WHERE bd.status = 1 and bd.billid = '$billid'
                                            ");
    $data->saleList = mysqli_fetch_all($result, MYSQLI_ASSOC);

    $result = mysqli_query($connection, "SELECT b.*, DATE_FORMAT(b.date,'%d/%m/%Y') as billDate, c.name as customername, c.gst customergst, c.mobile as customermobile, c.address as customeraddress
                                            from bill as b                                            
                                            LEFT join customer as c ON b.customerid = c.id
                                            WHERE b.status = 1 and b.id = '$billid'
                                            ");
    $data->billDetails = mysqli_fetch_all($result, MYSQLI_ASSOC);

    echo json_encode($data);
    exit();
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?= $project ?> : Bill </title>

    <!-- Font Awesome -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link rel="stylesheet" href="../../bower_components/font-awesome/css/font-awesome.min.css">
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="../../bower_components/qrcode/qrcode.min.js"></script>
</head>


<!------ Include the above in your HEAD tag ---------->
<style>
    #invoice {
        padding: 30px;
    }

    #address {
        font-size: 22px;
    }

    .invoice {
        position: relative;
        background-color: #FFF;
        min-height: 680px;
        padding: 15px
    }

    .invoice header {
        padding: 10px 0;
        margin-bottom: 20px;
        border-bottom: 1px solid #3989c6
    }

    .invoice .company-details {
        text-align: left;
        font-size: 1.4em;
    }

    .invoice .company-details .name {
        margin-top: 0;
        margin-bottom: 0;
        font-size: 2.0rem
    }

    .invoice .contacts {
        margin-bottom: 20px
    }

    .invoice .invoice-to {
        text-align: left;
        font-size: 1.5em;
    }

    .invoice .invoice-to .to {
        margin-top: 0;
        margin-bottom: 0
    }

    .invoice .invoice-details {
        text-align: right;
        font-size: 20px;

    }

    .invoice .invoice-details .invoice-id {
        margin-top: 0;
        color: #000000
    }

    .invoice main {
        padding-bottom: 50px
    }

    .invoice main .thanks {
        margin-top: -10px;
        font-size: 2em;
        margin-bottom: 50px
    }

    .invoice main .notices {
        padding-left: 6px;
        border-left: 6px solid #3989c6
    }

    .invoice main .notices .notice {
        font-size: 1.2em
    }

    .invoice table {
        width: 100%;
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 20px
    }

    .invoice table td,
    .invoice table th {
        padding: 15px;
        background: #eee;
        border-bottom: 1px solid #fff
    }

    .invoice table th {
        white-space: nowrap;
        font-weight: 500;
        font-size: 20px
    }

    .invoice table td h3 {
        margin: 0;
        font-weight: 400;
        color: #000000;
        font-size: 1.6em
    }

    .invoice table .qty,
    .invoice table .total,
    .invoice table .unit {
        text-align: right;
        font-size: 1.6em
    }

    .invoice table .no {
        color: #000000;
        font-size: 1.6em;

    }

    .invoice table .unit {
        /* background: #ddd */
    }

    .invoice table .total {
        /* background: #3989c6;
        color: #fff */
    }

    .invoice table tbody tr:last-child td {
        border: none
    }

    .invoice table tfoot td {
        background: 0 0;
        border-bottom: none;
        white-space: nowrap;
        /* text-align: right; */
        padding: 10px 20px;
        font-size: 1.2em;
        border-top: 1px solid #aaa
    }

    .invoice table tfoot tr:first-child td {
        border-top: none
    }

    .invoice table tfoot tr:last-child td {
        color: #000000;
        font-size: 1.4em;
        border-top: 1px solid #000000
    }

    .invoice table tfoot tr td:first-child {
        border: none
    }

    .invoice footer {
        width: 100%;
        text-align: center;
        color: #777;
        border-top: 1px solid #aaa;
        padding: 8px 0;
        font-size: 20px;
    }

    @media print {
        .invoice {
            font-size: 11px !important;
            overflow: hidden !important
        }

        .invoice footer {
            position: absolute;
            bottom: 10px;
            page-break-after: always;

        }

        .invoice>div:last-child {
            page-break-before: always
        }
    }
</style>

<body>
    <div id="invoice">

        <div class="toolbar hidden-print">
            <!-- <div class="text-right">
                <button id="printInvoice" class="btn btn-info"><i class="fa fa-print"></i> Print</button>
                <button class="btn btn-info"><i class="fa fa-file-pdf-o"></i> Export as PDF</button>
            </div> -->
            <hr>
        </div>
        <div class="invoice overflow-auto">
            <div style="min-width: 600px">
                <header>
                    <div class="row">

                        <div class="col company-details">
                            <h2 class="name">Shree VaradLaxmi Electicals </h2>
                            <div class="address">Sanegurugi Vasahat Kolhapur </div>
                            <div>Mobile : 90968-42658</div>
                            <div>GSTIN : 27BGSPB0705J1ZG</div>
                            <div>PAN Number : BGSPB0705J</div>
                        </div>
                        <div class="col" style="text-align:right">
                            <a href="index.php">
                                <img src="../../dist/img/small.png" data-holder-rendered="true" width="170" height="170" />
                            </a>
                        </div>
                    </div>
                </header>
                <main>
                    <div class="row contacts">
                        <div class="col invoice-to">
                            <div class="text-gray-light">Bill TO:</div>
                            <h2 class="to" id="to"></h2>
                            <div id="mobile"></div>
                            <div id="customergst"></div>
                            <div class="address" id="address"></div>
                            <div id="email"></div>

                        </div>
                        <div class="col invoice-details">
                            <h3 class="invoice-id" id="invoice-id">Invoice Details </h3>
                            <div class="date" id="invoice-number">Invoice Number : </div>
                            <div class="date" id="invoice-date">Invoice Date : </div>
                        </div>
                    </div>
                    <table border="0" cellspacing="0" cellpadding="0">
                        <thead>

                            <tr>
                                <th class="text-center">Sr.no. </th>
                                <th class="text-center">Item</th>
                                <th class="text-center">HSN Code</th>
                                <th class="text-center">Quantity</th>
                                <th class="text-center">Rate</th>
                                <th class="text-center">Discount</th>
                                <th class="text-center">Subtotal</th>
                                <th class="text-center">Tax</th>
                                <th class="text-center">Amount</th>
                            </tr>
                        </thead>
                        <tbody id="salesdetails">
                            <tr>

                            </tr>
                        </tbody>
                    </table>
                    <table border="0" cellspacing="0" cellpadding="0" style="border-style: dotted; border-width: 0.8px; padding-top:20px  !important; ">
                        <tfoot>
                            <tr>
                                <td rowspan="5">
                                    <table style="border-style: none !important; " border="0">
                                        <tr>
                                            <td style="text-align: left !important; font-size:15px;">
                                                <div> Bank Details:</div>
                                                <div>Account Holder: SHREE VARADLAXMI ELECTRICALS</div>
                                                <div> Name: RBL BANK, SHAHUPURI KOLHAPUR</div>
                                                <div>A/c No: 409001361545 </div>
                                                <div>IFSC code: RATN0000001</div>
                                            </td>
                                            <td style="border-style: none !important; text-align: left;">
                                                <div id="qrcode"></div>
                                            </td>
                                        </tr>
                                    </table>


                                </td>
                                <td >Taxable Amount</td>
                                <td id="subtotal"><i class="fa fa-inr" style="font-size: 15px;text-align: left !important;"></i></td>
                            </tr>
                            <tr>
                                <td>CGST </td>
                                <td id="cgst"><i class="fa fa-inr" style="font-size: 15px;text-align: left !important;"></i></td>
                            </tr>
                            <tr>
                                <td>SGST </td>
                                <td id="sgst"><i class="fa fa-inr" style="font-size: 15px;text-align: left !important;"></i></td>
                            </tr>
                            <tr>
                                <td>Grand Total</td>
                                <td id="grandtotal"><i class="fa fa-inr" style="font-size: 15px;text-align: left !important;"></i></td>
                            </tr>
                            <!-- <tr style="text-align: left !important; font-size:10px;">
                                <td id="grandtotalwords">Total Amount (in words) : <i class="fa fa-inr"></i></td>
                                
                            </tr> -->

                        </tfoot>
                    </table>

                    <div class="thanks">Thank you for your business!</div>
                    <div class="notices">
                        <div>NOTICE:</div>
                        <div class="notice">Goods once sold will not be taken back or exchanged</div>
                        <div class="notice">All disputes are subject to Kolhapur jurisdiction only</div>
                    </div>
                </main>
                <footer>
                    <!-- Invoice was created on a computer and is valid without the signature and seal. -->
                </footer>
            </div>
            <!--DO NOT DELETE THIS div. IT is responsible for showing footer always at the bottom-->
            <div></div>
        </div>
    </div>
    <!-- jQuery 3 -->
    <!-- <script src="../../bower_components/jquery/dist/jquery.min.js"></script> -->
    <!-- Bootstrap 3.3.7 -->
    <!-- <script src="../../bower_components/bootstrap/dist/js/bootstrap.min.js"></script> -->
    <!-- SlimScroll -->
    <!-- <script src="../../bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script> -->
    <!-- FastClick -->
    <!-- <script src="../../bower_components/fastclick/lib/fastclick.js"></script> -->

    <script>
        $(document).ready(function() {

            var qrcode = new QRCode("qrcode", {
                width: 120,
                height: 120,
                colorDark: "#000000",
                colorLight: "#ffffff",
                correctLevel: QRCode.CorrectLevel.H
            });

            function makeCode(elText) {
                qrcode.makeCode(elText);
            }

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

                $.ajax({
                    url: $(location).attr('href'),
                    type: 'POST',
                    data: {
                        'billid': vars["billid"],
                    },
                    success: function(response) {
                        console.log(response);
                        var returnedData = JSON.parse(response);
                        console.log(returnedData);
                        var srno = 0;
                        if (returnedData['status'] == 0) {

                        } else {
                            document.title = returnedData['billDetails'][0]['customername'] + '_' + returnedData['billDetails'][0]['billDate'];
                            $("#to").append(returnedData['billDetails'][0]['customername']);
                            $("#address").append(returnedData['billDetails'][0]['customeraddress']);                            
                            $("#customergst").append((returnedData['billDetails'][0]['customergst']===null)?'':"GST : " + returnedData['billDetails'][0]['customergst']);
                            $("#mobile").append("Mobile : " + returnedData['billDetails'][0]['customermobile']);

                            $("#invoice-number").append(vars["billid"]);
                            $("#invoice-date").append(returnedData['billDetails'][0]['billDate']);

                            var srno = 0;
                            var subTotal = 0;
                            var taxTotal = 0;
                            var quantityTotal = 0;
                            var discountTotal = 0;
                            var total = 0;


                            $.each(returnedData['saleList'], function(key, value) {
                                srno++;
                                var discount = parseFloat(value.discount) * parseFloat(value.quantity);
                                subTotal = parseFloat(subTotal) + parseFloat(value.subtotal);
                                taxTotal = parseFloat(taxTotal) + parseFloat(value.gstamount);
                                total = parseFloat(total) + parseFloat(value.total);
                                var hsn = (value.hsn === null) ? "-" : value.hsn;
                                quantityTotal = parseFloat(quantityTotal) + parseFloat(value.quantity);
                                discountTotal = parseFloat(discountTotal) + parseFloat(discount);

                                var html = '<tr class="odd gradeX">' +
                                    '<td class="text-center">' + srno + '</td>' +
                                    '<td class="text-center">' + value.productname + '</td>' +
                                    '<td class="text-center">' + hsn + '</td>' +
                                    '<td class="text-center">' + parseFloat(value.quantity).toLocaleString('en-IN') + '</td>' +
                                    '<td class="text-center">' + parseFloat(value.saleprice).toLocaleString('en-IN') + '</td>' +
                                    '<td class="text-center">' + parseFloat(discount).toLocaleString('en-IN') + '</td>' +
                                    '<td class="text-center">' + parseFloat(value.subtotal).toLocaleString('en-IN') + '</td>' +
                                    '<td class="text-center">' + parseFloat(value.gstamount).toLocaleString('en-IN') + ' <br><small>(' + value.gst + '%)</small> </td>' +
                                    '<td class="text-center">' + parseFloat(value.total).toLocaleString('en-IN') + '</td>' +
                                    '</tr>';
                                $('#salesdetails').append(html);
                            });

                            var html = '<tr class="odd gradeX">' +
                                '<td class="text-right" colspan="3"> <b>Total Quantity</b>  </td>' +
                                '<td class="text-center">' + quantityTotal.toLocaleString('en-IN') + '</td>' +
                                '<td class="text-center">-</td>' +
                                '<td class="text-center">' + discountTotal.toLocaleString('en-IN') + '</td>' +
                                '<td class="text-center">' + subTotal.toLocaleString('en-IN') + '</td>' +
                                '<td class="text-center">' + taxTotal.toLocaleString('en-IN') + '</td>' +
                                '<td class="text-center">' + total.toLocaleString('en-IN') + '</td>' +
                                '</tr>';
                            $('#salesdetails').append(html);


                            $("#subtotal").append(' ' + parseFloat(subTotal).toLocaleString('en-IN') + "/-");
                            $("#cgst").append(' ' + parseFloat(taxTotal / 2).toLocaleString('en-IN') + "/-");
                            $("#sgst").append(' ' + parseFloat(taxTotal / 2).toLocaleString('en-IN') + "/-");
                            $("#grandtotal").append(' ' + parseFloat(total).toLocaleString('en-IN') + "/- ");

                            $("#grandtotalwords").append(wordify(total) + ' Only.');
                            makeCode("upi://pay?pa=9960130405@rbl&amp;pn=Bill- "+returnedData['billDetails'][0]['customername']+"&amp;cu=INR&amp;am=" + total);

                        }
                    }
                });
            }

            tabledata();

            const wordify = (num) => {
                const single = ["Zero", "One", "Two", "Three", "Four", "Five", "Six", "Seven", "Eight", "Nine"];
                const double = ["Ten", "Eleven", "Twelve", "Thirteen", "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eighteen", "Nineteen"];
                const tens = ["", "Ten", "Twenty", "Thirty", "Forty", "Fifty", "Sixty", "Seventy", "Eighty", "Ninety"];
                const formatTenth = (digit, prev) => {
                    return 0 == digit ? "" : " " + (1 == digit ? double[prev] : tens[digit])
                };
                const formatOther = (digit, next, denom) => {
                    return (0 != digit && 1 != next ? " " + single[digit] : "") + (0 != next || digit > 0 ? " " + denom : "")
                };
                let res = "";
                let index = 0;
                let digit = 0;
                let next = 0;
                let words = [];
                if (num += "", isNaN(parseInt(num))) {
                    res = "";
                } else if (parseInt(num) > 0 && num.length <= 10) {
                    for (index = num.length - 1; index >= 0; index--) switch (digit = num[index] - 0, next = index > 0 ? num[index - 1] - 0 : 0, num.length - index - 1) {
                        case 0:
                            words.push(formatOther(digit, next, ""));
                            break;
                        case 1:
                            words.push(formatTenth(digit, num[index + 1]));
                            break;
                        case 2:
                            words.push(0 != digit ? " " + single[digit] + " Hundred" + (0 != num[index + 1] && 0 != num[index + 2] ? " and" : "") : "");
                            break;
                        case 3:
                            words.push(formatOther(digit, next, "Thousand"));
                            break;
                        case 4:
                            words.push(formatTenth(digit, num[index + 1]));
                            break;
                        case 5:
                            words.push(formatOther(digit, next, "Lakh"));
                            break;
                        case 6:
                            words.push(formatTenth(digit, num[index + 1]));
                            break;
                        case 7:
                            words.push(formatOther(digit, next, "Crore"));
                            break;
                        case 8:
                            words.push(formatTenth(digit, num[index + 1]));
                            break;
                        case 9:
                            words.push(0 != digit ? " " + single[digit] + " Hundred" + (0 != num[index + 1] || 0 != num[index + 2] ? " and" : " Crore") : "")
                    };
                    res = words.reverse().join("")
                } else res = "";
                return res
            };
        })
    </script>
</body>

</html>