function add_filter_html_element(ref, previous_values){
    const select_element = $(ref);
    const selected_values = select_element.val();
    const filters_container_element = $('#'+select_element.attr('data-filters-container'));
    const filters_options_route = select_element.attr('data-lookup-options-route');
    const filters_datatable_id = select_element.attr('data-datatable-id');


    $.each(selected_values,function(index,filter_name){
        const option_element = $(select_element.find("option[value='"+filter_name+"']")[0]);
        const input_type = option_element.attr('data-input-type');
        const display_name = option_element.attr('data-display-name');

        const option_db_name = option_element.attr('data-filter-db-name');
        const option_value_name = option_element.attr('data-filter-value-name');
        const option_value_id = option_element.attr('data-filter-id-name');
        const option_table_name = option_element.attr('data-filter-table-name');

        let filter_element = '';

        if(previous_values.indexOf(filter_name) === -1){
            if(input_type == 'select'){
                filter_element = get_select_filter(filter_name,display_name,option_db_name,select_element.attr('id'),filters_datatable_id);
                filters_container_element.append(filter_element);
                filters_container_element.find('#'+filter_name).selectpicker().on('change',function(){
                    $('#'+filters_datatable_id).DataTable().ajax.reload();
                });
                get_select_filter_options(filters_options_route,filter_name,option_table_name).then( function(options) {
                    let filter_options_html = '';
                    $.each(options, function(o_index,o_value){
                        filter_options_html += '<option value=\''+o_value[option_value_id]+'\'>'+o_value[option_value_name]+'</option>'
                    });
                    filters_container_element.find('#'+filter_name).html(filter_options_html);
                    filters_container_element.find('#'+filter_name).selectpicker('refresh').selectpicker('render');
                });
            }else if(input_type == 'text' || input_type == 'switch_checkbox'){
                filter_element = get_text_filter(filter_name,display_name,option_db_name,select_element.attr('id'),filters_datatable_id);
                filters_container_element.append(filter_element);
                filters_container_element.find('#'+filter_name).on('change',function(){
                    $('#'+filters_datatable_id).DataTable().ajax.reload();
                });
            }
        }
    });
}

function remove_filter(ref){

    const filter_container = $(ref).parent().parent();
    const ref_filters_select_element = $('#'+$(ref).attr('data-ref-filters-select-id'));
    const current_selected_filters = ref_filters_select_element.val();
    const remove_filter_name = $(ref).attr('data-filter-name');
    const datatable_id = $(ref).attr('data-datatable-id');

    const remove_index = current_selected_filters.indexOf(remove_filter_name);
    if (remove_index > -1) {
        current_selected_filters.splice(remove_index, 1);
        ref_filters_select_element.selectpicker('val',current_selected_filters);
    }
    filter_container.remove();
    $('#'+datatable_id).DataTable().ajax.reload();
}

function get_text_filter(filter_name,display_name,option_db_name,ref_filters_select_id, datatable_id){

    return "<div class=\"col-lg-3 col-md-4 mr-2 my-2\"><div class=\"btn btn-info btn-icon-split btn-icon-split-select\">\n" +
        "                        <span class=\"icon filter-select-text-header text-white\">\n" +  display_name+
        "                        </span>\n" +
        "\n" +
        "  <input data-option-db-name=\""+option_db_name+"\" id=\""+filter_name+"\" type=\"text\" class=\"form-control datatable-filter\" placeholder=\"Enter Value\">\n" +

        "<div id=\""+filter_name+"_remover\" class=\"btn btn-danger btn-sm btn-circle filter-select-remover\" data-datatable-id=\""+datatable_id+"\"  data-filter-name=\""+filter_name+"\" data-ref-filters-select-id=\""+ref_filters_select_id+"\" onclick=\"remove_filter(this)\">\n" +
        "                                        <i class=\"far fa-times-circle fa-2x\"></i>\n" +
        "                                    </div>"+
        "</div></div>";
}

function get_select_filter(filter_name,display_name,option_db_name,ref_filters_select_id,datatable_id){

    return "<div class=\"col-lg-3 col-md-4 mr-2 my-2\"><div class=\"btn btn-tertiary btn-icon-split btn-icon-split-select\">\n" +
        "                        <span class=\"icon filter-select-text-header text-white\">\n" +  display_name+
        "                        </span>\n" +
        "\n" +
        "    <select data-option-db-name=\""+option_db_name+"\" multiple data-selected-text-format=\"count\" data-width=\"100%\" id=\""+filter_name+"\" class=\"selectpicker site_filters_select datatable-filter\"  data-live-search=\"true\" multiple>\n" +
        "    </select>\n" +
        "<div id=\""+filter_name+"_remover\" class=\"btn btn-danger btn-sm btn-circle filter-select-remover\" data-datatable-id=\""+datatable_id+"\" data-filter-name=\""+filter_name+"\" data-ref-filters-select-id=\""+ref_filters_select_id+"\" onclick=\"remove_filter(this)\" >\n" +
        "                                        <i class=\"far fa-times-circle fa-2x\"></i>\n" +
        "                                    </div>"+
        "</div></div>";
}

function get_select_filter_options(options_route,filter_name,option_db_name){
    return $.ajax({
        url: options_route,
        type: 'GET',
        data: {lookup:option_db_name},
        beforeSend:function(){
            $($('#'+filter_name).parents('.btn-icon-split-select')[0]).LoadingOverlay('show');
        },
        success: function (options) {
            $($('#'+filter_name).parents('.btn-icon-split-select')[0]).LoadingOverlay('hide');
        },
    });
}
