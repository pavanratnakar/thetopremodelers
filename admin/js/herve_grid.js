// <![CDATA[
var herve_grid = {
    ready: function() {
        herve_grid.theme.init();
        herve_grid.menu.resize();
        herve_grid.jqgrid.init();
        herve_grid.menu.init();
        herve_grid.events.init();
    },
    events : {
        init : function(){
            $('.purge button').live('click', function(event){
                $.ajax({url: $.url+"/admin/controller/ajaxController.php?purge="+$(this).attr('id').replace('purge-',''), dataType: "json", cache: true, async: false, success: function(data, result) {

                }});
            });
            $('.purge button').live('hover', function(event){
                if ($(this).hasClass('ui-state-default')) {
                    $(this).addClass('ui-state-hover');
                    $(this).removeClass('ui-state-default');
                } else {
                    $(this).removeClass('ui-state-hover');
                    $(this).addClass('ui-state-default');
                }
            });
        }
    },
    theme : {
        width:760,
        date_width:100,
        category_width:120,
        amount_width:80,
        username_width:150,
        payment_width:100,
        note_width:140,
        init : function(){
            this.switcher();
        },
        switcher : function(){
            $('#switcher').themeswitcher();
        }
    },
    lib : {
        selectRefresh : function(container,data){
            container.empty();
                container.append($("<option/>", {
                    value: 'All',
                    text: 'All'
                }));
            $.each(data, function(key, value) {
                container.append($("<option/>", {
                    value: key,
                    text: value
                }));
            });
        }
    },
    menu :{
        menuId : "#west-grid",
        tabId : "#tabs",
        rightPane : "#RightPane",
        mainTab : "",
        xml : "tree.xml",
        xmlPath : "xml/",
        caption : "Admin Module",
        init : function(){
            this.tab();
        },
        resize : function(){
            $('body').layout({
                resizerClass: 'ui-state-default',
                west__onresize: function (pane, $Pane) {
                    $(herve_grid.menu.menuId).jqGrid('setGridWidth',$Pane.innerWidth()-2);
                }
            });
        },
        tab: function(){
            herve_grid.menu.maintab =$(herve_grid.menu.tabId,herve_grid.menu.rightPane).tabs({
                add: function(e, ui){
                    $(ui.tab).parents('li:first')
                        .append('<span class="ui-tabs-close ui-icon ui-icon-close" title="Close Tab"></span>')
                        .find('span.ui-tabs-close')
                        .click(function() {
                            herve_grid.menu.maintab.tabs('remove', $('li', herve_grid.menu.maintab).index($(this).parents('li:first')[0]));
                        });
                    herve_grid.menu.maintab.tabs('select', '#' + ui.panel.id);
                }
            });
            $(herve_grid.menu.menuId).jqGrid({
                url: herve_grid.menu.xmlPath+herve_grid.menu.xml,
                datatype: "xml",
                height: "auto",
                pager: false,
                loadui: "disable",
                colNames: ["id","Items","url"],
                colModel: [
                    {name: "id",width:1,hidden:true, key:true},
                    {name: "menu", width:150, resizable: false, sortable:false},
                    {name: "url",width:1,hidden:true}
                ],
                treeGrid: true,
                caption: herve_grid.menu.caption,
                ExpandColumn: "menu",
                autowidth: true,
                rowNum: 200,
                ExpandColClick: true,
                treeIcons: {leaf:'ui-icon-document-b'},
                onSelectRow: function(rowid){
                    var treedata = $(herve_grid.menu.menuId).jqGrid('getRowData',rowid);
                    if(treedata.isLeaf=="true"){
                        var st = "#t"+treedata.id;
                        if($(st).html() !== null ){
                            herve_grid.menu.maintab.tabs('select',st);
                        }else{
                            herve_grid.menu.maintab.tabs('add',st, treedata.menu);
                            $(st,"#tabs").load(treedata.url);
                        }
                    }
                }
            });
        }
    },
    jqgrid : {
        init : function() {
            this.default();
        },
        default : function() {
            $.jgrid.defaults = $.extend($.jgrid.defaults,{loadui:"enable"});
        },
        place: {
            id      :   "#place",
            page    :   "#p_place",
            select  :   "#place_select",
            init    :   function() {
                this.setup();
                herve_grid.jqgrid.crud(herve_grid.jqgrid.place.id,herve_grid.jqgrid.place.page);
            },
            setup   :   function() {
                this.places=$.ajax({url: $.url+"/admin/controller/placeController.php?ref=select&type=place", dataType: "json", cache: true, async: false, success: function(data, result) {if (!result) alert('Failure to retrieve the Place.');}}).responseText; 
                $(herve_grid.jqgrid.place.id).jqGrid({
                    url:$.url+'/admin/controller/placeController.php?ref=details',
                    datatype: "json",
                    height: 'auto',
                    width: herve_grid.theme.width,
                    colNames:['Id', 'Place Name', 'Place Title', 'Parent' , 'Active'],
                    colModel:[
                        {name:'place_id',index:'place_id',hidden:true,align:'center',editable:false, sorttype:'int',key:true},
                        {name:'place_name',index:'place_name', width:herve_grid.theme.note_width,formoptions:{label: 'Place Name'},align:"center", sortable:true,editable: true,editrules: { required: true } ,edittype:"textarea", editoptions:{rows:"2",cols:"20"}},
                        {name:'place_title',index:'place_title', width:herve_grid.theme.note_width,formoptions:{label: 'Place Title'},align:"center", sortable:true,editable: true,editrules: { required: true } ,edittype:"textarea", editoptions:{rows:"2",cols:"20"}},
                        {name:'under',index:'under', width:herve_grid.theme.note_width,formoptions:{label: 'Parent'},align:"center", sortable:true,editable: true,editrules: { required: false } ,edittype:"select",editoptions: {value: (":;"+this.places.replace('"','')).replace('"','') } },
                        {name:'active',index:'active', width:herve_grid.theme.note_width,formoptions:{label: 'Active'},align:"left", sortable:true,editable: true,editrules: { required: false } ,edittype:'checkbox', editoptions: { value:"1:0" } }
                    ],
                    rowNum:100,
                    rowList:[100,500,1000,1500,2000,2500,3000],
                    pager: herve_grid.jqgrid.place.page,
                    sortname: 'place_name',
                    viewrecords: true,
                    sortorder: "asc",
                    multiselect: false,
                    subGrid: false,
                    caption: "Place Operations",
                    editurl:$.url+"/admin/controller/placeController.php?ref=operation",
                    grouping: false,
                    groupingView : {
                        groupField : ['place_name'],
                        groupColumnShow : [true],
                        groupText : ['<b>{0}</b>'],
                        groupCollapse : false,
                        groupOrder: ['desc'],
                        groupSummary : [true],
                        showSummaryOnHide: true,
                        groupDataSorted : true
                    },
                    footerrow: true,
                    userDataOnFooter: true
                });
            }
        },
        placeCode: {
            id      :   "#placeCode",
            page    :   "#p_placeCode",
            select  :   "#placeCode_select",
            init    :   function() {
                this.setup();
                herve_grid.jqgrid.crud(herve_grid.jqgrid.placeCode.id,herve_grid.jqgrid.placeCode.page);
            },
            setup   :   function() {
                this.places=$.ajax({url: $.url+"/admin/controller/placeController.php?ref=select&type=place", dataType: "json", cache: true, async: false, success: function(data, result) {if (!result) alert('Failure to retrieve the Place.');}}).responseText; 
                $(herve_grid.jqgrid.placeCode.id).jqGrid({
                    url:$.url+'/admin/controller/placeCodeController.php?ref=details',
                    datatype: "json",
                    height: 'auto',
                    width: herve_grid.theme.width,
                    colNames:['Id', 'Code', 'Place Title', 'Type'],
                    colModel:[
                        {name:'placeCode_id',index:'placeCode_id',hidden:true,align:'center',editable:false, sorttype:'int',key:true},
                        {name:'code',index:'code', width:herve_grid.theme.note_width,formoptions:{label: 'Code'},align:"center", sortable:true,editable: true,editrules: { required: true } ,edittype:"textarea", editoptions:{rows:"2",cols:"20"}},
                        {name:'place_title',index:'place_title', width:herve_grid.theme.note_width,formoptions:{label: 'Place'},align:"center", sortable:true,editable: true,editrules: { required: true } ,edittype:"select",editoptions: {value: (this.places.replace('"','')).replace('"','') } },
                        {name:'type',index:'type', width:herve_grid.theme.note_width,formoptions:{label: 'Type'},align:"center", sortable:true,editable: true,editrules: { required: false } ,edittype:"select", editoptions: {value: "Standard:Standard;PO Box:PO Box;Unique:Unique" } }
                    ],
                    rowNum:100,
                    rowList:[100,500,1000,1500,2000,2500,3000],
                    pager: herve_grid.jqgrid.placeCode.page,
                    sortname: 'place_title',
                    viewrecords: true,
                    sortorder: "asc",
                    multiselect: false,
                    subGrid: false,
                    caption: "Place Operations",
                    editurl:$.url+"/admin/controller/placeCodeController.php?ref=operation",
                    grouping: true,
                    groupingView : {
                        groupField : ['place_title'],
                        groupColumnShow : [true],
                        groupText : ['<b>{0}</b>'],
                        groupCollapse : false,
                        groupOrder: ['desc'],
                        groupSummary : [true],
                        showSummaryOnHide: true,
                        groupDataSorted : true
                    },
                    footerrow: true,
                    userDataOnFooter: true
                });
            }
        },
        category: {
            id      :   "#category",
            page    :   "#p_category",
            select  :   "#category_select",
            init    :   function() {
                this.setup();
                herve_grid.jqgrid.crud(herve_grid.jqgrid.category.id,herve_grid.jqgrid.category.page);
            },
            setup   :   function() {
                $(herve_grid.jqgrid.category.id).jqGrid({
                    url:$.url+'/admin/controller/categoryController.php?ref=details',
                    datatype: "json",
                    height: 'auto',
                    width: herve_grid.theme.width,
                    colNames:['Id', 'Category Name', 'Category Title', 'Category Value', 'Category Order', 'Category Position', 'Active'],
                    colModel:[
                        {name:'category_id',index:'category_id',hidden:true,align:'center',editable:false, sorttype:'int',key:true},
                        {name:'category_name',index:'category_name', width:herve_grid.theme.note_width,formoptions:{label: 'Category Name'},align:"center", sortable:true,editable: true,editrules: { required: true } ,edittype:"textarea", editoptions:{rows:"2",cols:"20"}},
                        {name:'category_title',index:'category_title', width:herve_grid.theme.note_width,formoptions:{label: 'Category Title'},align:"center", sortable:true,editable: true,editrules: { required: true } ,edittype:"textarea", editoptions:{rows:"2",cols:"20"}},
                        {name:'category_value',index:'category_value', width:herve_grid.theme.note_width,formoptions:{label: 'Category Value'},align:"center", sortable:true,editable: true,editrules: { required: true } ,edittype:"textarea", editoptions:{rows:"2",cols:"20"}},
                        {name:'category_order',index:'category_order', width:herve_grid.theme.note_width,formoptions:{label: 'Order'},align:"center", sortable:true,editable: true,editrules: { required: true } ,edittype:"textarea", editoptions:{rows:"2",cols:"20"}},
                        {name:'position',index:'position', width:herve_grid.theme.note_width,formoptions:{label: 'Position'},align:"center", sortable:true,editable: true,editrules: { required: true } ,edittype:"select",editoptions:{value:"1:1;2:2"}},
                        {name:'active',index:'active', width:herve_grid.theme.note_width,formoptions:{label: 'Active'},align:"center", sortable:true,editable: true,editrules: { required: true } ,edittype:"select",editoptions:{value:"1:True;0:False"}}
                    ],
                    rowNum:100,
                    rowList:[100,500,1000,1500,2000,2500,3000],
                    pager: herve_grid.jqgrid.category.page,
                    sortname: 'category_order',
                    viewrecords: true,
                    sortorder: "asc",
                    multiselect: false,
                    subGrid: false,
                    caption: "Category Operations",
                    editurl:$.url+"/admin/controller/categoryController.php?ref=operation",
                    grouping: false,
                    groupingView : {
                        groupField : ['position'],
                        groupColumnShow : [true],
                        groupText : ['<b>{0}</b>'],
                        groupCollapse : false,
                        groupOrder: ['desc'],
                        groupSummary : [true],
                        showSummaryOnHide: true,
                        groupDataSorted : true
                    },
                    footerrow: true,
                    userDataOnFooter: true
                });
            }
        },
        placeCategory: {
            id      :   "#placeCategory",
            page    :   "#p_placeCategory",
            select  :   "#placeCategory_select",
            init    :   function() {
                this.setup();
                herve_grid.jqgrid.crud(herve_grid.jqgrid.placeCategory.id,herve_grid.jqgrid.placeCategory.page);
            },
            setup   :   function() {
                this.categories=$.ajax({url: $.url+"/admin/controller/categoryController.php?ref=select&type=category", dataType: "json", cache: true, async: false, success: function(data, result) {if (!result) alert('Failure to retrieve the Category.');}}).responseText;
                this.places=$.ajax({url: $.url+"/admin/controller/placeController.php?ref=select&type=place", dataType: "json", cache: true, async: false, success: function(data, result) {if (!result) alert('Failure to retrieve the Place.');}}).responseText;
                $(herve_grid.jqgrid.placeCategory.id).jqGrid({
                    url:$.url+'/admin/controller/placeCategoryController.php?ref=details',
                    datatype: "json",
                    height: 'auto',
                    width: herve_grid.theme.width,
                    colNames:['Id', 'Place Name', 'Category Name', 'Active'],
                    colModel:[
                        {name:'placeCategory_id',index:'placeCategory_id',hidden:true,align:'center',editable:false, sorttype:'int',key:true},
                        {name:'place_title',index:'place_title', width:herve_grid.theme.note_width,formoptions:{label: 'Place'},align:"center", sortable:true,editable: true,editrules: { required: true } ,edittype:"select",editoptions: {value: (this.places.replace('"','')).replace('"','') } },
                        {name:'category_title',index:'categocategory_titlery_name', width:herve_grid.theme.note_width,formoptions:{label: 'Category'},align:"center", sortable:true,editable: true,editrules: { required: true } ,edittype:"select",editoptions: {value: ('All:All;'+this.categories.replace('"','')).replace('"','') } },
                        {name:'active',index:'active', width:herve_grid.theme.note_width,formoptions:{label: 'Active'},align:"center", sortable:true,editable: true,editrules: { required: true } ,edittype:"select",editoptions:{value:"1:True;0:False"}}
                    ],
                    rowNum:100,
                    rowList:[100,500,1000,1500,2000,2500,3000],
                    pager: herve_grid.jqgrid.placeCategory.page,
                    sortname: 'place_title',
                    viewrecords: true,
                    sortorder: "asc",
                    multiselect: false,
                    subGrid: false,
                    caption: "Category - Place Mapping",
                    editurl:$.url+"/admin/controller/placeCategoryController.php?ref=operation",
                    grouping: true,
                    groupingView : {
                        groupField : ['place_title'],
                        groupColumnShow : [true],
                        groupText : ['<b>{0} - {1} Item(s)</b>'],
                        groupCollapse : false,
                        groupOrder: ['desc'],
                        groupSummary : [true],
                        showSummaryOnHide: true,
                        groupDataSorted : true
                    },
                    footerrow: true,
                    userDataOnFooter: true
                });
            }
        },
        section: {
            id      :   "#section",
            page    :   "#p_section",
            select  :   "#section_select",
            init    :   function() {
                this.setup();
                herve_grid.jqgrid.crud(herve_grid.jqgrid.section.id,herve_grid.jqgrid.section.page);
            },
            setup   :   function() {
                $(herve_grid.jqgrid.section.id).jqGrid({
                    url:$.url+'/admin/controller/sectionController.php?ref=details',
                    datatype: "json",
                    height: 'auto',
                    width: herve_grid.theme.width,
                    colNames:['Id', 'Section Name', 'Section Title'],
                    colModel:[
                        {name:'section_id',index:'section_id',hidden:true,align:'center',editable:false, sorttype:'int',key:true},
                        {name:'section_name',index:'section_name', width:herve_grid.theme.note_width,formoptions:{label: 'Section Name'},align:"center", sortable:true,editable: true,editrules: { required: true } ,edittype:"textarea", editoptions:{rows:"2",cols:"20"}},
                        {name:'section_title',index:'section_title', width:herve_grid.theme.note_width,formoptions:{label: 'Section Title'},align:"center", sortable:true,editable: true,editrules: { required: true } ,edittype:"textarea", editoptions:{rows:"2",cols:"20"}}
                    ],
                    rowNum:100,
                    rowList:[100,500,1000,1500,2000,2500,3000],
                    pager: herve_grid.jqgrid.section.page,
                    sortname: 'section_title',
                    viewrecords: true,
                    sortorder: "asc",
                    multiselect: false,
                    subGrid: false,
                    caption: "Task Operations",
                    editurl:$.url+"/admin/controller/sectionController.php?ref=operation",
                    grouping: false,
                    groupingView : {
                        groupField : ['section_title'],
                        groupColumnShow : [true],
                        groupText : ['<b>{0}</b>'],
                        groupCollapse : false,
                        groupOrder: ['desc'],
                        groupSummary : [true],
                        showSummaryOnHide: true,
                        groupDataSorted : true
                    },
                    footerrow: true,
                    userDataOnFooter: true
                });
            }
        },
        categorySection: {
            id      :   "#categorySection",
            page    :   "#p_categorySection",
            select  :   "#categorySection_select",
            init    :   function() {
                this.setup();
                herve_grid.jqgrid.crud(herve_grid.jqgrid.categorySection.id,herve_grid.jqgrid.categorySection.page);
            },
            setup   :   function() {
                // $('#TblGrid_categorySection #place_title').live('change', function(event){
                //     $.ajax({url: $.url+"/admin/controller/placeCategoryController.php?ref=select&type=placeCategory&place_id="+$('#TblGrid_categorySection #place_title').val(), dataType: "json", cache: true, async: false, success: function(data, result) {
                //         if (!result) alert('Failure to retrieve the Answers.');
                //         //$(herve_grid.jqgrid.categorySection.id).jqGrid.setColProp('category_name', {editoptions : data});
                //         herve_grid.lib.selectRefresh($('#TblGrid_categorySection #tr_category_title select'),data);
                //     }}).responseText;
                // });
                this.places=$.ajax({url: $.url+"/admin/controller/placeController.php?ref=select&type=place", dataType: "json", cache: true, async: false, success: function(data, result) {if (!result) alert('Failure to retrieve the Answers.');}}).responseText;
                this.sections=$.ajax({url: $.url+"/admin/controller/sectionController.php?ref=select&type=section", dataType: "json", cache: true, async: false, success: function(data, result) {if (!result) alert('Failure to retrieve the Questions.');}}).responseText;
                this.categories=$.ajax({url: $.url+"/admin/controller/categoryController.php?ref=select&type=category", dataType: "json", cache: true, async: false, success: function(data, result) {if (!result) alert('Failure to retrieve the Category.');}}).responseText;

                $(herve_grid.jqgrid.categorySection.id).jqGrid({
                    url:$.url+'/admin/controller/categorySectionController.php?ref=details',
                    datatype: "json",
                    height: 'auto',
                    width: herve_grid.theme.width,
                    colNames:['Id', 'Place Name','Category Name', 'Section Name', 'Order', 'Meta Id', 'Active'],
                    colModel:[
                        {name:'categorySection_id',index:'categorySection_id',hidden:true,align:'center',editable:false, sorttype:'int',key:true},
                        {name:'place_title',index:'place_title', width:herve_grid.theme.note_width,formoptions:{label: 'Place'},align:"center", sortable:true,editable: true,editrules: { required: true } ,edittype:"select",editoptions: { value: (this.places.replace('"','')).replace('"','') } },
                        {name:'category_title',index:'category_title', width:herve_grid.theme.note_width,formoptions:{label: 'Category'},align:"center", sortable:true,editable: true,editrules: { required: true } ,edittype:"select", editoptions: { value: (this.categories.replace('"','')).replace('"','') } },
                        {name:'section_title',index:'section_title', width:herve_grid.theme.note_width,formoptions:{label: 'Section'},align:"center", sortable:true,editable: true,editrules: { required: true } ,edittype:"select",editoptions: { value: ('All:All;'+this.sections.replace('"','')).replace('"','') } },
                        {name:'categorysection_order',index:'categorysection_order', width:herve_grid.theme.note_width,formoptions:{label: 'Order'},align:"center", sortable:true,editable: true,editrules: { required: false } ,edittype:"textarea", editoptions:{rows:"2",cols:"20"}},
                        {name:'meta_id',index:'meta_id', width:herve_grid.theme.note_width,formoptions:{label: 'Meta Id'},align:"center", sortable:true,editable: true,editrules: { required: true } ,edittype:"textarea", editoptions:{rows:"2",cols:"20"}},
                        {name:'active',index:'active', width:herve_grid.theme.note_width,formoptions:{label: 'Active'},align:"center", sortable:true,editable: true,editrules: { required: true } ,edittype:"select",editoptions:{value:"1:True;0:False"}}
                    ],
                    rowNum:100,
                    rowList:[100,500,1000,1500,2000,2500,3000],
                    pager: herve_grid.jqgrid.categorySection.page,
                    sortname: 'categorysection_order',
                    viewrecords: true,
                    sortorder: "asc",
                    multiselect: false,
                    subGrid: false,
                    caption: "Task Mapping",
                    editurl:$.url+"/admin/controller/categorySectionController.php?ref=operation",
                    grouping: true,
                    groupingView : {
                        groupField : ['place_title'],
                        groupColumnShow : [true],
                        groupText : ['<b>{0} - {1} Item(s)</b>'],
                        groupCollapse : true,
                        groupOrder: ['desc'],
                        groupSummary : [true],
                        showSummaryOnHide: true,
                        groupDataSorted : true
                    },
                    footerrow: true,
                    userDataOnFooter: true
                });
            }
        },
        contractor: {
            id      :   "#contractor",
            page    :   "#p_contractor",
            select  :   "#contractor_select",
            init    :   function() {
                this.setup();
                herve_grid.jqgrid.crud(herve_grid.jqgrid.contractor.id,herve_grid.jqgrid.contractor.page);
            },
            setup   :   function() {
                $(herve_grid.jqgrid.contractor.id).jqGrid({
                    url:$.url+'/admin/controller/contractorController.php?ref=details',
                    datatype: "json",
                    height: 'auto',
                    width: herve_grid.theme.width,
                    colNames:['Id', 'Title', 'Desciption', 'Phone', 'Address', 'Name'],
                    colModel:[
                        {name:'contractor_id',index:'contractor_id',hidden:true,align:'center',editable:false, sorttype:'int',key:true},
                        {name:'contractor_title',index:'contractor_title', width:herve_grid.theme.note_width,formoptions:{label: 'Contractor Title'},align:"center", sortable:true,editable: true,editrules: { required: true } ,edittype:"textarea", editoptions:{rows:"2",cols:"20"}},
                        {name:'contractor_description',index:'contractor_description', width:herve_grid.theme.note_width,formoptions:{label: 'Contractor Description'},align:"center", sortable:true,editable: true,editrules: { required: false } ,edittype:"textarea", editoptions:{rows:"2",cols:"20"}},
                        {name:'contractor_phone',index:'contractor_phone', width:herve_grid.theme.note_width,formoptions:{label: 'Contractor Phone'},align:"center", sortable:true,editable: true,editrules: { required: false } ,edittype:"textarea", editoptions:{rows:"2",cols:"20"}},
                        {name:'contractor_address',index:'contractor_address', width:herve_grid.theme.note_width,formoptions:{label: 'Contractor Address'},align:"center", sortable:true,editable: true,editrules: { required: false } ,edittype:"textarea", editoptions:{rows:"2",cols:"20"}},
                        {name:'contractor_name',index:'contractor_name', width:herve_grid.theme.note_width,formoptions:{label: 'Contractor Name'},align:"center", sortable:true,editable: true,editrules: { required: true } ,edittype:"textarea", editoptions:{rows:"2",cols:"20"}}                    ],
                    rowNum:100,
                    rowList:[100,500,1000,1500,2000,2500,3000],
                    pager: herve_grid.jqgrid.contractor.page,
                    sortname: 'contractor_name',
                    viewrecords: true,
                    sortorder: "asc",
                    multiselect: false,
                    subGrid: false,
                    caption: "Contractor Operations",
                    editurl:$.url+"/admin/controller/contractorController.php?ref=operation",
                    grouping: false,
                    groupingView : {
                        groupField : ['contractor_name'],
                        groupColumnShow : [true],
                        groupText : ['<b>{0}</b>'],
                        groupCollapse : false,
                        groupOrder: ['desc'],
                        groupSummary : [true],
                        showSummaryOnHide: true,
                        groupDataSorted : true
                    },
                    footerrow: true,
                    userDataOnFooter: true
                });
            }
        },
        contractorMapping: {
            id      :   "#contractorMapping",
            page    :   "#p_contractorMapping",
            select  :   "#contractorMapping_select",
            init    :   function() {
                this.setup();
                herve_grid.jqgrid.crud(herve_grid.jqgrid.contractorMapping.id,herve_grid.jqgrid.contractorMapping.page);
            },
            setup   :   function() {
                // $('#TblGrid_contractorMapping #place_title').live('change', function(event){
                //     $.ajax({url: $.url+"/admin/controller/placeCategoryController.php?ref=select&type=placeCategory&place_id="+$('#TblGrid_contractorMapping #place_title').val(), dataType: "json", cache: true, async: false, success: function(data, result) {
                //         if (!result) {
                //             alert('Failure to retrieve the Answers.');
                //         } else {
                //             var firstKey = null;
                //             $.each(data, function(key, value) {
                //                 firstKey = firstKey || key;
                //             });
                //             $.ajax({url: $.url+"/admin/controller/categorySectionController.php?ref=select&type=categorySection&placeCategory_id="+firstKey, dataType: "json", cache: true, async: false, success: function(data1, result1) {
                //                 if (!result) {
                //                     alert('Failure to retrieve the Categories.');
                //                 } else {
                //                     herve_grid.lib.selectRefresh($('#TblGrid_contractorMapping #tr_section_title select'),data1);
                //                 }
                //             }}).responseText;
                //             herve_grid.lib.selectRefresh($('#TblGrid_contractorMapping #tr_category_title select'),data);
                //         }
                //     }}).responseText;
                // });
                //  $('#TblGrid_contractorMapping #category_title').live('change', function(event){
                //     $.ajax({url: $.url+"/admin/controller/categorySectionController.php?ref=select&type=categorySection&placeCategory_id="+$('#TblGrid_contractorMapping #category_title').val(), dataType: "json", cache: true, async: false, success: function(data, result) {
                //     if (!result) {
                //         alert('Failure to retrieve the Tasks.');
                //     } else {
                //         herve_grid.lib.selectRefresh($('#TblGrid_contractorMapping #tr_section_title select'),data);
                //     }
                //     }}).responseText;
                // });
                this.places=$.ajax({url: $.url+"/admin/controller/placeController.php?ref=select&type=place", dataType: "json", cache: true, async: false, success: function(data, result) {if (!result) alert('Failure to retrieve the Places.');}}).responseText;
                this.contractors=$.ajax({url: $.url+"/admin/controller/contractorController.php?ref=select&type=contractor", dataType: "json", cache: true, async: false, success: function(data, result) {if (!result) alert('Failure to retrieve the Contractors.');}}).responseText;
                this.categories=$.ajax({url: $.url+"/admin/controller/categoryController.php?ref=select&type=category", dataType: "json", cache: true, async: false, success: function(data, result) {if (!result) alert('Failure to retrieve the Category.');}}).responseText;
                this.sections=$.ajax({url: $.url+"/admin/controller/sectionController.php?ref=select&type=section", dataType: "json", cache: true, async: false, success: function(data, result) {if (!result) alert('Failure to retrieve the Questions.');}}).responseText;

                $(herve_grid.jqgrid.contractorMapping.id).jqGrid({
                    url:$.url+'/admin/controller/contractorMappingController.php?ref=details',
                    datatype: "json",
                    height: 'auto',
                    width: herve_grid.theme.width,
                    colNames:['Id','Place Name','Category Name','Section Name','Contractor Name','Active'],
                    colModel:[
                        {name:'contractorMapping_id',index:'contractorMapping_id',hidden:true,align:'center',editable:false, sorttype:'int',key:true},
                        {name:'place_title',index:'place_title', width:herve_grid.theme.note_width,formoptions:{label: 'Place'},align:"center", sortable:true,editable: true,editrules: { required: true } ,edittype:"select",editoptions: {value: (this.places.replace('"','')).replace('"','') } },
                        {name:'category_title',index:'category_title', width:herve_grid.theme.note_width,formoptions:{label: 'Category'},align:"center", sortable:true,editable: true,editrules: { required: true } ,edittype:"select",editoptions: {value: (this.categories.replace('"','')).replace('"','') } },
                        {name:'section_title',index:'section_title', width:herve_grid.theme.note_width,formoptions:{label: 'Section'},align:"center", sortable:true,editable: true,editrules: { required: true } ,edittype:"select",editoptions:  {value: 'All:All;'+(this.sections.replace('"','')).replace('"','') } },
                        {name:'contractor_title',index:'contractor_title', width:herve_grid.theme.note_width,formoptions:{label: 'Contractor'},align:"center", sortable:true,editable: true,editrules: { required: true } ,edittype:"select",editoptions: {value: (this.contractors.replace('"','')).replace('"','') } },
                        {name:'active',index:'active', width:herve_grid.theme.note_width,formoptions:{label: 'Active'},align:"center", sortable:true,editable: true,editrules: { required: true } ,edittype:"select",editoptions:{value:"1:True;0:False"}}
                    ],
                    rowNum:1000,
                    rowList:[1000,2000,3000,4000,5000],
                    pager: herve_grid.jqgrid.contractorMapping.page,
                    sortname: 'contractorMapping_id',
                    viewrecords: true,
                    sortorder: "asc",
                    multiselect: false,
                    subGrid: false,
                    caption: "Contractor Mapping",
                    editurl:$.url+"/admin/controller/contractorMappingController.php?ref=operation",
                    grouping: true,
                    groupingView : {
                        groupField : ['contractor_title'],
                        groupColumnShow : [true],
                        groupText : ['<b>{0} - {1} Item(s)</b>'],
                        groupCollapse : true,
                        groupOrder: ['desc'],
                        groupSummary : [true],
                        showSummaryOnHide: true,
                        groupDataSorted : true
                    },
                    footerrow: true,
                    userDataOnFooter: true
                });
            }
        },
        contractorRating: {
            id      :   "#contractorRating",
            page    :   "#p_contractorRating",
            select  :   "#contractorRating_select",
            init    :   function() {
                this.setup();
                herve_grid.jqgrid.crud(herve_grid.jqgrid.contractorRating.id,herve_grid.jqgrid.contractorRating.page);
            },
            setup   :   function() {
                this.contractors=$.ajax({url: $.url+"/admin/controller/contractorController.php?ref=select&type=contractor", dataType: "json", cache: true, async: false, success: function(data, result) {if (!result) alert('Failure to retrieve the Contractors.');}}).responseText;
                this.places=$.ajax({url: $.url+"/admin/controller/placeController.php?ref=select&type=place", dataType: "json", cache: true, async: false, success: function(data, result) {if (!result) alert('Failure to retrieve the Places.');}}).responseText;

                $(herve_grid.jqgrid.contractorRating.id).jqGrid({
                    url:$.url+'/admin/controller/contractorRatingController.php?ref=details',
                    datatype: "json",
                    height: 'auto',
                    width: herve_grid.theme.width,
                    colNames:['Id','Score','Review','Contractor Name','Timestamp','Person','Place','Project'],
                    colModel:[
                        {name:'contractorRating_id',index:'contractorRating_id',hidden:true,align:'center',editable:false, sorttype:'int',key:true},
                        {name:'score',index:'score', width:herve_grid.theme.note_width,formoptions:{label: 'Score'},align:"center", sortable:true,editable: true,editrules: { required: true } ,edittype:"select", editoptions:{value:"0:0;1:1;2:2;3:3;4:4;5:5"}},
                        {name:'review',index:'review', width:herve_grid.theme.note_width,formoptions:{label: 'Review'},align:"center", sortable:true,editable: true,editrules: { required: false } ,edittype:"textarea", editoptions:{rows:"2",cols:"20"}},
                        {name:'contractor_title',index:'contractor_title', width:herve_grid.theme.note_width,formoptions:{label: 'Contractor'},align:"center", sortable:true,editable: true,editrules: { required: true } ,edittype:"select",editoptions: {value: (this.contractors.replace('"','')).replace('"','') } },
                        {name:'timestamp',index:'timestamp', width:herve_grid.theme.note_width,formoptions:{label: 'Timestamp'},align:"center", sortable:true,editable:true, editoptions: { dataInit: function (elem) { $(elem).datepicker(); } }},
                        {name:'person',index:'person', width:herve_grid.theme.note_width,formoptions:{label: 'Person'},align:"center", sortable:true,editable: true,editrules: { required: true } ,edittype:"textarea", editoptions:{rows:"2",cols:"20"}},
                        {name:'place_title',index:'place_title', width:herve_grid.theme.note_width,formoptions:{label: 'Place'},align:"center", sortable:true,editable: true,editrules: { required: true } ,edittype:"select",editoptions: {value: (this.places.replace('"','')).replace('"','') } },
                        {name:'project',index:'project', width:herve_grid.theme.note_width,formoptions:{label: 'Project'},align:"center", sortable:true,editable: true,editrules: { required: true } ,edittype:"textarea", editoptions:{rows:"2",cols:"20"}}
                    ],
                    rowNum:100,
                    rowList:[100,500,1000,1500,2000,2500,3000],
                    pager: herve_grid.jqgrid.contractorRating.page,
                    sortname: 'score',
                    viewrecords: true,
                    sortorder: "asc",
                    multiselect: false,
                    subGrid: false,
                    caption: "Contractor Mapping",
                    editurl:$.url+"/admin/controller/contractorRatingController.php?ref=operation",
                    grouping: true,
                    groupingView : {
                        groupField : ['contractor_title'],
                        groupColumnShow : [true],
                        groupText : ['<b>{0} - {1} Item(s)</b>'],
                        groupCollapse : false,
                        groupOrder: ['desc'],
                        groupSummary : [true],
                        showSummaryOnHide: true,
                        groupDataSorted : true
                    },
                    footerrow: true,
                    userDataOnFooter: true
                });
            }
        },
        contractorImage: {
            id      :   "#contractorImage",
            page    :   "#p_contractorImage",
            select  :   "#contractorImage_select",
            init    :   function() {
                this.setup();
                herve_grid.jqgrid.crud(herve_grid.jqgrid.contractorImage.id,herve_grid.jqgrid.contractorImage.page);
            },
            setup   :   function() {
                this.contractors=$.ajax({url: $.url+"/admin/controller/contractorController.php?ref=select&type=contractor", dataType: "json", cache: true, async: false, success: function(data, result) {if (!result) alert('Failure to retrieve the Contractors.');}}).responseText;
                this.images=$.ajax({url: $.url+"/admin/controller/ajaxController.php?action=files&type=images", dataType: "json", cache: true, async: false, success: function(data, result) {if (!result) alert('Failure to retrieve the Images.');}}).responseText;

                $(herve_grid.jqgrid.contractorImage.id).jqGrid({
                    url:$.url+'/admin/controller/contractorImageController.php?ref=details',
                    datatype: "json",
                    height: 'auto',
                    width: herve_grid.theme.width,
                    colNames:['Id','Contractor Name','Type','Image Name'],
                    colModel:[
                        {name:'contractorImage_id',index:'contractorImage_id',hidden:true,align:'center',editable:false, sorttype:'int',key:true},
                        {name:'contractor_title',index:'contractor_title', width:herve_grid.theme.note_width,formoptions:{label: 'Contractor Name'},align:"center", sortable:true,editable: true,editrules: { required: true } ,edittype:"select",editoptions: {value: (this.contractors.replace('"','')).replace('"','') } },
                        {name:'type',index:'type', width:herve_grid.theme.note_width,formoptions:{label: 'Type'},align:"center", sortable:true,editable: true,edittype:"select",editoptions: {value: ('1:Profile;2:Other')} },
                        {name:'image_id',index:'image_id', width:herve_grid.theme.note_width,formoptions:{label: 'Image Name'},align:"center", sortable:true,editable: true,editrules: { required: true } ,edittype:"select",editoptions: {value: (this.images.replace('"','')).replace('"','') } }
                    ],
                    rowNum:100,
                    rowList:[100,500,1000,1500,2000,2500,3000],
                    pager: herve_grid.jqgrid.contractorImage.page,
                    sortname: 'contractor_title',
                    viewrecords: true,
                    sortorder: "asc",
                    multiselect: false,
                    subGrid: false,
                    caption: "Contractor Mapping",
                    editurl:$.url+"/admin/controller/contractorImageController.php?ref=operation",
                    grouping: true,
                    groupingView : {
                        groupField : ['contractor_title'],
                        groupColumnShow : [true],
                        groupText : ['<b>{0} - {1} Item(s)</b>'],
                        groupCollapse : false,
                        groupOrder: ['asc'],
                        groupSummary : [true],
                        showSummaryOnHide: true,
                        groupDataSorted : true
                    },
                    footerrow: true,
                    userDataOnFooter: true
                });
            }
        },
        article: {
            id      :   "#article",
            page    :   "#p_article",
            select  :   "#article_select",
            init    :   function() {
                this.setup();
                herve_grid.jqgrid.crud(herve_grid.jqgrid.article.id,herve_grid.jqgrid.article.page);
            },
            setup   :   function() {
                $(herve_grid.jqgrid.article.id).jqGrid({
                    url:$.url+'/admin/controller/articleController.php?ref=details',
                    datatype: "json",
                    height: 'auto',
                    width: herve_grid.theme.width,
                    colNames:['Id','Name','Title','Keywords','Description','Content','Active'],
                    colModel:[
                        {name:'id',index:'id',hidden:true,align:'center',editable:false, sorttype:'int',key:true},
                        {name:'name',index:'name', width:herve_grid.theme.note_width,formoptions:{label: 'Name'},align:"center", sortable:true,editable: true,editrules: { required: true } ,edittype:"textarea", editoptions:{rows:"2",cols:"10"}},
                        {name:'title',index:'title', width:herve_grid.theme.note_width,formoptions:{label: 'Title'},align:"center", sortable:true,editable: true,editrules: { required: true } ,edittype:"textarea", editoptions:{rows:"2",cols:"10"}},
                        {name:'keywords',index:'keywords', width:herve_grid.theme.note_width,formoptions:{label: 'Keywords'},align:"center", sortable:true,editable: true,editrules: { required: false } ,edittype:"textarea", editoptions:{rows:"2",cols:"10"}},
                        {name:'description',index:'description', width:herve_grid.theme.note_width,formoptions:{label: 'Description'},align:"center", sortable:true,editable: true,editrules: { required: true } ,edittype:"textarea", editoptions:{rows:"2",cols:"10"}},
                        {name:'content',index:'content', width:herve_grid.theme.note_width,formoptions:{label: 'Content'},align:"center", sortable:true,editable: true,editrules: { required: true } ,edittype:"textarea", editoptions:{rows:"10",cols:"20"}},
                        {name:'active',index:'active', width:herve_grid.theme.note_width,formoptions:{label: 'Active'},align:"center", sortable:true,editable: true,editrules: { required: true } ,edittype:"select",editoptions:{value:"1:True;0:False"}}
                    ],
                    rowNum:30,
                    rowList:[30,60,90,120,150,180,210,240,270,300],
                    pager: herve_grid.jqgrid.article.page,
                    sortname: 'name',
                    viewrecords: true,
                    sortorder: "asc",
                    multiselect: false,
                    subGrid: false,
                    caption: "Article",
                    editurl:$.url+"/admin/controller/articleController.php?ref=operation",
                    grouping: false,
                    groupingView : {
                        groupField : ['question_text'],
                        groupColumnShow : [true],
                        groupText : ['<b>{0}</b>'],
                        groupCollapse : false,
                        groupOrder: ['desc'],
                        groupSummary : [true],
                        showSummaryOnHide: true,
                        groupDataSorted : true
                    },
                    footerrow: true,
                    userDataOnFooter: true
                });
            }
        },
        crud : function($id,$type){
            $($id).jqGrid('navGrid',$type,
            {add:true, view:true, del:true,edit:true},
            {top:0,closeAfterEdit:true,reloadAfterSubmit:true,closeOnEscape:true,bottominfo:'* Mandatory fields.'}, // edit options
            {top:0,clearAfterAdd:true,reloadAfterSubmit:true,closeOnEscape:true,bottominfo:'* Mandatory fields.'}, // add options
            {top:0,reloadAfterSubmit:true,closeOnEscape:true}, // del options
            {}, // search options
            {closeOnEscape:true}
            );
        },
        selectChange : {
            selectcontainer : "",
            selectArray : "",
            selectDiv : "",
            grid : "",
            setup : function(){
                $(this.selectcontainer).html('Group By: <select id="'+this.selectDiv.replace('#','')+'"></select>');
                for (key in this.selectArray) {
                    $(this.selectDiv).append('<option value="' + key + '">' + this.selectArray[key] + '</option>');
                }
                $(this.selectDiv).append('<option value="clear">Remove Grouping</option>');
                this.clickBinding();
            },
            clickBinding : function(){
                $(herve_grid.jqgrid.selectChange.selectDiv).change(function(){
                    var vl = $(this).val();
                    if(vl) {
                        if(vl == "clear") {
                            $(herve_grid.jqgrid.selectChange.grid).jqGrid('groupingRemove',true);
                        } else {
                            $(herve_grid.jqgrid.selectChange.grid).jqGrid('groupingGroupBy',vl);
                        }
                    }
                });
            
            }
        }
    },
    selectDate : {
        dateFormat : 'yy-mm-dd',
        setup : function() {
            $( ".datePicker" ).datepicker({
                changeMonth: true,
                changeYear: true,
                yearRange: '1900:2011',
                showAnim: 'bounce',
                dateFormat:this.dateFormat
            });
        }
    }
};
/// CUSTORM JQUERY FUNCTIONS
$.fn.beautifyform = function(){
     $(this).children("input:submit").button();
};
/// CUSTORM JQUERY FUNCTIONS
$(herve_grid.ready);
// ]]>