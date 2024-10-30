let $j = jQuery.noConflict();
let addon_counter = 1;

window.onload = function () {
    let dt = $j("#tbl_addon").attr('data-attr');

    if(typeof dt != 'undefined' && dt != '') {
        let data = JSON.parse(dt);

        let str = '';
        addon_counter = data.length;
        $j.each(data,function(i,record){
            $j.each(record,function(key,val){
                // console.log(key+'===='+val);
                str += '<tr id="add_row_'+i+'">' +
                    '<td><input type="text" name="addons['+i+'][label]" class="input-text" placeholder="label" style="width: 100%;" value="'+key+'"></td>' +
                    '<td><input type="text" name="addons['+i+'][value]" class="input-text" placeholder="value" style="width: 100%;" value="'+val+'"></td>' +
                    '<td><a href="javascript:void(0);" onclick="javascript:addmorerow(this);" data-action="remove" data-counter="'+i+'">x</a></td>'+
                    '</tr>';
            });
        });

        $j("#tbl_addon tbody").html('');
        $j("#tbl_addon tbody").append(str);
    }
};

function addmorerow(current) {
    let str = '<tr id="add_row_'+addon_counter+'">' +
        '<td><input type="text" name="addons['+addon_counter+'][label]" class="input-text" placeholder="label" style="width: 100%;"></td>' +
        '<td><input type="text" name="addons['+addon_counter+'][value]" class="input-text" placeholder="value" style="width: 100%;"></td>' +
        '<td><a href="javascript:void(0);" onclick="javascript:addmorerow(this);" data-action="remove" data-counter="'+addon_counter+'">x</a></td>'+
        '</tr>';

    var act = $j(current).attr('data-action');

    if(act == 'remove') {
        $j(current).parents('tr').remove();
    }
    else {
        $j("#tbl_addon tbody").append(str);
        addon_counter++;
    }
}