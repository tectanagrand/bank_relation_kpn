<link href="https://cdn.datatables.net/rowgroup/1.1.1/css/rowGroup.dataTables.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/rowgroup/1.1.1/js/dataTables.rowGroup.min.js"></script>
<script src="//cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js"></script>
<style>
    tr.dtrg-group.dtrg-start.dtrg-level-0 {
        border-top: 1px solid #fff;
    }
    tr.dtrg-group.dtrg-end.dtrg-level-0 {
        border-bottom: 10px solid #fff;
    }
    table.dataTable tr.dtrg-group.dtrg-level-1 td, table.dataTable tr.dtrg-group.dtrg-level-2 td {
        background-color: #848484 !important;
        color: white !important;
    }
    .dataTables_processing {
        top: 155px !important;
        z-index: 11000000 !important;
    }

    #dtf {
        height: 550px;
        overflow: auto;
    }

    #dtf th {
        border: 2px solid #000000 !important;
        background-color: #c5f5ff;
    }

    /*td,th {padding: .5em 1em;}
    tbody:before {
         This doesn't work because of border-collapse 
        line-height:1em;
        content:"_";
        color:white;
        display:block;
    }*/

    #DtCash_filter {
        display: none;
    }

    #DtCash_wrapper {
        position: relative;
        clear: both;
        width: auto;
        max-height: 550px;
        margin-left: 0px;
        /*        border-bottom: 1px solid black; .dataTables_wrapper
                border-top: 1px solid black;
                border-left: 1px solid black;
                border-right: 1px solid black;
                background-color: #9D9C9D; */
        /*zoom: 1;*/
    }

    /*.float{
        position:fixed;
        width:60px;
        height:60px;
        bottom:40px;
        
        background-color:#008a8a;
        color:#fff;
        border-radius:50px;
        text-align:center;
        box-shadow: 2px 2px 3px #000;
    }*/

</style>
<!-- begin widget-chart -->
<div class="widget widget-rounded m-b-30">
    
    <!-- begin widget-header -->
    <!-- <div class="widget-header">
        <h4 class="widget-header-title">Monthly Forecast</h4>
        <div class="widget-header-icon"><a href="#" class="text-muted"><i class="fa fa-fw fa-upload"></i></a></div>
        <div class="widget-header-icon"><a href="#" class="text-muted"><i class="fa fa-fw fa-cog"></i></a></div>
    </div> -->
    <!-- end widget-header -->
    <!-- begin vertical-box -->
    <div class="vertical-box with-grid with-border-top">
        <!-- begin vertical-box-column -->
        <div class="vertical-box-column p-15" style="width: 15%;">
            <div class="widget-chart-info">
				<!-- <div class="widget-chart-info-progress">
                    <b>Month :</b>
                    <select class="form-control category" data-size="10" id='category' data-plugin='select2' name='category' data-live-search="true" data-style="btn-primary">
                        <option value="ALL" selected>Select a Category</option>
                    </select>
                </div> -->
                <div class="widget-chart-info-progress">
                    <b>Company Group</b>
                    <select class="form-control COMPANYGROUP" id="COMPANYGROUP" name='COMPANYGROUP'>
						<option value="" selected>Select</option>
                        <option value="CMT">CEMENT</option><option value="MOTIVE">MOTIVE</option><option value="PLT">PLANTATION</option><option value="PROPERTY">PROPERTY</option><option value="WOOD">WOOD</option>
                    </select>
                </div>
                <div class="widget-chart-info-progress">
                    <b>Company SubGroup</b>
                    <select class="form-control COMPANYSUBGROUP" id="COMPANYSUBGROUP" name='COMPANYSUBGROUP'>
                        <option value="" selected>Select</option>
                        <option value="UPSTREAM">UPSTREAM</option>
                        <option value="DOWNSTREAM">DOWNSTREAM</option></select>
                    </select>
                </div>
                <div class="widget-chart-info-progress">
                    <b>Company</b>
                    <select class="form-control company" id="COMPANY">
                        <option value="" selected>Select</option>
                       <?php foreach ($DtCompany as $values) {
                                                echo '<option value="' . $values->ID . '">' . $values->COMPANYCODE . ' - ' . $values->COMPANYNAME . '</option>';
                                            }
                                            ?>
                    </select>
                </div>
                <div class="widget-chart-info-progress">
                    <b>Year :</b>
                    <input type="text" class="form-control" name="PERIOD" id="PERIOD" autocomplete="off">
                </div>
                <div class="widget-chart-info">
                    <label>Angka dalam <strong>Jutaan</strong>.</label>
                </div>
				<!-- <div class="widget-chart-info-progress">
                    <b>Cashflow Type :</b>
                    <select class="form-control cashflowtype" data-size="10" id='cashflowtype' data-plugin='select2' name='cashflowtype' data-live-search="true" data-style="btn-primary">
                        <option value="ALL" selected>Select a Cashflow Type</option>
                    </select>
                </div>
				<div class="widget-chart-info-progress">
                    <b>Category :</b>
                    <select class="form-control category" data-size="10" id='category' data-plugin='select2' name='category' data-live-search="true" data-style="btn-primary">
                        <option value="ALL" selected>Select a Category</option>
                    </select>
                </div> -->
            </div>
            <div class="widget-chart-info">
                <button type="button" id="btnSave" onclick="loadData()" class="btn btn-success btn-sm">Submit</button>
            </div>
            <div class="dt-buttons btn-group"><a class="btn btn-default btn-sm btn-green mt-4 xport" tabindex="0" aria-controls="DtCash"><span>Excel</span></a></div>
        </div>
        <!-- end vertical-box-column -->
        <!-- begin vertical-box-column -->
        
        <div class="vertical-box-column">
			<div class="alert alert-secondary fade show">
				<div class="tab-content" >
                    
                        <button id="myelement" class="btn btn-info btn-sm mb-2" >Fullscreen Dev</button>
                    
					<div class="row m-0 table-responsive" id="dtf">
					<table id="DtCash" class="table table-striped table-bordered nowrap" cellspacing="0" width="100%" aria-describedby="DtCash_info" style="border:1px solid black">
						<thead style="box-shadow: black 0px 0px 6px 8px;">
							<tr role="row">
								<!-- <th rowspan="2" class="text-center sorting_asc" aria-sort="ascending" style="width: 30px;">No</th> -->
								<th rowspan="2" class="text-center no-sort">TYPE</th>								
								<th rowspan="2" class="text-center no-sort">CATEGORY</th>
								<th rowspan="2" class="text-center no-sort">Description</th>
								<th colspan="6" class="text-center no-sort">Forecast</th>
								<th colspan="6" class="text-center no-sort">Actual</th>
								<th colspan="6" class="text-center no-sort">Variance</th>
							</tr>
							<tr role="row">
								<th class="text-center no-sort W1">W1</th>
								<th class="text-center no-sort W2">W2</th>
								<th class="text-center no-sort W3">W3</th>
								<th class="text-center no-sort W4">W4</th>
								<th class="text-center no-sort W5">W5</th>
                                <th class="text-center no-sort TotalFor">Total</th>
								<th class="text-center no-sort W1">W1</th>
                                <th class="text-center no-sort W2">W2</th>
                                <th class="text-center no-sort W3">W3</th>
                                <th class="text-center no-sort W4">W4</th>
                                <th class="text-center no-sort W5">W5</th>
                                <th class="text-center no-sort TotalAct">Total</th>
								<th class="text-center no-sort W1">W1</th>
                                <th class="text-center no-sort W2">W2</th>
                                <th class="text-center no-sort W3">W3</th>
                                <th class="text-center no-sort W4">W4</th>
                                <th class="text-center no-sort W5">W5</th>
                                <th class="text-center no-sort TotalVar">Total</th>
							</tr>
						</thead>
                        <tfoot>
                            <!-- surplus -->
                            <tr>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th class="SP1 text-right"></th>
                                <th class="SP2 text-right"></th>
                                <th class="SP3 text-right"></th>
                                <th class="SP4 text-right"></th>
                                <th class="SP5 text-right"></th>
                                <th class="TOTALSP text-right" style="background:burlywood;"></th>
                                <th class="SA1 text-right"></th>
                                <th class="SA2 text-right"></th>
                                <th class="SA3 text-right"></th>
                                <th class="SA4 text-right"></th>
                                <th class="SA5 text-right"></th>
                                <th class="TOTALSA text-right" style="background:burlywood;"></th>
                                <th class="SV1 text-right"></th>
                                <th class="SV2 text-right"></th>
                                <th class="SV3 text-right"></th>
                                <th class="SV4 text-right"></th>
                                <th class="SV5 text-right"></th>
                                <th class="TOTALSV text-right" style="background:burlywood;"></th>
                            </tr>
                            <!-- end surplus -->
                            <tr>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th class="TP1 text-right"></th>
                                <th class="TP2 text-right"></th>
                                <th class="TP3 text-right"></th>
                                <th class="TP4 text-right"></th>
                                <th class="TP5 text-right"></th>
                                <th class="TOTALTP text-right" style="background:burlywood;"></th>
                                <th class="TA1 text-right"></th>
                                <th class="TA2 text-right"></th>
                                <th class="TA3 text-right"></th>
                                <th class="TA4 text-right"></th>
                                <th class="TA5 text-right"></th>
                                <th class="TOTALTA text-right" style="background:burlywood;"></th>
                                <th class="TV1 text-right"></th>
                                <th class="TV2 text-right"></th>
                                <th class="TV3 text-right"></th>
                                <th class="TV4 text-right"></th>
                                <th class="TV5 text-right"></th>
                                <th class="TOTALTV text-right" style="background:burlywood;"></th>
                            </tr>
                            <tr>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th class="IP1 text-right"></th>
                                <th class="IP2 text-right"></th>
                                <th class="IP3 text-right"></th>
                                <th class="IP4 text-right"></th>
                                <th class="IP5 text-right"></th>
                                <th class="TOTALIP text-right" style="background:burlywood;"></th>
                                <th class="IA1 text-right"></th>
                                <th class="IA2 text-right"></th>
                                <th class="IA3 text-right"></th>
                                <th class="IA4 text-right"></th>
                                <th class="IA5 text-right"></th>
                                <th class="TOTALIA text-right" style="background:burlywood;"></th>
                                <th class="IV1 text-right"></th>
                                <th class="IV2 text-right"></th>
                                <th class="IV3 text-right"></th>
                                <th class="IV4 text-right"></th>
                                <th class="IV5 text-right"></th>
                                <th class="TOTALIV text-right" style="background:burlywood;"></th>
                            </tr>
                            <tr>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th class="OP1 text-right"></th>
                                <th class="OP2 text-right"></th>
                                <th class="OP3 text-right"></th>
                                <th class="OP4 text-right"></th>
                                <th class="OP5 text-right"></th>
                                <th class="TOTALOP text-right" style="background:burlywood;"></th>
                                <th class="OA1 text-right"></th>
                                <th class="OA2 text-right"></th>
                                <th class="OA3 text-right"></th>
                                <th class="OA4 text-right"></th>
                                <th class="OA5 text-right"></th>
                                <th class="TOTALOA text-right" style="background:burlywood;"></th>
                                <th class="OV1 text-right"></th>
                                <th class="OV2 text-right"></th>
                                <th class="OV3 text-right"></th>
                                <th class="OV4 text-right"></th>
                                <th class="OV5 text-right"></th>
                                <th class="TOTALOV text-right" style="background:burlywood;"></th>
                            </tr>
                            <!-- opbal -->
                            <tr>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th  class="OP1 text-right"></th>
                                <th  class="OP2 text-right"></th>
                                <th  class="OP3 text-right"></th>
                                <th  class="OP4 text-right"></th>
                                <th  class="OP5 text-right"></th>
                                <th  class="TOTALOP text-right" style="background:burlywood;"></th>
                                <th  class="OA1 text-right"></th>
                                <th  class="OA2 text-right"></th>
                                <th  class="OA3 text-right"></th>
                                <th  class="OA4 text-right"></th>
                                <th  class="OA5 text-right"></th>
                                <th  class="TOTALOA text-right" style="background:burlywood;"></th>
                                <th  class="OV1 text-right"></th>
                                <th  class="OV2 text-right"></th>
                                <th  class="OV3 text-right"></th>
                                <th  class="OV4 text-right"></th>
                                <th  class="OV5 text-right"></th>
                                <th  class="TOTALOV text-right" style="background:burlywood;"></th>
                            </tr>
                            <!-- endbal -->
                            <tr>
                                <th></th>
                                <th></th>
                                <th></th>
                                <th  class="text-right"></th>
                                <th  class="text-right"></th>
                                <th  class="text-right"></th>
                                <th  class="text-right"></th>
                                <th  class="text-right"></th>
                                <th  class="text-right" style="background:burlywood;"></th>
                                <th  class="text-right"></th>
                                <th  class="text-right"></th>
                                <th  class="text-right"></th>
                                <th  class="text-right"></th>
                                <th  class="text-right"></th>
                                <th  class="text-right" style="background:burlywood;"></th>
                                <th  class="text-right"></th>
                                <th  class="text-right"></th>
                                <th  class="text-right"></th>
                                <th  class="text-right"></th>
                                <th  class="text-right"></th>
                                <th  class="text-right" style="background:burlywood;"></th>
                            </tr>
                        </tfoot>
					</table>
					</div>
				</div>
			</div>
        </div>
        <!-- end vertical-box-column -->
    </div>
    <!-- end vertical-box -->
</div>
<!-- end widget-chart -->
<script type="text/javascript">
    $( "#myelement" ).click(function() {     
        if($('.p-15:visible').length){
            $('#myelement').text('Minimize');
            $('#myelement').css('background', 'orange');
            $('.p-15').hide("slide", { direction: "left" }, 500);
        }
        else{
            $('#myelement').text('Fullscreen');
            $('#myelement').css('background-color', '#49b6d6');
            $('.p-15').show("slide", { direction: "left" }, 500);        
        }
    });
</script>
<script>
    let statusCard = document.querySelector('#dtf');
    // add scroll event listener for change head's position 
    statusCard.addEventListener('scroll', e => {
        let tableHead = document.querySelector('thead');
        let scrollTop = statusCard.scrollTop;
        tableHead.style.transform = 'translateY(' + scrollTop + 'px)';
    })
    var ADDS = <?php echo $ACCESS['ADDS']; ?>;
    var EDITS = <?php echo $ACCESS['EDITS']; ?>;
    var DELETES = <?php echo $ACCESS['DELETES']; ?>;
    var USERNAME = "<?php echo $SESSION->FCCODE; ?>";
    var getUrlParameter = function getUrlParameter(sParam) {
        var sPageURL = decodeURIComponent(window.location.search.substring(1)),
                sURLVariables = sPageURL.split('&'),
                sParameterName,
                i;
        for (i = 0; i < sURLVariables.length; i++) {
            sParameterName = sURLVariables[i].split('=');
            if (sParameterName[0] === sParam) {
                return sParameterName[1] === undefined ? true : sParameterName[1];
            }
        }
    };
    var files, filetypeUpload = ['XLS', 'XLSX'];
    var DtCash = [];
    var STATUS = true;
    const queryString = window.location.href;
    const type = queryString.substr(queryString.lastIndexOf('/') + 1);
    var ListBulan = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
    var MONTH, YEAR;
    //IP ( interco IN prop) IA ( interco IN actual ) IV ( interco IN variance)
    var IP1,IP2,IP3,IP4,IP5,totalIP,IA1,IA2,IA3,IA4,IA5,totalIA,IV1,IV2,IV3,IV4,IV5,totalIV;
    //IP ( interco OUT prop) IA ( interco OUT actual ) IV ( interco OUT variance)
    var OP1,OP2,OP3,OP4,OP5,totalOP,OA1,OA2,OA3,OA4,OA5,totalOA,OV1,OV2,OV3,OV4,OV5,totalOV;
    // TI total interco
    var TP1,TP2,TP3,TP4,TP5,totalTP,TA1,TA2,TA3,TA4,TA5,totalTA,TV1,TV2,TV3,TV4,TV5,totalTV;
    //P ( prop ) A ( actual ) V ( variance )
    var P1,P2,P3,P4,P5,A1,A2,A3,A4,A5,V1,V2,V3,V4,V5,totalProp,totalAct,totalVar;
    // POB ( prop opbal ) AOB ( actual opbal ) VOB ( variance opbal )
    var POB1,POB2,POB3,POB4,POB5,totalPOB,AOB1,AOB2,AOB3,AOB4,AOB5,totalAOB,VOB1,VOB2,VOB3,VOB4,VOB5,totalVOB;
    // PEB ( prop endbal ) AEB ( actual endbal) VOB ( variance endbal )
    var PEB1,PEB2,PEB3,PEB4,PEB5,totalPEB,AEB1,AEB2,AEB3,AEB4,AEB5,totalAEB,VEB1,VEB2,VEB3,VEB4,VEB5,totalVEB;
    //
    var totalA1,totalA2,totalA3,totalA4,totalA5;

    if (type === 'CashOut') {
        $('.title').text('Cash Out');
        var newtype = 1
    } else {
        $('.title').text('Yearly Forecast');
        var newtype = 0
    }

    $(".xport").click(function(){
        // $('<table>')
        // .append(
        //      $("#DtCash").html()
        //  )
        //  .append(
        //     $("#DtCash").DataTable().$('tfoot').clone()
        //  )
        $('<table>')
        .append($(table.table().header()).clone())
        .append($(table.table().body()).clone())
        .append($(table.table().tr()).clone())
        .append($(table.table().footer()).clone())
        // .append(table.$('tr').clone())
         .table2excel({
                    exclude: "",
                    name: "Report Monthly Forecast",
                    filename: "MonthlyForecast_"+MONTH+'_'+YEAR, // do include extension
                    fileext: ".xls",
                    preserveColors: true // set to true if you want background colors and font colors preserved
                });
        });

    //$('#EXTSYSTEM').val('');
    if (!$.fn.DataTable.isDataTable('#DtCash')) {
        $('#DtCash').DataTable({
            processing: true,
            // fixedHeader: true,
			// "dom": "Bfrtip",
			// 	"buttons": [{
			// 		extend: "excel",
   //                  footer: true ,
			// 		className: "btn-xs btn-green mb-4",
   //                  customize: (xlsx, config, dataTable) => {
   //                    let sheet = xlsx.xl.worksheets['sheet1.xml'];
   //                    let footerIndex = $('sheetData row', sheet).length;
   //                    let $footerRows = $('tr', dataTable.footer());

   //                    // If there are more than one footer rows
   //                    if ($footerRows.length > 1) {
   //                      // First header row is already present, so we start from the second row (i = 1)
   //                      for (let i = 1; i < $footerRows.length; i++) {
   //                        // Get the current footer row
   //                        let $footerRow = $footerRows[i];

   //                        // Get footer row columns
   //                        let $footerRowCols = $('th', $footerRow);

   //                        // Increment the last row index
   //                        footerIndex++;

   //                        // Create the new header row XML using footerIndex and append it at sheetData
   //                        $('sheetData', sheet).append(`
   //                          <row r="${footerIndex}">
   //                            ${$footerRowCols.map((index, el) => `
   //                              <c t="inlineStr" r="${String.fromCharCode(62 + index)}${footerIndex}" s="2">
   //                                <is>
   //                                  <t xml:space="preserve">${$(el).text()}</t>
   //                                </is>
   //                              </c>
   //                            `).get().join('')}
   //                          </row>
   //                        `);
   //                      }
   //                    }
   //                  }
			// 	}],
            "aaData": DtCash,
            "language": {
                                processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading..n.</span> '
            },
            "columns": [
				{ "data": "CFTYPE", "orderable": false },
				{ "data": "FORECAST_CATEGORY", "orderable": false },
                { "width": "100px", "data": "FINANCEGROUP", "orderable": false, 'className': '' },
				{ "data": null, "className": "text-right", 
					render: function (data, type, row, meta) {
                            var numFormat   = $.fn.dataTable.render.number(',', '.', 0).display;
                            var res = data.PROPW1 ;// parseInt(1000000);
                            if(res == "0"){
                                res = "-";
                            }
                            else if(res < 0.04){
                                res = 0;
                            }
                            else{
                                res = res;
                            }
                            var f = numFormat(res);
                            return f;
                            
                        }, "orderable": false },
                { "data": null, "className": "text-right", render: function (data, type, row, meta) {
                            var numFormat   = $.fn.dataTable.render.number(',', '.', 0).display;
                            var res = data.PROPW2 ;// parseInt(1000000);
                            if(res == "0"){
                                res = "-";
                            }
                            else if(res < 0.04){
                                res = 0;
                            }
                            else{
                                res = res;
                            }
                            var f = numFormat(res);
                            return f;
                            
                        }, "orderable": false },
                { "data": null, "className": "text-right", render: function (data, type, row, meta) {
                            var numFormat   = $.fn.dataTable.render.number(',', '.', 0).display;
                            var res = data.PROPW3 ;// parseInt(1000000);
                            if(res == "0"){
                                res = "-";
                            }
                            else if(res < 0.04){
                                res = 0;
                            }
                            else{
                                res = res;
                            }
                            var f = numFormat(res);
                            return f;
                            
                        }, "orderable": false },
                { "data": null, "className": "text-right", render: function (data, type, row, meta) {
                            var numFormat   = $.fn.dataTable.render.number(',', '.', 0).display;
                            var res = data.PROPW4 ;// parseInt(1000000);
                            if(res == "0"){
                                res = "-";
                            }
                            else if(res < 0.04){
                                res = 0;
                            }
                            else{
                                res = res;
                            }
                            var f = numFormat(res);
                            return f;
                            
                        }, "orderable": false },
                { "data": null, "className": "text-right", render: function (data, type, row, meta) {
                            var numFormat   = $.fn.dataTable.render.number(',', '.', 0).display;
                            var res = data.PROPW5 ;// parseInt(1000000);
                            if(res == "0"){
                                res = "-";
                            }
                            else if(res < 0.04){
                                res = 0;
                            }
                            else{
                                res = res;
                            }
                            var f = numFormat(res);
                            return f;
                            
                        }, "orderable": false },
                { "data": null, "className": "text-right", render: function (data, type, row, meta) {
                    var intVal = function (i) {
                        return typeof i === 'string' ?
                                i.replace(/[\$,]/g, '') * 1 :
                                typeof i === 'number' ?
                                i : 0;
                    };
                    var numFormat   = $.fn.dataTable.render.number(',', '.', 0).display;
                    var res = (intVal(data.PROPW1) + intVal(data.PROPW2) + intVal(data.PROPW3) + intVal(data.PROPW4) + intVal(data.PROPW5) / 1000000);// parseInt(1000000);
                            if(res == "0"){
                                res = "-";
                            }
                            else if(res < 0.04){
                                res = 0;
                            }
                            else{
                                res = res;
                            }
                    var f = numFormat(res);
                    return f;
                    }, "orderable": false },
				{ "data": null, "className": "text-right", render: function (data, type, row, meta) {
                            var numFormat   = $.fn.dataTable.render.number(',', '.', 0).display;
                            var res = data.WACTUAL1 ;// parseInt(1000000);
                            
                            if(res == "0"){
                                res = "-";
                            }
                            else if(res < 0.04){
                                res = 0;
                            }
                            else{
                                res = res;
                            }
                            var f = numFormat(res);
                            return f;
                            
                        }, "orderable": false },
				{ "data": null, "className": "text-right", render: function (data, type, row, meta) {
                            var numFormat   = $.fn.dataTable.render.number(',', '.', 0).display;
                            var res = data.WACTUAL2 ;// parseInt(1000000);
                            if(res == "0"){
                                res = "-";
                            }
                            else if(res < 0.04){
                                res = 0;
                            }
                            else{
                                res = res;
                            }
                            var f = numFormat(res);
                            return f;
                            
                        }, "orderable": false },
				{ "data": null, "className": "text-right", render: function (data, type, row, meta) {
                            var numFormat   = $.fn.dataTable.render.number(',', '.', 0).display;
                            var res = data.WACTUAL3 ;// parseInt(1000000);
                            if(res == "0"){
                                res = "-";
                            }
                            else if(res < 0.04){
                                res = 0;
                            }
                            else{
                                res = res;
                            }
                            var f = numFormat(res);
                            return f;
                            
                        }, "orderable": false },
				{ "data": null, "className": "text-right", render: function (data, type, row, meta) {
                            var numFormat   = $.fn.dataTable.render.number(',', '.', 0).display;
                            var res = data.WACTUAL4 ;// parseInt(1000000);
                            if(res == "0"){
                                res = "-";
                            }
                            else if(res < 0.04){
                                res = 0;
                            }
                            else{
                                res = res;
                            }
                            var f = numFormat(res);
                            return f;
                            
                        }, "orderable": false },
				{ "data": null, "className": "text-right", render: function (data, type, row, meta) {
                            var numFormat   = $.fn.dataTable.render.number(',', '.', 0).display;
                            var res = data.WACTUAL5 ;// parseInt(1000000);
                            if(res == "0"){
                                res = "-";
                            }
                            else if(res < 0.04){
                                res = 0;
                            }
                            else{
                                res = res;
                            }
                            var f = numFormat(res);
                            return f;
                            
                        }, "orderable": false },
                { "data": null, "className": "text-right", render: function (data, type, row, meta) {
                    var intVal = function (i) {
                        return typeof i === 'string' ?
                                i.replace(/[\$,]/g, '') * 1 :
                                typeof i === 'number' ?
                                i : 0;
                    };
                    var numFormat   = $.fn.dataTable.render.number(',', '.', 0).display;
                    var res = (intVal(data.WACTUAL1) + intVal(data.WACTUAL2) + intVal(data.WACTUAL3) + intVal(data.WACTUAL4) + intVal(data.WACTUAL5));// parseInt(1000000);
                    if(res == "0"){
                                res = "-";
                            }
                            else if(res < 0.04){
                                res = 0;
                            }
                            else{
                                res = res;
                            }
                    var f = numFormat(res);
                    return f;
                    
                    }, "orderable": false },
				{ "data": null, "className": "text-right", render: function (data, type, row, meta) {
                            var numFormat   = $.fn.dataTable.render.number(',', '.', 0).display;
                            var res = data.WAVAR1 ;// parseInt(1000000);
                            if(res == "0"){
                                res = "-";
                            }
                            else if(res < 0.04){
                                res = 0;
                            }
                            else{
                                res = res;
                            }
                            var f = numFormat(res);
                            return f;
                            
                        }, "orderable": false },
				{ "data": null, "className": "text-right", render: function (data, type, row, meta) {
                            var numFormat   = $.fn.dataTable.render.number(',', '.', 0).display;
                            var res = data.WAVAR2 ;// parseInt(1000000);
                            if(res == "0"){
                                res = "-";
                            }
                            else if(res < 0.04){
                                res = 0;
                            }
                            else{
                                res = res;
                            }
                            var f = numFormat(res);
                            return f;
                            
                        }, "orderable": false },
				{ "data": null, "className": "text-right", render: function (data, type, row, meta) {
                            var numFormat   = $.fn.dataTable.render.number(',', '.', 0).display;
                            var res = data.WAVAR3 ;// parseInt(1000000);
                            if(res == "0"){
                                res = "-";
                            }
                            else if(res < 0.04){
                                res = 0;
                            }
                            else{
                                res = res;
                            }
                            var f = numFormat(res);
                            return f;
                            
                        }, "orderable": false },
				{ "data": null, "className": "text-right", render: function (data, type, row, meta) {
                            var numFormat   = $.fn.dataTable.render.number(',', '.', 0).display;
                            var res = data.WAVAR4 ;// parseInt(1000000);
                            if(res == "0"){
                                res = "-";
                            }
                            else if(res < 0.04){
                                res = 0;
                            }
                            else{
                                res = res;
                            }
                            var f = numFormat(res);
                            return f;
                            
                        }, "orderable": false },
				{ "data": null, "className": "text-right", render: function (data, type, row, meta) {
                            var numFormat   = $.fn.dataTable.render.number(',', '.', 0).display;
                            var res = data.WAVAR5 ;// parseInt(1000000);
                            if(res == "0"){
                                res = "-";
                            }
                            else if(res < 0.04){
                                res = 0;
                            }
                            else{
                                res = res;
                            }
                            var f = numFormat(res);
                            return f;
                            
                        }, "orderable": false },
                { "data": null, "className": "text-right", render: function (data, type, row, meta) {
                    var intVal = function (i) {
                        return typeof i === 'string' ?
                                i.replace(/[\$,]/g, '') * 1 :
                                typeof i === 'number' ?
                                i : 0;
                    };
                    var numFormat   = $.fn.dataTable.render.number(',', '.', 0).display;
                    var res = (intVal(data.WAVAR1) + intVal(data.WAVAR2) + intVal(data.WAVAR3) + intVal(data.WAVAR4) + intVal(data.WAVAR5)/ 1000000);// parseInt(1000000);
                    if(res == "0"){
                        res = "-";
                    }
                    else if(res < 0.04){
                        res = 0;
                    }
                    else{
                        res = res;
                    }
                    var f = numFormat(res);
                    return f;
                    
                    }, "orderable": false }
            ],
            "bProcessing": true,
            "bFilter": true,
            "bPaginate": false,
            "bLengthChange": false,
            "bInfo": false,
            "responsive": false,
            // "order": [[ 0, 'asc' ], [ 1, 'asc' ]],
            "columnDefs": [
                {"visible": false, "targets": [0, 1]}
            ],
            rowGroup: {
                dataSrc: ["CFTYPE", "FORECAST_CATEGORY"],
                startRender: function (rows, group) {

                    // console.log(group);
                    // return group;
                    var intVal = function (i) {
                        return typeof i === 'string' ?
                                i.replace(/[\$,]/g, '') * 1 :
                                typeof i === 'number' ?
                                i : 0;
                    };
                    var PROPW1 = rows.data().pluck('PROPW1').reduce(function (a, b) {
                        return (intVal(a) + intVal(b)) ;// parseInt(1000000) ;
                    }, 0);
                    var PROPW2 = rows.data().pluck('PROPW2').reduce(function (a, b) {
                        return (intVal(a) + intVal(b)) ;// parseInt(1000000);
                    }, 0);
                    var PROPW3 = rows.data().pluck('PROPW3').reduce(function (a, b) {
                        return (intVal(a) + intVal(b)) ;// parseInt(1000000);
                    }, 0);
                    var PROPW4 = rows.data().pluck('PROPW4').reduce(function (a, b) {
                        return (intVal(a) + intVal(b)) ;// parseInt(1000000);
                    }, 0);
                    var PROPW5 = rows.data().pluck('PROPW5').reduce(function (a, b) {
                        return (intVal(a) + intVal(b)) ;// parseInt(1000000);
                    }, 0);
                    var WACTUAL1 = rows.data().pluck('WACTUAL1').reduce(function (a, b) {
                        return (intVal(a) + intVal(b)) ;// parseInt(1000000);
                    }, 0);
                    var WACTUAL2 = rows.data().pluck('WACTUAL2').reduce(function (a, b) {
                        return (intVal(a) + intVal(b)) ;// parseInt(1000000);
                    }, 0);
                    var WACTUAL3 = rows.data().pluck('WACTUAL3').reduce(function (a, b) {
                        return (intVal(a) + intVal(b)) ;// parseInt(1000000);
                    }, 0);
                    var WACTUAL4 = rows.data().pluck('WACTUAL4').reduce(function (a, b) {
                        return (intVal(a) + intVal(b)) ;// parseInt(1000000);
                    }, 0);
                    var WACTUAL5 = rows.data().pluck('WACTUAL5').reduce(function (a, b) {
                        return (intVal(a) + intVal(b)) ;// parseInt(1000000);
                    }, 0);
                    var WAVAR1 = rows.data().pluck('WAVAR1').reduce(function (a, b) {
                        return (intVal(a) + intVal(b)) ;// parseInt(1000000);
                    }, 0);
                    var WAVAR2 = rows.data().pluck('WAVAR2').reduce(function (a, b) {
                        return (intVal(a) + intVal(b)) ;// parseInt(1000000);
                    }, 0);
                    var WAVAR3 = rows.data().pluck('WAVAR3').reduce(function (a, b) {
                        return (intVal(a) + intVal(b)) ;// parseInt(1000000);
                    }, 0);
                    var WAVAR4 = rows.data().pluck('WAVAR4').reduce(function (a, b) {
                        return (intVal(a) + intVal(b)) ;// parseInt(1000000);
                    }, 0);
                    var WAVAR5 = rows.data().pluck('WAVAR5').reduce(function (a, b) {
                        return (intVal(a) + intVal(b)) ;// parseInt(1000000);
                    }, 0);

                    var numFormat = $.fn.dataTable.render.number(',', '.', 0).display;

                    var totPROP     = Math.round(PROPW1+PROPW2+PROPW3+PROPW4+PROPW5);
                    var totWACTUAL  = Math.round(WACTUAL1+WACTUAL2+WACTUAL3+WACTUAL4+WACTUAL5);
                    var totVAR      = Math.round(WAVAR1+WAVAR2+WAVAR3+WAVAR4+WAVAR5);

                    return $('<tr/>')
                        .append('<td>TOTAL ' + group + '</td>')
                        .append('<td class="text-right">' + numFormat(PROPW1) + '</td>')
                        .append('<td class="text-right">' + numFormat(PROPW2) + '</td>')
                        .append('<td class="text-right">' + numFormat(PROPW3) + '</td>')
                        .append('<td class="text-right">' + numFormat(PROPW4) + '</td>')
                        .append('<td class="text-right">' + numFormat(PROPW5) + '</td>')
                        .append('<td class="text-right PropTot" data-value="'+totPROP+'" style="background-color: burlywood;">' + numFormat(totPROP) + '</td>')
                        .append('<td class="text-right">' + numFormat(WACTUAL1) + '</td>')
                        .append('<td class="text-right">' + numFormat(WACTUAL2) + '</td>')
                        .append('<td class="text-right">' + numFormat(WACTUAL3) + '</td>')
                        .append('<td class="text-right">' + numFormat(WACTUAL4) + '</td>')
                        .append('<td class="text-right">' + numFormat(WACTUAL5) + '</td>')
                        .append('<td class="text-right ActTot" data-value="'+totWACTUAL+'" style="background-color: burlywood;">' + numFormat(totWACTUAL) + '</td>')
                        .append('<td class="text-right">' + numFormat(WAVAR1) + '</td>')
                        .append('<td class="text-right">' + numFormat(WAVAR2) + '</td>')
                        .append('<td class="text-right">' + numFormat(WAVAR3) + '</td>')
                        .append('<td class="text-right">' + numFormat(WAVAR4) + '</td>')
                        .append('<td class="text-right">' + numFormat(WAVAR5) + '</td>')
                        .append('<td class="text-right VarTot" data-value="'+totVAR+'" style="background-color: burlywood;">' + numFormat(totVAR) + '</td>')
                        ;
                    
                    
                },
                endRender: null
                // function(rows, group) {
                //         var intVal = function(i) {
                //             return typeof i === 'string' ?
                //                 i.replace(/[\$,]/g, '') * 1 :
                //                 typeof i === 'number' ?
                //                 i : 0;
                //         };
                //         vAMOUNT = 0;
                //         vPAYAMOUNT = 0;
                       
                //         var vAMOUNT = rows.data().pluck('1').reduce(function (a, b) {
                //             return intVal(a) + intVal(b);
                //         }, 0);
                //         var vPAYAMOUNT = rows.data().pluck('2').reduce(function (a, b) {
                //             return intVal(a) + intVal(b);
                //         }, 0);
                        
                //         var numFormat = $.fn.dataTable.render.number('\,', '.', 0).display;
                //         return $('<tr/>')
                //             .append('<td class="text-right">Total</td>')
                //             .append('<td class="text-right">' + numFormat(vAMOUNT) + '</td>')
                //             .append('<td class="text-right">' + numFormat(vPAYAMOUNT) + '</td>')
                //             .append('<td colspan="16"></td>');
                //     },
            },
            "footerCallback": function ( row, data, start, end, display ) {
                    var api = this.api(), data;
         
                    // Remove the formatting to get integer data for summation
                    var intVal = function ( i ) {
                        return typeof i === 'string' ?
                            i.replace(/[\$,]/g, '')*1 :
                            typeof i === 'number' ?
                                i : 0;
                    };

                            
                    // var totalCashIn = $('.PropTot').data('value') + $('.ActTot').data('value') + $('.VarTot').data('value');

                    // console.log(totalCashIn);
                    // numFormat(amount) + ' | '+ 
                    // numFormat(basic_conv) + ' | '+ 
                    var numFormat = $.fn.dataTable.render.number('\,', '.', 0).display;
                    setTimeout(function() {
                        $( api.column( 2 ).footer() ).html(numFormat('SURPLUS / DEFICIT'));
                        $( api.column( 3 ).footer() ).html(numFormat(P1));
                        $( api.column( 4 ).footer() ).html(numFormat(P2));
                        $( api.column( 5 ).footer() ).html(numFormat(P3));
                        $( api.column( 6 ).footer() ).html(numFormat(P4));
                        $( api.column( 7 ).footer() ).html(numFormat(P5));
                        $( api.column( 8 ).footer() ).html(numFormat(totalProp));
                        $( api.column( 9 ).footer() ).html(numFormat(A1));
                        $( api.column( 10 ).footer() ).html(numFormat(A2));
                        $( api.column( 11 ).footer() ).html(numFormat(A3));
                        $( api.column( 12 ).footer() ).html(numFormat(A4));
                        $( api.column( 13 ).footer() ).html(numFormat(A5));
                        $( api.column( 14 ).footer() ).html(numFormat(totalAct));
                        $( api.column( 15 ).footer() ).html(numFormat(V1));
                        $( api.column( 16 ).footer() ).html(numFormat(V2));
                        $( api.column( 17 ).footer() ).html(numFormat(V3));
                        $( api.column( 18 ).footer() ).html(numFormat(V4));
                        $( api.column( 19 ).footer() ).html(numFormat(V5));
                        $( api.column( 20 ).footer() ).html(numFormat(totalVar));
                        $('tr:eq(1) th:eq(0)', api.table().footer()).html(numFormat('TOTAL INTERCO'));
                        $('tr:eq(1) th:eq(1)', api.table().footer()).html(numFormat(TP1));
                        $('tr:eq(1) th:eq(2)', api.table().footer()).html(numFormat(TP2));
                        $('tr:eq(1) th:eq(3)', api.table().footer()).html(numFormat(TP3));
                        $('tr:eq(1) th:eq(4)', api.table().footer()).html(numFormat(TP4));
                        $('tr:eq(1) th:eq(5)', api.table().footer()).html(numFormat(TP5));
                        $('tr:eq(1) th:eq(6)', api.table().footer()).html(numFormat(totalTP));
                        $('tr:eq(1) th:eq(7)', api.table().footer()).html(numFormat(TA1));
                        $('tr:eq(1) th:eq(8)', api.table().footer()).html(numFormat(TA2));
                        $('tr:eq(1) th:eq(9)', api.table().footer()).html(numFormat(TA3));
                        $('tr:eq(1) th:eq(10)', api.table().footer()).html(numFormat(TA4));
                        $('tr:eq(1) th:eq(11)', api.table().footer()).html(numFormat(TA5));
                        $('tr:eq(1) th:eq(12)', api.table().footer()).html(numFormat(totalTA));
                        $('tr:eq(1) th:eq(13)', api.table().footer()).html(numFormat(TV1));
                        $('tr:eq(1) th:eq(14)', api.table().footer()).html(numFormat(TV2));
                        $('tr:eq(1) th:eq(15)', api.table().footer()).html(numFormat(TV3));
                        $('tr:eq(1) th:eq(16)', api.table().footer()).html(numFormat(TV4));
                        $('tr:eq(1) th:eq(17)', api.table().footer()).html(numFormat(TV5));
                        $('tr:eq(1) th:eq(18)', api.table().footer()).html(numFormat(totalTV));
                        $('tr:eq(2) th:eq(0)', api.table().footer()).html('IN');
                        $('tr:eq(2) th:eq(1)', api.table().footer()).html((IP1 == "0" ? '-' : numFormat(IP1)));
                        $('tr:eq(2) th:eq(2)', api.table().footer()).html((IP2 == "0" ? '-' : numFormat(IP2)));
                        $('tr:eq(2) th:eq(3)', api.table().footer()).html((IP3 == "0" ? '-' : numFormat(IP3)));
                        $('tr:eq(2) th:eq(4)', api.table().footer()).html((IP4 == "0" ? '-' : numFormat(IP4)));
                        $('tr:eq(2) th:eq(5)', api.table().footer()).html((IP5 == "0" ? '-' : numFormat(IP5)));
                        $('tr:eq(2) th:eq(6)', api.table().footer()).html((totalIP == "0" ? '-' : numFormat(totalIP)));
                        $('tr:eq(2) th:eq(7)', api.table().footer()).html((IA1 == "0" ? '-' : numFormat(IA1)));
                        $('tr:eq(2) th:eq(8)', api.table().footer()).html((IA2 == "0" ? '-' : numFormat(IA2)));
                        $('tr:eq(2) th:eq(9)', api.table().footer()).html((IA3 == "0" ? '-' : numFormat(IA3)));
                        $('tr:eq(2) th:eq(10)', api.table().footer()).html((IA4 == "0" ? '-' : numFormat(IA4)));
                        $('tr:eq(2) th:eq(11)', api.table().footer()).html((IA5 == "0" ? '-' : numFormat(IA5)));
                        $('tr:eq(2) th:eq(12)', api.table().footer()).html((totalIA == "0" ? '-' : numFormat(totalIA)));
                        $('tr:eq(2) th:eq(13)', api.table().footer()).html((IV1 == "0" ? '-' : numFormat(IV1)));
                        $('tr:eq(2) th:eq(14)', api.table().footer()).html((IV2 == "0" ? '-' : numFormat(IV2)));
                        $('tr:eq(2) th:eq(15)', api.table().footer()).html((IV3 == "0" ? '-' : numFormat(IV3)));
                        $('tr:eq(2) th:eq(16)', api.table().footer()).html((IV4 == "0" ? '-' : numFormat(IV4)));
                        $('tr:eq(2) th:eq(17)', api.table().footer()).html((IV5 == "0" ? '-' : numFormat(IV5)));
                        $('tr:eq(2) th:eq(18)', api.table().footer()).html((totalIV == "0" ? '-' : numFormat(totalIV)));
                        $('tr:eq(3) th:eq(0)', api.table().footer()).html('OUT');
                        $('tr:eq(3) th:eq(1)', api.table().footer()).html((OP1 == "0" ? '-' : numFormat(OP1)));
                        $('tr:eq(3) th:eq(2)', api.table().footer()).html((OP2 == "0" ? '-' : numFormat(OP2)));
                        $('tr:eq(3) th:eq(3)', api.table().footer()).html((OP3 == "0" ? '-' : numFormat(OP3)));
                        $('tr:eq(3) th:eq(4)', api.table().footer()).html((OP4 == "0" ? '-' : numFormat(OP4)));
                        $('tr:eq(3) th:eq(5)', api.table().footer()).html((OP5 == "0" ? '-' : numFormat(OP5)));
                        $('tr:eq(3) th:eq(6)', api.table().footer()).html((totalOP == "0" ? '-' : numFormat(totalOP)));
                        $('tr:eq(3) th:eq(7)', api.table().footer()).html((OA1 == "0" ? '-' : numFormat(OA1)));
                        $('tr:eq(3) th:eq(8)', api.table().footer()).html((OA2 == "0" ? '-' : numFormat(OA2)));
                        $('tr:eq(3) th:eq(9)', api.table().footer()).html((OA3 == "0" ? '-' : numFormat(OA3)));
                        $('tr:eq(3) th:eq(10)', api.table().footer()).html((OA4 == "0" ? '-' : numFormat(OA4)));
                        $('tr:eq(3) th:eq(11)', api.table().footer()).html((OA5 == "0" ? '-' : numFormat(OA5)));
                        $('tr:eq(3) th:eq(12)', api.table().footer()).html((totalOA == "0" ? '-' : numFormat(totalOA)));
                        $('tr:eq(3) th:eq(13)', api.table().footer()).html((OV1 == "0" ? '-' : numFormat(OV1)));
                        $('tr:eq(3) th:eq(14)', api.table().footer()).html((OV2 == "0" ? '-' : numFormat(OV2)));
                        $('tr:eq(3) th:eq(15)', api.table().footer()).html((OV3 == "0" ? '-' : numFormat(OV3)));
                        $('tr:eq(3) th:eq(16)', api.table().footer()).html((OV4 == "0" ? '-' : numFormat(OV4)));
                        $('tr:eq(3) th:eq(17)', api.table().footer()).html((OV5 == "0" ? '-' : numFormat(OV5)));
                        $('tr:eq(3) th:eq(18)', api.table().footer()).html((totalOV == "0" ? '-' : numFormat(totalOV)));
                        $('tr:eq(4) th:eq(0)', api.table().footer()).html('OPENING BALANCE');
                        $('tr:eq(4) th:eq(1)', api.table().footer()).html(POB1 == "0" ? '-' : numFormat(POB1));
                        $('tr:eq(4) th:eq(2)', api.table().footer()).html(POB2 == "0" ? '-' : numFormat(POB2));
                        $('tr:eq(4) th:eq(3)', api.table().footer()).html(POB3 == "0" ? '-' : numFormat(POB3));
                        $('tr:eq(4) th:eq(4)', api.table().footer()).html(POB4 == "0" ? '-' : numFormat(POB4));
                        $('tr:eq(4) th:eq(5)', api.table().footer()).html(POB5 == "0" ? '-' : numFormat(POB5));
                        $('tr:eq(4) th:eq(6)', api.table().footer()).html(totalPOB == "0" ? '-' : numFormat(totalPOB));
                        $('tr:eq(4) th:eq(7)', api.table().footer()).html(AOB1 == "0" ? '-' : numFormat(AOB1));
                        $('tr:eq(4) th:eq(8)', api.table().footer()).html(AOB2 == "0" ? '-' : numFormat(AOB2));
                        $('tr:eq(4) th:eq(9)', api.table().footer()).html(AOB3 == "0" ? '-' : numFormat(AOB3));
                        $('tr:eq(4) th:eq(10)', api.table().footer()).html(AOB4 == "0" ? '-' : numFormat(AOB4));
                        $('tr:eq(4) th:eq(11)', api.table().footer()).html(AOB5 == "0" ? '-' : numFormat(AOB5));
                        $('tr:eq(4) th:eq(12)', api.table().footer()).html(totalAOB == "0" ? '-' :  numFormat(totalAOB));
                        $('tr:eq(4) th:eq(13)', api.table().footer()).html('-');
                        $('tr:eq(4) th:eq(14)', api.table().footer()).html('-');
                        $('tr:eq(4) th:eq(15)', api.table().footer()).html('-');
                        $('tr:eq(4) th:eq(16)', api.table().footer()).html('-');
                        $('tr:eq(4) th:eq(17)', api.table().footer()).html('-');
                        $('tr:eq(4) th:eq(18)', api.table().footer()).html('-');
                        $('tr:eq(5) th:eq(0)', api.table().footer()).html('ENDING BALANCE');
                        $('tr:eq(5) th:eq(1)', api.table().footer()).html(PEB1 == "0" ? '-' : numFormat(PEB1));
                        $('tr:eq(5) th:eq(2)', api.table().footer()).html(PEB2 == "0" ? '-' : numFormat(PEB2));
                        $('tr:eq(5) th:eq(3)', api.table().footer()).html(PEB3 == "0" ? '-' : numFormat(PEB3));
                        $('tr:eq(5) th:eq(4)', api.table().footer()).html(PEB4 == "0" ? '-' : numFormat(PEB4));
                        $('tr:eq(5) th:eq(5)', api.table().footer()).html(PEB5 == "0" ? '-' : numFormat(PEB5));
                        $('tr:eq(5) th:eq(6)', api.table().footer()).html(totalPEB == "0" ? '-' : numFormat(totalPEB));
                        $('tr:eq(5) th:eq(7)', api.table().footer()).html(AEB1 == "0" ? '-' : numFormat(AEB1));
                        $('tr:eq(5) th:eq(8)', api.table().footer()).html(AEB2 == "0" ? '-' : numFormat(AEB2));
                        $('tr:eq(5) th:eq(9)', api.table().footer()).html(AEB3 == "0" ? '-' : numFormat(AEB3));
                        $('tr:eq(5) th:eq(10)', api.table().footer()).html(AEB4 == "0" ? '-' : numFormat(AEB4));
                        $('tr:eq(5) th:eq(11)', api.table().footer()).html(AEB5 == "0" ? '-' : numFormat(AEB5));
                        $('tr:eq(5) th:eq(12)', api.table().footer()).html(totalAEB == "0" ? '-' : numFormat(totalAEB));
                        $('tr:eq(5) th:eq(13)', api.table().footer()).html('-');
                        $('tr:eq(5) th:eq(14)', api.table().footer()).html('-');
                        $('tr:eq(5) th:eq(15)', api.table().footer()).html('-');
                        $('tr:eq(5) th:eq(16)', api.table().footer()).html('-');
                        $('tr:eq(5) th:eq(17)', api.table().footer()).html('-');
                        $('tr:eq(5) th:eq(18)', api.table().footer()).html('-');
                        // $('tr:eq(2) th:eq(1)', api.table().footer()).html(numFormat(totalEnbal));
                    }, 3000);
                    // $('tr:eq(1) td:eq(3)', api.table().footer()).html(totalSurplus, '');
            },

        });
        table = $('#DtCash').DataTable();
    }


        $('#PERIOD').datepicker({
            "autoclose": true,
            "todayHighlight": true,
            "viewMode": "months",
            "minViewMode": "months",
            "format": "M yyyy",
            "orientation": "bottom"
        });
    var COMPANYGROUP = '', COMPANYSUBGROUP= '', COMPANY = '';
    $(document).on('change', '#COMPANYGROUP', function () {            
        COMPANYGROUP = $(this).val();
        if(COMPANYGROUP == 'CMT'){
            $.ajax({
                url : "<?php echo site_url('Cash/getSubgroup');?>",
                method : "POST",
                data : {GROUP: COMPANYGROUP},
                async : true,
                dataType : 'json',
                success: function(data){
                            // console.log(data.result);
                            var listSub = '';
                            var i;
                            for(i=0; i<data.result.data.length; i++){
                                listSub += '<option value='+data.result.data[i].FCCODE+'>'+data.result.data[i].FCCODE_GROUP+'</option>';
                            }
                            $('#COMPANYSUBGROUP').html(listSub);
                            $('#loader').removeClass('show');
                            
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            $('#loader').removeClass('show');
                            alert('Please Check Your Connection !!!');
                        }
            });
            COMPANYGROUP = 'CEMENT';
            $.ajax({
                url : "<?php echo site_url('Cash/getCompany');?>",
                method : "POST",
                data : {SUBGROUP: COMPANYGROUP},
                async : true,
                dataType : 'json',
                success: function(data){
                            // console.log(data.result);
                            var listCompany = '';
                            var i;
                            for(i=0; i<data.result.data.length; i++){
                                listCompany += '<option value='+data.result.data[i].ID+'>'+data.result.data[i].COMPANYCODE+' - '+data.result.data[i].COMPANYNAME+'</option>';
                            }
                            $('#COMPANY').html(listCompany);
                            $('#loader').removeClass('show');
                            
                        },
                        error: function(jqXHR, textStatus, errorThrown) {
                            $('#loader').removeClass('show');
                            alert('Please Check Your Connection !!!');
                        }
            });
        }
        else{
            var listSub = '<option value="">All</option> <option value="UPSTREAM">UPSTREAM</option><option value="DOWNSTREAM">DOWNSTREAM</option>';
            $('#COMPANYSUBGROUP').html(listSub);
            if($('#COMPANYSUBGROUP').val() == '' || $('#COMPANYSUBGROUP').val() == null){
                var selectCompany = <?php echo json_encode($DtCompany) ?>;
                var mSel = '';
                for (i in selectCompany) {
                  
                    mSel+= '<option value="'+ selectCompany[i].ID +'">'+ selectCompany[i].COMPANYCODE + ' - ' + selectCompany[i].COMPANYNAME +'</option>';
                }
                mSel+= '<option >Select Company</option></select>';
                $('#COMPANY').html(mSel);
                $('#loader').removeClass('show');
            }
        }
        
    });

    $(document).on('change', '#COMPANYSUBGROUP', function () {            
        COMPANYSUBGROUP = $(this).val();
        // LoadDataTable()
    });

    $(document).on('change', '#COMPANY', function () {            
        COMPANY = $(this).val();
        // LoadDataTable()
    });

    $("#COMPANYSUBGROUP").on({
        'change': function() {
            $('#loader').addClass('show');
            var value = $(this).val();
            if(value == '' || value == null){
                var selectCompany = <?php echo json_encode($DtCompany) ?>;
                var mSel = '';
                for (i in selectCompany) {
                  
                    mSel+= '<option value="'+ selectCompany[i].ID +'">'+ selectCompany[i].COMPANYCODE + ' - ' + selectCompany[i].COMPANYNAME +'</option>';
                }
                mSel+= '<option >Select Company</option></select>';
                $('#COMPANY').html(mSel);
                $('#loader').removeClass('show');
            }
            else {
                $.ajax({
                    url : "<?php echo site_url('Cash/getCompany');?>",
                    method : "POST",
                    data : {SUBGROUP: value},
                    async : true,
                    dataType : 'json',
                    success: function(data){
                                // console.log(data.result);
                                var listCompany = '';
                                var i;
                                for(i=0; i<data.result.data.length; i++){
                                    listCompany += '<option value='+data.result.data[i].ID+'>'+data.result.data[i].COMPANYCODE+' - '+data.result.data[i].COMPANYNAME+'</option>';
                                }
                                $('#COMPANY').html(listCompany);
                                $('#loader').removeClass('show');
                                
                            },
                            error: function(jqXHR, textStatus, errorThrown) {
                                $('#loader').removeClass('show');
                                alert('Please Check Your Connection !!!');
                            }
                });
            }
            return false;
            // loadData();
        }
    });

    function loadData() {
            // $('.page-loader').addClass('show');
            MONTH = ListBulan.indexOf($('#PERIOD').val().substr(0, 3)) + 1;
            YEAR = $('#PERIOD').val().substr(4, 4);
            $('#DtCash_processing').removeAttr("style");
            $("#DtCash_processing").show();
            $.ajax({
                    dataType: "JSON",
                    type: "POST",
                    url: "<?php echo site_url('Cash/getTotalInterco'); ?>",
                    data: {
                        MONTH: parseInt(MONTH),
                        YEAR: YEAR,
                        COMPANYGROUP: COMPANYGROUP,
                        COMPANYSUBGROUP: $('#COMPANYSUBGROUP').val(),
                        COMPANY:COMPANY
                    },success: function(get) {
                        
                        // totalSurplus = (res.result.data[0].TOTAL - res.result.data[1].TOTAL);
                        TP1 = get.result.data.PROP1;
                        TP2 = get.result.data.PROP2;
                        TP3 = get.result.data.PROP3;
                        TP4 = get.result.data.PROP4;
                        TP5 = get.result.data.PROP5;
                        totalTP = (parseFloat(TP1) + parseFloat(TP2) + parseFloat(TP3) + parseFloat(TP4) + parseFloat(TP5));
                        TA1 = get.result.data.ACTUAL1;
                        TA2 = get.result.data.ACTUAL2;
                        TA3 = get.result.data.ACTUAL3;
                        TA4 = get.result.data.ACTUAL4;
                        TA5 = get.result.data.ACTUAL5;
                        totalTA = (parseFloat(TA1) + parseFloat(TA2) + parseFloat(TA3) + parseFloat(TA4) + parseFloat(TA5));
                        TV1 = get.result.data.VAR1;
                        TV2 = get.result.data.VAR2;
                        TV3 = get.result.data.VAR3;
                        TV4 = get.result.data.VAR4;
                        TV5 = get.result.data.VAR5;
                        totalTV = (parseFloat(TV1) + parseFloat(TV2) + parseFloat(TV3) + parseFloat(TV4) + parseFloat(TV5));
                        // totalInterco = res.result.data[2].TOTAL;
                        // console.log(totalSurplus);
                    }
            });

            $.ajax({
                    dataType: "JSON",
                    type: "POST",
                    url: "<?php echo site_url('Cash/getInOutInterco'); ?>",
                    data: {
                        MONTH: parseInt(MONTH),
                        YEAR: YEAR,
                        COMPANYGROUP: COMPANYGROUP,
                        COMPANYSUBGROUP: $('#COMPANYSUBGROUP').val(),
                        COMPANY:COMPANY
                    },success: function(get) {
                        
                        
                        IP1 = get.result.data[0].PROP1;
                        IP2 = get.result.data[0].PROP2;
                        IP3 = get.result.data[0].PROP3;
                        IP4 = get.result.data[0].PROP4;
                        IP5 = get.result.data[0].PROP5;
                        totalIP = (parseFloat(IP1) + parseFloat(IP2) + parseFloat(IP3) + parseFloat(IP4) + parseFloat(IP5));
                        IA1 = get.result.data[0].ACTUAL1;
                        IA2 = get.result.data[0].ACTUAL2;
                        IA3 = get.result.data[0].ACTUAL3;
                        IA4 = get.result.data[0].ACTUAL4;
                        IA5 = get.result.data[0].ACTUAL5;
                        totalIA = (parseFloat(IA1) + parseFloat(IA2) + parseFloat(IA3) + parseFloat(IA4) + parseFloat(IA5));
                        IV1 = get.result.data[0].VAR1;
                        IV2 = get.result.data[0].VAR2;
                        IV3 = get.result.data[0].VAR3;
                        IV4 = get.result.data[0].VAR4;
                        IV5 = get.result.data[0].VAR5;
                        totalIV = (parseFloat(IV1) + parseFloat(IV2) + parseFloat(IV3) + parseFloat(IV4) + parseFloat(IV5));

                        OP1 = get.result.data[1].PROP1;
                        OP2 = get.result.data[1].PROP2;
                        OP3 = get.result.data[1].PROP3;
                        OP4 = get.result.data[1].PROP4;
                        OP5 = get.result.data[1].PROP5;
                        totalOP = (parseFloat(OP1) + parseFloat(OP2) + parseFloat(OP3) + parseFloat(OP4) + parseFloat(OP5));
                        OA1 = get.result.data[1].ACTUAL1;
                        OA2 = get.result.data[1].ACTUAL2;
                        OA3 = get.result.data[1].ACTUAL3;
                        OA4 = get.result.data[1].ACTUAL4;
                        OA5 = get.result.data[1].ACTUAL5;
                        totalOA = (parseFloat(OA1) + parseFloat(OA2) + parseFloat(OA3) + parseFloat(OA4) + parseFloat(OA5));
                        OV1 = get.result.data[1].VAR1;
                        OV2 = get.result.data[1].VAR2;
                        OV3 = get.result.data[1].VAR3;
                        OV4 = get.result.data[1].VAR4;
                        OV5 = get.result.data[1].VAR5;
                        totalOV = (parseFloat(OV1) + parseFloat(OV2) + parseFloat(OV3) + parseFloat(OV4) + parseFloat(OV5));
                        
                    }
            });

            $.ajax({
                    async: false,
                    dataType: "JSON",
                    type: "POST",
                    url: "<?php echo site_url('Cash/getSurplus'); ?>",
                    data: {
                        MONTH: parseInt(MONTH),
                        YEAR: YEAR,
                        COMPANYGROUP: COMPANYGROUP,
                        COMPANYSUBGROUP: $('#COMPANYSUBGROUP').val(),
                        COMPANY:COMPANY
                    },success: function(res) {
                        
                        // totalSurplus = (res.result.data[0].TOTAL - res.result.data[1].TOTAL);
                        P1 = res.result.data.PROP1;
                        P2 = res.result.data.PROP2;
                        P3 = res.result.data.PROP3;
                        P4 = res.result.data.PROP4;
                        P5 = res.result.data.PROP5;
                        totalProp = res.result.data.TOTALPROP;
                        A1 = res.result.data.ACTUAL1;
                        A2 = res.result.data.ACTUAL2;
                        A3 = res.result.data.ACTUAL3;
                        A4 = res.result.data.ACTUAL4;
                        A5 = res.result.data.ACTUAL5;
                        totalAct = res.result.data.TOTALACT;
                        V1 = res.result.data.VAR1;
                        V2 = res.result.data.VAR2;
                        V3 = res.result.data.VAR3;
                        V4 = res.result.data.VAR4;
                        V5 = res.result.data.VAR5;
                        totalVar = res.result.data.TOTALVAR;
                        // totalInterco = res.result.data[2].TOTAL;
                        // console.log(totalSurplus);
                    }
            });

            $.ajax({
                    async: false,
                    dataType: "JSON",
                    type: "POST",
                    url: "<?php echo site_url('Cash/getOpbal'); ?>",
                    data: {
                        MONTH: parseInt(MONTH),
                        YEAR: YEAR,
                        COMPANYGROUP: COMPANYGROUP,
                        COMPANYSUBGROUP: $('#COMPANYSUBGROUP').val(),
                        COMPANY:COMPANY
                    },success: function(hasil) {
                        
                        // console.log(hasil.result.data);
                        if(hasil.result.data.OPBAL){                         
                            POB1 = hasil.result.data.OPBAL;
                            AOB1 = hasil.result.data.OPBAL;
                            PEB1 = parseFloat(POB1) + parseFloat(P1);

                            POB2 = PEB1;
                            PEB2 = parseFloat(PEB1) + parseFloat(P2);

                            POB3 = PEB2;
                            PEB3 = parseFloat(PEB2) + parseFloat(P3);

                            POB4 = PEB3;
                            PEB4 = parseFloat(PEB3) + parseFloat(P4);

                            POB5 = PEB4;
                            PEB5 = parseFloat(PEB4) + parseFloat(P5);

                            totalPEB = PEB5;
                            totalPOB = POB1;
                        }else{
                            POB1 = hasil.result.data.ENDING_BALANCE;
                            
                            totalPOB = POB1;
                            AOB1 = hasil.result.data.ENDING_BALANCE;
                            // AOB2 = hasil.result.data.ACTUAL2;
                            // AOB3 = hasil.result.data.ACTUAL3;
                            // AOB4 = hasil.result.data.ACTUAL4;
                            // AOB5 = hasil.result.data.ACTUAL5;
                            // totalAOB = hasil.result.data.TOTALACT;
                            // VOB1 = hasil.result.data.VAR1;
                            // VOB2 = hasil.result.data.VAR2;
                            // VOB3 = hasil.result.data.VAR3;
                            // VOB4 = hasil.result.data.VAR4;
                            // VOB5 = hasil.result.data.VAR5;
                            // totalVOB = hasil.result.data.TOTALVAR;
                            PEB1 = parseFloat(POB1) + parseFloat(P1);    
                            PEB2 = parseFloat(PEB1) + parseFloat(P2);
                            PEB3 = parseFloat(PEB2) + parseFloat(P3);
                            PEB4 = parseFloat(PEB3) + parseFloat(P4);
                            PEB5 = parseFloat(PEB4) + parseFloat(P5);
                        }


                        // console.log(AEB1)
                        
                    }
            });

             // setTimeout(function() {
            $.ajax({
                        dataType: "JSON",
                        type: 'POST',
                        url: "<?php echo site_url('Cash/ShowData'); ?>",
                        data: {
                                    MONTH: parseInt(MONTH),
                                    YEAR: YEAR,
                                    COMPANYGROUP: COMPANYGROUP,
                                    COMPANYSUBGROUP: $('#COMPANYSUBGROUP').val(),
                                    COMPANY:COMPANY
                                },
                        success: function (response) {
                            // $('#loader').addClass('show');
                            //alert(response.data);
                            if (response.status == 200) {
                                STATUS = true;
                                DtCash = response.result.data;
                                $('html, body').animate({
                                    scrollTop: $("#dtf").offset().top
                                }, 1000);
                                table.clear();
                                table.rows.add(DtCash);
                                table.draw();
                                $('.dtrg-level-0 td:contains("TOTAL INTERCO")').parent().hide();

                                POB2 = PEB1;
                                POB3 = PEB2;
                                POB4 = PEB3;
                                POB5 = PEB4;
                                totalPEB = PEB5;
                                AEB1 =  parseFloat(TA1) + parseFloat(A1) + parseFloat(POB1);
                                // console.log(TA1)
                                // console.log(A1)
                                // console.log(POB1)
                                AOB2 = AEB1;

                                AEB2 = parseFloat(TA2) + parseFloat(A2) + parseFloat(AEB1);
                                AOB3 = AEB2;

                                AEB3 =  parseFloat(TA3) + parseFloat(A3) + parseFloat(AEB2);
                                AOB4 = AEB3;

                                AEB4 =  parseFloat(TA4) + parseFloat(A4) + parseFloat(AEB3);
                                AOB5 = AEB4;

                                AEB5 =  parseFloat(TA5) + parseFloat(A5) + parseFloat(AEB4);

                                totalAOB = POB1;
                                totalAEB = AEB5;

                                VOB1 = parseFloat(POB1) - parseFloat(AOB1);
                                VOB2 = parseFloat(POB2) - parseFloat(AOB2);
                                VOB3 = parseFloat(POB3) - parseFloat(AOB3);
                                VOB4 = parseFloat(POB4) - parseFloat(AOB4);
                                VOB5 = parseFloat(POB5) - parseFloat(AOB5);
                                totalVOB = parseFloat(VOB1) + parseFloat(VOB2) + parseFloat(VOB3) + parseFloat(VOB4) + parseFloat(VOB5);

                                VEB1 = parseFloat(AEB1) - parseFloat(PEB1);
                                VEB2 = parseFloat(AEB2) - parseFloat(PEB2);
                                VEB3 = parseFloat(AEB3) - parseFloat(PEB3);
                                VEB4 = parseFloat(AEB4) - parseFloat(PEB4);
                                VEB5 = parseFloat(AEB5) - parseFloat(PEB5);
                                totalVEB = VEB1 + VEB2 + VEB3 + VEB4 + VEB5;

                                // console.log(PROPW1);

                                $.ajax({
                                        // async: false,
                                        dataType: "JSON",
                                        type: "POST",
                                        url: "<?php echo site_url('Cash/submitOpbal'); ?>",
                                        data: {
                                            MONTH: parseInt(MONTH),
                                            YEAR: YEAR,
                                            COMPANYGROUP: COMPANYGROUP,
                                            COMPANYSUBGROUP: $('#COMPANYSUBGROUP').val(),
                                            COMPANY:COMPANY,
                                            CASH: parseFloat(AEB5)
                                        },success: function(resp) {
                                            
                                            // console.log(hasil.result.data);
                                            if (resp.status == 200) {
                                                $('#DtCash_processing').hide();
                                            }else{
                                                alert(resp.result.data);
                                                $('#DtCash_processing').hide();
                                            }


                                            // console.log(AEB1)
                                            
                                        }
                                });

                                $('#DtCash_processing').hide();
                                
                            } else if (response.status == 504) {
                                alert(response.result.data);
                                location.reload();
                            } else {
                                alert(response.result.data);
                            }
                            $.ajax({
                                        dataType: "JSON",
                                        type: "POST",
                                        url: "<?php echo site_url('Forecast/WeekHeader'); ?>",
                                        data: {
                                            YEAR: YEAR,
                                            MONTH: MONTH
                                        },success: function(response) {
                                            // console.log(response);
                                            $(".W1").removeClass("text-right");
                                            $(".W2").removeClass("text-right");
                                            $(".W3").removeClass("text-right");
                                            $(".W4").removeClass("text-right");
                                            $(".W5").removeClass("text-right");
                                            $(".TotalFor").removeClass("text-right");
                                            $(".TotalAct").removeClass("text-right");
                                            $(".TotalVar").removeClass("text-right");
                                            $('.W1').text(response[0].DATEFROM + '-' + response[0].DATEUNTIL);
                                            $('.W2').text(response[1].DATEFROM + '-' + response[1].DATEUNTIL);
                                            $('.W3').text(response[2].DATEFROM + '-' + response[2].DATEUNTIL);
                                            $('.W4').text(response[3].DATEFROM + '-' + response[3].DATEUNTIL);
                                            $('.W5').text(response[4].DATEFROM + '-' + response[4].DATEUNTIL);
                                            
                                        }
                            });
                            
                        },
                        error: function (e) {
                            // console.info(e);
                            $('#DtCash_processing').hide();
                            alert('Error Get Data !!');
                            files = '';
                            $('.upload-file').val('');
                        }
            });
            // $('.page-loader').removeClass('d-none');
            // $('.page-loader').addClass('d-none');
            // $('#DtCash_processing').hide();
             // },1000);
            
    }
    
</script>