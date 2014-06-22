// <![CDATA[
var herve_grid = {
    ready: function() {
        herve_grid.theme.init();
        herve_grid.menu.resize();
        herve_grid.jqgrid.init()
        herve_grid.menu.init();
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
                        if($(st).html() != null ){
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
                    rowNum:30,
                    rowList:[30,60,90,120,150,180,210,240,270,300],
                    pager: herve_grid.jqgrid.category.page,
                    sortname: 'category_order',
                    viewrecords: true,
                    sortorder: "asc",
                    multiselect: false,
                    subGrid: false,
                    caption: "Category Operations",
                    editurl:$.url+"/admin/controller/categoryController.php?ref=operation",
                    grouping: true,
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
                    colNames:['Id', 'Section Name', 'Section Title', 'Section Value'],
                    colModel:[
                        {name:'section_id',index:'section_id',hidden:true,align:'center',editable:false, sorttype:'int',key:true},
                        {name:'section_name',index:'section_name', width:herve_grid.theme.note_width,formoptions:{label: 'Section Name'},align:"center", sortable:true,editable: true,editrules: { required: true } ,edittype:"textarea", editoptions:{rows:"2",cols:"20"}},
                        {name:'section_title',index:'section_title', width:herve_grid.theme.note_width,formoptions:{label: 'Section Title'},align:"center", sortable:true,editable: true,editrules: { required: true } ,edittype:"textarea", editoptions:{rows:"2",cols:"20"}},
                        {name:'section_value',index:'section_value', width:herve_grid.theme.note_width,formoptions:{label: 'Section Value'},align:"center", sortable:true,editable: true,editrules: { required: true } ,edittype:"textarea", editoptions:{rows:"2",cols:"20"}}
                    ],
                    rowNum:30,
                    rowList:[30,60,90,120,150,180,210,240,270,300],
                    pager: herve_grid.jqgrid.section.page,
                    sortname: 'section_name',
                    viewrecords: true,
                    sortorder: "asc",
                    multiselect: false,
                    subGrid: false,
                    caption: "Section Operations",
                    editurl:$.url+"/admin/controller/sectionController.php?ref=operation",
                    grouping: false,
                    groupingView : {
                        groupField : ['section_name'],
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
                this.categories=$.ajax({url: $.url+"/admin/controller/categoryController.php?ref=select&type=category", dataType: "json", cache: true, async: false, success: function(data, result) {if (!result) alert('Failure to retrieve the Answers.');}}).responseText;
                this.sections=$.ajax({url: $.url+"/admin/controller/sectionController.php?ref=select&type=section", dataType: "json", cache: true, async: false, success: function(data, result) {if (!result) alert('Failure to retrieve the Questions.');}}).responseText;
                $(herve_grid.jqgrid.categorySection.id).jqGrid({
                    url:$.url+'/admin/controller/categorySectionController.php?ref=details',
                    datatype: "json",
                    height: 'auto',
                    width: herve_grid.theme.width,
                    colNames:['Id', 'Category Name', 'Section Name', 'Order', 'Active'],
                    colModel:[
                        {name:'id',index:'id',hidden:true,align:'center',editable:false, sorttype:'int',key:true},
                        {name:'category_name',index:'category_name', width:herve_grid.theme.note_width,formoptions:{label: 'Category'},align:"center", sortable:true,editable: true,editrules: { required: true } ,edittype:"select",editoptions: { size: 50,value: (this.categories.replace('"','')).replace('"','') } },
                        {name:'section_name',index:'section_name', width:herve_grid.theme.note_width,formoptions:{label: 'Section'},align:"center", sortable:true,editable: true,editrules: { required: true } ,edittype:"select",editoptions: { size: 50,value: (this.sections.replace('"','')).replace('"','') } },
                        {name:'categorysection_order',index:'categorysection_order', width:herve_grid.theme.note_width,formoptions:{label: 'Order'},align:"center", sortable:true,editable: true,editrules: { required: true } ,edittype:"textarea", editoptions:{rows:"2",cols:"20"}},
                        {name:'active',index:'active', width:herve_grid.theme.note_width,formoptions:{label: 'Active'},align:"center", sortable:true,editable: true,editrules: { required: true } ,edittype:"select",editoptions:{value:"1:True;0:False"}}
                    ],
                    rowNum:30,
                    rowList:[30,60,90,120,150,180,210,240,270,300],
                    pager: herve_grid.jqgrid.categorySection.page,
                    sortname: 'categorysection_order',
                    viewrecords: true,
                    sortorder: "asc",
                    multiselect: false,
                    subGrid: false,
                    caption: "Category Section Mapping",
                    editurl:$.url+"/admin/controller/categorySectionController.php?ref=operation",
                    grouping: true,
                    groupingView : {
                        groupField : ['category_name'],
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
        question: {
            id      :   "#question",
            page    :   "#p_question",
            select  :   "#question_select",
            init    :   function() {
                this.setup();
                herve_grid.jqgrid.crud(herve_grid.jqgrid.question.id,herve_grid.jqgrid.question.page);
            },
            setup   :   function() {
                this.question_type=$.ajax({url: $.url+"/admin/controller/questionController.php?ref=selectType&type=question_type", dataType: "json", cache: true, async: false, success: function(data, result) {if (!result) alert('Failure to retrieve the Products.');}}).responseText;
                $(herve_grid.jqgrid.question.id).jqGrid({
                    url:$.url+'/admin/controller/questionController.php?ref=details',
                    datatype: "json",
                    height: 'auto',
                    width: herve_grid.theme.width,
                    colNames:['Id', 'Question', 'Question Type', 'Question Validation'],
                    colModel:[
                        {name:'question_id',index:'question_id',hidden:true,align:'center',editable:false, sorttype:'int',key:true},
                        {name:'question_text',index:'question_text', width:herve_grid.theme.note_width,formoptions:{label: 'Question Text'},align:"center", sortable:true,editable: true,editrules: { required: true } ,edittype:"textarea", editoptions:{rows:"2",cols:"20"}},
                        {name:'question_type',index:'question_type', width:herve_grid.theme.note_width,formoptions:{label: 'Question Type'},align:"center", sortable:true,editable: true,editrules: { required: true } ,edittype:"select",editoptions: { size: 50,value: (this.question_type.replace('"','')).replace('"','') } },
                        {name:'question_validation',index:'question_validation', width:herve_grid.theme.note_width,formoptions:{label: 'Question Validation'},align:"center", sortable:true,editable: true,editrules: { required: true } ,edittype:"textarea", editoptions:{rows:"2",cols:"20"}}
                    ],
                    rowNum:30,
                    rowList:[30,60,90,120,150,180,210,240,270,300],
                    pager: herve_grid.jqgrid.question.page,
                    sortname: 'question_id',
                    viewrecords: true,
                    sortorder: "asc",
                    multiselect: false,
                    subGrid: false,
                    caption: "Question Operations",
                    editurl:$.url+"/admin/controller/questionController.php?ref=operation",
                    grouping: false,
                    groupingView : {
                        groupField : ['question_date'],
                        groupColumnShow : [true],
                        groupText : ['<b>{0}</b>'],
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
        sectionQuestion: {
            id      :   "#sectionQuestion",
            page    :   "#p_sectionQuestion",
            select  :   "#sectionQuestion_select",
            init    :   function() {
                this.setup();
                herve_grid.jqgrid.crud(herve_grid.jqgrid.sectionQuestion.id,herve_grid.jqgrid.sectionQuestion.page);
            },
            setup   :   function() {
                this.sections=$.ajax({url: $.url+"/admin/controller/sectionController.php?ref=select&type=section", dataType: "json", cache: true, async: false, success: function(data, result) {if (!result) alert('Failure to retrieve the Answers.');}}).responseText;
                this.questions=$.ajax({url: $.url+"/admin/controller/questionController.php?ref=select&type=question", dataType: "json", cache: true, async: false, success: function(data, result) {if (!result) alert('Failure to retrieve the Questions.');}}).responseText;
                this.categories=$.ajax({url: $.url+"/admin/controller/categoryController.php?ref=select&type=category", dataType: "json", cache: true, async: false, success: function(data, result) {if (!result) alert('Failure to retrieve the Answers.');}}).responseText;
                $(herve_grid.jqgrid.sectionQuestion.id).jqGrid({
                    url:$.url+'/admin/controller/sectionQuestionController.php?ref=details',
                    datatype: "json",
                    height: 'auto',
                    width: herve_grid.theme.width,
                    colNames:['Id','Section','Category','Question','Order'],
                    colModel:[
                        {name:'id',index:'id',hidden:true,align:'center',editable:false, sorttype:'int',key:true},
                        {name:'section_name',index:'section_name', width:herve_grid.theme.note_width,formoptions:{label: 'Section'},align:"center", sortable:true,editable: true,editrules: { required: true } ,edittype:"select",editoptions: { size: 50,value: (this.sections.replace('"','')).replace('"','') } },
                        {name:'category_name',index:'category_name', width:herve_grid.theme.note_width,formoptions:{label: 'Category'},align:"center", sortable:true,editable: true,editrules: { required: true } ,edittype:"select",editoptions: { size: 50,value: (this.categories.replace('"','')).replace('"','') } },
                        {name:'question_text',index:'question_text', width:herve_grid.theme.note_width,formoptions:{label: 'Question'},align:"center", sortable:true,editable: true,editrules: { required: true } ,edittype:"select",editoptions: { size: 50,value: (this.questions.replace('"','')).replace('"','') } },
                        {name:'question_order',index:'question_order', width:herve_grid.theme.note_width,formoptions:{label: 'Order'},align:"center", sortable:true,editable: true,editrules: { required: true ,number:true}  }
                    ],
                    rowNum:30,
                    rowList:[30,60,90,120,150,180,210,240,270,300],
                    pager: herve_grid.jqgrid.sectionQuestion.page,
                    sortname: 'question_order',
                    viewrecords: true,
                    sortorder: "asc",
                    multiselect: false,
                    subGrid: false,
                    caption: "Section and Question Mapping",
                    editurl:$.url+"/admin/controller/sectionQuestionController.php?ref=operation",
                    grouping: true,
                    groupingView : {
                        groupField : ['section_name'],
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
        answer: {
            id      :   "#answer",
            page    :   "#p_answer",
            select  :   "#answer_select",
            init    :   function() {
                this.setup();
                herve_grid.jqgrid.crud(herve_grid.jqgrid.answer.id,herve_grid.jqgrid.answer.page);
            },
            setup   :   function() {
                this.questions=$.ajax({url: $.url+"/admin/controller/questionController.php?ref=select&type=question", dataType: "json", cache: true, async: false, success: function(data, result) {if (!result) alert('Failure to retrieve the Questions.');}}).responseText;
                this.answers=$.ajax({url: $.url+"/admin/controller/answerController.php?ref=select&type=answer", dataType: "json", cache: true, async: false, success: function(data, result) {if (!result) alert('Failure to retrieve the Answers.');}}).responseText;
                $(herve_grid.jqgrid.answer.id).jqGrid({
                    url:$.url+'/admin/controller/answerController.php?ref=details',
                    datatype: "json",
                    height: 'auto',
                    width: herve_grid.theme.width,
                    colNames:['Id', 'Answer'],
                    colModel:[
                        {name:'answer_id',index:'answer_id',hidden:true,align:'center',editable:false, sorttype:'int',key:true},
                        {name:'answer_text',index:'answer_text', width:herve_grid.theme.note_width,formoptions:{label: 'Answer'},align:"center", sortable:true,editable: true,editrules: { required: true } ,edittype:"textarea", editoptions:{rows:"2",cols:"20"}}
                    ],
                    rowNum:30,
                    rowList:[30,60,90,120,150,180,210,240,270,300],
                    pager: herve_grid.jqgrid.answer.page,
                    sortname: 'answer_date',
                    viewrecords: true,
                    sortorder: "asc",
                    multiselect: false,
                    subGrid: false,
                    caption: "Answer Operations",
                    editurl:$.url+"/admin/controller/answerController.php?ref=operation",
                    grouping: false,
                    groupingView : {
                        groupField : ['answer_date'],
                        groupColumnShow : [true],
                        groupText : ['<b>{0}</b>'],
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
        questionAnswer: {
            id      :   "#questionAnswer",
            page    :   "#p_questionAnswer",
            select  :   "#questionAnswer_select",
            init    :   function() {
                this.setup();
                herve_grid.jqgrid.crud(herve_grid.jqgrid.questionAnswer.id,herve_grid.jqgrid.questionAnswer.page);
            },
            setup   :   function() {
                this.questions=$.ajax({url: $.url+"/admin/controller/questionController.php?ref=select&type=question", dataType: "json", cache: true, async: false, success: function(data, result) {if (!result) alert('Failure to retrieve the Questions.');}}).responseText;
                this.answers=$.ajax({url: $.url+"/admin/controller/answerController.php?ref=select&type=answer", dataType: "json", cache: true, async: false, success: function(data, result) {if (!result) alert('Failure to retrieve the Answers.');}}).responseText;
                $(herve_grid.jqgrid.questionAnswer.id).jqGrid({
                    url:$.url+'/admin/controller/questionAnswerController.php?ref=details',
                    datatype: "json",
                    height: 'auto',
                    width: herve_grid.theme.width,
                    colNames:['Id','Question','Answer','Order'],
                    colModel:[
                        {name:'id',index:'id',hidden:true,align:'center',editable:false, sorttype:'int',key:true},
                        {name:'question_text',index:'question_text', width:herve_grid.theme.note_width,formoptions:{label: 'Question'},align:"center", sortable:true,editable: true,editrules: { required: true } ,edittype:"select",editoptions: { size: 50,value: (this.questions.replace('"','')).replace('"','') } },
                        {name:'answer_text',index:'answer_text', width:herve_grid.theme.note_width,formoptions:{label: 'Answer'},align:"center", sortable:true,editable: true,editrules: { required: true } ,edittype:"select",editoptions: { size: 50,value: (this.answers.replace('"','')).replace('"','') } },
                        {name:'answer_order',index:'answer_order', width:herve_grid.theme.note_width,formoptions:{label: 'Order'},align:"center", sortable:true,editable: true,editrules: { required: true ,number:true}  },
                    ],
                    rowNum:30,
                    rowList:[30,60,90,120,150,180,210,240,270,300],
                    pager: herve_grid.jqgrid.questionAnswer.page,
                    sortname: 'answer_order',
                    viewrecords: true,
                    sortorder: "asc",
                    multiselect: false,
                    subGrid: false,
                    caption: "Question and Answer Mapping",
                    editurl:$.url+"/admin/controller/questionAnswerController.php?ref=operation",
                    grouping: true,
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
        selectChange :
        {
            selectcontainer : "",
            selectArray : "",
            selectDiv : "",
            grid : "",
            setup : function()
            {
                $(this.selectcontainer).html('Group By: <select id="'+this.selectDiv.replace('#','')+'"></select>');
                for (key in this.selectArray) {
                    $(this.selectDiv).append('<option value="' + key + '">' + this.selectArray[key] + '</option>');
                }
                $(this.selectDiv).append('<option value="clear">Remove Grouping</option>');
                this.clickBinding();
            },
            clickBinding : function()
            {
                $(herve_grid.jqgrid.selectChange.selectDiv).change(function()
                {
                    var vl = $(this).val();
                    if(vl) 
                    {
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
    selectDate : 
    {
        dateFormat : 'yy-mm-dd',
        setup : function()
        {
            $( ".datePicker" ).datepicker({
                changeMonth: true,
                changeYear: true,
                yearRange: '1900:2011',
                showAnim: 'bounce',
                dateFormat:this.dateFormat
            });
        }
    }
}
/// CUSTORM JQUERY FUNCTIONS
$.fn.beautifyform = function(){
     $(this).children("input:submit").button();
}
/// CUSTORM JQUERY FUNCTIONS
$(herve_grid.ready);
// ]]>