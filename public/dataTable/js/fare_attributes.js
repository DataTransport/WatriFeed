$(document).ready(function(){

    ConfigJs.fetch_id='fare_attributes_data';
    ConfigJs.pre_url='fareattribute/';
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

    $('#add').click(function(){

        let t= $(this).data('t');
        let g= $(this).data('g');
        let m= $(this).data('m');

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.get( '/agency/?t='+t+'&g='+g+'&_='+m+'&a=true', function( data ) {

            let agencyField;
            let options='';

            data.forEach(function(element) {
                options+=`<option value="`+element.agency_id+`"> `+element.agency_id+`</option>`;

            });

            agencyField = ` <td >
                                 <select id="faaid_data7" name="" class="" data-id="" >`+
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
            html +=agencyField;
            html += '<td><button type="button" name="insert" id="insert" class="btn btn-success btn-xs" data-resource="/fareattribute/">Insert</button></td>';
            html += '</tr>';

            $('#fare_attributes_data tbody').prepend(html);
        });




    });
    $(document).on('click', '#insert', function(){
        const fare_id = $('#data1').text();
        const price = $('#data2').text();
        const currency_type = $('#data3').text();
        const payment_method = $('#data4').text();
        const transfers = $('#data5').text();
        const transfer_duration = $('#data6').text();
        const agency_id = $('#faaid_data7').find(":selected").text();
        const gtfs = $('#gtfs').text();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url:"/fareattribute/store",
                method:"POST",
                data:{
                    fare_id:fare_id,
                    price:price,
                    currency_type:currency_type,
                    payment_method:payment_method,
                    transfers:transfers,
                    transfer_duration:transfer_duration,
                    agency_id:agency_id,
                    gtfs:gtfs
                },
                success:function(data)
                {
                    messageFlash(data,'success');
                    $('#fare_attributes_data').DataTable().destroy();
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
