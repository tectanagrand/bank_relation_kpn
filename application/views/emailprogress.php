<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!--Favicon-->
        <link rel="shortcut icon" href="<?php echo base_url() ?>favicon.ico" type="image/x-icon">
        <link rel="icon" href="<?php echo base_url() ?>favicon.ico" type="image/x-icon">
    
        <!-- Bootstrap CSS -->
        <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous"> -->

        <!-- <link rel="stylesheet" href="<?php echo base_url() ?>assets/css/stylee.css"> -->

        <!-- Font Awesome -->
<!--         <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous"> -->
        <!-- <link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet"> -->

        <!-- <title>Mustela - Detail Product</title> -->
        <title>Update Progress</title>
        <style>
            img.img20email {
                width: 100%;
                height: auto;
                border-radius: 5px;
                margin-right: 5px;
            }
            table {
                border-collapse: collapse;
                width: 100%;
            }
            th{
                color: white;
                background-color: #04AA6D;
            }
            th, td {
              /*border: 1px solid;*/
              padding: 15px;
              text-align: center;
              border-bottom: 1px solid #ddd;
            }
            /*td {
                border-bottom: 1px solid #f8f8f8;
            }*/
            tr:nth-child(even) {background-color: #f2f2f2;}
            
            .padding-30 {
                padding: 5% 5%;
            }
            .divgrey {
                background: #f8f8f8;
                padding: 5%;
                text-align: center;
                margin-top: 20px;
            }
            /*.divgrey a {
                background: #004d9d;
                color: #fff;
                padding: 20px 30px;
            }*/
            .text-footer {
                font-size: .6rem;
                padding: 0 10%;
                text-align: center
            }
            .f-8 {
                font-size: .8rem;
            }
            .f-1 {
                font-size: 1rem;
            }
            .text-right {
                text-align: right;
            }
            .text-left {
                text-align: left;
            }
            .text-center {
                text-align: center;
            }
            .mt-20 {
                margin-top: 20px;
            }
            .btn-email {
                background: #004d9d;
                color: #fff;
                padding: 5% 20%;
            }
            ul.socmed > li {
                display: inline;
                margin-right: 10px;
            }
            img.wid20 {
                width: 30px;
            }
            img.height40 {
                height: 25px !important;
                width: auto !important;
            }

            @media (min-width: 1200px)
            {
                .container {
                    max-width: 1440px !important;
                }
            }
            .isicontent div {
                padding-left: 0px !important;
                border: transparent !important;
            }
        </style>
    </head>
    <body>

        <section>
            <div class="container">
                <div class="bg-white">
                    <div class="row">
                        <!-- <div class="col-md-6">
                            <img src="http://www.kpnplantation.com/assets/frontend-images/logo_dark.png" class="img20email" alt="">
                        </div> -->
                        <!-- <div class="col-md-6"></div> -->
                    </div>
                    <div class="row">
                        <div class="col-md-12 isicontent">
                            <h4>Dear All,</h4>
                            <p>Please Process Forecast and Payment For This Transaction.</p>
                            <div class="">
                                <table class="" style="border: 1px solid black;">
                                    <thead class="">
                                        <tr>
                                            <th>No</th>
                                            <th>Docnumber</th>
                                            <th>Duedate</th>
                                            <th>Amount</th>
                                            <th>Vendor</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i = 1; foreach($task as $r){?>
                                        <tr>
                                                <td><?= $i; ?></td>
                                                <td><?= $r->DOCNUMBER; ?></td>
                                                <td><?= $r->DUEDATE; ?></td>
                                                <td><?= number_format($r->AMOUNT);?></td>
                                                <td><?= $r->VENDOR; ?></td>
                                        </tr>
                                        <?php $i++;} ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-12 mt-20 f-1">
                            <i><b>(For Bank Relation Team: Payment Can’t be Process by Finance Team, If Bank Relation Team Not Doing Forecast Process.)</b></i>
                            <br>
                            <br>
                            <i><b>Do Not Reply to This Message, This Email Has Been Automatically Generated.<b></i>
                        </div>
                        
                    </div>
                    <div class="row mt-20">
                        <span>Copyright © <?php echo Date('Y'); ?> KPN Corp All Rights Reserved <br></span>
                        <!-- <table width="100%">
                            <tr>
                                <td>
                                    <div class="f-8">
                                        Copyright © <?php echo Date('Y'); ?> KPN Corp All Rights Reserved <br>
                                    </div>
                                </td>
                            </tr>
                        </table> -->
                    </div>
                    <hr>
                </div>
            </div>
        </section>

        <!-- Whatsapp -->
        <!-- <script async progress-id="5706" src="https://cdn.widgetwhats.com/script.min.js"></script>   -->

        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

    </body>
</html>