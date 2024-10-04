<div id="judul-main" style="margin-top:-50px;"><span> Main</span> Menu&nbsp;</div>
<div style="clear: both ;"></div>

<div id="content" style="visibility: hidden;">
    <div id="browser_incompatible" class="alert">
        <button class="close" data-dismiss="alert">Ã—</button>
        <strong>Warning!</strong>
        Your browser is incompatible with Droptiles. Please use Internet Explorer 9+, Chrome, Firefox or Safari.
    </div>
    <div id="CombinedScriptAlert" class="alert">
        <button class="close" data-dismiss="alert">Ã—</button>
        <strong>Warning!</strong>
        Combined javascript files are outdated. Please retun the js\Combine.bat file. Otherwise it won't work when you will deploy on a server.    
    </div>
    
    <div id="metro-sections-container" class="metro">
        <div id="trash" class="trashcan" data-bind="sortable: { data: trash }"></div>
        <div class="metro-sections" data-bind="foreach: sections">
    
            <div class="metro-section" data-bind="sortable: { data: tiles }">
                <div data-bind="attr: { id: uniqueId, 'class': tileClasses }">
                    <!-- ko if: tileImage -->
                    <div class="tile-image">
                        <img data-bind="attr: { src: tileImage }" src="droptiles/img/Internet Explorer.png" />
                    </div>
                    <!-- /ko -->
    
                    <!-- ko if: iconSrc -->
                    <!-- ko if: slides().length == 0 -->
                    <div data-bind="attr: { 'class': iconClasses }">
                        <img data-bind="attr: { src: iconSrc }" src="droptiles/img/Internet Explorer.png" />
                    </div>
                    <!-- /ko -->
                    <!-- /ko -->
    
                    <div data-bind="foreach: slides">
                        <div class="tile-content-main">
                            <div data-bind="html: $data">
                            </div>
                        </div>
                    </div>
    
                    <!-- ko if: label -->
                    <span class="tile-label" data-bind="html: label">Label</span>
                    <!-- /ko -->
    
                    <!-- ko if: counter -->
                    <span class="tile-counter" data-bind="html: counter">10</span>
                    <!-- /ko -->
    
                    <!-- ko if: subContent -->
                    <div data-bind="attr: { 'class': subContentClasses }, html: subContent">
                        subContent
                    </div>
                    <!-- /ko -->
    
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END METRO CONTENT -->

