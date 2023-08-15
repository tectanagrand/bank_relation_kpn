<?php
defined('BASEPATH') OR exit('No direct script access allowed');
 
class Welcome_model extends CI_Model {
	
	//query get current production
	function cur_production(){
		$a = $this->db->query(" Select
YEAR,
MONTH,
0 BANKBALANCE,
Round(SUM(CASHIN))CASHIN,
Round(SUM(CASHOUT))CASHOUT,
SUM(CASHIN)-SUM(CASHOUT) PROFITLOS
FROM
(
Select
YEAR, 
MONTH,
DECODE (DOCTYPENAME,'CASH IN',CASH,0)CASHIN,
DECODE (DOCTYPENAME,'CASH OUT',CASH,0)CASHOUT
from(
Select
DOCTYPENAME,
YEAR, 
TO_CHAR(TO_DATE(MONTH, 'MM'), 'Mon') MONTH,
SUM(FORC_AMT_W1)+ SUM(FORC_AMT_W2)+SUM(FORC_AMT_W3)+SUM(FORC_AMT_W4)+SUM(FORC_AMT_W5)CASH
from (
Select
DECODE (DOCTYPE.CASHFLOWTYPE,0,'CASH IN','CASH OUT')DOCTYPENAME,
foregroup.FCNAME,
matgroup.fcname as groups,
DEPARTMENT, W4.DOCTYPE, DOCREF, YEAR, MONTH,
W1, W2, W3, W4, W5,
W4.MATERIAL,
ITEM_AMOUNT_W4 - FORC_AMT_W4 ITEM_AMOUNT_W5,
SUM(ITEM_AMOUNT_W4 - FORC_AMT_W4) OVER (PARTITION BY DEPARTMENT, DOCREF, YEAR, MONTH) TOTAL_ITEM_AMOUNT_W5,
DECODE(SUM(ITEM_AMOUNT_W4 - FORC_AMT_W4) OVER (PARTITION BY DEPARTMENT, DOCREF, YEAR, MONTH),0,0,(ITEM_AMOUNT_W4 - FORC_AMT_W4) / SUM(ITEM_AMOUNT_W4 - FORC_AMT_W4) OVER (PARTITION BY DEPARTMENT, DOCREF, YEAR, MONTH) * 100) PERCENT_W5,
FORC_AMT_W1,
FORC_AMT_W2,
FORC_AMT_W3,
FORC_AMT_W4,
DECODE(SUM(ITEM_AMOUNT_W4 - FORC_AMT_W4) OVER (PARTITION BY DEPARTMENT, DOCREF, YEAR, MONTH),0,0,((ITEM_AMOUNT_W4 - FORC_AMT_W4) / SUM(ITEM_AMOUNT_W4 - FORC_AMT_W4) OVER (PARTITION BY DEPARTMENT, DOCREF, YEAR, MONTH)* 100)/100 * W4) FORC_AMT_W5
from (
Select
DEPARTMENT, DOCTYPE, DOCREF, YEAR, MONTH,
W1, W2, W3, W4, W5,
MATERIAL,
ITEM_AMOUNT_W3 - FORC_AMT_W3 ITEM_AMOUNT_W4,
SUM(ITEM_AMOUNT_W3 - FORC_AMT_W3) OVER (PARTITION BY DEPARTMENT, DOCREF, YEAR, MONTH) TOTAL_ITEM_AMOUNT_W4,
(ITEM_AMOUNT_W3 - FORC_AMT_W3) / SUM(ITEM_AMOUNT_W3 - FORC_AMT_W3) OVER (PARTITION BY DEPARTMENT, DOCREF, YEAR, MONTH) * 100 PERCENT_W4,
FORC_AMT_W1,
FORC_AMT_W2,
FORC_AMT_W3,
DECODE(SUM(ITEM_AMOUNT_W3 - FORC_AMT_W3) OVER (PARTITION BY DEPARTMENT, DOCREF, YEAR, MONTH),0,0,((ITEM_AMOUNT_W3 - FORC_AMT_W3) / SUM(ITEM_AMOUNT_W3 - FORC_AMT_W3) OVER (PARTITION BY DEPARTMENT, DOCREF, YEAR, MONTH)* 100)/100 * W4) FORC_AMT_W4
from (
Select
DEPARTMENT, DOCTYPE, DOCREF, YEAR, MONTH,
W1, W2, W3, W4, W5,
MATERIAL,
ITEM_AMOUNT_W2 - FORC_AMT_W2 ITEM_AMOUNT_W3,
SUM(ITEM_AMOUNT_W2 - FORC_AMT_W2) OVER (PARTITION BY DEPARTMENT, DOCREF, YEAR, MONTH) TOTAL_ITEM_AMOUNT_W3,
(ITEM_AMOUNT_W2 - FORC_AMT_W2) / SUM(ITEM_AMOUNT_W2 - FORC_AMT_W2) OVER (PARTITION BY DEPARTMENT, DOCREF, YEAR, MONTH) * 100 PERCENT_W3,
FORC_AMT_W1,
FORC_AMT_W2,
DECODE((SUM(ITEM_AMOUNT_W2 - FORC_AMT_W2) OVER (PARTITION BY DEPARTMENT, DOCREF, YEAR, MONTH)* 100),0,0,((ITEM_AMOUNT_W2 - FORC_AMT_W2) / SUM(ITEM_AMOUNT_W2 - FORC_AMT_W2) OVER (PARTITION BY DEPARTMENT, DOCREF, YEAR, MONTH)* 100)/100 * W3) FORC_AMT_W3
from (
Select
DEPARTMENT, DOCTYPE, DOCREF, YEAR, MONTH,
W1, W2, W3, W4, W5,
MATERIAL,
ITEM_AMOUNT_W1 - FORC_AMT_W1 ITEM_AMOUNT_W2,
SUM(ITEM_AMOUNT_W1 - FORC_AMT_W1) OVER (PARTITION BY DEPARTMENT, DOCREF, YEAR, MONTH) TOTAL_ITEM_AMOUNT_W2,
(ITEM_AMOUNT_W1 - FORC_AMT_W1) / SUM(ITEM_AMOUNT_W1 - FORC_AMT_W1) OVER (PARTITION BY DEPARTMENT, DOCREF, YEAR, MONTH) * 100 PERCENT_W2,
FORC_AMT_W1,
DECODE(SUM(ITEM_AMOUNT_W1 - FORC_AMT_W1) OVER (PARTITION BY DEPARTMENT, DOCREF, YEAR, MONTH),0,0,((ITEM_AMOUNT_W1 - FORC_AMT_W1) / SUM(ITEM_AMOUNT_W1 - FORC_AMT_W1) OVER (PARTITION BY DEPARTMENT, DOCREF, YEAR, MONTH)* 100)/100 * W2) FORC_AMT_W2
from
(
Select
DEPARTMENT, DOCTYPE, DOCREF, YEAR, MONTH,
W1, W2, W3, W4, W5,
NOTINVDET.MATERIAL,
NOTINVDET.AMOUNT_INCLUDE_VAT ITEM_AMOUNT_W1,
SUM(NOTINVDET.AMOUNT_INCLUDE_VAT) OVER (PARTITION BY DEPARTMENT, DOCREF, YEAR, MONTH) TOTAL_ITEM_AMOUNT_W1,
NOTINVDET.AMOUNT_INCLUDE_VAT / SUM(NOTINVDET.AMOUNT_INCLUDE_VAT) OVER (PARTITION BY DEPARTMENT, DOCREF, YEAR, MONTH) * 100 PERCENT_W1,
DECODE(SUM(NOTINVDET.AMOUNT_INCLUDE_VAT) OVER (PARTITION BY DEPARTMENT, DOCREF, YEAR, MONTH),0,0,(NOTINVDET.AMOUNT_INCLUDE_VAT / SUM(NOTINVDET.AMOUNT_INCLUDE_VAT) OVER (PARTITION BY DEPARTMENT, DOCREF, YEAR, MONTH) * 100)/100*W1) FORC_AMT_W1
from
(
									select 
									FX.DEPARTMENT,
									NVL(NOTINV.ID,INV.ID) NOTINVID,
									NVL(NOTINV.DOCTYPE,INV.DOCTYPE) DOCTYPE,
									INV.DOCREF,
									FX.YEAR,
									FX.MONTH,
									SUM(DECODE(FX.WEEK,'W1',FX.AMOUNTADJS,0)) W1,
									SUM(DECODE(FX.WEEK,'W2',FX.AMOUNTADJS,0)) W2,
									SUM(DECODE(FX.WEEK,'W3',FX.AMOUNTADJS,0)) W3,
									SUM(DECODE(FX.WEEK,'W4',FX.AMOUNTADJS,0)) W4,
									SUM(DECODE(FX.WEEK,'W5',FX.AMOUNTADJS,0)) W5
									from 
									FORECAST_FIX FX
									INNER JOIN CF_TRANSACTION INV ON FX.CFTRANSID = INV.ID
									LEFT JOIN CF_TRANSACTION NOTINV ON INV.DOCREF = NOTINV.DOCNUMBER and NOTINV.DOCTYPE !='INV'
									Where FX.YEAR = 2020 and FX.MONTH = 4
									GROUP BY FX.DEPARTMENT, NVL(NOTINV.ID,INV.ID), NVL(NOTINV.DOCTYPE,INV.DOCTYPE), INV.DOCREF, FX.YEAR, FX.MONTH
) HDR01
INNER JOIN CF_TRANSACTION_DET NOTINVDET ON HDR01.NOTINVID = NOTINVDET.ID
)W1
)W2
)W3
)W4
inner join material_groupitem mat on W4.material = mat.material
inner join material_group matgroup on mat.materialgroup = matgroup.id
inner join forecast_category foregroup on matgroup.forecast_category = foregroup.fccode
inner join doctype on W4.doctype = doctype.fccode
) Finalx
GROUP BY DOCTYPENAME, YEAR, MONTH
ORDER BY DOCTYPENAME, YEAR, TO_DATE(MONTH, 'MM')
) Finalxx
)Finalxxx
GROUP BY YEAR, MONTH");
		return $a->result();
	 }
	 //query get current production chart
	 function cur_production_chart(){
		$a = $this->db->query("Select
YEAR,
MONTH,
Round(SUM(CASHIN))CASHIN,
Round(SUM(CASHOUT))CASHOUT
FROM
(
Select
YEAR, 
MONTH,
DECODE (DOCTYPENAME,'CASH IN',CASH,0)CASHIN,
DECODE (DOCTYPENAME,'CASH OUT',CASH,0)CASHOUT
from(
Select
DOCTYPENAME,
YEAR, 
TO_CHAR(TO_DATE(MONTH, 'MM'), 'Mon') MONTH,
SUM(FORC_AMT_W1)+ SUM(FORC_AMT_W2)+SUM(FORC_AMT_W3)+SUM(FORC_AMT_W4)+SUM(FORC_AMT_W5)CASH
from (
Select
DECODE (DOCTYPE.CASHFLOWTYPE,0,'CASH IN','CASH OUT')DOCTYPENAME,
foregroup.FCNAME,
matgroup.fcname as groups,
DEPARTMENT, W4.DOCTYPE, DOCREF, YEAR, MONTH,
W1, W2, W3, W4, W5,
W4.MATERIAL,
ITEM_AMOUNT_W4 - FORC_AMT_W4 ITEM_AMOUNT_W5,
SUM(ITEM_AMOUNT_W4 - FORC_AMT_W4) OVER (PARTITION BY DEPARTMENT, DOCREF, YEAR, MONTH) TOTAL_ITEM_AMOUNT_W5,
DECODE(SUM(ITEM_AMOUNT_W4 - FORC_AMT_W4) OVER (PARTITION BY DEPARTMENT, DOCREF, YEAR, MONTH),0,0,(ITEM_AMOUNT_W4 - FORC_AMT_W4) / SUM(ITEM_AMOUNT_W4 - FORC_AMT_W4) OVER (PARTITION BY DEPARTMENT, DOCREF, YEAR, MONTH) * 100) PERCENT_W5,
FORC_AMT_W1,
FORC_AMT_W2,
FORC_AMT_W3,
FORC_AMT_W4,
DECODE(SUM(ITEM_AMOUNT_W4 - FORC_AMT_W4) OVER (PARTITION BY DEPARTMENT, DOCREF, YEAR, MONTH),0,0,((ITEM_AMOUNT_W4 - FORC_AMT_W4) / SUM(ITEM_AMOUNT_W4 - FORC_AMT_W4) OVER (PARTITION BY DEPARTMENT, DOCREF, YEAR, MONTH)* 100)/100 * W4) FORC_AMT_W5
from (
Select
DEPARTMENT, DOCTYPE, DOCREF, YEAR, MONTH,
W1, W2, W3, W4, W5,
MATERIAL,
ITEM_AMOUNT_W3 - FORC_AMT_W3 ITEM_AMOUNT_W4,
SUM(ITEM_AMOUNT_W3 - FORC_AMT_W3) OVER (PARTITION BY DEPARTMENT, DOCREF, YEAR, MONTH) TOTAL_ITEM_AMOUNT_W4,
(ITEM_AMOUNT_W3 - FORC_AMT_W3) / SUM(ITEM_AMOUNT_W3 - FORC_AMT_W3) OVER (PARTITION BY DEPARTMENT, DOCREF, YEAR, MONTH) * 100 PERCENT_W4,
FORC_AMT_W1,
FORC_AMT_W2,
FORC_AMT_W3,
DECODE(SUM(ITEM_AMOUNT_W3 - FORC_AMT_W3) OVER (PARTITION BY DEPARTMENT, DOCREF, YEAR, MONTH),0,0,((ITEM_AMOUNT_W3 - FORC_AMT_W3) / SUM(ITEM_AMOUNT_W3 - FORC_AMT_W3) OVER (PARTITION BY DEPARTMENT, DOCREF, YEAR, MONTH)* 100)/100 * W4) FORC_AMT_W4
from (
Select
DEPARTMENT, DOCTYPE, DOCREF, YEAR, MONTH,
W1, W2, W3, W4, W5,
MATERIAL,
ITEM_AMOUNT_W2 - FORC_AMT_W2 ITEM_AMOUNT_W3,
SUM(ITEM_AMOUNT_W2 - FORC_AMT_W2) OVER (PARTITION BY DEPARTMENT, DOCREF, YEAR, MONTH) TOTAL_ITEM_AMOUNT_W3,
(ITEM_AMOUNT_W2 - FORC_AMT_W2) / SUM(ITEM_AMOUNT_W2 - FORC_AMT_W2) OVER (PARTITION BY DEPARTMENT, DOCREF, YEAR, MONTH) * 100 PERCENT_W3,
FORC_AMT_W1,
FORC_AMT_W2,
DECODE((SUM(ITEM_AMOUNT_W2 - FORC_AMT_W2) OVER (PARTITION BY DEPARTMENT, DOCREF, YEAR, MONTH)* 100),0,0,((ITEM_AMOUNT_W2 - FORC_AMT_W2) / SUM(ITEM_AMOUNT_W2 - FORC_AMT_W2) OVER (PARTITION BY DEPARTMENT, DOCREF, YEAR, MONTH)* 100)/100 * W3) FORC_AMT_W3
from (
Select
DEPARTMENT, DOCTYPE, DOCREF, YEAR, MONTH,
W1, W2, W3, W4, W5,
MATERIAL,
ITEM_AMOUNT_W1 - FORC_AMT_W1 ITEM_AMOUNT_W2,
SUM(ITEM_AMOUNT_W1 - FORC_AMT_W1) OVER (PARTITION BY DEPARTMENT, DOCREF, YEAR, MONTH) TOTAL_ITEM_AMOUNT_W2,
(ITEM_AMOUNT_W1 - FORC_AMT_W1) / SUM(ITEM_AMOUNT_W1 - FORC_AMT_W1) OVER (PARTITION BY DEPARTMENT, DOCREF, YEAR, MONTH) * 100 PERCENT_W2,
FORC_AMT_W1,
DECODE(SUM(ITEM_AMOUNT_W1 - FORC_AMT_W1) OVER (PARTITION BY DEPARTMENT, DOCREF, YEAR, MONTH),0,0,((ITEM_AMOUNT_W1 - FORC_AMT_W1) / SUM(ITEM_AMOUNT_W1 - FORC_AMT_W1) OVER (PARTITION BY DEPARTMENT, DOCREF, YEAR, MONTH)* 100)/100 * W2) FORC_AMT_W2
from
(
Select
DEPARTMENT, DOCTYPE, DOCREF, YEAR, MONTH,
W1, W2, W3, W4, W5,
NOTINVDET.MATERIAL,
NOTINVDET.AMOUNT_INCLUDE_VAT ITEM_AMOUNT_W1,
SUM(NOTINVDET.AMOUNT_INCLUDE_VAT) OVER (PARTITION BY DEPARTMENT, DOCREF, YEAR, MONTH) TOTAL_ITEM_AMOUNT_W1,
NOTINVDET.AMOUNT_INCLUDE_VAT / SUM(NOTINVDET.AMOUNT_INCLUDE_VAT) OVER (PARTITION BY DEPARTMENT, DOCREF, YEAR, MONTH) * 100 PERCENT_W1,
DECODE(SUM(NOTINVDET.AMOUNT_INCLUDE_VAT) OVER (PARTITION BY DEPARTMENT, DOCREF, YEAR, MONTH),0,0,(NOTINVDET.AMOUNT_INCLUDE_VAT / SUM(NOTINVDET.AMOUNT_INCLUDE_VAT) OVER (PARTITION BY DEPARTMENT, DOCREF, YEAR, MONTH) * 100)/100*W1) FORC_AMT_W1
from
(
									select 
									FX.DEPARTMENT,
									NVL(NOTINV.ID,INV.ID) NOTINVID,
									NVL(NOTINV.DOCTYPE,INV.DOCTYPE) DOCTYPE,
									INV.DOCREF,
									FX.YEAR,
									FX.MONTH,
									SUM(DECODE(FX.WEEK,'W1',FX.AMOUNTADJS,0)) W1,
									SUM(DECODE(FX.WEEK,'W2',FX.AMOUNTADJS,0)) W2,
									SUM(DECODE(FX.WEEK,'W3',FX.AMOUNTADJS,0)) W3,
									SUM(DECODE(FX.WEEK,'W4',FX.AMOUNTADJS,0)) W4,
									SUM(DECODE(FX.WEEK,'W5',FX.AMOUNTADJS,0)) W5
									from 
									FORECAST_FIX FX
									INNER JOIN CF_TRANSACTION INV ON FX.CFTRANSID = INV.ID
									LEFT JOIN CF_TRANSACTION NOTINV ON INV.DOCREF = NOTINV.DOCNUMBER and NOTINV.DOCTYPE !='INV'
									GROUP BY FX.DEPARTMENT, NVL(NOTINV.ID,INV.ID), NVL(NOTINV.DOCTYPE,INV.DOCTYPE), INV.DOCREF, FX.YEAR, FX.MONTH
) HDR01
INNER JOIN CF_TRANSACTION_DET NOTINVDET ON HDR01.NOTINVID = NOTINVDET.ID
)W1
)W2
)W3
)W4
inner join material_groupitem mat on W4.material = mat.material
inner join material_group matgroup on mat.materialgroup = matgroup.id
inner join forecast_category foregroup on matgroup.forecast_category = foregroup.fccode
inner join doctype on W4.doctype = doctype.fccode
) Finalx
GROUP BY DOCTYPENAME, YEAR, MONTH
ORDER BY DOCTYPENAME, YEAR, TO_DATE(MONTH, 'MM')
) Finalxx
)Finalxxx
GROUP BY YEAR, MONTH
ORDER BY YEAR, TO_DATE(MONTH, 'MM')");
		return $a->result();
	 }
	 function cur_production_chart_fcba($fcba){
		$a = $this->db->query("WITH your_table
							 AS (SELECT 'Ton' Uom,
										'BGT' PROD,
										ROUND (SUM (bp.qty_01 / 1000), 0) AS JAN,
										ROUND (SUM (bp.qty_02 / 1000), 0) AS FEB,
										ROUND (SUM (bp.qty_03 / 1000), 0) AS MAR,
										ROUND (SUM (bp.qty_04 / 1000), 0) AS APR,
										ROUND (SUM (bp.qty_05 / 1000), 0) AS MAY,
										ROUND (SUM (bp.qty_06 / 1000), 0) AS JUN,
										ROUND (SUM (bp.qty_07 / 1000), 0) AS JUL,
										ROUND (SUM (bp.qty_08 / 1000), 0) AS AUG,
										ROUND (SUM (bp.qty_09 / 1000), 0) AS SEP,
										ROUND (SUM (bp.qty_10 / 1000), 0) AS OCT,
										ROUND (SUM (bp.qty_11 / 1000), 0) AS NOV,
										ROUND (SUM (bp.qty_12 / 1000), 0) AS DEC
								   FROM iplasprod.budget_transaction_prodqty bp
								  WHERE     bp.budget_year = '2019'
										AND bp.fcba = '".$fcba."'
								 UNION ALL
								 SELECT 'Ton' Uom,
										'REAL' PROD,
										ROUND (SUM (DECODE (BULAN, '01', TOTAL, 0) / 1000), 0) JAN,
										ROUND (SUM (DECODE (BULAN, '02', TOTAL, 0) / 1000), 0) FEB,
										ROUND (SUM (DECODE (BULAN, '03', TOTAL, 0) / 1000), 0) MAR,
										ROUND (SUM (DECODE (BULAN, '04', TOTAL, 0) / 1000), 0) APR,
										ROUND (SUM (DECODE (BULAN, '05', TOTAL, 0) / 1000), 0) MAY,
										ROUND (SUM (DECODE (BULAN, '06', TOTAL, 0) / 1000), 0) JUN,
										ROUND (SUM (DECODE (BULAN, '07', TOTAL, 0) / 1000), 0) JUL,
										ROUND (SUM (DECODE (BULAN, '08', TOTAL, 0) / 1000), 0) AUG,
										ROUND (SUM (DECODE (BULAN, '09', TOTAL, 0) / 1000), 0) SEP,
										ROUND (SUM (DECODE (BULAN, '10', TOTAL, 0) / 1000), 0) OCT,
										ROUND (SUM (DECODE (BULAN, '11', TOTAL, 0) / 1000), 0) NOV,
										ROUND (SUM (DECODE (BULAN, '12', TOTAL, 0) / 1000), 0) DEC
								   FROM (  SELECT TO_CHAR (RECEPTIONDATE, 'YYYY') AS TAHUN,
												  TO_CHAR (RECEPTIONDATE, 'MM') AS BULAN,
												  SUM (kg_per_block) + SUM (Kg_Brondolan) AS total
											 FROM (SELECT iplasprod.harvestingspb.RECEPTIONDATE,
														  DECODE (
															 total_bunch.total_bunch,
															 0, 0,
															 (  (  iplasprod.harvestingspb.bunch
																 / total_bunch.total_bunch)
															  * wb_harv.Bunch_Mill_Weight_Netto))
															 AS kg_per_block,
														  DECODE (
															 wb_harv.total_bucket,
															 0, 0,
															   iplasprod.harvestingspb.bucket
															 / wb_harv.total_bucket
															 * wb_harv.Kg_Brondolan)
															 Kg_Brondolan
													 FROM iplasprod.harvestingspb
														  INNER JOIN FIELD
															 ON     iplasprod.harvestingspb.fieldcode =
																	   field.fccode
																AND iplasprod.harvestingspb.fcba =
																	   field.fcba
																AND field.ACTIVATION = 'Y'
														  INNER JOIN
														  (  SELECT fcba,
																	spbno,
																	SUM (bunch) AS total_bunch
															   FROM iplasprod.harvestingspb
															  WHERE harvestingspb.fcba = '".$fcba."'
														   GROUP BY fcba, spbno) total_bunch
															 ON (    iplasprod.harvestingspb.fcba =
																		total_bunch.fcba
																 AND iplasprod.harvestingspb.spbno =
																		total_bunch.spbno)
														  INNER JOIN
														  (  SELECT iplasprod.wb_harvesting.fcba_estate,
																	iplasprod.wb_harvesting.spbno,
																	SUM (
																	   iplasprod.wb_harvesting.bunch_mill_weight_netto)
																	   AS Bunch_Mill_Weight_Netto,
																	SUM (
																	   iplasprod.wb_harvesting.Kg_Brondolan)
																	   Kg_Brondolan,
																	SUM (
																	   iplasprod.wb_harvesting.total_bucket)
																	   total_bucket
															   FROM iplasprod.wb_harvesting
														   GROUP BY iplasprod.wb_harvesting.fcba_estate,
																	iplasprod.wb_harvesting.spbno)
														  wb_harv
															 ON (    iplasprod.harvestingspb.fcba =
																		wb_harv.fcba_estate
																 AND iplasprod.harvestingspb.spbno =
																		wb_harv.spbno)
													WHERE     iplasprod.harvestingspb.fcba = '".$fcba."')
										 GROUP BY TO_CHAR (RECEPTIONDATE, 'YYYY'),
												  TO_CHAR (RECEPTIONDATE, 'MM')))
						  SELECT Uom,
								 MONTH,
								 MAX (BGT) BGT,
								 MAX (REAL) REAL
							FROM (SELECT yt.Uom,
										 CASE
											WHEN d.id = 1 THEN 'Jan'
											WHEN d.id = 2 THEN 'Feb'
											WHEN d.id = 3 THEN 'Mar'
											WHEN d.id = 4 THEN 'Apr'
											WHEN d.id = 5 THEN 'May'
											WHEN d.id = 6 THEN 'Jun'
											WHEN d.id = 7 THEN 'Jul'
											WHEN d.id = 8 THEN 'Aug'
											WHEN d.id = 9 THEN 'Sep'
											WHEN d.id = 10 THEN 'Oct'
											WHEN d.id = 11 THEN 'Nov'
											WHEN d.id = 12 THEN 'Dec'
										 END
											MONTH,
										 CASE
											WHEN yt.PROD = 'BGT'
											THEN
											   CASE
												  WHEN d.id = 1 THEN yt.JAN
												  WHEN d.id = 2 THEN yt.FEB
												  WHEN d.id = 3 THEN yt.MAR
												  WHEN d.id = 4 THEN yt.APR
												  WHEN d.id = 5 THEN yt.MAY
												  WHEN d.id = 6 THEN yt.JUN
												  WHEN d.id = 7 THEN yt.JUL
												  WHEN d.id = 8 THEN yt.AUG
												  WHEN d.id = 9 THEN yt.SEP
												  WHEN d.id = 10 THEN yt.OCT
												  WHEN d.id = 11 THEN yt.NOV
												  WHEN d.id = 12 THEN yt.DEC
											   END
										 END
											BGT,
										 CASE
											WHEN yt.PROD = 'REAL'
											THEN
											   CASE
												  WHEN d.id = 1 THEN yt.JAN
												  WHEN d.id = 2 THEN yt.FEB
												  WHEN d.id = 3 THEN yt.MAR
												  WHEN d.id = 4 THEN yt.APR
												  WHEN d.id = 5 THEN yt.MAY
												  WHEN d.id = 6 THEN yt.JUN
												  WHEN d.id = 7 THEN yt.JUL
												  WHEN d.id = 8 THEN yt.AUG
												  WHEN d.id = 9 THEN yt.SEP
												  WHEN d.id = 10 THEN yt.OCT
												  WHEN d.id = 11 THEN yt.NOV
												  WHEN d.id = 12 THEN yt.DEC
											   END
										 END
											REAL
									FROM your_table yt
										 CROSS JOIN (    SELECT LEVEL ID
														   FROM DUAL
													 CONNECT BY LEVEL <= 12) d)
						GROUP BY Uom, MONTH
						ORDER BY TO_DATE (MONTH, 'mm')");
		return $a->result();
	 }
	  //query get current production chart
	 function cur_production_chart_det($fcba, $division){
		$a = $this->db->query("WITH your_table
							 AS (SELECT 'Ton' Uom,
										'BGT' PROD,
										ROUND (SUM (bp.qty_01 / 1000), 0) AS JAN,
										ROUND (SUM (bp.qty_02 / 1000), 0) AS FEB,
										ROUND (SUM (bp.qty_03 / 1000), 0) AS MAR,
										ROUND (SUM (bp.qty_04 / 1000), 0) AS APR,
										ROUND (SUM (bp.qty_05 / 1000), 0) AS MAY,
										ROUND (SUM (bp.qty_06 / 1000), 0) AS JUN,
										ROUND (SUM (bp.qty_07 / 1000), 0) AS JUL,
										ROUND (SUM (bp.qty_08 / 1000), 0) AS AUG,
										ROUND (SUM (bp.qty_09 / 1000), 0) AS SEP,
										ROUND (SUM (bp.qty_10 / 1000), 0) AS OCT,
										ROUND (SUM (bp.qty_11 / 1000), 0) AS NOV,
										ROUND (SUM (bp.qty_12 / 1000), 0) AS DEC
								   FROM iplasprod.budget_transaction_prodqty bp
								  WHERE     bp.budget_year = '2019'
										AND bp.fcba = '".$fcba."'
										AND bp.division = '".$division."'
								 UNION ALL
								 SELECT 'Ton' Uom,
										'REAL' PROD,
										ROUND (SUM (DECODE (BULAN, '01', TOTAL, 0) / 1000), 0) JAN,
										ROUND (SUM (DECODE (BULAN, '02', TOTAL, 0) / 1000), 0) FEB,
										ROUND (SUM (DECODE (BULAN, '03', TOTAL, 0) / 1000), 0) MAR,
										ROUND (SUM (DECODE (BULAN, '04', TOTAL, 0) / 1000), 0) APR,
										ROUND (SUM (DECODE (BULAN, '05', TOTAL, 0) / 1000), 0) MAY,
										ROUND (SUM (DECODE (BULAN, '06', TOTAL, 0) / 1000), 0) JUN,
										ROUND (SUM (DECODE (BULAN, '07', TOTAL, 0) / 1000), 0) JUL,
										ROUND (SUM (DECODE (BULAN, '08', TOTAL, 0) / 1000), 0) AUG,
										ROUND (SUM (DECODE (BULAN, '09', TOTAL, 0) / 1000), 0) SEP,
										ROUND (SUM (DECODE (BULAN, '10', TOTAL, 0) / 1000), 0) OCT,
										ROUND (SUM (DECODE (BULAN, '11', TOTAL, 0) / 1000), 0) NOV,
										ROUND (SUM (DECODE (BULAN, '12', TOTAL, 0) / 1000), 0) DEC
								   FROM (  SELECT TO_CHAR (RECEPTIONDATE, 'YYYY') AS TAHUN,
												  TO_CHAR (RECEPTIONDATE, 'MM') AS BULAN,
												  SUM (kg_per_block) + SUM (Kg_Brondolan) AS total
											 FROM (SELECT iplasprod.harvestingspb.RECEPTIONDATE,
														  DECODE (
															 total_bunch.total_bunch,
															 0, 0,
															 (  (  iplasprod.harvestingspb.bunch
																 / total_bunch.total_bunch)
															  * wb_harv.Bunch_Mill_Weight_Netto))
															 AS kg_per_block,
														  DECODE (
															 wb_harv.total_bucket,
															 0, 0,
															   iplasprod.harvestingspb.bucket
															 / wb_harv.total_bucket
															 * wb_harv.Kg_Brondolan)
															 Kg_Brondolan
													 FROM iplasprod.harvestingspb
														  INNER JOIN FIELD
															 ON     iplasprod.harvestingspb.fieldcode =
																	   field.fccode
																AND iplasprod.harvestingspb.fcba =
																	   field.fcba
																AND field.ACTIVATION = 'Y'
														  INNER JOIN
														  (  SELECT fcba,
																	spbno,
																	SUM (bunch) AS total_bunch
															   FROM iplasprod.harvestingspb
															  WHERE harvestingspb.fcba = '".$fcba."'
														   GROUP BY fcba, spbno) total_bunch
															 ON (    iplasprod.harvestingspb.fcba =
																		total_bunch.fcba
																 AND iplasprod.harvestingspb.spbno =
																		total_bunch.spbno)
														  INNER JOIN
														  (  SELECT iplasprod.wb_harvesting.fcba_estate,
																	iplasprod.wb_harvesting.spbno,
																	SUM (
																	   iplasprod.wb_harvesting.bunch_mill_weight_netto)
																	   AS Bunch_Mill_Weight_Netto,
																	SUM (
																	   iplasprod.wb_harvesting.Kg_Brondolan)
																	   Kg_Brondolan,
																	SUM (
																	   iplasprod.wb_harvesting.total_bucket)
																	   total_bucket
															   FROM iplasprod.wb_harvesting
														   GROUP BY iplasprod.wb_harvesting.fcba_estate,
																	iplasprod.wb_harvesting.spbno)
														  wb_harv
															 ON (    iplasprod.harvestingspb.fcba =
																		wb_harv.fcba_estate
																 AND iplasprod.harvestingspb.spbno =
																		wb_harv.spbno)
													WHERE     iplasprod.harvestingspb.fcba = '".$fcba."'
														  AND field.division = '".$division."')
										 GROUP BY TO_CHAR (RECEPTIONDATE, 'YYYY'),
												  TO_CHAR (RECEPTIONDATE, 'MM')))
						  SELECT Uom,
								 MONTH,
								 MAX (BGT) BGT,
								 MAX (REAL) REAL
							FROM (SELECT yt.Uom,
										 CASE
											WHEN d.id = 1 THEN 'Jan'
											WHEN d.id = 2 THEN 'Feb'
											WHEN d.id = 3 THEN 'Mar'
											WHEN d.id = 4 THEN 'Apr'
											WHEN d.id = 5 THEN 'May'
											WHEN d.id = 6 THEN 'Jun'
											WHEN d.id = 7 THEN 'Jul'
											WHEN d.id = 8 THEN 'Aug'
											WHEN d.id = 9 THEN 'Sep'
											WHEN d.id = 10 THEN 'Oct'
											WHEN d.id = 11 THEN 'Nov'
											WHEN d.id = 12 THEN 'Dec'
										 END
											MONTH,
										 CASE
											WHEN yt.PROD = 'BGT'
											THEN
											   CASE
												  WHEN d.id = 1 THEN yt.JAN
												  WHEN d.id = 2 THEN yt.FEB
												  WHEN d.id = 3 THEN yt.MAR
												  WHEN d.id = 4 THEN yt.APR
												  WHEN d.id = 5 THEN yt.MAY
												  WHEN d.id = 6 THEN yt.JUN
												  WHEN d.id = 7 THEN yt.JUL
												  WHEN d.id = 8 THEN yt.AUG
												  WHEN d.id = 9 THEN yt.SEP
												  WHEN d.id = 10 THEN yt.OCT
												  WHEN d.id = 11 THEN yt.NOV
												  WHEN d.id = 12 THEN yt.DEC
											   END
										 END
											BGT,
										 CASE
											WHEN yt.PROD = 'REAL'
											THEN
											   CASE
												  WHEN d.id = 1 THEN yt.JAN
												  WHEN d.id = 2 THEN yt.FEB
												  WHEN d.id = 3 THEN yt.MAR
												  WHEN d.id = 4 THEN yt.APR
												  WHEN d.id = 5 THEN yt.MAY
												  WHEN d.id = 6 THEN yt.JUN
												  WHEN d.id = 7 THEN yt.JUL
												  WHEN d.id = 8 THEN yt.AUG
												  WHEN d.id = 9 THEN yt.SEP
												  WHEN d.id = 10 THEN yt.OCT
												  WHEN d.id = 11 THEN yt.NOV
												  WHEN d.id = 12 THEN yt.DEC
											   END
										 END
											REAL
									FROM your_table yt
										 CROSS JOIN (    SELECT LEVEL ID
														   FROM DUAL
													 CONNECT BY LEVEL <= 12) d)
						GROUP BY Uom, MONTH
						ORDER BY TO_DATE (MONTH, 'mm')");
		return $a->result();
	 }
	 function cur_cpo_chart(){
		$a = $this->db->query("
											select
                                            BULAN month,
                                            MAX(DECODE(PRODUCT_CODE,'CPO',VAL))CPO,
                                            MAX(DECODE(PRODUCT_CODE,'KER',VAL))KER,
                                            MAX(DECODE(PRODUCT_CODE,'SHL',VAL))SHL
                                            FROM
                                            (
                                            SELECT PRODUCT_CODE,'Ton_Real'Uom,
                                            ROUND(SUM(DECODE(BULAN,'01',TOTAL,0)/1000),0)JAN, 
                                            ROUND(SUM(DECODE(BULAN,'02',TOTAL,0)/1000),0)FEB, 
                                            ROUND(SUM(DECODE(BULAN,'03',TOTAL,0)/1000),0)MAR,
                                            ROUND(SUM(DECODE(BULAN,'04',TOTAL,0)/1000),0)APR,
                                            ROUND(SUM(DECODE(BULAN,'05',TOTAL,0)/1000),0)MAY,
                                            ROUND(SUM(DECODE(BULAN,'06',TOTAL,0)/1000),0)JUN,
                                            ROUND(SUM(DECODE(BULAN,'07',TOTAL,0)/1000),0)JUL,
                                            ROUND(SUM(DECODE(BULAN,'08',TOTAL,0)/1000),0)AUG,
                                            ROUND(SUM(DECODE(BULAN,'09',TOTAL,0)/1000),0)SEP,
                                            ROUND(SUM(DECODE(BULAN,'10',TOTAL,0)/1000),0)OCT,
                                            ROUND(SUM(DECODE(BULAN,'11',TOTAL,0)/1000),0)NOV,
                                            ROUND(SUM(DECODE(BULAN,'12',TOTAL,0)/1000),0)DEC
                                            FROM (
                                            SELECT PRODUCT_CODE,TO_CHAR(TDATE,'YYYY') AS TAHUN,TO_CHAR(TDATE,'MM') AS BULAN, SUM(PRODUCTION_TODAY) AS TOTAL 
                                            FROM IPLASPROD.MILL_PRODUCTION
                                            WHERE 
                                            TO_CHAR(TDATE,'YYYY')='2019'
                                            GROUP BY PRODUCT_CODE,TO_CHAR(TDATE,'YYYY'),TO_CHAR(TDATE,'MM')
                                            )
                                            GROUP BY PRODUCT_CODE
                                            UNION ALL
                                            SELECT PRODUCTCODE,'Ton_Bgt'UOM,
                                            ROUND(sum(qty_01/1000),0) as Jan,
                                            ROUND(sum(qty_02/1000),0) as Feb,
                                            ROUND(sum(qty_03/1000),0) as Mar,
                                            ROUND(sum(qty_04/1000),0) as Apr,
                                            ROUND(sum(qty_05/1000),0) as May,
                                            ROUND(sum(qty_06/1000),0) as Jun,
                                            ROUND(sum(qty_07/1000),0) as Jul,
                                            ROUND(sum(qty_08/1000),0) as Aug,
                                            ROUND(sum(qty_09/1000),0) as Sep,
                                            ROUND(sum(qty_10/1000),0) as Oct,
                                            ROUND(sum(qty_11/1000),0) as Nov,
                                            ROUND(sum(qty_12/1000),0) as Dec
                                            FROM IPLASPROD.BUDGET_TRANSACTION_MILLQTY
                                            WHERE 
                                            BUDGET_YEAR='2019'
                                            AND BUDGETMILLCODE IS NULL
                                            AND PRODUCTCODE IN ('CPO','KER','SHL')
                                            GROUP BY PRODUCTCODE
                                            ORDER BY PRODUCT_CODE
                                            )unpivot(
                                             VAL
                                             for BULAN in (
                                             Jan, Feb, Mar, Apr, May, Jun, Jul,Aug, Sep, Oct, Nov,Dec
                                             )
                                            )
                                            GROUP BY BULAN
                                            order by to_date(BULAN, 'mm')");
		return $a->result();
	 }
}
?>
