(function ($) {

    $.fn.createInput = function (option = {}) {
        let settings = $.extend(
            {
                type: 'text',
                value: '',
                placeholder: 'Write something!',
                required: false,
                class:"form-control"
            },
            option
        );

        let res = Object.keys(settings).reduce(function (previous, key) {
            previous += ' ' + [key] + '= \"' + settings[key] + "\"";
            return previous;
        }, '');

        return this.append('<input ' + res + ' />');
    };

    $.fn.buildForm = function (form_data = [],type="inputs") {
        let res = "";
        if(form_data.groups){
            $(form_data.groups).each(function (i,group){
                let inputs="";
                if (form_data[type]) {
                    $(form_data[type]).each(function (i, input) {
                        if(input.group===group.name){
                            inputs+=generate_form_inputs(input);
                        }
                    })
                }
                res+=generate_group(group,inputs);
            })
        }
        else{
            $(form_data[type]).each(function (i, input) {
                res+=generate_form_inputs(input);
            })
        }

        return this.prepend(res);
    };

    function generate_form_inputs(input){
        let inputs=""
        switch (input.attrs.type){
            case "select": inputs +=generate_select(input);break;
            case "text": case "password": case "date" :case "datetime-local" :case "file": case "number" : inputs +=generate_text_input(input);break;
            case "table": inputs +=generate_table(input);break;
            case "multiple_input": inputs +=generate_multi_input(input); break;
            case "multi_row_table": inputs +=generate_multi_row_table(input); break;
            case "custom": inputs=(input.attrs.html)??"&nbsp;";break;
            case "checkbox": inputs +=generate_checkbox(input);break;
            case "radio": inputs +=generate_radio_group(input);break;
            case "textarea": inputs +=generate_textarea(input);break;
        }
        return inputs;
    }

    function generate_textarea(input){
        const input_size=input.col_size?input.col_size:12;
        const title=input.title?input.title:"No title";
        const input_title=input.attrs.required?'<span>'+title+'<span class="text-danger">  * </span></span>':'<span>'+title+'</span>';
        const small_message=input.message?'<small class="form-text text-muted">'+input.message+'</small>':"";
        const text=input.attrs.text? input.attrs.text :"";

        return '<div class="modal-body col-'+input_size+'">\n' +
            input_title+
            '       <textarea ' + generate_attrs(input.attrs) + ' />'+text+'</textarea>\n' +
            small_message+
            '   </div>';
    }
    function generate_checkbox(input){
        const input_size=input.col_size?input.col_size:12;
        const title=input.title?input.title:"No title";
        const input_title=input.attrs.required?'<span>'+title+'<span class="text-danger">  * </span></span>':'<span>'+title+'</span>';

        return '<div class="modal-body col-'+input_size+' form-check">\n' +
            '    <input ' + generate_attrs(input.attrs) + '>\n' +
            '    <label class="form-check-label" for="exampleCheck1">'+input_title+'</label>\n' +
            '  </div>';
    }
    function generate_radio_group(input){
        let res="";
        $(input.options).each(function (i,val){
            res+='<div class="form-check form-check-inline">\n' +
                '  <input ' + generate_attrs(input.attrs)+" value="+val.value + '>\n' +
                '  <label class="form-check-label" for="inlineRadio1">'+val.title+'</label>\n' +
                '</div>';
        })
        return '<div class="modal-body col-4"> ' +
            '<span>Gender<span class="text-danger">  * </span></span>'+
            '<div class="d-flex">'+
                res+
            '</div>'+
            '</div>';
    }
    function generate_text_input(input){
        const input_size=input.col_size?input.col_size:12;
        const title=input.title?input.title:"No title";
        const input_title=input.attrs.required?'<span>'+title+'<span class="text-danger">  * </span></span>':'<span>'+title+'</span>';
        const small_message=input.message?'<small class="form-text text-muted">'+input.message+'</small>':"";

        return '<div class="modal-body col-'+input_size+'">\n' +
            input_title+
            '       <input ' + generate_attrs(input.attrs) + ' />\n' +
            small_message+
            '   </div>';
    }
    function generate_select(input){
        const input_size=input.col_size?input.col_size:12;
        const title=input.title?input.title:"No title";
        const input_title=input.attrs.required?'<span>'+title+'<span class="text-danger">  * </span></span>':'<span>'+title+'</span>';
        const small_message=input.message?'<small class="form-text text-muted">'+input.message+'</small>':"";

        let opt = "<option > </option>"
        if (input.options) {
            if(!input.optionValue){
                console.log("optionValue missing!")
            }else if(!input.optionLabel){
                console.log("optionLabel missing!")
            }else{
                $(input.options).each(function (i, option) {
                    opt += "<option value=" + option[input.optionValue] + ">" + option[input.optionLabel] + "</option>";
                })
            }
        }

        return '<div class="modal-body col-'+input_size+'">\n' +
                    input_title+
            '       <select ' + generate_attrs(input.attrs) + ' />\n' +
                        opt+
            '       </select>' +
                    small_message+
            '   </div>';
    }
    function generate_table(input){

    }
    function generate_multi_input(input){
        return '<div class="col-'+input.col_size+' multi_value_element" id="multi_record_container">\n' +
                ' <div class="">\n' +
                '    <div class="card mb-1">\n' +
                '       <div class="card-body" id="multiple_input_container">\n' +
                '          <i class="fas fa-plus-circle fa-3x m-auto pt-2 korek-color-blue clickable" style="font-size: 1.5rem" onclick="add_multi_records_input(this)"></i>\n' +
                '          <div class="multi-records-input">\n' +
                '             <div class="m-2 m-3">\n' +
                '                <input ' + generate_attrs(input.attrs) + '">\n' +
                '             </div>\n' +
                '             <i class="fas fa-times-circle fa-2x m-auto text-danger clickable" style="position: relative;top: -62px;right: 10px; float:right;" onclick="remove_multi_records_input(this);"></i>\n' +
                '          </div>\n' +
                '       </div>\n' +
                '    </div>\n' +
                '  </div>\n' +
                '</div>';
    }
    function generate_group(group,inputs){
        const right_title=group.right_title?group.right_title:"";
        return '<div class="card col-'+group.size+' mt-1">\n' +
            '   <div class="card-body ">\n' +
            '      <div class="row no-gutters align-items-center justify-content-between">\n' +
            '         <div class="h6 font-weight-bold text-info text-uppercase mb-1">\n' +
                        group.title+
            '         </div>\n' +
            '         <div class="h6 font-weight-bold text-dark float-right mb-1">\n' +
                        right_title+
            '         </div>\n' +
            '      </div>\n' +
            '      <div class="row">\n' +
                    inputs +
            '      </div>\n' +
            '   </div>\n' +
            '</div>';
    }
    function generate_attrs(input){
        const multiple=input.multiple?" multiple ":"";
        const required=input.required?" required ":"";
        const readonly=input.readonly?" readonly ":"";

        const res=multiple+" " +readonly+" "+required;

        let input_attrs = Object.keys(input).reduce(function (previous, key) {
            if(key!=='multiple' && key!=='required' && key!=='readonly') {
                previous += ' ' + key + '= \"' + input[key] + "\"";
            }
            return previous;

        }, '');

        return input_attrs+res;
    }

    function generate_multi_row_table(input){
        let head="";
        const index=uuidv4();
        let body="<tr index='"+index+"'>";
        add_modal(index)
        let element="";
        let input_type="";
        $(input.columns).each(function (i, v) {
            let required="";
            if(v.attrs.required){
                required='<span class="text-danger">  * </span>'
            }
            head +='<th width="'+v.width+'%"><span>'+v.title+required+'</span></th>';
        })
        head +='<th width="3%"></th>';

        $(input.columns).each(function (i, v) {
                switch (v.attrs.type){
                    case "select": input_type="select";break;
                    case "text": case "password": case "date" :case "datetime-local" :case "file": case "number": case "hidden" : input_type="input";break;
                    case "textarea": input_type="textarea";break;
                    case "custom":input_type="a";break;
                }
            element=$(generate_form_inputs(v)).find(input_type)[0];
            body+='<td>'+element.outerHTML+'</td>';
        })

        body +='<td style="color: #ff0000"><i class="fas fa-backspace clickable" onclick="remove_multi_row_input(this);"></i></td></tr>';

    let table='<div class="col-'+input.col_size+' my-1 table_element" data-key="family">\n' +
            '<div class="">\n' +
            '<div class="card-header bg-dark clickable add-table-row-btn" id="family" onclick="add_multi_row_input(this)" style="width: fit-content; border-top: 1px solid #d5d9e0; border-right: 1px solid #d5d9e0;">\n' +
            '    <span class="icon text-white">\n' +
            '            <i class="fas fa-2x fa-plus"></i>\n' +
            '    </span>\n' +
            '</div>\n' +
            '<div class="table-responsive">\n' +
            '  <div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4">\n' +
            '    <div class="row">\n' +
            '        <div class="col-sm-12">\n' +
            '            <table class="table table-bordered dataTable" id="'+input.attrs.id+'_container" width="100%" cellspacing="0" role="grid" aria-describedby="dataTable_info" style="width: 100%;">\n' +
            '                <thead>\n' +
            '                   <tr style="text-align: center" class="dt-body-nowrap" id="table_head">';
                                    table+=head;
        table+='                </tr>                ' +
            '                </thead>\n' +
            '                <tbody>\n' +
            '                   ';
                                    table+=body;
        table+='                ' +
            '                </tbody>\n' +
            '            </table>\n' +
            '        </div>\n' +
            '    </div>\n' +
            '</div>\n' +
            '</div>\n' +
            '</div></div>';

        $('#table_head').append(head)

        return table;
    }

}(jQuery));

function remove_multi_records_input(remove_btn_ref){
    const input_container = $(remove_btn_ref).parent();
    if(input_container.parent().find('input').length>1){
        input_container.remove();
    }else{
        input_container.parent().find('input').val('');
    }
}
function add_multi_records_input(){
    const clone = $($('#multiple_input_container').find('.multi-records-input')[0]).clone();
    clone.find('input').val('');
    $('#multiple_input_container').append(clone);
}

function remove_multi_row_input(remove_btn_ref){
    const index=$(remove_btn_ref).parent().parent().attr('index');
    const input_container = $(remove_btn_ref).parent();
    if(input_container.parent().parent().find('tr').length>1){
        input_container.parent().remove();
        remove_modal(index)
    }else{
        input_container.parent().find('input').val('');
    }
    // remove_modal(index)
}

function add_multi_row_input(add_btn_ref){
    const form_container=$(add_btn_ref).parent();
    if(form_container.find('tbody tr').length>0) {
        const clone = $(form_container.find('tbody tr')[0]).clone();
        form_container.find('tbody').append(clone);
        const index=uuidv4();
        form_container.find('tbody tr:last-child').attr('index',index);
        add_modal(index)
    }
}

function populateDataToForm(form_id, formData) {
    let available_form_data = getFormData(form_id)
    $.each(formData, function (key, value) {
        if (key in available_form_data) {
            let element_type = getElementType(key, form_id)['element_type']
            let element_input_type = getElementType(key, form_id)['element_input_type']
            if (element_type) {
                {
                    if (isDate(value)) {
                        value = getFormattedDate(value,element_input_type);
                    }
                    let ele_id = '#' + form_id + ' ' + element_type.toLowerCase() + '[name=' + key + ']'
                    $(ele_id).val(value)
                }
            }
        }
    });
    $('.selectpicker').selectpicker('refresh')
}
function getFormattedDate(date,input_type) {
    if(input_type==="datetime-local"){
        const event = new Date(date);
        let datee="";
        event.setHours( event.getHours() + 3 );
        datee=event.toISOString();
        return datee.substring(0, 19);
    }

    if(input_type==="date"){
        return formatDate(date.substring(0, 10).replace(/-/g, '/'))
    }
}
function getElementType(id, form_id) {
    const selector=$('#' + form_id + ' #' + id);
    return {
        "element_type":selector.prop('tagName'),
        "element_input_type":selector.prop('type')
    };
}
function getFormData(stringFormId) {
    return Object.fromEntries(new FormData($('form#' + stringFormId)[0]).entries())
}
function formatDate(date) {
    var d = new Date(date),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();

    if (month.length < 2)
        month = '0' + month;
    if (day.length < 2)
        day = '0' + day;

    return [year, month, day].join('-');
}
function isDate(s) {
    return isNaN(s) && !isNaN(Date.parse(s));
}

function uuidv4() {
    return ([1e7]+-1e3+-4e3+-8e3+-1e11).replace(/[018]/g, c =>
        (c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4).toString(16)
    );
}

//custom
function add_modal(index){
    $('#modal_cont').append('<div class="modal fade" id="'+index+'_modal" tabindex="-1" data-keyboard="false" data-backdrop="static" aria-hidden="true" style="display: none;">\n' +
        '    <div class="modal-dialog" role="document">\n' +
        '        <div class="modal-content">\n' +
        '            <div class="modal-header korek_blue text-white">\n' +
        '                <h6 class="m-0 font-weight-bold text-white" id="settings_modal_title">Attachments</h6>\n' +
        '                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">\n' +
        '                    <span aria-hidden="true">×</span>\n' +
        '                </button>\n' +
        '            </div>\n' +
        '                <div id="'+index+'_input_container" index="'+index+'" class="row pl-3 pr-3 attachments_input_container">\n' +
        '\n' +
        '                </div>\n' +
        '        </div>\n' +
        '    </div>\n' +
        '</div>')
}

function remove_modal(index){
    $('#'+index+"_modal").remove()
}

