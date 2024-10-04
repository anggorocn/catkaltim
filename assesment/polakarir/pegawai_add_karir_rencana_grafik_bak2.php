<?
include_once("../WEB/classes/utils/UserLogin.php");
include_once("../WEB/functions/string.func.php");
include_once("../WEB/functions/default.func.php");
include_once("../WEB/functions/date.func.php");
//include_once("../WEB/classes/base/Pegawai.php");
include_once("../WEB/classes/base-polakarir/PerencanaanDetil.php");

//$pegawai = new Pegawai();
$pegawai_add_karir_rencana = new PerencanaanDetil();

$reqId = httpFilterGet("reqId");
$reqDeleteId = httpFilterGet("reqDeleteId");
$reqMode = httpFilterGet("reqMode");

/*if($reqId == "")
{
	echo '<script language="javascript">';
	echo 'alert("Isi data pegawai terlebih dahulu.");';	
	echo 'window.parent.location.href = "pegawai_add.php";';
	echo '</script>';
	exit();
}*/
//$reqId=1;
//$pegawai->selectByParams(array("A.PEGAWAI_ID" => $reqId));
//$pegawai->firstRow();
//$tempNama= $pegawai->getField("NAMA");
//echo $pegawai_add_karir_rencana->query;

$pegawai_add_karir_rencana= new PerencanaanDetil();
$jumlah_data= $pegawai_add_karir_rencana->getCountByParamsNetwork($reqId);
$pegawai_add_karir_rencana->selectByParamsNetwork($reqId);
//echo $pegawai_add_karir_rencana->query;exit;
$index_karir= 0;
while($pegawai_add_karir_rencana->nextRow())
{
	$arrKarir[$index_karir]["data_rencana"]= $pegawai_add_karir_rencana->getField("data_rencana");
	$arrKarir[$index_karir]["JABATAN"]= $pegawai_add_karir_rencana->getField("JABATAN");
	$arrKarir[$index_karir]["TAHUN"]= $pegawai_add_karir_rencana->getField("TAHUN");
	$arrKarir[$index_karir]["USIA"]= $pegawai_add_karir_rencana->getField("USIA");
	$arrKarir[$index_karir]["TMT"]= $pegawai_add_karir_rencana->getField("TMT");
	$arrKarir[$index_karir]["RUMPUN_JABATAN_ID"]= $pegawai_add_karir_rencana->getField("RUMPUN_JABATAN_ID");
	$arrKarir[$index_karir]["UNIT_KERJA"]= $pegawai_add_karir_rencana->getField("UNIT_KERJA");
	$index_karir++;
}
//echo print_r($arrKarir);exit;
//echo $pegawai_add_karir_rencana->query;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<title>Form Validation - jQuery EasyUI Demo</title>
    <link href="css/begron.css" rel="stylesheet" type="text/css">
    <link href="css/admin.css" rel="stylesheet" type="text/css">

    <link href="../WEB/css/gaya.css" rel="stylesheet" type="text/css">
    <link href="../WEB/lib/media/themes/main_datatables.css" rel="stylesheet" type="text/css" /> 
    
    <link rel="stylesheet" href="../WEB/lib/phpdemo/demo/js/jquery/ui-lightness/jquery-ui-1.10.2.custom.css" />
    <script type="text/javascript" src="../WEB/lib/phpdemo/demo/js/jquery/jquery-1.9.1.js"></script>
    <script type="text/javascript" src="../WEB/lib/phpdemo/demo/js/jquery/jquery-ui-1.10.2.custom.min.js"></script>

    <script type="text/javascript" src="../WEB/lib/phpdemo/demo/js/primitives.min.js"></script>
    <link href="../WEB/lib/phpdemo/demo/css/primitives.latest.css" media="screen" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="../WEB/css/bluetabs.css" />
	
    <style>
	* {
	padding: 0;
	margin: 0;
	}
	html, body {
	height: 100%;
	}
	
	#kompak-area{ width:320px;}
	#kompak-area-Utama{ width:320px;background:#0FC;}
	#kompak-area-Rumpun{ width:320px;background:#F33;}
	#kompak-konten{ padding:10px;}
	#kompak-header{ float:left; width:100%; background:#096; text-align:center; color:#FFF;}
	#kompak-header-Utama{ float:left; width:100%; background:#096; text-align:center; color:#FFF;}
	
	#kompak-body{ float:left; width:100%; /*background:#0FC;*/}
	#kompak-body-Utama{ float:left; width:100%; background:#0FC;}
	#kompak-row{ float:left; width:100%;}
	#kompak-row div:first-child{ float:left; width:100px; font-size:12px;/* background:#F66;*/}
	#kompak-row div:nth-child(2){ float:left; width:19px; font-size:12px; /*background:#C9C;*/}
	#kompak-row div:nth-child(3){ float:left; width:200px; font-size:12px; /*background:#99F;*/}
	</style>
    
    <script type='text/javascript'>//<![CDATA[ 
        jQuery(window).load(function () {
            var options = new primitives.orgdiagram.Config();

            var items = [
			<?
			$row=0;
			for($row=0; $row < count($arrKarir); $row++)
          	{
				if($row == 0){}
				else
				echo ",";
				
				//if($row % 2 == 0)
				//{
					if($row == 0)
					{
						$tempRow= $row;
						$tempRowParent='null';
						$template="contactTemplateUtama";
					}
					else
					{
						$tempRow= $row;
						$tempRowParent= $row-1;
						$template="contactTemplate";
					}
					
					
					if($arrKarir[$row]["data_rencana"] == 1)
						$template="contactTemplateUtama";
					else
					{
						$template="contactTemplate";
						
						if($jumlah_data == $row+1)
						{
							if($arrKarir[$row-2]["RUMPUN_JABATAN_ID"] == $arrKarir[$row-1]["RUMPUN_JABATAN_ID"])
								$template="contactTemplate";
							else
								$template="contactTemplateRumpun";
						}
					}
					
				//}
			?>
				new primitives.orgdiagram.ItemConfig({
                    id: <?=$tempRow?>,
                    parent: <?=$tempRowParent?>,
                    jabatan: "<?=$arrKarir[$row]["JABATAN"]?>",
					<?
					if($row == 0){}
					else
					{
						if($row % 2 == 0)
						{
					?>
					itemType: primitives.orgdiagram.ItemType.Adviser,
					adviserPlacementType: primitives.common.AdviserPlacementType.Right,
					groupTitle: "",
					<?
						}
					}
					?>
                    tahun: "<?=$arrKarir[$row]["TAHUN"]?>",
                    usia: "<?=$arrKarir[$row]["USIA"]?>",
                    unit_kerja: "<?=$arrKarir[$row]["UNIT_KERJA"]?>",
                    templateName: "<?=$template?>",
                    itemTitleColor: "red",
					href: "uploads/jabatan_syarat/syarat_IA.pdf"
                })
			<?
			}
			?>
            ];

			/*new primitives.orgdiagram.ItemConfig({
                    id: 0,
                    parent: null,
                    jabatan: "Scott Aasrud",
                    tahun: "VP, Public Sector",
                    usia: "(123) 456-78-90",
                    unit_kerja: "itema@org.com",
                    templateName: "contactTemplate",
                    itemTitleColor: "red"
                }),
                new primitives.orgdiagram.ItemConfig({
                    id: 1,
                    parent: 0,
                    jabatan: "Scott Aasrud",
                    tahun: "VP,PublicSectorSector SectorSectorSectorSectorSector",
                    usia: "(123) 456-78-90",
                    unit_kerja: "itema@org.com",
                    templateName: "contactTemplate"
                }),
                new primitives.orgdiagram.ItemConfig({
                    id: 2,
                    parent: 0,
                    jabatan: "Scott Aasrud",
                    tahun: "VP, Public Sector",
                    usia: "(123) 456-78-90",
                    unit_kerja: "itema@org.com",
                    templateName: "contactTemplate",
                })*/

            options.items = items;
            options.cursorItem = 0;
			options.hasSelectorCheckbox = primitives.common.Enabled.False;
            //options.templates = [getPhotoTemplate(), getContactTemplate()];
			options.templates = [getContactTemplateUtama(), getContactTemplateRumpun(), getContactTemplate()];
			options.pageFitMode = primitives.orgdiagram.PageFitMode.None;
            options.onItemRender = onTemplateRender;

            jQuery("#basicdiagram").orgDiagram(options);


            function onTemplateRender(event, data) {
				var hrefElement = data.element.find("[name=readmore]");
                var emailElement = data.element.find("[name=email]");
                switch (data.renderingMode) {
                    case primitives.common.RenderingMode.Create:
                        /* Initialize widgets here */
                        hrefElement.click(function (e) {
                            /* Block mouse click propogation in order to avoid layout updates before server postback*/
                            primitives.common.stopPropagation(e);
                        });
                        emailElement.click(function (e) {
                            /* Block mouse click propogation in order to avoid layout updates before server postback*/
                            primitives.common.stopPropagation(e);
                        });
                        break;
                    case primitives.common.RenderingMode.Update:
                        /* Update widgets here */
                        break;
                }
				
                var itemConfig = data.context;

                if (data.templateName == "photoTemplate") 
				{
                    data.element.find("[name=photo]").attr({ "src": itemConfig.image, "alt": itemConfig.title });
                    data.element.find("[name=title]").text(itemConfig.title);
                } 
				else if (data.templateName == "contactTemplateUtama") 
				{
					hrefElement.attr({ "href": itemConfig.href, "target": "_blank"});
					//emailElement.attr({ "href": ("mailto:" + itemConfig.email + "?Subject=Hello%20again") });
                    data.element.find("[name=photo]").attr({ "src": itemConfig.image, "alt": itemConfig.title });
                    data.element.find("[name=titleBackground]").css({ "background": itemConfig.itemTitleColor });

                    var fields = ["jabatan", "tahun", "usia", "unit_kerja"];
                    for (var index = 0; index < fields.length; index++) 
					{
                        var field = fields[index];

                        var element = data.element.find("[name=" + field + "]");
                        if (element.text() != itemConfig[field]) 
						{
                            element.text(itemConfig[field]);
                        }
                    }
                }
				else if (data.templateName == "contactTemplate") 
				{
					hrefElement.attr({ "href": itemConfig.href, "target": "_blank"});
					//emailElement.attr({ "href": ("mailto:" + itemConfig.email + "?Subject=Hello%20again") });
                    data.element.find("[name=photo]").attr({ "src": itemConfig.image, "alt": itemConfig.title });
                    data.element.find("[name=titleBackground]").css({ "background": itemConfig.itemTitleColor });

                    var fields = ["jabatan", "tahun", "usia", "unit_kerja"];
                    for (var index = 0; index < fields.length; index++) 
					{
                        var field = fields[index];

                        var element = data.element.find("[name=" + field + "]");
                        if (element.text() != itemConfig[field]) 
						{
                            element.text(itemConfig[field]);
                        }
                    }
                }
				else if (data.templateName == "contactTemplateRumpun") 
				{
					hrefElement.attr({ "href": itemConfig.href, "target": "_blank"});
					//emailElement.attr({ "href": ("mailto:" + itemConfig.email + "?Subject=Hello%20again") });
                    data.element.find("[name=photo]").attr({ "src": itemConfig.image, "alt": itemConfig.title });
                    data.element.find("[name=titleBackground]").css({ "background": itemConfig.itemTitleColor });

                    var fields = ["jabatan", "tahun", "usia", "unit_kerja"];
                    for (var index = 0; index < fields.length; index++) 
					{
                        var field = fields[index];

                        var element = data.element.find("[name=" + field + "]");
                        if (element.text() != itemConfig[field]) 
						{
                            element.text(itemConfig[field]);
                        }
                    }
                }
            }
			
			function getContactTemplateUtama() {
                var result = new primitives.orgdiagram.TemplateConfig();
                result.name = "contactTemplateUtama";

                result.itemSize = new primitives.common.Size(374, 154);
                result.minimizedItemSize = new primitives.common.Size(10, 3);
                result.highlightPadding = new primitives.common.Thickness(2, 2, 2, 2);


                var itemTemplate = jQuery(
                  '<div id="kompak-area-Utama">'
				+ '<div id="kompak-header-Utama">'
					+ '<div id="kompak-konten" name="jabatan"></div>'
				+ '</div>'
				+ '<div id="kompak-body-Utama">'
					+ '<div id="kompak-row">'
						+ '<div>Tahun</div>'
						+ '<div>:</div>'
						+ '<div name="tahun"></div>'
					+ '</div>'
					+ '<div id="kompak-row">'
						+ '<div>Usia</div>'
						+ '<div>:</div>'
						+ '<div name="usia"></div>'
					+ '</div>'
					+ '<div id="kompak-row">'
						+ '<div>Unit Kerja</div>'
						+ '<div>:</div>'
						+ '<div name="unit_kerja"></div>'
					+ '</div>'
					+ '<div id="kompak-row">'
						+ '<div>'
						//+ '<a name="readmore" style="font-size: 10px; font-family: Arial; text-align: right; font-weight: bold; text-decoration: none;">Read more ...</a>'
						+ '</div>'
					+ '</div>'
				+ '</div>'
			+ '</div>'
                ).css({
                    width: result.itemSize.width + "px",
                    height: result.itemSize.height + "px"
                }).addClass("bp-item bp-corner-all bt-item-frame");
                result.itemTemplate = itemTemplate.wrap('<div>').parent().html();

                return result;
            }
			
            function getContactTemplateRumpun() {
                var result = new primitives.orgdiagram.TemplateConfig();
                result.name = "contactTemplateRumpun";

                result.itemSize = new primitives.common.Size(350, 140);
                result.minimizedItemSize = new primitives.common.Size(10, 3);
                result.highlightPadding = new primitives.common.Thickness(2, 2, 2, 2);


                var itemTemplate = jQuery(
                  '<div id="kompak-area-Rumpun">'
				+ '<div id="kompak-header">'
					+ '<div id="kompak-konten" name="jabatan"></div>'
				+ '</div>'
				+ '<div id="kompak-body">'
					+ '<div id="kompak-row">'
						+ '<div>Tahun</div>'
						+ '<div>:</div>'
						+ '<div name="tahun"></div>'
					+ '</div>'
					+ '<div id="kompak-row">'
						+ '<div>Usia</div>'
						+ '<div>:</div>'
						+ '<div name="usia"></div>'
					+ '</div>'
					+ '<div id="kompak-row">'
						+ '<div>Unit Kerja</div>'
						+ '<div>:</div>'
						+ '<div name="unit_kerja"></div>'
					+ '</div>'
					+ '<div id="kompak-row">'
						+ '<div>'
						//+ '<a name="readmore" style="font-size: 10px; font-family: Arial; text-align: right; font-weight: bold; text-decoration: none;">Read more ...</a>'
						+ '</div>'
					+ '</div>'
				+ '</div>'
			+ '</div>'
                ).css({
                    width: result.itemSize.width + "px",
                    height: result.itemSize.height + "px"
                }).addClass("bp-item bp-corner-all bt-item-frame");
                result.itemTemplate = itemTemplate.wrap('<div>').parent().html();

                return result;
            }
			
			function getContactTemplate() {
                var result = new primitives.orgdiagram.TemplateConfig();
                result.name = "contactTemplate";

                result.itemSize = new primitives.common.Size(350, 140);
                result.minimizedItemSize = new primitives.common.Size(10, 3);
                result.highlightPadding = new primitives.common.Thickness(2, 2, 2, 2);


                var itemTemplate = jQuery(
                  '<div id="kompak-area">'
				+ '<div id="kompak-header">'
					+ '<div id="kompak-konten" name="jabatan"></div>'
				+ '</div>'
				+ '<div id="kompak-body">'
					+ '<div id="kompak-row">'
						+ '<div>Tahun</div>'
						+ '<div>:</div>'
						+ '<div name="tahun"></div>'
					+ '</div>'
					+ '<div id="kompak-row">'
						+ '<div>Usia</div>'
						+ '<div>:</div>'
						+ '<div name="usia"></div>'
					+ '</div>'
					+ '<div id="kompak-row">'
						+ '<div>Unit Kerja</div>'
						+ '<div>:</div>'
						+ '<div name="unit_kerja"></div>'
					+ '</div>'
					+ '<div id="kompak-row">'
						+ '<div>'
						//+ '<a name="readmore" style="font-size: 10px; font-family: Arial; text-align: right; font-weight: bold; text-decoration: none;">Read more ...</a>'
						+ '</div>'
					+ '</div>'
				+ '</div>'
			+ '</div>'
                ).css({
                    width: result.itemSize.width + "px",
                    height: result.itemSize.height + "px"
                }).addClass("bp-item bp-corner-all bt-item-frame");
                result.itemTemplate = itemTemplate.wrap('<div>').parent().html();

                return result;
            }

        });//]]>  
    </script>
    
<!-- STYLE GARIS ULAR -->
<style>
/* two selectors are required */
li{
	float:left;
	display:inline-block;
	*border:1px solid red;
	margin:10px;
	width:calc(25% - 20px);
	box-sizing:border-box;
}
/*li:nth-child(4n+3),
li:nth-child(4n+4) {
  background: yellow;
  float:right;
}
*/
li:nth-child(8n+5),
li:nth-child(8n+6),
li:nth-child(8n+7),
li:nth-child(8n+8) {
	background: yellow;
	float:right;
}
li:nth-child(8n+5) .area-karir .jabatan:before,
li:nth-child(8n+6) .area-karir .jabatan:before,
li:nth-child(8n+7) .area-karir .jabatan:before,
li:nth-child(8n+8) .area-karir .jabatan:before{
	content: url(../WEB/images/panah-kiri.png);
	*float:left;
	
	position:absolute;
	left:-10px;
}
li:nth-child(8n+5) .area-karir .jabatan:after,
li:nth-child(8n+6) .area-karir .jabatan:after,
li:nth-child(8n+7) .area-karir .jabatan:after,
li:nth-child(8n+8) .area-karir .jabatan:after{
	content: "";
}


</style>

</head>

<body>
<div id="wadah">
	
    <div id="header-tna-detil">Grafik <span>Rencana Karir</span></div>
    
    <div style="clear:both"></div>
    
    <div class="area-karir-wrapper">
    	<ol>
            <li>
                <div class="area-karir">
                    <div class="jabatan">Kasubbag perikanan</div>
                    <div class="data">
                        <table>
                            <tr>
                                <td>Tahun</td>
                                <td>:</td>
                                <td>1997</td>
                            </tr>
                            <tr>
                                <td>Usia</td>
                                <td>:</td>
                                <td>38 Tahun</td>
                            </tr>
                            <tr>
                                <td>Unit Kerja</td>
                                <td>:</td>
                                <td>DIREKTORAT JENDERAL PERIKANAN TANGKAP</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </li>
            <li>
            	<div class="area-karir">
                    <div class="jabatan">Karo Pertanian dan Kehutanan</div>
                    <div class="data">
                        <table>
                            <tr>
                                <td>Tahun</td>
                                <td>:</td>
                                <td>1998</td>
                            </tr>
                            <tr>
                                <td>Usia</td>
                                <td>:</td>
                                <td>39 Tahun</td>
                            </tr>
                            <tr>
                                <td>Unit Kerja</td>
                                <td>:</td>
                                <td>DIREKTORAT JENDERAL PERIKANAN TANGKAP</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </li>
            <li>
                <div class="area-karir">
                    <div class="jabatan">Kasubbag perikanan</div>
                    <div class="data">
                        <table>
                            <tr>
                                <td>Tahun</td>
                                <td>:</td>
                                <td>1997</td>
                            </tr>
                            <tr>
                                <td>Usia</td>
                                <td>:</td>
                                <td>38 Tahun</td>
                            </tr>
                            <tr>
                                <td>Unit Kerja</td>
                                <td>:</td>
                                <td>DIREKTORAT JENDERAL PERIKANAN TANGKAP</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </li>
            <li>
            	<div class="area-karir">
                    <div class="jabatan">Karo Pertanian dan Kehutanan</div>
                    <div class="data">
                        <table>
                            <tr>
                                <td>Tahun</td>
                                <td>:</td>
                                <td>1998</td>
                            </tr>
                            <tr>
                                <td>Usia</td>
                                <td>:</td>
                                <td>39 Tahun</td>
                            </tr>
                            <tr>
                                <td>Unit Kerja</td>
                                <td>:</td>
                                <td>DIREKTORAT JENDERAL PERIKANAN TANGKAP</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </li>
            <li>
                <div class="area-karir">
                    <div class="jabatan">Kasubbag perikanan</div>
                    <div class="data">
                        <table>
                            <tr>
                                <td>Tahun</td>
                                <td>:</td>
                                <td>1997</td>
                            </tr>
                            <tr>
                                <td>Usia</td>
                                <td>:</td>
                                <td>38 Tahun</td>
                            </tr>
                            <tr>
                                <td>Unit Kerja</td>
                                <td>:</td>
                                <td>DIREKTORAT JENDERAL PERIKANAN TANGKAP</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </li>
            <li>
            	<div class="area-karir">
                    <div class="jabatan">Karo Pertanian dan Kehutanan</div>
                    <div class="data">
                        <table>
                            <tr>
                                <td>Tahun</td>
                                <td>:</td>
                                <td>1998</td>
                            </tr>
                            <tr>
                                <td>Usia</td>
                                <td>:</td>
                                <td>39 Tahun</td>
                            </tr>
                            <tr>
                                <td>Unit Kerja</td>
                                <td>:</td>
                                <td>DIREKTORAT JENDERAL PERIKANAN TANGKAP</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </li>
            <li>
                <div class="area-karir">
                    <div class="jabatan">Kasubbag perikanan</div>
                    <div class="data">
                        <table>
                            <tr>
                                <td>Tahun</td>
                                <td>:</td>
                                <td>1997</td>
                            </tr>
                            <tr>
                                <td>Usia</td>
                                <td>:</td>
                                <td>38 Tahun</td>
                            </tr>
                            <tr>
                                <td>Unit Kerja</td>
                                <td>:</td>
                                <td>DIREKTORAT JENDERAL PERIKANAN TANGKAP</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </li>
            <li>
            	<div class="area-karir">
                    <div class="jabatan">Karo Pertanian dan Kehutanan</div>
                    <div class="data">
                        <table>
                            <tr>
                                <td>Tahun</td>
                                <td>:</td>
                                <td>1998</td>
                            </tr>
                            <tr>
                                <td>Usia</td>
                                <td>:</td>
                                <td>39 Tahun</td>
                            </tr>
                            <tr>
                                <td>Unit Kerja</td>
                                <td>:</td>
                                <td>DIREKTORAT JENDERAL PERIKANAN TANGKAP</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </li>
            <li>
                <div class="area-karir">
                    <div class="jabatan">Kasubbag perikanan</div>
                    <div class="data">
                        <table>
                            <tr>
                                <td>Tahun</td>
                                <td>:</td>
                                <td>1997</td>
                            </tr>
                            <tr>
                                <td>Usia</td>
                                <td>:</td>
                                <td>38 Tahun</td>
                            </tr>
                            <tr>
                                <td>Unit Kerja</td>
                                <td>:</td>
                                <td>DIREKTORAT JENDERAL PERIKANAN TANGKAP</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </li>
            <li>
            	<div class="area-karir">
                    <div class="jabatan">Karo Pertanian dan Kehutanan</div>
                    <div class="data">
                        <table>
                            <tr>
                                <td>Tahun</td>
                                <td>:</td>
                                <td>1998</td>
                            </tr>
                            <tr>
                                <td>Usia</td>
                                <td>:</td>
                                <td>39 Tahun</td>
                            </tr>
                            <tr>
                                <td>Unit Kerja</td>
                                <td>:</td>
                                <td>DIREKTORAT JENDERAL PERIKANAN TANGKAP</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </li>
            
        </ol>
        
    </div>
	
    <?php /*?><div id="basicdiagram" style="width: -moz-calc(100% - 10px);
    width: -webkit-calc(100% - 10px);
    width: -o-calc(100% - 10px);
    width: calc(100% - 10px); height:500px; position:relative; z-index:999"></div><?php */?>
    <!--<div id="basicdiagram" style="width: 100%; min-height:100%; border-style: dotted; border-width: 1px;" />-->
</div>
</body>
</html>