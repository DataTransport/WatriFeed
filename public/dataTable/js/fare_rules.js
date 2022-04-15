$(document).ready(function(){

    ConfigJs.fetch_id='fare_rules_data';
    ConfigJs.pre_url='farerule/';
    init_data();
    btn_edit();

    $(document).on('blur', '.update9', function(){
        const id = $(this).data("id");
        const column_name = $(this).data("column");
        const value = $(this).text();
        update_data(id, column_name, value);
    });
    $(document).on('change', '.fareId', function(){
        const id = $(this).data("id");
        const column_name ="fare_id";
        const value = $("option:selected",this).text();
        update_data(id, column_name, value);
    });
    $(document).on('change', '.routeId', function(){
        const id = $(this).data("id");
        const column_name ="route_id";
        const value = $("option:selected",this).text();
        update_data(id, column_name, value);
    });

    $(document).on('change', '.originId', function(){
        const id = $(this).data("id");
        const column_name ="origin_id";
        const value = $("option:selected",this).text();
        update_data(id, column_name, value);
    });
    $(document).on('change', '.destinationId', function(){
        const id = $(this).data("id");
        const column_name ="destination_id";
        const value = $("option:selected",this).text();
        update_data(id, column_name, value);
    });

    $(document).on('change', '.containsId', function(){
        const id = $(this).data("id");
        const column_name ="contains_id";
        const value = $("option:selected",this).text();
        update_data(id, column_name, value);
    });

    $('#add').click(function(){

        let t= $(this).data('t');
        let g= $(this).data('g');
        let m= $(this).data('m');

        const gtfs = $('#gtfs').text();
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.get( '/fareruleadd/?t='+t+'&g='+g+'&_='+m+'&a=true', function( data ) {


            let fareattributeFields = data[0];
            let routeFields = data[1];
            let zoneFields = data[2];
            let options1='';
            let options2='';
            let options3='';

            fareattributeFields.forEach(function(element) {
                    options1+=`<option value="`+element.fare_id+`"> `+element.fare_id+`</option>`;

            });

            routeFields.forEach(function(element) {
                    options2+=`<option value="`+element.route_id+`"> `+element.route_id+`</option>`;
            });
            zoneFields.forEach(function(element) {
                    options3+=`<option value="`+element.zone_id+`"> `+element.zone_id+`</option>`;
            });






            let html = '<tr>';
            html +=` <td><select id="data1" name="" class="" data-id="" >`+options1+`</select></td>`;
            html +=` <td><select id="data2" name="" class="" data-id="" >`+options2+`</select></td>`;
            html +=` <td><select id="data3" name="" class="" data-id="" >`+options3+`</select></td>`;
            html +=` <td><select id="data4" name="" class="" data-id="" >`+options3+`</select></td>`;
            html +=` <td><select id="data5" name="" class="" data-id="" >`+options3+`</select></td>`;
            // html += '<td contenteditable id="data2"></td>';
            // html += '<td contenteditable id="data3"></td>';
            // html += '<td contenteditable id="data4"></td>';
            // html += '<td contenteditable id="data5"></td>';
            html += '<td><button type="button" name="insert" id="insert" class="btn btn-success btn-xs" data-resource="/farerule/">Insert</button></td>';
            html += '</tr>';

            $('#fare_rules_data tbody').prepend(html);
        });






    });
    $(document).on('click', '#insert', function(){
        // const fare_id = $('#data1').text();
        const fare_id = $('#data1').find(":selected").text();
        const route_id = $('#data2').find(":selected").text();
        const origin_id = $('#data3').find(":selected").text();
        const destination_id = $('#data4').find(":selected").text();
        const contains_id = $('#data5').find(":selected").text();
        const gtfs = $('#gtfs').text();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url:"/farerule/store",
                method:"POST",
                data:{
                    fare_id:fare_id,
                    route_id:route_id,
                    origin_id:origin_id,
                    destination_id:destination_id,
                    contains_id:contains_id,
                    gtfs:gtfs
                },
                success:function(data)
                {
                    messageFlash(data,'success');
                    $('#fare_rules_data').DataTable().destroy();
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
