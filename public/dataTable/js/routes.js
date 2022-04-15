$(document).ready(function(){

    ConfigJs.fetch_id='routes_data';
    ConfigJs.pre_url='route/';
    init_data();
    btn_edit();

    $(document).on('blur', '.update', function(){
        const id = $(this).data("id");
        const column_name = $(this).data("column");
        const value = $(this).text();
        update_data(id, column_name, value);
    });


    $(document).on('change', '.agencyId', function(){
        const id = $(this).data("id");
        const column_name ="agency_id";
        const value = $("option:selected",this).text();
        update_data(id, column_name, value);
    });
    $(document).on('change', '.routeType', function(){
        const id = $(this).data("id");
        const column_name ="route_type";
        const value = $("option:selected",this).text();
        update_data(id, column_name, value);
    });

    $('#add').click(function(){
        let t= $(this).data('t');
        let g= $(this).data('g');
        let m= $(this).data('m');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.get( '/agencies/?t='+t+'&g='+g+'&_='+m, function( data ) {

            console.log(data);
            let typesField=` <td >
                                 <select id="rtype_data6" name="" class="" data-id="" >
                                      <option value="0"> 0</option>
                                      <option value="1"> 1</option>
                                      <option value="2"> 2</option>
                                      <option value="3"> 3</option>
                                      <option value="4"> 4</option>
                                      <option value="5"> 5</option>
                                      <option value="6"> 6</option>
                                      <option value="7"> 7</option>
                                 </select>
                                    </td>`;
            let agencyField;
            let options='';

            data.forEach(function(element) {
                options+=`<option value="`+element.agency_id+`"> `+element.agency_id+`</option>`;

            });

            agencyField = ` <td >
                                 <select id="raid_data2" name="" class="" data-id="" >`+
                options
                +`</select>
                                    </td>`;

            let html = '<tr>';


            html += '<td contenteditable id="data1"></td>';
            html +=agencyField;
            html += '<td contenteditable id="data3"></td>';
            html += '<td contenteditable id="data4"></td>';
            html += '<td contenteditable id="data5"></td>';
            html +=typesField;
            html += '<td contenteditable id="data7"></td>';
            html += '<td contenteditable id="data8"></td>';
            html += '<td contenteditable id="data9"></td>';
            html += '<td contenteditable id="dat10"></td>';
            html += '<td><button type="button" name="insert" id="insert" class="btn btn-success btn-xs" data-resource="/route/">Insert</button></td>';
            html += '</tr>';

            $('#routes_data tbody').prepend(html);
        });



    });
    $(document).on('click', '#insert', function(){
        const route_id = $('#data1').text();
        const agency_id = $('#raid_data2').find(":selected").text();
        const route_short_name = $('#data3').text();
        const route_long_name = $('#data4').text();
        const route_desc = $('#data5').text();
        const route_type = $('#rtype_data6').find(":selected").text();
        const route_url = $('#data7').text();
        const route_color = $('#data8').text();
        const route_text_color = $('#data9').text();
        const route_sort_order = $('#data10').text();
        const gtfs = $('#gtfs').text();

        const resource = $(this).data("resource");
        console.log(resource);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $.ajax({
            url:"/route/store",
            method:"POST",
            data:{
                route_id:route_id,
                agency_id:agency_id,
                route_short_name:route_short_name,
                route_long_name:route_long_name,
                route_desc:route_desc,
                route_type:route_type,
                route_url:route_url,
                route_color:route_color,
                route_text_color:route_text_color,
                route_sort_order:route_sort_order,
                gtfs:gtfs
            },
            success:function(data)
            {
                messageFlash(data,'success');
                $('#routes_data').DataTable().destroy();
                fetch_data();
                setInterval(function(){
                    location.reload();
                }, 2000);
            },
            error:function (data) {
                const errors = $.parseJSON(data.responseText);
                let message='';
                $.each(errors.errors, function (key, value) {
                    message+=value+'<br>';
                });
                messageFlash(message,'error');
            }
        });

    });

    delete_row()
});
