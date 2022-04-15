$(document).ready(function(){

    ConfigJs.fetch_id='trips_data';
    ConfigJs.pre_url='trip/';
    init_data();
    btn_edit();

    $(document).on('blur', '.update', function(){
        const id = $(this).data("id");
        const column_name = $(this).data("column");
        const value = $(this).text();
        update_data(id, column_name, value);
    });
   
    
    $(document).on('change', '.routeId1', function(){
        const id = $(this).data("id");
        const column_name ="route_id";
        const value = $("option:selected",this).text();
        update_data(id, column_name, value);
    });
    $(document).on('change', '.shapeId', function(){
        const id = $(this).data("id");
        const column_name ="shape_id";
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

        $.get( '/routes/?t='+t+'&g='+g+'&_='+m, function( data ) {

            let routeField;
            let options='';

            data.forEach(function(element) {
                options+=`<option value="`+element.route_id+`"> `+element.route_id+`</option>`

                console.log(element);
            });
            routeField = ` <td >
                                 <select id="trid_data3"   >`+
                                      options
                                 +`</select>
                                    </td>`;
            $.get('/shapes/?t='+t+'&g='+g+'&_='+m, function (data) {
                let shapeField;
                let options='';

                data.forEach(function(element) {
                    options+=`<option value="`+element.shape_id+`"> `+element.shape_id+`</option>`

                    // console.log(element);
                });
                shapeField = ` <td >
                                 <select id="strid_data3"   >`+
                    options
                    +`</select>
                                    </td>`;

                let html = '<tr>';
                html += '<td contenteditable id="data1"></td>';
                html += '<td contenteditable id="data2"></td>';
                html +=routeField;
                html += '<td contenteditable id="data4"></td>';
                html += '<td contenteditable id="data5"></td>';
                html += '<td contenteditable id="data6"></td>';
                html += '<td contenteditable id="data7"></td>';
                html += shapeField;
                html += '<td contenteditable id="data9"></td>';
                html += '<td contenteditable id="data10"></td>';
                html += '<td><button type="button" name="insert" id="insert" class="btn btn-success btn-xs" data-resource="/trip/">Insert</button></td>';
                html += '</tr>';

                $('#trips_data tbody').prepend(html);
            });

        });



    });
    $(document).on('click', '#insert', function(){
        const trip_id = $('#data1').text();
        const service_id = $('#data2').text();
        const route_id = $('#trid_data3').find(":selected").text();
        const trip_headsign = $('#data4').text();
        const trip_short_name = $('#data5').text();
        const direction_id = $('#data6').text();
        const block_id = $('#data7').text();
        const shape_id = $('#strid_data3').find(":selected").text();
        const wheelchair_accessible = $('#data9').text();
        const bikes_allowed = $('#data10').text();
        const gtfs = $('#gtfs').text();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url:"/trip/store",
                method:"POST",
                data:{
                    trip_id:trip_id,
                    service_id:service_id,
                    route_id:route_id,
                    trip_headsign:trip_headsign,
                    trip_short_name:trip_short_name,
                    direction_id:direction_id,
                    block_id:block_id,
                    shape_id:shape_id,
                    wheelchair_accessible:wheelchair_accessible,
                    bikes_allowed:bikes_allowed,
                    gtfs:gtfs
                },
                success:function(data)
                {
                    messageFlash(data,'success');
                    $('#trips_data').DataTable().destroy();
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
