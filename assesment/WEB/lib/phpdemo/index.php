<?
function encodeURIComponent($str) 
{
	$revert = array('%21'=>'!', '%2A'=>'*', '%27'=>"'", '%28'=>'(', '%29'=>')');
	return strtr(rawurlencode($str), $revert);
}

$jsonstring= $string= file_get_contents("../../../json/tes.json"); 

//$jsonstring = encodeURIComponent(json_decode($string));
//$data_json=json_decode($string,true);
//echo $jsonstring;
?>
<!DOCTYPE html> 
<html>
<head>
	<title>jQuery Widget Hierarchical Chart PHP Demo</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" href="demo/js/jquery/ui-lightness/jquery-ui-1.10.2.custom.css" />
	<script type="text/javascript" src="demo/js/jquery/jquery-1.9.1.js"></script>
	<script type="text/javascript" src="demo/js/jquery/jquery-ui-1.10.2.custom.js"></script>

	<script type="text/javascript" src="demo/js/json3.min.js"></script>

	<!-- jQuery UI Layout -->
	<script type="text/javascript" src="demo/jquerylayout/jquery.layout-latest.min.js"></script>
	<link rel="stylesheet" type="text/css" href="demo/jquerylayout/layout-default-latest.css" />

	<script type="text/javascript">
		jQuery(document).ready(function () {
			jQuery('body').layout(
			{
				center__paneSelector: "#contentpanel"
				, north__paneSelector: "#northpanel"
				, north__resizable: false
				, north__closable: false
				, north__spacing_open: 0
				, north__size: 40
			});
		});
	</script>

	<!-- # include file="src/src.primitives.html"-->

	<!-- header -->
	<link href="demo/css/primitives.latest.css?205" media="screen" rel="stylesheet" type="text/css" />
	<script  type="text/javascript" src="demo/js/primitives.min.js?205"></script>


	<script type="text/javascript">
		//var data="";
		/*function read_json() {
            $.getJSON("../../../json/tes.php", function(data) {
                alert("My data: " + data["id"]);
                $.each(data["prime"], function(idx,prime) {
                    alert("Prime number: " + prime);
                });
            });
        }*/
		
		/*$.ajax({
			   url: "../../../json/tes.php",
				   //force to handle it as text
			   dataType: "text",
					success: function (dataTest) 
					{
		
						//data downloaded so we call parseJSON function 
						//and pass downloaded data
						var data = $.parseJSON(dataTest);
						//now json variable contains data in json format
						//let's display a few items
						$.each(data, function (i, jsonObjectList) 
						{
							for (var index = 0; index < jsonObjectList.listValue_.length;index++) 
							{
								  alert(jsonObjectList.listKey_[index][0] + " -- " + jsonObjectList.listValue_[index].description_);
							}
						});
					 }
		  });*/



		/*function loadJSON(callback) 
		{
			var xobj = new XMLHttpRequest();
			xobj.overrideMimeType("application/json");
			xobj.open('GET', '../../../json/tes.php', true);
			xobj.onreadystatechange = function () 
			{
				if (xobj.readyState == 4 && xobj.status == "200") 
				{
				// .open will NOT return a value but simply returns undefined in async mode so use a callback
				callback(xobj.responseText);
				}
			}
			xobj.send(null);
		}
		
		// Call to function with anonymous callback
		loadJSON(function(response) 
		{
		// Do Something with the response e.g.
		data=JSON.parse(response);
		alert(data);
		// Assuming json data is wrapped in square brackets as Drew suggests
		//console.log(jsonresponse[0].name);
		
		});*/
		
		//alert(data);
		/* insert serialized JSON string here */
		<?php Print("var data=\"" . $jsonstring . "\";"); ?>		 
	</script>

	<script type="text/javascript">
		var orgDiagram = null;

		jQuery(document).ready(function () {
			jQuery.ajaxSetup({
				cache: false
			});

			jQuery('#contentpanel').layout(
			{
				center__paneSelector: "#centerpanel"
				, south__paneSelector: "#southpanel"
				, south__resizable: false
				, south__closable: false
				, south__spacing_open: 0
				, south__size: 50
				, west__size: 200
				, west__paneSelector: "#westpanel"
				, west__resizable: true
				, center__onresize: function () {
					if (orgDiagram != null) {
						jQuery("#centerpanel").orgDiagram("update", primitives.common.UpdateMode.Refresh);
					}
				}
			});

			var templates = [];
			templates.push(getContactTemplate());

			var items = JSON.parse(decodeURIComponent(data));
			items[0].templateName = "contactTemplate";
			
			orgDiagram = jQuery("#centerpanel").orgDiagram({
				items: items,
				cursorItem: 2,
				graphicsType: primitives.common.GraphicsType.SVG,
				pageFitMode: primitives.common.PageFitMode.FitToPage,
				verticalAlignment: primitives.common.VerticalAlignmentType.Middle,
				connectorType: primitives.common.ConnectorType.Angular,
				minimalVisibility: primitives.common.Visibility.Dot,
				selectionPathMode: primitives.common.SelectionPathMode.FullStack,
				leavesPlacementType: primitives.common.ChildrenPlacementType.Horizontal,
				hasButtons: primitives.common.Enabled.False,
				hasSelectorCheckbox: primitives.common.Enabled.False,
				templates: templates,
				onButtonClick: onButtonClick,
				onCursorChanging: onCursorChanging,
				onCursorChanged: onCursorChanged,
				onHighlightChanging: onHighlightChanging,
				onHighlightChanged: onHighlightChanged,
				onSelectionChanged: onSelectionChanged,
				onItemRender: onTemplateRender,
				itemTitleFirstFontColor: primitives.common.Colors.White,
				itemTitleSecondFontColor: primitives.common.Colors.White
			});
		});

		function getContactTemplate() {
			var result = new primitives.orgdiagram.TemplateConfig();
			result.name = "contactTemplate";

			result.itemSize = new primitives.common.Size(220, 120);
			result.minimizedItemSize = new primitives.common.Size(3, 3);
			result.highlightPadding = new primitives.common.Thickness(2, 2, 2, 2);


			var itemTemplate = jQuery(
			  '<div class="bp-item bp-corner-all bt-item-frame">'
				+ '<div class="bp-item bp-corner-all bp-title-frame" style="top: 2px; left: 2px; width: 216px; height: 20px;">'
					+ '<div name="title" class="bp-item bp-title" style="top: 3px; left: 6px; width: 208px; height: 18px;">'
					+ '</div>'
				+ '</div>'
				+ '<div class="bp-item bp-photo-frame" style="top: 26px; left: 2px; width: 50px; height: 60px;">'
					+ '<img name="photo" style="height:60px; width:50px;" />'
				+ '</div>'
				+ '<div name="phone" class="bp-item" style="top: 26px; left: 56px; width: 162px; height: 18px; font-size: 12px;"></div>'
				+ '<div name="email" class="bp-item" style="top: 44px; left: 56px; width: 162px; height: 18px; font-size: 12px;"></div>'
				+ '<div name="description" class="bp-item" style="top: 62px; left: 56px; width: 162px; height: 36px; font-size: 10px;"></div>'
				+ '<a name="readmore" class="bp-item" style="top: 104px; left: 4px; width: 212px; height: 12px; font-size: 10px; font-family: Arial; text-align: right; font-weight: bold; text-decoration: none; z-index:100;">Read more ...</a>'
			+ '</div>'
			).css({
				width: result.itemSize.width + "px",
				height: result.itemSize.height + "px"
			}).addClass("bp-item bp-corner-all bt-item-frame");
			result.itemTemplate = itemTemplate.wrap('<div>').parent().html();

			return result;
		}

		function onTemplateRender(event, data) {
			var hrefElement = data.element.find("[name=readmore]");
			switch (data.renderingMode) {
				case primitives.common.RenderingMode.Create:
					/* Initialize widgets here */
					hrefElement.click(function (e)
					{
						/* Block mouse click propogation in order to avoid layout updates before server postback*/
						primitives.common.stopPropagation(e);
					});
					break;
				case primitives.common.RenderingMode.Update:
					/* Update widgets here */
					break;
			}

			var itemConfig = data.context;

			if (data.templateName == "contactTemplate") {
				data.element.find("[name=photo]").attr({ "src": itemConfig.image });

				var fields = ["title", "description", "phone", "email"];
				for (var index = 0; index < fields.length; index += 1) {
					var field = fields[index];

					var element = data.element.find("[name=" + field + "]");
					if (element.text() != itemConfig[field]) {
						element.text(itemConfig[field]);
					}
				}
			}
			hrefElement.attr({ "href": itemConfig.href });
		}

		function onSelectionChanged(e, data) {
			var selectedItems = jQuery("#centerpanel").orgDiagram("option", "selectedItems");
			var message = "";
			for (var index = 0; index < selectedItems.length; index += 1) {
				var itemConfig = selectedItems[index];
				if (message != "") {
					message += ", ";
				}
				message += "<b>'" + itemConfig.title + "'</b>";
			}
			message += (data.parentItem != null ? " Parent item <b>'" + data.parentItem.title + "'" : "");
			jQuery("#southpanel").empty().append("User selected next items: " + message);
		}

		function onHighlightChanging(e, data) {
			var message = (data.context != null) ? "User is hovering mouse over item <b>'" + data.context.title + "'</b>." : "";
			message += (data.parentItem != null ? " Parent item <b>'" + data.parentItem.title + "'" : "");

			jQuery("#southpanel").empty().append(message);
		}

		function onHighlightChanged(e, data) {
			var message = (data.context != null) ? "User hovers mouse over item <b>'" + data.context.title + "'</b>." : "";
			message += (data.parentItem != null ? " Parent item <b>'" + data.parentItem.title + "'" : "");

			jQuery("#southpanel").empty().append(message);
		}

		function onCursorChanging(e, data) {
			var message = "User is clicking on item '" + data.context.title + "'.";
			message += (data.parentItem != null ? " Parent item <b>'" + data.parentItem.title + "'" : "");

			jQuery("#southpanel").empty().append(message);

			data.oldContext.templateName = null;
			data.context.templateName = "contactTemplate";
		}

		function onCursorChanged(e, data) {
			var message = "User clicked on item '" + data.context.title + "'.";
			message += (data.parentItem != null ? " Parent item <b>'" + data.parentItem.title + "'" : "");
			jQuery("#southpanel").empty().append(message);
		}

		function onButtonClick(e, data) {
			var message = "User clicked <b>'" + data.name + "'</b> button for item <b>'" + data.context.title + "'</b>.";
			message += (data.parentItem != null ? " Parent item <b>'" + data.parentItem.title + "'" : "");
			jQuery("#southpanel").empty().append(message);
		}

	</script>
</head>
<body style="font-size:12px" >
	<div id="contentpanel" style="padding: 0px;">
		<div id="centerpanel" style="overflow: hidden; padding: 0px; margin: 0px; border: 0px;">
		</div>
		
	</div>
</body>
</html>