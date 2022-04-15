$(document).ready(function(){
    ConfigJs.fetch_id='stops_data';
    ConfigJs.pre_url='stop/';
    init_data();
    btn_edit();


    $(document).on('blur', '.update', function(){
        const id = $(this).data("id");
        const column_name = $(this).data("column");
        const value = $(this).text();
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

        $.get( '/level/?t='+t+'&g='+g+'&_='+m+'&a=true', function( data ) {


            let levelField;
            let options='';

            data.forEach(function(element) {
                options+=`<option value="`+element.level_id+`"> `+element.level_id+`</option>`;

            });

            levelField = ` <td >
                                 <select id="levelid_data2" name="" class="" data-id="" >`+
                options
                +`</select>
                                    </td>`;

            let html = '<tr>';

            html += '<td contenteditable id="data1"></td>';
            html += '<td contenteditable id="data2"></td>';
            html += '<td contenteditable id="data3"></td>';
            html += '<td contenteditable id="data4"></td>';
            html += '<td contenteditable id="data5"></td>';
            html += '<td contenteditable id="data6"></td>';
            html += '<td contenteditable id="data7"></td>';
            html += '<td contenteditable id="data8"></td>';
            html += '<td contenteditable id="data9"></td>';
            html += '<td contenteditable id="data10"></td>';
            html += '<td contenteditable id="data11"></td>';
            html += '<td contenteditable id="data12"></td>';
            html +=levelField;
            html += '<td contenteditable id="data14"></td>';
            html += '<td><button type="button" name="insert" id="insert" class="btn btn-success btn-xs" data-resource="/stop/">Insert</button></td>';
            html += '</tr>';

            $('#stops_data tbody').prepend(html);
        });




    });
    $(document).on('click', '#insert', function(){
        const stop_id = $('#data1').text();
        const stop_code = $('#data2').text();
        const stop_name = $('#data3').text();
        const stop_desc = $('#data4').text();
        const stop_lat = $('#data5').text();
        const stop_lon = $('#data6').text();
        const zone_id = $('#data7').text();
        const stop_url = $('#data8').text();
        const location_type = $('#data9').text();
        const parent_station = $('#data10').text();
        const stop_timezone = $('#data11').text();
        const wheelchair_boarding = $('#data12').text();
        const level_id = $('#levelid_data2').find(":selected").text();
        const platform_code = $('#data14').text();
        const gtfs = $('#gtfs').text();

            const resource = $(this).data("resource");
            console.log(resource);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url:"/stop/store",
                method:"POST",
                data:{
                    stop_id:stop_id,
                    stop_name:stop_name,
                    zone_id:zone_id,
                    stop_code:stop_code,
                    stop_desc:stop_desc,
                    stop_lat:stop_lat,
                    stop_lon:stop_lon,
                    stop_url:stop_url,
                    location_type:location_type,
                    parent_station:parent_station,
                    stop_timezone:stop_timezone,
                    wheelchair_boarding:wheelchair_boarding,
                    level_id:level_id,
                    platform_code:platform_code,
                    gtfs:gtfs
                },
                success:function(data)
                {
                    messageFlash(data,'success');
                    $('#stops_data').DataTable().destroy();
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
